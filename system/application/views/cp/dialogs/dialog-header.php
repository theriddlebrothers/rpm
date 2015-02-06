<div class="dialog-header">
	<div class="search">
		<label>Search: </label> 
		<input type="text" id="<?php echo $id; ?>" class="text" value="" />
	</div>
</div>
<script type="text/javascript">
	$(function() {
		/* Search table for rows containing value */
		$('#<?php echo $id; ?>').keyup(function() {
			var search = $(this).val();
			
			var table = $(this).parent().parent().parent().find('.list-wrap tbody');
			
			// if no search term, show all rows
			if (search.length == 0) {
				$('tr', table).show();
				return;
			}
			
			// hide all rows
			$('tr', table).hide();
			
			// show matching rows
			var pattern = new RegExp(search, "i");
			
			$('tr', table).each(function(el) {
				// search each cell contents for match
				var row = $(this);
				var cell = $('td', row).text();
				if (pattern.test(cell)) {
					$(row).show();
				}
			});
		});
	});
</script>