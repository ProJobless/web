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
		
		$ses = new SimpleEmailService(AWS_SES_KEY, AWS_SES_SECRET);

	}
}