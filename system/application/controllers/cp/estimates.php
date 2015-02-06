<?php

class Estimates extends Admin_Controller {

	function ajax($action) {
		$estimateFactory = new Estimate();
		switch($action) {
			case 'lineitems':
				$id = $this->input->get('estimate');
				if ($id) {
					$estimate = $estimateFactory->where('id', $id)->get();
					$line_items = array();
					if ($estimate->id) {
						foreach($estimate->costitem->order_by('id')->get()->all as $item) {
							$i = new stdClass();
							if ($item->item_type == 'price') {
								$i->description = $item->description;
								$i->amount = $item->amount;
								$line_items[] = $i;
							}
						}
					}
					echo json_encode($line_items);
					break;
				}
				break;
			case 'project':
				// return estimates for specific project
				$id = $this->input->get('project');
				$project = new Project();
				$project = $project->where('id', $id)->get();
				
				if (!$project->id) return;
				
				$project->company->get();
				
				// company info
				$response = new stdClass();
				$company = new stdClass();
				$company->name = $project->company->name;
				$company->address = str_replace("<br />", "\n", $project->company->getAddress());
				$response->company = $company;
				
				// estimates
				$estimates = array();
				if ($id) {
					foreach($project->estimate->all as $estimate) {
						$item = new stdClass();
						$item->id = $estimate->id;
						$item->name = $estimate->name;
						$item->estimate_number = $estimate->estimate_number;
						$estimates[] = $item;
					}
				}
				$response->estimates = $estimates;
				echo json_encode($response);
				break;	
		}
	}
	
	function create() 
	{
		if (!user_can('create', 'estimates')) access_error();
		$this->edit(null);
	}
	
	function delete($id) {
		if (!user_can('delete', 'estimates')) access_error();
		$estimateFactory = new Estimate();
		$estimate = $estimateFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $estimate->name . ' was deleted.');
	
		// remove previous cost associations
		foreach($estimate->costitem->all as $item) {
			$item->delete();
		}
		$estimate->delete($estimate->costitem->all);
		
		
		$estimate->delete();
		
