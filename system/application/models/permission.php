<?php
/**
 * User permission model.
 * DEPRECATED! User permissions have been replaced with user Roles (a field within the users table).
 */
class Permission extends DataMapper {

	var $table = 'permissions';
	
	var $has_many = array('user');
	
	var $validation = array(
	);

	public function Permission() {
		parent::DataMapper();
	}
	
}