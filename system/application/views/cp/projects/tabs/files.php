<?php if (user_can('view', 'files')) : ?>
	
	<div id="tab-files">
		
		<?php if ($files) : ?>
		
			<table id="files">
				<thead>
					<tr>
						<th class="span-4">File Date</th>
						<th>File Name</th>
						<th class="alignright span-3">File Size</th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<tr><td colspan="3">Loading files...</td></tr>
				</tbody>
			</table>
			
		<?php else : ?>
			<p>No files listed.</p>
		<?php endif; ?>
		
	</div>
	
	<script type="text/javascript">
		$(function() {
			
			function loadProjectFiles(path, projectID) {
				Dropbox.getProjectFiles(path, projectID, function(response) {
					var html = Dropbox.getFileDisplay(projectID, response.files, path, response.is_root);
					$('#files tbody').html(html);
				});
			}
			
			$('#files a.open').live('click', function() {
				var url = $(this).attr("href").replace("#", "");
				loadProjectFiles(url, '<?php echo $project->id; ?>');
				if (url.length == 0) return false;
			});
			
			var path = '';
			if (window.location.hash) {
				path = window.location.hash.substring(1);
				$('#project-tabs').tabs().tabs('select', '#tab-files');
			}
			loadProjectFiles(path, '<?php echo $project->id; ?>');
			
			
		});
	</script>
	
<?php endif; ?>