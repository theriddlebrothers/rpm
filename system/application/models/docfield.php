<?php
/*
 * Document field
 */
class Docfield extends DataMapper {

	var $table = 'docfields';
	var $has_one = array('doctype');
	var $has_many = array('docfieldvalue');
	
	public function Docfield() {
		parent::DataMapper();
	}
	
}