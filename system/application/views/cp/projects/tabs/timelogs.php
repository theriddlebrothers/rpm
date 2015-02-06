<?php if (user_can('view', 'timelogs')) : ?>
				
	<div id="tab-timelogs">

		<?php if ($timelogs) : ?>
		
			<table>
				<thead>
					<tr>
						<th class="span-2">Date</th>
						<th class="span-3">User</th>
						<th>Description</th>
						<th class="span-2">Time (h:m)</th>
						<th class="span-1"></th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<?php foreach($timelogs as $timelog) : ?>
						<tr>
							<td><?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></td>
							<td><?php echo $timelog->user->name; ?></td>
							<td><?php echo $timelog->description; ?></td>
							<td><?php echo $timelog->hours; ?></td>
							<td><a class="icon view" href="<?php echo site_url('timelogs/edit/' . $timelog->id); ?>">View</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		<?php else : ?>
			<p>No time logs to list.</p>
		<?php endif; ?>
	
		<?php if (user_can('view', 'timelogs')) : ?>
			<p><a href="<?php echo site_url('timelogs/index/?project=' . $project->id); ?>">View All</a></p>
		<?php endif; ?>
	
	</div>
	
<?php endif; ?>	