<?php
/*
 * Document model
 */
class Doc extends DataMapper {

	var $table = 'docs';
	var $has_one = array('project', 'doctype');
	var $has_many = array('docsection', 'docfieldvalue');
	
	var $fields = array();
	
	public function Doc() {
		parent::DataMapper();
	}
	
}