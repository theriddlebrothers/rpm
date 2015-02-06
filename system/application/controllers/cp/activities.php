<?php

class Activities extends Admin_Controller {

	public function Activities() {
		parent::Admin_Controller();
	}
	
	function ajax($action) {
		
	}
	
	function create() 
	{
		if (!user_can('create', 'activities')) access_error();
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'activities')) access_error();
		$activityFactory = new Activity();
		$activity = $activityFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $activity->name . ' was deleted.');
		$activity->delete();
		redirect('activities/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'activities')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$activityFactory = new Activity();
		$activity = new Activity();
		if ($id != null) {
			$activity = $activityFactory->where('id', $id)->get();
			$activity->company->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($activity);
			
			// format date
			if ($activity->activity_date) {
				$activity->activity_date = format_datetimepicker($activity->activity_date);
			}
			
			$associations = array();
			
			// company
			if ($this->input->post('company')) {
				$company = new Company();
				$company->where('name', $this->input->post('company'))->get();
				$associations[] = $company;
				$activity->company = $company;
			} else {
				$activity->delete($activity->company->all);
				$activity->refresh_all();
			}
							
			if ($activity->save($associations)) {
				// save
				$this->session->set_flashdata('success', $activity->subject . ' was saved.');
				redirect('activities/');
			} else {
				// invalid
				$this->data['errors'] = $activity->error->string;
			}		
		} else {
			// defaults
	
			// preset company
			if ($this->input->get('company')) {
				$company = new Company();
				$company->where('id', $this->input->get('company'))->get();
				$activity->company = $company;
			}
		}
		
		$activity_types = array(
			''						=>	'Select a type...',
			'Email'					=>	'Email',
			'Phone Call'			=>	'Phone Call',
			'Meeting'				=>	'Meeting',
			'Other'					=>	'Other',
		);
		
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
		
		// render template
		$this->data['companies'] = $companies->all;
		$this->data['activity_types'] = $activity_types;
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['activity'] = $activity;
		if (!$activity->id) $this->data['title'] = 'Create Activity';
		else $this->data['title'] = 'Edit ' . $activity->name;
		$this->render('activities/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'activities')) access_error();
		
		// setup
		$activityFactory = new Activity();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$activityFactory->start_cache();
		
		if ($this->current_company) {
			$activityFactory->where('id', $this->current_company->id);
		}
		
		$count = $activityFactory->count();
		$activities = $activityFactory->order_by('activity_date', 'desc')->get($limit, $offset)->all;
		$activityFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('activities/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['activities'] = $activities;
		$this->data['title'] = 'Activities';
		$this->render('activities/list');
	}
	
	function view($id) {
		if (!user_can('view', 'activities')) access_error();
		
		$activityFactory = new Activity();
		
		$activity = $activityFactory->where('id', $id)->get();
		
		if (!$activity) redirect('activities/');
		
		$this->authorize($activity->id);
		
		$this->load->library('markup'); 
		$activity->info = $this->markup->translate($activity->info);
		
		
		// render template
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['activity'] = $activity;
		$this->data['title'] = $activity->name;
		$this->render('activities/view');
	}
	
}
