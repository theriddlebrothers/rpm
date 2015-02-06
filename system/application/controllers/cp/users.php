<?php

class Users extends Admin_Controller {

	function create() 
	{
		if (!user_can('create', 'users')) access_error();
		
		$this->edit(null);
	}
		
	function delete($id) {
		if (!user_can('delete', 'users')) access_error();
		
		$userFactory = new User();
		$user = $userFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $user->name . ' was deleted.');
		$user->delete();
		
		if ($id) redirect('users/');
		else redirect('users/');
	}
	
	function edit($id) {
	
		if (!user_can('edit', 'users')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$userFactory = new User();
		$user = new User();
		if ($id != null) {
			$user = $userFactory->where('id', $id)->get();
			$user->company->get();
		}
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$associations = array();
			$user->name = $this->input->post('name');
			$user->username = $this->input->post('username');
			$user->role = $this->input->post('role');
			
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
				}
				if ($company->id) $associations[] = $company;
				$user->company = $company;
			} else {
				// remove existing association
				$company = new Company();
				$company = $company->where('id', $user->company->id)->get();
				if ($company->id) $user->delete($company);
			}
			
			if ($this->input->post('password')) {
				$user->password = $this->input->post('password');
			}
			$user->email = $this->input->post('email');
						
			if (!$user->save($associations)) {
				// invalid
				$this->data['errors'] = $user->error->string;
			} else {
				$this->save_permissions($user);
				
				// save
				$this->session->set_flashdata('success', $user->name . ' was saved.');
				redirect('users/');
			}
		}
		
		// companies
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
		$this->data['companies'] = $companies->all;
		
		// roles setup
		$roles = array(
			''				=>	'Select a role...',
			'Administrator'	=>	ROLE_ADMINISTRATOR,
			'Client'		=>	ROLE_CLIENT,
			'Employee'		=>	ROLE_EMPLOYEE
		);
		
		// permissions setup
		$permissions = new Permission();
		$permissions->order_by('action')->order_by('name')->get()->all;
		
		
		// current user permissions
		$current_perms = new Permission();
		$current_perms->where_related('user', 'id', $id)->get();
		$user_perms = array();
		foreach($current_perms->all as $p) {
			$user_perms[] = $p->id;
		}
		
		// render template
		$this->data['roles'] = $roles;
		$this->data['permissions'] = $permissions;
		$this->data['user_perms'] = $user_perms;
		$this->data['user'] = $user;
		if (!$user->id) $this->data['title'] = 'Create User';
		else $this->data['title'] = 'Edit ' . $user->name;
		$this->render('users/edit');
	}
	
	function save_permissions(&$user) {
		$perm = $this->input->post("perm");
		
		// delete old perms
		$permissions = new Permission();
		$permissions->where_related('user', 'id', $user->id)->get();
		$user->delete($permissions->all);
		$user->refresh_all();
		
		if ($perm) {
			foreach($perm as $p) {
				$permission = new Permission();
				$permission->where('id', $p)->get();
				$permission->save($user);
			}
		}
	}
	
	function index()
	{
		if (!user_can('view', 'users')) access_error();
		
		// setup
		$userFactory = new User();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$users = $userFactory->order_by('name')->limit($limit)->get($limit, $offset)->all;
		
		// pagination
		$config['base_url'] = site_url('users/index/?limit=' . $limit);
		$config['total_rows'] = $userFactory->count();
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['company'] = new Company();
		$this->data['users'] = $users;
		$this->data['title'] = 'Users';
		$this->render('users/list');	
	}
	
}
