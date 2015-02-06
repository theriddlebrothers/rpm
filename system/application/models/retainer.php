<?php
/**
 * Retainer model
 */
class Retainer extends DataMapper {

    var $table = 'retainers';
    var $has_one = array(
        'company'
    );
    
    var $has_many = array(
        'project'
    );

    var $validation = array(

        array(
            'field' => 'billable_rate',
            'label' => 'billable rate',
            'rules' => array('required', 'numeric')
        ),

        array(
            'field' => 'company',
            'label' => 'company',
            'rules' => array('required'),
        ),
        array(
            'field' =>  'start_date',
            'label' =>  'start date',
            'rules' => array('required', 'trim', 'valid_date', 'valid_start')
        ),
        array(
            'field' =>  'end_date',
            'label' =>  'end date',
            'rules' => array('required', 'trim', 'valid_date')
        ),
        array(
            'field' => 'invoice_recipients',
            'label' => 'invoice recipients',
            'rules' => array('trim', 'check_emails')
        ),
        array(
            'field' => 'status',
            'label' => 'status',
            'rules' => array('required', 'trim'),
        )

    );

    public function Retainer($data = array()) {
            parent::DataMapper();
    	$this->load->helper('data');
    	import_data($this, $data);
    }
    
    public function getPlainObject() {
    	$resp = new stdClass();
    	$resp->hours = $this->hours;
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
    
    public function _valid_start() {

        $dt = strtotime($this->start_date);
        if (date("d", $dt) > 28) {
            return "Start date must be prior to the 29th of the month.";
        }
        return true;
    }
    
    public function monthlyTotal() {
        return $this->billable_rate * $this->hours;
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
            $filename = ereg_replace("[^A-Za-z0-9]", "", $this->company->name) . '-retainer.pdf';
        }

        // footer text
        $setting = new Setting();
        $setting->where('name', 'footer_text');
        $footer_text = $setting->get()->value;

        // render template
        $pdf = new PDF();
        $font = 'arial';
        $pdf->SetFont($font, '', 24);
        $pdf->SetMargins(10, 10);

        $pdf->AddPage();
        
        $pdf->Image(ROOTPATH . '/images/pdf-header.png', 50, 15, 120, 12, 'PNG', 'http://www.theriddlebrothers.com');
        
        $margin = 10;                   // margin from left
        $margin_right = $margin + 110;  // margin for right column
        $offset = 45;                   // offset from top
        $line_height = 6;
        $pdf->Text($margin, $offset, $this->company->name . ' Retainer');
        
        // Invoice Information Labels
        $pdf->SetFont($font, 'B', 11);
        $ii_offset = 9;
        $pdf->Text($margin, $offset+$ii_offset, 'Hours');
        $pdf->Text($margin, $offset+$ii_offset+($line_height), 'Hourly Rate');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*2), 'Per Month');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*3), 'Status');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*4), 'Start Date');
        $pdf->Text($margin, $offset+$ii_offset+($line_height*5), 'End Date');
                
        // Data
        $pdf->SetFont($font, '', 11);
        $pdf->Text($margin+35, $offset+$ii_offset, $this->hours);
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height), '$' . number_format($this->billable_rate));
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*2), '$' . number_format($this->monthlyTotal()));
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*3), $this->status);
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*4), date('m/d/Y', strtotime($this->start_date)));
        $pdf->Text($margin+35, $offset+$ii_offset+($line_height*5), date('m/d/Y', strtotime($this->end_date)));
        
        // Terms
        $pdf->SetXY($margin, $offset+40);
        $ci =& get_instance();
        $ci->load->library('markup'); 
        $terms = $ci->markup->translate($this->terms);
        $pdf->WriteHTML($terms);
        
        // Signature line
        if ($this->status == 'Approved') {
        	$pdf->WriteHTML('<br /><p>Agreed to by: <strong>' . $this->signature . '</strong></p>');
        	$pdf->WriteHTML('<p>Signed electronically on ' . date("m/d/Y @ H:i:s", strtotime($this->signature_date)) . ' from IP address ' . $this->signature_ip . '.</p>');
        } elseif($this->status == 'Pending Approval') {
    		$pdf->WriteHTML('<br /><br /><p>Agreed to By: ____________________________</p>');
    		$pdf->WriteHTML('<p>Printed Name: ___________________________</p>');
    		$pdf->WriteHTML('<p>Date: __________________________________</p>');
        }
        if($dest != 'F') {
            header('Content-type: application/pdf');
        }
        $pdf->Output($filename, $dest);
    }
        
    function send() {
        	
    	// CREATE FILE ATTACHMENT
    	$attachment = ROOTPATH . '/files/temp/' . ereg_replace("[^A-Za-z0-9]", "", $this->company->name) . '-retainer.pdf';
    	$this->generatePDF('F', $attachment);
    	
    	// SEND EMAIL
        $setting = new Setting();
    	$email_from_name = $setting->where('name', 'email_fromname')->get()->value;
    	$email_from = $setting->where('name', 'email_from')->get()->value;
    	$email_cc = $setting->where('name', 'email_cc')->get()->value;
    	$email_bcc = $setting->where('name', 'email_bcc')->get()->value;
    	$estimate_notification_email = $setting->where('name', 'estimate_notificationemail')->get()->value;

        // Subject
        $this_subject = $setting->where('name', 'retainer_subject')->get()->value;
        $this_subject = str_replace('[COMPANY NAME]', $this->company->name, $this_subject);

        // create message
        $message = '<p>To view this retainer, please click the link below. It is also attached as a PDF for your records.</p>';

        // load message
        $email = new Brandedemail();
        $email->from_address = $setting->where('name', 'email_invoicefrom')->get()->value;
        $email->subject = $this_subject;
        $email->body = $message;
        $email->to = $this->recipients;
        $email->bcc = $setting->where('name', 'estimate_notificationemail')->get()->value;
        $email->link_text = 'View Retainer';
        $email->link_url = $this->company->getViewLink('/client/retainers/view/' . $this->id);
    
    	if (file_exists($attachment)) {
    	    $email->attach($attachment);
    	}
    	
    	if ($email->send()) {
            // Delete attachment file on server
            unlink($attachment);
    	} else {
    		return 'Unable to send estimate copy to ' . $email->to;
    	}
    	
    	//echo $ci->email->print_debugger();
    	
    }

}