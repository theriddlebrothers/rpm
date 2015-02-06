<?php

class Issues extends Admin_Controller {

	public function Issues() {
		parent::Admin_Controller();
	}
	
	function ajax($action) {
		
	}
	
	function create($project_id) 
	{
		if (!user_can('create', 'issues')) access_error();
		if (!$project_id) redirect('/');
		$this->edit(null, $project_id);
	}
	
	function delete($id) {
		if (!user_can('delete', 'issues')) access_error();
		$issueFactory = new Issue();
		$issue = $issueFactory->where('id', $id)->get();
		$this->session->set_flashdata('success', $issue->name . ' was deleted.');
		$issue->delete();
		redirect('issues/');
	}
	
	function edit($id, $project_id=null) {
		if (!user_can('edit', 'issues')) access_error();
		
		// setup
		$this->data['errors'] = null;
		$issueFactory = new Issue();
		$issue = new Issue();
		if ($id != null) {
			$issue = $issueFactory->where('id', $id);
			
			if ($this->user->role != ROLE_ADMINISTRATOR) {
				// don't list hidden items
				$issue->where('visibility !=', 'Hidden');
			}
			
			$issue->get();
			
			if (!$issue->exists()) {
				redirect('issues/');
			}
			
			$issue->reporter->get();
			$issue->assignee->get();
		}
		
		if ($project_id) {
			$project = new Project();
			$project->where('id', $project_id)->get();
		} else {
			$project = $issue->project->get();
		}
		
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($issue);
			
			$associations = array();
			
			// project
			if ($project_id) {
				// new item, associate with project
				$associations['project'] = $project;
			}
			
			// reported by
			if ($this->input->post('reporter')) {
				$reporter = new User();
				$reporter->where('id', $this->input->post('reporter'))->get();
				if ($reporter) $associations['reporter'] = $reporter;
			} else {
				// remove existing associations
				$issue->delete_reporter($issue->reporter->all);
				$issue->refresh_all();
			}

			// assigned to
			if ($this->input->post('assignee')) {
				$assignee = new User();
				$assignee->where('id', $this->input->post('assignee'))->get();
				if ($assignee) $associations['assignee'] = $assignee;
			} else {
				// remove existing associations
				$issue->delete_assignee($issue->assignee->all);
				$issue->refresh_all();
			}
			
			// dates
			if ($issue->date_reported) $issue->date_reported = date("Y-m-d 00:00:00", strtotime($issue->date_reported));
			else $issue->date_reported = null;
			
			if ($issue->date_due) $issue->date_due = date("Y-m-d 00:00:00", strtotime($issue->date_due));
			else $issue->date_due = null;
			
			// save
			if ($issue->save($associations)) {
				
				$issue->refresh_all();
				
				// send notifications
                                if ($this->input->post("notify")) {
                                    $is_new = ($project_id != null);
                                    $this->send_notification($issue, $is_new);
                                }
                                
				$this->session->set_flashdata('success', $issue->title . ' was saved.');
				redirect('issues/index/' . $issue->project->id);
			
			} else {
				// invalid
				$this->data['errors'] = $issue->error->string;
			}		
		} else {
			// defaults
			$issue->date_reported = date("m/d/Y");
			foreach($project->users as $u) {
				if ($u->role == 'Client') {
					$issue->reporter = $u;
				}
			}
		}
		
		// categories
		$this->data['categories'] = $issue->lists['categories'];
		
		// priorities
		$this->data['priorities'] = $issue->lists['priorities'];
		
		// statuses
		$this->data['statuses'] = $issue->lists['statuses'];
		
		// users
		$users = array(
			''	=>	'Select a user...',
		);
		// get users on this project
		$project_users = $project->users;
		if ($project_users) {
			foreach($project_users as $u) {
				$users[$u->id] = $u->name;
			}
		}
		$this->data['users'] = $users;
		
		// browsers
		$this->data['browsers'] = $issue->lists['browsers'];
				
		// operating systems
		$this->data['operating_systems'] = $issue->lists['operating_systems'];
				
		// visibilities
		$this->data['visibilities'] = $issue->lists['visibilities'];
				
