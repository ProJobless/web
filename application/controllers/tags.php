<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
		$args = array();
		if (!($args['page'] = $this->input->get('page'))) {
			$args['page'] = 1;
		}
		// if (!($args['filter'] = $this->input->get('filter'))) {
		// 	$args['filter'] = 'week';
		// }
		if (!($args['tab'] = $this->input->get('tab'))) {
			$args['tab'] = 'popular';
		}
		if (!($args['search'] = $this->input->get('search'))) {
			$args['search'] = '';
		}

		$this->load->helper('pagination_helper');
		$data = array();
		$data['args'] = $args;
		$data['tags'] = $this->Tag_model->get_list($args);
		$data['pagination_pages'] = get_pagination_buttons($args);
		$data['main_content'] = 'tags';
		$this->load->view('includes/template', $data);
	}
	
	public function display_by_name($tag) {
		if ($u = Current_User::user()) {
			$constraints = array("tags" => $tag,
								 "page" => 1,
								 "posts_per_page" => 25,
								 "sort_by" => "created");

			$posts = $this->Post_model->get_list($constraints);

			$post_ids = array();

			foreach ($posts as $post) {
				$post_ids[] = $post['sid'];
			}

			$votes = $this->Vote_model->get_by_username($u['username'], $post_ids);
			$saves = $this->Save_model->get_saves($u['username'], $post_ids);
			$posts = $this->Post_model->attach_votes_saves($u['username'], $posts, $votes, $saves);

			$data = array(
				       'posts' => $posts,
				       'tags'  => $tag,
				'main_content' => 'tag'
			);

			$this->load->view('includes/template', $data);	
		} else {
			redirect('/');
		}
		
	}

}