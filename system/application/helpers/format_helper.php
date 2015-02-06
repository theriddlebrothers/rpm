<?php

/**
 * Format a date/time string from datetimepicker JS library to a MySQL string
 * @param	string		$str		Date/time in format of mm/dd/YYYY @ hh:sstt
 * @return	date					Parsed date/time object
 */
function format_datetimepicker($str) {
	if (!preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4} @ [0-9]{2}:[0-9]{2}[apm]{2}/', $str)) return false;
	
	$month = substr($str, 0, 2);
	$day = substr($str, 3, 2);
	$year = substr($str, 6, 4);
	$hour = substr($str, 13, 2);
	$minute = substr($str, 16, 2);
	$ampm = substr($str, 18, 2);
	if ($ampm == 'pm') $hour += 12;
	$formatted = mktime($hour, $minute, 0, $month, $day, $year);
	return date("Y-m-d H:i:s", $formatted);
}

/**
 * Return content as translated Markdown content
 */
function markdownify($str) {
	$ci =& get_instance();
	$ci->load->library('markup'); 
	return $ci->markup->translate($str);
}