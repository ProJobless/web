<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	public function index() {
	
		if ($u = Current_user::user()) {
			
			$images = $this->Image_model->get_all_images_of_user($u['username']);
			$data = array(
				"images" => $images,
				"main_content" => "images",
				"user" => $u,
				"javascript_includes" => array($this->load->view('upload_images_js', '', TRUE)),
			);

			$this->load->view('includes/template', $data);
			
		} else {
			
			redirect('/');

		}

	}

	public function upload() {

		if ($u = Current_user::user()) {
			
			$data = array(
				"main_content" => "upload_images",
				"user" => $u,
				"javascript_includes" => array($this->load->view('upload_images_js', '', TRUE)),
			);

			$this->load->view('includes/template', $data);
			
		} else {
			
			redirect('/');

		}

	}

	public function submit() {

		if ($u = Current_user::user()) {

			$type = substr($_FILES['userfile']['name'], -3, 3);
			$this->load->helper('id_gen_helper');
			$this->load->helper('image_helper');
			$config = array();
			$config['file_name'] = $u['username'] . '_' . get_unique_image_id() . '.' . $type;
			$config['upload_path'] = $this->config->item('temp_image_path');
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = FALSE;
			$config['file_type'] = $type;
			$this->load->library('upload', $config);

			$file_path = $this->config->item('image_path') . $config['file_name'];

			if ( ! $this->upload->do_upload("userfile")) {
				$error = $this->upload->display_errors();
				// do stuff
				show_error('doh! what in the world!', 500);
			} else {
				$data = $this->upload->data();
				echo preview_crop_image($config['file_name'], $config['upload_path']);
				
			}

		} else {
			echo "nope";
		}

	}
		
}