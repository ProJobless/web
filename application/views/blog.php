<?php $this->load->helper('time_format_helper'); ?>

<div id="blog-outer-container">

	<div id="blog-author-container">
		<div class="portrait-container">
			<img src="<?php echo base_url() . $author_info['avatar']; ?>" />
		</div>

		<?php if ($type == "self"): ?>

			<a href="<?php echo base_url() . 'posts';?>">
				<div class="posts-button button">
					<p>Posts</p>
				</div>
			</a>
			<a href="<?php echo base_url() . 'images';?>">
				<div class="pictures-button button">
					<p>Pictures</p>
				</div>
			</a>
			<a href="<?php echo base_url() . 'comments';?>">
				<div class="comments-button button">
					<p>Comments</p>
				</div>
			</a>
			<a href="<?php echo base_url() . 'shares';?>">
				<div class="shares-button button">
					<p>Shares</p>
				</div>
			</a>
			<a href="<?php echo base_url() . 'starred';?>">
				<div class="starred-button button">
					<p>Starred</p>
				</div>
			</a>
		<?php endif; ?>
	</div>

	<div id="blog-content-container">

		<?php if ($type == "self"): ?>

			<div class="compose-container">

				<div class="compose-inner-container">

					<a href="<?php echo base_url() . 'compose'; ?>">
						<div class="compose-button new_post">
							<img src="<?php echo base_url() . 'assets/new_post.png';?>" />
							<p>Post</p>
						</div>
					</a>
					<a href="<?php echo base_url() . 'compose?type=image'; ?>">
						<div class="compose-button new_image">
							<img src="<?php echo base_url() . 'assets/new_picture.png';?>" />
							<p>Image</p>
						</div>
						</a>
					<a href="<?php echo base_url() . 'compose?type=link'; ?>">
						<div class="compose-button new_link">
							<img src="<?php echo base_url() . 'assets/new_share.png';?>" />
							<p>Link</p>
						</div>
						</a>
					<a href="<?php echo base_url() . 'compose?type=quote'; ?>">
						<div class="compose-button new_quote">
							<img src="<?php echo base_url() . 'assets/new_quote.png';?>" />
							<p>Quote</p>
						</div>
					</a>
					<div style="clear:both"></div>
				</div>

			</div>

		<?php endif; ?>

		<?php if (sizeof($posts) > 0) { ?>

			<?php $post_amount = sizeof($posts); ?>

			<div class="posts-container">

				<?php $first_post = "first-post"; ?>

				<?php $post_number = 0; ?>
				
				<?php foreach ($posts as $row) { ?>

					<?php $post_number++;
					if ($post_number == $post_amount) {
				  		$last_post = "last-post";
				    } else {
				  		$last_post = "";
				    } ?>
					
					<?php if ($row['type'] != 'comment') { ?>
						<div class="outer-post-container clearfix <?php echo $first_post; ?> <?php echo $last_post; ?>">
							<?php $first_post = ""; ?>
								<div id="<?php echo $row['sid']; ?>"class="post-container clearfix">
									<div class="vote-picture-container">
										<div class='comment-rating feed-post'>
											
											<?php if (isset($row['vote_status']) && $row['vote_status'] && $type != "self"): ?>

												<?php if($row['vote_status'] == "disabled"): ?>
													<div class='disabled_upvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_up.png'; ?>" /></div>
													<div class="influence_gain"><?php echo $row['influence_gain'];?></div>
													<div class='disabled_downvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_down.png'; ?>" /></div>
												<?php elseif($row['vote_status'] == "enabled"): ?>
													<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $row['influence_gain'];?></div>
													<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
												<?php elseif($row['vote_status'] == "downvote-disabled"): ?>
													<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $row['influence_gain'];?></div>
													<div class='clicked_downvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_down.png'; ?>" /></div>
												<?php elseif($row['vote_status'] == "upvote-disabled"): ?>
													<div class='clicked_upvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_up.png'; ?>" /></div>
													<div class='influence_gain'><?php echo $row['influence_gain'];?></div>
													<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
												<?php endif; ?>

											<?php else: ?>

												<div class='disabled_upvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_up.png'; ?>" /></div>
												<div class="influence_gain"><?php echo $row['influence_gain'];?></div>
												<div class='disabled_downvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_down.png'; ?>" /></div>

											<?php endif; ?>
											
										</div>
										<div class="picture">
											<a href="<?php echo base_url() . $row['author']; ?>"><img src="<?php echo base_url() . $row['avatar_thumbnail']; ?>" alt="<?php echo $row['author']; ?>" /></a>
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
												<?php if ($row['type'] == 'share'): ?>
													<img class="share-icon" src="<?=base_url() . 'assets/little_share.png';?>" />
													<a href="<?php echo $row['share_url']; ?>">
														<?php echo $row['share_root']; ?>
													</a>
												<?php elseif ($row['type'] == 'quote'): ?>
													<img class="share-icon" src="<?=base_url() . 'assets/little_share.png';?>" />
													<?php echo $row['quote_author']; ?>
												<?php endif; ?>
											</div>

											<?php if ($type != "self"): ?>
												<div class="control-buttons">
													<img class="share-post" src="<?php echo base_url() . 'assets/new_share.png';?>" />
													<?php if ($row['save_status'] == 'disabled'): ?>
														<img class="save-post-disabled" src="<?php echo base_url() . 'assets/star_fav_empty.png'; ?>" />
													<?php elseif($row['save_status'] == 'not-saved'): ?>
														<img class="save-post unclicked" src="<?php echo base_url() . 'assets/star_fav_empty.png'; ?>" />
													<?php else: ?>
														<img class="save-post clicked" src="<?php echo base_url() . 'assets/star_fav_full.png'; ?>" />
													<?php endif; ?>
												</div>
											<?php endif;?>

											<?php if ($row['type'] != 'small-post' && $row['type'] != 'quote' && $row['title'] != ''): ?>
												<h2><a href="<?php echo $row['url']; ?>" class="post-title"><?php echo $row['title']; ?></a></h2>
											<?php endif;?>
											<?php if ($row['type'] == 'quote'):?>
												<div class="post-body quote-body">
													<?php echo $row['body']; ?>
												</div>
											<?php else: ?>
												<div class="post-body">
													<?php echo $row['body']; ?>
												</div>
											<?php endif; ?>
										</div>
										<div class="expand-link">
											<img src="<?php echo base_url() . 'assets/arrow_down_3_50p.png'; ?>" />
										</div>

										<div class="meta-container">
											<div class="created-container left">
												<p>Created</p>
												<p class="bold-meta">
													<a href="<?php echo $row['url']; ?>"><?php echo long_time_formatter($row['created']); ?></a>
												</p>
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
												<p class="bold-meta">
													<a href="<?php echo $row['url']; ?>"><?php echo $row['views_count'];?></a>
												</p>
											</div>
											<div class="comment-amount left">
												<p>Comments</p>
												<p class="bold-meta">
													<a href="<?php echo $row['url'] . '?tab=comments'; ?>"><?php echo $row['comments_count']; ?></a>
												</p>
											</div>
											<div class="share-amount left">
												<p>Shares</p>
												<p class="bold-meta">
													<a href="<?php echo $row['url'] . '?tab=shares'; ?>"><?php echo $row['shares_count'];?></a>
												</p>
											</div>
											<div class="tag-container left">
												<p>Tags</p>
												<p class="bold-meta">
													<a href="<?php echo $row['url'] . '?tab=tags'; ?>"><?php echo count($row['tags']); ?></a>
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