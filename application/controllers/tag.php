<?php

class Tag extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
			redirect('/');
			
	}
	
	public function display($name) {

		if ($u = Current_user::user()) {

			$s = $this->Post_model->get_list(array('tags' => array($name), 'published' => 'true'));

			if (sizeof($t) == 1) {
				$data = array('main_content' => 'post',
							          'post' => $t[0],
							 )
				$this->load->view('includes/template', $data);	
			} else {
			
				redirect('/');
			
			}

		} else {

			redirect('/');

		}
	}

}