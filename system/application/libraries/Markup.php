<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  

require('markdown/Markdown.php');

if (defined('BASEPATH')) {

	class Markup {
	
		function translate($text) {
			return Markdown($text);
		}
		
	}

}