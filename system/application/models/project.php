<?php
/**
 * Project model
 */
class Project extends DataMapper {

	var $table = 'projects';
	var $has_one = array('company', 'retainer',
		'project' => array(
	   		'other_field'	=>	'parentproject',
	   	),
    	'parentproject'	=>	array(
    		'class'			=>	'project',
	        'other_field'	=>	'project',
	        'reciprocal'	=>	TRUE
    	)
    );
    var $has_many = array(
    	'estimate', 'task', 'invoice', 'contact', 'statusreport', 'user', 'doc', 'issue', 'story',
    );
    
	var $_total_invoiced = 0;
	var $_total_estimated = 0;
	var $_accepted_estimated = 0;
	
	var $validation = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 200),
        ),

        array(
            'field' => 'start_date',
            'label' => 'start date',
            'rules' => array('required', 'trim')
        ),

        array(
            'field' => 'project_number',
            'label' => 'project number',
            'rules' => array('required', 'trim', 'max_length' => 15)
        ),

        array(
            'field' => 'project_type',
            'label' => 'project type',
            'rules' => array('required', 'trim')
        ),
        array(
            'field' => 'billable_rate',
            'label' => 'billable rate',
            'rules' => array('required', 'numeric')
        ),
        
        array(
            'field' => 'company',
            'label' => 'company',
            'rules' => array('required'),
        ),
        
        array(
            'field' => 'status',
            'label' => 'status',
            'rules' => array('required', 'trim'),
        ),
        
	);

	public function Project($data = array()) {
		parent::DataMapper();
		
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	public function getPlainObject() {
	
		$this->parentproject->get();
		$this->retainer->get();
	
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->name = $this->name;
		$resp->projectNumber = $this->fullProjectNumber();
		$resp->company = $this->company->getPlainObject();
		
		$retainer = $this->retainer;
		if (!$retainer->exists() && $this->parentproject->exists()) {
			$retainer = $this->parentproject->retainer;
		}
		
		$resp->retainer = $retainer->getPlainObject();
		
		// Takes up too much memory, don't send down as children
		// Timelogs
		/*$resp->currentTimelogs = array();
		$period_start = $this->retainer->start_date;
		$period_end = strtotime(date("Y-m-d", strtotime($period_start)) . " +1 month");
		foreach($this->task as $task) {
			foreach($task->timelog as $timelog) {
				if ($timelog->log_date >= $period_start && $timelog->log_date < $period_end) {
					$resp->currentTimelogs[] = $timelog->getPlainObject();	
				}
			}
		}*/
		
		return $resp;
	}
	
	public function fullProjectNumber() {
		return date("y", strtotime($this->start_date)) . '-RID-' . sprintf("%03s", $this->project_number);
	}
	
	public function getLastInvoice() {
		return $this->invoice->order_by('invoice_date')->limit(1)->get();
	}
	
	public function getTotalInvoiced() {
		if ($this->_total_invoiced) return $this->_total_invoiced;
		$total = 0;
		$this->invoice->get();
		foreach($this->invoice->all as $invoice) {
			$total += $invoice->getTotal();
		}
		$this->_total_invoiced = $total;
		return $total;
	}
	
	public function getRemainingInvoice() {
		$remaining = ($this->getTotalAcceptedEstimated() - $this->getTotalInvoiced());
		if ($remaining > 0) return $remaining;
		return 0;
	}
	
	public function getTotalAcceptedEstimated() {
		if ($this->_accepted_estimated) return $this->_accepted_estimated;
		$total = 0;
		$this->estimate->where('status', 'Approved')->get();
		foreach($this->estimate->all as $estimate) {
			$total += $estimate->getTotalEstimated();
		}
		$this->_accepted_estimated = $total;
		return $total;
	}
	
	public function getTotalEstimated() {
		if ($this->_total_estimated) return $this->_total_estimated;
		$total = 0;
		$this->estimate->get();
		foreach($this->estimate->all as $estimate) {
			$total += $estimate->getTotalEstimated();
		}
		$this->_total_estimated = $total;
		return $total;
	}
	
}