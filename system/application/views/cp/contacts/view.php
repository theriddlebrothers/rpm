<div id="company">

	<h1><?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?></h1>
		
	<p><a href="<?php echo site_url('contacts/edit/' . $contact->id); ?>">Edit</a></p>	
	
	<div class="span-12">
	
		<fieldset>
			<legend>Contact Information</legend>
			
			<table>
				<tr>
					<th>Email</th>
					<td><?php echo $contact->email; ?></td>
				</tr>
				<tr>
					<th>Phone</th>
					<td><?php echo $contact->phone; ?></td>
				</tr>
				<tr>
					<th>Fax</th>
					<td><?php echo $contact->fax; ?></td>
				</tr>
				<tr>
					<th>Address</th>
					<td>
						<?php echo $contact->getAddress(); ?>
					</td>
				</tr>
				<tr>
					<th>Website</th>
					<td><?php echo $contact->website; ?></td>
				</tr>
			</table>
		
		</fieldset>
	</div>
	
	<div class="span-12 last">
		
		<fieldset>
			<legend>Company Information</legend>	
			
			<?php if ($contact->company->all) : ?>
				<table>
					<tr>
						<th>Phone</th>
						<td><?php echo $contact->company->phone; ?></td>
					</tr>
					<tr>
						<th>Fax</th>
						<td><?php echo $contact->company->fax; ?></td>
					</tr>
					<tr>
						<th>Address</th>
						<td>
							<?php echo $contact->company->getAddress(); ?>
						</td>
					</tr>
					<tr>
						<th>Website</th>
						<td><?php echo $contact->company->website; ?></td>
					</tr>
				</table>
			<?php else : ?>
				<p>This contact does not have an associated company.</p>
			<?php endif; ?>			
		</fieldset>
	
	</div>
</div>
