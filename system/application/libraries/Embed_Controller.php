<?php
/**
 * Renders a template to be displayed in an iframe.
 *
 * Template is fit to render in an iframe width of 900px
 *
 * Various parameters may be passed to URLs using this controller to 
 * customize the interface. Each controller may have GET or POST variables
 * that may be used. These are the global parameters used by the template:
 *
 * $_GET['stylesheet']	string		Absolute URL to stylesheet to be linked to in head tag of iframe
 */
class Embed_Controller extends Public_Controller {

	protected $data = array();
	protected $directory = 'embed/';
	protected $template = 'embed';
	protected $view_key = null;
	
	public function Embed_Controller() {
		parent::Public_Controller();
		
		// load view key which validates the embed form
		$key = $this->input->get('key');
		
		$company = new Company();
		$company->where('view_key', $key)->get();
		if (!$company->exists()) {
			die("Invalid key.");
		}
		$this->current_company = $company;
		$this->data['view_key'] = $key;
		
		$this->data['stylesheet'] = $this->input->get('stylesheet');

	}
	
	/**
	 * Public access can do anything except delete
	 */
	public function isAllowed($action, $object) {
		return ($action != 'delete');
	}
}