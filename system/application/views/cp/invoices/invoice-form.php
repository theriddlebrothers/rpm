<div id="invoice">

	<fieldset>
	  		
	  	<?php if ($errors) : ?>
	  		<div class="error">
	  			<?php echo $errors; ?>
	  		</div>
	  	<?php endif; ?>
	  	
		<legend>Invoice Information</legend>
		
		
		<div class="span-12">
			
			<p>
				<label>Invoice Number</label><br />
				<input name="invoice_number" type="text" class="title number" value="<?php echo postback($invoice, 'invoice_number'); ?>" />
			</p>
			
			<?php echo dialog_select_project($invoice->project); ?>
			
			<div id="estimates" <?php if (!$estimates): ?>style="display:none;"<?php endif; ?>>
				<p>
					<label>Estimate</label><br />
					<select id="estimate" name="estimate">
						<option value="">Select an estimate...</option>
						<?php foreach($estimates as $estimate) : ?>
							<option <?php if (postback($invoice, 'estimate', 'id') == $estimate->id) echo ' selected="selected"'; ?> value="<?php echo $estimate->id; ?>">#<?php echo $estimate->estimate_number . ' - ' . $estimate->name; ?></option>
						<?php endforeach; ?>
					</select>
				</p>		
			</div>
	
			<?php echo dialog_select_company($invoice->company); ?>
			
			<p>
				<label>Bill To</label><br />
				<textarea style="height:75px;" id="bill_to" name="bill_to" class="text"><?php echo postback($invoice, 'bill_to'); ?></textarea>
			</p>
			
		</div>
		
		<div class="span-11 last">
		
			<p>
				<label>Invoice Date</label><br />
				<input name="invoice_date" type="text" class="datepicker text" value="<?php if (postback($invoice, 'invoice_date')) echo date("m/d/Y", strtotime(postback($invoice, 'invoice_date'))); ?>" />
			</p>
			
			<p>
				<label>Due Date</label><br />
				<input name="due_date" type="text" class="datepicker text" value="<?php if (postback($invoice, 'due_date')) echo date("m/d/Y", strtotime(postback($invoice, 'due_date'))); ?>" />
			</p>
			
			<p>
				<label>Send Date</label><br />
				<input name="send_date" type="text" class="datepicker text" value="<?php if (postback($invoice, 'send_date')) echo date("m/d/Y", strtotime(postback($invoice, 'send_date'))); ?>" /><br />
                                <span class="quiet">Invoice will be automatically emailed to recipients on date set.</span>
                        </p>
			
			<p>
				<label>Description</label><br />
				<input type="text" class="text" id="invoice_description" name="invoice_description" value="<?php echo $invoice->description; // cannot use postback function here ?>" /><br />
				<span class="quiet">General description/overview of this invoice's contents.</span>
			</p>

			<p>
				<label>Payment Terms</label><br />
				<select id="terms" name="terms" class="editable">
					<?php foreach($terms as $term) : ?>
						<option <?php if ($invoice->terms == $term) echo 'selected="selected"'; ?> value="<?php echo $term; ?>"><?php echo $term; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<p>
				<label>Status</label><br />
				<select id="status" name="status">
					<option value="">Select a status...</option>
					<option <?php if (postback($invoice, 'status') == 'Unsent') echo 'selected="selected"'; ?> value="Unsent">Unsent</option>
					<option <?php if (postback($invoice, 'status') == 'Unpaid') echo 'selected="selected"'; ?> value="Unpaid">Unpaid</option>
					<option <?php if (postback($invoice, 'status') == 'Paid') echo 'selected="selected"'; ?> value="Paid">Paid</option>
					<option <?php if (postback($invoice, 'status') == 'Void') echo 'selected="selected"'; ?> value="Void">Void</option>
				</select>
			</p>
			
		</div>
		
	</fieldset>
	
	<fieldset>
	
		<legend>Line Items</legend>
	
		<table id="invoiceItems">
			<thead>
				<tr>
					<th>#</th>
					<th>Description</th>
					<th>Amount</th>
					<th></th>
				</tr>
			</thead>	
			<tfoot>
				<tr>
					<td colspan="2">
						<a id="addMore" href="#">Add more</a>
					</td>
					<td class="total">
						<span id="total">$0.00</span>
					</td>
					<td></td>
				</tr>
			</tfoot>
			<tbody>
				<?php if ($line_items) : ?>
					<?php for($i=0; $i<sizeof($line_items); $i++) : ?>
						<tr>
							<td class="num"><?php echo $i+1; ?></td>
							<td><input type="text" class="span-16 text" name="description[]" value="<?php echo $line_items[$i]['description']; ?>" /></td>
							<td><input type="text" class="span-3 text" name="amount[]" value="<?php if ($line_items[$i]) echo number_format($line_items[$i]['amount'], 2); ?>" /></td>
							<td class="actions span-1">
								<a class="icon delete" href="#">Delete</a>
							</td>
						</tr>
					<?php endfor; ?>
				<?php endif; ?>
			</tbody>
		</table>
	
	</fieldset>
	
	<fieldset>
		
		<legend>Invoice Notes</legend>
		
		<div class="span-12">
	
			<p>
				<label>Message</label><br />
				<textarea style="height:100px;" name="message"><?php echo postback($invoice, 'message'); ?></textarea><br />
				<span class="quiet">Message appears at the bottom of the invoice.</span>
			</p>
			
		</div>
		<div class="span-11 last">
			<p>
				<label>Private Memo</label><br />
				<input type="text" class="text" name="memo" value="<?php echo postback($invoice, 'memo'); ?>" /><br />
				<span class="quiet">Memo does not appear on client-facing invoice and is for internal purposes only.</span>
			</p>
		</div>
		
	</fieldset>
		
	<fieldset>
	
		<legend>Delivery Options</legend>
		
		<p>
			<strong>Invoice will not be sent when you save this page.</strong> In order to send an invoice select it from the <a href="<?php echo site_url('invoices'); ?>">invoices list</a> and click the "Send Invoices" button.
		</p>
		
		<div class="span-12">
		
			<p>
				<label>Email Recipients</label><br />
				<textarea id="recipients" style="height:60px;" name="recipients"><?php echo postback($invoice, 'recipients'); ?></textarea><br />
				<span class="quiet">Separate each recipient email with a comma.</span>
			</p>
		</div>
		
		<div class="span-11 last">	
			
			<p>
				<label>Recipient Message</label><br />
				<textarea style="height:60px;" name="recipient_message"><?php echo postback($invoice, 'recipient_message'); ?></textarea><br />
				<span class="quiet">This message will be displayed at the top of the email sent to the Email Recipients.</span>
			</p>
			
		</div>
	</fieldset>
		
	<p>
		<button type="submit" class="button primary" name="submit">Save</button>
	</p>

