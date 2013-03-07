<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	public function index() {
	
		if ($u = Current_user::user()) {
			
			redirect('/');
			
		} else {
			
			redirect('/');

		}

	}

	public function get_image_by_id($image_id) {

		if (strlen($image_id) == 10) {

			$this->load->helper('image_helper');
			$id = substr($image_id, 0, 6);
			$image_data = $this->Image_model->get_image_data($id);
			serve_image($image_data);

		} else if (strlen($image_id) == 6) {

			$image_data = $this->Image_model->get_image_data($id);
			$data = array(
				'main_content' => 'image',
				'image_data'   => $image_data,
			);
			$this->load->view('includes/template', $data);

		} else {

			echo $image_id;

		}

	}


}