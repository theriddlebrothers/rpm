<?php

class Estimate extends DataMapper {

	var $table = 'estimates';
	var $has_many = array('costitem', 'invoice');
	var $has_one = array('estimateterm', 'project', 'company');
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'estimate name',
            'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 200),
        ),
        
        array(
            'field' => 'estimate_date',
            'label' => 'estimate date',
            'rules' => array('required', 'trim'),
        ),
        
        array(
            'field' => 'estimate_number',
            'label' => 'estimate number',
            'rules' => array('required', 'trim', 'integer'),
        ),
        
        array(
            'field' => 'company',
            'label' => 'company',
            'rules' => array('required'),
        ),
        
        array(
        	'field' => 'send_email_recipient',
        	'label' => 'email recipient',
        	'rules'	=>	array('valid_email')
        ),
        
	);

	public function Project() {
		parent::DataMapper();
	}
	
	/**
	 * Validate email address formats
	 */
	public function _check_emails($field) {
		$emails = $this->{$field};
		$err = '';
		if ($emails) {
			$arr = explode(",", $emails);
			foreach($arr as $email) {
				$email = trim($email);
				if (!$this->form_validation->valid_email($email)) {
					$err .= "Recipient email " . $email . " is not valid.<br />";
				}
			}
		}
		return $err;
	}
	
	/**
	 * Validate invoice amount and email addresses
	 */
	public function _check_request_info() {
		$msg = '';
		if ($this->generate_invoice_request) {
			if (($this->generate_request_amount < 1) || ($this->generate_request_amount > 100)) {
				$msg .= "Invoice request amount must be between 1 and 100.<br />";
			}
			if (!$this->generate_request_recipients) {
				$msg .= "You must select a user as a recipient for the invoice request.<br />";
			}
		}
		return $msg;
	}
	
	
	/**
	 * Retrieve cost items as array
	 */
	public function getCostItemsAsArray(&$input, $nopost = false) {
	
		// if no cost items, and not a postback, return default display
		if (!$this->costitem->all && ($input->server('REQUEST_METHOD') != 'POST')) {
			return array();
		}
		$cost_items = array();
		
		if (!$nopost && ($input->server('REQUEST_METHOD') == 'POST')) {
			
			// return posted values
			$descriptions = $input->post('cost_description');
			$amount_headings = $input->post('cost_amountheading');
			$item_types = $input->post('cost_item_type');
			$num_items = sizeof($descriptions);
			$cost_items = array();
			for($i=0; $i<$num_items; $i++) {
				// don't save empty lines
				if (empty($descriptions[$i]) && empty($amount_headings[$i])) continue;
				
				$item = array();
				$item['description'] = $descriptions[$i];
				$item['item_type'] = $item_types[$i];
				if ($item['item_type'] == 'heading') {
					$item['heading'] = $amount_headings[$i];
					$item['amount'] =  null;
				} else {
					$item['amount'] = $amount_headings[$i];
					$item['heading'] =  null;
				}
				
				$cost_items[] = $item;
			}


		} elseif ($this->costitem->all) {
			// return cost items
			foreach($this->costitem->all as $cost_item) {
				if (empty($cost_item->description)) continue;
				
				$item = array();
				$item['description'] = $cost_item->description;
				$item['item_type'] = $cost_item->item_type;
				if ($item['item_type'] == 'heading') {
					$item['heading'] = $cost_item->heading;
					$item['amount'] =  null;
				} else {
					$item['amount'] = $cost_item->amount;
					$item['heading'] =  null;
				}			
				$cost_items[] = $item;
			}
		}
		return $cost_items;
	}
	
	/**
	 * Retrieve total billed for this estimate to date
	 */
	public function getTotalBilled() {
		$total = 0;
		
		foreach($this->invoice->all as $invoice) {
			foreach($invoice->lineitem->get()->all as $lineitem) {
				$total += $lineitem->amount;
			}
		}
		return $total;
	}
	
	/**
	 * Retrieve total amount estimated
	 */
	public function getTotalEstimated() {
		$total = 0;
		$this->costitem->get();
		foreach($this->costitem->all as $item) {
			if ($item->item_type == 'price') {
				$total += $item->amount;
			}
		}
		return $total;
		
	}
	
}