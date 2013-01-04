<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate_email extends CI_Controller {

	public function index() {
		
		if($u = Current_User::user()){		

			$data = $this->User_model->get_all_data($u['username']);
			if (!$u['validated']) {

				$data['main_content'] = 'validate_email';
				$this->load->view('includes/template', $data);

			} else {

				$data['main_content'] = 'self_user_profile';
				$this->load->view('includes/template', $data);

			}
			
		} else {
			
			redirect('/');
			
		}
	
	}

	public function validate() {

		if($u = Current_User::user()){		

			$data = $this->User_model->get_all_data($u['username']);
			if (!$u['validated']) {

				$this->load->helper('email_helper');
				$this->load->helper('encryption_helper');
				$this->load->model('Email_validation_model');
				$code = $this->input->get('code');

				if($code) {

					$validated = $this->Email_validation_model->get_validation_code($u['email']);
					if ($code === $validated) {
						$this->Email_validation_model->validate($u['email']);
						$this->User_model->update(array('email' => $u['email']), array('validated' => TRUE));
						$this->load->view('includes/template', array('main_content' => 'validate_email_success'));
					} else {
						$this->load->view('includes/template', array('main_content' => 'validate_email_fail'));
					}

				} else {

					$email_data = array(
						'validation_code' => $this->Email_validation_model->add(array('email' => $u['email'])),
					);

					email_validation($email_data);

					$data['main_content'] = 'validate_email_sent';
					$this->load->view('includes/template', $data);

				}

				

			} else {

				redirect('/profile');

			}
			
		} else {
			
			redirect('/');
			
		}
	
	}

}