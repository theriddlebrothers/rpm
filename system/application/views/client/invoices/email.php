<div id="invoice">
	
	<h1>Invoice #<?php echo $invoice->invoice_number; ?></h1>	
	
	<?php if ($custom_message) : ?>
		<p><strong><?php echo $custom_message; ?></strong></p>
	<?php endif; ?>
	
	<p>A copy of your invoice is below. If you are having trouble viewing this invoice, you may view it online any time at <?php echo $view_link; ?></p>
	
	<p>If you would like to pay online, click the link above and click the "Pay Now" button at the bottom of the invoice.</p>
	
	<table style="width:100%" border="0">

		<tr>
			<td valign="top" style="width:60%;">
				<table cellpadding="2">
					<tr>
						<td colspan="2">
							<h3>Invoice Information</h3>
						</td>
					</tr>
					<tr>
						<td><strong>Invoice Date</strong></th>
						<td><?php echo date("m/d/Y", strtotime($invoice->invoice_date)); ?></td>
					</tr>
					<tr>
						<td><strong>Invoice Number</strong></th>
						<td><?php echo $invoice->invoice_number; ?></td>
					</tr>
					<tr>
						<td><strong>Payment Terms</strong></th>
						<td><?php echo $invoice->terms; ?></td>
					</tr>
					<?php if ($invoice->project->id) : ?>
						<tr>
							<td><strong>Project Name</strong></th>
							<td><?php echo $invoice->project->name; ?></td>
						</tr>
						<tr>
							<td><strong>Project Number</strong></th>
							<td><?php echo $invoice->project->fullProjectNumber(); ?></td>
						</tr>
					<?php endif; ?>
				</table>
					
				<p><?php echo $invoice->description; ?></p>
				
			</td>
			
			<td valign="top" style="width:40%;">
				<table cellpadding="2">
					<tr>
						<td colspan="2">		
							<h3>Bill To</h3>	
						</td>
					</tr>
					<tr>
						<td valign="top">	
							<p>
								<?php echo $invoice->company->name; ?><br />
								<?php echo str_replace("\n", "<br />", $invoice->bill_to); ?>
							</p>
						</td>
					</tr>
				
				</table>	
				
			</td>
		</tr>
		
		<?php if ($invoice->message) : ?>
			<tr>
				<td style="background-color:#FFF6BF; border:2px solid #FFD324" colspan="2">
					<strong><?php echo $invoice->message; ?></strong>
				</td>
			</tr>
		<?php endif; ?>
				
		<tr>
		
			<td colspan="2">
			
				<h3>Invoice Detail</h3>
				
				<table border="1" cellspacing="0" style="width:100%;">
					<thead>
						<tr>
							<th>Description</th>
							<th style="width:200px;">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($invoice->lineitem->all) : ?>
							<?php foreach($invoice->lineitem->all as $item) : ?>
								<!-- stored items -->
								<tr>
									<td><?php echo $item->description; ?></td>
									<td  style="text-align:right;"><?php echo number_format($item->amount, 2); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td>Total</td>
							<td style="text-align:right;">
								$<?php echo number_format($invoice->getTotal(), 2); ?>
							</td>
						</tr>
					</tfoot>
				</table>
				
			</td>
		
		</tr>	
		
		<tr>
			<td colspan="2"><p><strong><?php echo $payable_terms; ?></strong></p></td>
		</tr>
		
		<tr>
		
			<td></td>
			<td style="text-align:right;">
			
				<table style="width:100%;">
				
					<tr>

						<td style="text-align:right;">					
							<p><strong>Total Due</strong></p>
						
							<h3>$<?php echo number_format($invoice->getTotal(), 2); ?></h3>
						
							<?php if (($invoice->status == 'Unpaid') || ($invoice->status == 'Unsent') && ($invoice->getTotal() > 0)) : ?>
								<h3>
									<a href="<?php echo $view_link; ?>">
										Pay Online
									</a>
								</h3>
							<?php endif; ?>
						</td>
					</tr>
					
				</table>
				
			</td>
		</tr>
		
		<?php if (($invoice->status == 'Paid') || ($invoice->status == 'Void')) : ?>
		<tr>
		
			<td></td>
			<td style="text-align:right;">
				<table style="width:100%;">
					<tr style="text-align:right;">
						<td style="width:75%;"></td>
						<?php if ($invoice->status == 'Paid') : ?>
							<td style="background:none repeat scroll 0 0 #E6EFC2; border:2px solid #C6D880;padding:10px; width:50px;">
					
								<h2>PAID</h2>
							</td>
						<?php elseif ($invoice->status == 'Void') : ?>
							<td style="background:none repeat scroll 0 0 #E6EFC2; border:2px solid #C6D880;padding:10px; width:50px;">
								<h2>VOID</h2>
							</td>
						<?php endif; ?>
					</tr>
				</table>
			</td>
		</tr>			
		<?php endif; ?>
		
		<tr>
		
			<td colspan="2" style="text-align:center;">
				<?php echo $footer_text; ?>

			</td>
		</tr>
	</table>	
	
	
	
</div>
