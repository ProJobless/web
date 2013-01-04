<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('email_validation')) {
	// function email_validation($data) {
	// 	$this->load->library('email');

	// 	$this->email->from('no-reply@mashtagg.com', 'No Reply');
	// 	$this->email->to($data['email']);

	// 	$this->email->subject('mashtagg.com - Validate your email.');
	// 	$this->email->message('Follow this link to validate your email with slasht.com: \n http://localhost/validate_email/'.$data['valdation_string'].' ');

	// 	$this->email->send();

	// }

	function email_validation($data) {

		$CI = get_instance();
		$CI->load->helper('secret_helper');
		
		$ses = new Ses(AWS_SES_KEY, AWS_SES_SECRET);

		$m = new SimpleEmailServiceMessage();
		$m->addTo('johnspar1@gmail.com');
		$m->setFrom('johnspar1@gmail.com');
		$m->setSubject('Email verification from mashtagg.com');
		$m->setMessageFromString('Follow this link to validate your email with slasht.com: \n '.base_url().'validate?code='.$data['validation_code'].' ');

		$ses->sendEmail($m);
	}

	function email_password_reset($data) {

	}
}