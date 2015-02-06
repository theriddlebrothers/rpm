<?php

class Reports extends Admin_Controller {

	public function Reports() {
		parent::Admin_Controller();
	}

	public function index() {
		$this->render('reports/index');
	}
	
	public function timelogs_monthly() {
		
		$setting = new Setting();
		
		$billdate_setting = $setting->where('name', 'retainer_billdate')->get()->value;
		// current month's bill date used solely for date calcuations
		$billdate = mktime(0, 0, 0, date("m"), $billdate_setting, date("Y"));
		
		// ex: billing date = 25th
		$now = strtotime("now");
		$current_day = date("d", $now);
		
		$start_date = null;
		$end_date = null;
		
		$when = $this->input->get('when');
		if ($when == 'last_month') {
			// get last month's report
			$billdate = strtotime("-1 month", $billdate);
		} 
	
		if ($current_day > date("d", $billdate)) {
			// if today is Dec 27th, we want the range from this month (dec 25th) through next month (24th)
			// thus, this month's past bill date through next month's bill date
			$start_date = date("m/d/Y", strtotime("+1 day", $billdate));
			$end_date = date("m/d/Y", strtotime("+1 month", $billdate));
		} else {
			// otherwise if today is January 2nd, we want the range from last month (25th) through this month (24th)
			// thus, last month's bill date through this months bill date
			$start_date = date("m/d/Y", strtotime("-1 month", strtotime("+1 day", $billdate)));
			$end_date = date("m/d/Y", $billdate);
		}
		
		
		// generate a monthly timelog URL based on current information
		$url = 'reports/timelogs_projects/';
		$params = array(
			'start_date'	=> $start_date,
			'end_date'		=> $end_date
		);
		redirect($url . '?search=1&' . http_build_query($params));
	}
	
