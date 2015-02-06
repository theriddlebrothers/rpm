<?php

class Cron extends RB_Controller {
	
	/**
	 * Run all cron processes
	 */
	public function run($key=null) {
            if ($key != 'riddlemethis') die("Invalid key.");
            echo "Running cron tasks...";
            $this->send_scheduled_invoices();
            $this->create_next_month_retainer_invoices();
            $this->invoice_reminders();
            echo "all tasks complete.";
	}
        
        /**
         * Create retainer invoices
         */
        public function create_next_month_retainer_invoices() {
            $retainer = new Retainer();
            $current_date = date("Y-m-d H:m:s");
            $sql = "SELECT * FROM retainers 
                WHERE end_date > '" . $current_date . "' 
                AND start_date <= '" . $current_date . "' 
                AND status='Approved' 
                AND DAY(start_date) = DAY(CURRENT_DATE)";
            $retainers = $retainer->query($sql)->all;
            echo count($retainers) . ' found...';
            $invoiceFactory = new Invoice();
            $max = $invoiceFactory->select_max('invoice_number')->get();
            $current_invoice_number = $max->invoice_number;
            foreach($retainers as $retainer) {
            	$current_invoice_number++;
            	$retainer->company->get();
            	$invoice = new Invoice();
            	$invoice->recipients = $retainer->invoice_recipients;
            	$invoice->bill_to = str_replace('<br />', "\n", $retainer->company->getAddress());
        		$invoice->invoice_number = $current_invoice_number;
        		$invoice->invoice_date = date("Y-m-d 00:00:00", strtotime("+30 day"));
        		$invoice->due_date = date("Y-m-d 00:00:00", strtotime("+60 day"));
        		$invoice->send_date = $invoice->invoice_date;
        		$invoice->description = "Invoice for " . $retainer->company->name . " Retainer #" . $retainer->id;
        		$invoice->terms = 'NET 30';
        		$invoice->status = 'Unsent';
        		$line_item = new Lineitem();
        		$line_item->description = 'Retainer #' . $retainer->id . ' billing for ' . date("F", strtotime($invoice->invoice_date)) . ' ' . date("Y", strtotime($invoice->invoice_date));
        		$line_item->amount = $retainer->monthlyTotal();
        		$line_item->save();
        		
        		if (!$invoice->save(array($line_item, $retainer->company, $retainer->project))) {
        			die("Unable to save invoice: " . var_export( $invoice->errors, true));
        		}
            }
        }
        
        /**
         * Send scheduled invoices
         */
        public function send_scheduled_invoices() {
            $invoices = new Invoice();
            $invoices->where('send_date >=', date("Y-m-d 00:00:00"));
            $invoices->where('send_date <=', date("Y-m-d 23:59:59"));
            $invoices->where('status', 'Unsent');
            
            foreach($invoices->get()->all as $invoice) {
                echo "Sending $invoice->id...<br />";
                $invoice->send();
            }
        }
        
        /**
         * Send out invoice reminders
         */
        public function invoice_reminders() {
            $invoices = new Invoice();
            $setting = new Setting();
            $setting->where('name', 'email_reminder_days')->get();
            $reminder_days = $setting->value;
            
            // where the diff between the due date and today is exactly X days away
            $invoices->where('status', 'Unpaid')
                     ->where('DATEDIFF(due_date, NOW()) =', $reminder_days)->get();
            
            $payment_email = $setting->where('name', 'invoice_notificationemail')->get()->value;
            
            foreach($invoices->all as $invoice) {
                
                $attachment = ROOTPATH . '/files/temp/invoice-' . $invoice->invoice_number . '.pdf';
                $invoice->generatePDF('F', $attachment);
                
                // send email reminder
                $email = new Brandedemail();
                $email->from_address = $setting->where('name', 'email_invoicefrom')->get()->value;
                $email->subject = "Friendly Reminder: Your Invoice No. " . $invoice->invoice_number . " is Past Due";
                
                $email->body = "Hi,<br /><br />This is a friendly reminder that your invoice no. " . $invoice->invoice_number . " is overdue. 
                    Please remit payment to The Riddle Brothers at your earliest convenience or 
                    <a href='mailto:" . $payment_email . "'>contact us</a> with any questions. Thank you!";
                        
                $email->to = $invoice->recipients;
                $email->bcc = $payment_email;
                $email->link_text = 'View Invoice';
                $email->link_url = $invoice->company->getViewLink('/client/invoices/number/' . $invoice->invoice_number);
                
                $email->attach($attachment);
                
                if (!$email->send()) {
                        echo 'Unable to send invoice #' . $invoice->invoice_number . ' email.';
                        echo $email->print_debugger();
                } else {
                    unlink($attachment);
                }

            }
        }
	
}