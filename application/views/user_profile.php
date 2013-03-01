<div class="profile-container">
	<div class="profile-header">
		<h1><?php echo $user_info['username']; ?></h1>
		<input type="hidden" id="username" value="<?php echo $user_info['username']; ?>" />
	</div>
	<div class="bio-container">
		<div class="left-bio">
			<img src="<?php echo base_url() . $user_info['avatar']; ?>" width="150" />
		</div>
		<div class="right-bio">
			<p>Name: <?php echo $user_info['full_name']; ?></p>
			<p>Website: <a href="<?php echo $user_info['website']; ?>"><?php echo $user_info['website']; ?></a></p>
			<p>Location: <?php echo $user_info['website']; ?></p>
			<p><?php echo $user_info['member_for_string']; ?></p>
			<p>Last seen <?php echo $user_info['last_seen_string']; ?></p>
			<p>Profile views: <?php echo $user_info['profile_views']; ?></p>
			<p><a href="<?php echo base_url() . $this->uri->uri_string . '?tab=following';?>">Following: <span style="font-weight:bold"><?php echo $following_count; ?></a></span></p>
			<p><a href="<?php echo base_url() . $this->uri->uri_string . '?tab=followers';?>">Followers: <span style="font-weight:bold"><?php echo $followers_count; ?></a></span></p>
			<p><a href="<?php echo base_url() . $this->uri->uri_string . '?tab=influence';?>">Influence: <span style="font-weight:bold"><?php echo $user_info['influence']; ?></a></span></p>
			<p>
				<?php if ($following): ?>
					<input id="follow-button" type="button" value="Unfollow" />
				<?php else: ?>
					<input id="follow-button" type="button" value="Follow" />
				<?php endif; ?>
			</p>
		</div>
		<div class="about">
			<p><?php echo $user_info['blurb']; ?></p>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php $this->load->view('profile_summary'); ?>
</div>