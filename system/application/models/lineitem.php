<?php
/**
 * Invoice line item model
 */
class Lineitem extends DataMapper {

	var $table = 'lineitems';
	var $has_one = array('invoice');
	
	var $validation = array(
        array(
            'field' => 'description',
            'label' => 'description',
            'rules' => array('required', 'trim', 'max_length' => 600),
        ),
        array(
            'field' => 'amount',
            'label' => 'amount',
            'rules' => array('required', 'trim', 'remove_commas', 'numeric'),
        ),
	);

	public function Lineitem($data = array()) {
		parent::DataMapper();
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	public function getPlainObject() {
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->description = $this->description;
		$resp->amount = $this->amount;
		return $resp;
	}
	
	public function _remove_commas($field) {
		$val = $this->{$field};
		if (!$val) return;
		$this->{$field} = str_replace(',', '', $val);
	}
	
}