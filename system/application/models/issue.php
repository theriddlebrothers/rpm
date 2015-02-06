<?php
/**
 * Issue model
 */
class Issue extends DataMapper {

	var $table = 'issues';
	var $has_one = array(
		'project',
		'assignee'	=>	array(
			'class'			=>	'user',
			'other_field'	=>	'assigned_issue'
		),
		'reporter'	=>	array(
			'class'			=>	'user',
			'other_field'	=>	'reported_issue'
		),
	);
	var $has_many = array('comment');
	
	var $validation = array(
		'title'	=>	array(
            'label' => 'title',
            'rules' => array('required', 'trim'),
        ),        
		'category'	=>	array(
			'field'	=>	'category',
            'label' => 'category',
            'rules' => array('trim'),
        ),        
		'priority'	=>	array(
            'label' => 'priority',
            'rules' => array('trim'),
        ),        
		'status'	=>	array(
            'label' => 'status',
            'rules' => array('required', 'trim'),
        ),        
		'component'	=>	array(
            'label' => 'component',
            'rules' => array('trim'),
        ),        
		'description'	=>	array(
            'label' => 'description',
            'rules' => array('required'),
        ),        
        'date_reported'	=>	array(
            'label' => 'date reported',
            'rules' => array('required', 'trim', 'valid_date'),
       	),   
        'project'	=>	array(
            'label' => 'project',
            'rules' => array('required'),
       	),
        'reporter'	=>	array(
            'label' => 'reporting user',
            'rules' => array('required'),
       	),
	);
	
	/*---------------------------
	 * Form Lists
	 *---------------------------*/
	var $lists = array(
		'browsers'	=>	array(
			''							=>	'Select a browser...',
			'All Browsers'				=>	'All Browsers',
			'Chrome 8.x'				=>	'Chrome 8.x',
			'Chrome 9.x'				=>	'Chrome 9.x',
			'Firefox 3.0.x'				=>	'Firefox 3.0',
			'Firefox 3.5.x'				=>	'Firefox 3.5.x',
			'Firefox 3.6.x'				=>	'Firefox 3.6.x',
			'Firefox 4.0.x'				=>	'Firefox 4.0.x',
			'Internet Explorer 6'		=>	'Internet Explorer 6',
			'Internet Explorer 7'		=>	'Internet Explorer 7',
			'Internet Explorer 8'		=>	'Internet Explorer 8',
			'Internet Explorer 9'		=>	'Internet Explorer 9',
			'Safari 3.x'				=>	'Safari 3.x',
			'Safari 4.x'				=>	'Safari 4.x'
		),
		
		'categories'	=>	 array(
			''					=>	'Select a category...',
			'Bug'				=>	'Bug',
			'Change'			=>	'Change',
			'Feature Request'	=>	'Feature Request',
			'Inquiry'			=>	'Inquiry',
		),
		
		'operating_systems'	=>	array(
			''				=>	'Select a system...',
			'Mac OS X'		=>	'Mac OS X',
			'Windows XP'	=>	'Windows XP',
			'Windows Vista'	=>	'Windows Vista',
			'Windows 7'		=>	'Windows 7',
		),
		
		'priorities'	=>	array(
			''							=>	'Select a priority...',
			'1 - Emergency'				=>	'1 - Emergency',
			'2 - Critical'				=>	'2 - Critical',
			'3 - Must Fix'				=>	'3 - Must Fix',
			'4 - Fix If Time'			=>	'4 - Fix If Time',
			'5 - Worth Remembering'		=>	'5 - Worth Remembering',
			'6 - Don\'t Fix'			=>	'6 - Don\'t Fix',
		),
		
		'statuses'	=>	array(
			''						=>	'Select a status...',
			'Submitted'				=>	'Submitted',
			'In Progress'			=>	'In Progress',
			'Awaiting QA'			=>	'Awaiting QA',
			'Pending Client Review'	=>	'Pending Client Review',
			'Closed'				=>	'Closed',
			'Inactive'				=>	'Inactive',
		),
		
		'visibilities'	=>	array(
			''			=>	'Select a visibility...',
			'Hidden'	=>	'Hidden',
			'Visible'	=>	'Visible'
		)
	);
	

	public function Issue() {
		parent::DataMapper();
	}
		
}