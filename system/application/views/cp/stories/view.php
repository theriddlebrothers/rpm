<div id="viewStories">

	<h1><?php echo $project->name; ?>: User Stories</h1>
	
	<?php if (user_can('edit', 'stories')) : ?>
		<p><a href="<?php echo site_url('stories/index/' . $project->id); ?>">Edit</a></p>
	<?php endif; ?>
			
	<div class="span-24 last">
				
		<div id="message" style="display:none;" class="notice"></div>
		
		<?php foreach($project->stories as $story) : ?>
			<div class="story span-23 last">
				<p><?php echo $story->description; ?></p>
				<div class="span-4">
					<label>ID:</label> <?php echo $story->id; ?><br />
					<label>Status:</label> <?php echo $story->status; ?><br />
					<label>Priority:</label> <?php echo $story->priority; ?><br />
					<label>Effort:</label> <?php echo $story->effort; ?>
				</div>
				<div class="span-16 last">
					<?php if ($story->notes) : ?>
						<label>Notes:</label> <?php echo $story->notes; ?>
					<?php endif; ?>
				</div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
</div>
