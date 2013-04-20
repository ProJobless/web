<?php echo form_open_multipart('images/multi_upload_submit'); ?>
<div class="upload-config">
	<p class="upload-text"><input id="image_url" type="text" placeholder="Enter a URL"/><span class="upload-url-button"></span></p>
	<p class="or-text">OR</p>
	<div class="upload-button-holder">
		<input type="file" name="userfile" id="upload_btn" />
	</div>
	<div class="post-upload-container">
		<div class="picture-attributes">
			<div class="name-description-container">
				<input id="selected_image_id" type="hidden" value="" />
				<p id="picture_attributes_placeholder">Select a picture to edit</p>
				<p id="picture_name">Test</p>
				<input type="text" id="picture_name_input" placeholder="Add a title" />
				<p id="picture_description">Description</p>
				<textarea id="picture_description_input" placeholder="Add a description"></textarea>
			</div>
			<div class="tags-container">
				<p id="picture_tags">Tags</p>
				<input type="text" id="picture_tags_input" value="" placeholder="Separated by spaces" />
			</div>
		</div>
		<div class="submit-container">
			<div class="alert-border">
				<input type="submit" class="confirm-upload" value="Upload 1 File"/>
			</div>
		</div>
	</div>
</div>

<div id="upload-queue" class="upload-queue clearfix">
<div class="modal-screen"></div>	

</div>
<?php echo form_close(); ?>
