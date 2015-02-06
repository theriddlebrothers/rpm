<?php
/**
 * Invoice model
 */
class Invoice extends DataMapper {

	var $table = 'invoices';
	var $has_one = array('company', 'project', 'estimate', 'transaction');
	var $has_many = array('lineitem', 'timelog');
	
	var $validation = array(
        array(
            'field' => 'invoice_number',
            'label' => 'invoice number',
            'rules' => array('required', 'trim', 'integer'),
        ),        
        array(
            'field' => 'invoice_date',
            'label' => 'invoice date',
            'rules' => array('required', 'trim', 'valid_date'),
       	),
        array(
            'field' => 'due_date',
            'label' => 'due date',
            'rules' => array('required', 'trim', 'valid_date'),
       	),
        array(
            'field' => 'send_date',
            'label' => 'send date',
            'rules' => array('trim', 'valid_date'),
       	),
        array(
            'field' => 'status',
            'label' => 'status',
            'rules' => array('required', 'trim')
        ),       
        array(
            'field' => 'terms',
            'label' => 'terms',
            'rules' => array('required', 'trim')
        ),        
        array(
            'field' => 'message',
            'label' => 'message',
            'rules' => array('trim')
        ),        
        array(
            'field' => 'recipients',
            'label' => 'recipients',
            'rules' => array('trim', 'check_emails')
        ),
        array(
            'field' => 'company',
            'label' => 'company',
            'rules' => array('required')
        ),
	);

