<?php $u = Current_User::user(); ?>

<div id="post_container">
	<div class="outer-post-container">
		<div class="picture">
			<a href="<?php echo base_url() . $post['author']; ?>"><img src="<?php echo base_url() . $post['profile_pic']; ?>" alt="<?php echo $post['author']; ?>" /></a>
		</div>
		<div class="inner-post-container">		
			<?php if ($post['title'] != '') { ?>
				<h2><a href="<?php echo $post['url']; ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
			<?php } ?>

			<div id="<?php echo $post['sid']; ?>"class="post-container-1">
				<div class="post-body-container">
					<div class="body">
						<?php echo $post['body']; ?>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		
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