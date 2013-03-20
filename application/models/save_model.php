<?php

class Save_model extends CI_Model {
	
	function __construct()
	{ 
		parent::__construct();
	}

	public function save($username, $post_id) {
		
		$data['created'] = time();		
		$data['username'] = $username;
		$data['post_id'] = $post_id;

		$this->mongo_db->insert('saves', $data);

	}

	public function unsave($username, $post_id) {
		if ($this->save_exists($username, $post_id)) {
			$this->mongo_db->where(array("username" => $username, "post_id" => $post_id))->delete("saves");
		} else {
			return FALSE;
		}
	}

	public function save_exists($username, $post_id) {
		$saves = $this->mongo_db->where(array("username" => $username, "post_id" => $post_id))->limit(1)->get('saves');
		if (count($saves) == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_saves($username, $post_ids = array()) {
		if(count($post_ids) == 0) {
			return $this->mongo_db->where(array("username" => $username))->get('saves');
		} else {
			return $this->mongo_db->where(array("username" => $username))->where_in('post_id', $post_ids)->get('saves');
		}
		
	}

	public function saves_count($username) {
		return $save = $this->mongo_db->where(array("username" => $username))->count('saves');
	}
	
}