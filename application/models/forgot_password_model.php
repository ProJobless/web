<?php

class Forgot_password_model extends CI_Model {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function add($email) {

		$this->load->helper('encryption_helper');
		$data = array(
			'validation_code' => get_validation_code(),
			'created' => time(),
			'email' => $email,
		);
		$this->mongo_db->where(array('email' => $data['email']))->delete_all('forgot_password');
		$this->mongo_db->insert('forgot_password', $data);
		return $data['validation_code'];

	}

	function get_validation_code($email) {

		$return = $this->mongo_db->where(array('email' => $email))->get('forgot_password');
		if (count($return) > 0) {
			return $return[0]['validation_code'];
		} else {
			return FALSE;
		}

	}

	function get_by_email($email) {
		$return = $this->mongo_db->where(array('email' => $email))->get('forgot_password');
		if (count($return) > 0) {
			return $return[0];
		} else {
			return FALSE;
		}
	}

	function get_by_validation_code($code) {
		$return = $this->mongo_db->where(array('validation_code' => $code))->get('forgot_password');
		if (count($return) > 0) {
			return $return[0];
		} else {
			return FALSE;
		}
	}

	function delete($email) {
		$this->mongo_db->where(array('email' => $email))->delete_all('forgot_password');
	}
		
	function validate($code) {
		$valid = $this->mongo_db->where(array('validation_code' => $code))->get('forgot_password');
		return (bool) (count($valid) > 0);
	}
}