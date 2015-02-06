<?php
class Wiki extends Admin_Controller {

	
	public function edit($name=null) {
		$page = new WikiPage();
		if ($name) {
			$page->where('name', $name)->get();
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$page->content = $this->input->post('content');
			$page->name = $name;
			$page->save();
			
			$this->session->set_flashdata('success', $name . ' was saved.');
			redirect('wiki/page/' . $name);
		}
		
		$this->data['title'] = 'Edit: ' . $name;
		$this->data['page'] = $page;
		$this->render('wiki/edit');
	}
	
	public function index() {
		redirect('wiki/page/Home');
	}
	
	public function page($name) {
	
		$wiki_page = new WikiPage();
		$wiki_page->where('name', $name)->get();
		
		$this->data['name'] = $name;
		
		if ($wiki_page->id) {
			// page not found. display not found text
			$content = $wiki_page->content;
		} else {
			$this->data['title'] = $name;
			$this->render('wiki/not-found');
			return;
		}
		
		// page found.
		$this->load->library('markup'); 
		$wiki_page->content = $this->markup->translate($wiki_page->content);
		
		$this->data['title'] = $name;
		$this->data['page'] = $wiki_page;
		$this->render('wiki/page');
	}
	
	public function search() {
		$query = $this->input->post('search');
		
		$pages = new WikiPage();
		$pages->like('name', '%' . $query . '%')->or_like('content', '%' . $query . '%')->get();
		
		$this->data['pages'] = $pages;
		$this->data['title'] = 'Search';
		$this->render('wiki/search');
	}

}