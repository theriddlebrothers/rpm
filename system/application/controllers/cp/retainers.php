<?php

class Retainers extends Admin_Controller {

	public function Retainers() {
		parent::Admin_Controller();
	}
	
	function create() 
	{
		if (!user_can('create', 'retainers')) access_error();
		
		$this->edit(null);
	}
	
	function delete($id) {
	
		if (!user_can('delete', 'retainers')) access_error();
		
		$retainerFactory = new Retainer();
		$retainer = $retainerFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $project->company->name . ' retainer was deleted.');
		$retainer->delete();
		
		redirect('retainers/');
	}
	
    /**
     * Download to PDF
     */
    function download($id) {
        if (!user_can('view', 'retainers')) access_error();

        $retainer = new Retainer();
        $retainer->where('id', $id)->get();
        $this->authorize($retainer->company->id);
        
        $retainer->generatePDF('I');
        
        return;
    }
    
	
	function edit($id) {
	
		if (!user_can('edit', 'retainers')) access_error();
		
		$associations = array();
		
		// setup
		$this->data['errors'] = null;
		$retainerFactory = new Retainer();
		$retainer = new Retainer();
		if ($id != null) {
			$retainer = $retainerFactory->where('id', $id)->get();
			$retainer->company->get();
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
			$retainer = new Retainer();
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
          	autobind($retainer);
            if ($this->input->post('start_date')) $retainer->start_date = date("Y-m-d 00:00:00", strtotime($this->input->post('start_date')));
            if ($this->input->post('end_date')) $retainer->end_date = date("Y-m-d 00:00:00", strtotime($this->input->post('end_date')));
            
            // notifications/actions
            if ($this->input->post('send_email_notification')) {
                    $retainer->send_email_notification = true;
                    $retainer->send_email_recipient = $this->input->post('send_email_recipient');
            } else {
                    $retainer->send_email_notification = false;
                    $retainer->send_email_recipient = null;
            }

            // company
            if ($this->input->post('company')) {
                $company = new Company();
                // see if another company has matching name - if so, use that company
                $company = $company->where('name', $this->input->post('company'))->get();
                $associations[] = $company;
                $estimate->company = $company;
            }
            
            if ($retainer->save($associations)) {
                    // save
                    $this->session->set_flashdata('success', $retainer->company->name . ' retainer was saved.');
                    redirect('retainers/');
            } else {
                    // invalid
                    $this->data['errors'] = $retainer->error->string;
            }		
		}
		
		
		
		// render template
		$companyFactory = new Company();
		$projectsFactory = new Project();
		$this->data['companies'] = $companyFactory->order_by('name')->get()->all;
		$this->data['projects'] = $projectsFactory->order_by('start_date', 'desc')->order_by('project_number', 'desc')->get()->all;
		$this->data['retainer'] = $retainer;
		if(!$retainer->id) $this->data['title'] = 'Create Retainer';
		else $this->data['title'] = 'Edit ' . $retainer->company->name . ' Retainer';
		$this->render('retainers/edit');
	}
	
	function index()
	{
		if (!user_can('view', 'retainers')) access_error();
		
		// setup
		$retainerFactory = new Retainer();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$retainerFactory->start_cache();
		/*
		// company
		$company = new Company();
		$company_name = $this->input->get('company');
		if ($company_name) {
			$projectFactory->where_related('company', 'name', $company_name);
			$company->where('name', $company_name)->get();
		}
		
		// companies list
		$companies = new Company();
		$companies->order_by('name', 'asc')->get();
		
		// status
		$status = $this->input->get('status');
		if ($status) {
			$projectFactory->where('status', $status);
		}
		
                 */
		
		if ($this->current_company) {
			$retainerFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		$count = $retainerFactory->count();
		$projects = $retainerFactory->order_by('id', 'desc')->get($limit, $offset)->all;
		$retainerFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('retainers/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['retainers'] = $projects;
		$this->data['title'] = 'Retainers';
		$this->render('retainers/list');
	}
	
	
	function view($id)
	{
		if (!user_can('view', 'retainers')) access_error();
		
		$retainerFactory = new Retainer();
		
		$retainer = $retainerFactory->where('id', $id)->get();
		if (!$retainer) redirect('retainers/');
		
		$this->authorize($retainer->company->id);
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if (!$this->input->post("signature")) {
				$errors[] = 'You must enter your name in order to approve this estimate.<br />';
			} else {
				$retainer->signature = $this->input->post('signature');
				$retainer->signature_date = date("Y-m-d H:i:s");
				$retainer->signature_ip = $this->input->ip_address();
				$retainer->status = 'Approved';
				$retainer->save();
		                        
                // mark project as approved
                $retainer->project->get();
                if ($retainer->project->id) {
                    $retainer->project->status = 'In Progress';
                    $retainer->project->save();
                }
				
				//now create first invoice - does NOT send invoice
				$this->create_invoice($retainer->id);
				
				// now send email
				$retainer->send();
		                        
			}
		}
				
		
		$this->load->library('markup'); 
		$retainer->terms = $this->markup->translate($retainer->terms);
		
		$this->data['retainer'] = $retainer;
		$this->data['title'] = $retainer->project . ' Retainer';
		$this->render('retainers/view');
	}
	
	
	function create_invoice($id) {
		$invoiceFactory = new Invoice();
		$max = $invoiceFactory->select_max('invoice_number')->get();
		$current_invoice_number = $max->invoice_number+1;
		$retainer = new Retainer();
		$retainer = $retainer->where('id', $id)->get();
		$retainer->company->get();
		
		$invoice = new Invoice();
		$invoice->recipients = $retainer->invoice_recipients;
		$invoice->bill_to = str_replace('<br />', "\n", $retainer->company->getAddress());
		$invoice->invoice_number = $current_invoice_number;
		$invoice->invoice_date = date("Y-m-d 00:00:00");
		$invoice->due_date = date("Y-m-d 00:00:00");
		$invoice->send_date = null;
		$invoice->description = "Invoice for " . $retainer->company->name . " Retainer #" . $retainer->id;
		$invoice->terms = 'NET 30';
		$invoice->status = 'Unsent';
		$line_item = new Lineitem();
		$line_item->description = 'Retainer #' . $retainer->id . ' billing for ' . date("F", strtotime($invoice->invoice_date)) . ' ' . date("Y", strtotime($invoice->invoice_date));
		$line_item->amount = $retainer->monthlyTotal();
		$line_item->save();
		
		if (!$invoice->save(array($line_item, $retainer->company, $retainer->project))) {
			die("Unable to save invoice: " . var_export( $invoice->errors, true));
		}
	}
}
