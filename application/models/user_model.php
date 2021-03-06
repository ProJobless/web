<?php

class User_model extends CI_Model {
		
	protected $USERS_PER_PAGE = 40;

	function __construct() { 

		parent::__construct();

	}
	
	public function signup($data) {
		
		$this->load->helper('encryption_helper');
		$this->load->helper('email_helper');
		
		$data['salt'] = get_salt();
		$data['created'] = time();		
		$data['password'] = encrypt_pw($data['password'], $data['salt']);		
		$data['avatar'] = "assets/silhoeutte.png";
		$data['avatar_thumbnail'] = "assets/silhoeutte.png";
		$data['blurb'] = "";
		$data['full_name'] = "";
		$data['website'] = "";
		$data['location'] = "";
		$data['last_seen'] = time();
		$data['last_login'] = time();
		$data['profile_views'] = 0;
		$data['validated'] = FALSE;
		$data['influence'] = 0;
		$data['achievement_score'] = 0;
		$data['user_number'] = $this->user_count();

		$this->mongo_db->insert('users', $data);
	}
	
	public function authenticate($username, $password) {
		
		$this->load->helper('encryption_helper');
		
		if ($u = $this->User_model->get_by_username($username)) {
			if ($u['password'] == encrypt_pw($password, $u['salt'])) {
				return $u;
			}
			return FALSE;
		}
		return FALSE;
	}
	
	public function change_password($data) {
	
		if (isset($data['username']) && isset($data['old_password']) && isset($data['new_password'])) {

			if ($u = $this->User_model->get_by_username($data['username'])) {

				$this->load->helper('encryption_helper');
				if ($u['password'] == encrypt_pw($data['old_password'], $u['salt'])) {
					$new_pw = encrypt_pw($data['new_password'], $u['salt']);
					$this->update(array('username' => $data['username']), array('password' => $new_pw));
					return TRUE;
				}
				return FALSE;
			}
			return FALSE;
		}
		return FALSE;
	
	}

	public function change_password_with_hash($data) {

		if (isset($data['username']) && isset($data['old_password_hash']) && isset($data['new_password'])) {

			if ($u = $this->User_model->get_by_username($data['username'])) {

				if ($u['password'] === $data['old_password_hash']) {
					$this->load->helper('encryption_helper');
					$new_pw = encrypt_pw($data['new_password'], $u['salt']);
					$this->update(array('username' => $data['username']), array('password' => $new_pw));
					return TRUE;
				}
				return FALSE;
			}
			return FALSE;
		}
		return FALSE;

	}
	
	public function get_by_username($username) {
		
		$u = $this->mongo_db->where(array('username'=>$username))->limit(1)->get('users');
		if(sizeof($u) == 1) {
			return $u[0];
		} else {
			return FALSE;
		}
	}
	
	public function get_by_id($user_id) {
		
		$u = $this->mongo_db->where(array('_id'=>$user_id))->limit(1)->get('users');
		if(sizeof($u) == 1) {
			return $u[0];
		} else {
			return FALSE;
		}
	
	}

	public function get_by_email($email) {
		
		$u = $this->mongo_db->where(array('email'=>$email))->limit(1)->get('users');
		if(sizeof($u) == 1) {
			return $u[0];
		} else {
			return FALSE;
		}
	}
	
	public function update($where, $data) {
		
		$this->mongo_db->where($where)->set($data)->update_all('users');
		
	}
	
	public function change_avatar($username, $picture) {
	
		if ($this->User_model->username_exists($username)) {
			$this->mongo_db->where(array('username' => $username))->set(array('avatar' => $picture))->update_all('users');
			return TRUE;	
		}
		return FALSE;
	
	}

	public function change_avatar_thumbnail($username, $thumbnail) {
		if ($this->User_model->username_exists($username)) {
			$this->mongo_db->where(array('username' => $username))->set(array('avatar_thumbnail' => $thumbnail))->update_all('users');
			$this->Post_model->change_avatar_thumbnail($username, $thumbnail);
			return TRUE;	
		}
		return FALSE;
	}
	
	public function username_exists($username) {
	
		return (bool) ( $this->mongo_db->where(array('username' => $username))->count('users') > 0 );
	
	}
	
	public function email_exists($email) {
	
		return (bool) ( $this->mongo_db->where(array('email' => $email))->count('users') > 0 );
	
	}
	
	public function delete($username) {
		
		$this->mongo_db->where(array('username' => $username))->delete_all('users');
		
	}
	
	public function get_avatar($username) {
	
		$u = $this->mongo_db->where(array('username' => $username))->limit(1)->get('users');
		
		return $u[0]['avatar'];
	
	}

