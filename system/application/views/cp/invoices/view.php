<div id="invoice">

	<h1>Invoice #<?php echo $invoice->invoice_number; ?></h1>	
	
	<p><a href="<?php echo site_url('invoices/edit/' . $invoice->id); ?>">Edit</a> | 
            <a href="<?php echo site_url('invoices/download/' . $invoice->id); ?>">Download</a> | 
            <a href="<?php echo site_url('/client/invoices/view/' . $invoice->id); ?>">View Client Invoice</a></p>	
	
	
	<div class="span-12">
		<fieldset>

			<legend>Invoice Information</legend>	
			
			<table>
				<tr>
					<th>Invoice Date</th>
					<td><?php echo date("m/d/Y", strtotime($invoice->invoice_date)); ?></td>
				</tr>
				<?php if ($invoice->last_sent) : ?>
					<tr>
						<th>Emailed to Client</th>
						<td><?php echo date("m/d/Y", strtotime($invoice->last_sent)); ?></td>
					</tr>
				<?php endif; ?>
				<tr>
					<th>Invoice Number</th>
					<td><?php echo $invoice->invoice_number; ?></td>
				</tr>
				<tr>
					<th>Payment Terms</th>
					<td><?php echo $invoice->terms; ?></td>
				</tr>
				<?php if ($invoice->project->id) : ?>
					<tr>
						<th>Project Name</th>
						<td><?php echo $invoice->project->name; ?></td>
					</tr>
					<tr>
						<th>Project Number</th>
						<td><?php echo $invoice->project->fullProjectNumber(); ?></td>
					</tr>
				<?php endif; ?>
			</table>
			
			<div class="span-11 last">
				<p><?php echo $invoice->description; ?></p>
			</div>
			
			<?php if ($invoice->memo) : ?>
				<div class="span-11 last">
					<p><strong>Memo</strong>: <?php echo $invoice->memo; ?></p>
				</div>
			<?php endif; ?>
		</fieldset>
	</div>
		
	<div class="span-12 last">
		
		<fieldset>

			<legend>Bill To</legend>	
			
			<div class="span-6">	
				<p>
					<?php echo $invoice->company->name; ?><br />
					<?php echo str_replace("\n", "<br />", $invoice->bill_to); ?></p>
			</div>
			
			<div class="span-5 last">
			
				<?php if ($invoice->status == 'Paid') : ?>
					<div class="payment success">
						<h2>PAID</h2>
					</div>
				<?php elseif ($invoice->status == 'Void') : ?>
					<div class="payment error">
						<h2>VOID</h2>
					</div>
				<?php endif; ?>
				
			</div>
			
		</fieldset>
		
		<?php if ($invoice->message) : ?>
			<div class="notice">
				<?php echo $invoice->message; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="span-24 last">
		
		<fieldset>
				
			<legend>Invoice Detail</legend>
			
			<table id="costTable" class="noedit">
				<thead>
					<tr>
						<th>Description</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tfoot>
					<tr class="total">
						<td>Total</td>
						<td class="money total">
							$<?php echo number_format($invoice->getTotal(), 2); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php if ($line_items) : ?>
						<?php foreach($line_items as $item) : ?>
							<!-- stored items -->
							<tr>
								<td><?php echo $item['description']; ?></td>
								<td class="money"><?php echo number_format($item['amount'], 2); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
			
		</fieldset>
		
	</div>
	
	<?php if ($invoice->transaction->id) : ?>
		<div class="span-24 last">
			
			<fieldset>
					
				<legend>Transaction Detail</legend>
				
				<table>
					<tr>
						<th>Transaction Date</th>
						<td><?php echo date("m/d/Y h:ia", strtotime($invoice->transaction->transaction_date)); ?></td>
					</tr>
					<tr>
						<th>Paypal Transaction ID</th>
						<td><?php echo $invoice->transaction->txn_id; ?></td>
					</tr>
					<tr>
						<th>Payer Email</th>
						<td><?php echo $invoice->transaction->payer_email; ?></td>
					</tr>
					<tr>
						<th>Total Paid</th>
						<td>$<?php echo number_format($invoice->transaction->total_paid, 2); ?></td>
					</tr>
					<tr>
						<th>Payer Status</th>
						<td><?php echo $invoice->transaction->payer_status; ?></td>
					</tr>
					<tr>
						<th>Verification Response</th>
						<td><?php echo $invoice->transaction->verification_response; ?></td>
					</tr>
					<tr>
						<th>Payment Status</th>
						<td><?php echo $invoice->transaction->payment_status; ?></td>
					</tr>
				</table>
				
			</fieldset>
			
		</div>
	<?php endif; ?>
	
	<div class="span-16">
	
		<p><strong><?php echo $payable_terms; ?></strong></p>
		
	</div>
	
	<div class="alignright span-8 last">
		<fieldset>
			<legend>Total Due</legend>
			
			<h3>$<?php echo number_format($invoice->getTotal(), 2); ?></h3>
		</fieldset>
	</div>
		
	<?php $this->load->view('cp/invoices/breakdown'); ?>
	
	<div id="address" class="aligncenter span-24 last">
		<p><?php echo $footer_text; ?></p>
	</div>
	
</div>