	public function Invoice($data = array()) {
		parent::DataMapper();
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	public function getPlainObject() {
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->invoiceNumber = $this->invoice_number;
		$resp->description = $this->description;
		$resp->invoiceDate = date("m/d/Y", strtotime($this->invoice_date));
		$resp->dueDate = date("m/d/Y", strtotime($this->due_date));
		$resp->sendDate = date("m/d/Y", strtotime($this->send_date));
		$resp->status = $this->status;
		$resp->terms = $this->terms;
		$resp->message = $this->message;
		$resp->recipients = $this->recipients;
		$resp->company = $this->company->getPlainObject();
		$resp->project = $this->project->getPlainObject();
		$resp->amount = $this->getTotal();
		
		$resp->lineItems = array();
		foreach($this->lineitem as $item) {
			$resp->lineItems[] = $item->getPlainObject();
		}
		return $resp;
	}
	
	/**
	 * Validate emails for invoice
	 */
	public function _check_emails($field) {
		$emails = $this->{$field};
		$err = '';
		if ($emails) {
			$arr = explode(",", $emails);
			foreach($arr as $email) {
				$email = trim($email);
				if (!$this->form_validation->valid_email($email)) {
					$err .= "Recipient email " . $email . " is not valid.<br />";
				}
			}
		}
		return $err;
	}
	
	/**
	 * Calculate Total
	 */
	public function getTotal() {
		$total = 0;
		$this->lineitem->get();
		foreach($this->lineitem->all as $item) {
			$total += $item->amount;
		}
		return $total;
	}
	
	/**
	 * Retrieve cost items as array
         * @param   object   $input         Input control from CI
         * $param   boolean  $force_get     Force use of stored values rather than posted values
	 */
	public function getLineItemsAsArray($input, $force_get=false) {
	
		// if no cost items, and not a postback, return default display
		if (!$this->lineitem->all && $input && ($input->server('REQUEST_METHOD') != 'POST')) {
			return array();
		}
		$cost_items = array();
		if ($input && ($input->server('REQUEST_METHOD') == 'POST') && !($force_get)) {
			
			// return posted values
			$descriptions = $input->post('description');
			$amounts = $input->post('amount');
			
			$num_items = sizeof($descriptions);
			$cost_items = array();
			for($i=0; $i<$num_items; $i++) {
				// don't save empty lines
				if (empty($descriptions[$i]) && empty($amounts[$i])) continue;
				
				$item = array();
				$item['description'] = $descriptions[$i];
				$item['amount'] =  $amounts[$i];				
				$cost_items[] = $item;
			}


		} elseif ($this->lineitem->all) {
			// return cost items
			foreach($this->lineitem->all as $cost_item) {
				if (empty($cost_item->description)) continue;
				
				$item = array();
				$item['description'] = $cost_item->description;
				$item['amount'] = $cost_item->amount;
				$cost_items[] = $item;
			}
		}
		return $cost_items;
	}
	
    /*
     * Destination where to send the document. It can take one of the following values:
     *  I: send the file inline to the browser. The plug-in is used if available. 
     *      The name given by name is used when one selects the "Save as" option on the link generating the PDF.
     *  D: send to the browser and force a file download with the name given by name.
     *  F: save to a local file with the name given by name (may include a path).
     *  S: return the document as a string. name is ignored.
     */
    function generatePDF($dest='I', $filename = null) {
        $this->load->library('pdf');
        
        if (!$filename) {
            $filename = 'invoice-' . $this->invoice_number . '.pdf';
        }
        
        // payable terms
        $setting = new Setting();
        $setting->where('name', 'payable_terms');
        $payable_terms = $setting->get()->value;

        // footer text
        $setting->where('name', 'footer_text');
        $footer_text = $setting->get()->value;

        // render template
        $timelogs = null;
        if ($this->timelog) {
                $timelogs = $this->timelog->get()->all;
        }
        
        $line_items = $this->getLineItemsAsArray(null, true);
        
        $pdf = new PDF();
        $font = 'arial';
        $pdf->SetFont($font, '', 24);
        $pdf->SetMargins(10, 10);

        $pdf->AddPage();
        
        $pdf->Image(ROOTPATH . '/images/pdf-header.png', 50, 15, 120, 12, 'PNG', 'http://www.theriddlebrothers.com');
        
        $margin = 10;                   // margin from left
        $margin_right = $margin + 110;  // margin for right column
        $offset = 45;                   // offset from top
        $line_height = 7;
        $pdf->Text($margin, $offset, 'Invoice #' . $this->invoice_number);
        
        // Payment Status
        if ($this->status == 'Paid') {
            $pdf->SetXY($margin_right+50, $offset-6);
            $pdf->setLineWidth(.5);
            $pdf->setDrawColor(198, 216, 128);
            $pdf->setFillColor(230, 239, 194);
            $pdf->Cell(30, 16, 'PAID', 1, 0, 'C', true);
        } elseif($this->status == 'Void') {
            $pdf->SetXY($margin_right+50, $offset-6);
            $pdf->setLineWidth(.5);
            $pdf->setDrawColor(255, 211, 36);
            $pdf->setFillColor(255, 246, 191);
            $pdf->Cell(30, 16, 'VOID', 1, 0, 'C', true);
        }
        
        // Invoice Information
        $pdf->SetFont($font, 'B', 16);
        $pdf->Text($margin, $offset+15, 'Invoice Information');
        
        // Invoice Information Labels
        $pdf->SetFont($font, 'B', 11);
        $ii_offset = 25;
        $pdf->Text($margin, $offset+$ii_offset, 'Invoice Date');
        $pdf->Text($margin, $offset+$ii_offset+($line_height), 'Invoice Number');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*2), 'Payment Terms');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*3), 'Project Name');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*4), 'Project Number');
        
        // Invoice Information Data
        $pdf->SetFont($font, '', 11);
        $pdf->Text($margin+35, $offset+$ii_offset, date("m/d/Y", strtotime($this->invoice_date)));
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height), $this->invoice_number);
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*2), $this->terms);
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*3), truncate($this->project->name, $ii_offset));
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*4), $this->project->fullProjectNumber());
        $pdf->Text($margin, $offset+65, $this->description);
        
        // Bill To
        $pdf->SetFont($font, 'B', 16);
        $pdf->Text($margin_right, $offset+15, 'Bill To');
        $pdf->SetFont($font, '', 11);
        $pdf->Text($margin_right, $offset+25, $this->company->name);
        $pdf->setXY($margin_right-1, $offset+27);
        $pdf->MultiCell(80, 5, str_replace("<br />", "\n", $this->bill_to), 0);
        $pdf->setXY($margin_right-1, $offset+50);
        if (!empty($this->message)) {
            $pdf->SetFont($font, '', 10);
            $pdf->setLineWidth(.5);
            $pdf->setDrawColor(255, 211, 36);
            $pdf->setFillColor(255, 246, 191);
            $pdf->MultiCell(80, 6, $this->message, 1, 'L', true);
        }
        
        // Invoice Detail
        $pdf->SetFont($font, 'B', 16);
        $pdf->Text($margin, $offset+80, 'Invoice Detail');
        
        $pdf->SetFont($font, '', 11);
        $pdf->setDrawColor(49, 60, 79);
        $pdf->setFillColor(49, 60, 79);
        $pdf->setTextColor(255, 255, 255);
        
        // Background of description cell is used as table header
        $pdf->setXY($margin, $offset+85);
        $pdf->Cell(190, 7, 'Description', 0, 1, 'L', true);
        
        $pdf->setXY($margin+173, $offset+85);
        $pdf->Cell(17, 7, 'Amount', 0, 1, 'L', true);
        
        $pdf->setTextColor(0, 0, 0);
        
        // Line Items
        if ($line_items) {
            foreach($line_items as $item) {
                $pdf->Cell(160, 7, truncate($item['description'], 90), 0, 0, 'L', false);
                $pdf->Cell(30, 7, '$'.number_format($item['amount'], 2), 0, 1, 'R', false);
            }
            
            $pdf->SetFont($font, 'B', 11);
            $pdf->Cell(160, 7, 'TOTAL', 0, 0, 'L', false);
            $pdf->Cell(30, 7, '$'.number_format($this->getTotal(), 2), 0, 1, 'R', false);

        } else {
            $pdf->Cell(160, 7, 'No items listed for this invoice.', 0, 1, 'L', false);
        }
        
        // Payable To
        $pdf->SetFont($font, 'B', 11);
        $pdf->Cell(130, 7, '', 0, 1, 'L', false);
        $pdf->Cell(160, 8, $payable_terms, 0, 0, 'L', false);
        
        // Total Due
        $pdf->SetFont($font, 'B', 14);
        $pdf->Cell(30, 8, 'Total Due', 0, 1, 'R', false);
        $pdf->SetFont($font, '', 14);
        $pdf->Cell(190, 8, '$'.number_format($this->getTotal(), 2), 0, 1, 'R', false);
        
        // If this invoice has a breakdown, place on the following page
        if ($timelogs) {
            $pdf->AddPage();
            $page2_offset = 30;
            $pdf->SetFont($font, '', 18);
            $pdf->Cell(190, 24, 'Invoice #' . $this->invoice_number . ' Breakdown', 0, 1, 'L', false);


            $pdf->SetFont($font, '', 10);
            $pdf->setDrawColor(49, 60, 79);
            $pdf->setFillColor(49, 60, 79);
            $pdf->setTextColor(255, 255, 255);

            // Background of description cell is used as table header
            $pdf->setXY($margin, $page2_offset);
            $pdf->Cell(190, 7, 'Log Date', 0, 1, 'L', true);

            $pdf->setXY($margin+24, $page2_offset);
            $pdf->Cell(127, 7, 'Description', 0, 0, 'L', true);
            $pdf->Cell(15, 7, 'Time', 0, 0, 'L', true);
            $pdf->Cell(20, 7, 'Amount', 0, 1, 'R', true);

            $pdf->setXY($margin, $page2_offset+7);
            $pdf->setTextColor(0, 0, 0);
            foreach($timelogs as $timelog) {
                $pdf->Cell(24, 7, date("m/d/Y", strtotime($timelog->log_date)), 0, 0, 'L', false);
                $pdf->Cell(127, 7, truncate($timelog->description, 70), 0, 0, 'L', false);
                $pdf->Cell(15, 7, $timelog->hours, 0, 0, 'L', false);
                $pdf->Cell(23, 7, '$'.number_format($timelog->task->project->billable_rate * doubleval($timelog->convertTimeToHours($timelog->hours)), 2), 0, 1, 'R', false);
            }
        }
        if($dest != 'F') {
	        header('Content-type: application/pdf');
        }
        $pdf->Output($filename, $dest);
    }
	
    
    /**
     * Email an invoice
     * @return string Response/error string
     */
    public function send() {
        $this->company->get();
        $this->project->get();

        $message = $this->recipient_message;

        $recipients = $this->recipients;

        if (!$recipients) return 'You must enter at least one recipient for invoice #' . $this->invoice_number . '.';

        if ($recipients) {
                // CREATE FILE ATTACHMENT
                $attachment = ROOTPATH . '/files/temp/invoice-' . $this->invoice_number . '.pdf';
                $this->generatePDF('F', $attachment);

                // SEND EMAIL
                $setting = new Setting();

                // Subject
                $this_subject = $setting->where('name', 'invoice_subject')->get()->value;
                $this_subject = str_replace('[INVOICE NUMBER]', $this->invoice_number, $this_subject);

                // create message
                $message = '<p>You have a new invoice from the Riddle Brothers.</p>';
                if ($this->recipient_message) {
                        $message .= '<p><strong>Client Message:</strong><br />' . $this->recipient_message . '</p>';
                }
                $message .= '<p>To view this invoice, please click the link below. It is also attached as a PDF for your records.</p>';

                // load message
                $email = new Brandedemail();
                $email->from_address = $setting->where('name', 'email_invoicefrom')->get()->value;
                $email->subject = $this_subject;
                $email->body = $message;
                $email->to = $this->recipients;
                $email->bcc = $setting->where('name', 'invoice_notificationemail')->get()->value;
                $email->link_text = 'View Invoice';
                $email->link_url = $this->company->getViewLink('/client/invoices/number/' . $this->invoice_number);

                if (file_exists($attachment)) {
                    $email->attach($attachment);
                }
                if ($email->send()) {
                        $this->last_sent = date("Y-m-d H:i:s");
                        $this->status = 'Unpaid';
                        $this->save();

                        // Delete attachment file on server
                        unlink($attachment);

                } else {
                        return 'Unable to send invoice #' . $this->invoice_number . ' email.';
                }

                //echo $ci->email->print_debugger();
        }

        if ($this->error->string) return $this->error->string;
    }

}