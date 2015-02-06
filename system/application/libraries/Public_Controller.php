<?php

class Public_Controller extends RB_Controller {

	protected $data = array();
	protected $directory = '';
	protected $template = 'public';
	
	public function Public_Controller() {
		parent::RB_Controller();
		
		$this->checkKey();

	}
	
	public function checkKey() {
		
		$key = $this->input->get('key');
		if ($key) {
			$this->session->set_userdata('view_key', $key);
			$redirect = $this->input->get('redirect');
			if ($redirect) {
				redirect(rawurldecode($redirect));
			}
		}
	}
}