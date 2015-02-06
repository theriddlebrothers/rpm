<!-- begin select project -->
<p>
	<label><?php echo $label; ?></label><br />
	<a href="#" class="icon inline find select-project">Find</a>
</p>

<div style="display:none;">
    
    <?php var_dump(); ?>
	<div class="project-list dialog" title="Select a Template">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-templates')); ?>
		<?php if ($templates) : ?>
			<div class="list-wrap">
				<table>
					<thead>
						<tr>
							<th>Template Name</th>
							<th>Template Type</th>
						</tr>
					</thead>
					<?php foreach($templates as $t) : ?>
						<tr>
							<td><?php echo $t->fullProjectNumber(); ?></td>
							<td>
								<a class="select" title="<?php echo $t->name; ?>" href="#<?php echo $t->id; ?>">
									<?php echo $t->name; ?>
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
			$('input[name="template_name"]').val(name);
			$('input[name="template"]').val(id);
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