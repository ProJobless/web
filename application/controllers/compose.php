<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compose extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {
		
			$data = array(
				'main_content' => 'compose',
				'type' => $this->input->get('type'),
			);
			$this->load->view('includes/template', $data);

		} else {
			
			redirect('/');
			
		}
		
	}

	public function submit() {

		if($u = Current_User::user()) {
		
			$data = array(
				'author' => $u['username'],
				'title' => $this->input->post('post_title'),
				'body' => $this->input->post('post_body'),
				'published' => 'true',
			);

			if ($tags = $this->input->post('post_tags')) {
				$tags = explode(' ', $tags);
				$data['tags'] = $tags;
			}

			if (!$data['title'] == '') {
				$data['type'] = "post";
			} else {
				$data['type'] = "small-post";
			}
			
			$this->Post_model->create($data);
			redirect('blog');

		} else {
			
			redirect('/');
			
		}

	}

}

?>