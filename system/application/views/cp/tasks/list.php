<div id="listTasks">

	<h1>Tasks</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'tasks')) : ?>
			<p><a href="<?php echo site_url('tasks/create' . ($this->input->get('project') ? '?project=' . $this->input->get('project') : '')); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($tasks) : ?>
			<table>
				<thead>
					<tr>
						<th class="span-2">Task Date</th>
						<th>Task Name</th>
						<th>Project</th>
						<th class="span-4">Assigned To</th>
						<th class="span-2"></th>
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
					<?php foreach($tasks as $task) : ?>
						<tr>
							<td><?php echo date("m/d/Y", strtotime($task->created_date)); ?></td>
							<td><?php echo truncate($task->name, 50); ?></td>
							<td><?php echo truncate($task->project->name, 50); ?></td>
							<td><?php echo $task->user->name; ?></td>
							<td class="actions span-2">
								<?php if (user_can('view', 'tasks')) : ?>
									<a class="icon view" href="<?php echo site_url('tasks/view/' . $task->id); ?>">View</a>
								<?php endif; ?>
								<?php if (user_can('edit', 'tasks')) : ?>
									<a class="icon edit" href="<?php echo site_url('tasks/edit/' . $task->id); ?>">Edit</a>
								<?php endif; ?>
								<?php if (user_can('delete', 'tasks')) : ?>
									<a class="icon delete popup" href="<?php echo site_url('tasks/delete/' . $task->id); ?>">Delete</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No tasks listed.</p>
		<?php endif; ?>

	</div>
	
</div>