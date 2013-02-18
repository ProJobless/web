<?php $this->load->helper('time_format_helper'); ?>

<div id="blog-outer-container">

	<div id="blog-author-container">
		<div class="portrait-container">
			<img src="<?php echo $author_info['profile_pic']; ?>" />
		</div>
		<div class="posts-button button">
			<p>Posts</p>
		</div>
		<div class="pictures-button button">
			<p>Pictures</p>
		</div>
		<div class="comments-button button">
			<p>Comments</p>
		</div>
		<div class="shares-button button">
			<p>Shares</p>
		</div>
		<div class="starred-button button">
			<p>Starred</p>
		</div>
	</div>

	<div id="blog-content-container">

		<?php if ($type == "self"): ?>

			<div class="compose-container">

				<div class="compose-inner-container">

					<a href="<?php echo base_url() . 'compose'; ?>">
						<div class="compose-button new_post">
							<img src="<?php echo base_url() . 'images/new_post.png';?>" />
							<p>Post</p>
						</div>
					</a>
					<a href="<?php echo base_url() . 'compose?type=image'; ?>">
						<div class="compose-button new_image">
							<img src="<?php echo base_url() . 'images/new_picture.png';?>" />
							<p>Image</p>
						</div>
						</a>
					<a href="<?php echo base_url() . 'compose?type=link'; ?>">
						<div class="compose-button new_link">
							<img src="<?php echo base_url() . 'images/new_share.png';?>" />
							<p>Link</p>
						</div>
						</a>
					<a href="<?php echo base_url() . 'compose?type=quote'; ?>">
						<div class="compose-button new_quote">
							<img src="<?php echo base_url() . 'images/new_quote.png';?>" />
							<p>Quote</p>
						</div>
					</a>
					<div style="clear:both"></div>
				</div>

			</div>

		<?php endif; ?>

		<?php if (sizeof($spool) > 0) { ?>

			<div class="posts-container">

				<?php $first_post = "first-post"; ?>
				
				<?php foreach ($spool as $row) { ?>
					
					<?php if ($row['type'] != 'comment') { ?>
						<div class="outer-post-container clearfix">
							<?php $first_post = ""; ?>
								<div id="<?php echo $row['sid']; ?>"class="post-container clearfix">
									<div class="vote-picture-container">
										<div class='comment-rating'>
											
											<div class='disabled_upvote'><img src="<?php echo base_url() . 'images/disabled_arrow_up.png'; ?>" /></div>
											<div class="influence_gain"><?php echo $row['influence_gain'];?></div>
											<div class='disabled_downvote'><img src="<?php echo base_url() . 'images/disabled_arrow_down.png'; ?>" /></div>
											
										</div>
										<div class="picture">
											<a href="<?php echo base_url() . $row['author']; ?>"><img src="<?php echo base_url() . $row['profile_pic']; ?>" alt="<?php echo $row['author']; ?>" /></a>
										</div>
										<div style="clear:both;"></div>
										<span class="arrow"></span>
									</div>
									<div class="inner-post-container  <?php echo $first_post; ?>">
										<div class="post-body-container">
											<div class="author">
												<a href="<?php echo base_url() . $row['author']; ?>">
													<?php echo $row['author']; ?>
												</a>
											</div>
											<?php if ($row['type'] != 'small-post' && $row['title'] != ''): ?>
												<h2><a href="<?php echo $row['url']; ?>" class="post-title"><?php echo $row['title']; ?></a></h2>
											<?php endif;?>
											<div class="post-body">
												<?php echo $row['body']; ?>
											</div>
										</div>
										<div class="expand-link">
											<img src="<?php echo base_url() . 'images/arrow_down_3.png'; ?>" />
										</div>

										<div class="meta-container">
											<div class="created-container left">
												<p>Created</p>
												<p class="bold-meta"><?php echo long_time_formatter($row['created']); ?></p>
											</div>
											<div class="last-post left">
												<p>Last comment</p>
												<p class="bold-meta">
													<?php if ($row['last_comment'] == ''){
														echo 'Never';
													} else {
														echo long_time_formatter($row['last_comment']);
													} ?>
												</p>
											</div>
											<div class="view-amount left">
												<p>Views</p>
												<p class="bold-meta"><?php echo $row['views_count'];?></p>
											</div>
											<div class="comment-amount left">
												<p>Comments</p>
												<p class="bold-meta"><?php echo $row['comments_count']; ?></p>
											</div>
											<div class="share-amount left">
												<p>Shares</p>
												<p class="bold-meta"><?php echo $row['shares_count'];?></p>
											</div>
											<div class="tag-container left">
												<p>Tags</p>
												<p class="bold-meta">
													<?php if (count($row['tags']) > 0){ 
														foreach ($row['tags'] as $tag) { 
															echo '<span class="tag"><a href="' . base_url() . '/t/' . $tag . '/"' . $tag . '</a></span> ';
														}
													} else {
														echo 'None';
													} ?>
												</p>
											</div>

											<div class="config-container right">
											</div>

										</div>

									</div>

								</div>
						</div>
					<?php } ?>
					
					
				<?php } ?>

			</div>
				
		<?php } ?>

	</div>

	<div style="clear:both"></div>

</div>

<div id="post-form-container">
	
	<?php echo form_open('ajax/add_post'); ?>

	<div class="fixed">	
		<label class="label" for="title">Title: </label>
		<div class="input">
			<input id="title" type="text" size="20" />
		</div>
	</div>
	<div class="fixed">	
		<label class="label" for="body">Body: </label>
		<div class="input">
			<textarea id="body" rows="3" cols="40"></textarea>
		</div>
	</div>
	<div class="fixed">	
		<label class="label" for="tags">Tags: </label>
		<div class="input">
			<textarea id="tags" rows="3" cols="20"></textarea>
		</div>
	</div>
	<div class="fixed">	
		<label class="label" for="publish">Publish:</label>
		<div class="input-checkbox">
			<input type="checkbox" id="publish" />
		</div>
	</div>
		
	<div class="fixed">	
		<div class="label"></div>
		<div class="input">
			<input type="button" id="post-submit" value="Post" />
		</div>
		<input type="hidden" id="type" value="post" />
		<input type="hidden" id="parent" value="" />
		<input type="hidden" id="root" value="" />
		
	</div>
	
	<?php echo form_close(); ?>
			
	<span class="close">
		<a href='/' id='close'>Close</a>
	</span>
	
</div>