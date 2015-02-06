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
	
		<p class="actions">
			<?php if (user_can('create', 'invoices')) : ?>
					<a href="<?php echo site_url('invoices/create'); ?>">Create new</a>
			<?php endif; ?>
			<a href="#" id="show-filter">Filter Results</a>
		</p>
		
	
		<form action="<?php echo site_url('invoices/index'); ?>/" method="get">	
			<div id="filters" class="span-15 last" style="display:none;">
								
				<fieldset>
			
					<legend>Filter</legend>
				
						<div class="span-10">
							<?php echo dialog_select_company($filter->company); ?>
						</div>
						
						
						<div class="span-4 last">
							<label>Status</label><br />
							<select id="status" name="status">
								<option value="">Select a status...</option>
								<option <?php if (postback($filter, 'status') == 'Unsent') echo 'selected="selected"'; ?> value="Unsent">Unsent</option>
								<option <?php if (postback($filter, 'status') == 'Unpaid') echo 'selected="selected"'; ?> value="Unpaid">Unpaid</option>
								<option <?php if (postback($filter, 'status') == 'Paid') echo 'selected="selected"'; ?> value="Paid">Paid</option>
								<option <?php if (postback($filter, 'status') == 'Void') echo 'selected="selected"'; ?> value="Void">Void</option>
							</select>
						</div>
						
						<div class="span-9 last">
							<?php echo dialog_select_project($filter->project); ?>
						</div>
						
												
						<button type="submit" id="filter-apply" class="button button-small secondary" name="submit">Search</button>
						<input type="hidden" name="search" value="1" />
				
					
				</fieldset>
				
			</div>
		</form>
			
		<form action="" method="post">
		
			<?php if (user_can('edit', 'invoices')) : ?>
				
				<div class="span-19">
					<p>						
						<button type="submit" class="button primary" name="prepare" value="1">Prepare Selected
						</button>
					</p>
				</div>
				
				<div class="span-5 last">
					<p>						
						<select id="batch" name="batch">
							<option value="">Batch action...</option>
							<option value="Paid">Mark paid</option>
							<option value="Void">Mark void</option>
							<option value="Unpaid">Mark unpaid</option>
							<option value="Unsent">Mark unsent</option>
						</select>
						<button name="apply" class="floatright button button-small" value="1">Apply</button>
					</p>
				</div>
				
			<?php endif; ?>
			
			<div class="span-24 last">
           		<?php if ($future_invoices) : ?>
            <h3>Scheduled Invoices</h3>
            <p>Invoices that will be sent out automatically on the Send Date.</p>
                    <table id="futureInvoicesTable">
                        <thead>
                            <tr>
                                <th>
                                    <?php if (user_can('edit', 'invoices')) : ?>
                                            <input type="checkbox" class="send" />
                                    <?php endif; ?>
                                </th>
                                <th class="span-2">Invoice #</th>
                                <th class="span-2">Send Date</th>
                                <th class="span-2">Due Date</th>
                                <th>Project</th>
                                <th>Company</th>
                                <th>Amount</th>
                                <th class="span-2"></th>
                                </tr>
                        </thead>	
                        <tfoot>
                            <tr>
                                <td colspan="9">
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach($future_invoices as $invoice) : ?>
                                <tr>
                                    <td>
                                        <?php if (user_can('edit', 'invoices')) : ?>
                                            <input type="checkbox" name="check[]" value="<?php echo $invoice->id; ?>" />
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $invoice->invoice_number; ?></td>
                                    <td><?php echo date("m/d/Y", strtotime($invoice->send_date)); ?></td>
                                    <td><?php echo date("m/d/Y", strtotime($invoice->due_date)); ?></td>
                                    <td><?php echo $invoice->project->name; ?></td>
                                    <td><?php echo $invoice->company->name; ?></td>
                                    <td>$<?php echo number_format($invoice->getTotal(), 2); ?></td>
                                    <td class="actions span-2">
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
                <?php endif; ?>
            </div>
                    
			<div class="span-24 last">
                            <h3>Invoices</h3>
				<?php if ($invoices) : ?>
					<table id="invoicesTable">
						<thead>
							<tr>
								<th>
									<?php if (user_can('edit', 'invoices')) : ?>
										<input type="checkbox" class="send" />
									<?php endif; ?>
								</th>
								<th class="span-2">Invoice #</th>
								<th class="span-2">Due Date</th>
								<th>Project</th>
								<th>Company</th>
								<th>Amount</th>
								<th>Status</th>
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
											<?php if (user_can('edit', 'invoices')) : ?>
												<input type="checkbox" name="check[]" value="<?php echo $invoice->id; ?>" />
											<?php endif; ?>
										</td>
										<td><?php echo $invoice->invoice_number; ?></td>
										<td><?php echo date("m/d/Y", strtotime($invoice->due_date)); ?></td>
										<td><?php echo $invoice->project->name; ?></td>
										<td><?php echo $invoice->company->name; ?></td>
										<td>$<?php echo number_format($invoice->getTotal(), 2); ?></td>
										<td><?php echo $invoice->status ?></td>
										<td class="actions span-2">
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
		// check all
		$('.send').click(function() {
			var checked = $(this).attr("checked");
			$(this).parent().parent().parent().parent().find('tbody input').each(function() {
				$(this).attr("checked", checked);
			});
		});
		
		// show filters
		$('#show-filter').click(function() {
			$('#filters').slideDown();
		});
	});
</script>