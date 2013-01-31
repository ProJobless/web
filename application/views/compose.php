<div class="compose-content-container">
	<div class="compose-outer-container">
		<div class="navigation-outline">
		</div>
		<?php echo form_open('compose/submit'); ?>
			<div class="input-container">

				<?php if($type == "image"): ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<div class="image-upload-container">
					</div>
					<textarea id="new_post_caption" name="post_body"></textarea>
					<div class="controls-container">
						<?php echo form_submit('submit','Create Post'); ?>
						<div class="publish-container">
							<input type="checkbox" name="published" value="true" checked /><p>Publish</p>
							<div style="clear:both"></div>
						</div>
						<div style="clear:both"></div>
					</div>

				<?php elseif($type == "link"): ?>

				<?php elseif($type == "quote"): ?>

				<?php else: ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<textarea id="new_post_textarea" name="post_body"></textarea>
					<div class="controls-container">
						<?php echo form_submit('submit','Create Post'); ?>
						<div class="publish-container">
							<input type="checkbox" name="published" value="true" checked /><p>Publish</p>
							<div style="clear:both"></div>
						</div>
						<div style="clear:both"></div>
					</div>

				<?php endif; ?>
				
			</div>
		<?php echo form_close(); ?>
	</div>
</div>