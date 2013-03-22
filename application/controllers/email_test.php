<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_test extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {

			
			
		} else {
			
			redirect('/');
			
		}
		
	
	}

}

?>