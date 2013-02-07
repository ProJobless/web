<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_account', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
		
	
	}
	
	public function password() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_password', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
	
	}
	
	public function profile() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_profile', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
	
	}
	
	public function submit() {
	
		$this->output->enable_profiler(TRUE);
	
		if($u = Current_User::user()) {		
		
			if ( $this->input->post('hidden') == "account" )  {
				
				$data = array();
				$data['username'] = $this->input->post('username');
				$data['email'] = $this->input->post('email');
				$this->User_model->update(array('username' => $u['username']), $data);
				redirect('settings');
				
			}
			
			if ( $this->input->post('hidden') == "password" ) {
			
				$this->User_model->change_password(array("username" => $u['username'], "old_password" => $this->input->post('current_password'), "new_password" => $this->input->post('new_password')));
				redirect('settings');
			
			}
			
			if ( $this->input->post('hidden') == "profile" ) {

				$data = array();
				$data['full_name'] = $this->input->post('full_name');
				$data['website'] = $this->input->post('website');
				$data['location'] = $this->input->post('location');
				$data['blurb'] = $this->input->post('blurb');
				$this->User_model->update(array('username' => $u['username']), $data);
				redirect('settings');
				
			}
			
			
			
		} else {
			
			redirect('/');
			
		}
		
	
	}

}

?>