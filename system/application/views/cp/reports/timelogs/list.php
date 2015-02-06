<div id="report">

	<?php $this->load->view('cp/reports/timelogs/filter'); ?>
	
	<div class="span-24 last">
		
		<?php if ($timelogs && count($timelogs->all) > 0) : ?>
			<table>
				<thead>
					<tr>
						<th class="span-2">Log Date</th>
						<th>Task</th>
						<th>Description</th>
						<th class="span-4">User</th>
						<th class="span-2">Time</th>
						<th class="span-2">Amount</th>
						<th class="span-2"></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="total"><?php echo $total_time; ?></td>
						<td></td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($timelogs as $timelog) : ?>
							<tr>
								<td><?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></td>
								<td><?php echo truncate($timelog->task->name, 40); ?></td>
								<td><?php echo truncate($timelog->description, 40); ?></td>
								<td><?php echo $timelog->user->name; ?></td>
								<td><?php echo $timelog->hours; ?></td>
								<td>$<?php echo number_format($timelog->convertTimeToHours($timelog->hours) * $timelog->task->project->billable_rate, 2); ?></td>
								<td class="actions span-2">
									<?php if (user_can('view', 'timelogs')) : ?>
										<a class="icon view" href="<?php echo site_url('timelogs/view/' . $timelog->id); ?>">View</a>
									<?php endif; ?>
									<?php if (user_can('edit', 'timelogs')) : ?>
										<a class="icon edit" href="<?php echo site_url('timelogs/edit/' . $timelog->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'timelogs')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('timelogs/delete/' . $timelog->id); ?>">Delete</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<?php if ($this->input->get('search')) : ?>
				<p>No time logs listed.</p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	
</div>