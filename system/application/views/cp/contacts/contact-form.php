<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Contact Information</legend>
	
	<p>
		<label>First Name</label><br />
		<input name="first_name" type="text" class="text" value="<?php echo postback($contact, 'first_name'); ?>" />
	</p>
	
	<p>
		<label>Last Name</label><br />
		<input name="last_name" type="text" class="text" value="<?php echo postback($contact, 'last_name'); ?>" />
	</p>
	
	<p>
		<label>Email</label><br />
		<input name="email" type="text" class="text" value="<?php echo postback($contact, 'email'); ?>" />
	</p>
	
	<?php echo dialog_select_company($contact->company); ?>
	
	<p>
		<label>Phone</label><br />
		<input name="phone" type="text" class="text" value="<?php echo postback($contact, 'phone'); ?>" />
	</p>
	
	<p>
		<label>Fax</label><br />
		<input name="fax" type="text" class="text" value="<?php echo postback($contact, 'fax'); ?>" />
	</p>
	
	<p>
		<label>Address</label><br />
		<input name="address" type="text" class="text" value="<?php echo postback($contact, 'address'); ?>" />
	</p>
	
	<p>
		<label>City</label><br />
		<input name="city" type="text" class="text" value="<?php echo postback($contact, 'city'); ?>" />
	</p>
	
	<p>
		<label>State</label><br />
		<select name="state">
			<option value="">Select a state...</option>
			<?php foreach($states as $code=>$name) : ?>
				<option <?php if ($code == postback($contact, 'state')) echo 'selected="selected"'; ?> value="<?php echo $code; ?>"><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	
	<p>
		<label>Zip</label><br />
		<input name="zip" type="text" class="text" value="<?php echo postback($contact, 'zip'); ?>" />
	</p>
	
	<p>
		<label>Website</label><br />
		<input name="website" type="text" class="text" value="<?php echo postback($contact, 'website'); ?>" />
	</p>
	
	
	<p>
		<input type="checkbox" name="subscribed" value="1" <?php if ($contact->isSubscribed()): ?>checked="checked"<?php endif; ?>/> <label>Subscribed to Client Mailing List</label>
	</p>
	

	
</fieldset>

<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>