<?php
/*
 * Document field value
 */
class Docfieldvalue extends DataMapper {

	var $table = 'docfieldvalues';
	var $has_one = array('docfield', 'doc', 'doctemplate');
	
	public function Docfieldvalue() {
		parent::DataMapper();
	}
	
}