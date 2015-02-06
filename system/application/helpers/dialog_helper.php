<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Helper for displaying various types of dialogs used for creating or selecting objects.
 */

/**
 * Display "Create a Document" dialog.
 * @param	string		$element	jQuery ID selector to bind dialog to (i.e. '#create-link')
 */
function dialog_create_document($element) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/create-document', array('element'=>$element));
}


/**
 * Display "Create a Document From Template" dialog.
 * @param	string		$element	jQuery ID selector to bind dialog to (i.e. '#create-link')
 */
function dialog_create_document_from_template($element) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/create-document-from-template', array('element'=>$element));
}


/**
 * Display "Create new Template" dialog.
 * @param	string		$element	jQuery ID selector to bind dialog to (i.e. '#create-link')
 */
function dialog_create_new_template($element) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/create-new-template', array('element'=>$element));
}

/**
 * Display "Select a Company" dialog.
 * @param	Company		$company		Currently selected company
 */
function dialog_select_company($company) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/select-company', array('company'=>$company));
}

/**
 * Display "Select a Folder" dialog.
 * @param	User		$user		Currently selected path
 */
function dialog_select_folder($path, $root='', $label='Dropbox Folder') {
	$ci =& get_instance();
	
	// load dropbox folders in root
	$files = array();
	$ci->load->library('dropbox');
	$root_path = $ci->config->item('root_path', 'dropbox');
	$dropbox_path = $root_path . $root;
	$response = $ci->dropbox->getMetaData($dropbox_path);
	foreach($response['contents'] as $r) {
		$file = array(
			'thumb_exists'		=>	$r['thumb_exists'],
			'size'				=>	$r['size'],
			'modified'			=>	date("m/d/Y", strtotime($r['modified'])),
			'path'				=>	str_replace($dropbox_path, '', $r['path']),
			'name'				=>	basename($r['path']),
			'is_dir'			=>	$r['is_dir'],
			'icon'				=>	$r['icon']
		);
		
		$file['url'] = $root . '/' . $file['name'];				
		$files[] = $file;
	}
	
	$ci->load->view('cp/dialogs/select-folder', array(	'path'	=>	$path, 
														'files'	=>	$files,
														'root'	=>	$root, 
														'label'	=>	$label
													));
}

/**
 * Display "Select a Project" dialog.
 * @param	Project		$project		Currently selected project
 */
function dialog_select_project($project, $label='Project') {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/select-project', array('project'=>$project, 'label'=>$label));
}

/**
 * Display "Select a Template" dialog.
 * @param	Project		$project		Currently selected project
 */
function dialog_select_template($template, $label='Template') {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/select-template', array('template'=>$template, 'label'=>$label));
}

/**
 * Display "Select a Task" dialog.
 * @param	Task		$task		Currently selected task
 */
function dialog_select_task($task) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/select-task', array('task'=>$task));
}

/**
 * Display "Select a User" dialog.
 * @param	User		$user		Currently selected user
 */
function dialog_select_user($user) {
	$ci =& get_instance();
	$ci->load->view('cp/dialogs/select-user', array('user'=>$user));
}