<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

    public function __construct() {	
        parent::__construct();
		
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
    }
	
    public function index() {
        $data['main_content'] = 'signup_form';
		$this->load->view('includes/empty_template', $data);
    }
	
	public function submit() {
		
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return false;
		} else {

			$code = $this->input->post('signup_code');

			if($code) {

				$this->load->model('Referral_model');

				$referral = $this->Referral_model->redeem($code);

				if(count($referral) == 1) {
					$data = array(
						'username' => $this->input->post('username'),
					    'email' => $this->input->post('email'),
					    'password' => $this->input->post('password'),
					    'referrer' => $referral[0]['referrer'],
					);

					$this->User_model->signup($data);

				}

				redirect('/');
					
			}

			//For when site is out of invite only mode:

			//else {

			// 	$data = array(
			// 		'username' => $this->input->post('username'),
			// 	    'email' => $this->input->post('email'),
			// 	    'password' => $this->input->post('password'),
			// 	);

			// 	$this->User_model->signup($data);

			// 	redirect('/');

			// }

			
		}

	}
	
	private function _submit_validate() {
	
		$this->form_validation->set_rules('email', 'E-mail',
            'required|valid_email|unique[users.email]');
		
		$this->form_validation->set_rules('username', 'Username',
            'required|alpha_numeric|min_length[3]|max_length[32]|unique[users.username]');
		
 
        $this->form_validation->set_rules('password', 'Password',
            'required|min_length[6]|max_length[20]');
 
        $this->form_validation->set_rules('passconf', 'Confirm Password',
            'required|matches[password]');

        $this->form_validation->set_rules('signup_code', 'invite code',
            'required|exists[referrals.code]');
 
 
        return $this->form_validation->run();
 
    }
}