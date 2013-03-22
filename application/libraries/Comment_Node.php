<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Node {
	
	public $comment;
	public $parentc;
	public $root;
	public $children = Array();
	public $size;
	public $vote;
	public $save;

	public function __construct($comment, $carray, $votes = array(), $saves = array(), $user) {
		
		$this->comment  = $comment;
		$this->parentc  = $comment['parent'];
		$this->root     = $comment['root'];
		$this->size     = sizeof($carray);

		if($user) {
			$this->vote = $this->find_vote($votes, $comment['sid']);
			$this->save = $this->find_save($saves, $comment['sid']);
		} else {
			$this->vote = false;
			$this->save = false;
		}
		
		foreach ($carray as $c) {
		
			if($c['parent'] == $this->comment['sid']) {
			
				$this->children[] = new Comment_Node($c, $carray, $votes, $saves, $user);
			
			}
		
		}
		
	}
	
	private function find_vote($votes, $sid) {

		foreach($votes as $vote) {

			if ($vote['sid'] == $sid) {
				return $vote;
			}
		
		}
		return false;

	}

	private function find_save($saves, $post_id) {

		foreach($saves as $save) {

			if ($save['post_id'] == $post_id) {
				return $save;
			}
		
		}
		return false;

	}

}