	public function timelogs_projects() {
		$projects = new Project();
		$projects->get();
		
		$errors = array();
		
		// get logs per-project
		$timelogs = array();
		foreach($projects->all as $project) {
			$logs = new Timelog();
			$this->filter_timelogs($logs);
			//$logs->where_related('task/project', 'project_type', 'Hourly Maintenance');
			$logs->where_related('task/project', 'id', $project->id);
			$logs->get();
			$logs->get_copy();
			$timelogs[$project->id] = $logs;
		}
		
		// get total hours per project
		$hours = array();
		foreach($projects->all as $project) {
			if (!isset($timelogs[$project->id])) continue;
			foreach($timelogs[$project->id] as $log) {
				if (!isset($hours[$project->id])) $hours[$project->id] = $log->convertTimeToHours($log->hours);
				else $hours[$project->id] += $log->convertTimeToHours($log->hours);
			}
		}
		
		if ($this->input->post('send')) {

			$retainer_terms = new Setting();
			$retainer_terms->where('name', 'retainer_terms')->get()->value;
						
			$invoice_number = new Invoice();
			$invoice_number->select_max('invoice_number')->get();
			$invoice_number = $invoice_number->invoice_number + 1;
			
			$batch = new Invoice();
			$batch->select_max('batch')->get();
			$batch = $batch->batch + 1;
			
			foreach($this->input->post('send') as $project_id) {
				$project = new Project();
				$project->company->get();
				$project->where('id', $project_id)->get();
				
				if (!$project->id) continue;
				
				$description = "Monthly retainer billing @ " . $project->billable_rate . "/hr for " . $project->fullProjectNumber() . " (" . $project->name . ")";
				
				$associations = array();
				
				$invoice = new Invoice();
				$invoice->invoice_number = $invoice_number;
				$invoice->batch = $batch;
				$invoice->description = $description;
				$invoice->invoice_date = date("Y-m-d 00:00:00", strtotime("now"));
				$invoice->due_date = date("Y-m-d 00:00:00", strtotime("+30 day"));
				$invoice->bill_to = $project->company->getAddress();
				$invoice->terms = $retainer_terms;
				$invoice->status = 'Unsent';
				
				// get recipients
				$contacts = $project->contacts->all;
				$emails = array();
				foreach($contacts as $c) {
					$emails[] = $c->email;
				}
				$invoice->recipients = implode(", ", $emails);
				
				// calculate total
				$amount = $hours[$project->id] * $project->billable_rate;
				
				// add single line item
				$line = new Lineitem();
				$line->description = $description;
				$line->amount = $amount;
				$line->save();
				$associations[] = $line;
				$associations[] = $project->company;
				$associations[] = $project;
				
				// associate the timelogs with this invoice to show they were billed
				if (isset($timelogs[$project_id])) {
					foreach($timelogs[$project_id] as $t) {
						$associations[] = $t;	
					}
				}
				
				if ($invoice->save($associations)) {
					
					// increment next invoice #
					$invoice_number += 1;
					
				} else {
					$errors[] = $invoice->error;
				}
			}
			
			if (!$errors) {
				// redirect to invoices filtered by batch so user may send them
				redirect('invoices/index/?batch=' . $batch . '&status=Unsent&orderby=invoice_number&order=ASC');
			}
		}
		
		// URL filter
		$params = array(
			'start_date'	=> $this->input->get('start_date'),
			'end_date'		=> $this->input->get('end_date'),
		);
		$filter = '&search=search&' . http_build_query($params);
		
		$this->data['errors'] = $errors;
		$this->data['projects'] = $projects->all;
		$this->data['hours'] = $hours;
		$this->data['timelog'] = new Timelog();
		$this->data['filter'] = $filter;
		$this->render('reports/timelogs/projects');
		
	}
	
	
	public function timelogs($type=null) {
	
		$timelogs = new Timelog();
		
		$total_time = 0;
		
		if ($this->input->get('search')) {
		
			$this->filter_timelogs($timelogs);
			
			$sum = $timelogs->get_clone();
			$total_time = $sum->select_sum('hours')->get()->hours;
		
		} else {
			$timelogs = null;
		}
		
		// companies
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
		$this->data['companies'] = $companies->all;
		
		// company
		$company = new Company();
		if ($this->input->get('company')) {
			$company->where('name', $this->input->get('company'))->get();
		}
		$this->data['company'] = $company;
		
		
		// projects
		$projects = new Project();
		$projects->order_by('start_date', 'desc')->order_by('project_number', 'desc')->get()->all;
		$this->data['companies'] = $projects->all;
		
		// project
		$project = new Project();
		if ($this->input->get('project')) {
			$project->where('id', $this->input->get('project'))->get();
		}
		$this->data['project'] = $project;
		
		// tasks
		$tasks = new Task();
		$tasks->order_by('name', 'asc')->get()->all;
		$this->data['tasks'] = $tasks->all;
		
		// task
		$task = new Task();
		if ($this->input->get('task')) {
			$task->where('id', $this->input->get('task'))->get();
		}
		$this->data['task'] = $task;
		
		// users
		$users = new User();
		$users->order_by('name', 'asc')->get();
		$this->data['users'] = $users->all;
		
		// user
		$user = new User();
		if ($this->input->get('user')) {
			$user->where('id', $this->input->get('user'))->get();
		}
		$this->data['user'] = $user;
		
		// render
		$user = new User();
		$project = new Project();
		$task = new Task();
		$this->data['projects'] = $project->order_by('name')->get()->all;
		$this->data['users'] = $user->order_by('name')->get()->all;
		$this->data['tasks'] = $task->order_by('name')->get()->all;
		
		
		$this->data['timelogs'] = $timelogs;
		$this->data['total_time'] = $total_time;
		$this->render('reports/timelogs/list');
	
	}
	
	public function filter_timelogs($timelogs) {
		$timelogs->start_cache();
			
		if ($this->input->get('start_date')) {
			$timelogs->where('log_date >= ', date("Y-m-d 00:00:00", strtotime($this->input->get('start_date'))));
		}
		
		if ($this->input->get('end_date')) {
			$timelogs->where('log_date <= ', date("Y-m-d 23:59:59", strtotime($this->input->get('end_date'))));
		}
		
		if ($this->input->get('project')) {
			$timelogs->where_related('task/project', 'id', $this->input->get('project'));
		}
		
		if ($this->input->get('company')) {
			$timelogs->where_related('task/project/company', 'name', urldecode($this->input->get('company')));
		}
		
		if ($this->input->get('user')) {
			$timelogs->where_related('user', 'id', $this->input->get('user'));
		}
		
		if ($this->input->get('task')) {
			$timelogs->where_related('task', 'id', $this->input->get('task'));
		}
		
		$count = $timelogs->count();
		
		$timelogs->order_by('log_date', 'desc')->get()->all;
		$timelogs->stop_cache();
		
		return $timelogs;
	}
	
}