<?php if (!$u = Current_user::user()) {$this->load->view('welcome_page');} ?>

<div id="settings-container">
	<div id="tab-container">
	
		<div class="settings-menu-item" ><a href="<?php echo base_url();?>settings/">Account</a></div>
		<div class="settings-menu-item" ><a href="<?php echo base_url();?>settings/password">Password</a></div>
		<div class="settings-active-menu-item" >Profile</div>
	
	</div>
	<div id="settings-inner">
		<?php echo form_open('settings/submit'); ?>
		<div class="fixed">			
			<div class="settings-label">&nbsp;</div>
			<div class="input">
				<h2>Profile settings</h2>
			</div>
		</div>

		<?php echo validation_errors('<p class="error">','</p>'); ?>

		<?php if ($u['website'] == '') {
			$website = 'http://';
		} else {
			$website = $u['website'];
		} ?>

		<div class="fixed">			
			<label class="label" for="avatar">Avatar: </label>
			<div class="input">
				<img id="profile_avatar" src="<?php echo base_url() . $u['profile_pic']; ?>" />
				<p><input type="file" name="avatar" onchange="readURL(this, '#profile_avatar');" /></p>
			</div>
		</div>
		<div class="fixed">		
			<label class="label" for="full_name">Full Name: </label>
			<div class="input">
				<input type="text" name="full_name" value="<?php echo $u['full_name']; ?>">
			</div>
		</div>
		<div class="fixed">		
			<label class="label" for="website">Website: </label>
			<div class="input">
				<input type="text" name="website" value="<?php echo $website; ?>">
			</div>
		</div>
		<div class="fixed">		
			<label class="label" for="location">Location: </label>
			<div class="input">
				<input type="text" name="location" value="<?php echo $u['location']; ?>">
			</div>
		</div>
		<div class="fixed">		
			<label class="label" for="blurb">Blurb: </label>
			<div class="input">
				<textarea name="blurb"><?php echo $u['blurb']; ?></textarea>
			</div>
		</div>
		<div class="fixed">	
			<div class="label">
				&nbsp;
			</div>
			<div class="input">
				<input type="hidden" name="hidden" value="profile" />
				<?php echo form_submit('submit','Submit'); ?>				
			</div>
		</div>
		
		<?php echo form_close(); ?>
	</div>
	
</div>