<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		$this->load->helper("image_helper");

		$images = $this->mongo_db->get('images');

		foreach($images as $image) {
			multi_crop_image($image['path'], $image['image_id'] . '.' . $image['file_type']);
		}

		foreach($images as $image) {
			$this->mongo_db->where(array("image_id" => $image['image_id']))->set(array(
				'thumbnail_path' => $this->config->item('image_path') . 'thumbnail_' . $image['image_id'] . '.' . $image['file_type'],
				'big_thumbnail_path' => $this->config->item('image_path') . 'big_thumbnail_' . $image['image_id'] . '.' . $image['file_type'],
				"thumbnail_source" => base_url() . 'i/thumbnail_' . $image['image_id'] . '.' . $image['file_type'],
				"big_thumbnail_source" => base_url() . 'i/big_thumbnail_' . $image['image_id'] . '.' . $image['file_type'],
			))
			->update('images');
		}

		foreach($images as $image) {
			echo "<img src='" . $image['big_thumbnail_source'] . "' />";
		}

		
		
	}
}