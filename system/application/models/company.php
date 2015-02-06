<?php
/**
 * Company model
 */
class Company extends DataMapper {

	var $table = 'companies';
	var $has_one = array('user', 'retainer');
	var $has_many = array('activity', 'contact', 'project', 'estimate', 'invoice');
	var $count = 0;
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => array('required', 'trim', 'max_length' => 200),
        ),
        array(
            'field' => 'view_key',
            'label' => 'view key',
            'rules' => array('always_validate', 'generate_viewkey'),
        ),
	);

	public function Company($data = array()) {
		parent::DataMapper();
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	/**
	 * If viewkey is not set (on create) generate a new viewkey. Companies are created throughout the system so
	 * it is necessary to bind a rule to this model instead of placing this check everywhere.
	 */
	public function _generate_viewkey($field) {
		$viewkey = $this->{$field};
		if (!$viewkey) {
			$viewkey = str_replace('-', '', md5(uniqid()));
			$this->{$field} = $viewkey;
		}
	}
	
	
	public function getPlainObject() {
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->name = $this->name;
		$resp->address = $this->address;
		$resp->city = $this->city;
		$resp->state = $this->state;
		$resp->zip = $this->zip;
		$resp->phone = $this->phone;
		return $resp;
	}
	
	
	/**
	 * Return address as a single line
	 */
	public function getAddress() {
		if (!$this->address && !$this->city && !$this->state && !$this->zip) return;
		
		$line = $this->address . "<br />";
		if ($this->city) {
			$line .= $this->city;
		}
		if ($this->state) {
			$line .= ', ' . $this->state;
		}
		if ($this->zip) {
			$line .= ' ' . $this->zip;
		}
		return $line;
	}
	
	/**
	 * Return address as a single line
	 */
	public function getAddressLine() {
		$line = $this->address;
		if ($this->city) {
			$line .= ', ' . $this->city;
		}
		if ($this->state) {
			$line .= ', ' . $this->state;
		}
		if ($this->zip) {
			$line .= ' ' . $this->zip;
		}
		return $line;
	}
	
	/**
	 * Return viewkey link
	 */
	public function getViewLink($redirect=null) {
		$link = '/welcome/index/?key=' . $this->view_key;
		if ($redirect) {
			$link .= '&redirect=' . rawurlencode($redirect);
		}
		return site_url($link);
	}
	
	/**
	 * Search companies
	 */
	public function search($criteria, $sort, $sort_direction, $offset, $limit) {
		if (!$sort) $sort = 'name';
		if (!$sort_direction) $sort_direction = 'asc';
		
		$this->start_cache();
		
		foreach($criteria as $key => $val) {
			$this->where($key, $val);
		}
		
		$this->count = $this->count();
		$this->stop_cache();
		return $this->order_by($sort, $sort_direction)->get($limit, $offset)->all;
	}
	
}