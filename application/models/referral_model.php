<?php

class Referral_model extends CI_Model {

	function __construct() { 
		parent::__construct();
	}

	public function create($data) {

		if(isset($data['referrer']) && isset($data['email'])) {

			$this->load->helper('encryption_helper');
			$data['code'] = get_validation_code();
			$data['created'] = time();
			$data['redeemed'] = 0;
			$this->mongo_db->insert('referrals', $data);
			return $data['code'];

		} else {

			return FALSE;

		}

	}
	
	public function get_all($username) {

		return $this->mongo_db->where(array('referrer' => $username))->get('referrals');

	}

	public function is_valid_code($code) {

		$result = $this->mongo_db->where(array('code' => $code, 'redeemed' => 0))->limit(1)->get('referrals');
		return (bool)(count($result) == 1);

	}

	public function redeem($code) {

		$this->mongo_db->where(array('code' => $code))->set(array('redeemed' => 1))->update('referrals');
		return $this->mongo_db->where(array('code' => $code))->limit(1)->get('referrals');

	}

}