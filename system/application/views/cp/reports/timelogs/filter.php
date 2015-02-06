<div class="span-19 last">
	
	<form action="" id="filters" method="get" class="printhide">
		
		<fieldset>
	
			<legend>Filter</legend>
		
			<div class="span-18 last">
				
				<div class="span-9">
					<?php echo dialog_select_company($company); ?>
				</div>
				
				<div class="span-9 last">
					<?php echo dialog_select_user($user); ?>
				</div>
			</div>
			
			<div class="span-18 last">
				
				<div class="span-9">
					<?php echo dialog_select_project($project); ?>
				</div>
				
				<div class="span-9 last">
					<?php echo dialog_select_task($task); ?>
				</div>
				
			</div>
		
			
			<div class="span-18 last">
					
				<div class="span-4">
					<p>
						<label>Start Date</label><br />
						<input class="span-3 datepicker text" id="start_date" name="start_date" value="<?php echo urldecode($this->input->get('start_date')); ?>"/>
					</p>
				</div>
				
				<div class="span-4">
					<p>
						<label>End Date</label><br />
						<input class="span-3 datepicker text" id="end_date" name="end_date" value="<?php echo urldecode($this->input->get('end_date')); ?>" />
					</p>
				</div>
			
			</div>
			
		
			<button id="filter-apply" type="submit" class="button button-small secondary" name="submit">Search</button>
			<input type="hidden" name="search" value="1" />
			
		</fieldset>
		
	</form>

</div>


<script type="text/javascript">
$(function() {
	
	$('#company').autocomplete('/cp/companies/ajax/search');

});
</script>