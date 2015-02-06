<form action="" method="get" class="printhide">
	
	<fieldset>

		<legend>Filter</legend>
	
		<div class="span-24 last">
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
		
		<div class="span-24 last">
			
			<div class="span-4">
				<button type="submit" class="button" name="submit">
					<img src="/images/icons/find.png" alt=""/> Search
				</button>
				<input type="hidden" name="search" value="1" />
			</div>
			
		</div>
	
		
	</fieldset>
	
</form>



<script type="text/javascript">
$(function() {
	
	$('#company').autocomplete('/cp/companies/ajax/search');

});
</script>