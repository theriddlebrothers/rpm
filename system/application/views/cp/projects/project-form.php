<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Project Information</legend>
	
	<p>
		<label>Project Name</label><br />
		<input name="name" type="text" class="title" value="<?php echo postback($project, 'name'); ?>" />
	</p>
	
	<p>
		<label>Project Number</label><br />
		<input name="project_number" type="text" class="number text" value="<?php echo postback($project, 'project_number'); ?>" />
	</p>
	
	<?php echo dialog_select_project($project->parentproject, 'Parent Project'); ?>
	
	<p>
		<label>Target Start Date</label><br />
		<input id="startDate" name="start_date" type="text" class="datepicker text" value="<?php if (postback($project, 'start_date')) echo date("m/d/Y", strtotime(postback($project, 'start_date'))); ?>" />
	</p>
	
	<p>
		<label>Target End Date</label><br />
		<input id="endDate" name="end_date" type="text" class="datepicker text" value="<?php if (postback($project, 'end_date')) echo date("m/d/Y", strtotime(postback($project, 'end_date'))); ?>" />
	</p>
	
	<p>
		<label>Project Type</label><br />
		<select name="project_type">
			<option value="">Select a type...</option>
			<option <?php if (postback($project, 'project_type') == 'Fixed Cost') echo ' selected="selected"'; ?> value="Fixed Cost">Fixed Cost</option>
			<option <?php if (postback($project, 'project_type') == 'Hourly Maintenance') echo ' selected="selected"'; ?> value="Hourly Maintenance">Hourly Maintenance</option>
			<option <?php if (postback($project, 'project_type') == 'Retainer') echo ' selected="selected"'; ?> value="Retainer">Retainer</option>
		</select>
	</p>
	
	<p>
		<label>Status</label><br />
		<select name="status">
			<option value="">Select a status...</option>
			<option <?php if (postback($project, 'status') == 'Approved') echo ' selected="selected"'; ?> value="Approved">Approved</option>
			<option <?php if (postback($project, 'status') == 'Closed') echo ' selected="selected"'; ?> value="Closed">Closed</option>
			<option <?php if (postback($project, 'status') == 'In Progress') echo ' selected="selected"'; ?> value="In Progress">In Progress</option>
			<option <?php if (postback($project, 'status') == 'Pending Approval') echo ' selected="selected"'; ?> value="Pending Approval">Pending Approval</option>
			<option <?php if (postback($project, 'status') == 'Rejected') echo ' selected="selected"'; ?> value="Rejected">Rejected</option>
		</select>
	</p>
	
	<?php echo dialog_select_company($project->company); ?>
	
	<div id="projectContacts" <?php if (!postback($project, 'company', 'id')) : ?>style="display:none;"<?php endif; ?>>
		<p>
			<label>Project Contacts</label><br />
			<select id="contacts" name="contacts[]" multiple="multiple">
				<?php foreach($contacts as $contact) : ?>
					<option <?php if (in_array($contact->id, $selected_contacts)) echo 'selected="selected"'; ?> value="<?php echo $contact->id; ?>"><?php echo $contact->first_name . ' ' . $contact->last_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</div>
	
	<div id="projectResources">
		<p>
			<label>Project Resources</label><br />
			<select id="users" name="users[]" multiple="multiple">
				<?php foreach($users->all as $user) : ?>
					<option <?php if (in_array($user->id, $selected_users)) echo 'selected="selected"'; ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</div>
	
	
	<p>
		<label>Billable Rate</label><br />
		<input name="billable_rate" type="text" class="number text" value="<?php echo postback($project, 'billable_rate'); ?>" />
	</p>
	
	
	
	<!--<p>
		<label>SVN Repository Name</label><br />
		<input name="svn_repo" type="text" class="text" value="<?php echo postback($project, 'svn_repo'); ?>" /><br />
		<span class="quiet">http://www.theriddlebrothers.net/svn/[repository-name]</span>
	</p>-->
	
	<p>
		<label>General Description</label><br />
		<textarea class="text span-23" name="description"><?php echo postback($project, 'description'); ?></textarea>
		<span class="quiet">Project description content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
	</p>
	
		
	<p>
		<button type="submit" class="button primary" name="submit">Save</button>
	</p>
	
</fieldset>


<script type="text/javascript">
	
	$(function() {
	
		// new company selected
		$('.select', '.company-list').click(function() {
			var company = $(this).attr("title");
			// get list of company contacts
			$.getJSON('/cp/contacts/ajax/company/', { company : company }, function(contacts) {
				if (contacts != null) {
					var list = $('#contacts');
					list.children().remove();
					for(i=0; i<contacts.length; i++) {
						var opt = '<option value="' + contacts[i].id + '">' + contacts[i].first_name + ' ' + contacts[i].last_name + '</option>';
						list.append(opt);
					}
					$('#projectContacts').slideDown();
				} else {
					$('#contacts').val('');
					$('#projectContacts').slideDown();
				}
			});
		});
		
	});
	
</script>