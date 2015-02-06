<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php foreach($errors as $error) : ?>
  				<?php echo $error->string; ?>
  			<?php endforeach; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Estimate Information</legend>
	
	<p>
		<label>Estimate Name</label><br />
		<input name="name" type="text" class="title" value="<?php echo postback($estimate, 'name'); ?>" />
	</p>
	
	<p>
		<label>Estimate Date</label><br />
		<input name="estimate_date" type="text" class="datepicker text" value="<?php if (postback($estimate, 'estimate_date')) echo date("m/d/Y", strtotime(postback($estimate, 'estimate_date'))); ?>" />
	</p>
	
	<p>
		<label>Estimate Number</label><br />
		<input name="estimate_number" type="text" class="text" value="<?php echo postback($estimate, 'estimate_number'); ?>" />
	</p>

	<?php echo dialog_select_project($estimate->project); ?>
	
	<?php echo dialog_select_company($estimate->company); ?>
	
	<p>
		<label>Status</label><br />
		<select name="status">
			<option value="">Select a status...</option>
			<option <?php if ($estimate->status == 'Pending Approval') echo ' selected="selected"'; ?> value="Pending Approval">Pending Approval</option>
			<option <?php if ($estimate->status == 'Approved') echo ' selected="selected"'; ?> value="Approved">Approved</option>
			<option <?php if ($estimate->status == 'Rejected') echo ' selected="selected"'; ?> value="Rejected">Rejected</option>
		</select>
	</p>
		
</fieldset>

<fieldset>
	      	
	<legend>Estimate Content</legend>   
	
	<p>
		<textarea name="content" class="span-23 text" rows="5" cols="20"><?php echo $estimate->content; ?></textarea>
		<span class="quiet">Estimate content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
	</p>
	
</fieldset>

<fieldset>
			 	
	<legend>Estimate Budget</legend>
	
	<table id="costTable">
		<thead>
			<tr>
				<th>#</th>
				<th>Description</th>
				<th>Amount/Heading</th>
				<th>Column Type</th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"><a href="#" id="addMore">Add more</a></td>
				<td class="total">
					Total: <span id="total">$0.00</span>
				</td>
				<td colspan="2"></td>
			</tr>
		</tfoot>
		<tbody>
			<?php if ($cost_items) : ?>
				<?php for($i=0; $i<sizeof($cost_items); $i++) : ?>
					<?php $item = $cost_items[$i]; ?>
					<!-- stored items -->
					<tr>
						<td class="num"><?php echo $i+1; ?></td>
						<td><input name="cost_description[]" type="text" class="text" value="<?php echo $item['description']; ?>" /></td>
						<td><input name="cost_amountheading[]" type="text" class="text" 
							value="<?php if ($item['item_type'] == 'price') echo number_format($item['amount'], 2); else echo $item['heading']; ?>" /></td>
						<td>
							<select name="cost_item_type[]">
								<option <?php if ($item['item_type'] == 'price') echo ' selected="selected" '; ?> value="price">Price</option>
								<option <?php if ($item['item_type'] == 'heading') echo ' selected="selected" '; ?> value="heading">Heading</option>
							</select>
						</td>
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

	<legend>Terms</legend>
	<p>
		<input type="radio" name="term_type" <?php if (postback($estimate, 'term_type') == 'stored') echo 'checked="checked" '; ?> value="stored" /> 
		<select name="estimateterm">
			<option value="">Select a term...</option>
			<?php foreach($terms as $term) : ?>
				<option <?php if ($term->id == postback($estimate, 'estimateterm', 'id')) echo 'selected="selected"'; ?> value="<?php echo $term->id; ?>"><?php echo $term->name; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	
	<p>
		<input <?php if (postback($estimate, 'term_type') == 'custom') echo ' checked="checked" '; ?> type="radio" name="term_type" value="custom" /> Custom Terms
		
		<div id="customTermsPanel">
			<textarea name="custom_terms" class="span-23 text"><?php echo $estimate->custom_terms; ?></textarea>
			<span class="quiet">Estimate content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
		</div>
	</p>
	

</fieldset>

<fieldset>

	<legend>Estimate Actions</legend>
	
	<p>When this estimate is accepted:</p>
	
	<p>
		<input <?php if (postback($estimate, 'send_email_notification')) echo 'checked="checked"'; ?> type="checkbox" name="send_email_notification"/> Email a copy to <input type="text" class="text" value="<?php echo (postback($estimate, 'send_email_recipient')); ?>" name="send_email_recipient" />
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>

	
<script type="text/javascript">
	$(function() {
	
		function renumberRows() {
			var i = 0;
			$('#costTable .num').each(function() {
				i++;
				$(this).text(i);
			});
		}
		
		// remove line item row
		$('.delete', '#costTable').live('click', function() {
			// don't remove all rows
			if ($('#costTable tbody tr').length > 1) {
				$(this).parent().parent().remove();
			}
			
			renumberRows();
			updateCostTotal();
			
			return false;
		});
		
		// Add More button for costs
		$('#addMore').click(function() {
			var row = $(this).parents('table').find('tbody tr:last').html();
			$(this).parents('table').children('tbody').append('<tr>' + row + '</tr>');
			$(this).parents('table').find('tbody tr:last input').each(function() {
				$(this).val('');
			});
			renumberRows();
			$('#costTable').tableDnD({
				onDrop: renumberRows
			});


			return false;
		});
		
		// Auto-calculate costs when adding items
		$('#costTable input').live('blur', function() {
			updateCostTotal();			
		});
		
		function updateCostTotal() {
			var totalCost = 0;
			$('#costTable input[name="cost_amountheading[]"]').each(function() {
				var val = $(this).val();
				
				val = val.replace(',', '');
				var floatVal = parseFloat(val);
				if (!isNaN(floatVal)) {
					var floatAmount = parseFloat(val);
					totalCost += floatAmount;
				}
			});
			$('#total', '#costTable').html('$' + addCommas(totalCost.toFixed(2)));
		}
		updateCostTotal();
				
		// Custom Terms
		if ($('input[name="term_type"]:checked').val() == 'stored') {
			$('#customTermsPanel').hide();
		}
		
		$('input[name="term_type"]').change(function() {
			if ($(this).val() == 'custom') {
				$('#customTermsPanel').slideDown();
			} else {
				$('#customTermsPanel').slideUp();		
			}
		});
		
		// Get project info
		$('.select', '.project-list').click(function() {
			var id = $(this).attr("href").replace("#", "");
			$.getJSON('/cp/companies/ajax/project', { project : id }, function(company){
				if (company) {
					$('input[name="company"]').val(company.name);
				}
			});
		});
		
		// Table sorting
		$('#costTable').tableDnD({
			onDrop: renumberRows
		});
	});
</script>