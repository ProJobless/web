<div class="change_password_container">
	<?php echo form_open('forgot_password/change_password', array("autocomplete" => "off")); ?>
		<?php echo validation_errors('<p class="error">','</p>'); ?>
		<p>To reset your password, enter your new password below:</p>
		<p>
			<input type="password" id="password" name="password" placeholder="Password" />
		</p>
		<p>
			<input type="password" id="passconf" name="passconf" placeholder="Confirm password" />
			<input type="hidden" id="code" name="code" value="<?php echo $code; ?>" />
		</p>
		<p>
			<input type="submit" value="Submit" />
			<div style="clear:both"></div>
		</p>
	<?php echo form_close(); ?>
</div>