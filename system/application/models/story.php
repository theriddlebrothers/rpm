<?php
/**
 * Story model
 */
class Story extends DataMapper {

	var $table = 'stories';
	var $has_one = array('project');
	
	var $validation = array(
		'description'	=>	array(
		    'label' => 'description',
		    'rules' => array('required', 'trim'),
		),   
		'priority'	=>	array(
		    'label' => 'priority',
		    'rules' => array('required', 'trim'),
		),   
		'status'	=>	array(
		    'label' => 'status',
		    'rules' => array('required', 'trim'),
		)
	);

	public function Story() {
		parent::DataMapper();
	}
	
		
}