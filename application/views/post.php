<?php $u = Current_User::user(); ?>

<?php $this->load->helper('time_format_helper'); ?>

<div id="post_container">
	<div class="outer-post-container clearfix">
		<div class="vote-picture-container">
			<div class='comment-rating'>
				<?php if ($post_vote_status == "disabled" || $post_vote_status == "upvote-disabled"): ?>
					<div class='disabled_upvote'><img src="<?php echo base_url() . 'images/disabled_arrow_up.png'; ?>" /></div>
					<div class="influence_gain"><?php echo $post['influence_gain'];?></div>
					<div class='disabled_downvote'><img src="<?php echo base_url() . 'images/disabled_arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "enabled"): ?>
					<div class='upvote'><img src="<?php echo base_url() . 'images/arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='downvote'><img src="<?php echo base_url() . 'images/arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "downvote-disabled"): ?>
					<div class='upvote'><img src="<?php echo base_url() . 'images/arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='clicked_downvote'><img src="<?php echo base_url() . 'images/clicked_arrow_down.png'; ?>" /></div>
				<?php elseif($post_vote_status == "upvote-disabled"): ?>
					<div class='clicked_upvote'><img src="<?php echo base_url() . 'images/clicked_arrow_up.png'; ?>" /></div>
					<div class='influence_gain'><?php echo $post['influence_gain'];?></div>
					<div class='downvote'><img src="<?php echo base_url() . 'images/arrow_down.png'; ?>" /></div>
				<?php endif; ?>
			</div>
			<div class="picture">
				<a href="<?php echo base_url() . $post['author']; ?>"><img src="<?php echo base_url() . $post['profile_pic']; ?>" alt="<?php echo $post['author']; ?>" /></a>
			</div>
			<div style="clear:both;"></div>
			<span class="arrow"></span>
			<p class="author"><?php echo $post['author'] ?></p>
		</div>
		<div class="inner-post-container">		
			<?php if ($post['type'] != 'small-post' && $post['title'] != '') { ?>
				<h1><a href="<?php echo $post['url']; ?>" class="post-title"><?php echo $post['title']; ?></a></h1>
			<?php } ?>

			<div id="<?php echo $post['sid']; ?>" class="post-body-container">
				<div class="body">
					<?php echo $post['body']; ?>
				</div>
			</div>

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
						<?php if (count($post['tags']) > 0){ 
							foreach ($post['tags'] as $tag) { 
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
				<div class="input_container">
					<textarea name="new_comment_input" class="new_comment_input" rows="5" cols="50"></textarea>
				</div>
				<div class="add_comment">
					<input type="button" id="first_new_comment" class="new_comment" value="New Comment"/>
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
					
					<p class="tag"><?php echo $tag ?></p>
					
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<p>This post doesn't have any tags.</p>
		<?php } ?>

	</div>
</div>