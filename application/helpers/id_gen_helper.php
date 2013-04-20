<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_unique_id')) {

	function make_seed() {
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}

}

if ( ! function_exists('get_unique_id')) {
	function get_unique_id() {

		$index = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
		$id = '';
		srand(make_seed());
		
		for($i = 1; $i <= 6; $i++) {
			$id = $id . $index[rand(0,63)];
		}
		
		$CI =& get_instance();
		
		if($CI->Post_model->sid_exists($id) || $CI->Image_model->id_exists($id)) {
			return get_unique_id();
		} else {
			return $id;
		}
		
	}

}

if ( ! function_exists('get_unique_email_id')) {
	
	function get_unique_email_id() {
		
		$index = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
		$id = '';
		srand(make_seed());
		
		for($i = 1;$i <= 32; $i++) {
			$id = $id . $index[rand(0,63)];
		}
		
		return $id;
	}

}

if ( ! function_exists('get_unique_image_id')) {

	function get_unique_image_id() {

		$index = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ-';
		$id = '';
		srand(make_seed());
		
		for($i = 1;$i <= 32; $i++) {
			$id = $id . $index[rand(0,62)];
		}
		
		return $id;

	}


}