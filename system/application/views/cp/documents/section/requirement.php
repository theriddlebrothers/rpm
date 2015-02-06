<fieldset>

	<legend>Requirement Items</legend>

	<table id="requirementItems">
		<thead>
			<tr>
				<th>Function/Title</th>
				<th>Description</th>
				<th></th>
			</tr>
		</thead>	
		<tfoot>
			<tr>
				<td colspan="2">
					<a id="addMore" href="#">Add another</a>
				</td>
				<td></td>
			</tr>
		</tfoot>
		<tbody>
			<?php if ($line_items) : ?>
				<?php for($i=0; $i<sizeof($line_items); $i++) : ?>
					<tr>
						<td class="name"><input type="text" class="text" name="name[]" value="<?php echo $line_items[$i]['name']; ?>" /></td>
						<td class="description"><textarea name="description[]"><?php if ($line_items[$i]) echo $line_items[$i]['description']; ?></textarea></td>
						<td class="span-1">
							<a class="icon delete" href="#">Delete</a>
						</td>
					</tr>
				<?php endfor; ?>
			<?php else : ?>
				<tr>
                                        <td class="name"><input type="text" class="text" name="name[]" value="" /></td>
                                        <td class="description"><textarea name="description[]"></textarea></td>
                                        <td class="span-1">
                                                <a class="icon delete" href="#">Delete</a>
                                        </td>
                                </tr>
			<?php endif; ?>
		</tbody>
	</table>

</fieldset>


<script type="text/javascript">
	
	$(function() {
		// remove line item row
		$('.delete', '#requirementItems').live('click', function() {
			// don't remove all rows
			if ($('#requirementItems tbody tr').length > 1) {
				$(this).parent().parent().remove();
			}
			
			return false;
		});
		
		// Table sorting
		$('#requirementItems').tableDnD();
		
		// add row
		$('#addMore').click(function() {
			var row = $(this).parents('table').find('tbody tr:last').html();
			$(this).parents('table').children('tbody').append('<tr>' + row + '</tr>');
			$(this).parents('table').find('tbody tr:last input').val('');
			$(this).parents('table').find('tbody tr:last textarea').val('');
			
			// re-init drag/drop sorting (plugin does not use "live")
			$('#requirementItems').tableDnD();
			
			return false;
		});
	
	});
	
</script>