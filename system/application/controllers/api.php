<?php

class Api extends Admin_Controller {

	public function Api() {
		parent::Admin_Controller();
	}
	
	private function initRequest() {
		
		if (!$this->checkAuth()) {
			die("Invalid authorization.");
		}
		$this->initHeaders();
	}
	
	private function initHeaders() {
				
		$this->output->set_header('Access-Control-Allow-Origin: *');
		//$this->output->set_header('Access-Control-Allow-Origin: http://example.com:8080 http://foo.example.com');
	
		$this->output->set_header('Content-Type:application/json');
	}
	
	
	function get($type, $id = null) {
	
		$isSingle = is_numeric($id);
		
		// Block access to invoicing data
		$table_access = array(
			'invoice'		=>	array('Administrator'),
			'costitem'		=>	array('Administrator'),
			'estimate'		=>	array('Administrator'),
			'transaction'	=>	array('Administrator')
		);
		
		if (array_key_exists($type, $table_access)) {
			$allowed_roles = $table_access[$type];
		
			if (!in_array($this->user->role, $allowed_roles)) {
				die("Access denied for table.");
			}
		}
	
	
		$limit = 100;
		$offset = 0;
	
		$this->db->start_cache();
		$ending = substr($type, -1);
		if ($ending == 'y') {
			$len = strlen($type);
			$base = substr($type, 0, $len-1);
			$table_name = $base . 'ies';
		} else {
			$table_name = $type . 's';
		}
		
		$query = new $type();
		
		$params = $this->getParams();
	    
		if ($params) {
			foreach($params as $key=>$val) {
				
				if (empty($val) || $val == 'null' || $val == 'undefined') continue;
					
				// if parameter contains slash, it means we need the related
				// object's parameter
				$is_related = (strpos($key, '/') !== false);
				if ($is_related) {
				
					//'task/project'
					$path = explode("/", $key);
					
					// get the property we are searching against
					$property_path = $path[count($path)-1];
					$properties = explode(".", $property_path);
					$related_property = $properties[1];
					
					// remove the property name from the path
					$pos = strpos($key, ".");
					$related_path = substr($key, 0, $pos);
					
					// In some situations where we need the direct child property
					// (i.e. the project related to a task) we need to specify
					// project.id, but in order to send it we have to use a path such
					// as project/project.id. This leaves a related path of project/project
					// which returns 0 results. So, if the related_path is
					// project/project, just change it to "project".
					$ps = explode("/", $related_path);
					if (count($ps) > 1 && $ps[0] == $ps[1]) $related_path = $ps[0];
					
					$query->where_related($related_path, $related_property, $val);
				} else {
					switch($key) {
						case 'token':
							// Authentication token for credential validation. Ignore.
							break;
						case '_':
							// jQuery no-cache parameter. Ignore.
							break;
						case 'sortasc':
							$query = $query->order_by($val, 'asc');
							break;
						case 'sortdesc':
							$query = $query->order_by($val, 'desc');
							break;
						case 'limit':
							$l = explode(",", $val);
							if (count($l) > 1) {
								$limit = $l[0];
								$offset = $l[1];
							} else {
								$limit = $l[0];
							}
							break;
						default:
							$query = $query->where($key, $val);
						break;
					}
				}
			}
		}
		
		if (!$isSingle) {
			// if $id is null, we are retrieving a list of objects
			$resp = array();
			$query = $query->get($limit, $offset);
			foreach ($query as $row) {
			    $ob = new $type($row->stored);
			    $resp[] = $ob->getPlainObject();
			}
		} else {
			// if $id is NOT null, we are retrieving a single object
			$data  = $query->where($table_name . '.id', $id)->get();
			$ob = new $type($data);
			$resp = $ob->getPlainObject();
		}
		
		$this->db->stop_cache();
		
		$this->initRequest();
		$this->output->set_output(json_encode($resp));
	}
	
	
	function timelog($action) {
		$this->initRequest();
		$params = $this->getParams();
		
		// Set defaults (overwritten by valid action responses).
		$resp = new stdClass();
		$resp->message = "Request action not found.";
		$resp->success = false;
		
		switch($action) {
			case 'edit':
				$resp = $this->_editTimelog($params);
				break;
			case 'delete':
				$resp = $this->_deleteTimelog($params);
				break;
		}
		
		$this->output->set_output(json_encode($resp));
	}
	
