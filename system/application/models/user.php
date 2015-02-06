<?php
/**
 * User model
 */
class User extends DataMapper {

	var $salt = '.c~,jVUgnt%?kT5g|am8lE&U 5WDyG(>[F^UWO=x|>wEJtSPvymwf;<$3rGo/0eN';
	var $table = 'users';
	
	var $has_one = array('company');
	
	var $has_many = array('task', 'timelog', 'permission', 'project', 'comment', 
		'assigned_issue'	=>	array(
			'class'			=>	'issue',
			'other_field'	=>	'assignee',
		),
		'reported_issue'	=>	array(
			'class'			=>	'issue',
			'other_field'	=>	'reporter',
		)
	);
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => array('required', 'trim', 'max_length' => 20),
        ),
        array(
            'field' => 'username',
            'label' => 'username',
            'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 60, 'username_unique'),
        ),
        array(
            'field' => 'password',
            'label' => 'password',
            'rules' => array('required', 'trim', 'min_length' => 3, 'encrypt'),
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => array('required', 'trim', 'valid_email'),
        ),
        array(
            'field' => 'role',
            'label' => 'role',
            'rules' => array('required'),
        ),
	);

	public function User($data = array()) {
		parent::DataMapper();
		
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	
	public function getPlainObject() {
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->name = $this->name;
		$resp->email = $this->email;
		$resp->role = $this->role;
		return $resp;
	}
	
	/**
	 * Confirm client association
	 */
	function _check_client($field) {
		$role = $this->{$field};
		if ($role == ROLE_CLIENT) {
			if (!$this->company->id) {
				return 'Client roles must be associated with a company.';
			}
		}
		return true;
	}
	
	// Validation prepping function to encrypt passwords
	function _encrypt($field) // optional second parameter is not used
	{
	    // Don't encrypt an empty string
	    if (!empty($this->{$field}))
	    {
	        $this->{$field} = sha1($this->salt . $this->{$field});
	    }
	}
	
	// Validation function for unique username
	function _username_unique($field) {
		// Don't validate empty
		if (!empty($this->{$field})) {
			$uname = $this->{$field};
			$user = new User();
			$user = $user->where('username', $uname)->get();
			if ($user->exists() && ($user->id != $this->id)) {
				return 'Username ' . $uname . ' is already being used. Please try another.';
			}
		}
		return TRUE;
	}
	
	/**
	 * Generate a session cookie
	 */
	public function generateCookie($series = null) {			
		if (!$series) $series = $this->generateSeries();
		$token = $this->generateToken();
		$this->series = $series;
		$this->token = $token;
		
		return array(
			'name'		=>	'auth',
			'value'		=>	$series . ':' . $token,
			'expire'	=>	'31536000',	// 1 year
			'domain'	=>	$_SERVER['HTTP_HOST'],
			'path'		=>	'/'
		);
	}
	
	/**
	 * Generate a series ID for persistent logins
	 */
	public function generateSeries() {
		return sha1(uniqid($this->username, true));
	}
	
	/**
	 * Generate a token for persistent logins
	 */
	public function generateToken() {
		return sha1(uniqid('', true));
	}
	
	/**
	 * Log user into system
	 */
	public function login() {
	
		// check fields
		if (!$this->username && !$this->password) return false;
		
		// Create a temporary user object
        $u = new User();

        // Get this users stored record via their username
        $u->where('username', $this->username)->get();

        // Validate and get this user by their property values,
        // this will see the 'encrypt' validation run, encrypting the password with the salt
        $this->validate()->get();
        
        // If the username and encrypted password matched a record in the database,
        // this user object would be fully populated, complete with their ID.

        // If there was no matching record, this user would be completely cleared so their id would be empty.
        if (empty($this->id))
        {
        	// Login failed
            return FALSE;
        }
        else
        {
            // Login succeeded
            return TRUE;
        }
	}
	
	function logout() {
		if ($this->id) {
			$this->token = null;
			$this->save();
		}		
		delete_cookie('auth');
	}
}