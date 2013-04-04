<div class="content-container">
	<div class="tag-header">
		<div class="left-side">
			<h1>Tags</h1>
		</div>
		<div class="right-side">
			<?php if($args['tab'] == 'created'):?>
				<p class="right-tab selected">New Tags</p>
			<?php else:?>
				<p class="right-tab unselected"><a href="<?php echo base_url() . 'tags?tab=created'; ?>">New Tags</a></p>
			<?php endif;?>
			<?php if($args['tab'] == 'influence'):?>
				<p class="right-tab selected">Influence</p>
			<?php else:?>
				<p class="right-tab unselected"><a href="<?php echo base_url() . 'tags?tab=influence'; ?>">Influence</a></p>
			<?php endif;?>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="tag-outer-container">
		<div class="tag-filter-container">
			<p class="search-label">Search for a tag:</p>
			<input type="text" class="tag-search-input" />
			<div style="clear:both"></div>
		</div>
		<div class="tags-inner-container">
			<div class="tag-column">
			<?php $count = 0; ?>
			<?php foreach ($tags as $tag): ?>

				<?php if ($count == 7 || $count == 14 || $count == 21 || $count == 28): ?>
					</div>
					<div class="tag-column">
				<?php endif; ?>
				<div class="tag-container">
					<div class="info">
						<p class="tag"><a href="<?php echo base_url() . 't/' . $tag['name']; ?>"><?php echo $tag['name']; ?></a></p>
						<p><?php echo $tag['count']; ?> tagged.</p>
					</div>
					<div style="clear:both"></div>
				</div>
				<?php $count++; ?>
			<?php endforeach; ?>
			</div>
		</div>
		<div class="tag-pages-container">
			<div class="inner-pages-container">
				<?php foreach($pagination_pages as $button) echo $button; ?>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>