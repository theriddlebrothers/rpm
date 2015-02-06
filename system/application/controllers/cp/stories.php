<?php

class Stories extends Admin_Controller {

	public function Stories() {
		parent::Admin_Controller();
	}
	
	function ajax($action) {
		switch($action) {
			case 'get':
				$project_id = $this->input->get('project');
				$stories = new Story();
				$stories->where_related('project', 'id', $project_id)->order_by('order', 'asc')->get();
				$data = array();
				foreach($stories->all as $s) {
					$storyObject = new stdClass();
					$storyObject->id = $s->id;
					$storyObject->description = $s->description;
					$storyObject->priority = $s->priority;
					$storyObject->status = $s->status;
					$storyObject->effort = $s->effort;
					$storyObject->notes = $s->notes;
					$data[] = $storyObject;
				}
				echo json_encode($data);
				break;
			case 'save':
				$project_id = $this->input->post('project');
				$project = new Project();
				$project->where('id', $project_id)->get();
				
				$ids = $this->input->post('ids');
				$descriptions = $this->input->post('descriptions');
				$statuses = $this->input->post('statuses');
				$priorities = $this->input->post('priorities');
				$efforts = $this->input->post('efforts');
				$notes = $this->input->post('notes');
				
				
				// Delete
				$delete = $this->input->post('delete');
				$story = new Story();
				for($i=0; $i<sizeof($descriptions); $i++) {
					$del = $delete[$i];
					if ($del == '1') {
						$story->where('id', $ids[$i])->get()->delete();
					}
				}
				
				$saved = array();
				for($i=0; $i<sizeof($descriptions); $i++) {
					$story = new Story();
					if ($ids[$i]) {
						$story->id = $ids[$i];
					}
					$story->description = $descriptions[$i];
					$story->status = $statuses[$i];
					$story->priority = $priorities[$i];
					$story->effort = $efforts[$i];
					$story->notes = $notes[$i];
					$story->order = $i;
					$story->save($project);
					$saved[] = $story->id;
				}
				echo json_encode($saved);
				
				break;
		}	
	}
	
	function index($project_id)
	{
		if (!user_can('view', 'stories')) access_error();
		
		$project = new Project();
		$project->where('id', $project_id)->get();

		// render
		$this->data['title'] = 'Stories';
		$this->data['project'] = $project;
		$this->render('stories/list');
	}
	
	function view($project_id) {
		if (!user_can('view', 'stories')) access_error();
		
		$project = new Project();
		$project->where('id', $project_id)->get();
		$project->stories->order_by('order', 'asc')->get();
		
		// render
		$this->data['title'] = 'Stories';
		$this->data['project'] = $project;
		$this->render('stories/view');
	}
	
}
