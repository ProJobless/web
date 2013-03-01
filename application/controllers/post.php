<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
			redirect('/');
			
	}
	
	public function display_by_sid($sid) {

		if($u = Current_User::user()) {
	
			$post     = $this->Post_model->get_by_sid($sid);
			$comments = $this->Post_model->get_comments($sid);
			$votes    = $this->Vote_model->get_by_username($u['username']);
			$shares   = $this->Post_model->get_shares($sid);

			//Construct the comments tree using the Comment_Node class.
			if (sizeof($comments) > 0) {
				$comments = new Comment_Node($post, $comments, $votes, $u['username']);
			} else {
				$comments = false;
			}
			
			//Determine the post's vote status, first set it as enabled.

			$post_vote_status = "enabled";

			if($post['author'] == $u['username']) {

				$post_vote_status = "disabled";

			} else {

				foreach($votes as $vote) {
		
					if ($vote['sid'] == $sid) {
						if($vote['type'] == "upvote") {
							$post_vote_status = "upvote-disabled";
						} else {
							$post_vote_status = "downvote-disabled";
						}
						break;		
					}

				}
				
			} 
			
			if ($post) {

				$data = array('main_content' => 'post',
							          'post' => $post,
								  'comments' => $comments,
								    'shares' => $shares,
								       'tab' => $this->input->get('tab'),
						  'post_vote_status' => $post_vote_status,
						);
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