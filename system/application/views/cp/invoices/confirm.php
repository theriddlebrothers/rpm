<div id="listInvoices">

	<h1>Invoices</h1>
			
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php foreach($errors as $error) : ?>
  				<?php echo $error; ?>
  			<?php endforeach; ?>
  		</div>
  	<?php endif; ?>
  	
	<div class="span-24 last">
		
		<?php if (user_can('create', 'invoices')) : ?>
			<p><a href="<?php echo site_url('invoices/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		
		<form action="" method="post">
		
			<?php if (user_can('edit', 'invoices')) : ?>
				
				<div class="span-24 last">
					<p>						
						<button type="submit" class="button primary" name="submit">
							Send Invoices
						</button>
					</p>
				</div>
				
			<?php endif; ?>
			
				<div class="span-24 last">
				
				<?php if ($invoices) : ?>
					<table id="invoicesTable">
						<thead>
							<tr>
								<th><input type="checkbox" id="send" checked="checked" /></th>
								<th class="span-2">Invoice #</th>
								<th class="span-5">Company</th>
								<th>Email Recipients</th>
								<th class="span-2">Amount</th>
								<th class="span-2">Status</th>
								<th class="span-2"></th>
							</tr>
						</thead>	
						<tfoot>
							<tr>
								<td colspan="9">
									<?php echo $pager; ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
								<?php foreach($invoices as $invoice) : ?>
									<tr>
										<td>
											<?php if ($invoice->recipients) : ?>
												<input type="checkbox" name="send[]" checked="checked" value="<?php echo $invoice->id; ?>" />
											<?php endif; ?>
											</td>
										<td><?php echo $invoice->invoice_number; ?></td>
										<td><?php echo $invoice->company->name; ?></td>
										<td>
											<?php echo $invoice->recipients; ?>
										</td>
										<td>$<?php echo number_format($invoice->getTotal(), 2); ?></td>
										<td><?php echo $invoice->status ?></td>
										<td>
											<?php if (user_can('view', 'invoices')) : ?>
												<a class="icon view" href="<?php echo site_url('invoices/view/' . $invoice->id); ?>">View</a>
											<?php endif; ?>
											<?php if (user_can('edit', 'invoices')) : ?>
												<a class="icon edit" href="<?php echo site_url('invoices/edit/' . $invoice->id); ?>">Edit</a> 
											<?php endif; ?>
											<?php if (user_can('delete', 'invoices')) : ?>
												<a class="icon delete popup" href="<?php echo site_url('invoices/delete/' . $invoice->id); ?>">Delete</a>						
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>			
				<?php else : ?>
					<p>No invoices listed.</p>
				<?php endif; ?>
					
					</div>
					
		</form>
	
	</div>
			
</div>

<script type="text/javascript">
	$(function() {
		$('#send').click(function() {
			var checked = $(this).attr("checked");
			$('#invoicesTable tbody input').each(function() {
				$(this).attr("checked", checked);
			});
		});
	});
</script>