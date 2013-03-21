<?php

class Post_model extends CI_Model {
	
	public $spool;
	
	function __construct() { 
		parent::__construct();
	}
	
	public function create($data) {
		$url_data = array();
		$this->load->helper('id_gen_helper');
		$data['sid'] = get_unique_id();

		switch($data['type']) {
			case "post" :
			case "small-post":
				$data['url'] = base_url() . 'p/' . $data['sid'];
				$data['parent'] = $data['sid'];
				$data['root'] = $data['sid'];
				$data['share_url'] = $data['url'];
				break;
			case "comment" :
				$data['url'] = base_url() . 'c/' . $data['sid'];
				$data['share_url'] = $data['url'];
				break;
			case "share" :
				$data['url'] = base_url() . 'p/' . $data['sid'];
				$data['parent'] = $data['sid'];
				$data['root'] = $data['sid'];
				break;
			case "image" :
				$data['url'] = base_url() . 'i/' . $data['sid'];
				$data['parent'] = $data['sid'];
				$data['root'] = $data['sid'];
				$data['share_url'] = $data['url'];
				break;
			case "quote":
				$data['url'] = base_url() . 'p/' . $data['sid'];
				$data['parent'] = $data['sid'];
				$data['root'] = $data['sid'];
				$data['share_url'] = $data['url'];
			default:
				$data['url'] = base_url() . $data['sid'];
				$data['parent'] = $data['sid'];
				$data['root'] = $data['sid'];
				$data['share_url'] = $data['url'];
		}

		$this->load->helper('html_parsing_helper');

		if ( ctype_space($data['title']) || $data['title'] == '') {
			$data['title'] = parse_title($data['body']);	
		}
		
		$data['avatar_thumbnail'] = $this->User_model->get_avatar_thumbnail($data['author']);
		$data['created'] = time();
		$data['saves_count'] = 0;
		$data['shares_count'] = 0;
		$data['upvotes_count'] = 0;
		$data['downvotes_count'] = 0;
		$data['influence_gain'] = 0;
		$data['vote_diff'] = 0;
		$data['comments_count'] = 0;
		$data['views_count'] = 0;
		$data['last_comment'] = NULL;
		$data['views'] = array();
		$data['options'] = array();

		if (isset($data['tags'])) {
			foreach ($data['tags'] as $tag) {
				if (strlen($tag) > 0) {
					$tag_data = array("name" => $tag, "sid" => $data['sid']);
					$this->Tag_model->add($tag_data);
				}
			}
		} else {
			$data['tags'] = array();
		}
		$this->mongo_db->insert('posts', $data);
		return $data['sid'];
	}
	