		redirect('estimates/');
	}
	
	function edit($id) {
		if (!user_can('edit', 'estimates')) access_error();
		// setup
		$this->data['errors'] = null;
		$estimateFactory = new Estimate();
		$estimate = new Estimate();
		$cost = null;
		$associations = array();
		$errors = array();
		
		if ($id != null) {
			$estimate = $estimateFactory->where('id', $id)->get();
			$estimate->project->get();
			$estimate->costitem->get();
			$estimate->estimateterm->get();
			$estimate->company->get();
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
			$number = new Estimate();
			$number = $number->select_max('estimate_number')->get()->estimate_number + 1;
			$estimate->estimate_number = $number;
			
			$default_terms = new Setting();
			$default_terms->where('name', 'estimate_termsboilerplate')->get();
			
			$boilerplate_content = new Setting();
			$boilerplate_content->where('name', 'estimate_boilerplate')->get();
			$estimate->content = $boilerplate_content->value;
			
			$estimate->custom_terms = $default_terms->value;
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$estimate->name = $this->input->post('name');
			$estimate->estimate_number = $this->input->post('estimate_number');
			$estimate->status = $this->input->post('status');
			$estimate->content = $this->input->post('content');
			$estimate->term_type = $this->input->post('term_type');
			
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
				$estimate->company = $company;
			} else {
				// remove existing association
				$company = new Company();
				$company = $company->where('id', $estimate->company->id)->get();
				$estimate->delete($company);
			}
						
			// project
			if ($this->input->post('project')) {
				$project = new Project();
				$project = $project->where('id', $this->input->post('project'))->get();
				$associations[] = $project;
				$estimate->project = $project;
			} else {
				// remove existing association
				$project = new Project();
				$project = $project->where('id', $estimate->project->id)->get();
				$estimate->delete($project);
			}
			
			// notifications/actions
			if ($this->input->post('send_email_notification')) {
				$estimate->send_email_notification = true;
				$estimate->send_email_recipient = $this->input->post('send_email_recipient');
			} else {
				$estimate->send_email_notification = false;
				$estimate->send_email_recipient = null;
			}
			
			// estimate date
			if ($this->input->post('estimate_date')) {
				$dt = strtotime($this->input->post('estimate_date'));
				$dt = date("Y-m-d 00:00:00", $dt);
				$estimate->estimate_date = $dt;
			}
			
			// save new terms
			$term = null;
			$estimate->custom_terms = $this->input->post('custom_terms');
			if ($this->input->post('term_type') != 'custom') {
				// use saved term
				$term = new EstimateTerm();
				$term = $term->where('id', $this->input->post('estimateterm'))->get();
				$associations[] = $term;
			} else {
				// remove existing association
				$term = new EstimateTerm();
				$term = $term->where('id', $estimate->estimateterm->id)->get();
				$estimate->delete($term);
			}
			
			// remove previous cost associations
			$estimate->delete($estimate->costitem->all);
			$estimate->refresh_all();
			
			// save cost items
			$cost_items = array();
			$item_array = $estimate->getCostItemsAsArray($this->input);
			foreach($item_array as $item_arr) {
				$item = new CostItem();
				$item->description = $item_arr['description'];
				$item->item_type = $item_arr['item_type'];
				$item->amount = $item_arr['amount'];
				$item->heading = $item_arr['heading'];

				if ($item->save()) {
					$cost_items[] = $item;
				} else {
					$errors[] = $item->error;
				}
				
			}
			if ($cost_items) $associations[] = $cost_items;
			
				
				
			// save estimate
			if (!$errors && $estimate->save($associations)) {
				// save
				$this->session->set_flashdata('success', $estimate->name . ' was saved.');
				redirect('estimates/');
			} else {
				// invalid
				$errors[] = $estimate->error;
			}		
		}
		
		// companies
		$companies = new Company();
		$companies->order_by('name')->get();
		
		// line items
		$cost_items = $estimate->getCostItemsAsArray($this->input);
		if (!$cost_items) {
			$cost_items = array();
			// create a few defaults
			for($i=1; $i<=4; $i++) {
				$item = array(	'description'		=>	'',
								'amount'			=>	'',
								'heading'			=>	'',
								'item_type'			=>	(($i == 1) ? 'heading' : ''));
				$cost_items[] = $item;
			}
		}
		
		// render template
		$projectsFactory = new Project();
		$termsFactory = new EstimateTerm();
		$usersFactory = new User();
		$this->data['errors'] = $errors;
		$this->data['companies'] = $companies->all;
		$this->data['terms'] = $termsFactory->get()->all;
		$this->data['projects'] = $projectsFactory->order_by('project_number', 'desc')->order_by('start_date', 'desc')->get()->all;
		$this->data['users'] = $usersFactory->get()->all;
		$this->data['estimate'] = $estimate;
		$this->data['cost_items'] = $cost_items;
		if (!$estimate->id) $this->data['title'] = 'Create Estimate';
		else $this->data['title'] = 'Edit ' . $estimate->name;
		$this->render('estimates/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'estimates')) access_error();
		// setup
		$estimateFactory = new Estimate();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$estimateFactory->start_cache();
		
		// project
		$project_id = $this->input->get('project');
		if ($project_id) {
			$estimateFactory->where_related('project', 'id', $project_id);
		}
		
		// company (current login)		
		if ($this->current_company) {
			$estimateFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		// company
		$company = new Company();
		$company_name = $this->input->get('company');
		if ($company_name) {
			$estimateFactory->where_related('company', 'name', $company_name);
			$company->where('name', $company_name)->get();
		}
                
                // status
                if ($this->input->get('status')) {
			$estimateFactory->where('status', $this->input->get('status'));
                }
                
                // retrieve estimates
		$count = $estimateFactory->count();
		$estimates = $estimateFactory->order_by('estimate_number', 'desc')->get($limit, $offset)->all;
		$estimateFactory->stop_cache();
		
		// companies list
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
                
                
		// pagination
		$config['base_url'] = site_url('estimates/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['estimates'] = $estimates;
		$this->data['company'] = $company;
		$this->data['companies'] = $companies->all;
		$this->data['title'] = 'Estimates';
		$this->render('estimates/list');
	}
	
	
	function send($id) {
	
		$estimate = new Estimate();
		$estimate->where('id', $id)->get();
		
		if (!$estimate->id) {
			redirect('estimates');
		}
		
		$estimate->company->get();
		
		
		$this->authorize($estimate->company->id);
		
		// Render email
		$setting = new Setting();
		
		$this->load->library('markup'); 
		$estimate->content = $this->markup->translate($estimate->content);
		$estimate->estimateterm->content = $this->markup->translate($estimate->estimateterm->content);
		$estimate->custom_terms = $this->markup->translate($estimate->custom_terms);
		
		// render template
		$this->data['estimate'] = $estimate;
		$this->data['cost_items'] = $estimate->getCostItemsAsArray($this->input, true);
		$this->data['view_link'] = $estimate->company->getViewLink('/client/estimates/view/' . $estimate->id);
		$body = $this->load->view('client/estimates/send', $this->data, true);
		
		// Send email
		$setting = new Setting();
		$email_from_name = $setting->where('name', 'email_fromname')->get()->value;
		$email_from = $setting->where('name', 'email_from')->get()->value;
		$email_cc = $setting->where('name', 'email_cc')->get()->value;
		$email_bcc = $setting->where('name', 'email_bcc')->get()->value;
		$estimate_notification_email = $setting->where('name', 'estimate_notificationemail')->get()->value;
		$estimate_subject = $setting->where('name', 'estimate_subject')->get()->value;
		
		$estimate_subject = str_replace('[ESTIMATE NUMBER]', $estimate->estimate_number, $estimate_subject);
	
		$ci =& get_instance();
		
		$ci->load->library('email');
		$ci->email->from($email_from, $email_from_name);
		if ($estimate->send_email_recipient) {
			$ci->email->to($estimate->send_email_recipient);
		} else {
			// if no recipient, just send to a throw away in order to still CC/BCC emails.
			$ci->email->to('noreply@theriddlebrothers.com');
		}
		
		// add global cc
		$cc = array();
		if ($email_cc) $cc[] = $email_cc;
		if ($cc) $ci->email->cc($cc);
		
		// add global bcc and estimates bcc
		$bcc = array();
		if ($estimate_notification_email) $bcc[] = $estimate_notification_email;
		if ($email_bcc) $bcc[] = $email_bcc;
		if ($bcc) $ci->email->bcc($bcc);
		
		$ci->email->subject($estimate_subject);
		
		$ci->email->message($body);
		
		if ($ci->email->send()) {
			$this->session->set_flashdata('success', 'Thank you! A copy of this estimate has been sent to ' . $estimate->send_email_recipient);
			redirect('estimates/view/' . $estimate->id);
		} else {
			return 'Unable to send estimate copy to ' . $estimate->send_email_recipient;
		}
		
		//echo $ci->email->print_debugger();
		
	}
	
	function view($id) {
		if (!user_can('view', 'estimates')) access_error();
		$estimateFactory = new Estimate();
		$errors = array();
		
		$estimate = $estimateFactory->where('id', $id)->get();
		$estimate->company->get();
                
		if (!$estimate) redirect('estimates/');
		$this->authorize($estimate->company->id);
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if (!$this->input->post("signature")) {
				$errors[] = 'You must enter your name in order to approve this estimate.<br />';
			} else {
                                $estimate->project->get();
				$estimate->signature = $this->input->post('signature');
				$estimate->signature_date = date("Y-m-d H:i:s");
				$estimate->signature_ip = $this->input->ip_address();
				$estimate->status = 'Approved';
				
				// estimate terms should be stored as customer terms in case the boilerplate
				// estimate terms change in the future
				if ($estimate->term_type == 'stored') {
					$estimate->term_type = 'custom';
					$estimate->custom_terms = $estimate->estimateterm->content;
				}	
				$estimate->save();
                                
                                // mark project as approved
                                if ($estimate->project->id) {
                                    $estimate->project->status = 'In Progress';
                                    $estimate->project->save();
                                }
				
				// now send email
				$this->send($estimate->id);
                                
                        }
		}
				
		$this->load->library('markup'); 
		$estimate->content = $this->markup->translate($estimate->content);
		$estimate->estimateterm->content = $this->markup->translate($estimate->estimateterm->content);
		$estimate->custom_terms = $this->markup->translate($estimate->custom_terms);
		
		// render template
		$this->data['errors'] = $errors;
		$this->data['estimate'] = $estimate;
		$this->data['view_link'] = $estimate->company->getViewLink('/client/estimates/view/' . $estimate->id);
		$this->data['cost_items'] = $estimate->getCostItemsAsArray($this->input);
		$this->data['title'] = $estimate->name;
		$this->render('estimates/view');
	}
	
	
}
