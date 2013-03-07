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
		
			if ($this->input->post('type') == "quote") {

			} else if ($this->input->post('type') == "link") {

			} else if ($this->input->post('type') == 'image') {

				if (isset($_FILES['userfile']) && isset($_FILES['userfile']['name'])) {

					$type = substr($_FILES['userfile']['name'], -3, 3);
					$this->load->helper('id_gen_helper');
					$config = array();
					$config['file_name'] = $u['username'] . '_' . get_unique_image_id() . '.' . $type;
					$config['upload_path'] = $this->config->item('image_path');
					$config['allowed_types'] = 'gif|jpg|png';
					$config['overwrite'] = TRUE;
					$config['file_type'] = $type;

					$file_path = $this->config->item('image_path') . $config['file_name'];

					$this->load->library('upload', $config);

					if ( !$this->upload->do_upload()) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$image_source = $this->Image_model->create($u['username'], $config);

						$data = array(
							'author' => $u['username'],
							'title' => $this->input->post('post_title'),
							'body' => "<p><img class='post-image' src='" . $image_source . "' /></p>" . $this->input->post('post_body'),
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
						
					}

				}

				

			} else {

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

			}

		} else {
			
			redirect('/');
			
		}

	}

}

?>