<div id="listTerms">

	<h1>Estimate Terms</h1>
			
	<div class="span-24 last">
		
		<p><a href="<?php echo site_url('estimateterms/create'); ?>">Create new</a></p>
		
		<?php if ($terms) : ?>
			<table>
				<thead>
					<tr>
						<th>Term Name</th>
						<th class="span-2"></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="2">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($terms as $term) : ?>
							<tr>
								<td><?php echo $term->name; ?></td>
								<td class="actions span-3">
									<?php if (user_can('view', 'estimateterms')) : ?>
										<a class="icon view" href="<?php echo site_url('estimateterms/view/' . $term->id); ?>">View</a>
									<?php endif; ?> 
									<?php if (user_can('edit', 'estimateterms')) : ?>
										<a class="icon edit" href="<?php echo site_url('estimateterms/edit/' . $term->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'estimateterms')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('estimateterms/delete/' . $term->id); ?>">Delete</a>
									<?php endif; ?>
									</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No terms listed.</p>
		<?php endif; ?>

	</div>
	
</div>