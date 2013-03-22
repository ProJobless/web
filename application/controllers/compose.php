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

			$post_data = array();

			if ($tags = $this->input->post('post_tags')) {
				$tags = explode(' ', strtolower($tags));
				$post_data['tags'] = $tags;
			}
		
			if ($this->input->post('type') == "quote") {

				$post_data['author'] = $u['username'];
				$post_data['quote_author'] = $this->input->post('post_title');
				$post_data['body'] = $this->input->post('quote_body');
				$post_data['type'] = 'quote';
				$post_data['published'] = 'true';
				$post_data['title'] = '';

				$this->Post_model->create($post_data);
				redirect('blog');

			} else if ($this->input->post('type') == "link") {

				$this->load->helper('share_helper');
				$post_data['author'] = $u['username'];
				$post_data['title'] = $this->input->post('post_title');
				$post_data['body'] = $this->input->post('post_body');
				$post_data['type'] = 'share';
				$post_data['share_root'] = $this->input->post('link_base_url');
				$post_data['share_url'] = $this->input->post('link_url');
				$post_data['link_base_url'] = $this->input->post('link_base_url');
				$post_data['link_url'] = $this->input->post('link_url');
				$post_data['link_title'] = $this->input->post('link_title');
				$post_data['link_description'] = $this->input->post('link_description');
				$post_data['link_media_url'] = $this->input->post('link_media_url');
				$post_data['link_media_height'] = $this->input->post('link_media_height');
				$post_data['link_media_width'] = $this->input->post('link_media_width');
				$post_data['link_type'] = $this->input->post('link_type');
				$post_data['link_image'] = $this->input->post('link_image');
				$post_data['thumbnail_image'] = $this->input->post('link_image');
				$post_data['published'] = 'true';

				//This helper function injects the share item html into the body of the post
				$post_data['body'] = create_share_html($post_data) . $post_data['body'];

				$post_id = $this->Post_model->create($post_data);

				$url_data = array(
					'post_id' => $post_id,
					'username' => $u['username'],
					'base_url' => $this->input->post('link_base_url'),
					'url' => $this->input->post('link_url'),
					'image' => $this->input->post('link_image'),
					'media' => $this->input->post('link_media_url'),
				);

				$this->Url_model->add($url_data);
				redirect('blog');

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
						print_r($error);
					} else {
						$image_source = $this->Image_model->create($u['username'], $config);

						$post_data['author'] = $u['username'];
						$post_data['title'] = $this->input->post('post_title');
						$post_data['body'] = "<br /><div class='uploaded-post-image'><img class='post-image' src='" . $image_source . "' /></div><br />" . $this->input->post('post_body');
						$post_data['published'] = 'true';
						$post_data['thumbnail_image'] = $image_source;
						

						if (!$post_data['title'] == '') {
							$post_data['type'] = "post";
						} else {
							$post_data['type'] = "small-post";
						}
						
						$this->Post_model->create($post_data);
						redirect('blog');					
						
					}

				}

				

			} else {

				$post_data['author'] = $u['username'];
				$post_data['title'] = $this->input->post('post_title');
				$post_data['body'] = $this->input->post('post_body');
				$post_data['published'] = 'true';


				if (!$post_data['title'] == '') {
					$post_data['type'] = "post";
				} else {
					$post_data['type'] = "small-post";
				}
				
				$this->Post_model->create($post_data);
				redirect('blog');

			}

		} else {
			
			redirect('/');
			
		}

	}

}

?>