<?php if ($u = Current_User::user()) { ?>

	<?php $this->load->helper('time_format_helper'); ?>

	<?php if ($odd == 'true') {
		$odd_even = "odd-comment";
	} else {
		$odd_even = "even-comment";
	}?>

	<?php foreach ($node->children as $child) { ?>

		<div id='<?php echo $child->comment['sid']?>' class='outer-comment-container <?php echo $odd_even; ?>'>
			<div class='comment-rating'>
			<?php if ($u['username'] == $child->comment['author']) { ?>
				<div class='disabled_upvote'><img src="<?php echo base_url() . 'images/disabled_arrow_up.png'; ?>" /></div>
				<div class='disabled_downvote'><img src="<?php echo base_url() . 'images/disabled_arrow_down.png'; ?>" /></div>
			<?php } else { ?>
				<?php if ($child->vote) { ?>
				
					<?php if($child->vote['type'] == 'upvote') { ?>
						<div class='clicked_upvote'><img src="<?php echo base_url() . 'images/clicked_arrow_up.png'; ?>" /></div>
						<div class='downvote'><img src="<?php echo base_url() . 'images/arrow_down.png'; ?>" /></div>
					<?php } else { ?>
						<div class='upvote'><img src="<?php echo base_url() . 'images/arrow_up.png'; ?>" /></div>
						<div class='clicked_downvote'><img src="<?php echo base_url() . 'images/clicked_arrow_down.png'; ?>" /></div>
					<?php } ?>
				
				<?php } else { ?>
					<div class='upvote'><img src="<?php echo base_url() . 'images/arrow_up.png'; ?>" /></div>
					<div class='downvote'><img src="<?php echo base_url() . 'images/arrow_down.png'; ?>" /></div>
				<?php } ?>
			<?php } ?>
			</div>
			<div class='profile-pic'>
				<img src="<?php echo base_url() . $child->comment['avatar_thumbnail']; ?>" />
			</div>
			<div class='inner-comment-container'>
				<div class='comment-body-container'>
					<p class='comment_author'><a href='<?php echo base_url() . $child->comment['author'] ?>'><?php echo $child->comment['author'] ?></a> - <?php echo $child->comment['influence_gain']; ?> influence - posted <a class="create_date" href="<?php echo $child->comment['url'];?>" title="<?php echo date('g:i A jS M Y', $child->comment['created']);?>"><?php echo long_time_formatter($child->comment['created']); ?></a></p>
					<div class='comment_body'>
						<?php echo $child->comment['body'] ?>
					</div>
						<div class='add_comment_container'>
							<div class='input_container'>
								<textarea name="<?php echo $child->comment['sid'];?>-input" class='new_comment_input' rows='5' cols='50'></textarea>
								<div class='add_comment'>
									<input type='button' class='new_comment' value='Save'/>
									<div class="loading-gif hidden"></div>
								</div>
							</div>
							<div class='reply'><a href='/'>reply</a></div>
						</div>
				</div>
				<div class="new-comment-holder">
				</div>
				<?php $this->load->view("comment", array("node" => $child, "odd" => !$odd)); ?>
			</div>
		</div>
	
	<?php } ?>

<?php } else { ?>

	<?php foreach ($node->children as $child) { ?>
	
		<div id='<?php echo $child->comment['sid'] ?>' class='outer-comment-container <?php echo $odd_even; ?>'>
			<div class='comment-rating'>
			</div>
			<div class='inner-comment-container'>
				<div class='comment-body-container'>
					<p class='comment_author'><a href='<?php echo base_url() . $child->comment['author'] ?>'><?php echo $child->comment['author'] ?></a> - <?php echo $child->comment['influence_gain']; ?> influence - posted <a class="create_date" href="<?php echo $child->comment['url'];?>" title="<?php echo date('g:i A jS M Y', $child->comment['created']);?>"><?php echo long_time_formatter($child->comment['created']); ?></a></p>
					<div class='comment_body'>
						<?php echo $child->comment['body'] ?>
					</div>
					<div class='add_comment_container'>
						<div class='input_container'>
							<textarea class='new_comment_input' rows='5' cols='50'></textarea>
							<div class='add_comment'><input type='button' class='new_comment' value='Save'/></div>
							<div class="loading-gif hidden"></div>
						</div>
						<div class='reply'><a href='/'>reply</a></div>
					</div>
				</div>
				<div class="new-comment-holder">
				</div>
				<?php $this->load->view("comment", array("node" => $child, "odd" => !$odd)); ?>
			</div>
		</div>
	
	<?php } ?>

<?php } ?>