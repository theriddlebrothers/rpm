<?php

class Emaillogs extends Admin_Controller {

	public function Emaillogs() {
		parent::Admin_Controller();
	}
	
	function index()
	{
		if (!user_can('view', 'emaillogs')) access_error();
		
		// setup
		$emaillogFactory = new Emaillog();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$emaillogs = $emaillogFactory->order_by('send_date', 'desc')->limit($limit)->get($limit, $offset)->all;
		
		// pagination
		$config['base_url'] = site_url('emaillogs/index/?limit=' . $limit);
		$config['total_rows'] = $emaillogFactory->count();
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['emaillogs'] = $emaillogs;
		$this->data['title'] = 'Email Logs';
		$this->render('emaillogs/list');	
	}
	
	
	function view($id) {
		if (!user_can('view', 'emaillogs')) access_error();
		
		$emaillogFactory = new Emaillog();
		
		$emaillog = $emaillogFactory->where('id', $id)->get();
		
		if (!$emaillog) redirect('emaillogs/');
		
		// render template
		$this->config->load('states', true);
		$this->data['emaillog'] = $emaillog;
		$this->data['title'] = 'Email Log ID ' . $emaillog->id;
		$this->render('emaillogs/view');
	}
	
}
