<?php

class Image_model extends CI_Model {
	
	function __construct()
	{ 
		parent::__construct();
	}

	public function create($username, $image_details) {

		$this->load->helper("id_gen_helper");
		$image_id = get_unique_id();

		$image_data = array(
			"image_id" => $image_id,
			"source" => base_url() . 'i/' . $image_id . '.' . $image_details['file_type'],
			"path" => $image_details['upload_path'] . $image_details['file_name'],
			"created" => time(),
			"views" => 0,
			"upvotes" => 0,
			"downvotes" => 0,
			"file_type" => $image_details['file_type'],
		);

		$this->mongo_db->insert('images', $image_data);
		return $image_data['source'];

	}

	public function get_source($image_id) {



	}

	public function get_image_data($image_id) {

		if(is_string($image_id)) {
			$image = $this->mongo_db->where(array("image_id" => $image_id))->limit(1)->get("images");
			if (sizeof($image) == 1) {
				return $image[0];
			} else {
				return FALSE;
			}
		} else {

			return FALSE;

		}

	}

	public function get_all_images_of_user($username) {



	}

	public function get_image_by_id($image_id) {

		if (count($image_id) == 6) {

			return $this->get_image_data($image_id);

		} else {

			return FALSE;

		}

	}

	public function id_exists($image_id) {

		return ( $this->mongo_db->where(array('image_id' => $image_id))->count('images') > 0 );

	}
	
}