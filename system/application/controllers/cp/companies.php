<?php

class Companies extends Admin_Controller {

	public function Companies() {
		parent::Admin_Controller();
	}
	
	function ajax($action) {
		switch($action) {
			case 'project':
				$project = new Project();
				$project->where('id', $this->input->get('project'))->get();
				if ($project->id) {
					$project->company->get();
					$company = new stdClass();
					$company->name = $project->company->name;
					echo json_encode($company);
				}
				break;
			case 'search':
				$q = $this->input->get('q');
				$companies = new Company();
				$results = $companies->like('name', $q)->get()->all;
				foreach($results as $company) {
					echo $company->name . '|' . $company->id . "\n";
				}
				break;
		}
	}
	
	function create() 
	{
		if (!user_can('create', 'companies')) access_error();
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'companies')) access_error();
		$companyFactory = new Company();
		$company = $companyFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $company->name . ' was deleted.');
		$company->delete();
		redirect('companies/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'companies')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$companyFactory = new Company();
		$company = new Company();
		if ($id != null) {
			$company = $companyFactory->where('id', $id)->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$company->name = $this->input->post('name');
			$company->phone = $this->input->post('phone');
			$company->fax = $this->input->post('fax');
			$company->address = $this->input->post('address');
			$company->city = $this->input->post('city');
			$company->state = $this->input->post('state');
			$company->zip = $this->input->post('zip');
			$company->website = $this->input->post('website');
			$company->email = $this->input->post('email');
			$company->status = $this->input->post('status');
			$company->info = $this->input->post('info');
			
			if ($this->input->post('reset_viewkey')) {
				// model will auto-generate a new key	
				$company->view_key = null;
			}
				
			if ($company->save()) {
				// save
				$this->session->set_flashdata('success', $company->name . ' was saved.');
				redirect('companies/');
			} else {
				// invalid
				$this->data['errors'] = $company->error->string;
			}		
		}
		
		$statuses = array(
			''						=>	'Select a status...',
			'New Business'			=>	'New Business',
			'Client'				=>	'Client',
			'Dead Lead'				=>	'Dead Lead',
			'Do Not Call'			=>	'Do Not Call',
		);
		
		// render template
		$this->data['statuses'] = $statuses;
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['company'] = $company;
		if (!$company->id) $this->data['title'] = 'Create Company';
		else $this->data['title'] = 'Edit ' . $company->name;
		$this->render('companies/edit');
	}
	
	function search($sort, $order, $offset, $limit) {
		$search = array();
		
		if ($this->current_company) {
			$search['id'] = $this->current_company->id;
		}
		
		// setup
		$companyFactory = new Company();
		$companies = $companyFactory->search($search, $sort, $order, $offset, $limit);
		
		// pagination
		$config['base_url'] = site_url('companies/index/?limit=' . $limit);
		$config['total_rows'] = $companyFactory->count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 
		
		return $companies;
	}
	
	function index()
	{
		if (!user_can('view', 'companies')) access_error();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		$sort = $this->input->get('sort');
		$direction = $this->input->get('sort_direction');
		
		$companies = $this->search($sort, $direction, $offset, $limit);

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['companies'] = $companies;
		$this->data['title'] = 'Companies';
		$this->render('companies/list');
	}
	
	function view($id) {
		if (!user_can('view', 'companies')) access_error();
		
		$companyFactory = new Company();
		
		$company = $companyFactory->where('id', $id)->get();
		
		if (!$company) redirect('companies/');
		
		$this->authorize($company->id);
		
		$this->load->library('markup'); 
		$company->info = $this->markup->translate($company->info);
		
		
		// render template
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['company'] = $company;
		$this->data['title'] = $company->name;
		$this->render('companies/view');
	}
	
}
