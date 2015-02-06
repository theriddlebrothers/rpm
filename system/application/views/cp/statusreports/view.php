<div id="invoice">

	<h1>Status Report</h1>	
	
	<?php if (user_can('edit', 'statusreports')) : ?>
		<p><a href="<?php echo site_url('statusreports/edit/' . $report->id); ?>">Edit</a></p>
	<?php endif; ?>	
	
	<table>
		<tr>
			<th>Date</th>
			<td><?php echo date("m/d/Y", strtotime($report->report_date)); ?></td>
		</tr>
		<tr>
			<th>Project</th>
			<td><?php echo $report->project->name; ?></td>
		</tr>
	</table>
	
	<div class="markdown span-24">
		<?php echo $report->content; ?>
	</div>
	
</div>