</div>

<div style="display:none;">
	<div id="generate">
		<h3>Generate Invoice</h3>
		
		<p>You are able to generate an invoice based on the amounts in this estimates. Enter a percentage below in order to pull the line items from this estimate. If you do not want to do this, click the cancel button.</p>
		
		<p>
			<label>Invoice Percentage</label><br />
			<input type="text" class="text number" id="percentage" /> %
		</p>
		
	</div>
</div>

<script type="text/javascript">
	
	$(function() {
	
		function renumberRows() {
			var i = 0;
			$('#invoiceItems .num').each(function() {
				i++;
				$(this).text(i);
			});
		}
		
		// remove line item row
		$('.delete', '#invoiceItems').live('click', function() {
			// don't remove all rows
			if ($('#invoiceItems tbody tr').length > 1) {
				$(this).parent().parent().remove();
			}
			
			renumberRows();
			updateCostTotal();
			
			return false;
		});
		
		// add row
		$('#addMore').click(function() {
			var row = $(this).parents('table').find('tbody tr:last').html();
			$(this).parents('table').children('tbody').append('<tr>' + row + '</tr>');
			$(this).parents('table').find('tbody tr:last input').each(function() {
				$(this).val('');
			});
			renumberRows();
			
			// allow new row to be be drag/dropable
			$('#invoiceItems').tableDnD({
				onDrop: renumberRows
			});
		
			return false;
		});

		// Auto-calculate costs when adding items
		$('#invoiceItems input').live('blur', function() {
			updateCostTotal();
		});
		
		function updateCostTotal() {
			var totalCost = 0;
			$('#invoiceItems input[name="amount[]"]').each(function() {
				var val = $(this).val();
				
				val = val.replace(',', '');
				var floatVal = parseFloat(val);

				if (!isNaN(floatVal)) {
					var floatAmount = parseFloat(val);
					totalCost += floatAmount;
				}

			});
			$('#total', '#invoiceItems').html('$' + addCommas(totalCost.toFixed(2)));
		}
		
		updateCostTotal();
		
		function getProjectContacts() {
			var project = $('#project').val();
			var recipients = $('#recipients');
			if (project != null) {
				// get list of company contacts
				$.getJSON('/cp/contacts/ajax/project/', { project : project }, function(contacts) {
					if (contacts != null) {
						emails = new Array();
						for(i=0; i<contacts.length; i++) {
							if (contacts[i].email != null) emails[emails.length] = contacts[i].email;
						}
						recipients.val(emails.join(', '));
					}
				});
			}
		}
		
		$('.select', '.project-list').click(function() {
			var id = $(this).attr("href").replace("#", "");
			if (id) {
				getProjectContacts();
				// get estimates/company info for this project
				$.getJSON('/cp/estimates/ajax/project/', { project : id }, function(data){
				    $('#estimate').children().remove();
				    
				    if (!data) return;
				    // company info
				    $('input[name="company"]').val(data.company.name);
				    $('#bill_to').val(data.company.address);
				    
				    // estimates
				    var len = data.estimates.length;
				    if (len == 0) {
				    	$('#estimates').slideUp();
						return;
				    }
				    
				    var html = '<option value="">Select an estimate...</option>';
				    for (var i = 0; i< len; i++) {
				        html += '<option value="' + data.estimates[i].id + '">#' + data.estimates[i].estimate_number + ' - ' + data.estimates[i].name + '</option>';
				    }
				    $('#estimate').html(html);
				
					$('#estimates').slideDown();
				});
			} else {
				$('#estimates').slideUp();
			}
		});
		
		/**
		 * Retrieve estimate line items and generate an invoice based on a specified percentage
		 */
		function generateInvoice(estimateID, percentage) {
			$.getJSON('/cp/estimates/ajax/lineitems/', { estimate: estimateID }, function(data) {
				if (data.length > 0) {
					var project = $('#project option:selected').text();
					$('#status').val('Unsent');
					$('#invoice_description').val(percentage + '% billing for ' + project);
					// clear existing
					var table = $('#invoiceItems tbody');
					var template = table.children('tr:last');
					table.children().remove();
					for(i=0; i<data.length; i++) {
						var index = i+1;
						var tr = template;
						table.append('<tr id="row-' + i + '">' + tr.html() + '</tr>');
						
						var row = $('#row-' + i);
						row.find('.num').html(index);
						row.find('input[name="description[]"]').val(data[i].description);
						row.find('input[name="amount[]"]').val(parseFloat(data[i].amount * (percentage / 100)).toFixed(2));
					}
					updateCostTotal();
				}
			});
		}
		
		// Fill invoice with estimate lines
		$('#estimate').change(function() {
			var val = $(this).val();
			if (val) {
				$('#generate').dialog( {
					buttons: { "Generate": function() { 
								var percent = $('#percentage', this).val();
								generateInvoice(val, percent);
								$(this).dialog("close"); 
							}, "Cancel" : function() {
								$(this).dialog("close"); 
							}
					},
					modal : true,
					width : '500px'
				});
			}
		})
		
		
		// Table sorting
		$('#invoiceItems').tableDnD({
			onDrop: renumberRows
		});
	
	});
	
</script>