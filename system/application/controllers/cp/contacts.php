<?php

class Contacts extends Admin_Controller {

	public function Contacts() {
		parent::Admin_Controller();
	}
	
	function ajax($action) {
		$contacts = array();
		
		switch($action) {
			case 'company':
				$contacts = new Contact();
				$company = $this->input->get('company');
				$contacts->where_related('company', 'name', $company)->get();
			break;
			case 'project':
				$contacts = new Contact();
				$project = $this->input->get('project');
				$contacts->where_related('project', 'id', $project)->get();
			break;
		}
		
		if (!$contacts) return;
				
		$ret = array();
		foreach($contacts->all as $c) {
			$contact = new stdClass();
			$contact->id = $c->id;
			$contact->first_name = $c->first_name;
			$contact->last_name = $c->last_name;
			$contact->email = $c->email;
			$ret[] = $contact;
		}
		echo json_encode($ret);
	}
	
	function create() 
	{
		if (!user_can('create', 'contacts')) access_error();
		$this->edit(null);
	}
	
	function delete($id, $company_id=null) {
		if (!user_can('delete', 'contacts')) access_error();
		$contactFactory = new Contact();
		$contact = $contactFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $contact->first_name . ' ' . $contact->last_name . ' was deleted.');
		$contact->delete();
		
		if ($company_id) redirect('contacts/company/' . $company_id);
		else redirect('contacts/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'contacts')) access_error();
		// setup
		$this->data['errors'] = null;
		$contactFactory = new Contact();
		$contact = new Contact();
		if ($id != null) {
			$contact = $contactFactory->where('id', $id)->get();
			$contact->company->get();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$contact->first_name = $this->input->post('first_name');
			$contact->last_name = $this->input->post('last_name');
			$contact->email = $this->input->post('email');
			$contact->phone = $this->input->post('phone');
			$contact->fax = $this->input->post('fax');
			$contact->address = $this->input->post('address');
			$contact->city = $this->input->post('city');
			$contact->state = $this->input->post('state');
			$contact->zip = $this->input->post('zip');
			$contact->website = $this->input->post('website');
			
			$company = null;
			if ($this->input->post('company')) {
				$company = new Company();
				$company = $company->where("name", $this->input->post('company'))->get();
				$contact->company = $company;
			}
				
			// subscribtion
			if ($this->input->post('subscribed')) {
				$result = $contact->subscribe();
		
				// 201 = success, response->code of 204 = previously unsubscribed (can't add again)
				if ($result->http_status_code != 201) {
					if ($result->response->Code == 204) {
						$this->session->set_flashdata('error', "Unable to subscribe: " . $result->response->Message);
					} else {
						$this->session->set_flashdata('error', "Unable to subscribe due to an error: " . $result->response->Message . var_export($result, true));
					}
				}
				
			} else {
				$result = $contact->unsubscribe();
				
				// 201 = success, 202 = not subscribed or not in list
				if ($result !== false) {
					if ($result->http_status_code != 200) {
						
						if ($result->http_status_code == 400) {
							$this->session->set_flashdata('error', "Unable to unsubscribe due to an error: " . $result->response->Message . var_export($result, true));
						} else {
							$this->session->set_flashdata('error', "Unable to unsubscribe: " . $result->response->Message);
						}
					}
				}
			}
			
			if ($contact->save($company)) {
				// save
				$this->session->set_flashdata('success', $contact->first_name . ' ' . $contact->last_name . ' was saved.');
				redirect('contacts/');
			} else {
				// invalid
				$this->data['errors'] = $contact->error->string;
			}		
		}
		
		// render template
		$companyFactory = new Company();
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['contact'] = $contact;
		$this->data['companies'] = $companyFactory->order_by('name')->get()->all;
		if (!$contact->id) $this->data['title'] = 'Create Contact';
		else $this->data['title'] = 'Edit ' . $contact->first_name . ' ' . $contact->last_name;
		$this->render('contacts/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'contacts')) access_error();
		
		// setup
		$contactFactory = new Contact();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		if ($this->current_company) {
			$contactFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		if ($this->input->get('company')) {
			$contactFactory->where_related('company', 'id', $this->input->get('company'));
		}
		
		$contacts = $contactFactory->order_by('last_name')->limit($limit)->get($limit, $offset)->all;
		
		
		// pagination
		$config['base_url'] = site_url('contacts/index/?limit=' . $limit);
		$config['total_rows'] = $contactFactory->count();
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['company'] = new Company();
		$this->data['contacts'] = $contacts;
		$this->data['title'] = 'Contacts';
		$this->render('contacts/list');	
	}
	
	
	function view($id) {
		if (!user_can('view', 'contacts')) access_error();
		
		$contactFactory = new Contact();
		
		$contact = $contactFactory->where('id', $id)->get();
		
		if (!$contact) redirect('contacts/');
		
		// render template
		$this->config->load('states', true);
		$this->data['states'] = $this->config->item('states');
		$this->data['contact'] = $contact;
		$this->data['title'] = $contact->first_name . ' ' . $contact->last_name;
		$this->render('contacts/view');
	}
	
}
