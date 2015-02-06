<?php
/**
 * Comment model
 */
class Comment extends DataMapper {

	var $table = 'comments';
	var $has_one = array('issue', 'user');

	public function Comment() {
		parent::DataMapper();
	}
	
		
}