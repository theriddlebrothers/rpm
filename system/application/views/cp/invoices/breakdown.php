<?php if (isset($timelogs) && sizeof($timelogs) > 0) : ?>

<div id="invoice-breakdown" class="span-24 last">
	<fieldset>
		<legend>Invoice Breakdown</legend>
		
		<table>
			<thead>
				<tr>
					<th class="span-2">Log Date</th>
					<th>Task</th>
					<th>Description</th>
					<th class="span-2">Time</th>
					<th>Amount</th>
					<th class="printhide span-2"></th>
				</tr>
			</thead>	
			<tbody>
				<?php $total_time = 0; $total_cost = 0; ?>
				<?php foreach($timelogs as $timelog) : ?>
					<?php
						$total_time += $timelog->convertTimeToHours($timelog->hours);
						$total_cost += $timelog->convertTimeToHours($timelog->hours) * $timelog->task->project->billable_rate;
					?>
					<tr>
						<td><?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></td>
						<td><?php echo truncate($timelog->task->name, 500); ?></td>
						<td><?php echo truncate($timelog->description, 500); ?></td>
						<td><?php echo $timelog->hours; ?></td>
						<td>$<?php echo number_format($timelog->task->project->billable_rate * doubleval($timelog->convertTimeToHours($timelog->hours)), 2); ?></td>
						<td class="printhide">
							<?php if (user_can('view', 'timelogs')) : ?>
								<a class="icon view" href="<?php echo site_url('timelogs/view/' . $timelog->id); ?>">View</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td class="total"><?php echo Timelog::convertHoursToTime($total_time); ?></td>
					<td class="total">$<?php echo number_format($total_cost, 2); ?></td>
					<td class="printhide"></td>
				</tr>
			</tfoot>
		</table>	
		
	</fieldset>
</div>

<?php endif; ?>

