<?php
/**
 * Class to handle all branded emails being sent out.
 */
class Brandedemail extends RB_Email {

        public $attachments;
	public $bcc;
	public $body;
	public $cc;
	public $from_address;
	public $from_name;
	public $link_text;
	public $link_url;
	public $subject;
	public $to;
	
	public function __construct() {
		// Send email
		$setting = new Setting();
		$this->from_name = $setting->where('name', 'email_fromname')->get()->value;
		$this->from_address = $setting->where('name', 'email_from')->get()->value;
		$this->cc = $setting->where('name', 'email_cc')->get()->value;
		$this->bcc = $setting->where('name', 'email_bcc')->get()->value;
                $this->attachments = array();
	}
        
        public function attach($filename) {
            $this->attachments[] = $filename;
        }
	
	public function send() {
		
		$ci =& get_instance();
		$ci->load->library('email');
                $ci->email->clear(true);
		$ci->email->from($this->from_address, $this->from_name);
		
				
		// add global cc
		if ($this->cc) $ci->email->cc($this->cc);
		
		// add global bcc
		if ($this->bcc) $ci->email->bcc($this->bcc);

		// load message		
		$body = $ci->load->view('templates/email', array(
			'email_title' => $this->subject, 
			'email_message' => $this->body, 
			'email_link_url'=> $this->link_url,
			'email_link_text'=>$this->link_text
		), true);
		
		if (is_array($this->to)) {
			$recipients = implode(',', $this->to);
		} else {
			$recipients = $this->to;
		}
                
                foreach($this->attachments as $a) {
                    $ci->email->attach($a);
                }
	
		$ci->email->to($recipients);
		$ci->email->subject($this->subject);
		$ci->email->message($body);
		return $ci->email->send();
		
	}
	
}