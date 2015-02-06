<?php

require('campaignmonitor/csrest_general.php');
require('campaignmonitor/csrest_subscribers.php');

class CampaignMonitor {
	
	
	/**
	 * Retrieve subscriber from list
	 * @param	string		$email		Subsriber email
	 * @return	object					JSON resonse object
	 */
	public function get_subscriber($email) {
		$ci =& get_instance();
		$config = $ci->config->item('campaignmonitor');

		$wrap = new CS_REST_Subscribers($config['list_id'], $config['key']);
		$result = $wrap->get($email);
		
		return $result;
	}
	
	/**
	 * Subscribe a user to a mailing list
	 * @param	string		$email		Subscriber email
	 * @param	string		$name		Subscriber name
	 * @return	object					JSON response object
	 */
	public function subscribe($email, $name) {
	
		$ci =& get_instance();
		$config = $ci->config->item('campaignmonitor');

		$wrap = new CS_REST_Subscribers($config['list_id'], $config['key']);
		$result = $wrap->add(array(
		    'EmailAddress' => $email,
		    'Name' => $name,
		    'Resubscribe' => false
		));
		
		return $result;
	}
	
	/**
	 * Unsubscribe a user from a mailing list
	 * @param	string		$email		Subscriber email
	 * @return	object					JSON response object
	 */
	public function unsubscribe($email) {
	
		$ci =& get_instance();
		$config = $ci->config->item('campaignmonitor');

		$wrap = new CS_REST_Subscribers($config['list_id'], $config['key']);
		$result = $wrap->unsubscribe($email);
		
		return $result;
	}
	
}