<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Signup Form</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css"
		type="text/css" media="all">
</head>
<body>

<div id="lone_signup_form">

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
		<input type="text" name="signup_code" placeholder="Invite code" />
	</p>
	<div class="button-container">
		<?php echo form_submit('submit','Create my account'); ?>
		<p><a href="<?php echo base_url(); ?>" class="cancel-signup">Cancel</a></p>
		<div style="clear:both"></div>
	</div>
	<?php echo form_close(); ?>

</div>