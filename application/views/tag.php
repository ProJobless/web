<?php $this->load->helper('time_format_helper'); ?>

<div id="tag-outer-container">

	<div id="tag-meta-container">

		<div class="meta-content">

			<?php if (is_array($tags)) {

				foreach($tags as $tag) {

					echo '<h1>#' . $tag . '</h1>';

				}

			} else {

				echo '<h1>#' . $tags . '</h1>';

			} ?>

		</div>
		
	</div>

	<div id="tag-content-container">

		<?php if (sizeof($posts) > 0) { ?>

			<?php $post_amount = sizeof($posts); ?>

			<div class="posts-container">

				<?php $first_post = "first-post"; ?>

				<?php $post_number = 0; ?>
				
				<?php foreach ($posts as $post) { ?>

					<?php $post_number++;
					if ($post_number == $post_amount) {
				  		$last_post = "last-post";
				    } else {
				  		$last_post = "";
				    } ?>
					
					<?php if ($post['type'] != 'comment') { ?>
						<div id="<?php echo $post['sid']; ?>" class="outer-post-container clearfix <?php echo $first_post; ?> <?php echo $last_post; ?>">
							<?php $first_post = ""; ?>
								<div id="<?php echo $post['sid']; ?>"class="post-container clearfix">
									<div class="vote-picture-container">
										<div class='comment-rating feed-post'>
											
											<?php if ($post['vote_status']): ?>

												<?php if($post['vote_status'] == "disabled"): ?>
													<div class='disabled_upvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_up.png'; ?>" /></div>
													<div class="influence_gain"><?php echo $post['influence_gain'];?></div>
													<div class='disabled_downvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_down.png'; ?>" /></div>
												<?php elseif($post['vote_status'] == "enabled"): ?>
													<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
													<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
												<?php elseif($post['vote_status'] == "downvote-disabled"): ?>
													<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
													<div class='clicked_downvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_down.png'; ?>" /></div>
												<?php elseif($post['vote_status'] == "upvote-disabled"): ?>
													<div class='clicked_upvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
													<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
												<?php endif; ?>

											<?php endif; ?>
											
										</div>
										<div class="picture">
											<a href="<?php echo base_url() . $post['author']; ?>"><img src="<?php echo base_url() . $post['avatar_thumbnail']; ?>" alt="<?php echo $post['author']; ?>" /></a>
										</div>
										<div style="clear:both;"></div>
										<span class="arrow"></span>
									</div>
									<div class="inner-post-container  <?php echo $first_post; ?>">
										<?php if(isset($post['thumbnail_image']) && $post['thumbnail_image'] != ''): ?>
											<div class="link-image-container">
												<a href="<?php echo $post['share_url']; ?>">
													<img src="<?=$post['thumbnail_image'];?>" />
												</a>
											</div>
										<?php endif; ?>
										<div class="post-body-container">
											<div class="author">
												<a href="<?php echo base_url() . $post['author']; ?>">
													<?php echo $post['author']; ?>
												</a>
												<?php if ($post['type'] == 'share'): ?>
													<img class="share-icon" src="<?=base_url() . 'assets/little_share.png';?>" />
													<a href="<?php echo $post['share_url']; ?>">
														<?php echo $post['share_root']; ?>
													</a>
												<?php elseif ($post['type'] == 'quote'): ?>
													<img class="share-icon" src="<?=base_url() . 'assets/little_share.png';?>" />
													<?php echo $post['quote_author']; ?>
												<?php endif; ?>
											</div>

											<div class="control-buttons">	
												<?php if($post['save_status'] == 'not-saved'): ?>
													<img class="share-post" src="<?php echo base_url() . 'assets/new_share.png';?>" />
													<img class="save-post unclicked" src="<?php echo base_url() . 'assets/star_fav_empty.png'; ?>" />
												<?php elseif($post['save_status'] == 'saved'): ?>
													<img class="share-post" src="<?php echo base_url() . 'assets/new_share.png';?>" />
													<img class="save-post clicked" src="<?php echo base_url() . 'assets/star_fav_full.png'; ?>" />
												<?php endif; ?>
											</div>

											<h2><a href="<?php echo $post['url']; ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
										</div>
										<div class="expand-link">
											<img src="<?php echo base_url() . 'assets/arrow_down_3_50p.png'; ?>" />
										</div>

										<div class="meta-container">
											<div class="created-container left">
												<p>Created</p>
												<p class="bold-meta">
													<a href="<?php echo $post['url']; ?>"><?php echo long_time_formatter($post['created']); ?></a>
												</p>
											</div>
											<div class="last-post left">
												<p>Last comment</p>
												<p class="bold-meta">
													<?php if ($post['last_comment'] == ''){
														echo 'Never';
													} else {
														echo long_time_formatter($post['last_comment']);
													} ?>
												</p>
											</div>
											<div class="view-amount left">
												<p>Views</p>
												<p class="bold-meta">
													<a href="<?php echo $post['url']; ?>"><?php echo $post['views_count'];?></a>
												</p>
											</div>
											<div class="comment-amount left">
												<p>Comments</p>
												<p class="bold-meta">
													<a href="<?php echo $post['url'] . '?tab=comments'; ?>"><?php echo $post['comments_count']; ?></a>
												</p>
											</div>
											<div class="share-amount left">
												<p>Shares</p>
												<p class="bold-meta">
													<a href="<?php echo $post['url'] . '?tab=shares'; ?>"><?php echo $post['shares_count'];?></a>
												</p>
											</div>
											<div class="tag-container left">
												<p>Tags</p>
												<p class="bold-meta">
													<a href="<?php echo $post['url'] . '?tab=tags'; ?>"><?php echo count($post['tags']); ?></a>
												</p>
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