<?php $u = Current_User::user(); ?>

<?php $this->load->helper('time_format_helper'); ?>

<div id="post_container">
	<div id="<?php echo $post['sid'];?>" class="outer-post-container clearfix">
		<div class="vote-picture-container">
			<div class='comment-rating root-post'>
				<?php if ($post_vote_status == "disabled"): ?>
					<div class='disabled_upvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_up.png'; ?>" /></div>
					<div class="influence_gain"><?php echo $post['influence_gain'];?></div>
					<div class='disabled_downvote'><img src="<?php echo base_url() . 'assets/disabled_arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "enabled"): ?>
					<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "downvote-disabled"): ?>
					<div class='upvote'><img src="<?php echo base_url() . 'assets/arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='clicked_downvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "upvote-disabled"): ?>
					<div class='clicked_upvote'><img src="<?php echo base_url() . 'assets/clicked_arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='downvote'><img src="<?php echo base_url() . 'assets/arrow_down.png'; ?>" /></div>
				<?php endif; ?>
			</div>
			<div class="picture">
				<a href="<?php echo base_url() . $post['author']; ?>"><img src="<?php echo base_url() . $post['avatar_thumbnail']; ?>" alt="<?php echo $post['author']; ?>" /></a>
			</div>
			<div style="clear:both;"></div>
			<span class="arrow"></span>
		</div>
		<div class="inner-post-container">
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
				<img class="share-post" src="<?php echo base_url() . 'assets/new_share.png';?>" />
				<?php if ($post_save_status == "unsaved"): ?>
					<img class="save-post unclicked" src="<?php echo base_url() . 'assets/star_fav_empty.png'; ?>" />
				<?php else: ?>
					<img class="save-post clicked" src="<?php echo base_url() . 'assets/star_fav_full.png'; ?>" />
				<?php endif; ?>
			</div>
			<?php if ($post['type'] != 'small-post' && $post['type'] != 'quote' && $post['title'] != '') { ?>
				<h1><a href="<?php echo $post['url']; ?>" class="post-title"><?php echo $post['title']; ?></a></h1>
			<?php } ?>

			<?php if ($post['type'] == 'quote'):?>
				<div id="<?php echo $post['sid']; ?>" class="post-body-container">
					<div class="body quote-body">
						<?php echo $post['body']; ?>
					</div>
				</div>
			<?php else: ?>
				<div id="<?php echo $post['sid']; ?>" class="post-body-container">
					<div class="body">
						<?php echo $post['body']; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="meta-container">
				<div class="created-container left">
					<p>Created</p>
					<p class="bold-meta"><?php echo long_time_formatter($post['created']); ?></p>
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
					<p class="bold-meta"><?php echo $post['views_count'];?></p>
				</div>
				<div class="comment-amount left">
					<p>Comments</p>
					<p class="bold-meta"><?php echo $post['comments_count']; ?></p>
				</div>
				<div class="share-amount left">
					<p>Shares</p>
					<p class="bold-meta"><?php echo $post['shares_count'];?></p>
				</div>
				<div class="tag-container left">
					<p>Tags</p>
					<p class="bold-meta">
						<?php echo count($post['tags']); ?>
					</p>
				</div>
				<div class="config-container right">
				</div>
			</div>

		</div>
		
	</div>
	
	<div id="tab-container">
	
		<?php if ($tab == "comments" || $tab == "") { ?>
			<div id="tabs">
				<div id="shares-tab" class="menu-item" ><a href="/">Shares</a></div>
				<div id="tags-tab" class="menu-item" ><a href="/">Tags</a></div>
				<div id="comments-tab" class="active-menu-item" >Comments</div>
			</div>
		<?php } else if ($tab == "shares") { ?>
			<div id="tabs">
				<div id="shares-tab" class="active-menu-item" >Shares</div>
				<div id="tags-tab" class="menu-item" ><a href="/">Tags</a></div>
				<div id="comments-tab" class="menu-item" ><a href="/" >Comments</a></div>
			</div>
		<?php } else if ($tab == "tags") { ?>
			<div id="tabs">
				<div id="shares-tab" class="menu-item" ><a href="/">Shares</a></div>
				<div id="tags-tab" class="active-menu-item" >Tags</div>
				<div id="comments-tab" class="menu-item" ><a href="/">Comments</a></div>
			</div>
		<?php } ?>
	
		<div style="clear:both"></div>
	</div>
	
	<?php if ($tab == "comments" || $tab == "") { ?>
		<div id="comment-container" class="visible">
	<?php } else { ?>
		<div id="comment-container" class="hidden">
	<?php } ?>
	
		<div class="new-comment-container">
			<div class="add_comment_container">
				<div class="input_container first_input_container">
					<textarea name="new_comment_input" class="new_comment_input" rows="5" cols="50"></textarea>
				</div>
				<div class="add_comment">
					<input type="button" id="first_new_comment" class="new_comment first_new_comment" value="New Comment"/>
					<input type="hidden" id="root_comment" value="<?php echo $post['sid']; ?>" />
					<input type="hidden" id="parent_comment" value="<?php echo $post['sid']; ?>" />
					<div class="loading-gif hidden"></div>
				</div>
			</div>
		</div>
		<div class="new-comment-holder">
		</div>
		
		<?php if ($comments) { ?>
			<?php $this->load->view("comment", array("node" => $comments, "odd" => TRUE));  ?>
		<?php } else { ?>
				<p id="no-comments-yet">There are no comments yet.</p>
		<?php } ?>
		
	</div>
	
	<?php if ($tab == "shares"){ ?>
		<div id="shares-container" class="visible">
	<?php } else { ?>
		<div id="shares-container" class="hidden">
	<?php } ?>
	
		<?php if (sizeof($shares) > 0) { ?>
			<?php foreach ($shares as $share) { ?>
				
				<p class="share">Shared by <?php echo $share['author'] ?> on <?php echo $share['published'] ?></p>
				
			<?php } ?>
		<?php } else { ?>
				<p>No one has shared this post yet.</p>
		<?php } ?>
	
	
	</div>
	
	<?php if ($tab == "tags") { ?>
		<div id="tags-container" class="visible">
	<?php } else { ?>
		<div id="tags-container" class="hidden">
	<?php } ?>

	
		<?php if (sizeof($post['tags']) > 0) { ?>
			<?php if ((sizeof($post['tags']) == 1) && ($post['tags'][0] == '')) {?>
				<p>This post doesn't have any tags.</p>
				<?php } else {?>
				<?php foreach ($post['tags'] as $tag) { ?>
					
					<p class="tag"><a href="<?php echo base_url() . 't/' . $tag;?>"><?php echo $tag ?></a></p>
					
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<p>This post doesn't have any tags.</p>
		<?php } ?>

	</div>
</div>