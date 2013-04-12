
<div id="images_outer_container">

	<div class="images-header">

		<div class="images-header-inner clearfix">

			<div class="avatar-container header-container">

				<img src="<?php echo $user['avatar_thumbnail'];?>" />

			</div>

			<div class="username-container header-container">
				<p><?php echo $user['username'];?>'s images</p>
			</div>

			<div class="upload-button">
				<p>Upload Images</p>
			</div>

		</div>

	</div>

	<div class="images-container">

		<?php $count = 1; ?>

		<?php foreach($images as $image): ?>

			<?php if($count == 1):?>

				<div class="image-row clearfix">

			<?php elseif (($count - 1) % 4 == 0):?>

				</div>
				<div class="image-row clearfix">

			<?php endif;?>

			<div class="image-container">

				<img src="<?php echo $image['big_thumbnail_source'];?>" />

			</div>

			<?php $count++;?>

		<?php endforeach; ?>

		</div>

	</div>

</div>

<?$this->load->view("upload_images"); ?>