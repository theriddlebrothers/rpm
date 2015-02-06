<?php
/**
 * Payment transactions
 */
class Transaction extends DataMapper {

	var $table = 'transactions';
	
	var $has_one = array('invoice');

	public function Transaction() {
		parent::DataMapper();
	}
	
}