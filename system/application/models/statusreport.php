<?php
/**
 * Project status report
 */
class StatusReport extends DataMapper {

	var $table = 'statusreports';
	
	var $has_one = array('project');
	
	var $validation = array(
        array(
            'field' => 'report_date',
            'label' => 'Report Date',
            'rules' => array('required', 'trim', 'valid_date'),
        ),
	);

	
}