<div class="compose-content-container">
	<div class="compose-outer-container">
		<div class="navigation-outline">
		</div>
		<?php echo form_open('compose/submit'); ?>
			<div class="input-container">
				<div class="title-input-container">
					<input type="text" id="post-title" name="post_title" placeholder="Title" />
				</div>
				<textarea id="example_textarea" name="post_body"></textarea>
				<?php echo form_submit('submit','Create Post'); ?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url(); ?>scripts/ckeditor/ckeditor.js"></script>
<script type='text/javascript'>

	CKEDITOR.replace('example_textarea', {
		height:"625px"
	});

</script>