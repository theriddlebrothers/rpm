<div id="listProjects">

	<h1>Projects</h1>
			
	<form action="<?php echo site_url('cp/projects/index'); ?>/" method="get">
	
		<div class="span-24 last">
			
			<?php $this->load->view('cp/projects/filter'); ?>
			
			<div class="span-24 last">
				
				<?php if ($projects) : ?>
					<table>
						<thead>
							<tr>
								<th>Project Number</th>
								<th>Project Name</th>
								<th>Company</th>
								<th>Status</th>
								<th class="span-2"></th>
							</tr>
						</thead>	
						<tfoot>
							<tr>
								<td colspan="5">
									<?php echo $pager; ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
								<?php foreach($projects as $project) : ?>
									<tr>
										<td><?php echo $project->fullProjectNumber(); ?></td>
										<td><?php echo $project->name; ?></td>
										<td><?php echo $project->company->name; ?></td>
										<td><?php echo $project->status; ?></td>
										<td class="actions span-2">
											<?php if (user_can('view', 'projects')) : ?>
												<a class="icon view" href="<?php echo site_url('projects/view/' . $project->id); ?>">View</a> 

											<?php endif; ?>
											
											<?php if (user_can('edit', 'projects')) : ?>
												<a class="icon edit" href="<?php echo site_url('projects/edit/' . $project->id); ?>">Edit</a> 
											<?php endif; ?>
											
											<?php if (user_can('delete', 'projects')) : ?>
											<a class="icon delete popup" href="<?php echo site_url('projects/delete/' . $project->id); ?>">Delete</a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>				
			
				<?php else : ?>
					<p>No projects listed.</p>
				<?php endif; ?>

			</div>
					
		</div>
	
	</form>
	
</div>



<script type="text/javascript">
	
	$(function() {
	
		$('#showFilter').click(function () {
			$('#filters').slideDown();
			return false;
		});
	

		$('#company').autocomplete('/cp/companies/ajax/search');
		
	});
	
</script>