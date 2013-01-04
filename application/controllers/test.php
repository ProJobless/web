<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		$this->load->view('includes/template', array('main_content' => 'validate_email_success'));

	}
}