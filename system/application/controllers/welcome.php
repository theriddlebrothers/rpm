<?php

class Welcome extends Public_Controller {

	function Welcome()
	{
		parent::Public_Controller();	
	}
	
	/**
	 * Show Forgot Password form
	 */
	function forgot() {
	
		$success = null;
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$user = new User();
			$user->where('username', $this->input->post('username'))->get();
			
			if ($user->exists()) {
				// generate custom key
				$key = new ActionKey();
				$key->generate();
				$key->save();
				// send password link
				$link = site_url('/welcome/reset/' . $key->unique_key);
				$email = new BrandedEmail();
				$email->to = $user->email;
				$email->subject = 'RPM Password Reset Link';
				$email->body = 'You have requested to reset your RPM password. Use the link below to create a new password for your account.';
				$email->link_url = $link;
				$email->link_text = 'Reset Your Password';
				$email->send();
			}
			
			// always show success message, even if account doesn't exist, to prevent malicious haxxorz
			$success = 'A password reset link has been sent to the email address associated with your account.';
		}
		
		$this->data['success'] = $success;
		$this->data['body_class'] = 'spotlight';
		$this->render('forgot');
	}
	
	/**
	 * Login screen
	 */
	function index()
	{
		$errors = null;
		
		$this->checkAuthorizationCookie();
		
		if ($this->input->get('logout')) {
			
			$user = new User();
			$user->where('id', $this->session->userdata('user_id'))->get();
			$user->logout();
			$this->session->sess_destroy();
			delete_cookie('auth', $_SERVER['HTTP_HOST'], '/');			
			if ($this->input->get('force')) {
				// login failed
				$this->session->set_flashdata('error', 'Your session has ended. Please log in again to continue using the site.');	
			}
			redirect("/");
		}
		
		// if user is already logged in
		if ($this->session->userdata('user_id')) {
			redirect("cp/dashboard");
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$user = new User();
			$user->username = $this->input->post('username');
			$user->password = $this->input->post('password');
			
			if ($user->login()) {
				// success!
				$user->where('username', $this->input->post('username'))->get();
				
				// remember me
				if ($this->input->post('remember')) {
				
					$cookie = $user->generateCookie();
					set_cookie($cookie);
					
					// store session/token
					$user->save();
				}
				
				$this->session->set_userdata('user_id', $user->id);
				
				if ($this->input->get('redirect')) {
					redirect($this->input->get('redirect'));
				} else {
					redirect('cp/dashboard');
				}
				
			} else {
				// login failed
				$this->session->set_flashdata('error', 'Invalid username/password. Please try again.');	
				
				// redirect to allow the session to set the error.
				redirect("/");		
			}
		}
		
		$this->data['body_class'] = 'spotlight';
		$this->render('login');
	}
	
	/**
	 * Reset password form
	 */
	public function reset($raw_key) {
		$success = null;
		$errors = array();
		
		// check action link
		$key = new ActionKey();
		$key->where('unique_key', $raw_key)->get();
		if (!$key->validate_key()) {
			$this->data['body_class'] = 'spotlight';
			$this->render('invalid_key');
			return;
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$user = new User();
			$user->where('username', $this->input->post('username'))->get();
			
			if (!$user->exists()) {
				$errors[] = 'Invalid username.';
			}
			
			// passwords entered?
			if (!$this->input->post('password') || !$this->input->post('confirm_password')) {
				$errors[] = 'You must enter a password and a confirmation password.';
			}
			
			// check passwords
			if ($this->input->post('password') != $this->input->post('confirm_password')) {
				$errors[] = 'Your passwords do not match.';
			}
			
			if (!$errors) {
				// save new password
				$user->password = $this->input->post('password');
				$user->save();
				
				// log user in
				$this->session->set_userdata('user_id', $user->id);
				
				// send password reset confirmation
				$email = new BrandedEmail();
				$email->to = $user->email;
				$email->subject = 'Your Password Has Been Reset';
				$email->body = 'You recently reset your RPM password. If you did not take this action, please <a href="mailto:contact@theriddlebrothers.com">contact us</a> immediately.';
				$email->send();
			
				// remove action key
				$key->delete();
				
				$success = 'Your password has been reset and you have been logged in to the system. <a href="/">Go to Dashboard</a>';
			}
			
		}

		$this->data['success'] = $success;
		$this->data['errors'] = $errors;
		$this->data['body_class'] = 'spotlight';
		$this->render('reset');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */