<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_test extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {

			$this->load->helper('email_helper');

			$data = array(
				'email' => 'johnspar1@gmail.com',
				'referral_name' => 'john',
				'signup_code' => 'aslkdalksjdlajsd',
			);

			email_referral($data);
			
		} else {
			
			redirect('/');
			
		}
		
	
	}

}

?>