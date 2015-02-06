<?php
/**
 * Use case section model
 */
class Docusecase extends DataMapper {

	var $table = 'docusecases';
	var $has_one = array('docsection');
   	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'Use case name',
            'rules' => array('required'),
        )
    );
    
	public function Docusecase() {
		parent::DataMapper();
	}
	
	/**
	 * Create numberic ID for use case
	 */
	public function getUCID() {
		// @todo
		return $this->id;
	}
	
	/**
	 * Create output for use case
	 */
	public function output() {
		return $this->load->view('cp/documents/compiled/usecase', array('item'=>$this), true);
	}
	
}