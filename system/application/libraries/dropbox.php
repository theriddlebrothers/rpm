<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * See http://code.google.com/p/dropbox-php/
 * Wiki: http://code.google.com/p/dropbox-php/wiki/Dropbox_API
 */
require('dropbox/autoload.php'); 

// wrapper class for dropbox integration	
class Dropbox {
	public $_dropbox;
	
	public function Dropbox() {
		$ci =& get_instance();
		
		$ci->load->library('pearloader');
 		$ci->pearloader->load('HTTP', 'OAuth');
 
		$key = $ci->config->item('key', 'dropbox');
		$secret = $ci->config->item('secret', 'dropbox');
		$email = $ci->config->item('email', 'dropbox');
		$password = $ci->config->item('password', 'dropbox');
		
		$oauth = new Dropbox_OAuth_PEAR($key, $secret);
		$dropbox = new Dropbox_API($oauth);
		$tokens = $dropbox->getToken($email, $password);	
		$oauth->setToken($tokens);
		$this->_dropbox = $dropbox;
	}
	
	public function getAccountInfo() {
		return $this->_dropbox->getAccountInfo();
	}
	
	public function getFile($filename) {
		$filename = str_replace(' ' , '%20', $filename);
		return $this->_dropbox->getFile($filename);
	}
	
	public function getMetaData($path) {
		$path = str_replace(' ' , '%20', $path);
		return $this->_dropbox->getMetaData($path);
	}
	
}