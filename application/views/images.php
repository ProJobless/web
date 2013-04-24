
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
				<p>Add Images</p>
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

				<div class="inspect-image">
					<a href="<?php echo base_url() . 'i/' . $image['image_id'];?>">
						<img src="<?php echo base_url() . 'assets/inspect_image.png';?>" />
					</a>
				</div>

				<img src="<?php echo $image['big_thumbnail_url'];?>" />
				<input type="hidden" class="image-url" value="<?=$image['url']?>"/>
				<input type="hidden" class="image-title" value="<?=$image['title']?>"/>
				<input type="hidden" class="image-description" value="<?=$image['description']?>"/>
				<input type="hidden" class="image-height" value="<?=$image['height']?>"/>
				<input type="hidden" class="image-width" value="<?=$image['width']?>"/>

				

			</div>

			<?php $count++;?>

		<?php endforeach; ?>

		</div>

	</div>

</div>

<div class="image-modal">
	<div class="inspect-image">
		<a href="<?php echo base_url() . 'i/' . $image['image_id'];?>">
			<img src="<?php echo base_url() . 'assets/inspect_image.png';?>" />
		</a>
	</div>
	<div class="close-modal">
		<img src="<?php echo base_url() . 'assets/uploadify-cancel.png';?>" />
	</div>
	<div class="next-image">
		<img src="<?php echo base_url() . 'assets/next_image.png';?>" />
	</div>
	<div class="prev-image">
		<img src="<?php echo base_url() . 'assets/prev_image.png';?>" />
	</div>
	<div class="image-wrapper">
		<img src="" />
	</div>
</div>
<div class="image-modal-screen"></div>

<?$this->load->view("upload_images"); ?>