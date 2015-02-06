<div id="timelogPanel">
	<div class="panel">
		
		<form action="<?php echo site_url('timelogs/create'); ?>" method="post">

			<fieldset>
				
				<legend>Add Timelog</legend>
	
				<div class="span-5">
					<p>
						<label>Log Date</label><br />
						<input style="width:100px;" name="log_date" type="text" class="datepicker text" value="<?php echo date("m/d/Y"); ?>" />
					</p>
				</div>
				
				<div class="span-5 last">
					<p>
						<label>Time (Hr:Min)</label><br />
						<input style="width:50px;" name="hours" type="text" class="number text" value="" /><br />
					</p>
				</div>
						
				<div class="span-10 last">
					<p>
						<label>Task</label><br />
						<input type="text" class="taskSearch text" value="" /><br />
						<span class="quiet">Type a project or task name.</span>
						<input id="timelogPanelTask" name="task_id" type="hidden" value="" />
					</p>
				</div>
				
				<div class="span-10 last">
					<label>Description</label><br />
					
					<p>
						<textarea id="quickDescription" name="description" class="text"></textarea>
					</p>

				</div>				
				<input type="hidden" name="user" value="<?php echo $this->session->userdata('user_id'); ?>" />
					
				<button type="submit" class="button primary" name="submit">
					Save
				</button>
					
			</fieldset>
		</form>
		
		<div style="clear:both;"></div>
	
	</div>
	<a class="trigger" href="#"><img src="/images/icons/time_add.png" alt="Time log" /></a>
</div>


<script type="text/javascript">
	$(function() {
		
		// open panel
		$("#timelogPanel .trigger").click(function(){
			$("#timelogPanel .panel").toggle("fast");
			$(this).toggleClass("active");
			return false;
		})
		
		// search tasks
		$('#timelogPanel .taskSearch').autocomplete('/cp/tasks/ajax/search/').result(function(event, data, formatted) {
			var hidden = $('#timelogPanelTask');
			hidden.val(data[1]);
		});

	});
</script>