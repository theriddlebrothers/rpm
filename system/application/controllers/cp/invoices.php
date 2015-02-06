<?php

class Invoices extends Admin_Controller {

	/**
	 * Create new invoice
	 */
	function create() 
	{
		if (!user_can('create', 'invoices')) access_error();
		$this->edit(null);
	}
	
	
	/**
	 * Confirm batch sending of invoices
	 */
	function confirm() 
	{
		$errors = array();
		$unsent = array();
		
		// send invoices
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->input->post('send')) {
				// prepare invoicees for sending
				foreach($this->input->post('send') as $invoice_id) {
					$error = $this->send($invoice_id);
					if ($error)
					{
						$errors[] = $error . '<br />';
						$unsent[] = $invoice_id;
					}
				}
				
				if (!$errors) {
					// show success log
                                        $this->session->set_flashdata('success', 'All of your invoices were sent successfully. To see a log of emails you may check the <a href="' . site_url('emaillogs') . '">email logs</a> screen.');
                                        redirect('invoices');
				} else {
					// re-display with errors
					$ids = implode(",", $unsent);
					$this->session->set_flashdata('error', 'There was a problem sending the invoices below. The other invoices selected have been sent.');
					redirect('invoices/confirm/?selected=' . $ids);
				}
				
				
			} else {
				$errors[] = 'You must select at least one invoice to send. If you do not see a checkbox next to the invoice then you have not entered any email recipients for that invoice.';
			}
		}
		
		// setup
		$invoiceFactory = new Invoice();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$invoiceFactory->start_cache();
		
		$ids = $this->input->get('selected');
		$ids = explode(",", $ids);
		$invoiceFactory->where_in('id', $ids);
		
		// order field
		$orderby = $this->input->get('orderby');
		if (!$orderby) $orderby = 'invoice_number';
		
		// direction
		$order = $this->input->get('order');
		if (!$order) $order = 'DESC';
		
		$count = $invoiceFactory->count();
		$invoices = $invoiceFactory->order_by($orderby, $order)->get()->all;
		$invoiceFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('invoices/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['errors'] = $errors;
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['invoices'] = $invoices;
		$this->data['title'] = 'Invoices';
		$this->render('invoices/confirm');

	}
	
	/**
	 * Delete an invoice
	 */
	function delete($id) {
		if (!user_can('delete', 'estimates')) access_error();
		$invoiceFactory = new Invoice();
		$invoice = $invoiceFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', 'Invoice #' . $invoice->invoice_number . ' was deleted.');
		$invoice->delete();
		
		redirect('invoices/');
	}
	
    /**
     * Download to PDF
     */
    function download($id) {
        if (!user_can('view', 'invoices')) access_error();

        $invoice = new Invoice();
        $invoice->where('id', $id)->get();
        $this->authorize($invoice->company->id);
        
        $invoice->generatePDF('I');
        
        return;
    }
    
        
	/** 
	* Edit an invoice
	*/
	function edit($id) {
		if (!user_can('edit', 'invoices')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$invoiceFactory = new Invoice();
		$invoice = new Invoice();	
		$associations = array();
			
		if ($id != null) {
			$invoice = $invoiceFactory->where('id', $id)->get();
			$invoice->company->get();
			$invoice->project->get();
			$invoice->estimate->get();
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
		
			// invoice number
			if (!$invoice->id) {
				$max = $invoiceFactory->select_max('invoice_number')->get();
				$invoice->invoice_number = $max->invoice_number + 1;
				$invoice->invoice_date = date("m/d/Y", strtotime("now"));
				$invoice->due_date = date("m/d/Y", strtotime("+30 day"));
				$invoice->terms = 'NET 30';
			}
		}
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			autobind($invoice);
			$invoice->terms = $this->input->post('terms__sexyCombo');
			$invoice->description = $this->input->post('invoice_description');
			
			if ($this->input->post('invoice_date')) {
				$end = strtotime($this->input->post('invoice_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$invoice->invoice_date = $end;
			}
			
			if ($this->input->post('due_date')) {
				$end = strtotime($this->input->post('due_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$invoice->due_date = $end;
			}
                        
			if ($this->input->post('send_date')) {
				$end = strtotime($this->input->post('send_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$invoice->send_date = $end;
			} else {
                            $invoice->send_date = null;
                        }
			
			
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
				$invoice->company = $company;
			} else {
				// remove existing association
				$company = new Company();
				$company = $company->where('id', $invoice->company->id)->get();
				$invoice->delete($company);
			}
						
			// project
			if ($this->input->post('project')) {
				$project = new Project();
				$project = $project->where('id', $this->input->post('project'))->get();
				$associations[] = $project;
				$invoice->project = $project;
			} else {
				// remove existing association
				$project = new Project();
				$project = $project->where('id', $invoice->project->id)->get();
				$invoice->delete($project);
			}
			
			// estimate
			if ($this->input->post('estimate')) {
				$estimate = new Estimate();
				$estimate = $estimate->where('id', $this->input->post('estimate'))->get();
				$associations[] = $estimate;
			} else {
				// remove existing association
				$estimate = new Estimate();
				$estimate = $estimate->where('id', $invoice->estimate->id)->get();
				$invoice->delete($estimate);
			}
			
			
			// remove previous line items
			$invoice->delete($invoice->lineitem->all);
			$invoice->refresh_all();
			
			// save line items
			$items = $invoice->getLineItemsAsArray($this->input);
			foreach($items as $item) {
				$line = new Lineitem();
				$line->description = $item['description'];
				$line->amount = $item['amount'];
				if (!$line->save()) {
					$errors[] = $line->error;
				} else {
					$associations[] = $line;
				}
			}
			
			if ($invoice->save($associations)) {
				// save
				$this->session->set_flashdata('success', 'Invoice #' . $invoice->invoice_number . ' was saved.');
				redirect('invoices/');
				
			} else {
				// invalid
				$this->data['errors'] = $invoice->error->string;
			}		
		}
		
		// terms
		$terms = array('Due on Receipt', 'NET 30', 'NET 45', 'NET 60');
		
		// add custom term if applicable
		if (!in_array($invoice->terms, $terms)) {
			array_unshift($terms, $invoice->terms);
		}
			
		// line items
		$line_items = $invoice->getLineItemsAsArray($this->input);
		if (!$line_items) {
			$line_items = array();
			// create a few defaults
			for($i=1; $i<=4; $i++) {
				$item = array(	'description'	=>	'',
								'amount'	=>	'');
				$line_items[] = $item;
			}
		}
		
		// estimates
		$estimates = array();
		if ($invoice->project->id) {
			$estimates = $invoice->project->estimate->all;
		}
		
		// render template
		$projectsFactory = new Project();
		$companyFactory = new Company();
		$estimatesFactory = new Estimate();
		$this->data['invoice'] = $invoice;
		$this->data['line_items'] = $line_items;
		$this->data['companies'] = $companyFactory->order_by('name')->get()->all;
		$this->data['estimates'] = $estimates;
		$this->data['projects'] = $projectsFactory->order_by('start_date', 'desc')->order_by('project_number', 'desc')->get()->all;
		$this->data['terms'] = $terms;
		if (!$invoice->id) $this->data['title'] = 'Create Invoice';
		else $this->data['title'] = 'Edit Invoice #' . $invoice->invoice_number;
		$this->render('invoices/edit');
	}
	
	/**
	 * List invoices
	 */
	function index()
	{
		$errors = array();
		
		if (!user_can('view', 'invoices')) access_error();
		
		// prepare to send invoices
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->input->post('check')) {

				$ids = $this->input->post('check');
				
				// prepare invoicees for sending
				if ($this->input->post('prepare')) {
					if ($ids) {
						$ids = implode(",", $ids);
						redirect('invoices/confirm/?selected=' . $ids);
					}
				}
				
				// batch processing
				if ($this->input->post('apply')) {
					foreach($ids as $id) {
						$invoice = new Invoice();
						$invoice->where('id', $id)->get();
						$invoice->status = $this->input->post('batch');
						$invoice->save();
					}	
					
					$this->session->set_flashdata('success', 'Invoices have been updated.');
					redirect('invoices');
				}
				
			}	
		}
				
		// setup
		$invoiceFactory = new Invoice();
		$futureInvoiceFactory = new Invoice();
                
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$invoiceFactory->start_cache();
		$futureInvoiceFactory->start_cache();
		
                $futureInvoiceFactory->where('send_date >', date("Y-m-d 00:00:00"));
                $futureInvoiceFactory->where('status', 'Unsent');
                
		// project
		$project_id = $this->input->get('project');
		if ($project_id) {
			$invoiceFactory->where_related('project', 'id', $project_id);
			$futureInvoiceFactory->where_related('project', 'id', $project_id);
		}
		
		// status
		$status = $this->input->get('status');
		if ($status) {
			$invoiceFactory->where('status', $status);
			$futureInvoiceFactory->where('status', $status);
		}
		
		// batch
		$batch = $this->input->get('batch');
		if ($batch) {
			$invoiceFactory->where('batch', $batch);
			$futureInvoiceFactory->where('batch', $batch);
		}
		
		// company name
		$company_name = $this->input->get('company');
		$company_name = urldecode($company_name);
		if ($company_name) {
			$invoiceFactory->where_related('company', 'name', $company_name);
			$futureInvoiceFactory->where_related('company', 'name', $company_name);
		}
		
		if ($this->current_company) {
			$invoiceFactory->where_related('company', 'id', $this->current_company->id);
			$futureInvoiceFactory->where_related('company', 'id', $this->current_company->id);
		}
		
		// order field
		$orderby = $this->input->get('orderby');
		if (!$orderby) $orderby = 'invoice_number';
		
		// direction
		$order = $this->input->get('order');
		if (!$order) $order = 'DESC';
		
		$count = $invoiceFactory->count();
		$invoices = $invoiceFactory->order_by($orderby, $order)->get($limit, $offset)->all;
		$future_invoices = $futureInvoiceFactory->order_by($orderby, $order)->get()->all;
		$invoiceFactory->stop_cache();
		$futureInvoiceFactory->stop_cache();
		
		// companies
		$companies = new Company();
		$companies->order_by('name')->get();
		
		// projects
		$projects = new Project();
		$projects->order_by('project_number', 'desc')->order_by('start_date', 'desc')->get();
		
		// filters
		$filter = new stdClass();
		
		// -- company
		$filterCompany = new Company();
		if ($company_name) $filterCompany->where('name', $company_name)->get();
		$filter->company = $filterCompany;
		
		// -- project
		$filterProject = new Project();
		if ($project_id) $filterProject->where('id', $project_id)->get();
		$filter->project = $filterProject;
		
		// pagination
		$config['base_url'] = site_url('invoices/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 
               
		// render
		$this->data['companies'] = $companies;
		$this->data['projects'] = $projects;
		$this->data['filter'] = $filter;
		$this->data['errors'] = $errors;
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['invoices'] = $invoices;
		$this->data['future_invoices'] = $future_invoices;
		$this->data['title'] = 'Invoices';
		$this->render('invoices/list');
	}
	
	/**
	 * Send an invoice
	 */
	function send($id) {
            $invoice = new Invoice();
            $invoice->where('id', $id)->get();
            $this->authorize($invoice->company->id);
            
            if (!$invoice->id) return 'Invoice ID ' . $id . ' not found.';

            return $invoice->send();
	}
	
	/**
	 * Test function to view email template
	 */
	function view_email() {
		$this->load->view('templates/email', $this->data);
	}
	
	/**
	 * View invoice by invoice #
	 */
	function number($id) {
		$invoice = new Invoice();
		$invoice->where('invoice_number', $id)->get();
		
		if ($invoice->id) {
			$this->view($invoice->id);
		} else {
			$this->session->set_flashdata('error', 'Invoice not found.');
			redirect('invoices/');
		}
	}
	
	/**
	 * View invoice by ID
	 */
	function view($id) {
		
		if (!user_can('view', 'invoices')) access_error();
		
		$invoice = new Invoice();
		$invoice->where('id', $id)->get();
		
		if (!$invoice->id) redirect('invoices/');
		$this->authorize($invoice->company->id);
		
		$invoice->company->get();
		$invoice->project->get();
				
		$setting = new Setting();
		
		// paypal email
		$setting->where('name', 'paypal_email');
		$paypal_email = $setting->get()->value;
		
		// paypal ipn
		$setting->where('name', 'paypal_ipn');
		$paypal_ipn = $setting->get()->value;
		
		// paypal url
		$setting->where('name', 'paypal_testmode');
		$test_mode = $setting->get()->value;
		
		
		// payable terms
		$setting->where('name', 'payable_terms');
		$payable_terms = $setting->get()->value;
		
		
		// footer text
		$setting->where('name', 'footer_text');
		$footer_text = $setting->get()->value;
		
		if ($test_mode) {
			$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
		}
		
		// render template
		$timelogs = null;
		if ($invoice->timelog) {
			$timelogs = $invoice->timelog->get()->all;
		}
		$this->data['timelogs'] = $timelogs;
		$this->data['paypal_email'] = $paypal_email;
		$this->data['paypal_url'] = $paypal_url;
		$this->data['paypal_test'] = $test_mode;
		$this->data['payable_terms'] = $payable_terms;
		$this->data['footer_text'] = $footer_text;
		$this->data['paypal_ipn'] = $paypal_ipn;
		$this->data['invoice'] = $invoice;
		$this->data['line_items'] = $invoice->getLineItemsAsArray($this->input);
		$this->data['title'] = 'Invoice #' . $invoice->invoice_number;
		$this->render('invoices/view');
	}
	
	
}
