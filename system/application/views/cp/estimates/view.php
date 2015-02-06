<div id="estimate">

	<h1><?php echo $estimate->name; ?></h1>
		
	<p><a href="<?php echo site_url('estimates/edit/' . $estimate->id); ?>">Edit</a> | 
	<a href="<?php echo site_url('/client/estimates/view/' . $estimate->id); ?>">View Client Estimate</a></p>	
	
	<!--<p>
		Client Estimate View Link: <input type="text" class="text" value="<?php echo $view_link; ?>" />
	</p>-->
	
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
			<th>Client/Company Name</th>
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
	
	<div id="content" class="markdown">
		<?php echo $estimate->content; ?>
	</div>
	
</fieldset>

<?php if ($cost_items) : ?>
	<fieldset>
				 	
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

	<legend>Estimate Actions</legend>
	
	<p>When this estimate is accepted:</p>
	
	<?php if ($estimate->send_email_notification) : ?>
		<p><input type="checkbox" checked="checked" /> Email a copy to <strong><?php echo $estimate->send_email_recipient; ?></strong></p>
	<?php endif; ?>
	
</fieldset>	
</div>
