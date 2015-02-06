<?php
/**
 * User timelog
 */
class Timelog extends DataMapper {

	var $table = 'timelogs';
	var $has_one = array('task', 'user', 'invoice');
	
	var $validation = array(
        array(
            'field' => 'log_date',
            'label' => 'log date',
            'rules' => array('required'),
        ),
        array(
            'field' => 'hours',
            'label' => 'time',
            'rules' => array('required'),
            'get_rules' => array('convert_hours'),
        ),
        array(
            'field' => 'description',
            'label' => 'description',
            'rules' => array('required', 'trim', 'max_length' => 2000),
        ),
        array(
            'field' => 'user',
            'label' => 'user',
            'rules' => array('required'),
        ),
	);

	public function Timelog($data = array()) {
		parent::DataMapper();
    	$this->load->helper('data');
    	import_data($this, $data);
	}
	
	
	public function getPlainObject() {
		$resp = new stdClass();
		$resp->id = $this->id;
		$resp->logDate = date("m/d/Y", strtotime($this->log_date));
		$resp->hours = $this->convertTimeToHours($this->hours);
		$resp->description = $this->description;
		$resp->user = $this->user->getPlainObject();
		$resp->task = $this->task->getPlainObject();
		$resp->project = $this->task->project->getPlainObject();
		return $resp;
	}
	

	/**
	 * Convert field hours to an hh:mm timestamp
	 */
	public function _convert_hours($field) {
		$time = $this->{$field};
		$this->{$field} = $this->convertHoursToTime($time);
	}
	
	/**
	 * Convert numeric # of hours to hh:mm timestamp
	 */
	public function convertHoursToTime($time) {
		$hours = floor($time);
		$remainder = fmod($time, 1);
	
		$minutes = round($remainder * 60, 2);
		return sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes);
	}
	
	/**
	 * Convert timestamp to numeric # of hours
	 */
	public function convertTimeToHours($time) {
		if (!$time) return null;
		$split = explode(":", $time);
		if (sizeof($split) !=2) return null;
		
		$hours = $split[0];
		$minutes = $split[1];
		
		$total_hours = $hours;
		$total_hours += doubleval($minutes / 60);
		return doubleval($total_hours);
	}
	
	
	public function saveTimelog() {
	
		$ci =& get_instance();
		
		$associations = array();
		
		// handle postback
		if ($ci->input->server('REQUEST_METHOD') == 'POST') {
			
			$task_id = $ci->input->post('task_id');
			$this->description = $ci->input->post('description');
			
			// format hours
			$this->hours = $this->convertTimeToHours($ci->input->post('hours'));
			
			if ($ci->input->post('log_date')) {
				$end = strtotime($ci->input->post('log_date'));
				$end = date("Y-m-d 00:00:00", $end);
				$this->log_date = $end;
			}
			
			// task association
			$task = null;
			if ($ci->input->post('task_id')) {
				$task = new Task();
				$task = $task->where('id', $ci->input->post('task_id'))->get();
				if ($task->id) {
					$task_text = $task->project . ': ' . $task->name;
					$associations[] = $task;
				}
			} else {
				// remove association if it existed
				if ($this->task) {
					$this->delete($this->task);
				}
			}
			
			// user association
			$user = null;
			if ($ci->input->post('user')) {
				$user = new User();
				$user = $user->where('id', $ci->input->post('user'))->get();
				$associations[] = $user;
			} else {
				// remove association if it existed
				if ($this->user) {
					$this->delete($this->user);
				}
			}
			
			return $this->save($associations);	
		}
	}
		
}