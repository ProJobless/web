<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class forgot_password extends CI_Controller {

	function __construct()
	{ 
		parent::__construct();
		$this->load->helper(array('form','url'));
        $this->load->library('form_validation');
	}
	
	function index() {
	
		redirect('/');

	}

	function submit() {

		$email = $this->input->post('email');
		if ($email) {

			$this->load->model('Forgot_password_model');
			if ($this->User_model->email_exists($email)) {
				$this->load->helper("email_helper");
				$data = array(
					"email" => $email,
					"pw_reset_code" => $this->Forgot_password_model->add($email),
				);
				email_password_reset($data);
				echo 'success!';
			}
			
		}

	}

	function reset() {
		$code = $this->input->get('code');
		if ($code) {
			$this->load->model('Forgot_password_model');
			$valid = $this->Forgot_password_model->validate($code);
			if($valid) {
				$data = array(
					'code' => $code,
				);
				$data['main_content'] = 'forgot_password_change_pw';
				$this->load->view('includes/empty_template', $data);
			} else {
				$data['main_content'] = 'forgot_password_fail';
				$this->load->view('includes/empty_template', $data);
			}
		} else {
			redirect('/');
		}
	}

	function change_password() {

		$code = $this->input->post('code');

		if ($code) {

			if ($this->_submit_validate() === FALSE) {
				$data = array(
					'code' => $code,
					'main_content' => 'forgot_password_change_pw',
				);
				$this->load->view('includes/empty_template', $data);
			} else { 
				
				$this->load->model('Forgot_password_model');
				
				$password = $this->input->post('password');
				$passconf = $this->input->post('passconf');

				if ($password == $passconf) {
					if($data = $this->Forgot_password_model->get_by_validation_code($code)) {
						if($user_data = $this->User_model->get_by_email($data['email'])) {
							$change_pw_data = array(
								'new_password' => $password,
								'old_password_hash' => $user_data['password'],
								'username' => $user_data['username'],
							);
							$this->User_model->change_password_with_hash($change_pw_data);
							$this->Forgot_password_model->delete($data['email']);
							$data = array('main_content' => 'forgot_password_success');
							$this->load->view('includes/empty_template', $data);
						}
					} else {
						$data = array('main_content' => 'forgot_password_fail');
						$this->load->view('includes/empty_template', $data);
					}
				}		
				
			}

		} else {

			$data = array('main_content' => 'forgot_password_fail');
			$this->load->view('includes/empty_template', $data);

		}

	}

	private function _submit_validate() {
	
        $this->form_validation->set_rules('password', 'Password',
            'required|min_length[6]|max_length[20]');
 
        $this->form_validation->set_rules('passconf', 'Confirm Password',
            'required|matches[password]');
 
        return $this->form_validation->run();
 
    }


}