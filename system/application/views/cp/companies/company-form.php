<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Company Information</legend>
	
	<p>
		<label>Company Name</label><br />
		<input name="name" type="text" class="title" value="<?php echo postback($company, 'name'); ?>" />
	</p>
	
	<p>
		<label>Status</label><br />
		<?php echo form_dropdown('status', $statuses, postback($company, 'status')); ?>
	</p>
	
	<p>
		<label>Email</label><br />
		<input name="email" type="text" class="text" value="<?php echo postback($company, 'email'); ?>" />
	</p>
		
	<p>
		<label>Phone</label><br />
		<input name="phone" type="text" class="text" value="<?php echo postback($company, 'phone'); ?>" />
	</p>
	
	<p>
		<label>Fax</label><br />
		<input name="fax" type="text" class="text" value="<?php echo postback($company, 'fax'); ?>" />
	</p>
	
	<p>
		<label>Address</label><br />
		<input name="address" type="text" class="text" value="<?php echo postback($company, 'address'); ?>" />
	</p>
	
	<p>
		<label>City</label><br />
		<input name="city" type="text" class="text" value="<?php echo postback($company, 'city'); ?>" />
	</p>
	
	<p>
		<label>State</label><br />
		<select name="state">
			<option value="">Select a state...</option>
			<?php foreach($states as $code=>$name) : ?>
				<option <?php if ($code == postback($company, 'state')) echo 'selected="selected"'; ?> value="<?php echo $code; ?>"><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	
	<p>
		<label>Zip</label><br />
		<input name="zip" type="text" class="text" value="<?php echo postback($company, 'zip'); ?>" />
	</p>
	
	<p>
		<label>Website</label><br />
		<input name="website" type="text" class="text" value="<?php echo postback($company, 'website'); ?>" />
	</p>
	
	<?php if ($company->id) : ?>
		<p>
			<label>Reset View Key</label><br />
			<input name="reset_viewkey" type="checkbox" /> Check this box to reset the company's view key.<br />
			<span class="quiet">The old view key will no longer allow a user access to the client section.</span>
		</p>
	<?php endif; ?>
	
	<p>
		<label>Additional Information</label><br />
		<textarea class="span-23 text" name="info"><?php echo postback($company, 'info'); ?></textarea><br />
		<span class="quiet">Include company-wide login information, FTP credentials, user accounts, etc. in this box. Content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>