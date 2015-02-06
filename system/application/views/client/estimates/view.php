<div id="estimate">

	<h1><?php echo $estimate->name; ?></h1>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php foreach($errors as $error) : ?>
  				<?php echo $error; ?>
  			<?php endforeach; ?>
  		</div>
  	<?php endif; ?>
	
	<p class="printhide actions">
		<button class="button secondary" onclick="window.print();">Print</button>
	</p>
	
	<fieldset>
		
		<legend>Estimate Information</legend>
		
		<table>
			<tr>
				<th>Estimate Date</th>
				<td><?php echo date("m/d/Y", strtotime($estimate->estimate_date)); ?></td>
			</tr>	
			<tr>
				<th>Estimate Number</th>
				<td><?php echo $estimate->estimate_number; ?></td>
			</tr>	
			<tr>
				<th>Client Name</th>
				<td><?php echo $estimate->company->name; ?></td>
			</tr>	
			<tr>
				<th>Project</th>
				<td><?php echo $estimate->project->name; ?></td>
			</tr>	
			<tr>
				<th>Status</th>
				<td><?php echo $estimate->status; ?></td>
			</tr>	
		</table>
		
	</fieldset>
	
	<fieldset class="markdown">
		      	
		<legend>Estimate Content</legend>   
		
		<div>
			<?php echo $estimate->content; ?>
		</div>
		
	</fieldset>
	
	<?php if ($cost_items) : ?>
		<fieldset id="estimate-budget">
					 	
			<legend>Estimate Budget</legend>
			
			<table id="costTable" class="noedit">
				<tfoot>
					<tr>
						<td>Total</td>
						<td class="money total">
							$<?php echo number_format($estimate->getTotalEstimated(), 2); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($cost_items as $item) : ?>
							<!-- stored items -->
							<tr class="<?php echo $item['item_type']; ?>">
								<td><?php echo $item['description']; ?></td>
								<td class="money"><?php if ($item['item_type'] == 'price') echo number_format($item['amount'], 2); else echo $item['heading']; ?></td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>
			
		</fieldset>
	<?php endif; ?>
	
<?php $cust = trim($estimate->custom_terms);
	if ( !empty($cust) || ($estimate->term_type == 'stored')) : ?>
		<fieldset class="markdown">
		
			<legend>Terms</legend>
			
			<div>
				<?php if ($estimate->term_type == 'stored') : ?>
					<?php echo $estimate->estimateterm->content; ?>
				<?php else : ?>
					<?php echo $estimate->custom_terms; ?>
				<?php endif; ?>
			</div>
		
		</fieldset>
	<?php endif; ?>
	
	<fieldset>
	
		<legend>Estimate Approval</legend>
		
		<?php if ($estimate->status == 'Approved') : ?>
			
			<p>Agreed to by: <strong><?php echo $estimate->signature; ?></strong></p>

			<p>Signed electronically on <?php echo date("m/d/Y @ H:i:s", strtotime($estimate->signature_date)); ?> from IP address <?php echo $estimate->signature_ip; ?>.</p>
			
		<?php elseif($estimate->status == 'Pending Approval') : ?>

			<form action="" method="post">
				<div class="printhide">
					<p>Fill in your full name below and click "I agree to these terms" to accept the terms of this agreement. By clicking "Accept" you hereby to the terms outlined above.</p>
				
					<p>
						<label>Agreed to by:</label><br /> 
				
						<input type="text" class="text" name="signature" /> from IP address <?php echo $this->input->ip_address(); ?>
					</p>
				
					<p>If you do not want to sign this agreement electronically you may <a href="javascript:window.print();">print this page</a>. A signature line will be available on the last page of the agreement.</p>
						
					<button type="submit" class="button primary" name="submit">I agree to these terms</button>
				</div>
				<div class="printonly">
					<p>
						Agreed to by: ___________________________________________<br />
						<br />
						Printed name: ___________________________________________<br />
						<br />
						Date: ___________________________________________________
					</p>
				</div>		
			</form>		
		<?php else : ?>
			<p>This estimate has expired or has been rejected.</p>
		<?php endif; ?>
		
	</fieldset>
	
</div>