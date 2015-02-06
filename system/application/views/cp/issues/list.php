<div id="listProjects">

	<h1>Issues</h1>
	
	<?php if ($project) : ?>
		<?php if (user_can('create', 'issues')) : ?>
			<p class="actions printhide">
				<a href="<?php echo site_url('issues/create/' . $project->id); ?>">Create new</a>
			</p>
		<?php endif; ?>
	<?php endif; ?>
			
	<p><a href="<?php echo site_url('issues/index/?show_closed=true'); ?>">Show Closed Tickets</a></p>
	
	<form action="" method="get">
	
		<div class="span-24 last">
			
			<div class="span-24 last">
				
				<?php if ($issues) : ?>
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>Date Reported</th>
								<th>Issue Title</th>
								<th>Category</th>
								<th>Priority</th>
								<th>Status</th>
								<th>Reported By</th>
								<th>Assigned To</th>
								<th></th>
							</tr>
						</thead>	
						<tfoot>
							<tr>
								<td colspan="5">
									<?php echo $pager; ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
								<?php foreach($issues as $issue) : ?>
									<tr>
										<td><?php echo $issue->id; ?></td>
										<td><?php echo date("m/d/Y", strtotime($issue->date_reported)); ?></td>
										<td><?php echo $issue->title; ?></td>
										<td><?php echo $issue->category; ?></td>
										<td><?php echo $issue->priority; ?></td>
										<td><?php echo $issue->status; ?></td>
										<td><?php echo $issue->reporter->name; ?></td>
										<td class="actions span-2">
											<?php if (user_can('view', 'issues')) : ?>
												<a class="icon view" href="<?php echo site_url('issues/view/' . $issue->id); ?>">View</a> 
											<?php endif; ?>
											
											<?php if (user_can('edit', 'issues')) : ?>
												<a class="icon edit" href="<?php echo site_url('issues/edit/' . $issue->id); ?>">Edit</a> 
											<?php endif; ?>
											
											<?php if (user_can('delete', 'issues')) : ?>
											<a class="icon delete popup" href="<?php echo site_url('issues/delete/' . $issue->id); ?>">Delete</a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>				
			
				<?php else : ?>
					<p>No issues listed.</p>
				<?php endif; ?>

			</div>
					
		</div>
	
	</form>
	
</div>