<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Email Class
 *
 * Permits email to be sent using Mail, Sendmail, or SMTP.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/email.html
 */
class RB_Email extends CI_Email {

	/**
	 * Send Email
	 *
	 * OVERRIDDEN to log emails being sent.
	 *
	 * @access	public
	 * @return	bool
	 */
	function send()
	{
	
		//$this->set_newline("\r\n");
		
		// LOG email
		$log = new Emaillog();
		$log->to = (is_array($this->_recipients) ? implode(', ', $this->_recipients) : $this->_recipients);
		$log->cc = ($this->_cc_array ? implode(', ', $this->_cc_array) : null);
		$log->bcc = ($this->_bcc_array ? implode(', ', $this->_cc_array) : null);
		$log->save();
		
		if ($this->_replyto_flag == FALSE)
		{
			$this->reply_to($this->_headers['From']);
		}

		if (( ! isset($this->_recipients) AND ! isset($this->_headers['To']))  AND
			( ! isset($this->_bcc_array) AND ! isset($this->_headers['Bcc'])) AND
			( ! isset($this->_headers['Cc'])))
		{
			$log->dump = 'No email recipients specified for this email';
			$log->save();
			$this->_set_error_message('email_no_recipients');
			return FALSE;
		}
		
		// if we are NOT in production, make all emails go to sandbox@theriddlebrothers.com 
		// to avoid anything from going to clients
		if (ENVIRONMENT != 'production') {
			$this->_recipients = TEST_EMAIL;
			$this->_cc_array = array();
			$this->_bcc_array = array();
		}
		
		$this->_build_headers();

		if ($this->bcc_batch_mode  AND  count($this->_bcc_array) > 0)
		{
			if (count($this->_bcc_array) > $this->bcc_batch_size) {
				
				$log->dump = 'Email sent in batch mode.';
				$log->save();
			
				return $this->batch_bcc_send();
			}
		}

		$this->_build_message();
		
		$response = false;
		
		if ( ! $this->_spool_email())
		{
			$response = false;
		}
		else
		{
			$response = true;
		}
		
		$log->dump = $this->print_debugger();
		$log->success = $response;
		$log->save();
		
		return $response;
	}
}
// END CI_Email class

/* End of file Email.php */
/* Location: ./system/libraries/Email.php */