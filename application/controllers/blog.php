<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {

			$posts = $this->Post_model->get(array('author' => $u['username'], 'published' => 'true'));
		
			$data  = array('main_content' => 'blog',
						          'posts' => $posts,
								   'type' => 'self',
						    'author_info' => $u);

			$this->load->view('includes/template', $data);
			
		} else {
			
			redirect('/');
			
		}
		
	
	}
	
	public function display_by_username($username) {

		if($u = Current_User::user()) {
	
			$posts = $this->Post_model->get(array('author' => $username, 'published' => 'true'));
			$blog_author = $this->User_model->get_by_username($username);

			$post_ids = array();

			foreach ($posts as $post) {
				$post_ids[] = $post['sid'];
			}

			$votes = $this->Vote_model->get_by_username($u['username'], $post_ids);
			$saves = $this->Save_model->get_saves($u['username'], $post_ids);
			$posts = $this->Post_model->attach_votes_saves($u['username'], $posts, $votes, $saves);
		
			if ($u) {
				$data = array('main_content' => 'blog',
							         'posts' => $posts,
							          'type' => 'other',
							   'author_info' => $blog_author);

				$this->load->view('includes/template', $data);	
			} else {
				redirect('/');
			}
			

		} else {
			
			redirect('/');
			
		}
	
	
	}

}

?>