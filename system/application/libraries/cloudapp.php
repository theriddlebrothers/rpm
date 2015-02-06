<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * See https://github.com/matthiasplappert/CloudApp-API-PHP-wrapper
 */
require('cloudapp/api.php'); 

class CloudApp extends Cloud_API {
	// wrapper class for CI integration
	
	public function shorten($url, $name='Project Manager Link', $private=false) {
		$ci =& get_instance();
		$email = $ci->config->item('email', 'cloudapp');
		$password = $ci->config->item('password', 'cloudapp');
		$cloudapp = new Cloud_API($email, $password, 'Riddle Brothers PM');
		$link = $cloudapp->addBookmark($url, $name, $private);
		return $link->content_url;
	}
	
}