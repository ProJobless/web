<div class="compose-content-container">
	<?php echo form_open_multipart('compose/submit'); ?>
		<div class="compose-outer-container clearfix">
			<div class="navigation-outline">
				&nbsp;
			</div>
			<div class="input-container">
				<input type="hidden" name="type" value="<?php echo $type;?>" />
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
						</div>
					</div>

				<?php elseif($type == "link"): ?>

					<div class="title-input-container">
						<input type="text" id="post-title" name="post_title" placeholder="Title" />
					</div>
					<div class="link-input-container">
						<input type="text" id="link" name="link" placeholder="URL" />
					</div>
					<div class="link-loading-container">
						<img src="<?= base_url() . 'assets/loading.gif';?>" />
					</div>
					<div class="link-placeholder-container">
						<div class="link-placeholder">
							<div class="link-summary-container">
								<div class="image-container">
									<img class="link-image" />
								</div>
								<div class="text-container">
									<h2 class="link-title"><a class="link-title-link" href=""></a></h2>
									<p class="link-description"></p>
								</div>
								<div style="clear:both"></div>
							</div>
							<div class="link-media-container">
								<div class="image-container">
									<img class="link-image" />
								</div>
								<div class="link-player-container">
									<iframe id="player_frame"></iframe>
									<object id="object_frame" type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" bgcolor="#000000">
										<param name="allowFullScreen" value="true" />
										<param name="allowScriptAccess" value="always" />
										<param name="allowNetworking" value="all" />
										<param name="movie" value="http://www.justin.tv/widgets/live_embed_player.swf" />
									</object>
								</div>
							</div>
						</div>
						<div><input type="button" class="cancel-link" value="Cancel" /></div>
					</div>
					<textarea id="new_post_caption" name="post_body" placeholder="Caption"></textarea>
					<div class="tags-input-container">
						<input type="text" id="post-tags" name="post_tags" placeholder="Tags" />
					</div>

					<input id="link_url" name="link_url" type="hidden" />
					<input id="link_title" name="link_title" type="hidden" />
					<input id="link_description" name="link_description" type="hidden" />
					<input id="link_media_url" name="link_media_url" type="hidden" />
					<input id="link_type" name="link_type" type="hidden" />
					<input id="link_image" name="link_image" type="hidden" />
					<input id="link_base_url" name="link_base_url" type="hidden" />
					<input id="link_media_height" name="link_media_height" type="hidden" />
					<input id="link_media_width" name="link_media_width" type="hidden" />

					<div class="controls-container">
						<?php echo form_submit('submit','Create Post'); ?>
						<div class="publish-container">
							<input type="checkbox" name="published" value="true" checked /><p>Publish</p>
						</div>
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
						</div>
					</div>

				<?php endif; ?>
				
			</div>
		</div>
	<?php echo form_close(); ?>
</div>