	public function get_avatar_thumbnail($username) {
	
		$u = $this->mongo_db->where(array('username' => $username))->limit(1)->get('users');
		
		return $u[0]['avatar_thumbnail'];
	
	}

	public function validate_email($username) {
		$this->mongo_db->where(array('username' => $username))->set(array('validated' => TRUE))->update('users');
	}

	public function get_list($constraints) {
		$pages = $this->User_model->get_pages_amount($constraints);
		if ($constraints['page'] > $pages) {
			$constraints['page'] = $pages;
		} else if ($constraints['page'] < 1) {
			$constraints['page'] = 1;
		}
		$this->mongo_db->offset(($constraints['page'] - 1) * $this->USERS_PER_PAGE)
		            ->limit($this->USERS_PER_PAGE)
		            ->order_by(array($constraints['tab'] => 'desc'));
		
		if (isset($constraints['search'])) {
			if ($constraints['search'] != "") {
				$this->mongo_db->or_like(array('username' => $constraints['search'],
					                           'full_name' => $constraints['search']));
			}
		}
		return $this->mongo_db->get('users');
	}

	public function get_total($constraints) {

		if (isset($constraints['search'])) {
			if ($constraints['search'] != "") {
				return $this->mongo_db->or_like(array('username' => $constraints['search'],
					                           'full_name' => $constraints['search']))
				                      ->count('users');
			}
		}
		return $this->mongo_db->count('users');
		
	}

	public function get_pages_amount($constraints) {
		return ceil($this->User_model->get_total($constraints) / $this->USERS_PER_PAGE);
	}

	public function get_all_data($username) {
		if ($this->User_model->username_exists($username)) {
			$this->load->helper('time_format_helper');
			$data = array();
			$args = array();
			$args['username'] = $username;
			$args['limit'] = 10;
			$args['type'] = 'short';
			$data['user_info'] = $this->User_model->get_by_username($username);
			$data['user_info']['member_for_string'] = member_time_formatter($data['user_info']['created']);
			$data['user_info']['last_seen_string'] = long_time_formatter($data['user_info']['last_seen']);
			$args['type'] = array('small-post','post');
			$data['post_history'] = $this->Post_model->get_post_history($args);
			$data['posts_count'] = $this->Post_model->get_post_count($args);
			$data['influence_history'] = $this->Post_model->get_influence_history($args);
			$args['type'] = array('comment');
			$data['comment_history'] = $this->Post_model->get_post_history($args);
			$data['comments_count'] = $this->Post_model->get_post_count($args);
			$args['type'] = array('share');
			$data['share_history'] = $this->Post_model->get_post_history($args);
			$data['shares_count'] = $this->Post_model->get_post_count($args);
			$args['type'] = array('picture');
			$data['picture_history'] = $this->Post_model->get_post_history($args);
			$data['pictures_count'] = $this->Post_model->get_post_count($args);
			$data['vote_history'] = $this->Vote_model->get_by_username($args);
			$data['votes_count'] = $this->Vote_model->get_count_by_username($username);
			$data['tags_count'] = $this->Tag_model->count_by_name($username);
			$data['following'] = $this->Follow_model->following_this_user($username);
			$data['following_count'] = $this->Follow_model->following_count($username);
			$data['followers_count'] = $this->Follow_model->followers_count($username);
			
			return $data;
		} else {
			return false;
		}
	}

	public function incr_value($username, $value, $amount = 1) {
		if ($u = $this->User_model->get_by_username($username)) {
			$this->mongo_db->where(array('username' => $username))->inc(array($value => $amount))->update('users');
		}
	}

	public function dec_value($username, $value, $amount = 1) {;
		if ($u = $this->User_model->get_by_username($username)) {
			$this->mongo_db->where(array('username' => $username))->dec(array($value => $amount))->update('users');
		}
	}

	public function update_vote_count($username) {
		$votes_count = $this->Vote_model->get_count_by_username($username);
		$this->User_model->update(array("username" => $username), array("votes_count" =>  $votes_count));

	}

	public function touch($username) {
		$this->User_model->update(array("username" => $username), array("last_seen" =>  time()));
	}

	public function user_count() {
		return $this->mongo_db->count('users');
	}

	/************************** Influence setters **********************/

	public function upvote($username) {
		$this->User_model->incr_value($username, "influence", VOTE_INFLUENCE_GAIN);
	}

	public function downvote($username) {
		$this->User_model->dec_value($username, "influence", VOTE_INFLUENCE_GAIN);
	}

	public function switch_to_upvote($username) {
		$this->User_model->incr_value($username, "influence", 2*VOTE_INFLUENCE_GAIN);
	}

	public function switch_to_downvote($username) {
		$this->User_model->dec_value($username, "influence", 2*VOTE_INFLUENCE_GAIN);
	}

}

?>