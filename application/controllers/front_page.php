<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_page extends CI_Controller {

	public function index() {
		
		if($u = Current_User::user()) {

			$posts = $this->Post_model->get(array("published" => "true"));

			$post_ids = array();

			foreach ($posts as $post) {
				$post_ids[] = $post['sid'];
			}

			$votes    = $this->Vote_model->get_by_username($u['username'], $post_ids);
			$saves    = $this->Save_model->get_saves($u['username'], $post_ids);
		
			$posts = $this->Post_model->attach_votes_saves($u['username'], $posts, $votes, $saves);
			
			$data = array('main_content' => 'front_page',
						         'posts' => $posts);
		
			$this->load->view('includes/template', $data);
			
		} else {
			
			$this->load->view('welcome_page');
			
		}
	}

}