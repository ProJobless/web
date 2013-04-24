<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		
		
	}

	function give_images_dimensions() {

		$images = $this->mongo_db->get("images");

		foreach($images as $image) {
			$dimensions = getimagesize ( $image['path'] );
			var_dump($dimensions);
			$this->mongo_db->where(array("image_id" => $image['image_id']))->set(array("height" => $dimensions[1], "width" => $dimensions[0]))->update("images");
		}

	}
}