<div id="invoice">

	<h1>Invoice #<?php echo $invoice->invoice_number; ?></h1>	
	
	<p class="actions">
		<button id="print" class="printhide secondary button">
			Print		
		</button>
            
		<a id="pdf" class="printhide secondary button" href="<?php echo site_url('invoices/download/' . $invoice->id); ?>">
			Download as PDF		
		</a>
	</p>
	
	<div id="invoice-information" class="span-12">
		<fieldset>

			<legend>Invoice Information</legend>	
			
			<table>
				<tr>
					<th>Invoice Date</th>
					<td><?php echo date("m/d/Y", strtotime($invoice->invoice_date)); ?></td>
				</tr>
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
				<?php echo $invoice->description; ?>
			</div>
		</fieldset>
	</div>
	
	<div id="bill-to" class="span-12 last">
		
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
	
	<div id="invoice-detail" class="span-24 last">
		
		<fieldset>
				
			<legend>Invoice Detail</legend>
			
			<table id="costTable" class="noedit">
				<thead>
					<tr>
						<th>Description</th>
						<th class="money">Amount</th>
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
	
	<div id="payable" class="span-19">
	
		<p><strong><?php echo $payable_terms; ?></strong></p>
		
	</div>
	
	<div id="total-due" class="span-5 last">
		<fieldset class="alignright">
			
			<legend>Total Due</legend>
			
			<h3>$<?php echo number_format($invoice->getTotal(), 2); ?></h3>
			
		</fieldset>
		
		<?php if (($invoice->status == 'Unpaid') || ($invoice->status == 'Unsent') && ($invoice->getTotal() > 0)) : ?>
			<form id="paypal" action="<?php echo $paypal_url; ?>" method="post">  
				<input type="hidden" name="cmd" value="_xclick" />
				<input type="hidden" name="notify_url" value="<?php echo $paypal_ipn; ?>" />
				<input type="hidden" name="business" value="<?php echo $paypal_email; ?>" />
				<input type="hidden" name="item_name" value="<?php echo $invoice->description; ?>" />
				<input type="hidden" name="item_number" value="<?php echo $invoice->invoice_number; ?>" />
				<input type="hidden" name="amount" value="<?php echo $invoice->getTotal(); ?>" />
				<input class="floatright" type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_paynow_LG.gif" alt="PayPal - The safer, easier way to pay online" />
			</form> 
		<?php endif; ?>
		
	</div>
	
	<?php $this->load->view('cp/invoices/breakdown'); ?>
	
	<div id="address" class="aligncenter span-24 last">
		<p><?php echo $footer_text; ?></p>
	</div>
	
</div>
<script type="text/javascript">
	$(function() {
		$('#print').click(function() {
			window.print();
		});
	});
</script>