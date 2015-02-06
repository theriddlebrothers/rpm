<!-- begin select folder -->
<p>
	<label><?php echo $label; ?></label><br />
	<input name="dropbox_folder" type="text" class="text" value="<?php echo $path; ?>" />
	<a href="#" class="icon inline find select-folder">Find</a>
	<br />
	<span class="quiet">/Dropbox/Shared/Clients/<?php echo $root; ?>[folder-name]</span>
</p>

<div style="display:none;">
	<div class="folder-list dialog" title="Select a Folder">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-folders')); ?>
		<?php if ($projects) : ?>
			<div class="list-wrap">
				<table id="files">
				<thead>
					<tr>
						<th>Folder Name</th>
						<th class="span-1">Select</th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<tr><td colspan="3">Loading files...</td></tr>
				</tbody>
			</table>	
			</div>
		<?php else : ?>
			<p>No folders found.</p>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		
		loadFiles('');
		
		function loadFiles(path) {
			Dropbox.getFolders('<?php echo $root; ?>', path, function(response) {
				var html = Dropbox.getFolderDisplay(response.files, path, response.is_root);
				$('#files tbody').html(html);
			});
		}
		
		// display dialog
		$('.select-folder').click(function() {
			$('.folder-list').dialog( {
					buttons: {
						"Cancel" : function() {
							$(this).dialog("close"); 
						}
				},
				modal : true,
				resizable: false,
				width : '600px'
			});
			return false;
		});
		
		// open folder
		$('#files a.open').live('click', function() {
			var url = $(this).attr("href").replace("#", "");
			loadFiles(url);
			if (url.length == 0) return false;
		});
		
		// selection of folder
		$('.select', '.folder-list').live('click', function() {
			var path = $(this).attr("href").replace("#", "");
			path = path.replace("<?php echo $root; ?>", "");
			$('input[name="dropbox_folder"]').val(path);
			$('.folder-list').dialog("close");
			return false;
		});
		
		// prevent manual company input
		$('.select-project').focus(function(){
		    this.blur();
		});
	});
</script>
<!-- end select company -->