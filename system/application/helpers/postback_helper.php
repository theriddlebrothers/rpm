<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 *CodeIgniter Postback Helper
 *
 * Functionality to allow postback binding to form data and remove logic
 * from within controllers site wide.
 */

// --------------------------------------------------------------------

if ( ! function_exists('autobind'))
{

	/**
	 * Check GET/POST for values and auto-bind the values to properties associated with an object.
	 * Will NOT bind to objects - only scalar properties and arrays
	 *
	 * @param	object				$obj	Object to bind to
	 */
	function autobind(&$obj) {
		$ci =& get_instance();
		foreach($_POST as $key=>$val) {
			if (property_exists($obj, $key)) {
				// only bind to scalars, or arrays if types match
				if (!is_object($obj->{$key})) {
					$obj->{$key} = $ci->input->post($key);
				}
			}
		}
	}
}

if ( ! function_exists('postback'))
{
	/**
	 * Return value in this order:
	 * - POST data
	 * - Property of $obj (object or array)
	 * - GET data
	 *
	 * This allows us to call postback_data($foo, 'bar') and retrieve
	 * the $_POST information for an invalid form without having
	 * to manually reset the data in each controller.
	 *
	 * @param	object|array		$obj		object that houses existing data
	 * @param	string				$property	property to find in object/post/get
	 * @param	string				$secondary	child property of $property value so we can 
	 								look for $obj->property->child. Does not effect $_POST/$_GET lookup.
	 * @param	string				$method		Method to look for values. Null searches both POST/GET.
	 */
	function postback($obj, $property, $secondary=null, $method=null)
	{
		$ci =& get_instance();
		if (($method == 'POST') || ($method == null)) {
			if (isset($_POST[$property])) {
				return htmlentities($ci->input->post($property));
			}
		}
				
		if (($method == 'GET') || ($method == null)) {
			if (isset($_GET[$property])) return htmlentities(urldecode($ci->input->get($property)));
		}
		
		if (is_object($obj)) {
			if (property_exists($obj, $property)) {
				// return child property if set
				if ($secondary) {
					if (isset($obj->{$property}->{$secondary})) {
						return $obj->{$property}->{$secondary};
					} else {
						return null;
					}
				}
				return $obj->{$property};
			}
		} elseif (is_array($obj)) {
			if (isset($obj[$property])) {
				if ($secondary)	{
					if (isset($obj[$property][$secondary])) {
						return $obj[$property][$secondary];
					} else {
						return null;
					}
				}
				return $obj[$property];
			}
		}
	}
}

if ( ! function_exists('truncate'))
{

	/**
	 * Truncate a string
	 * @param	string		$str		String to truncate	
	 * @param	integer		$length		Max length
	 * @param	string		$delim		String to append at end if longer than max length
	*/
	function truncate($str, $length, $delim='...') {
		if (!$str) return $str;
		$ret = $str;
		if (strlen($str) > $length) {
			$ret = substr($str, 0, $length);
			if ($delim) $ret .= $delim;
		}
		return $ret;
	}
}


/* End of file inflector_helper.php */
/* Location: ./application/helpers/inflector_helper.php */