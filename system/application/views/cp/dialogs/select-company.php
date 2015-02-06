<!-- begin select company -->
<p>
	<label>Company</label><br />
	<input name="company" class="text select-company" type="text" value="<?php echo $company->name; ?>" /> 
	<a href="#" class="icon inline find select-company">Find</a>
</p>

<div style="display:none;">
	<div class="company-list dialog" title="Select a Company">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-company')); ?>
		<?php if ($companies) : ?>
			<div class="list-wrap">
				<table>
					<thead>
						<tr>
							<th>Company Name</th>
						</tr>
					</thead>
					<?php foreach($companies as $c) : ?>
						<tr>
							<td><a class="select" title="<?php echo $c->name; ?>" href="#"><?php echo $c->name; ?></a></td>
						</tr>
					<?php endforeach; ?>	
				</table>	
			</div>
		<?php else : ?>
			<p>No companies found.</p>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		// display dialog
		$('.select-company').click(function() {
			$('.company-list').dialog( {
					buttons: {
						"Cancel" : function() {
							$(this).dialog("close"); 
						}
				},
				modal : true,
				resizable: false,
				width : '500px'
			});
			return false;
		});
			
		// selection of company
		$('.select', '.company-list').click(function() {
			var name = $(this).attr("title");
			$('input[name="company"]').val(name);
			$('.company-list').dialog("close");
			return false;
		});
		
		// prevent manual company input
		$('.select-company').focus(function(){
		    this.blur();
		});
	});
</script>
<!-- end select company -->