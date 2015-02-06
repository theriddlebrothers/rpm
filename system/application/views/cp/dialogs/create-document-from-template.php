<!-- begin create document-->
<div style="display:none;">
	<div class="new-document-from-template dialog" title="Create a New Document From Template">
            
            <h4>Select a template</h4>
		<ul>
			<li>
				<a class="creative" href="<?php echo site_url('/cp/documents/create/2'); ?>">
					Creative Brief
				</a>
			</li>
			<li>
				<a class="requirements" href="<?php echo site_url('/cp/documents/create/1'); ?>">
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
			$('.new-document-from-template').dialog( {
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