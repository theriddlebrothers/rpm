<!-- begin create document-->
<div style="display:none;"> 
	<div class="create-template dialog" title="Create New Template">
            
            
            <h4>Select a template type</h4>
		<ul>
			<li>
				<a class="creative" href="<?php echo site_url('/cp/documents/create/2/1'); ?>">
					Creative Brief
				</a>
			</li>
			<li>
				<a class="requirements" href="<?php echo site_url('/cp/documents/create/1/1'); ?>">
					Requirements Specification
				</a>
			</li>
		</ul>
            
           
	</div>
</div>

<script type="text/javascript">
	$(function() {
		
		// create new document
		$('<?php echo $element; ?>').click(function() {
			$('.create-template').dialog( {
					buttons: {
						"Cancel" : function() {
							$(this).dialog("close"); 
						}
				},
				modal : true,
				resizable: false,
				width : '500px'
			});
		});
		
	});
</script>
<!-- end create document -->