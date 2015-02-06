<?php if (user_can('view', 'issues')) : ?>


	<div id="tab-issues">
			
		<?php if ($issues) : ?>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Date Reported</th>
						<th>Issue Title</th>
						<th>Category</th>
						<th>Priority</th>
						<th>Reported By</th>
						<th>Assigned To</th>
						<th></th>
					</tr>
				</thead>	
				<tbody>
						<?php foreach($issues as $issue) : ?>
							<tr>
								<td><?php echo $issue->id; ?></td>
								<td><?php echo date("m/d/Y", strtotime($issue->date_reported)); ?></td>
								<td><?php echo $issue->title; ?></td>
								<td><?php echo $issue->category; ?></td>
								<td><?php echo $issue->priority; ?></td>
								<td><?php echo $issue->reporter->name; ?></td>
								<td><?php echo $issue->assignee->name; ?></td>
								<td class="actions span-2">
									<?php if (user_can('view', 'issues')) : ?>
										<a class="icon view" href="<?php echo site_url('issues/view/' . $issue->id); ?>">View</a> 
									<?php endif; ?>
									
									<?php if (user_can('edit', 'issues')) : ?>
										<a class="icon edit" href="<?php echo site_url('issues/edit/' . $issue->id); ?>">Edit</a> 
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No issues listed.</p>
		<?php endif; ?>
		
		<p class="actions">
			<?php if (user_can('view', 'issues')) : ?>
				<a href="<?php echo site_url('issues/index/' . $project->id); ?>">View All</a>
			<?php endif; ?>
			
			<?php if (user_can('create', 'issues')) : ?>
				<a href="<?php echo site_url('issues/create/' . $project->id); ?>">Create new</a>
			<?php endif; ?>
		</p>
			
		
	</div>
	
<?php endif; ?>