<?php
/*
 * Contact model
 */
class Contact extends DataMapper {

	var $table = 'contacts';
	var $has_one = array('company');
	var $has_many = array('project');
	
	var $validation = array(
        array(
            'field' => 'first_name',
            'label' => 'first name',
            'rules' => array('required', 'trim', 'max_length' => 60),
        ),
        array(
            'field' => 'last_name',
            'label' => 'last name',
            'rules' => array('required', 'trim', 'max_length' => 60),
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => array('required', 'valid_email', 'trim', 'min_length' => 3, 'max_length' => 300),
        ),
	);

	public function Contact() {
		parent::DataMapper();
	}
	
	
	/**
	 * Return address as formatted block
	 */
	public function getAddress() {
		$line = $this->address . "<br />";
		if ($this->city) {
			$line .= $this->city;
		}
		if ($this->state) {
			$line .= ', ' . $this->state;
		}
		if ($this->zip) {
			$line .= ' ' . $this->zip;
		}
		return $line;
	}
	
	/**
	 * Return address as a single line
	 */
	public function getAddressLine() {
		$line = $this->address;
		if ($this->city) {
			$line .= ', ' . $this->city;
		}
		if ($this->state) {
			$line .= ', ' . $this->state;
		}
		if ($this->zip) {
			$line .= ' ' . $this->zip;
		}
		return $line;
	}
	
	
	/**
	 * Check if subscribed to mailing list
	 */
	public function isSubscribed() {
		$ci =& get_instance();
		$is_subscribed = false;
		if ($this->email) {
			$ci->load->library('campaignmonitor');
			$result = $ci->campaignmonitor->get_subscriber($this->email);
			
			if (($result->http_status_code == 200) || ($result->http_status_code == 200)) {
				if ($result->response->State == "Unsubscribed") return false;
				return true;
			} elseif ($result->http_status_code == 400) {
				// not subscribed
				return false;
			}
			
			// error has occurred
			throw new Exception("Unable to verify subscription due to an error: " . $result->response->Message . var_export($result, true));
		}
		return $is_subscribed;
	}
	
	/**
	 * Subscribe to mailing list
	 */
	function subscribe() {
		$email = $this->email;
		$name = $this->first_name . ' ' . $this->last_name;
		$ci =& get_instance();
		$ci->load->library('campaignmonitor');
		$result = $ci->campaignmonitor->subscribe($email, $name);
		return $result;
	}
	
	/**
	 * Unsubscribe from mailing list
	 */
	function unsubscribe() {
		$email = $this->email;
		if (!$this->isSubscribed()) return false;
		
		$ci =& get_instance();
		$ci->load->library('campaignmonitor');
		$result = $ci->campaignmonitor->unsubscribe($email);
		return $result;
	}
	
}