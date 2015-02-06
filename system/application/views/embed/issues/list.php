<div id="listProjects">

	<h1>Issues</h1>
	
	<form action="" method="get">
			
		<div class="span-20 last">
			
			<?php if ($issues) : ?>
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Date Reported</th>
							<th>Issue Title</th>
							<th>Category</th>
							<th>Priority</th>
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
									<td class="actions span-2">
										<?php if (user_can('view', 'issues')) : ?>
											<a class="icon view" href="<?php echo site_url('issues/view/' . $issue->id); ?>/?reporter_id=<?php echo $reporter->id; ?>&key=<?php echo $view_key; ?>">View</a> 
										<?php endif; ?>
										
										<?php if (user_can('edit', 'issues')) : ?>
											<a class="icon edit" href="<?php echo site_url('issues/edit/' . $issue->id); ?>/?reporter_id=<?php echo $reporter->id; ?>&key=<?php echo $view_key; ?>">Edit</a> 
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
			
	</form>
	
</div>