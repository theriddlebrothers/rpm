<?php

require('Admin_Controller.php');
require('Public_Controller.php');
require('Embed_Controller.php');

class RB_Controller extends Controller {

	// view data
	protected $data = array();
	
	// directory where templates for this controller will live.
	// allows for views in /cp/company/list.php to be called as $this->render('company/list').
	protected $directory = '';
	
	// template to use
	protected $template = 'cp';
	
	// current user data
	protected $user;
	
	// hard coded controller role
	public $current_company = null;
	
	public function RB_Controller() {
		parent::Controller();	
	}
	
	/**
	 * Checks for user authorization cookie from "remember me" checkbox
	 *
	 * If user series/token is valid, user session is created.
	 */
	public function checkAuthorizationCookie() {
				
		// if no user ID is set, check for persistent login cookie
		if (!$this->session->userdata('user_id')) {
			$cookie = get_cookie('auth');
			if ($cookie) {
			
				list($series, $token) = explode(':', $cookie);
				$user = new User();
				$user->where('series', $series)->get();
				
				if ($user->exists() && ($user->token == $token)) {
				
					// user has been authenticated. create session and generate new token
					$cookie = $user->generateCookie($series);
					set_cookie($cookie);
					
					// store session/token
					$user->save();
					$this->session->set_userdata('user_id', $user->id);
				} elseif($user->exists() && ($user->token != $token)) {
					// hijacked cookie, clear everything
					$user->token = null;
					$user->series = null;
					$user->save();
					$this->session->sess_destroy();
					delete_cookie('auth');
				}
			}
		}
		
	}
	
	
	// If any parameters were passed. Cannot use $_GET because some parameters
	// must contain periods (which PHP automatically converts to underscores)
	// Also since Endeavor/Discovery servers don't recognize theq eruy string, we have
	// to retrieve it directly from the request URI
	protected function getParams() {
		$url = $_SERVER['REQUEST_URI'];
		$querystring = substr($url, strpos($url, "?") + 1, strlen($url) - strpos($url, "?"));

		$params = array();
		foreach(explode('&', $querystring) as $part) {
		    $part = explode('=', $part);
		    if($key = array_shift($part)) {
		        $params[ urldecode($key) ] = urldecode(implode('', $part));
	        }
	    }
	    
	    return $params;
	}
	
	public function getTemplate() {
		return $this->template;
	}
	
	public function render($template, $return=false) {
		$templateDirectory = $this->directory . $template;
		if (!isset($this->data['title'])) $this->data['page_title'] = 'RPM';
		else $this->data['page_title'] = $this->data['title'] . ' | RPM';
		$this->data['current_company'] = $this->current_company;
		$this->data['current_user'] = $this->user;
		$this->data['message'] = $this->load->view('shared/message', $this->data, true);
		$this->data['content'] = $this->load->view($templateDirectory, $this->data, true);
		
		$this->load->view('templates/' . $this->template, $this->data, $return);
	}

}