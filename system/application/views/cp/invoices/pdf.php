<img src="http://sandbox.projectmanager.com/images/logo.png" alt="" />

<div id="invoice">

	<h1>Invoice #<?php echo $invoice->invoice_number; ?></h1>	
	
        <h3>Invoice Information</h3>
        	
        <table style="float:left;" border="1" width="200">
                <tr>
                        <th valign="top" align="left">Invoice Date</th>
                        <td><?php echo date("m/d/Y", strtotime($invoice->invoice_date)); ?></td>
                </tr>
                <tr>
                        <th align="left">Invoice Number</th>
                        <td><?php echo $invoice->invoice_number; ?></td>
                </tr>
                <tr>
                        <th align="left">Payment Terms</th>
                        <td><?php echo $invoice->terms; ?></td>
                </tr>
                <?php if ($invoice->project->id) : ?>
                        <tr>
                                <th valign="top"  align="left">Project Name</th>
                                <td><?php echo $invoice->project->name; ?></td>
                        </tr>
                        <tr>
                                <th align="left">Project Number</th>
                                <td><?php echo $invoice->project->fullProjectNumber(); ?></td>
                        </tr>
                <?php endif; ?>
        </table>
        
        <div >
            <tr>
                <td>
                    <p>
					<?php echo $invoice->company->name; ?><br />
					<?php echo str_replace("\n", "<br />", $invoice->bill_to); ?></p>
                </td>
            </tr>
        </table>

        <p><?php echo $invoice->description; ?></p>

        <?php if ($invoice->memo) : ?>
            <p><strong>Memo</strong>: <?php echo $invoice->memo; ?></p>
        <?php endif; ?>
		
	<div>
		
		<fieldset>

			<legend>Bill To</legend>	
			
			<div>	
				<p>
					<?php echo $invoice->company->name; ?><br />
					<?php echo str_replace("\n", "<br />", $invoice->bill_to); ?></p>
			</div>
			
			<div>
			
				<?php if ($invoice->status == 'Paid') : ?>
					<div>
						<h2>PAID</h2>
					</div>
				<?php elseif ($invoice->status == 'Void') : ?>
					<div>
						<h2>VOID</h2>
					</div>
				<?php endif; ?>
				
			</div>
			
		</fieldset>
		
		<?php if ($invoice->message) : ?>
			<div>
				<?php echo $invoice->message; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<div>
		
            <fieldset>

                    <legend>Invoice Detail</legend>

                    <table id="costTable">
                            <thead>
                                    <tr>
                                            <th>Description</th>
                                            <th>Amount</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <?php if ($line_items) : ?>
                                            <?php foreach($line_items as $item) : ?>
                                                    <!-- stored items -->
                                                    <tr>
                                                            <td><?php echo $item['description']; ?></td>
                                                            <td><?php echo number_format($item['amount'], 2); ?></td>
                                                    </tr>
                                            <?php endforeach; ?>
                                    <?php endif; ?>
                            </tbody>
                            <tfoot>
                                    <tr>
                                            <td>Total</td>
                                            <td>
                                                    $<?php echo number_format($invoice->getTotal(), 2); ?>
                                            </td>
                                    </tr>
                            </tfoot>
                    </table>

            </fieldset>
		
	</div>
	
	<?php if ($invoice->transaction->id) : ?>
		<div>
			
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
	
	<div>
	
		<p><strong><?php echo $payable_terms; ?></strong></p>
		
	</div>
	
	<div>
		<fieldset>
			<legend>Total Due</legend>
			
			<h3>$<?php echo number_format($invoice->getTotal(), 2); ?></h3>
		</fieldset>
	</div>
		
	<?php $this->load->view('cp/invoices/breakdown'); ?>
	
	<div id="address">
		<p><?php echo $footer_text; ?></p>
	</div>
	
</div>
