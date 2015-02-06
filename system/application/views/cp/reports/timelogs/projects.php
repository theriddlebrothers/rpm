<div id="report">

	<h1>Project Time Logs</h1>
	
	<h3>Timelogs Between <?php echo $this->input->get('start_date'); ?> and <?php echo $this->input->get('end_date'); ?></h3>
		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php foreach($errors as $error) : ?>
  				<?php echo $error->string; ?>
  			<?php endforeach; ?>
  		</div>
  	<?php endif; ?>
	<?php if (sizeof($hours) > 0) : ?>
		
		<form action="/cp/reports/timelogs_projects/" method="get">
			<input type="hidden" name="search" value="1" />
			<div class="span-12 prepend-12 last">
				<div class="span-1">
					Start: 
				</div>
				<div class="span-4">
					<input type="text" class="datepicker" name="start_date" style="margin:0;" />
				</div>
				<div class="span-1">
					End: 
				</div>
				<div class="span-4">
					<input type="text" class="datepicker" name="end_date" style="margin:0;" />
				</div>
				<div class="span-2 last">
					<button type="submit" class="button secondary" name="filter">Apply</button>
				</div>
			</div>
		</form>

		<form action="" method="post">
			<div class="span-24 last">
				<p>						
					<button type="submit" class="button primary" name="submit">Create Invoices</button>
				</p>
			</div>

			<div class="span-24 last">
				<table id="timelogsTable">
					<thead>
						<tr>
							<th><input type="checkbox" id="send" /></th>
							<th>Project Number</th>
							<th>Project Name</th>
							<th class="alignright">Total Time</th>
							<th class="money">Billable Amount</th>
							<th class="span-1"></th>
						</tr>
					</thead>
					
					<tbody>
					
						<?php foreach($projects as $project) : ?>
							<?php if (isset($hours[$project->id])) : ?>
								<tr>
									<td><input type="checkbox" name="send[]" value="<?php echo $project->id; ?>" /></td>
									<td><?php echo $project->fullProjectNumber(); ?></td>
									<td><a href="<?php echo site_url('projects/view/' . $project->id); ?>"><?php echo $project->name; ?></a></td>
									<td class="alignright"><?php echo $timelog->convertHoursToTime($hours[$project->id]); ?></td>
									<td class="money">$<?php echo number_format($project->billable_rate * $hours[$project->id], 2); ?></td>
									<td>
										<?php if (user_can('view', 'timelogs')) : ?>
											<a class="icon view" href="<?php echo site_url('reports/timelogs/?project=' . $project->id . $filter); ?>">View Logs</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
						
					</tbody>
				
				</table>
			</div>
		</form>
	<?php else : ?>
		<p>No time logs to list.</p>	
	<?php endif; ?>
</div>

<script type="text/javascript">
	$(function() {
		$('#send').click(function() {
			var checked = $(this).attr("checked");
			$('#timelogsTable tbody input').each(function() {
				$(this).attr("checked", checked);
			});
		});
	});
</script>