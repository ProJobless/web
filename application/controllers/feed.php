<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {
	
		if ($u = Current_user::user()) {
			
			$s = $this->Post_model->get(array('author' => $u['username']));
			$data = array('main_content' => 'spool',
						  'spool' => $s);
			$this->load->view('includes/template', $data);
			
		} else {
			
			redirect('/');

		}
	}


}