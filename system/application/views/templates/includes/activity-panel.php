<div id="activityPanel">
	<div class="panel">
		
		<form action="<?php echo site_url('activities/create'); ?>" method="post">

			<fieldset>
				
				<legend>Add Activity</legend>
	
				<div class="span-9">
				<p>
					<label>Subject</label><br />
					<input name="subject" type="text" class="text" value="" />
				</p>
				
				<p>
					<label>Company</label><br />
					<input style="width:330px;" id="activityPanelCompany" name="company" type="text" class="text" value="" />
				</p>
				
				<p>
					<label>Activity Type</label><br />
					<select name="activity_type">
						<option selected="selected" value="">Select a type...</option>
						<option value="Email">Email</option>
						<option value="Phone Call">Phone Call</option>
						<option value="Meeting">Meeting</option>
						<option value="Other">Other</option>
					</select>
				</p>
				
				<p>
					<label>Activity Date/Time</label><br />
					<input name="activity_date" type="text" class="datetimepicker text" value="" />
				</p>
				
				<p>
					<label>Description</label><br />
					<textarea name="description" class="text span-9"></textarea><br />
					<span class="quiet">Activity description content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
					
				</p>
							
				<div class="span-9 last">
					
					<button id="activityPanelGo" type="submit" class="button primary" name="submit">
						Save
					</button>
				</div>
			</fieldset>
		</form>
		
		<div style="clear:both;"></div>
	
	</div>
	<a class="trigger" href="#"><img src="/images/icons/book_add.png" alt="Add Activity" /></a>
</div>


<script type="text/javascript">
	$(function() {
		
		// open panel
		$("#activityPanel .trigger").click(function(){
			$("#activityPanel .panel").toggle("fast");
			$(this).toggleClass("active");
			return false;
		})
		
		// Autocomplete company name
		$('#activityPanelCompany').autocomplete('/cp/companies/ajax/search');	

	});
</script>