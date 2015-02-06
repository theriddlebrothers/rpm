<?php
/*
 * Estimate cost item model
 */
class CostItem extends DataMapper {

	var $table = 'costitems';
	var $has_one = array('estimate');
	
	var $validation = array(
        array(
            'field' => 'description',
            'label' => 'cost item description',
            'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 500),
        ),
        array(
            'field' => 'amount',
            'label' => 'amount',
            'rules' => array('trim', 'remove_commas'),
        ),
        array(
            'field' => 'item_type',
            'label' => 'cost item type',
            'rules' => array('required'),
        ),
	);

	public function CostItem() {
		parent::DataMapper();
	}
	
	/**
	 * Strip commas from a field
	 */
	public function _remove_commas($field) {
		$val = $this->{$field};
		if (!$val) return;
		$this->{$field} = str_replace(',', '', $val);
	}
	
}