<?php

class Projects extends Admin_Controller {

	public function Projects() {
		parent::Admin_Controller();
	}
	
	function create() 
	{
		if (!user_can('create', 'projects')) access_error();
		
		$this->edit(null);
	}
	
	function delete($id) {
	
		if (!user_can('delete', 'projects')) access_error();
		
		$projectFactory = new Project();
		$project = $projectFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $project->name . ' was deleted.');
		$project->delete();
		
		redirect('projects/');
	}
	
	function edit($id) {
	
		if (!user_can('edit', 'projects')) access_error();
		
		$associations = array();
		
		// setup
		$this->data['errors'] = null;
		$projectFactory = new Project();
		$project = new Project();
		if ($id != null) {
			$project = $projectFactory->where('id', $id)->get();
			$project->company->get();
			$project->contact->get();
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
			$number = new Project();
			$number->where('start_date >=', date("Y-01-01 00:00:00"))->select_max('project_number')->get();
			$number = $number->project_number + 1;
			$project->project_number = $number;
			$project->start_date = date("m/d/Y");
			$project->billable_rate = 90;
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$project->name = $this->input->post('name');
			$project->project_number = $this->input->post('project_number');
			$project->project_type = $this->input->post('project_type');
			$project->status = $this->input->post('status');
			$project->billable_rate = $this->input->post('billable_rate');
			$project->svn_repo = $this->input->post('svn_repo');
			
			if ($this->input->post('start_date')) {
				$start = strtotime($this->input->post('start_date'));
				$start = date("Y-m-d 00:00:00", $start);
				$project->start_date = $start;
			}
			
			if ($this->input->post('end_date')) {
				$end = strtotime($this->input->post('end_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$project->end_date = $end;
			}
			
			$project->dropbox_folder = $this->input->post('dropbox_folder');
			$project->description = $this->input->post('description');
				
			// company
			// if no company ID then this is a new company being created.
			if ($this->input->post('company')) {
				$company = new Company();
				// see if another company has matching name - if so, use that company
				$company = $company->where('name', $this->input->post('company'))->get();
				if (!$company->id) {
					// nothing matches, create new company
					$company = new Company();
					$company->name = $this->input->post('company');
					if (!$company->save()) {
						$errors[] = $company->error;
					}
				}
				if ($company->id) $associations[] = $company;
				$project->company = $company;
			} else {
				// remove existing association
				$company = new Company();
				$company = $company->where('id', $project->company->id)->get();
				$project->delete($company);
			}
			
			// contacts
			if ($this->input->post('contacts')) {
				$contacts = $this->input->post('contacts');
				foreach($contacts as $contact_id) {
					$contact = new Contact();
					$contact->where('id', $contact_id)->get();
					$associations[] = $contact;
				}
			} else {
				// remove existing associations
				$project->delete($project->contact->all);
				$project->refresh_all();
			}
			
			// parent project
			if ($this->input->post('project')) {
				$parent_project = new Project();
				$parent_project->where('id', $this->input->post('project'))->get();
				$associations['parentproject'] = $parent_project;
			} else {
				// remove existing associations
				$project->delete($project->parentproject->all);
				$project->refresh_all();
			}
			
			// resources (users)
			if ($this->input->post('users')) {
				$users = $this->input->post('users');
				foreach($users as $user_id) {
					$user = new User();
					$user->where('id', $user_id)->get();
					$associations[] = $user;
				}
			} else {
				// remove existing associations
				$project->delete($project->user->all);
				$project->refresh_all();
			}
			
			if ($project->save($associations)) {
				// save
				$this->session->set_flashdata('success', $project->name . ' was saved.');
				redirect('projects/');
			} else {
				// invalid
				$this->data['errors'] = $project->error->string;
			}		
		}
		
		// get project contacts
		$contacts = array();
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$company = $this->input->post('company');
		} else {
			$company = $project->company->name;
		}
		if ($company) {
			$contacts = new Contact();
			$contacts->where_related('company', 'name', $company)->get()->all;
		}
		
		// selected contacts
		$selected_contacts = array();
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->input->post('contacts')) $selected_contacts = $this->input->post('contacts');
		} elseif($project->contact) {
			foreach($project->contact->all as $c) {
				$selected_contacts[] = $c->id;
			}
		}
		
		// get users (resources)
		$users = new User();
		$users->get();
		
		// get project resources
		$selected_users = array();
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->input->post('users')) $selected_users = $this->input->post('users');
		} elseif($project->user) {
			foreach($project->user->all as $u) {
				$selected_users[] = $u->id;
			}
		}
		
		// render template
		$companyFactory = new Company();
		$projectsFactory = new Project();
		$this->data['companies'] = $companyFactory->order_by('name')->get()->all;
		$this->data['projects'] = $projectsFactory->order_by('start_date', 'desc')->order_by('project_number', 'desc')->get()->all;
		$this->data['project'] = $project;
		$this->data['contacts'] = $contacts;
		$this->data['users'] = $users;
		$this->data['selected_contacts'] = $selected_contacts;
		$this->data['selected_users'] = $selected_users;
		if(!$project->id) $this->data['title'] = 'Create Project';
		else $this->data['title'] = 'Edit ' . $project->name;
		$this->render('projects/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'projects')) access_error();
		
		// setup
		$projectFactory = new Project();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$projectFactory->start_cache();
		
		// company
		$company = new Company();
		$company_name = $this->input->get('company');
		if ($company_name) {
			$projectFactory->where_related('company', 'name', $company_name);
			$company->where('name', $company_name)->get();
		}
		
		// companies list
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
		
		// status
		$status = $this->input->get('status');
		if ($status) {
			$projectFactory->where('status', $status);
		}
		
		if ($this->current_company) {
			$projectFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		$count = $projectFactory->count();
		$projects = $projectFactory->order_by('start_date', 'desc')->order_by('project_number', 'desc')->get($limit, $offset)->all;
		$projectFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('projects/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['projects'] = $projects;
		$this->data['company'] = $company;
		$this->data['companies'] = $companies->all;
		$this->data['title'] = 'Projects';
		$this->render('projects/list');
	}
	
	function view($id)
	{
		if (!user_can('view', 'projects')) access_error();
		
		$projectFactory = new Project();
		
		$project = $projectFactory->where('id', $id)->get();
		if (!$project) redirect('projects/');
		
		$this->authorize($project->company->id);
		
		$project->estimate->get();
		
		// get project files
		$files = array();
		
		if ($project->dropbox_folder) {
		
			$this->load->library('dropbox');
			
			$folder = $this->input->get('folder');
			$root_path = $this->config->item('root_path', 'dropbox');
			$path = $root_path . $project->dropbox_folder . $folder;
		
			if ($this->input->get('dl')) {
				// download file
				$dl_path = $root_path . $project->dropbox_folder . $this->input->get('dl');
				$filename = basename($dl_path);
								
				// We'll be outputting a PDF
				header('Content-type: application');
				
				// It will be called downloaded.pdf
				header('Content-Disposition: attachment; filename="' . $filename . '"');
				
				echo $this->dropbox->getFile($dl_path);
			}
			
			$response = $this->dropbox->getMetaData($path);
			foreach($response['contents'] as $r) {
				$file = array(
					'thumb_exists'		=>	$r['thumb_exists'],
					'size'				=>	$r['size'],
					'modified'			=>	$r['modified'],
					'path'				=>	$r['path'],
					'name'				=>	basename($r['path']),
					'is_dir'			=>	$r['is_dir'],
					'icon'				=>	$r['icon']
				);
				
				if ($r['is_dir']) {
					$file['url'] = '?folder=' . $folder . '/' . $file['name'];
				} else {
					$file['url'] = '?dl=' . $folder . '/' . $file['name'];
				}
				
				$files[] = $file;
			}
		}
		
		// tasks
		$tasks = $project->task->where('status', 'In Progress')->or_where('status', 'Pending')->get(10)->all;
		
		// status reports
		$reports = $project->statusreport->order_by('report_date', 'desc')->get(10)->all;
		
		// issues
		$issues = $project->issues->order_by('priority', 'asc')->order_by('date_reported', 'desc')->get(10)->all;
		
		// get timelogs
		$timelogFactory = new Timelog();
		$timelogs = $timelogFactory->where_related('task/project', 'id', $project->id)->order_by('log_date', 'desc')->get(10)->all;
		
		// documents
		$documents = $project->doc->order_by('title', 'asc')->get(10)->all;
		
		// render
		$invoice_totals = 0;
		foreach($project->invoice as $invoice) {
			$invoice_totals += $invoice->getTotal();
		}
		
		$this->load->library('markup'); 
		$project->description = $this->markup->translate($project->description);
		$project->description = trim($project->description);
		
		$this->load->library('markup'); 
		$estimate->content = $this->markup->translate($project->description);
		
		$this->data['invoice_totals'] = $invoice_totals;
		$this->data['timelogs'] = $timelogs;
		$this->data['project'] = $project;
		$this->data['issues'] = $issues;
		$this->data['status_reports'] = $reports;
		$this->data['tasks'] = $tasks;
		$this->data['documents'] = $documents;
		$this->data['files'] = $files;
		$this->data['title'] = $project->name;
		$this->render('projects/view');
	}
	
}
