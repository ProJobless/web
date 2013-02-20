<div class="profile-container">
	<div class="profile-header">
		<h1><?php echo $user_info['username']; ?></h1>
	</div>
	<div class="bio-container">
		<div class="left-bio">
			<img src="<?php echo base_url() . $user_info['profile_pic']; ?>" />
			<div class="influence-container">
				<span class="influence"><?php echo $user_info['influence']; ?></span><br />
				<span class="influence-label">Influence</span>
			</div>
		</div>
		<div class="right-bio">
			<p>Name: <span style="font-weight:bold"><?php echo $user_info['full_name']; ?></span></p>
			<p>Website: <span style="font-weight:bold"><a style="color:#DE935F" href="<?php echo $user_info['website']; ?>"><?php echo $user_info['website']; ?></a></span></p>
			<p>Location: <span style="font-weight:bold"><?php echo $user_info['location']; ?></span></p>
			<p><?php echo $user_info['member_for_string']; ?></p>
			<p>Last seen <?php echo $user_info['last_seen_string']; ?></p>
			<p>Profile views: <span style="font-weight:bold"><?php echo $user_info['profile_views']; ?></span></p>
			<p><a href="<?php echo base_url() . $this->uri->uri_string . '?tab=following';?>">Following: <span style="font-weight:bold"><?php echo $following_count; ?></a></span></p>
			<p><a href="<?php echo base_url() . $this->uri->uri_string . '?tab=followers';?>">Followers: <span style="font-weight:bold"><?php echo $followers_count; ?></a></span></p>
			<?php if ($user_info['validated']): ?>
				<p>You email is validated.</p>
			<?php else: ?>
				<p><a href="<?php echo base_url() . 'validate_email/'; ?>">Validate your email</a></p>
			<?php endif; ?>
			<p><a href="<?php echo base_url() . 'refer'; ?>">Refer your friends</a></p>
		</div>
		<div class="about">
			<p><?php echo $user_info['blurb']; ?></p>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php $this->load->view('profile_summary'); ?>
</div>