	private function _deleteTimelog() {
		if (!user_can('delete', 'timelogs')) die("No access");
		
		$params = $this->getParams();
		$id = $this->input->post('id');
		
		$timelogFactory = new Timelog();
		$timelog = $timelogFactory->where('id', $id)->get();
		
		if ($timelog->user->id != $this->user->id) {
			access_error();
		}
		
		$timelog->delete();
		
		$resp = new stdClass();
		$resp->success = true;
		$resp->message = "Timelog was deleted.";
		return $resp;
	}
	
	private function _editTimelog() {
		if (!user_can('edit', 'timelogs')) die("No access");
		
		$id = $this->input->post('id');
		
		// setup
		$this->data['errors'] = null;
		$timelogFactory = new Timelog();
		$timelog = new Timelog();	
			
		if ($id != null) {
			$timelog = $timelogFactory->where('id', $id)->get();
			$timelog->user->get();
			
			if ($timelog->user->id != $this->user->id) {
				access_error();
			}
		} 
		
		$resp = new stdClass();
				
		if ($timelog->saveTimelog()) {
			// save
			$resp->message = 'Timelog for ' . date("m/d/Y", strtotime($timelog->log_date)) . ' was saved.';
			$resp->success = true;
		} else {
			// invalid
			$resp->message = $timelog->error->string;
			$resp->success = true;
		}	
		
		return $resp;
	}
	
	
		
	function authenticate() {
		$this->initHeaders();
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$user = new User();
			$user->username = $this->input->post('username');
			$user->password = $this->input->post('password');
			
			$resp = new stdClass();
			
			if ($user->login()) {
				// success!
				$user->where('username', $this->input->post('username'))->get();
				$user->oauth_token = $user->generateToken();
					
				// store session/token
				$user->save();
				
				$this->session->set_userdata('user_id', $user->id);
				
				$resp->success = true;
				$resp->message = "";
				$resp->id = $user->id;
				$resp->token = $user->oauth_token;
				$resp->name = $user->name;
				$resp->email = $user->email;
				$resp->role = $user->role;
				
			} else {
				// login failed
				$resp->success = false;
				$resp->message = "<p>Invalid username/password.</p>";
				$resp->token = null;
			}
			
			$this->output->set_output(json_encode($resp));
		}
	}
	
	// See if user is already logged in.
	function doauth() {
		$this->initHeaders();
		$resp = new stdClass();
		$resp->success = false;
		if ($this->checkauth()) {
			$resp->success = true;
		}
		
		$this->output->set_output(json_encode($resp));
	}
	
	function checkauth() {
		
		$resp = new stdClass();
		$token = $this->input->get_post('token');
		
		$user = new User();
		$user->where('oauth_token', $token)->get();
		
		return $user->exists();
		/*if ($user->exists()) {
			$resp->success = true;
		} else {
			$resp->success = false;
		}
		
		$this->output->set_output(json_encode($resp));*/
	}
	
	function logtime() {
		$this->initRequest();
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$timelog = new Timelog();
			$task_id = $this->input->post('task_id');
			$timelog->description = $this->input->post('description');
			
			// format hours
			$timelog->hours = $this->input->post('hours');
			
			if ($this->input->post('log_date')) {
				$end = strtotime($this->input->post('log_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$timelog->log_date = $end;
			}
			
			// task association
			$task = null;
			if ($this->input->post('task_id')) {
				$task = new Task();
				$task = $task->where('id', $this->input->post('task_id'))->get();
				if ($task->id) {
					$task_text = $task->project . ': ' . $task->name;
					$associations[] = $task;
				}
			} else {
				// remove association if it existed
				if ($timelog->task) {
					$timelog->delete($timelog->task);
				}
			}
			
			// user association
			$user = new User();
			$user = $user->where('oauth_token', $this->input->post('token'))->get();
			$associations[] = $user;
					
					
			$resp = new stdClass();
				
			if ($timelog->save($associations)) {
				$resp->success = true;
				$resp->message = 'Timelog for ' . date("m/d/Y", strtotime($timelog->log_date)) . ' was saved.';
			} else {
				// invalid
				$resp->success = false;
				$resp->message =  $timelog->error->string;
			}		
			
			$this->output->set_output(json_encode($resp));
						
		}
	}
	
	
}
