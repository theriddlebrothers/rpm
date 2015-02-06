<?php if (user_can('view', 'documents')) : ?>

	<div id="tab-documents">

		<?php if ($documents) : ?>
	
			<table>
				<thead>
					<tr>
						<th class="span-2">Date</th>
						<th>Title</th>
						<th class="span-4">Type</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<?php foreach($documents as $doc) : ?>
					<tr>
						<td><?php echo date("m/d/Y", strtotime($doc->doc_date)); ?></td>
						<td><?php echo $doc->title; ?></td>
						<td><?php echo $doc->doctype->name; ?></td>
						<td>
							<?php if (user_can('view', 'documents')) : ?>
								<a class="icon view" href="<?php echo site_url('documents/view/' . $doc->id); ?>">View</a>
							<?php endif; ?>
							
							<?php if (user_can('edit', 'documents')) : ?>
								<a class="icon edit" href="<?php echo site_url('documents/edit_document/' . $doc->id); ?>">Edit</a>
								<?php if ($doc->doctype->id == DOCTYPE_REQUIREMENTS) : ?>
									<a class="icon edit-doc document" href="<?php echo site_url('documents/view/' . $doc->id); ?>">Edit</a>
								<?php endif; ?>
							<?php endif; ?>
							
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
		<?php else : ?>
			<p>No documents listed.</p>
		<?php endif; ?>
		
		
		<p class="actions">
			<?php if (user_can('view', 'documents')) : ?>
				<a href="<?php echo site_url('documents/index/?project=' . $project->id); ?>">View All</a>
			<?php endif; ?>
		</p>
		
</div>
<?php endif; ?>