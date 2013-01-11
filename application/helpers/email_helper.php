<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if (!defined('MT_VALIDATION_EMAIL')) define('MT_VALIDATION_EMAIL', 'email-validation@mashtagg.com');
if (!defined('MT_PASSWORD_RESET_EMAIL')) define('MT_PASSWORD_RESET_EMAIL', 'password-reset@mashtagg.com');


if ( ! function_exists('email_validation')) {

	function email_validation($data) {

		$CI = get_instance();
		$CI->load->helper('secret_helper');
		
		$ses = new Ses(AWS_SES_KEY, AWS_SES_SECRET);

		$m = new SimpleEmailServiceMessage();
		$m->addTo($data['email']);
		$m->setFrom(MT_VALIDATION_EMAIL);
		$m->setSubject('Email verification from mashtagg.com');
		$m->setMessageFromString('Follow this link to validate your email with mashtagg: '.base_url().'/validate_email/validate?code='.$data['validation_code'].' ');

		$ses->sendEmail($m);
	}

}

if ( ! function_exists('email_password_reset')) {

	function email_password_reset($data) {

		$CI = get_instance();
		$CI->load->helper('secret_helper');
		
		$ses = new Ses(AWS_SES_KEY, AWS_SES_SECRET);

		$m = new SimpleEmailServiceMessage();
		$m->addTo($data['email']);
		$m->setFrom(MT_PASSWORD_RESET_EMAIL);
		$m->setSubject('Password reset from mashtagg.com');
		$m->setMessageFromString('Follow this link to reset your password with mashtagg: '.base_url().'/forgot_password/reset?code='.$data['pw_reset_code'].' ');

		$ses->sendEmail($m);

	}
	
}