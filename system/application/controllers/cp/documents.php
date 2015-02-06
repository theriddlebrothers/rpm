<?php

class Documents extends Admin_Controller {

	public $doc_id = null;
	public $requirements_indexes = array();
	
	public function Documents() {
		parent::Admin_Controller();
	}
	
	/**
	 * Handle ajax callbacks
	 */
	function ax($action) {
		switch($action) {
			case 'section_sort':
				$this->section_sort();
				$response = new stdClass();
				$response->result = true;
				echo json_encode($response);
				break;
		}
	}
	
	/**
	 * Build heirarchical array of document sections
	 * @param	array		$rows			Flat array of rows
	 * @param	string		$parent_column	Column key of parent row
	 */
	function build_tree($rows, $parent_column='parent') {
		$index = array();
	  	$tree = array();  //stores the tree
	  	$tree_index = array();  //an array used to quickly find nodes in the tree

		while(count($rows) > 0){
	 
	    foreach($rows as $row_id => $row){
	   
	      if($row[$parent_column]){
	        if((!array_key_exists($row[$parent_column], $rows)) and (!array_key_exists($row[$parent_column], $tree_index))){
	           unset($rows[$row_id]);
	        }
	        else{
	          if(array_key_exists($row[$parent_column], $tree_index)){
	            $parent = & $tree_index[$row[$parent_column]];
	            $parent['children'][$row_id] = array("node" => $row, "children" => array());
	            $tree_index[$row_id] = & $parent['children'][$row_id];
	            unset($rows[$row_id]);
	          }
	        }
	      }
	      else{
	        $tree[$row_id] = array("node" => $row, "children" => array());
	        $tree_index[$row_id] = & $tree[$row_id];
	        unset($rows[$row_id]);
	      }
	    }
	  }
	  return $tree;
	}
	
	/**
	 * Create new document
	 */
	function create($type, $template=null) {
		if (!user_can('create', 'documents')) access_error();
                $this->edit_document(null, $type, $template);

	}
	
