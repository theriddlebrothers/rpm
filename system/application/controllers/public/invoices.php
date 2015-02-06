<?php

class Invoices extends RB_Controller {
	
	/**
	 * Process a payment - currently through PayPal only.
	 */
	public function process() {
	
		// log request
		$transaction = new Transaction();
		$dump = '';
		foreach($_GET as $key=>$value) {
			$dump .= '[GET] ' . $key . ' = ' . $value . "\n";
		}
		foreach($_POST as $key=>$value) {
			$dump .= '[POST] ' . $key . ' = ' . $value . "\n";
		}
		$transaction->dump = $dump;
		$transaction->save();
		
		// paypal url
		$setting = new Setting();
		$setting->where('name', 'paypal_testmode');
		$test_mode = $setting->get()->value;
		if ($test_mode) {
			$paypal_url = 'ssl://www.sandbox.paypal.com';
		} else {
			$paypal_url = 'ssl://www.paypal.com';
		}
	
		// Read the post from PayPal and add 'cmd' 
		$req = 'cmd=_notify-validate'; 
		if(function_exists('get_magic_quotes_gpc')) {  
			$get_magic_quotes_exits = true; 
		} 
		
		// Handle escape characters, which depends on setting of magic quotes 
		foreach ($_POST as $key => $value) 
		{  
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){  
				$value = urlencode(stripslashes($value)); 
			} else { 
				$value = urlencode($value); 
			} 
			$req .= "&$key=$value"; 
		} 

		// Post back to PayPal to validate 
		$header = '';
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
		$fp = fsockopen ($paypal_url, 443, $errno, $errstr, 30); 
		 
				 
		// Process validation from PayPal 
		// TODO: This sample does not test the HTTP response code. All 
		// HTTP response codes must be handles or you should use an HTTP 
		// library, such as cUrl 
		if ($errno) {
			$transaction->verification_error = $errno . ': ' . $errstr;
			$transaction->save();
		}
		 
		if (!$fp) { 
			// HTTP ERROR 
		} else { 
			// NO HTTP ERROR 
			fputs ($fp, $header . $req); 
			while (!feof($fp)) { 
				$res = fgets ($fp, 1024); 
				
				$transaction->verification_response = $res;
				$transaction->save();
				
				if (strcmp ($res, "VERIFIED") == 0) { 
				
					$transaction->payer_email = $_POST['payer_email'];
					$transaction->payer_status = $_POST['payer_status'];
					$transaction->payment_status = $_POST['payment_status'];
					$transaction->total_paid = $_POST['mc_gross'];
					$transaction->txn_id = $_POST['txn_id'];
					
					$invoice_id = $_POST['item_number'];
					
					// update invoice status
					$invoice = new Invoice();
					$invoice->where('invoice_number', $invoice_id)->get();
					if ($invoice->id) {
						$invoice->status = 'Paid';
						$invoice->save();
					
						$transaction->save($invoice);
					}
							
					// send receipt to customer
					$payer_email = $_POST['payer_email'];
					$payer_email = 'josh@theriddlebrothers.com';
					//mail($payer_email, 'Riddle Brothers Invoice #' . $invoice->invoice_number . ' Payment', 'Thank you!');
					
					// TODO: 
					// Check the payment_status is Completed 
					// Check that txn_id has not been previously processed 
					// Check that receiver_email is your Primary PayPal email 
					// Check that payment_amount/payment_currency are correct 
					// Process payment 
					// If 'VERIFIED', send an email of IPN variables and values to the 
					// specified email address 
					
					$emailtext = '<p>This invoice has been marked as <strong>PAID</strong>. The following information was processed from PayPal:</p><table>';
					foreach ($_POST as $key => $value){ 
						$emailtext .= '<tr><th>' . $key . '</th><td>' . $value . '</td></tr>';
					}
					$emailtext .= '</table>';
					
					// Send email
					$setting = new Setting();
					$email_from_name = $setting->where('name', 'email_fromname')->get()->value;
					$email_from = $setting->where('name', 'email_from')->get()->value;
					$email_cc = $setting->where('name', 'email_cc')->get()->value;
					$email_bcc = $setting->where('name', 'email_bcc')->get()->value;
					$invoice_notification_email = $setting->where('name', 'invoice_notificationemail')->get()->value;
					$invoice_subject = $setting->where('name', 'payment_subject')->get()->value;
					
					$invoice_subject = str_replace('[INVOICE NUMBER]', $invoice->invoice_number, $invoice_subject);
				
					$ci =& get_instance();
					
					$ci->load->library('email');
					$ci->email->from($email_from, $email_from_name);
					$ci->email->to($invoice_notification_email);
					
					
					// add global cc
					$cc = array();
					if ($email_cc) $cc[] = $email_cc;
					if ($cc) $ci->email->cc($cc);
					
					// add global bcc and invoices bcc invoice_notificationemail
					$bcc = array();
					if ($email_bcc) $bcc[] = $email_bcc;
					if ($bcc) $ci->email->bcc($bcc);
					
					$ci->email->subject($invoice_subject);
					
					$ci->email->message($emailtext);
					
					$ci->email->send();
					
				} elseif (strcmp ($res, "INVALID") == 0) { 
				
					// If 'INVALID', send an email.
					$emailtext = '<p>An invalid email request was sent from PayPal. This may have been an error or a malicious request.</p><table>';
					foreach ($_POST as $key => $value){ 
						$emailtext .= '<tr><th>' . $key . '</th><td>' . $value . '</td></tr>';
					} 
					$emailtext .= '</table>';
					
					// Send email
					$setting = new Setting();
					$email_from_name = $setting->where('name', 'email_fromname')->get()->value;
					$email_from = $setting->where('name', 'email_from')->get()->value;
					$email_cc = $setting->where('name', 'email_cc')->get()->value;
					$email_bcc = $setting->where('name', 'email_bcc')->get()->value;
					$invoice_notification_email = $setting->where('name', 'invoice_notificationemail')->get()->value;
					
					$ci =& get_instance();
					
					$ci->load->library('email');
					$ci->email->from($email_from, $email_from_name);
					$ci->email->to($invoice_notification_email);
					
					
					// add global cc
					$cc = array();
					if ($email_cc) $cc[] = $email_cc;
					if ($cc) $ci->email->cc($cc);
					
					// add global bcc and invoices bcc invoice_notificationemail
					$bcc = array();
					if ($email_bcc) $bcc[] = $email_bcc;
					if ($bcc) $ci->email->bcc($bcc);
					
					$ci->email->subject("Invalid payment received");
					
					$ci->email->message($emailtext);
					
					$ci->email->send();
				}  
			} 
		}
		fclose ($fp); 
	}
	
}