<!-- begin select project -->
<p>
	<label><?php echo $label; ?></label><br />
	<input name="project_name" class="text select-project" type="text" value="<?php echo $project->name; ?>" /> 
	<a href="#" class="icon inline find select-project">Find</a>
	<input type="hidden" name="project" value="<?php echo $project->id; ?>" />
</p>

<div style="display:none;">
	<div class="project-list dialog" title="Select a Project">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-project')); ?>
		<?php if ($projects) : ?>
			<div class="list-wrap">
				<table>
					<thead>
						<tr>
							<th>Project Number</th>
							<th>Project Name</th>
						</tr>
					</thead>
					<?php foreach($projects as $p) : ?>
						<tr>
							<td><?php echo $p->fullProjectNumber(); ?></td>
							<td>
								<a class="select" title="<?php echo $p->name; ?>" href="#<?php echo $p->id; ?>">
									<?php echo $p->name; ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>	
				</table>	
			</div>
		<?php else : ?>
			<p>No projects found.</p>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		// display dialog
		$('.select-project').click(function() {
			$('.project-list').dialog( {
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
			
		// selection of project
		$('.select', '.project-list').click(function() {
			var id = $(this).attr("href").replace("#", "");
			var name = $(this).attr("title");
			$('input[name="project_name"]').val(name);
			$('input[name="project"]').val(id);
			$('.project-list').dialog("close");
			return false;
		});
		
		// prevent manual company input
		$('.select-project').focus(function(){
		    this.blur();
		});
	});
</script>
<!-- end select company -->