	/**
	 * Builds full output when viewing compiled document
	 */
	function create_compiled($rows){
	  $index = array();
	  $tree = array();  //stores the tree
	  $tree_index = array();  //an array used to quickly find nodes in the tree
	  $id_column = "id";  //The column that contains the id of each node
	  $parent_column = "parent";  //The column that contains the id of each node's parent
	  $this->requirements_indexes = array();
	  
	  //build the tree - this will complete in a single pass if no parents are defined after children
	  $tree = $this->build_tree($rows);
	  
	  //we are done with index now so free it
	  unset($tree_index);
	  $html = '';
	  $i = 1;
	  if ($tree) {
		  foreach($tree as $node){
		    //go to each top level node and print it and it's children
		    $index = $i . '.';
		    $html .= $this->print_object($node, 1, 1, $index);
		    $i++;
		  }
	  }
	  return $html;
	}

	
	/**
	 * Create a dropdown menu of heirarchical sections
	 */
	function create_dropdown($rows, $selected){
	  $tree = array();  //stores the tree
	  $tree_index = array();  //an array used to quickly find nodes in the tree
	  $id_column = "id";  //The column that contains the id of each node
	  $parent_column = "parent";  //The column that contains the id of each node's parent
	  $text_column = "title";  //The column to display when printing the tree to html
	    
	  //build the tree - this will complete in a single pass if no parents are defined after children
	  $tree = $this->build_tree($rows);
	  
	  //we are done with index now so free it
	  unset($tree_index);
	  $html = '';
	  if ($tree) {
		  foreach($tree as $node){
		    //go to each top level node and print it and it's children
		    $html .= $this->print_option($node, $text_column, 1, 1, $selected);
		  }
	  }
	  return $html;
	}
        
        
	/**
	 * Create from template
	 */
	function create_from_template($id){
            
            
            //get template information, fields, etc...
            $doc = new Doc();
		$doc->where('id', $id);
		$doc->get();
		$this->doc_id = $doc->id;
                
             $sections = array();
		foreach($doc->docsections->order_by('order', 'asc')->get()->all as $sec) {
			$sections[$sec->id] = array(
				'id'				=>	$sec->id,
				'title'				=>	$sec->title,
				'parent_section'	=>	$sec->parent_section
			);
		}
		
		$types = new Docsectiontype();
		$types->order_by('name', 'ASC')->get();
		$type_dropdown = array(''=>'Select a section type...');
		foreach($types as $t) {
			$type_dropdown[$t->id] = $t->name;
		}
		$this->data['types'] = $type_dropdown;
		$this->data['olddoc'] = $doc;
		$this->data['tree'] = $this->create_list($sections);
		//$this->render('documents/view');   
            
            
            
                //get the template document and fields
                $newdoc = new Doc();
		if ($id != null) {
			$newdoc->where('id', $id)->get();
			if ($newdoc->project) $project_id = $newdoc->project->id;
			$type = $newdoc->doctype->id;
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
		}
                

		
		// get document and fields
		$doctype = new Doctype();
		$doctype->where('id', $type)->get();
		
		$fields = $doctype->docfields->order_by('order', 'asc')->get()->all;
                
                
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($newdoc);
			$newdoc->doc_date = date("Y-m-d H:i:s");
			$associations = array();
			$newdoc->is_template = 0;
			// project
			if ($this->input->post('project')) {
				$project = new Project();
				$project->where('id', $this->input->post('project'))->get();
				$associations[] = $project;
				$newdoc->project = $project;
			} else {
				$newdoc->delete($newdoc->project->all);
				$newdoc->refresh_all();
			}
			
			// document type
			if ($this->input->post('type')) {
				$type = new Doctype();
				$type->where('id', $this->input->post('type'))->get();
				$associations[] = $type;
			} 
			
			// field values
			if ($fields) {
				foreach($fields as $f) {
					// retrieve field value
					$value = $this->input->post($f->name);
					$field_value = new Docfieldvalue();
					$field_value->value = $value;
					$field_value->save($f);
					$associations[] = $field_value;
				}
			}
			
			if ($newdoc->save($associations)) {
				// save
				$this->session->set_flashdata('success', $newdoc->title . ' was saved.');
				redirect('documents/');
			} else {
				// invalid
				$this->data['errors'] = $newdoc->error->string;
			}		
		}
		
		$projectsFactory = new Project();
		
		
		if ($id) {
			// get fields for this document
			$doc_fields = $newdoc->docfieldvalue->get()->all;
			// populate field values with stored document info
			foreach($fields as &$field) {
				foreach($doc_fields as $doc_field) {
					if ($doc_field->docfield->name == $field->name) {
						$field->value = $doc_field->value;
					}
				}
			}
		}
		
		// render template
                
