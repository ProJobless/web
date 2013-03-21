<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refer extends CI_Controller {
	
	public function index() {
	
		if($u = Current_User::user()) {

			$this->load->model('Referral_model');
		
			if (isset($u['admin']) && $u['admin']) {

				$data = array(
					'main_content' => 'refer_admin',
					'referrer' => $u['username'],
					'referrals' => $this->Referral_model->get_all($u['username']),
				);
				$this->load->view('includes/template', $data);

			} else {

				$data = array(
					'main_content' => 'refer',
					'referrer' => $u['username'],
					'referrals' => $this->Referral_model->get_all($u['username']),
				);
				$this->load->view('includes/template', $data);

			}
			
		} else {
			
			redirect('/');
			
		}
	
	}

	public function create() {

		if($u = Current_User::user()) {

			$referrer = $this->input->post('referrer');
			$email = $this->input->post('email');

			if ($email && $referrer) {

				$this->load->model('Referral_model');

				if (isset($u['admin']) && $u['admin']) {

					$this->load->helper("email_helper");

					$data = array(
						'referrer' => $referrer,
						'email' => $email,
					);

					$code = $this->Referral_model->create($data);

					if ($u['full_name'] == "") {
						$referral_name = $u['username'];
					} else {
						$referral_name = $u['full_name'];
					}

					$email = array(
						'signup_code' => $code,
						'referral_name' => $referral_name,
						'email' => $data['email'],
					);

					email_referral($email);

				} else {

					$referrals = $this->Referral_model->get_all($u['username']);
					if (!isset($u['admin']) && count($referrals) > MAX_REFERRAL_AMOUNT) {

						

					} else {

						$this->load->helper("email_helper");

						$data = array(
							'referrer' => $referrer,
							'email' => $email,
						);

						$code = $this->Referral_model->create($data);

						if ($u['full_name'] == "") {
							$referral_name = $u['username'];
						} else {
							$referral_name = $u['full_name'];
						}

						$email = array(
							'signup_code' => $code,
							'referral_name' => $referral_name,
							'email' => $data['email'],
						);

						email_referral($email);

					}

				}

			} else {

				

			}

		} else {

			

		}

	}
		
}