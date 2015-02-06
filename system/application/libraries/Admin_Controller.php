<?php

class Admin_Controller extends RB_Controller {

	protected $data = array();
	protected $directory = 'cp/';
	protected $current_permissions = array();
	protected $user;
	
	public function Admin_Controller() {
		parent::RB_Controller();
		
		$this->checkAuthorizationCookie();
		
		$currentUser = $this->_getUserFromSession();
		if (!$currentUser) $currentUser = $this->_getUserFromApi();
		
		if ($currentUser) {
			$this->user = $currentUser;
		}
				
		// if currently in "client" section (based on URL), check for the company ID
		$this->authenticate();
	}
	
	private function _getUserFromApi() {
		$user = new User();
		$params = $this->getParams();
		if (isset($params['token'])) {
			$user = new User();
			$user->where('oauth_token', $params['token'])->get();
			if ($user->exists()) {
				$this->_setupUser($user);
			} else {
				// Not logged in
				$user = null;
			}
		}
		return $user;
	}
	
	private function _setupUser($user) {
		$user->company->get();
		if ($user->company->id) $this->current_company = $user->company;
		
		// if user is a client, ensure they are looking at the client side of the app
		if ($user->role == ROLE_CLIENT) {
			if (strpos($this->input->server('REQUEST_URI'), '/cp/') !== false) {
				$url = $_SERVER['REQUEST_URI'];
				$url = str_replace('/cp/', '/client/', $url);
				header('location: ' . $url);
				exit();
			}
		}
	}
	
	private function _getUserFromSession() {
		$user = null;
		if ($this->session->userdata('user_id')) {
			$user = new User();
			$user->where('id', $this->session->userdata('user_id'))->get();
			if ($user->exists()) {
				$this->_setupUser($user);
			} else {
				// Not logged in
				$user = null;
			}
		}
		return $user;
	}
	
	public function authenticate() {
		if (!$this->session->userdata('user_id')) {
			$this->session->set_flashdata('error', 'You do not have access to this page.');
			$url = parse_url($this->input->server('REQUEST_URI'));
			$path = $url['path'] . (isset($url['query']) ? '?' . $url['query'] : '') . (isset($url['fragment']) ? '#' . $url['fragment'] : '');
			redirect('/welcome/index/?redirect=' . $path);
		}
		return true;

	}
	
	public function inRole($role) {
		if (!$this->user) return false;
		return ($this->user->role == $role);
	}
	
	public function authorize($company_id) {
		$authorized = false;
		if ($this->user) {
			// user is admin
			if ($this->user->role == ROLE_ADMINISTRATOR) $authorized = true;
			if ($this->user->role == ROLE_EMPLOYEE) $authorized = true;
		
			// user signed in as this company
			if ($company_id == $this->user->company->id) {
				$authorized = true;
			}
		}
				
		if (!$authorized) {
			$this->session->set_flashdata('error', 'You are not authorized to view this page.');
			redirect('/');
		}
	}
	
	/**
	 * Check if current user is allowed to perform a specific
	 * action. Currently only checks against controller
	 * until roles are added later. As such, the $object
	 * var is not used but will later allow us to define if
	 * users are allowed to perform object-specific actions
	 * such as "create" a "project" or "delete" a "user".
	 */
	public function isAllowed($action, $object=null) {
	
		$allowed = false;
		
		if ($this->user && ($this->user->role == ROLE_EMPLOYEE)) {
			$empl_allowed = array('timelogs', 'contacts', 'companies');
			
			$permissions = array();
			$permissions['timelogs'] = array('view', 'edit', 'create', 'delete');
			$permissions['contacts'] = array('view');
			$permissions['companies'] = array('view');
			
			// check allowance
			if (in_array($object, $empl_allowed)) {
				$allowed = ($action == 'view');
				if (isset($permissions[$object])) {
					$allowed = in_array($action, $permissions[$object]);
				}
			}
		}
		elseif (($this->user && ($this->user->role == ROLE_CLIENT)) || !$this->user) {
			$client_allowed = array('invoices', 'files', 'contacts', 'documents', 'timelogs', 'statusreports', 'tasks', 'projects', 'retainers', 'estimates');
			
			$permissions = array();
			
			if ($this->user) 
			{
				$client_allowed[] = 'issues';
			
				// permissions are more granular - set the object as the key and the value to an array of actions allowed
				$permissions['issues'] = array('view', 'edit', 'create');
			}
			
			// check allowance
			if (in_array($object, $client_allowed)) {
				$allowed = ($action == 'view');
				if (isset($permissions[$object])) {
					$allowed = in_array($action, $permissions[$object]);
				}
			}
		} else {
			$allowed = true;
		}
		return $allowed;
	}
	
}