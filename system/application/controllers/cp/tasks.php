<?php

class Tasks extends Admin_Controller {

	function ajax($action) {
	
		switch($action) {
			case 'search':
				$q = $this->input->get('q');
				$tasks = new Task();
				$results = $tasks->where('status', 'In Progress')
									->like_related('project', 'name', $q)->or_like('name', $q)->get()->all;
				foreach($results as $task) {
					echo $task->project->name . ': ' . $task->name . '|' . $task->id . "\n";
				}
				break;
		}
	}

	function create() 
	{
		if (!user_can('create', 'tasks')) access_error();
		
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'tasks')) access_error();
		
		$taskFactory = new Task();
		$task = $taskFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $task->name . ' was deleted.');
		$task->delete();
		
		redirect('tasks/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'tasks')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$taskFactory = new Task();
		$task = new Task();	
		$associations = array();
			
		if ($id != null) {
			$task = $taskFactory->where('id', $id)->get();
			$task->project->get();
			$task->user->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$task->name = $this->input->post('name');
			$task->status = $this->input->post('status');
			$task->description = $this->input->post('description');
			
			if ($this->input->post('created_date')) {
				$start = strtotime($this->input->post('created_date'));
				$start = date("Y-m-d 00:00:00", $start);
				$task->created_date = $start;
			}
			
			if ($this->input->post('due_date')) {
				$end = strtotime($this->input->post('due_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$task->due_date = $end;
			}
			
			
			// project association
			$project = null;
			if ($this->input->post('project')) {
				$project = new Project();
				$project = $project->where('id', $this->input->post('project'))->get();
				$associations[] = $project;
				$task->project = $project;
			} else {
				// remove association if it existed
				if ($task->project) {
					$task->delete($task->project);
				}
			}
			
			// user association
			$user = null;
			if ($this->input->post('user')) {
				$user = new User();
				$user = $user->where('id', $this->input->post('user'))->get();
				$associations[] = $user;
				$task->user = $user;
			} else {
				// remove association if it existed
				if ($task->user) {
					$task->delete($task->user);
				}
			}
			
			if ($task->save($associations)) {
				// save
				$this->session->set_flashdata('success', $task->name . ' was saved.');
				redirect('tasks/');
			} else {
				// invalid
				$this->data['errors'] = $task->error->string;
			}		
		}
		
		// render template
		$projectFactory = new Project();
		$userFactory = new User();
		$this->data['projects'] = $projectFactory->order_by('name')->get()->all;
		$this->data['users'] = $userFactory->order_by('name')->get()->all;
		$this->data['task'] = $task;
		if (!$task->id) $this->data['title'] = 'Create Task';
		else $this->data['title'] = 'Edit ' . $task->name;
		$this->render('tasks/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'tasks')) access_error();
		
		// setup
		$taskFactory = new Task();
		$taskFactory->start_cache();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		$paging_url = 'cp/tasks/index/?limit=' . $limit;
		
		$taskFactory->order_by('created_date', 'desc');
				
		// project
		$project_id = $this->input->get('project');
		if ($project_id) {
			$taskFactory->where_related('project', 'id', $project_id);
		}
		
		// company
		$company_id = $this->input->get('company');
		if ($company_id) {
			$taskFactory->where_related('company', 'id', $company_id);
		}
		
		if ($this->current_company) {
			$taskFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		$count = $taskFactory->count();
		$taskFactory->get($limit, $offset);
		
		$taskFactory->stop_cache();
		
		$tasks = $taskFactory->all;
		
		// pagination
		$config['base_url'] = site_url($paging_url);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['tasks'] = $tasks;
		$this->data['title'] = 'Tasks';
		$this->render('tasks/list');
	}
	
	
	
	function view($id) {
		if (!user_can('view', 'tasks')) access_error();
		
		$taskFactory = new Task();
		
		$task = $taskFactory->where('id', $id)->get();
		
		if (!$task->id) redirect('invoices/');
		$task->project->get();
		$task->project->company->get();
		$this->authorize($task->project->company->id);
		
		$task->project->get();
		
		// render template
		$this->data['task'] = $task;
		$this->data['timelogs'] = $task->timelog->get(10)->all;
		$this->data['title'] = $task->name;
		$this->render('tasks/view');
	}
	
	
}
