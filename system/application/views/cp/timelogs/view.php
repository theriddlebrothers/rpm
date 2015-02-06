<div id="timelog">

	<h1>Timelog from <?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></h1>
		
	<div class="span-24 last">
	
		<table>
			<tr>
				<th>Project</th>
				<td><?php echo $timelog->task->project->name; ?></td>
			</tr>
			<tr>
				<th>Task Name</th>
				<td><?php echo $timelog->task->name; ?></td>
			</tr>
			<tr>
				<th>Hours</th>
				<td><?php echo $timelog->hours; ?></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><?php echo $timelog->description; ?></td>
			</tr>
			<tr>
				<th>User</th>
				<td><?php echo $timelog->user->name; ?></td>
			</tr>
		</table>

	</div>

</div>
