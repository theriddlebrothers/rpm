<div id="invoice">

	<h1><?php echo $title; ?></h1>
	
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<p>A link to the <a href="<?php echo site_url('/client/invoices/view/' . $invoice->id); ?>">client invoice</a> will be sent to the recipients specified below.</p>
	
	<form action="" method="post">
	
		<fieldset>
			<legend>Send Invoice</legend>	
			
			<div class="span-12">
			
				<p>
					<label>Custom Message</label><br />
					<textarea class="text" name="message"></textarea><br />
					<span class="quiet">Message will be displayed at the top of the email.</span>
				</p>
				
			</div>
		
			<div class="span-11 last">
			
				<p>
					<label>Email Recipients</label><br />
					<textarea class="text" name="recipients"><?php echo $invoice->recipients; ?></textarea><br />
					<span class="quiet">Separate multiple email addresses with commas.</span>
				</p>
				
			</div>
		
		</fieldset>
		
		<button type="submit" class="button positive" name="submit">
			<img src="/images/icons/email_go.png" alt=""/> Send
		</button>
		
	</form>


</div>