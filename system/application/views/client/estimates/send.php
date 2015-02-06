<div id="estimate">

	<h1><?php echo $estimate->name; ?></h1>
  		
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
	
	<fieldset>
		      	
		<legend>Estimate Content</legend>   
		
		<div>
			<?php echo $estimate->content; ?>
		</div>
		
	</fieldset>
	
	<?php if ($cost_items) : ?>
		<fieldset>
					 	
			<legend>Estimate Budget</legend>
			
			<table id="costTable" class="noedit" style="width:100%;" border="1" cellpadding="1" cellspacing="0">
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
								<?php if ($item['item_type'] != 'price') : ?>
									<th><?php echo $item['description']; ?></th>
									<th class="money"><?php echo $item['heading']; ?></th>
								<?php else : ?>
									<td><?php echo $item['description']; ?></td>
									<td class="money"><?php if ($item['item_type'] == 'price') echo number_format($item['amount'], 2); else echo $item['heading']; ?></td>
								<?php endif; ?>
								
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
	
		<p>Agreed to by: <strong><?php echo $estimate->signature; ?></strong></p>

		<p>Signed electronically on <?php echo date("m/d/Y @ H:i:s", strtotime($estimate->signature_date)); ?> from IP address <?php echo $estimate->signature_ip; ?>.</p>
		
	</fieldset>
	
</div>