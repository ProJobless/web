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
			$config['file_name'] = $u['username'] . '~' . get_unique_image_id() . '.' . $type;
			$config['upload_path'] = $this->config->item('temp_image_path');
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = FALSE;
			$config['file_type'] = $type;
			$this->load->library('upload', $config);

			$file_path = $this->config->item('image_path') . $config['file_name'];

			if ( ! $this->upload->do_upload("userfile")) {
				$error = $this->upload->display_errors();
				// do stuff
				echo $error;
				show_error('doh! what in the world!', 500);
			} else {
				$data = $this->upload->data();
				big_thumbnail_crop($config['file_name'], $config['upload_path']);
				echo json_encode(array(
					"image_source" => base_url() . 'i/bigthumbnail~' . $config['file_name'],
					"image_file_name" => $config['file_name'],
					"thumbnail_file_name" => 'bigthumbnail~' . $config['file_name'],
					"file_type" => $type,
				));
				
			}

		} else {

			echo "nope";

		}

	}

	public function multi_upload_submit() {

		if ($u = Current_user::user()) {

			$names = $this->input->post('names');
			$descriptions = $this->input->post('descriptions');
			$tags = $this->input->post('tags');
			$file_names = $this->input->post('file_names');
			$thumbnail_names = $this->input->post('thumbnail_names');
			$file_types = $this->input->post('file_types');

			for($i = 0; $i < count($names); $i++) {

				$image_data = array(
					"title" => $names[$i],
					"description" => $descriptions[$i],
					"file_name" => $file_names[$i],
					"file_type" => $file_types[$i],
					"tags" => $tags[$i],
					"multi_upload" => TRUE,
				);

				copy($this->config->item('temp_image_path') . $thumbnail_names[$i], $this->config->item('image_path') . $thumbnail_names[$i]);
				copy($this->config->item('temp_image_path') . $file_names[$i], $this->config->item('image_path') . $file_names[$i]);

				$this->Image_model->create($u['username'], $image_data);

			}

			redirect("/images");

		} else {

			redirect('/');

		}

	}
		
}