	public function get_by_sid($sid) {
		
		if(is_string($sid)) {
			$s = $this->mongo_db->where(array("sid" => $sid))->limit(1)->get("posts");
			if (sizeof($s) == 1) {
				return $s[0];
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
		
	}
	
	public function get($data) {
		
		return $this->mongo_db->where($data)->order_by(array('created' => -1))->get('posts');
		
		
	}
	
	public function get_comments($sid) {
		
		return $this->mongo_db->where(array("root" => $sid, "type" => "comment"))->get("posts");
		
	}
	
	public function get_shares($sid) {
	
		return $this->mongo_db->where(array("root" => $sid, "type" => "share"))->get("posts");
	
	}

	public function generate_comment_html($data) {

		$node = new stdClass;
		$node->children = array();
		$node->children[] = new stdClass;
		$node->children[0]->comment = $data;
		$node->children[0]->children = $data['children'];

		$this->load->view("comment", array("node" => $node, "odd" => $data['odd']));

	}
	
	public function sid_exists($sid) {
		
		return ( $this->mongo_db->where(array('sid' => $sid))->count('posts') > 0 );
		
	}
	
	public function publish($sid) {
		
		$this->mongo_db->where(array('sid' => $sid))->set(array('published' => true))->update('posts');
		
	}
	
	public function hide($sid) {
		
		$this->mongo_db->where(array('sid' => $sid))->set(array('published' => false))->update('posts');
		
	}

	public function add_tag($data) {

		if(isset($data['sid']) && isset($data['name'])) {
			$tags = $this->Post_model->get_tags($data['sid']);
			$does_not_exist = TRUE;
			if (is_array($tags) && count($tags) > 0) {
				foreach($tags as $tag) {
					if ($tag == $data['name']) $does_not_exist = FALSE;
				}
			}
			if ($does_not_exist) {
				$this->mongo_db->where(array('sid' => $data['sid']))->push('tags', $data['name'])->update('posts');
				return TRUE;
			}
		}
		return FALSE;

	}
	
	public function remove_tag($data) {

		if(isset($data['sid']) && isset($data['name'])) {
			$this->mongo_db->where(array('sid' => $data['sid']))->pull('tags', $data['name'])->update('posts');
			return TRUE;	
		}
		return FALSE;
		
	}
	
	public function get_tags($sid) {
		
		$s = $this->mongo_db->where(array('sid' => $sid))->limit(1)->get('posts');
		if (sizeof($s) == 1) {
			return $s[0]['tags'];
		} else {
			return FALSE;
		}
		
	}

	public function get_list($constraints) {
		$pages = $this->Post_model->get_pages_amount($constraints);

		if (isset($constraints['page']) && isset($constrains['posts_per_page'])) {

			if ($constraints['page'] > $pages) {
				$constraints['page'] = $pages;
			} else if ($constraints['page'] < 1) {
				$constraints['page'] = 1;
			}
			$this->mongo_db->where(array("tags" => $constraints['tags']))
	            ->offset(($constraints['page'] - 1) * $constraints['posts_per_page'])
	            ->limit($constraints['posts_per_page'])
	            ->order_by(array($constraints['sort_by'] => 'desc'));

		} else {

			$this->mongo_db->where(array("tags" => $constraints['tags']))
				->order_by(array('created' => 'desc'));

		} 

		if (isset($constraints['published'])) {
			$this->mongo_db->where(array('published' => $constraints['published']));
		}

		
		
		if (isset($constraints['search'])) {
			if ($constraints['search'] != "") {
				$this->mongo_db->or_like(array('title' => $constraints['search'],
					                           'body' => $constraints['search']));
			}
		}
		return $this->mongo_db->get('posts');
	}

	public function get_total($constraints) {

		if (isset($constraints['search'])) {
			if ($constraints['search'] != "") {
				return $this->mongo_db->where(array("tags" => $constraints['tags']))
									  ->or_like(array('title' => $constraints['search'],
					                           'body' => $constraints['search']))
				                      ->count('posts');
			}
		}
		return $this->mongo_db->where(array("tags" => $constraints['tags']))->count('posts');
		
	}

	public function get_pages_amount($constraints) {
		return ceil($this->Post_model->get_total($constraints) / $constraints['posts_per_page']);
	}

	public function update($sid, $data) {
		
		$this->mongo_db->where(array('sid' => $sid))->set($data)->update('posts');
		
	}
	
	public function increment($sid, $field) {
	
		$this->mongo_db->where(array('sid' => $sid))->inc($field)->update('posts');
	
	}
	
	public function decrement($sid, $field) {
	
		$this->mongo_db->where(array('sid' => $sid))->dec($field)->update('posts');
	
	}

	public function upvote($sid) {
		if ($s = $this->Post_model->get_by_sid($sid)) {
			$influence = $s['influence_gain'];
			$upvotes = $s['upvotes_count'];
			$downvotes = $s['downvotes_count'];
			$upvotes++;
			$influence = $influence + VOTE_INFLUENCE_GAIN;
			$vote_diff = $upvotes - $downvotes;
			$this->mongo_db->where(array('sid' => $sid))->set(array('influence_gain' => $influence, 'upvotes_count' => $upvotes, 'vote_diff' => $vote_diff))->update('posts');
		}
	}

	public function downvote($sid) {
		if ($s = $this->Post_model->get_by_sid($sid)) {
			$influence = $s['influence_gain'];
			$upvotes = $s['upvotes_count'];
			$downvotes = $s['downvotes_count'];
			$downvotes++;
			$influence = $influence - VOTE_INFLUENCE_GAIN;
			$vote_diff = $upvotes - $downvotes;
			$this->mongo_db->where(array('sid' => $sid))->set(array('influence_gain' => $influence, 'downvotes_count' => $downvotes, 'vote_diff' => $vote_diff))->update('posts');
		}
	}

	public function switch_to_upvote($sid) {
		if ($s = $this->Post_model->get_by_sid($sid)) {
			$influence = $s['influence_gain'];
			$upvotes = $s['upvotes_count'];
			$downvotes = $s['downvotes_count'];
			$downvotes--;
			$upvotes++;
			$influence = $influence + (2 * VOTE_INFLUENCE_GAIN);
			$vote_diff = $upvotes - $downvotes;
			$this->mongo_db->where(array('sid' => $sid))->set(array('influence_gain' => $influence, 'upvotes_count' => $upvotes, 'vote_diff' => $vote_diff))->update('posts');
		}
	}

	public function switch_to_downvote($sid) {
		if ($s = $this->Post_model->get_by_sid($sid)) {
			$influence = $s['influence_gain'];
			$upvotes = $s['upvotes_count'];
			$downvotes = $s['downvotes_count'];
			$downvotes++;
			$upvotes--;
			$influence = $influence - (2 * VOTE_INFLUENCE_GAIN);
			$vote_diff = $upvotes - $downvotes;
			$this->mongo_db->where(array('sid' => $sid))->set(array('influence_gain' => $influence, 'downvotes_count' => $downvotes, 'vote_diff' => $vote_diff))->update('posts');
		}
	}

	public function change_avatar_thumbnail($username, $new_pic) {
		$this->mongo_db->where(array('author' => $username))->set(array("avatar_thumbnail" => $new_pic))->update_all('posts');
	}

	/************************** History fetch function **********************/

	public function get_post_history($args) {
		if (isset($args['limit'])) {
			if(isset($args['offset'])) {
				return $this->mongo_db->where(array('author' => $args['username']))->where_in('type', $args['type'])->order_by(array('created' => 'desc'))->limit($args['limit'])->offset($args['offset'])->get('posts');
			} else {
				return $this->mongo_db->where(array('author' => $args['username']))->where_in('type', $args['type'])->order_by(array('created' => 'desc'))->limit($args['limit'])->get('posts');
			} 
		} else {
			return $this->mongo_db->where(array('author' => $args['username']))->where_in('type', $args['type'])->order_by(array('created' => 'desc'))->get('posts');
		}
		
	}

	public function get_influence_history($args) {
		if (isset($args['limit'])) {
			if(isset($args['offset']) && isset($args['limit'])) {
				return $this->mongo_db->where(array('author' => $args['username'], 'influence_gain' => array('$ne' => 0)))->order_by(array('created' => 'desc'))->limit($args['limit'])->offset($args['offset'])->get('posts');
			} else {
				return $this->mongo_db->where(array('author' => $args['username'], 'influence_gain' => array('$ne' => 0)))->order_by(array('created' => 'desc'))->limit($args['limit'])->get('posts');
			} 
		} else {
			return $this->mongo_db->where(array('author' => $args['username'], 'influence_gain' => array('$ne' => 0)))->order_by(array('created' => 'desc'))->get('posts');
		}
	}

	public function get_post_count($args) {
		return $this->mongo_db->where(array('author' => $args['username'], 'type' => $args['type']))->count('posts');
	}

	/*********************** Votes and Saves stuff ************************/

	public function attach_votes_saves($posts, $votes, $saves) {

		$vote_ids = array();
		$save_ids = array();
		$index = 0;

		foreach($votes as $vote) {
			$vote_ids[] = $vote['sid'];
		}

		foreach($saves as $save) {
			$save_ids[] = $save['post_id'];
		}

		foreach($posts as $post) {

			$vote_key = array_search($post['sid'], $vote_ids);

			if ($vote_key !== false) {

				$posts[$index]['vote_status'] = $votes[$vote_key]['type'];

			} else {

				$posts[$index]['vote_status'] = false;

			}

			$post_key = array_search($post['sid'], $save_ids);

			if ($post_key !== false) {

				$posts[$index]['save_status'] = true;

			} else {

				$posts[$index]['save_status'] = false;

			}

			$index++;

		}

		return $posts;

	}

}