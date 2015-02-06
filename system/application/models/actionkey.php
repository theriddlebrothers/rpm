<?php
/*
 * Action Key model
 */
class ActionKey extends DataMapper {

	var $table = 'action_keys';
	
	public function ActionKey() {
		parent::DataMapper();
	}
	
	/**
	 * Generate a custom action key that expires after specified time period
	 * @param	string		$expires	Date/time key will expire, using strtotime() parameter
	 */
	public function generate($expires='+1 hour') {
		$this->unique_key = sha1(uniqid(null, false));
		$this->expiration_date = date("Y-m-d H:i:s", strtotime($expires));
	}
	
	/**
	 * Validate this key has not expired/exists
	 */
	public function validate_key() {
		if (!$this->unique_key) return false;
		if (!$this->expiration_date) return false;
		
		// check expiration
		if (strtotime($this->expiration_date) >= strtotime("now")) return true;
	}
}