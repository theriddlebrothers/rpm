<?php

class Dashboard extends Admin_Controller {

	function index()
	{
		require_once './libs/Github/Autoloader.php';
		Github_Autoloader::register();
		
		if ($this->user && ($this->user->role == ROLE_ADMINISTRATOR)) {
			// New Leads
			$this->_get_leads();
			
			// latest activitieis
			$this->_get_activities();
			
			// Dashboard Projects
			$this->_get_projects();
			
			//Git info
			//$this->_get_gitinfo();
			
		} else {
			// Client
			$this->_get_projects(true);
		}
		
		$this->data['title'] = 'Dashboard';
		$this->render('dashboard/index');	
	}
	
	function testmail() {
		$this->load->library('email');
			
		$this->email->from('payment@theriddlebrothers.com', 'the Riddle Brothers');
		$this->email->to('josh@theriddlebrothers.com');
		$this->email->subject('Test email');
		$this->email->send();
		echo $this->email->print_debugger();
	}
	
	function _get_activities() {
		$activities = new Activity();
		$activities->order_by('activity_date', 'desc')->get(10);
		$this->data['activities'] = $activities->all;
	}
	
	function _get_leads() {
		$leads = new Company();
		$leads->where('status', 'New Business')->get();
		$this->data['leads'] = $leads->all;
	}
	
	function _get_projects($show_retainer=null) {
		$projects = new Project();
		
		if ($show_retainer === null) $show_retainer = $this->input->get('show_retainer');
		
		if (!$show_retainer) {
			$projects->where('project_type', 'Fixed Cost');
		}
		
		if ($this->current_company) {
			$projects->where_related('company', 'id', $this->current_company->id);
		}
		
		$projects->group_start()
					->where('status', 'In Progress')
					->or_where('status', 'Approved')
					->group_end()
					->order_by('start_date', 'desc')
					->order_by('project_number', 'desc')
					->get();
		
		$total_estimated = 0;
		$total_invoiced = 0;
		$remaining_invoice = 0;
		foreach($projects->all as $p) {
			$total_estimated += $p->getTotalAcceptedEstimated();
			$total_invoiced += $p->getTotalInvoiced();
			$remaining_invoice += $p->getRemainingInvoice();
		}
		
		$this->data['show_retainer'] = $show_retainer;
		$this->data['total_estimated'] = $total_estimated;
		$this->data['total_invoiced'] = $total_invoiced;
		$this->data['remaining_invoice'] = $remaining_invoice;
		$this->data['projects'] = $projects->all;
	}
	
	function _get_gitinfo($user = 'theriddlebrothers'){
	
		$returned = $this->github_lib->user_info($user);
		
				$github = new Github_Client();
				try{
					$github->authenticate('theriddlebrothers', 'c79A80j83', Github_Client::AUTH_HTTP_PASSWORD);
					//$users = $github->getUserApi()->search('theriddlebrothers');
					$repos = $github->getRepoApi()->getUserRepos('theriddlebrothers');
					$commits = $github->getCommitApi()->getBranchCommits('ornicar', 'php-github-api', 'master');
					
					$commitArray = Array();
					foreach($repos as $repo)
					{
						$commits = $github->getCommitApi()->getBranchCommits('theriddlebrothers', $repo['name'], 'master');
						
						$commitArray[] = Array('name' => $repo['name'], 'commits' => $commits[0]);
					}
				}catch(Exception $e)
				{
				
				
				}
		
				
						
				//$this->data['git_userInfo'] = $users;
				//$this->data['git_repoInfo'] = $repos;
				$this->data['git_commits'] = $commitArray;
		
	}
	
}
