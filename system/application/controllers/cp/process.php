<?php 
class Process extends Admin_Controller {

	function Process()
	{
		parent::Admin_Controller();	
		$this->template = 'process';
	}
	
	function index()
	{
	
		$this->render('process/view');
	}
	
	function preproduction()
	{
		$this->render('process/preproduction');
	}
	
	
	function production()
	{
		$this->render('process/production');
	}
	
	
	function maintenance()
	{
		$this->render('process/maintenance');
	}
	
	
	function analysis()
	{
		$this->render('process/analysis');
	}

}
	
	
	
/* End of file process.php */
/* Location: ./system/application/controllers/process.php */