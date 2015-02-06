<?php

class Svn extends Admin_Controller {

	public function Svn() {
		parent::Admin_Controller();
	}
	
	public function browse($repname) {
		$this->data['repname'] = $repname;
		$this->render('svn/index');	
	}
	
}