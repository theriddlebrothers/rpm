<div id="listDocuments">

	<h1>Documents</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'documents')) : ?>
			<p><a id="new" href="#">Create new</a>
                            <a id="create_template" href="#">New Template</a>
                        </p>
		<?php endif; ?>
		
		<?php if ($documents) : ?>
			<table>
				<thead>
					<tr>
						
						<th>Template Name</th>
						<th>Type</th>
						<th></th>
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
					<?php foreach($documents as $doc) : ?>
                                    
						<tr>
							
							<td><?php echo $doc->title; ?></td>
							<td><?php echo $doc->doctype->name; ?></td>
							<td class="actions span-3">
								<?php if (user_can('view', 'documents')) : ?>
									<a class="icon view document" href="<?php echo site_url('documents/compile/' . $doc->id); ?>">View</a>
								<?php endif; ?>
								<?php if (user_can('edit', 'documents')) : ?>
									<a class="icon edit document" href="<?php echo site_url('documents/edit_document/' . $doc->id . '/null/1'); ?>">Edit</a>
								<?php endif; ?>
								
								<?php if ($doc->doctype->id == DOCTYPE_REQUIREMENTS) : ?>
									<?php if (user_can('edit', 'documents')) : ?>
										<a class="icon edit-doc document" href="<?php echo site_url('documents/view/' . $doc->id); ?>">Edit</a>
									<?php endif; ?>
								<?php endif; ?>
								
								<?php if (user_can('delete', 'documents')) : ?>
									<a class="icon delete popup document" class="popup" href="<?php echo site_url('documents/delete/document/' . $doc->id); ?>">Delete</a>
								<?php endif; ?>
                                                                        
                                                                <?php if (user_can('edit', 'documents')) : ?>
									<a class="icon edit document" href="<?php echo site_url('documents/create_from_template/' . $doc->id); ?>">Create from template</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No documents listed.</p>
		<?php endif; ?>

	</div>
	
</div>

<?php 

    dialog_create_document('#new'); 
    dialog_create_new_template('#create_template');
?> 