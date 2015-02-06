<?php

class Timelogs extends Admin_Controller {

	function create() 
	{
		if (!user_can('create', 'timelogs')) access_error();
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'timelogs')) access_error();
		$timelogFactory = new Timelog();
		$timelog = $timelogFactory->where('id', $id)->get();
		
		if ($timelog->user->id != $this->user->id) {
			access_error();
		}
		
		$this->session->set_flashdata('success', 'Timelog was deleted.');
		$timelog->delete();
		
		redirect('timelogs/', 'refresh');
	}
	
	function edit($id) {
		if (!user_can('edit', 'timelogs')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$timelogFactory = new Timelog();
		$timelog = new Timelog();	
		$task_text = '';
		$task_id = null;
			
		if ($id != null) {
			$timelog = $timelogFactory->where('id', $id)->get();
			$timelog->task->get();
			$timelog->user->get();
			
			if ($timelog->user->id != $this->user->id) {
				access_error();
			}

			$task_text = $timelog->task->project->name . ': ' . $timelog->task->name;
			$task_id = $timelog->task->id;
		} 
				
		if ($timelog->saveTimelog()) {
			// save
			$this->session->set_flashdata('success', 'Timelog for ' . date("m/d/Y", strtotime($timelog->log_date)) . ' was saved.');
			redirect('timelogs/', 'refresh');
		} else {
			// invalid
			$this->data['errors'] = $timelog->error->string;
		}	
		
		// render template
		$taskFactory = new Task();
		$userFactory = new User();
		$this->data['task_text'] = $task_text;
		$this->data['task_id'] = $task_id;
		$this->data['tasks'] = $taskFactory->order_by('name')->get()->all;
		$this->data['users'] = $userFactory->order_by('name')->get()->all;
		$this->data['current_user'] = $this->user;
		$this->data['timelog'] = $timelog;
		if (!$timelog->id) $this->data['title'] = 'Create Time Log';
		else $this->data['title'] = 'Edit ' . $timelog->name;
		$this->render('timelogs/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'timelogs')) access_error();
		
		// setup
		$timelogFactory = new Timelog();
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		$paging_url = 'timelogs/index/?limit=' . $limit;
		
		$timelogFactory->start_cache();
		$timelogFactory->order_by('log_date', 'desc');
		
		// project
		$project_id = $this->input->get('project');
		if ($project_id) {
			$timelogFactory->where_related('task/project', 'id', $project_id);
		}
		
		// company
		$company_id = $this->input->get('company');
		if ($company_id) {
			$timelogFactory->where_related('task/project/company', 'id', $company_id);
		}
		
		// task
		$task_id = $this->input->get('task');
		if ($task_id) {
			$timelogFactory->where_related('task', 'id', $task_id);
		}
		
		if ($this->current_company) {
			$timelogFactory->where_related('task/project/company', 'id', $this->current_company->id);
		}
		
		// Employee
		if ($this->user->role == ROLE_EMPLOYEE) {
			$timelogFactory->where_related('user', 'id', $this->user->id);
		}
		
		$count = $timelogFactory->count();
		$timelogFactory->get($limit, $offset);
		$timelogFactory->stop_cache();
		
		$timelogs = $timelogFactory->all;
		
		// pagination
		if ($project_id) {
		
		}
		$config['base_url'] = site_url($paging_url);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['timelogs'] = $timelogs;
		$this->data['title'] = 'Time Logs';
		$this->render('timelogs/list');
	}
	
	
	function view($id) {
		if (!user_can('view', 'timelogs')) access_error();
		
		$timelog = new Timelog();
		
		$timelog->where('id', $id)->get();
		
		if (!$timelog->id) redirect('timelogs/');
		
		if ($timelog->user->id != $this->user->id) {
			access_error();
		}
		
		$timelog->task->get();
		$timelog->task->project->get();
		$timelog->user->get();
		$this->authorize($timelog->task->project->company->id);
		
		// render template
		$this->data['timelog'] = $timelog;
		$this->data['title'] = "Time Log for " . date("m/d/Y", strtotime($timelog->log_date));
		$this->render('timelogs/view');
	}
	
	
}
