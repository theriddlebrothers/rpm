<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Status Report</legend>
	
	<p>
		<label>Report Date</label><br />
		<input id="reportDate" name="report_date" type="text" class="datepicker text" value="<?php if (postback($report, 'report_date')) echo date("m/d/Y", strtotime(postback($report, 'report_date'))); ?>" />
	</p>
	
	<?php echo dialog_select_project($report->project); ?>	
	
	<p>
		<label>Report Content</label><br />
		<textarea class="span-23 text" name="content"><?php echo postback($report, 'content'); ?></textarea>
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>