<?php

require('markdownify/markdownify_extra.php');
			

class Run extends Controller {

	function index()
	{
		$dsn = 'mysqli://root:R1ddl3br0s@localhost/old_projects';
		$OLDDB = $this->load->database($dsn, TRUE);
		
		$dsn = 'mysqli://root:R1ddl3br0s@localhost/projectmanager';
		$NEWDB = $this->load->database($dsn, TRUE);
	
		// initialize
		$NEWDB->query("TRUNCATE companies");
		$NEWDB->query("TRUNCATE companies_contacts");
		$NEWDB->query("TRUNCATE companies_estimates");
		$NEWDB->query("TRUNCATE companies_invoices");
		$NEWDB->query("TRUNCATE companies_projects");
		$NEWDB->query("TRUNCATE contacts");
		$NEWDB->query("TRUNCATE contacts_projects");
		$NEWDB->query("TRUNCATE costitems");
		$NEWDB->query("TRUNCATE costitems_estimates");
		$NEWDB->query("TRUNCATE estimates");
		$NEWDB->query("TRUNCATE estimates_estimateterms");
		$NEWDB->query("TRUNCATE estimates_invoices");
		$NEWDB->query("TRUNCATE estimates_projects");
		$NEWDB->query("TRUNCATE estimateterms");
		$NEWDB->query("TRUNCATE invoices");
		$NEWDB->query("TRUNCATE invoices_lineitems");
		$NEWDB->query("TRUNCATE invoices_projects");
		$NEWDB->query("TRUNCATE invoices_transactions");
		$NEWDB->query("TRUNCATE lineitems");
		$NEWDB->query("TRUNCATE projects");
		$NEWDB->query("TRUNCATE projects_tasks");
		$NEWDB->query("TRUNCATE tasks");
		$NEWDB->query("TRUNCATE tasks_timelogs");
		$NEWDB->query("TRUNCATE tasks_users");
		$NEWDB->query("TRUNCATE timelogs");
		$NEWDB->query("TRUNCATE timelogs_users");
		$NEWDB->query("TRUNCATE transactions");
		$NEWDB->query("TRUNCATE users");
		
		// import users
		$query = $OLDDB->from('users')->join('contacts', 'users.user_id = contacts.contact_id')->get();
		$users = array();
		foreach($query->result() as $user) {
			echo 'Saving ' . $user->user_username . '<br />';
			$new_user = new User();
			$new_user->name = $user->contact_first_name . ' ' . $user->contact_last_name;
			$new_user->username = $user->user_username;
			$new_user->password = 'password';
			$new_user->email = $user->contact_email;
			$new_user->save();
			$users[$user->user_id] = $new_user;
			echo $new_user->error->string;
		}
		// the $users array now has an index of the old user ID for reference
		
		// import companies
		$query = $OLDDB->from('companies')->get();
		$companies = array();
		foreach($query->result() as $company) {
			echo 'Saving ' . $company->company_name . '<br />';
			$new_company = new Company();
			$new_company->name = $company->company_name;
			$new_company->phone = $company->company_phone1;
			$new_company->fax = $company->company_fax;
			$new_company->address = $company->company_address1;
			$new_company->city = $company->company_city;
			$new_company->state = $company->company_state;
			$new_company->zip = $company->company_zip;
			$new_company->website = $company->company_primary_url;
			$new_company->save();
			$companies[$company->company_id] = $new_company;
			echo $new_company->error->string;
		}
		// the $companies array now has an index of the old company ID for reference
		
		
		// import contacts
		$query = $OLDDB->from('contacts')->get();
		$contacts = array();
		foreach($query->result() as $contact) {
			echo 'Saving ' . $contact->contact_first_name . ' ' . $contact->contact_last_name . '<br />';
			
			$associations = array();
			
			$new_contact = new Contact();
			$new_contact->name = $contact->contact_first_name . ' ' . $contact->contact_last_name;
			$new_contact->first_name = $contact->contact_first_name;
			$new_contact->last_name = $contact->contact_last_name;
			$new_contact->email = $contact->contact_email;
			$new_contact->phone = $contact->contact_phone;
			$new_contact->fax = $contact->contact_fax;
			$new_contact->address = $contact->contact_address1;
			$new_contact->city = $contact->contact_city;
			$new_contact->state = $contact->contact_city;
			$new_contact->zip = $contact->contact_zip;
			$new_contact->website = $contact->contact_url;
			
			if ($contact->contact_company) {
				$company = $companies[$contact->contact_company];
				$associations[] = $company;
			}
			$new_contact->save($associations);
			$contacts[$contact->contact_id] = $new_contact;
			echo $new_contact->error->string;
		}
		
		
		// import projects
		$query = $OLDDB->from('projects')->get();
		$projects = array();
		foreach($query->result() as $project) {
			echo 'Saving ' . $project->project_name . '<br />';
			$associations = array();
			
			$new_project = new Project();
			$new_project->name = $project->project_name;
			$new_project->project_number = $project->project_id;
			$new_project->website = $project->project_url;
			$new_project->start_date = $project->project_start_date;
			$new_project->end_date = $project->project_end_date;
			$new_project->description = $project->project_description;
			$new_project->status = "Approved";
			$company = $companies[$project->project_company];
			$associations[] = $company;
			
			// get project contacts
			$project_contacts = $OLDDB->from('project_contacts')->where('project_id', $project->project_id)->get();
			foreach($project_contacts->result() as $p) {
				if (isset($contacts[$p->contact_id])) {
					$pc = $contacts[$p->contact_id];
					$associations[] = $pc;
				}
			}
			
			$new_project->save($associations);
			$projects[$project->project_id] = $new_project;
			echo $new_project->error->string;
		}
		// the $projects array now has an index of the old project ID for reference
		
		// import estimates
		$query = $OLDDB->from('estimate')->get();
		$estimates = array();
		
		$m = new Markdownify_Extra(false, MDFY_BODYWIDTH, true);
		
		foreach($query->result() as $estimate) {
			$associations = array();
			echo 'Saving ' . $estimate->estimate_project . '<br />';
			$new_estimate = new Estimate();
			$new_estimate->name = $estimate->estimate_project;
			$new_estimate->estimate_date = $estimate->estimate_date;
			$new_estimate->estimate_number = $estimate->estimate_number;
			
			$status = '';
			switch($estimate->estimate_status) {
				case '0':
					$status = 'Pending Approval';
					break;
				case '1';
					$status = 'Approved';
					break;
				case '2';
					$status = 'Rejected';
					break;
			}
			$new_estimate->status = $status;
			$new_estimate->content = $estimate->estimate_html;
			$new_estimate->custom_terms = $estimate->estimate_terms;
			$new_estimate->term_type = 'custom';
			$new_estimate->signature = $estimate->estimate_signature;
			$new_estimate->signature_ip = $estimate->estimate_signatureip;
			$new_estimate->signature_date = $estimate->estimate_signaturedate;
			$new_estimate->send_email_recipient = $estimate->estimate_email;
			$new_estimate->send_email_notification = true;
			$new_estimate->generate_invoice_request = false;
			
			$new_estimate->content = $m->parseString($new_estimate->content);
			$new_estimate->custom_terms = $m->parseString($new_estimate->custom_terms);
			
			if ($estimate->estimate_projectid) {
				$project = $projects[$estimate->estimate_projectid];
				$associations[] = $project;
			}
			if ($estimate->estimate_company) {
				$company = $companies[$estimate->estimate_company];
				$associations[] = $company;
			} else {
				// get a company with matching name, or create new if not existant
				$company = new Company();
				$company = $company->where('name', $estimate->estimate_client)->get();
				if (!$company->id) {
					echo 'Creating new company for this estimate..<br />';
					$company = new Company();
					$company->name = $estimate->estimate_client;
					$company->save();
					echo $company->error->string;
				}
				$associations[] = $company;
			}
			
			$new_estimate->save($associations);
				
			$estimates[$estimate->estimate_id] = $new_estimate;
			echo $new_estimate->error->string;
		}
		// the $estimates array now has an index of the old estimate ID for reference
		
		// save estimate items
		$query = $OLDDB->from('estimate_items')->orderby('estimate_item_id')->get();
		$cost_items = array();
				

		foreach($query->result() as $item) {
			echo 'Saving estimate cost item ' . $item->estimate_item_id . '<br />';
			
			$new_item = new Costitem();
			$new_item->description = $item->item_description;
			$new_item->amount = $item->item_amount;
			$new_item->heading = $item->item_header;
			$new_item->item_type = (empty($item->item_header) ? 'price' : 'heading');
			$estimate = $estimates[$item->estimate_id];
			$new_item->save($estimate);
			
			$cost_items[] = $new_item;
			echo 'Finishes saving new ID ' . $new_item->id . '<br />';
			echo $new_item->error->string;

		}
		// the $cost_items array now has an idnex of the old items ID for reference
		
		// import tasks
		$query = $OLDDB->from('tasks')->get();
		$tasks = array();
		foreach($query->result() as $task) {
			echo 'Saving task ' . $task->task_name . '<br />';
			$new_task = new Task();
			
			$new_task->name = $task->task_name;
			$new_task->created_date = $task->task_start_date;
			$new_task->due_date = $task->task_end_date;
			$new_task->description = $task->task_description;
			
			$status = '';
			switch($task->task_status) {
				case '0':
					$status = 'In Progress';
					break;
				case '-1';
					$status = 'Complete';
					break;
				case '-2';
					$status = 'Closed';
					break;
			}
			$new_task->status = $status;
			
			$associations = array();
			
			if ($task->task_project) {
				$project = $projects[$task->task_project];
				$associations[] = $project;
			}
			if ($task->task_owner) {
				$user = $users[$task->task_owner];
				$associations[] = $user;
			}

			$new_task->save($associations);
			
			$tasks[$task->task_id] = $new_task;
			echo $new_task->error->string;
			
		}
		// the $tasks array now has an index of the old task ID for reference
		
		// import task logs/timelogs
		$query = $OLDDB->from('task_log')->get();
		$task_logs = array();
		foreach($query->result() as $log) {
			echo 'Saving log ' . $log->task_log_name . '<br />';
			
			if (!isset($users[$log->task_log_creator])) {
				echo 'Could not create task - log creator user is not defined.';
				continue;
			}
			if (!isset($tasks[$log->task_log_task])) {
				echo 'Could not create task log - task not defined for log.';
				continue;
			}
			
			$new_timelog = new Timelog();
			
			$new_timelog->description = $log->task_log_description . '<br />';
			$new_timelog->hours = $log->task_log_hours;
			$new_timelog->log_date = $log->task_log_date;
			
			$associations = array();
			
			if ($log->task_log_task) {
				$task = $tasks[$log->task_log_task];
				$associations[] = $task;
			}
			if ($log->task_log_creator) {
				$user = $users[$log->task_log_creator];
				$associations[] = $user;
			}

			$new_timelog->save($associations);
			
			$task_logs[$task->task_id] = $new_task;
			echo $new_timelog->error->string;
			
		}
		// the $tasks array now has an index of the old task ID for reference
	
		// the $tasks array now has an index of the old task ID for reference
		
		// import invoices - these were imported into the old_projects table
		// via an excel import from quickbooks online, then converted to .csv
		// and imported with Sequel Pro's csv import feature
		$query = $OLDDB->from('imported_invoices')->get();
		$invoices = array();
		foreach($query->result() as $invoice) {
			echo 'Saving invoice ' . $invoice->invoice_number . '<br />';
			$associations = array();
			$new_invoice = new Invoice();
			$new_invoice->invoice_number = $invoice->invoice_number;
			$new_invoice->invoice_date = date("Y-m-d 00:00:00", strtotime($invoice->invoice_date));
			$new_invoice->due_date = date("Y-m-d 00:00:00", strtotime($invoice->due_date));
			$new_invoice->amount = $invoice->amount;
			$new_invoice->terms = $invoice->terms;
			
			// company
			$company = new Company();
			$company = $company->where('name', $invoice->company)->get();
			if (!$company->id) {
				$company->name = $invoice->company;
				$company->save();
			}
			$associations[] = $company;
			
			$new_invoice->memo = ($invoice->memo ? $invoice->memo . ' -- IMPORTED' : 'IMPORTED');
			$new_invoice->status = $invoice->status;
			if (stripos($invoice->memo, 'void') !== false) {
				$new_invoice->status = 'Void';
			}
			$new_invoice->bill_to = $invoice->address;
			$new_invoice->message = $invoice->message;
			
			// project
			$project = new Project();
			echo 'Looking for project # ' . $invoice->project_number . '<br />';
			$yr = '20' . substr($invoice->project_number, 0, 2);
			$short_pn = substr($invoice->project_number, 7, 3);
			echo 'Short PN: ' . $short_pn . ' with year ' . $yr . '<br />';
			$project = $project->where('project_number', $short_pn)->where('YEAR(start_date)', $yr)->get();
			if ($project->id) {
				echo 'Found matching project ' . $invoice->project_number . ' : ' . $project->name . '<br />';
				$associations[] = $project;
			}
			
			// add single line item with amount
			$line = new Lineitem();
			if ($project->id) {
				$line->description = "Invoice for " . $project->name . ' (' . $invoice->project_number . ')';
			} else {
				$line->description = "Client invoice for month of " . date("F, Y", strtotime($invoice->invoice_date));
			}
			$new_invoice->description = $line->description;
			$line->amount = $invoice->amount;
			$line->save();
			echo $line->error->string . '<br />';
			$associations[] = $line;
			
			$new_invoice->save($associations);
			echo $new_timelog->error->string;
			
				
		}

	}
	
}
