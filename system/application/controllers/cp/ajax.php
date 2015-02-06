<?php

class Ajax extends Admin_Controller {

	public function Ajax() {
		parent::Admin_Controller();
	}
	
	function companies($action) {
		switch($action) {
			case 'geturl':
				$company_name = $this->input->get('company');
				$link = $this->input->get('link');
				$link = rawurldecode($link);
				
				// remove domain from link
				$domain = 'http://' . $this->input->server('HTTP_HOST');
				$link = str_replace($domain, '', $link);
				
				// replace /cp/ with /client/
				$link = str_replace('/cp/', '/client/', $link);
				
				$company = new Company();
				$company->where('name', $company_name);
				$company->get();
				
				$resp = new stdClass();
				
				if ($company->id) {
					$resp->value =  $this->cloudapp->shorten($company->getViewLink($link));
				} else {
					$resp->value = false;
				}
				
				echo json_encode($resp);
				
				break;
		}
	}
	
	
	/**
	 * Retrieve files
	 * $param	integer		$id		Project ID
	 */
	function get_files($base, $folder) {
	
		$this->load->library('dropbox');
		
		$root_path = $this->config->item('root_path', 'dropbox');
		
		$base_path = $root_path . $base;
		$path = $base_path . $folder;
		
		// if $base is empty there will be double slash characters
		$path = str_replace('//', '/', $path);
		
		$response = $this->dropbox->getMetaData($path);
		$files = array();
		foreach($response['contents'] as $r) {
			$file = array(
				'thumb_exists'		=>	$r['thumb_exists'],
				'size'				=>	$r['size'],
				'modified'			=>	date("m/d/Y", strtotime($r['modified'])),
				'path'				=>	$r['path'],
				'relative_path'		=>	str_replace($base_path, '', $r['path']),
				'name'				=>	basename($r['path']),
				'is_dir'			=>	$r['is_dir'],
				'icon'				=>	$r['icon']
			);
			
			$file['url'] = $folder . '/' . $file['name'];				
			$files[] = $file;
		}
		
		return $files;
	}
	
	/**
	 * Retrieve files
	 * $param	integer		$id		Project ID
	 */
	function get_folders() {
		$root = $this->input->get('root');
		$folder = $this->input->get('folder');
		
		$files = $this->get_files($root, $folder);
		
		$response = new stdClass();
		$response->files = $files;
		$response->is_root = ($folder ? false : true);
		echo json_encode($response);
	}
	
	/**
	 * Retrieve files
	 * $param	integer		$id		Project ID
	 */
	function get_project_files($id) {
		
		if (!user_can('view', 'projects')) return;
		if (!user_can('view', 'files')) return;
				
		$projectFactory = new Project();
		
		$project = $projectFactory->where('id', $id)->get();
		if (!$project) redirect('projects/');
		
		$this->authorize($project->company->id);
		
		// get project files
		$files = array();
		
		if ($project->dropbox_folder) {
		
			$this->load->library('dropbox');
			
			$folder = $this->input->get('folder');
			if (substr($folder, strlen($folder)-2, 2) == '..') {
				$exp = explode('/', $folder);
				$f = '';
				for($i=0; $i<sizeof($exp)-2; $i++) {
					$f .= $exp[$i] . '/';
				}
				$folder = $f;
			}
			
			$root_path = $this->config->item('root_path', 'dropbox');
			$path = $root_path . $project->dropbox_folder . $folder;
			
			if ($this->input->get('dl')) {
				// download file
				$dl_path = $root_path . $project->dropbox_folder . $this->input->get('dl');
				$filename = basename($dl_path);
								
				// We'll be outputting a PDF
				header('Content-type: application');
				
				// It will be called downloaded.pdf
				header('Content-Disposition: attachment; filename="' . $filename . '"');
				
				echo $this->dropbox->getFile($dl_path);
			}
			
			$files = $this->get_files($project->dropbox_folder, $folder);
		}
		
		$response = new stdClass();
		$response->files = $files;
		$response->is_root = ($folder ? false : true);
		echo json_encode($response);
	}
	
	function search() {
		$query = $this->input->get('q');
		
		$data = array();
		
		// Search Projects
		$objects = new Project();
		$objects->like('name', $query)->or_like('project_number', $query)->get();
		foreach($objects->all as $o) {
			$data[$o->name . ' (P)'] =  "/cp/projects/view/" . $o->id;
		}
		
		// Search Estimates
		$objects = new Estimate();
		$objects->like('name', $query)->or_like('estimate_number', $query)->get();
		foreach($objects->all as $o) {
			$data[$o->name . ' (E)'] =  "/cp/estimates/view/" . $o->id;
		}
		
		// Search Companies
		$objects = new Company();
		$objects->like('name', $query)->get();
		foreach($objects->all as $o) {
			$data[$o->name . ' (C)'] =  "/cp/companies/view/" . $o->id;
		}
		
		// Search Invoices
		$objects = new Invoice();
		$objects->like('invoice_number', $query)->get();
		foreach($objects->all as $o) {
			$data['Invoice #' . $o->invoice_number . ' (I)'] =  "/cp/invoices/view/" . $o->id;
		}
		
		// Search Contacts
		$objects = new Contact();
		$objects->like('first_name', $query)->or_like('last_name', $query)->get();
		foreach($objects->all as $o) {
			$data[$o->first_name . ' ' . $o->last_name . ' (U)'] =  "/cp/contacts/view/" . $o->id;
		}
		
		ksort($data);
		
		foreach($data as $text=>$link) {
			echo $text.'|'.$link."\n";
		}
		
	}
	
	function users($action) {
		switch($action) {
			case 'checklogin':
				// check that current user is still logged in
				$resp = new stdClass();
				if ($this->session->userdata('user_id')) {
					$resp->value = 1;
				} else {
					$resp->value = 0;
				}
				echo json_encode($resp);
				break;
		}

	}
	
}
