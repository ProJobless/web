<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index() {

        if($u = Current_User::user()) {

            redirect('front_page');
            
        } else {
            
            redirect('/');
            
        }
        
    }

}