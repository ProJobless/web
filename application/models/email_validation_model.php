<?php

class Email_validation_model extends CI_Model {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function add($data) {
		$this->load->helper('encryption_helper');
		$data['validation_code'] = get_email_validation_code();
		$data['created'] = time();
		$this->mongo_db->where(array('email' => $data['email']))->delete_all('email_validation');
		$this->mongo_db->insert('email_validation', $data);
		return $data['validation_code'];
	}

	function validate($email) {
		$this->mongo_db->where(array('email' => $email))->delete('email_validation');
	}

	function get_validation_code($email) {
		$return = $this->mongo_db->where(array('email' => $email))->get('email_validation');
		if (count($return) > 0) {
			return $return[0]['validation_code'];
		} else {
			return FALSE;
		}
	}
		
}