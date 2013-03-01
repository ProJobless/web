<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		$sid = "nQiqQl";

		$post     = $this->Post_model->get_by_sid($sid);
		$comments = $this->Post_model->get_comments($sid);
		$votes    = $this->Vote_model->get_by_username("jimmy");
		$shares   = $this->Post_model->get_shares($sid);

		//Construct the comments tree using the Comment_Node class.
		if (sizeof($comments) > 0) {
			$comments = new Comment_Node($post, $comments, $votes, "jimmy");
		} else {
			$comments = false;
		}

		print_r($comments);

	}
}