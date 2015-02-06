<?php
/*
 * Requirements section model
 */
class Docrequirement extends DataMapper {

	var $table = 'docrequirements';
	var $has_one = array('docsection');
	
	public function Docrequirement() {
		parent::DataMapper();
	}
	
}