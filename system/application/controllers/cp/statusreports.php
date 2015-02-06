<?php

class StatusReports extends Admin_Controller {

	public function StatusReports() {
		parent::Admin_Controller();
	}
	
	function create() 
	{
		if (!user_can('create', 'statusreports')) access_error();
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'statusreports')) access_error();
		$status_report = new StatusReport();
		$status_report->where('id', $id)->get();
		$this->session->set_flashdata('success', 'Status report dated ' . date("m/d/Y",  strtotime($status_report->report_date)) . ' was deleted.');
		$status_report->delete();
		redirect('statusreports/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'statusreports')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$status_report = new StatusReport();
		if ($id != null) {
			$status_report = $status_report->where('id', $id)->get();
			$status_report->project->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$status_report->report_date = date("Y-m-d 00:00:00", strtotime($this->input->post('report_date')));
			$status_report->content = $this->input->post('content');
			
			$project = new Project();
			if ($this->input->post('project')) {
				$project->where('id', $this->input->post('project'))->get();
				$status_report->project = $project;
			}
						
			if ($status_report->save($project)) {
				// save
				$this->session->set_flashdata('success', 'Status report dated ' . date("m/d/Y", strtotime($status_report->report_date)) . ' was saved.');
				redirect('statusreports/');
			} else {
				// invalid
				$this->data['errors'] = $status_report->error->string;
			}		
		}
		
		// render template
		$projectFactory = new Project();
		$this->config->load('states', true);
		$this->data['report'] = $status_report;
		$this->data['projects'] = $projectFactory->order_by('project_number', 'desc')->get()->all;
		if (!$status_report->id) $this->data['title'] = 'Create Status Report';
		else $this->data['title'] = 'Edit Report';
		$this->render('statusreports/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'statusreports')) access_error();
		
		// setup
		$status_report = new StatusReport();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$status_report->start_cache();
		
		$count = $status_report->count();
		
		if ($this->input->get('project')) {
			$status_report->where_related('project', 'id', $this->input->get('project'));
		}
		
		if ($this->current_company) {
			$status_report->where_related('project/company', 'id', $this->current_company->id);
		}
		
		$companies = $status_report->order_by('report_date', 'desc')->get($limit, $offset)->all;
		$status_report->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('companies/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['reports'] = $status_report;
		$this->data['title'] = 'Status Reports';
		$this->render('statusreports/list');
	}
	
	function view($id) {
		if (!user_can('view', 'statusreports')) access_error();
		
		$status_report = new StatusReport();
		
		$status_report->where('id', $id)->get();
		
		if (!$status_report->id) redirect('statusreports/');
		
		$this->authorize($status_report->project->company->id);
		
		// markdown
		$this->load->library('markup'); 
		$status_report->content = $this->markup->translate($status_report->content);
		
		// render template
		$this->config->load('states', true);
		$this->data['report'] = $status_report;
		$this->data['title'] = date("m/d/Y", strtotime($status_report->report_date)) . ' Status Report';
		$this->render('statusreports/view');
	}
	
}
