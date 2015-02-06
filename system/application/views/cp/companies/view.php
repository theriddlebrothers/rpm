<div id="company">

	<h1><?php echo $company->name; ?></h1>
		
	<?php if (user_can('edit', 'companies')) : ?>
		<p><a href="<?php echo site_url('companies/edit/' . $company->id); ?>">Edit</a></p>
	<?php endif; ?>	
		
	<div class="span-12">
	
		<fieldset>
			<legend>Contact Information</legend>
			
			<table>
				<tr>
					<th>Phone</th>
					<td><?php echo $company->phone; ?></td>
				</tr>
				<tr>
					<th>Fax</th>
					<td><?php echo $company->fax; ?></td>
				</tr>
				<tr>
					<th>Address</th>
					<td>
						<?php echo $company->getAddress(); ?>
					</td>
				</tr>
				<tr>
					<th>Website</th>
					<td><?php echo $company->website; ?></td>
				</tr>
				<tr>
					<th class="span-3">View Key 
						<a class="icon help floatright" href="#viewkeyHelp">View Key Help</a>
					
						<div id="viewkeyHelp" class="helpPanel">
							
							<p>This link will allow a visitor to access all documents associated with their company including estimates, invoices, projects, files, and time log reports. Do not give this link out to anyone that should not have full access to the company's files!</p>
							
							<p>You may also add a URL to the end of the view link and redirect the user to a specific page after authenticating the View Key.</p>
							
							<p><span class="quiet">Example: <br /><a href="<?php echo $company->getViewLink('/client/estimates/'); ?>"><?php echo $company->getViewLink('/client/estimates/'); ?></a></span></p>

						</div>
					
					</th>
					<td>
						<a href="<?php echo $company->getViewLink(); ?>"><?php echo $company->getViewLink(); ?></a>
					</td>
				</tr>
			</table>
		
		</fieldset>
	</div>
	
	<?php if (user_can('view', 'contacts')) : ?>
	<div class="span-12 last">
		
		<fieldset>
			
			<legend>Contacts</legend>
			
			<?php if ($company->contact->all) : ?>
			
				<table>
					<thead>
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($company->contact->order_by('last_name')->get()->all as $contact) : ?>
							<tr>
								<td><?php echo $contact->first_name; ?></td>
								<td><?php echo $contact->last_name; ?></td>
								<td><?php echo $contact->email; ?></td>
								<td><?php echo $contact->phone; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			
			<?php else : ?>
				<p>No contacts listed.</p>
			<?php endif; ?>
			
			<p class="actions">
				<?php if (user_can('create', 'contacts')) : ?>
					<a href="<?php echo site_url('contacts/create/?company=' . $company->id); ?>">Create new</a>
				<?php endif; ?>
				<?php if (user_can('view', 'contacts')) : ?>
					<a href="<?php echo site_url('contacts/company/' . $company->id); ?>">Manage</a>
				<?php endif; ?>
			</p>
			
		</fieldset>
		
	</div>
	<?php endif; ?>
	
	
	<?php if (user_can('view', 'activities')) : ?>
	<div class="span-24 last">
		
		<fieldset>
			
			<legend>Acitivities</legend>
			
			<?php if ($company->activities->all) : ?>
			
				<table>
					<thead>
						<tr>
							<th class="span-4">Date</th>
							<th class="span-3">Type</th>
							<th>Subject</th>
							<th class="actions span-2"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($company->activities->all as $acitivity) : ?>
							<tr>
								<td><?php echo date("m/d/Y h:ia", strtotime($acitivity->activity_date)); ?></td>
								<td><?php echo $acitivity->activity_type; ?></td>
								<td><?php echo $acitivity->subject; ?></td>
								<td>
									<?php if (user_can('view', 'activities')) : ?>
										<a class="icon view activity" href="<?php echo site_url('activities/view/' . $acitivity->id); ?>">View</a>
									<?php endif; ?>
									<?php if (user_can('edit', 'activities')) : ?>
										<a class="icon edit activity" href="<?php echo site_url('activities/edit/' . $acitivity->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'activities')) : ?>
										<a class="icon delete popup activity" class="popup" href="<?php echo site_url('activities/delete/' . $acitivity->id); ?>">Delete</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			
			<?php else : ?>
				<p>No activities listed.</p>
			<?php endif; ?>
			
			<p class="actions">
				<?php if (user_can('create', 'activities')) : ?>
					<a href="<?php echo site_url('activities/create/?company=' . $company->id); ?>">Create new</a>
				<?php endif; ?>
				<?php if (user_can('view', 'activities')) : ?>
					<a href="<?php echo site_url('activities/?company=' . $company->id); ?>">Manage</a>
				<?php endif; ?>
			</p>
			
		</fieldset>
		
	</div>
	<?php endif; ?>
	
	<?php if (trim($company->info)) : ?>
	<div class="span-24 last">
		<fieldset>
			<legend>Additional Information</legend>
			<?php echo $company->info; ?>
		</fieldset>
	</div>
	<?php endif; ?>

</div>
