<?php
/**
 * Estimate term model
 */
class Estimateterm extends DataMapper {

	var $table = 'estimateterms';
	var $has_many = array('estimate');
	
	var $validation = array(
        array(
            'field' => 'content',
            'label' => 'content',
            'rules' => array('required'),
        ),
	);

	public function EstimateTerm() {
		parent::DataMapper();
	}
	
}