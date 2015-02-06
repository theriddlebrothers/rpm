<?php
/*
 * Document model
 */
class Doctemplate extends DataMapper {

	var $table = 'doctemplates';
	var $has_one = array('doctype');
	var $has_many = array('docsection', 'docfieldvalue');
	
	var $fields = array();
	
	public function Doctemplate() {
		parent::DataMapper();
	}
	
}