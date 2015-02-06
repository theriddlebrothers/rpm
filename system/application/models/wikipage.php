<?php
/**
 * Wiki page model
 */
class WikiPage extends DataMapper {

	var $table = 'wikipages';
	var $has_many = array();
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => array('required', 'trim', 'max_length' => 500),
        ),
  	);

	public function WikiPage() {
		parent::DataMapper();
	}
	
}