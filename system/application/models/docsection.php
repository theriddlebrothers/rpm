<?php
/*
 * Document section model
 */
class Docsection extends DataMapper {

	var $table = 'docsections';
	var $has_one = array('doc', 'doccontent', 'docusecase', 'docsectiontype', 'doctemplate');
	var $has_many = array('docrequirement');
	var $requirements_index = array();
	
	public function Docsection() {
		parent::DataMapper();
	}
	
	/**
	 * Generate output for model
	 */
	public function output() {
		$html = '';
		if (!$this->docsectiontype->code) {
			$html .= $this->doccontent->output();
		} elseif(strtolower($this->docsectiontype->code) == 'uc') {
			$html .= $this->docusecase->output();
		} else {
			$html .= $this->generateRequirements();
		}
		return $html;
	}
	
	/**
	 * Create compiled requirement items
	 */
	public function generateRequirements() {
		$items = $this->getLineItemsAsArray(null);
		return $this->load->view('cp/documents/compiled/requirement', array('items'=>$items), true);
	}
	
	/**
	 * Retrieve  items as array
	 * Pass NULL for $input to ignore $_POST request or CI input object
	 */
	public function getLineItemsAsArray($input) {
		$items = array();
		
		
		// if no cost items, and not a postback, return default display
		if ($input) {
			if (!$this->docrequirement->all && ($input->server('REQUEST_METHOD') != 'POST')) {
				return array();
			}
		}
		
		$is_post = false;
		
		if ($input) {
			if ($input->server('REQUEST_METHOD') == 'POST') {
				$is_post = true;
				// return posted values
				$names = $input->post('name');
				$descriptions = $input->post('description');
				
				$num_items = sizeof($names);
				$items = array();
				for($i=0; $i<$num_items; $i++) {
					// don't save empty lines
					if (empty($descriptions[$i]) && empty($names[$i])) continue;
					
					$item = array();
					$item['name'] =  $names[$i];				
					$item['description'] = $descriptions[$i];
					$items[] = $item;
				}
			}
		}

		if (!$is_post && $this->docrequirement->all) {
			// return cost items
			$code = $this->docsectiontype->get()->code;
			foreach($this->docrequirement->all as $line_item) {
				if (empty($line_item->name)) continue;
				
				$item = array();
				$item['id'] = $code . "-" . $this->requirements_index;
				$item['name'] = $line_item->name;
				$item['description'] = $line_item->description;
				$items[] = $item;
				$this->requirements_index++;
			}
		}
		return $items;
	}
	
}