		// render template
		$this->data['states'] = $this->config->item('states');
		$this->data['issue'] = $issue;
		if (!$issue->id) $this->data['title'] = 'Create Issue';
		else $this->data['title'] = 'Edit Issue #' . $issue->id;
		$this->render('issues/edit');
	}
	
	function index($project_id=null)
	{
		if (!user_can('view', 'issues')) access_error();
			
		// setup
		$issue = new Issue();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$issue->start_cache();
		
		$project = null;
		if ($project_id) {
			$issue->where_related('project', 'id', $project_id);
			
			$project = new Project();
			$project->where('id', $project_id)->get();
		}
		
		if (!$this->input->get('show_closed')) {
			$issue->where('status !=', 'Closed');
			$issue->where('status !=', 'Inactive');
		}
		
		if ($this->user && $this->user->role != ROLE_ADMINISTRATOR) {
			// don't list hidden items
			$issue->where('visibility !=', 'Hidden');
		}
		
		if ($this->current_company) {
			$issue->where_related('project/company', 'id', $this->current_company->id);
		}
		
		$count = $issue->count();
		$issues = $issue->order_by('priority', 'asc')->order_by('status', 'asc')->order_by('date_reported', 'desc')->get($limit, $offset)->all;
		$issue->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('issues/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config); 

		// render
		$this->data['project'] = $project;
		$this->data['issues'] = $issues;
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['title'] = 'Issues';
		$this->render('issues/list');
	}


	private function send_comment_notification($issue, $comment) {
		
		$hidden = ($issue->visibility == 'Hidden');
		
		$recipients = array();
		foreach($issue->project->user->all as $user) {
			if ($user->email) {
				// don't email clients if this is hidden
				if (!$hidden || $user->role != ROLE_CLIENT) {
					$recipients[] = $user->email;
				}
			}
		}
		
		if (!$recipients) return false;
		$recipients = implode(',', $recipients);
		
		
		$link_url = $issue->project->company->getViewLink('/cp/issues/view/' . $issue->id . '#comment-' . $comment->id);
		$link_text = 'View Comment';
		
		$message = '<p><strong>' . $this->user->name . '</strong> said:</p>' .
					markdownify($comment->comment) . '
					<p>To view this comment, please click the link below:</p>';
		
		
		// load message
		$email = new Brandedemail();
		$email->subject = $this->user->name . ' has made a comment on Issue Report #' . $issue->id;
		$email->body = $message;
		$email->to = $recipients;
		$email->link_url = $link_url;
		$email->link_text = $link_text;
		$email->send();
				
		return true;

	}
	
	private function send_notification($issue, $is_new=true) {
            
		$hidden = ($issue->visibility == 'Hidden');
		
		$recipients = array();
		foreach($issue->project->user->all as $user) {
			if ($user->email) {
				// don't email clients if this is hidden
				if (!$hidden || $user->role != ROLE_CLIENT) {
					$recipients[] = $user->email;
				}
			}
		}
		
		if (!$recipients) return false;
		$recipients = implode(',', $recipients);
		
		
		$link_url = $issue->project->company->getViewLink('/cp/issues/view/' . $issue->id);
		$link_text = 'View Issue Report';
		
		$message = '<p><strong>' . $issue->title . '</strong></p>';
		if ($issue->assignee->exists()) {
			$message .= '<p><em>Assigned To:</em> ' . $issue->assignee->name . '</p>';
		}
		$message .= '<p>' . $issue->description . '</p>
					
					<p>To view this issue, please click the link below:</p>';
		
		
		// load message
		$email = new Brandedemail();
		if ($is_new) {
                    $email->subject = 'A New Issue Has Been Submitted: Issue Report #' . $issue->id;
                } else {
                    $email->subject = 'Issue Report #' . $issue->id . ' has been updated.';
                }
		$email->body = $message;
		$email->to = $recipients;
		$email->link_url = $link_url;
		$email->link_text = $link_text;
		$email->send();
				
		return true;
		
	}
	
	function view($id) {
		if (!user_can('view', 'issues')) access_error();
		
		$issue = new Issue();
		$issue->where('id', $id);
		
		if ($this->user->role != ROLE_ADMINISTRATOR) {
			// don't list hidden items
			$issue->where('visibility !=', 'Hidden');
		}
		
		$issue->get();
		
		if (!$issue->exists()) {
			redirect('issues/');
		}
		
		if (!$issue) redirect('issues');
		
		$this->authorize($issue->project->company->id);
		
		// post comment
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->input->post('comment')) {
				$comment = new Comment();
				$comment->comment_date = date("Y-m-d H:i:s");
				$comment->comment = $this->input->post('comment');
				$comment_associations = array($issue, $this->user);
				// save
				if ($comment->save($comment_associations)) {
					$comment->refresh_all();
					if ($this->input->post('notify')) {
                                            $this->send_comment_notification($issue, $comment);
                                        }
					$this->session->set_flashdata('success', 'Comment was added.');
					redirect('issues/view/' . $issue->id);
				} else {
					// invalid
					$this->data['errors'] = $issue->error->string;
				}		

			}
		}
		
		// render template
		$this->data['issue'] = $issue;
		$this->data['comments'] = $issue->comment->order_by('comment_date' ,'asc')->get()->all;
		$this->data['title'] = 'Issue #' . $issue->id . ' - ' . $issue->title;
		$this->render('issues/view');
	}
	
}
