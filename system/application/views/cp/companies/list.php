<div id="listCompanies">

	<h1>Companies</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'companies')) : ?>
			<p><a href="<?php echo site_url('companies/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($companies) : ?>
			<table id="companiesTable">
				<thead>
					<tr>
						<th>Company Name</th>
						<th>Status</th>
						<th>Email</th>
						<th>Phone</th>
						<th></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="4">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($companies as $company) : ?>
						<tr>
							<td><?php echo $company->name; ?></td>
							<td><?php echo $company->status; ?></td>
							<td><a href="mailto:<?php echo $company->email; ?>"><?php echo $company->email; ?></a></td>
							<td><?php echo $company->phone; ?></td>
							<td class="actions span-3">
								<?php if (user_can('view', 'companies')) : ?>
									<a class="icon view company" href="<?php echo site_url('companies/view/' . $company->id); ?>">View</a>
								<?php endif; ?>
								<?php if (user_can('edit', 'companies')) : ?>
									<a class="icon edit company" href="<?php echo site_url('companies/edit/' . $company->id); ?>">Edit</a>
								<?php endif; ?>
								<?php if (user_can('delete', 'companies')) : ?>
									<a class="icon delete popup company" class="popup" href="<?php echo site_url('companies/delete/' . $company->id); ?>">Delete</a>
								<?php endif; ?>
								<?php if (user_can('view', 'contacts')) : ?>
									<a class="icon view contacts" href="<?php echo site_url('contacts/index/?company=' . $company->id); ?>">Contacts</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No companies listed.</p>
		<?php endif; ?>

	</div>
	
</div>
