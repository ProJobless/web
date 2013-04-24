<?php

class Image_model extends CI_Model {
	
	function __construct()
	{ 
		parent::__construct();
	}

	public function create($username, $image_details) {

		$this->load->helper("id_gen_helper");
		$this->load->helper("image_helper");
		$image_id = get_unique_id();
		$upload_folder = $this->config->item('image_path');

		if (isset($image_details['title'])) {
			$title = $image_details['title'];
		} else {
			$title = "";
		}

		if (isset($image_details['description'])) {
			$description = $image_details['description'];
		} else {
			$description = "";
		}


		if (isset($image_details['tags'])) {
			$tags = explode(' ', strtolower($image_details['tags']));
			foreach ($tags as $tag) {
				if (strlen($tag) > 0) {
					$tag_data = array("name" => $tag, "sid" => $image_id);
					$this->Tag_model->add($tag_data);
				}
			}
		} else {
			$tags = array();
		}


		$image_data = array(
			"image_id" => $image_id,
			"file_type" => $image_details['file_type'],
			"url" => base_url() . 'i/' . $image_id . '.' . $image_details['file_type'],
			"filename" => $image_details['file_name'],
			"path" => $upload_folder . $image_details['file_name'],
			"thumbnail_url" => base_url() . 'i/thumbnail_' . $image_id . '.' . $image_details['file_type'],
			"thumbnail_filename" => 'thumbnail~' . $image_details['file_name'],
			"thumbnail_path" => $upload_folder . 'thumbnail~' . $image_details['file_name'],
			"big_thumbnail_url" => base_url() . 'i/bigthumbnail_' . $image_id . '.' . $image_details['file_type'],
			"big_thumbnail_filename" => 'bigthumbnail~' . $image_details['file_name'],
			"big_thumbnail_path" => $upload_folder . 'bigthumbnail~' . $image_details['file_name'],
			"title" => $title,
			"description" => $description,
			"tags" => $tags,
			"created" => time(),
			"views" => 0,
			"upvotes" => 0,
			"downvotes" => 0,
		);

		$dimensions = getimagesize ( $image_data['path'] );
		$image_data['height'] = $dimensions[1];
		$image_data['width'] = $dimensions[0];

		if (isset($image_details['multi_upload'])) {
			thumbnail_crop($image_details['file_name'], $upload_folder);

		} else {
			thumbnail_crop($image_details['file_name'], $upload_folder);
			big_thumbnail_crop($image_details['file_name'], $upload_folder);
		}

		$this->mongo_db->insert('images', $image_data);

		$user_image_data = array(
			"image_id" => $image_id,
			"username" => $username,
		);

		$this->mongo_db->insert('user_images', $user_image_data);
		return $image_data;

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

		$user_images = $this->mongo_db->select(array('image_id'))->where(array("username" => $username))->get("user_images");

		$ids = array();

		foreach($user_images as $image) {
			$ids[] = $image['image_id'];
		}

		return $this->mongo_db->where_in('image_id', $ids)->get("images");

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

	public function resize_image($path, $file_name, $upload_path) {

		

	}
	
}