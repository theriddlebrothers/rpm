<!-- begin select task -->
<p>
	<label>Task</label><br />
	<input name="task_name" class="text select-task" type="text" value="<?php echo $task->name; ?>" /> 
	<a href="#" class="icon inline find select-task">Find</a>
	<input type="hidden" name="task" value="<?php echo $task->id; ?>" />
</p>

<div style="display:none;">
	<div class="task-list dialog" title="Select a Task">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-task')); ?>
		<?php if ($tasks) : ?>
			<div class="list-wrap">
				<table>
					<thead>
						<tr>
							<th>Task Name</th>
							<th>Project</th>
							<th>Company</th>
						</tr>
					</thead>
					<?php foreach($tasks as $t) : ?>
						<tr>
							<td>
								<a class="select" title="<?php echo $t->name; ?>" href="#<?php echo $t->id; ?>">
									<?php echo $t->name; ?>
								</a>
							</td>
							<td>
								<?php echo $t->project->name; ?>
							</td>
							<td>
								<?php echo $t->project->company->name; ?>
							</td>
						</tr>
					<?php endforeach; ?>	
				</table>	
			</div>
		<?php else : ?>
			<p>No tasks found.</p>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		// display dialog
		$('.select-task').click(function() {
			$('.task-list').dialog( {
					buttons: {
						"Cancel" : function() {
							$(this).dialog("close"); 
						}
				},
				modal : true,
				resizable: false,
				width : '700px'
			});
			
			return false;
		});
			
		// selection of task
		$('.select', '.task-list').click(function() {
			var id = $(this).attr("href").replace("#", "");
			var name = $(this).attr("title");
			$('input[name="task_name"]').val(name);
			$('input[name="task"]').val(id);
			$('.task-list').dialog("close");
			return false;
		});
			
		// prevent manual company input
		$('.select-task').focus(function(){
		    this.blur();
		});
	});
</script>
<!-- end select company -->