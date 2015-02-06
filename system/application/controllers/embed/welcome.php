<?php

class Welcome extends Embed_Controller {

	public function Welcome() {
		parent::Embed_Controller();
	}
	
	function index()
	{
		$this->render('message');
	}
	
}
