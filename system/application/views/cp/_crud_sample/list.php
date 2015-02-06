<div id="listProjects">

	<h1>Projects</h1>
			
	<div class="span-24 last">
		
		<p><a href="<?php echo site_url('projects/create'); ?>">Create new</a></p>
		
		<?php if ($projects) : ?>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Project Name</th>
						<th></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="3">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($projects as $project) : ?>
							<tr>
								<td><?php echo $project->id; ?></td>
								<td><?php echo $project->name; ?></td>
								<td>
									<a class="icon view" href="<?php echo site_url('projects/view/' . $project->id); ?>">View</a> 
									<a class="icon edit" href="<?php echo site_url('projects/edit/' . $project->id); ?>">Edit</a>
									<a class="icon delete popup" href="<?php echo site_url('projects/delete/' . $project->id); ?>">Delete</a>								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No projects listed.</p>
		<?php endif; ?>

	</div>
	
</div>