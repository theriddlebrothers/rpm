<?php
/*
 * Type of document section
 */
class Docsectiontype extends DataMapper {

	var $table = 'docsectiontypes';
	var $has_many = array('docsection');
	
	public function Docsectiontype() {
		parent::DataMapper();
	}
	
}