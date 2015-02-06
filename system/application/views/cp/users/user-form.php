<?php if ($errors) : ?>
	<div class="error">
		<?php echo $errors; ?>
	</div>
<?php endif; ?>

<div class="span-24 last">
	
	<fieldset>
	  	
		<legend>User Information</legend>
		
		<p>
			<label>Name</label><br />
			<input name="name" type="text" class="text" value="<?php echo $user->name; ?>" />
		</p>
		
		<p>
			<label>Username</label><br />
			<input name="username" type="text" class="text" value="<?php echo $user->username; ?>" />
		</p>
		
		<p>
			<label>Password</label><br />
			<input name="password" type="password" class="text" value="" /><br />
			<?php if ($user->id) : ?>
			<span class="quiet">Leave blank to keep current password.</span>
			<?php endif; ?>
		</p>
		
		<p>
			<label>Email</label><br />
			<input name="email" type="text" class="text" value="<?php echo $user->email; ?>" />
		</p>
		
		<p>
			<label>Role</label><br />
			<?php echo form_dropdown('role', $roles, $user->role); ?>
		</p>
		
		<div id="company-panel" <?php if ($user->role != ROLE_CLIENT): ?>style="display:none;"<?php endif; ?>>
			<?php echo dialog_select_company($user->company); ?>
		</div>
		
	</fieldset>

</div>
	
<div class="span-24 last">
	<p>
		<button type="submit" class="button primary" name="submit">Save</button>
	</p>
</div>

<script type="text/javascript">
	$(function() {
	
		$('select[name="role"]').change(function() {
			if ($(this).val() == '<?php echo ROLE_CLIENT; ?>') {
				$('#company-panel').slideDown();
			} else {
				$('#company-panel').slideUp();
			}
		});
		
		
		// Autocomplete company name
		$('#company').autocomplete('/cp/companies/ajax/search');
		
	});
</script>