                   $this->data['template'] = 0;
                    $this->data['type'] = $type;
                    $this->data['fields'] = $fields;
                    $this->data['project_id'] = $project_id;
                    $this->data['projects'] = $projectsFactory->order_by('project_number', 'desc')->get()->all;
                    $this->data['doc'] = $newdoc;
                    if(!$newdoc->id) $this->data['title'] = 'Create ' . $doctype->name;
                    else $this->data['title'] = 'Edit ' . $doc->title;
                    $this->render('documents/edit');
            
            
        }
        
	
	/**
	 * Create a heirarchical list of sections
	 */
	function create_list($rows){
	  $tree = array();  //stores the tree
	  $tree_index = array();  //an array used to quickly find nodes in the tree
	  $id_column = "id";  //The column that contains the id of each node
	  $parent_column = "parent_section";  //The column that contains the id of each node's parent
	  $text_column = "title";  //The column to display when printing the tree to html
	    
	  //build the tree - this will complete in a single pass if no parents are defined after children
	  $tree = $this->build_tree($rows, $parent_column);
	  
	  //we are done with index now so free it
	  unset($tree_index);
	  $html = '';
	  if ($tree) {
		  //start printing out the tree
		  $html = "    <div id='tree'>\n";
		  $html .= "      <ul class=\"sortable\">\n";
		  foreach($tree as $node){
		    //go to each top level node and print it and it's children
		    $html .= $this->print_tree($node, $text_column, 8, 2);
		  }
		  $html .= "      </ul>\n";
		  $html .= "  </div>\n";
	  }
	  return $html;
	}
        
        
        /**
	 *  Template Edit Area
	 */
        function templates()
        {
            if (!user_can('view', 'documents')) access_error();
		
		// setup
		$docFactory = new Doc();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$docFactory->start_cache();
		
		if ($this->current_company) {
			$docFactory->where_related('project/company', 'id', $this->current_company->id);
		}
		
		// project filter
		if ($this->input->get('project')) {
			$docFactory->where_related('project', 'id', $this->input->get('project'));
		}
		
		$count = $docFactory->count();
		$documents = $docFactory->order_by('title')->where('is_template', 1)->get($limit, $offset)->all;
		$docFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('docs/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config);
		
		$types = array(''	=>	'Select a document...');
		$doc_types = new Doctype();
		$doc_types->get();
		foreach($doc_types->all as $t) {
			$types[$t->id] = $t->name;
		}

		// render
		$this->data['types'] = $types; 
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['documents'] = $documents;
		$this->data['title'] = 'Document Templates';
		$this->render('documents/template-list');
            
        }
        
	
	/**
	 * View printable document
	 */
	function compile($id, $doc=false) {
			
		$doc = new Doc();
		$doc->where('id', $id)->get();
		
		$raw_sections = $doc->docsection->order_by('order', 'asc')->get()->all;
		$sections = array();
		foreach($raw_sections as $sec) {
			$sections[$sec->id] = array(
				'id'		=>	$sec->id,
				'title'		=>	$sec->title,
				'parent'	=>	$sec->parent_section,
				'object'	=>	$sec
			);
		}
		
		if ($doc->doctype->id == DOCTYPE_CREATIVE) {
			$compiled = $this->compile_form($doc);
		} else {
			$compiled = $this->create_compiled($sections);
		}
		
		$this->data['title'] = $doc->title;
		$this->data['doc'] = $doc;
		$this->data['compiled'] = $compiled;
		$this->render('documents/compile');
	}
	
	/**
	 * Display a form document type
	 */
	function compile_form($doc) {
		$fields = $doc->doctype->docfields->order_by('order', 'asc')->get()->all;
		// get fields for this document
		$doc_fields = $doc->docfieldvalue->get()->all;
		// populate field values with stored document info
		foreach($fields as &$field) {
			foreach($doc_fields as $doc_field) {
				if ($doc_field->docfield->name == $field->name) {
					$field->value = $doc_field->value;
				}
			}
		}
		
		return $this->load->view('cp/documents/compiled/form', array('fields'=>$fields), true);
	}
	
	/**
	 * Delete document
	 */
	function delete($type, $id) {
		if (!user_can('delete', 'documents')) access_error();

		if ($type == 'section') {
			$section = new Docsection();
			$section->where('id', $id)->get();
			$doc_id = $section->doc->id;
			$this->session->set_flashdata('success', "Section " . $section->title . " was deleted.");
			$section->delete();
			redirect('documents/view/' . $doc_id);
		} elseif ($type == 'document') {
			$doc = new Doc();
			$doc->where('id', $id)->get();
			$this->session->set_flashdata('success', "Document " . $doc->title . " was deleted.");
			$doc->delete();
			redirect('documents');
		}
	}
        
        
       
        
        
	/**
	 * Edit existing document
	 */
	function edit_document($id=null, $type=null, $template=null) {
		if (!user_can('edit', 'documents')) access_error();
		
		// setup
		$project_id = null;
		$this->data['errors'] = null;
		$doc = new Doc();
		if ($id != null) {
			$doc->where('id', $id)->get();
			if ($doc->project) $project_id = $doc->project->id;
			$type = $doc->doctype->id;
		} elseif($this->input->server('REQUEST_METHOD') != 'POST') {
			// set some defaults
		}
                
                //set if is template
                $doc->is_template = $template;
               
		
		// get document and fields
		$doctype = new Doctype();
		$doctype->where('id', $type)->get();
		
		$fields = $doctype->docfields->order_by('order', 'asc')->get()->all;
		
		// handle postback
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			autobind($doc);
			$doc->doc_date = date("Y-m-d H:i:s");
			$associations = array();
			
			// project
			if ($this->input->post('project')) {
				$project = new Project();
				$project->where('id', $this->input->post('project'))->get();
				$associations[] = $project;
				$doc->project = $project;
			} else {
				$doc->delete($doc->project->all);
				$doc->refresh_all();
			}
			
			// document type
			if ($this->input->post('type')) {
				$type = new Doctype();
				$type->where('id', $this->input->post('type'))->get();
				$associations[] = $type;
			} 
			
			// field values
			if ($fields) {
				foreach($fields as $f) {
					// retrieve field value
					$value = $this->input->post($f->name);
					$field_value = new Docfieldvalue();
					$field_value->value = $value;
					$field_value->save($f);
					$associations[] = $field_value;
				}
			}
			
			if ($doc->save($associations)) {
				// save
				$this->session->set_flashdata('success', $doc->title . ' was saved.');
				redirect('documents/');
			} else {
				// invalid
				$this->data['errors'] = $doc->error->string;
			}		
		}
		
		$projectsFactory = new Project();
		
		
		if ($id) {
			// get fields for this document
			$doc_fields = $doc->docfieldvalue->get()->all;
			// populate field values with stored document info
			foreach($fields as &$field) {
				foreach($doc_fields as $doc_field) {
					if ($doc_field->docfield->name == $field->name) {
						$field->value = $doc_field->value;
					}
				}
			}
		}
		
		// render template
                
                    $this->data['template'] = $template;
                    $this->data['type'] = $type;
                    $this->data['fields'] = $fields;
                    $this->data['project_id'] = $project_id;
                    $this->data['projects'] = $projectsFactory->order_by('project_number', 'desc')->get()->all;
                    $this->data['doc'] = $doc;
                    if(!$doc->id) $this->data['title'] = 'Create ' . $doctype->name;
                    else $this->data['title'] = 'Edit ' . $doc->title;
                    $this->render('documents/edit');
               
                    
                   
                
		
	}
	
	/**
	 * Export a file to specified type
	 */
	function export($type, $id) {
		$this->data['showlinks'] = false;
		if ($type == 'pdf') $this->export_pdf($id);
		else if ($type == 'word') $this->export_word($id);
	}
	
	/**
	 * Export document as MS Word
	 */
	function export_word($id) {
		
		$doc = new Doc();
		$doc->where('id', $id)->get();
		
		$compiled = null;
		
		if ($doc->doctype->id == DOCTYPE_CREATIVE) { 
			$compiled = $this->compile_form($doc);
		} else { 		
			$raw_sections = $doc->docsection->order_by('order', 'asc')->get()->all;
			$sections = array();
			foreach($raw_sections as $sec) {
				$sections[$sec->id] = array(
					'id'		=>	$sec->id,
					'title'		=>	$sec->title,
					'parent'	=>	$sec->parent_section,
					'object'	=>	$sec
				);
			}
			
			$compiled = $this->create_compiled($sections);
		}
		
		$this->data['doc'] = $doc;
		$this->data['compiled'] = $compiled;
		$this->data['title'] = $doc->title;
		
		$html = $this->load->view('cp/documents/export', $this->data, true);
			
		$this->load->library('html2doc');
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($html, $doc->title, true);
		return;
	}
	
	/**
	 * Export document as PDF
	 */
	function export_pdf($id) {
		$doc = new Doc();
		$doc->where('id', $id)->get();
		
		$raw_sections = $doc->docsection->order_by('order', 'asc')->get()->all;
		$sections = array();
		foreach($raw_sections as $sec) {
			$sections[$sec->id] = array(
				'id'		=>	$sec->id,
				'title'		=>	$sec->title,
				'parent'	=>	$sec->parent_section,
				'object'	=>	$sec
			);
		}
		
		$html = $this->create_compiled($sections);
		
		$this->load->library('pdf');
		$pdf = new PDF();
		$pdf->SetFont('arial', '', 24);
		$pdf->SetMargins(10, 10);
		
		// cover page
		/*$pdf->AddPage();
		$pdf->SetXY(10,10);
		$pdf->WriteHTML('<img src="http://pm.theriddlebrothers.net/images/logo.png" />');
		$pdf->SetXY(10,30);
		$pdf->WriteHTML('<h1 align="right">' . $doc->title . '</h1>');
		$pdf->WriteHTML('<h3 align="right">for</h3>');
		$pdf->WriteHTML('<h2 align="right">Maryland Hall</h2>');
		$pdf->WriteHTML('<h4 align="right">Version 1.0</h4>');
		$pdf->WriteHTML('<h4 align="right">Pomerantz Agency</h4>');
		$pdf->WriteHTML('<h4 align="right">' . date("M d, Y") . "</h4>");*/
		
    	//$pdf->Write(10,$doc->title,1,0,'R');
    
		$pdf->AddPage();
		$pdf->WriteHTML($html);
		$pdf->Output();
		return;
	}
	
	/**
	 * List existing documents
	 */
	public function index() {
		if (!user_can('view', 'documents')) access_error();
                
                
		
		// setup
		$docFactory = new Doc();
		
		$offset = $this->input->get('per_page');
		$limit = ($this->input->get('limit') ? $this->input->get('limit') : RB_ADMIN_PER_PAGE);
		
		$docFactory->start_cache();
		
		if ($this->current_company) {
			$docFactory->where_related('project/company', 'id', $this->current_company->id);
		}
		
		// project filter
		if ($this->input->get('project')) {
			$docFactory->where_related('project', 'id', $this->input->get('project'));
		}
		
		$count = $docFactory->count();
		$documents = $docFactory->order_by('title')->where('is_template', 0)->get($limit, $offset)->all;
		$docFactory->stop_cache();
		
		// pagination
		$config['base_url'] = site_url('docs/index/?limit=' . $limit);
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		
		$this->pagination->initialize($config);
		
		$types = array(''	=>	'Select a document...');
		$doc_types = new Doctype();
		$doc_types->get();
		foreach($doc_types->all as $t) {
			$types[$t->id] = $t->name;
		}

		// render
		$this->data['types'] = $types; 
		$this->data['pager'] = $this->pagination->create_links();
		$this->data['documents'] = $documents;
		$this->data['title'] = 'Documents';
		$this->render('documents/list');
	}
	
	/**
	 * Create numerical index of parent/child items
	 * @param	string		$index		Current index 
	 */
	function parse_index($index) {		
		$exp = explode('.', $index);
		$exp = array_filter($exp, array($this,'remove_empty'));
		$exp = array_slice($exp, 0, count($exp)-1);
		$index = implode('.', $exp) . '.';
		return $index;
	}
	
	/**
	 * Recursive function to print full node HTML
	 */
	function print_object($node, $indent, $indent_size, &$index){
	  $section = $node['node']['object'];
	  
	  // update indexes per-type
	  if (!isset($this->requirements_indexes[$section->docsectiontype->id])) $this->requirements_indexes[$section->docsectiontype->id] = 1;
	  
	  $requirements_index = $this->requirements_indexes[$section->docsectiontype->id];
	  $section->requirements_index = $requirements_index;
	  
	  // $index[1] = array();
	  $html = '<h' . $indent . '>' . $index . ' ';
	  $html .= $section->title;
	  $html .= '</h' . $indent . '>';
	  
	  if ($section->priority) $html .= '<p><strong>Priority:</strong> ' . $section->priority . '</p>';
	  
	  $html .= $section->output();
	  $this->requirements_indexes[$section->docsectiontype->id] = $section->requirements_index;
	  
	  if($node['children']){
	    //then print it's children nodes
	    $i = 1;
        
	    foreach($node['children'] as $child){
	    
			if ($i > 1 ) {
				$index = $this->parse_index($index);
			}
	        
	        $index .= $i . '.';
	        $html .= $this->print_object($child, $indent + $indent_size, $indent_size, $index);
	        $i++;
	    }
	    
	    $index = $this->parse_index($index);
	  } 
	  return $html;
	}
	
	/**
	 * Recursive function used to print tree structure to option elements
	 */
	function print_option($node, $text_column, $indent, $indent_size, $selected){
	  //print the current node
	  $html = "<option";
	  if ($selected == $node['node']['id']) $html .= ' selected="selected" ';
	  $html .= " value=\"" . $node['node']['id'] . "\">" . str_repeat("-", $indent + $indent_size) . $node['node'][$text_column] . '</option>';
	  if($node['children']){
	    //then print it's children nodes
	    foreach($node['children'] as $child){
	      $html .= $this->print_option($child, $text_column, $indent + $indent_size * 2, $indent_size, $selected);
	    }
	  }
	  return $html;
	}
	
	
	/**
	 * Recursive function used to print tree structure to li elements
	 */
	function print_tree($node, $text_column, $indent, $indent_size){
	  //print the current node
	  $html = str_repeat(" ", $indent) . "<li id=\"list_" . $node['node']['id'] . "\"><div><a href=\"" . site_url('documents/section/' . $this->doc_id . '/' . $node['node']['id']) . "\">". $node['node'][$text_column] . '</a> <a href="' . site_url('documents/delete/section/' . $node['node']['id']) . '" class="icon delete popup">Delete</a></div>';
	  if($node['children']){
	    $html .= "\n". str_repeat(" ", $indent + $indent_size) . "<ul>\n";
	    //then print it's children nodes
	    foreach($node['children'] as $child){
	      $html .= $this->print_tree($child, $text_column, $indent + $indent_size * 2, $indent_size);
	    }
	    $html .= str_repeat(" ", $indent + $indent_size) . "</ul>\n". str_repeat(" ", $indent);
	  }
	  $html .= "</li>\n";
	  return $html;
	}
	
	/**
	 * Remove empty items from an array
	 */
	function remove_empty($ar) {
	  return (!empty($ar));
	}
	
	/**
	 * Save requirement section
	 */
	function save_requirement($section) {
	
		// remove previous requirement lines
		$section->delete($section->docrequirement->all);
		$section->refresh_all();
		
		// save new line items
		$names = $this->input->post('name');
		$descriptions = $this->input->post('description');
		$num = count($names);
		for($i=0; $i<$num; $i++) {
                    $req = new Docrequirement();
                    $req->name = $names[$i];
                    $req->description = $descriptions[$i];
                    $req->save($section);
		}		
	}
	
	/**
	 * Save basic text section
	 */
	function save_text($section) {
		$content = $section->doccontent;
		if (!$content->exists()) {
			$content = new Doccontent();
		}
		autobind($content);
		$content->save($section);
	}
	
	/**
	 * Save use case section
	 */
	function save_usecase($section) {
		$usecase = $section->docusecase;
		if (!$usecase->exists()) {
			$usecase = new Docusecase();
		}
		if (!$usecase->date_created) $usecase->date_created = date("Y-m-d H:i:s");
		$usecase->date_updated = date("Y-m-d H:i:s");
		autobind($usecase);
		$usecase->save($section);
	}
	
	/**
	 * Edit a section
	 */
	function section($doc_id, $id, $type_id=null) {
		
		$this->load->helper('form');
		
		$doc = new Doc();
		$doc->where('id', $doc_id)->get();
		
		$section  = new Docsection();
		$type = new Docsectiontype();
		$line_items = array();
		
		if ($id) {
			// Retrieve existing
			$section->where('id', $id)->get();
			$section->doccontent->get();
			$section->docusecase->get();
			$type = $section->docsectiontype->get();
			$line_items = $section->getLineItemsAsArray($this->input);
		} else {
			$type->where('id', $type_id)->get();
			if (!$type->exists()) {
				die("Invalid section type.");
			}
			// Defaults			
			if (!$line_items) {
				$line_items[] = array('name'		=>	'',
									  'description'	=>	'');
			}
		} 
		
		// save
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    if ($this->input->post('title')) {
			$section->title = $this->input->post('title');
			$section->parent_section = ($this->input->post('parent') ? $this->input->post('parent') : null);
			$section->priority = $this->input->post('priority');
			$section->save(array($doc,$type));
			if (!$type->code) {
				$this->save_text($section);
			} elseif($type->code == 'UC') {
				$this->save_usecase($section);
			} else {
				$this->save_requirement($section);
			}
			$this->session->set_flashdata('success', $section->title . ' was saved.');
			redirect('documents/view/' . $section->doc->id);
                    } else {
			$this->session->set_flashdata('error', 'Section title is required.');
			redirect('documents/section/' . $doc->id . '/0/' . $type->id);
                    }
		}
		
		$sections = array();
		$docsection = new Docsection();
		$docsection->where_related('doc', 'id', $doc->id);
		$docsection->order_by('order', 'asc')->get();
		foreach($docsection->all as $sec) {
			if ($sec->parent_section && (($sec->parent_section == $section->id) ||
			($sec->id == $section->id))) continue;
			$sections[$sec->id] = array(
				'id'		=>	$sec->id,
				'title'		=>	$sec->title,
				'parent'	=>	$sec->parent_section
			);
		}
		
		$this->data['priorities'] = array(
			''			=>	'Select a priority...',
			'Low'		=>	'Low',
			'Medium'	=>	'Medium',
			'High'		=>	'High'
		);
		
		$viewname = strtolower($type->code);
		if (!$viewname) $viewname = 'text';
		
		$this->data['section'] = $section;
		$this->data['type'] = $type;
		$this->data['doc'] = $doc;
		$this->data['line_items'] = $line_items;
		$this->data['section_dropdown'] = $this->create_dropdown($sections, $section->parent_section);
		$this->data['section_dropdown'];
		$this->render('documents/section/' . $viewname);
	}
	
	
	/**
	 * Store new sorting of sections
	 */
	function section_sort() {
		$doc_id = $this->input->post('doc');
		$url = $this->input->post('sections');
		if (!$url) return;
		parse_str($url);
		
		$doc = new Doc();
		$doc->where('id', $doc_id)->get();
		if (!$doc->exists()) return;
		
		$sections = array();
		foreach($doc->docsection->all as $section) {
			$sections[$section->id] = $section;
		}
		
		$index = 0;
		foreach($list as $id => $parent) {
			if ($parent == "root") {
				// no parent
				$sections[$id]->parent_section = null;
			} else {
				$sections[$id]->parent_section = $parent;
			}
			$sections[$id]->order = $index;
			$sections[$id]->save();
			$index++;
		}
	}
	
	/**
	 * View document and manage sections
	 */
	function view($id) {
		$doc = new Doc();
		$doc->where('id', $id);
		$doc->get();
		$this->doc_id = $doc->id;
		
		if (!$doc->exists()) {
			$this->render('documents/not-found');
			return;
		}
		$sections = array();
		foreach($doc->docsections->order_by('order', 'asc')->get()->all as $sec) {
			$sections[$sec->id] = array(
				'id'				=>	$sec->id,
				'title'				=>	$sec->title,
				'parent_section'	=>	$sec->parent_section
			);
		}
		
		$types = new Docsectiontype();
		$types->order_by('name', 'ASC')->get();
		$type_dropdown = array(''=>'Select a section type...');
		foreach($types as $t) {
			$type_dropdown[$t->id] = $t->name;
		}
		$this->data['types'] = $type_dropdown;
		$this->data['doc'] = $doc;
		$this->data['tree'] = $this->create_list($sections);
		$this->render('documents/view');
	}
        
        
        

}