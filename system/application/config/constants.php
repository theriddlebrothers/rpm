<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$environment = null;

switch($_SERVER['HTTP_HOST']) {
	case 'rpm.theriddlebrothers.com':
	case 'pm.theriddlebrothers.net':
		$environment = 'production';
		break;
	case 'rpm.theriddlebrothers.net':
	case 'projects-beta.theriddlebrothers.net':
		$environment = 'staging';
		break;
	default:
		$environment = 'sandbox';
		break;
}
define('ENVIRONMENT', $environment);

// # email for testing
define('TEST_EMAIL', 'josh@theriddlebrothers.com');

// # items to show in admin pages
define('RB_ADMIN_PER_PAGE', 50);

// # of minutes to check that a user is logged in and log them out
// automatically if not.
define('CHECK_LOGIN_MINUTES', 1);

// role strings used in DB
define('ROLE_ADMINISTRATOR', 'Administrator');
define('ROLE_CLIENT', 'Client');
define('ROLE_EMPLOYEE', 'Employee');

// document types
define('DOCTYPE_REQUIREMENTS', 1);
define('DOCTYPE_CREATIVE', 2);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */