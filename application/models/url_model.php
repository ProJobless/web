<?php

class Url_model extends CI_Model {

	function __construct() { 
		parent::__construct();
		
	}
	
	function add($data) {
		
		$this->mongo_db->insert('urls', $data);
		
	}
	
	function get_by_url($url) {
		
		$u = $this->mongo_db->where(array('url' => $url))->get('urls');
		if(sizeof($u) > 0) {
			return $u;
		} else {
			return FALSE;
		}
		
	}

}