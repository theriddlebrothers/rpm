<div id="listContacts">

	<h1><?php echo $company->name; ?> Contacts</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'contacts')) : ?>
			<p><a href="<?php echo site_url('contacts/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($contacts) : ?>
			<table>
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Company</th>
						<th>Email</th>
						<th></th>
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
						<?php foreach($contacts as $contact) : ?>
							<tr>
								<td><?php echo $contact->first_name; ?></td>
								<td><?php echo $contact->last_name; ?></td>
								<td><?php echo $contact->company->name; ?></td>
								<td><?php echo $contact->email; ?></td>
								<td class="actions span-2">
									<?php if (user_can('view', 'contacts')) : ?>
										<a class="icon view" href="<?php echo site_url('contacts/view/' . $contact->id); ?>">View</a>
									<?php endif; ?>
									<?php if (user_can('edit', 'contacts')) : ?>
										<a class="icon edit" href="<?php echo site_url('contacts/edit/' . $contact->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'contacts')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('contacts/delete/' . $contact->id); ?>">Delete</a> 
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
		</table>	
		<?php else : ?>
			<p>No contacts listed.</p>
		<?php endif; ?>			
	
	</div>
	
</div>