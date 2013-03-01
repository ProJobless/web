<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_page extends CI_Controller {

	public function index() {
		
		if($u = Current_User::user()) {
		
			$data = array(
				'main_content' => 'front_page',
				'posts' => $this->Post_model->get(array("published" => "true")),
			);
			$this->load->view('includes/template', $data);
			
		} else {
			
			$this->load->view('welcome_page');
			
		}
	}

}