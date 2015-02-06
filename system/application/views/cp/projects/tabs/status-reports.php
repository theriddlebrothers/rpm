<?php if (user_can('view', 'statusreports')) : ?>
	<div id="tab-status-reports">
			
		<?php if ($status_reports) : ?>
	
			<table>
				<thead>
					<tr>
						<th>Report Date</th>
						<th>Content</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<?php foreach($status_reports as $report) : ?>
					<tr>
						<td><?php echo date("m/d/Y", strtotime($report->report_date)); ?></td>
						<td><?php echo truncate($report->content, 40); ?></td>
						<td>
							<?php if (user_can('view', 'statusreports')) : ?>
								<a class="icon view" href="<?php echo site_url('statusreports/view/' . $report->id); ?>">View</a>
							<?php endif; ?>
							
							<?php if (user_can('edit', 'statusreports')) : ?>
								<a class="icon edit" href="<?php echo site_url('statusreports/edit/' . $report->id); ?>">Edit</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
		<?php else : ?>
			<p>No status reports listed.</p>
		<?php endif; ?>
		
		
		<p class="actions">
			<?php if (user_can('view', 'statusreports')) : ?>
				<a href="<?php echo site_url('statusreports/index/?project=' . $project->id); ?>">View All</a>
			<?php endif; ?>
			
			<?php if (user_can('create', 'statusreports')) : ?>
				<a href="<?php echo site_url('statusreports/create/?project=' . $project->id); ?>">Create new</a>
			<?php endif; ?>
		</p>
			
	</div>
<?php endif; ?>