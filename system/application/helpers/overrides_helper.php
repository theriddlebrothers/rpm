<?php
/**
 * Helper to override existing codeigniter helper functions and assist with controller/view
 * integration of business logic.
 */
 
 
/**
 * Header Redirect
 *
 * Modification of the CI redirect function so it includes the controller's base URL of cp vs client.
 * This allows us to use redirect but also take into account the /client/ vs /cp/ 
 * base URL to filter to show only company-related info.
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}
 
if ( ! function_exists('site_url'))
{

	/**
	 * Modification of the CI site url function so it includes the base url.
	 * This allows us to use site_url but also take into account the /client/ vs /cp/ 
	 * data filtering.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function site_url($uri = '')
	{
		$ci =& get_instance();
		
		$template = '';
		$is_absolute = false;
		
		if (strlen($uri) > 0) $is_absolute = ($uri[0] == '/');
		
		
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}
		
		// get base
		$path = pathinfo($_SERVER['REQUEST_URI']);
		$exp = explode('/', $path['dirname']);
		$section = '';
		if (!$is_absolute && sizeof($exp) >= 1) {
			if ($exp[1] != '') $section = $exp[1] . '/';
		}
		if ($uri == '')
		{
			return $ci->config->slash_item('base_url').$section.$ci->config->item('index_page');
		}
		else
		{
			$suffix = ($ci->config->item('url_suffix') == FALSE) ? '' : $ci->config->item('url_suffix');
			return $ci->config->slash_item('base_url').$section.$ci->config->slash_item('index_page').trim($uri, '/').$suffix; 
		}

	}
}