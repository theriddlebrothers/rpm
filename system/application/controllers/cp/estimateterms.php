<?php

class Estimateterms extends Admin_Controller {

	function create() 
	{
		if (!user_can('create', 'estimateterms')) access_error();
		
		$this->edit(null);
	}
		
	function delete($id) {
		if (!user_can('delete', 'estimateterms')) access_error();
		
		$termFactory = new Estimateterm();
		$term = $termFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $term->name . ' was deleted.');
		$term->delete();
		
		if ($id) redirect('estimateterms/');
		else redirect('estimateterms/');
	}
	
	function edit($id) {
	
		if (!user_can('edit', 'estimateterms')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$termFactory = new Estimateterm();
		$term = new Estimateterm();
		if ($id != null) {
			$term = $termFactory->where('id', $id)->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($term);
			
			if ($term->save()) {
				// save
				$this->session->set_flashdata('success', $term->name . ' was saved.');
				redirect('estimateterms/');
			} else {
				// invalid
				$this->data['errors'] = $term->error->string;
			}		
		}
		
		// render template
		$this->data['term'] = $term;
		if (!$term->id) $this->data['title'] = 'Create Estimateterm';
		else $this->data['title'] = 'Edit ' . $term->name;
		$this->render('estimateterms/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'estimateterms')) access_error();
		
		// setup
		$termFactory = new Estimateterm();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$estimateterms = $termFactory->order_by('name')->limit($limit)->get($limit, $offset)->all;
		
		// pagination
		$config['base_url'] = site_url('estimateterms/index/?limit=' . $limit);
		$config['total_rows'] = $termFactory->count();
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['company'] = new Company();
		$this->data['terms'] = $estimateterms;
		$this->data['title'] = 'Estimateterms';
		$this->render('estimateterms/list');	
	}
	
	
	function view($id) {
		if (!user_can('view', 'companies')) access_error();
		
		$termFactory = new Estimateterm();
		
		$term = $termFactory->where('id', $id)->get();
		
		if (!$term) redirect('estimateterms/');
		
		$term->content = str_replace("\n", "<br />", $term->content);
		
		// render template
		$this->data['term'] = $term;
		$this->data['title'] = $term->name;
		$this->render('estimateterms/view');
	}
	
}
