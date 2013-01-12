<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function unique($value, $params)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('unique',
			'The %s is already being used.');

		list($collection, $field) = explode(".", $params, 2);

		$q = $CI->mongo_db->where(array($field => $value))->count($collection);

		if ($q  == 1) {
			return false;
		} else {
			return true;
		}

	}

	function exists($value, $params)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('exists',
			'That %s was not found.');

		list($collection, $field) = explode(".", $params, 2);

		$q = $CI->mongo_db->where(array($field => $value))->count($collection);

		if ($q  == 1) {
			return true;
		} else {
			return false;
		}

	}

}
