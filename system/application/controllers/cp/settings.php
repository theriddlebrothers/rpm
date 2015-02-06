<?php

class Settings extends Admin_Controller {

	public function Settings() {
		parent::Admin_Controller();
	}
	
	function edit($id) {
		if (!user_can('edit', 'settings')) access_error();
		// setup
		$this->data['errors'] = null;
		$settingFactory = new Setting();
		$setting = new Setting();
		if ($id != null) {
			$setting = $settingFactory->where('id', $id)->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($setting);
			
			if ($setting->save()) {
				// save
				$this->session->set_flashdata('success', $setting->name . ' was saved.');
				redirect('settings/');
			} else {
				// invalid
				$this->data['errors'] = $setting->error->string;
			}		
		}
		
		// render template
		$this->data['setting'] = $setting;
		$this->data['title'] = 'Edit ' . $setting->name;
		$this->render('settings/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'settings')) access_error();
		
		// setup
		$settingFactory = new Setting();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$settings = $settingFactory->order_by('name')->limit($limit)->get($limit, $offset)->all;
		
		// pagination
		$config['base_url'] = site_url('settings/index/?limit=' . $limit);
		$config['total_rows'] = $settingFactory->count();
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['company'] = new Company();
		$this->data['settings'] = $settings;
		$this->data['title'] = 'Settings';
		$this->render('settings/list');	
	}
	
}
