<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
	}
	
	function index() {

		$this->load->helper('html_parsing_helper');


		echo parse_title("<p>                                  <a href='asdfasdfasdf'><em>Hsdfgsdfgsdfgggsdfgsdfgsdfgsdfsdfgsdfsdfgsdfgsdfgsdfsdfgsgfdei</em></a>                                    </p> <p><a href='blah blah'>How are you?</a></p>");

	}
}