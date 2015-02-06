<?php
/**
 * Activity log model
 */
class Activity extends DataMapper {

	var $table = 'activities';
	var $has_one = array('company');
	
	var $validation = array(
		'subject'	=>	array(
            'label' => 'subject',
            'rules' => array('required', 'trim'),
        ),        
       'activity_type'	=> array(
            'label' => 'activity type',
            'rules' => array('required', 'trim'),
       	),
        'activity_date'	=>	array(
            'label' => 'activity date',
            'rules' => array('required', 'trim', 'valid_date'),
       	),
        'company'	=>	array(
            'label' => 'company',
            'rules' => array('required'),
       	),
	);

	public function Activity() {
		parent::DataMapper();
	}
	
		
}