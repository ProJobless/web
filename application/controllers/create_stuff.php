<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_Stuff extends CI_Controller {

	public function index() {
        
    }

    public function users() {

        if($u = Current_User::user()) {

        	for($i = 1; $i <= 1000; $i++) {
        		$data = array('username' => "test" . $i,
    			              'email' => "test" . $i . "@test.com",
    			              'password' => "password");						
    			$this->User_model->signup($data);
        	}

        } else {

            redirect("/");

        }
    	
    }

}