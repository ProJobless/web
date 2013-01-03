<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		$this->load->helper('secret');

		$ses = new Ses(AWS_SES_KEY, AWS_SES_SECRET);
		print_r($ses->listVerifiedEmailAddresses());

	}
}