<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Login Form</title>
	<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="all">
</head>
<body>

<?php
if (!isset($signup)){
	$signup = "hidden";
	$login = "";
	$signup_code = "";
}
if (!isset($signup_code)){
	$signup_code = "";
}
?>

<div id="body">

	<div id="content">

		<div id="login-outer-container">

			<div id="logo">
				
			</div>

			<div class="login-boxes">

			</div>

			<div id="signup_form">

				<div class="login-form <?php echo $login; ?>">

					<?php echo form_open('login/submit', array("autocomplete" => "off")); ?>

					<?php echo validation_errors('<p class="error">','</p>'); ?>

					<p>
						<?php echo form_input(array("name" => "username", "placeholder" => "Username or Email")); ?>
					</p>
					<p>
						<?php echo form_password(array("name" => "password", "placeholder" => "Password")); ?>
					</p>

					<div class="submit-forgot-container">
						<a href="<?php echo base_url() . 'forgot_password';?>" class="forgot_password">Forget your password?</a>
						<?php echo form_submit('submit','Login'); ?>
						<div style="clear:both"></div>
					</div>

					<div class="signup-container">
						<p>Mashtagg is currently invite only, but if you have an invite code:</p>
						<span class="signup-button">Signup here</span>
					</div>

					<?php echo form_close(); ?>

				</div>

				<div class="signup-form <?php echo $signup; ?>">
					<?php echo form_open('signup/submit', array("autocomplete" => "off")); ?>

					<?php echo validation_errors('<p class="error">','</p>'); ?>
					
					<p>
						<input type="text" id="username" name="username" placeholder="Username" />
					</p>
					<p>
						<input type="text" id="email" name="email" placeholder="Email address" />
					</p>	
					<p>
						<input type="password" id="password" name="password" placeholder="Password" />
					</p>
					<p>
						<input type="password" id="passconf" name="passconf" placeholder="Confirm password" />
					</p>
					<p>
						<input type="text" name="signup_code" placeholder="Invite code" value="<?php echo $signup_code; ?>" />
					</p>
					<div class="button-container">
						<?php echo form_submit('submit','Create my account'); ?>
						<div class="cancel-signup">Cancel</div>
						<div style="clear:both"></div>
					</div>
					<?php echo form_close(); ?>
				</div>

				<div class="forgot-password-form hidden">
					<?php echo form_open('forgot-password/submit', array("autocomplete" => "off")); ?>

					<p>Please enter the email address you used to register your account:</p>

					<p>
						<input id="forgot_password_email" type="text" name="email" placeholder="Email Address" />
					</p>

					<div class="button-container">
						<div id="submit-forgot-password">Submit</div>
						<div class="cancel-forgot-password">Cancel</div>
						<div style="clear:both"></div>
					</div>

					<div class="info-container">
						<p>We will send you an email directing you to a page where you can reset your password.</p>
					</div>

					<?php echo form_close(); ?>
				</div>

				<div class="forgot-password-success hidden">
					<p>An email was sent to the submitted email address if that email address is a valid email for one of our accounts.</p>
					<p>Click <span class="return-to-login">here</span> to return to the login form.</p>
				</div>

				<div class="forgot-password-failure hidden">
					<p>An error has occured when trying to send an email to that address, please try again later.</p>
				</div>

			</div>

		</div>

	</div>

	<div id="footer">
		<p>© 2013 John Sparwasser</p>
	</div>

</div>

</body>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/mashtagg_globals.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/mashtagg.js"></script>

</html>
