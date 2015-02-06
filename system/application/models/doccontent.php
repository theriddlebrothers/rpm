<?php
/*
 * Basic content section model
 */
class Doccontent extends DataMapper {

	var $table = 'doccontents';
	var $has_one = array('docsection');
	
	public function Doccontent() {
		parent::DataMapper();
	}
	
	/**
	 * Generate output for this model
	 */
	public function output() {
		return $this->load->view('cp/documents/compiled/text', array('item'=>$this), true);
	}
	
}