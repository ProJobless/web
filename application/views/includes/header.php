<!DOCTYPE html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Slasht</title>
	<script src="<?=base_url()?>scripts/jquery.js" ></script>
	<script src="<?=base_url()?>scripts/slasht.js" ></script>
	<link href='http://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:300italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" charset="utf-8">
</head>

<body>
<?php if ($u = Current_User::user()):?>
<div id="top-bar">
	<div id = "container1">
		<div id = "slasht"><p><a href = "<?php echo base_url();?>">slasht</a></p></div>
		<div id = "container3">
			<p>
				<span class="link-box feed-link"><a href = "<?php echo base_url();?>feed">FEED</a></span><span class="link-box blog-link"><a href = "<?php echo base_url();?>blog">BLOG</a></span><span class="link-box profile-link"><a href = "<?php echo base_url() . 'profile'; ?>">PROFILE</a></span><span class="link-box tags-link"><a href = "<?php echo base_url();?>tags">TAGS</a></span><span class="link-box users-link"><a href = "<?php echo base_url();?>users">USERS</a></span><span class="link-box saved-link"><a href = "<?php echo base_url();?>saved">SAVED</a></span><span class="link-box settings-link"><a href = "<?php echo base_url();?>settings">SETTINGS</a></span><span class="link-box about-link"><a href = "<?php echo base_url();?>about">ABOUT</a></span>
			</p>
		</div>
	</div>
	<div id = "container2">
		<div id = "user_control_box"><p>Hello <em><?php echo $u['username']; ?></em></p></div>
		<div id = "logout"><p><?php echo anchor('logout','Logout'); ?></p></div>
	</div>
</div>


<?php else: ?>

<div id="top-bar">
	<div id = "container1">
		<div id = "slasht"><p><a href = "<?php echo base_url();?>">Slasht</a></p></div>
		<div id = "container3">
			<p>
				<a href = "<?php echo base_url();?>tags">TAGS</a> |
				<a href = "<?php echo base_url();?>users">USERS</a> |
				<a href = "<?php echo base_url();?>about">ABOUT</a>
			</p>
		</div>
	</div>
</div>

<?php endif; ?>