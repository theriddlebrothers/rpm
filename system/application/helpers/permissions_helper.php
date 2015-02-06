<?php
/**
 * Helper for checking user permissions within views (see RB_Controller::is_allowed() function.
 */
if (!function_exists('user_can')) {

	function access_error() {
		$ci =& get_instance();	
		$ci->session->set_flashdata('error', 'You do not have access to this page.');
		redirect('/');
	}
	
	function has_role($role) {
		$ci =& get_instance();
		return ($role == $ci->current_role);
	}
	
	function in_role($role) {
		$ci =& get_instance();
		return $ci->inRole($role);
	}
	
	// see RB_Controller:is_allowed()
	function user_can($action, $object) {
		$ci =& get_instance();
		return $ci->isAllowed($action, $object);
	}
	
}