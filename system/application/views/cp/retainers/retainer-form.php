<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Retainer Information</legend>
	
        
	<?php echo dialog_select_company($retainer->company); ?>
        
        <p>
            <label>Start Date</label><br />
            <input type="text" class="text datepicker" name="start_date" value="<?php if (postback($retainer, 'end_date')) echo date("m/d/Y", strtotime(postback($retainer, 'start_date'))); ?>" />
        </p>
        
        <p>
            <label>End Date</label><br />
            <input type="text" class="text datepicker" name="end_date" value="<?php if (postback($retainer, 'start_date')) echo date("m/d/Y", strtotime(postback($retainer, 'end_date'))); ?>" />
        </p>
        
        <p>
            <label>Hours</label><br />
            <input type="text" class="text number" name="hours" value="<?php echo postback($retainer, 'hours'); ?>" />
        </p>
        
        <p>
            <label>Billable Rate</label><br />
            <input type="text" class="text number" name="billable_rate" value="<?php echo postback($retainer, 'billable_rate'); ?>" />
        </p>
        
        <p>
            <label>Status</label><br />
            <select name="status">
                <option value="">Select a status...</option>
                <option <?php if ($retainer->status == 'Pending Approval') echo ' selected="selected"'; ?> value="Pending Approval">Pending Approval</option>
                <option <?php if ($retainer->status == 'Approved') echo ' selected="selected"'; ?> value="Approved">Approved</option>
                <option <?php if ($retainer->status == 'Rejected') echo ' selected="selected"'; ?> value="Rejected">Rejected</option>
                <option <?php if ($retainer->status == 'Expired') echo ' selected="selected"'; ?> value="Expired">Expired</option>
            </select>
        </p>
</fieldset>

<fieldset>
    <legend>Terms</legend>
    <textarea class="text span-23" name="terms"><?php echo postback($retainer, 'terms'); ?></textarea>
</fieldset>

<fieldset>
	<legend>Invoice Recipients</legend>
	<p>Emails below will receive a copy of the invoice each month.</p>
	<textarea style="height:60px;" class="text" name="invoice_recipients"><?php echo postback($retainer, 'invoice_recipients'); ?></textarea><br />
	<span class="quiet">Separate multiple email addresses with commas.</span>
</fieldset>
	
<fieldset>

	<legend>Retainer Actions</legend>
	
	<p>When this retainer is accepted:</p>
	
	<p>
		<input <?php if (postback($retainer, 'send_email_notification')) echo 'checked="checked"'; ?> type="checkbox" name="send_email_notification"/> Email a copy to <input type="text" class="text" value="<?php echo (postback($retainer, 'send_email_recipient')); ?>" name="send_email_recipient" />
	</p>
	
</fieldset>
        
<p><button type="submit" class="button primary" name="submit">Save</button></p>
