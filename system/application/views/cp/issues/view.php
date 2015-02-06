<div id="issue">

	<h1>Issue: <?php echo $issue->title; ?></h1>	
	
	<?php if (user_can('edit', 'issues')) : ?>
		<p><a href="<?php echo site_url('issues/edit/' . $issue->id); ?>">Edit</a></p>	
	<?php endif; ?>
	
	<div class="span-16">
		<fieldset>
			<legend>Issue Details</legend>
			
			<table>
				<tr>
					<th class="span-4">Issue ID</th>
					<td><?php echo $issue->id; ?></td>
				</tr>
				<tr>
					<th class="span-4">Title</th>
					<td><?php echo $issue->title; ?></td>
				</tr>
				<tr>
					<th>Category</th>
					<td><?php echo $issue->category; ?></td>
				</tr>
				<tr>
					<th>Priority</th>
					<td><?php echo $issue->priority; ?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td><?php echo $issue->status; ?></td>
				</tr>
				<tr>
					<th>Component/Feature</th>
					<td><?php echo $issue->component; ?></td>
				</tr>
				<tr>
					<th>Description</th>
					<td>
						<?php echo markdownify($issue->description); ?>
					</td>
				</tr>
				<tr>
					<th>Steps to Reproduce</th>
					<td><?php echo markdownify($issue->steps); ?></td>
				</tr>
				<!--<tr>
					<th>Attachment</th>
					<td>
						<?php if ($issue->attachment) : ?>
							<a href="#">View Attachment</a>
						<?php else : ?>
							<em>No files attached.</em>
						<?php endif; ?>
					</td>
				</tr>-->
			</table>
		</fieldset>	
	</div>
	
	<div class="span-8 last">
		<fieldset>
			<legend>User Info</legend>
			
			<table>
				<tr>
					<th>Reported By</th>
					<td><?php echo $issue->reporter->name; ?></td>
				</tr>	
				<tr>
					<th>Assiged To</th>
					<td>
						<?php if ($issue->assignee->exists()) echo $issue->assignee->name; ?>
					</td>
				</tr>	
				<tr>
					<th>Date Reported</th>
					<td><?php echo date("m/d/Y", strtotime($issue->date_reported)); ?></td>
				</tr>	
				<tr>
					<th>Date Due</th>
					<td>
						<?php if ($issue->date_due) : ?>
							<?php echo date("m/d/Y", strtotime($issue->date_due)); ?>
						<?php endif; ?>
					</td>
				</tr>	
				<tr>
					<th>Browser</th>
					<td>
						<?php if ($issue->browser) : ?>
							<?php echo $issue->browser; ?>
						<?php endif; ?>
					</td>
				</tr>	
				<tr>
					<th>Operating System</th>
					<td>
						<?php if ($issue->operating_system) : ?>
							<?php echo $issue->operating_system; ?>
						<?php endif; ?>
					</td>
				</tr>	
				<?php if ($current_user->role != ROLE_CLIENT) : ?>
					<tr>
						<th>Visibility</th>
						<td>
							<?php if ($issue->visibility) : ?>
								<?php echo $issue->visibility; ?>
							<?php endif; ?>
						</td>
					</tr>	
				<?php endif; ?>
			</table>
		
		</fieldset>	
	</div>
	
		
	<div class="span-24 last">
	
		<h2>Comments</h2>
	
		<?php if ($comments) : ?>
		
		<?php
			$side = 'left'; 
			for($i=0; $i<count($comments); $i++) : 
				// if same user made a comment again don't change the side
				if (($i > 0) && ($comments[$i-1]->user->id != $comments[$i]->user->id)) {
					if ($side == 'left') $side = 'right';
					else $side = 'left';
				}
				$comment = $comments[$i];
		?>
			
			<a id="comment-<?php echo $comment->id; ?>" name="comment-<?php echo $comment->id; ?>"></a>
			
			<div class="triangle-border <?php echo $side; ?>">
				<p><strong class="username"><?php echo $comment->user->name; ?></strong> <span class="date"><?php echo date("m/d/Y @ h:ia", strtotime($comment->comment_date)); ?></span></p>
				
				<?php echo markdownify($comment->comment); ?>
				
			</div>
		
		<?php endfor; ?>
		<?php else : ?>
			<p>No comments have been posted.</p>
		<?php endif; ?>
	</div>
	
	<div class="span-24 last">
		
		<form action="" method="post">
			<p><textarea name="comment" id="comment" class="span-23 last text"></textarea></p>
				
                        <p class="clear">
                            <label><?php echo form_checkbox('notify', 1, true); ?> Send an email notification to users alerting them of this issue.</label>
                        </p>

			<p><button type="submit" class="button primary" value="1" name="submit">Add Comment</button></p>
		</form>
		
	</div>

</div>
