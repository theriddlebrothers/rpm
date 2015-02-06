<?php
/*
 * Document type
 */
class Doctype extends DataMapper {

	var $table = 'doctypes';
	var $has_many = array('doc', 'docfield', 'doctemplate');
	
	public function Doctype() {
		parent::DataMapper();
	}
	
}