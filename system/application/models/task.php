<?php
/**
 * Project task model
 */
class Task extends DataMapper {

	var $table = 'tasks';
	var $has_one = array('project', 'user');
	var $has_many = array('timelog');
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => array('required', 'trim', 'max_length' => 200),
        ),
        array(
            'field' => 'created_date',
            'label' => 'task date',
            'rules' => array('required',),
        ),
        array(
            'field' => 'due_date',
            'label' => 'due date',
            'rules' => array('required',),
        ),
        array(
            'field' => 'status',
            'label' => 'status',
            'rules' => array('required',),
        ),
	);

	public function Task($data = array()) {
		parent::DataMapper();
		
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	public function getPlainObject() {
	
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->name = $this->name;
		
		return $resp;
	}
	
	
}