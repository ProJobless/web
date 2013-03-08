<div class="compose-content-container">
	<div class="compose-outer-container">
		<div class="navigation-outline">
			&nbsp;
		</div>
		<?php echo form_open_multipart('compose/submit'); ?>
			<input type="hidden" name="type" value="<?php echo $type;?>" />
			<div class="input-container">

				<?php if($type == "image"): ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<div class="image-upload-container">
						<input type="file" id="upload_image" name="userfile" />
					</div>
					<div class="image-placeholder-container">
						<div><img class="upload-image-preview" /></div>
						<div><input type="button" class="cancel-image" value="Cancel" /></div>
					</div>
					<textarea id="new_post_caption" name="post_body" placeholder="Caption"></textarea>
					<div class="tags-input-container">
						<input type="text" id="post-tags" name="post_tags" placeholder="Tags" />
					</div>
					<div class="controls-container">
						<?php echo form_submit('submit','Create Post'); ?>
						<div class="publish-container">
							<input type="checkbox" name="published" value="true" checked /><p>Publish</p>
							<div style="clear:both"></div>
						</div>
						<div style="clear:both"></div>
					</div>

				<?php elseif($type == "link"): ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<div class="link-container">
						<input type="text" id="link" name="link" />
					</div>
					<div class="link-placeholder-container">
						<div><img class="upload-image-preview" /></div>
						<div><input type="button" class="cancel-image" value="Cancel" /></div>
					</div>
					<textarea id="new_post_caption" name="post_body" placeholder="Caption"></textarea>
					<div class="tags-input-container">
						<input type="text" id="post-tags" name="post_tags" placeholder="Tags" />
					</div>
					<div class="controls-container">
						<?php echo form_submit('submit','Create Post'); ?>
						<div class="publish-container">
							<input type="checkbox" name="published" value="true" checked /><p>Publish</p>
							<div style="clear:both"></div>
						</div>
						<div style="clear:both"></div>
					</div>

				<?php elseif($type == "quote"): ?>

				<?php else: ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<textarea id="new_post_textarea" name="post_body"></textarea>
					<div class="tags-input-container">
						<input type="text" id="post-tags" name="post_tags" placeholder="Tags" />
					</div>
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
		<div style="clear:both"></div>
	</div>
</div>