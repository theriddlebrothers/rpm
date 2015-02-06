<div id="listStatusReports">

	<h1>Status Reports</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'statusreports')) : ?>
			<p><a href="<?php echo site_url('statusreports/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($reports) : ?>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Report Date</th>
						<th>Project</th>
						<th></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="3">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($reports as $report) : ?>
							<tr>
								<td><?php echo $report->id; ?></td>
								<td><?php echo date("m/d/Y", strtotime($report->report_date)); ?></td>
								<td><?php echo $report->project->name; ?></td>
								<td class="actions span-2">
									<?php if (user_can('view', 'statusreports')) : ?>
										<a class="icon view" href="<?php echo site_url('statusreports/view/' . $report->id); ?>">View</a> 
									<?php endif; ?>
									<?php if (user_can('edit', 'statusreports')) : ?>
										<a class="icon edit" href="<?php echo site_url('statusreports/edit/' . $report->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'statusreports')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('statusreports/delete/' . $report->id); ?>">Delete</a>								
									<?php endif; ?>	
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No projects listed.</p>
		<?php endif; ?>

	</div>
	
</div>