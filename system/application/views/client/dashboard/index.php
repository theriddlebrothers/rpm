<div id="dashboard">

	<h1>Welcome!</h1>
	
	<p>You are currently logged in as <strong><?php echo $current_company->name; ?></strong>. <a href="<?php echo site_url('/welcome/index/?logout=true'); ?>">Logout</a></p>
	
	<h2>Current Projects</h2>
	
	<?php if (user_can('view', 'projects')) : ?>
		<?php if ($projects) : ?>
			<table>
				<thead>
					<tr>
						<th>Project Name</th>
						<th>Project Number</th>
						<th>Estimated Amount</th>
						<th>Total Invoiced</th>
						<th>Remaining Invoice*</th>
						<th>Last Invoiced</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th>$<?php echo number_format($total_estimated, 2); ?></th>
						<th>$<?php echo number_format($total_invoiced, 2); ?></th>
						<th>$<?php echo number_format($remaining_invoice, 2); ?></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($projects as $p) : ?>
						<tr>
							<td><?php echo truncate($p->name, 25); ?></td>
							<td><?php echo $p->fullProjectNumber(); ?></td>
							<td>$<?php echo number_format($p->getTotalAcceptedEstimated(), 2); ?></td>
							<td>$<?php echo number_format($p->getTotalInvoiced(), 2); ?></td>
							<td>$<?php echo number_format($p->getRemainingInvoice(), 2); ?></td>
							<td><?php if ($p->getLastInvoice()->invoice_date) echo date("m/d/Y", strtotime($p->getLastInvoice()->invoice_date)); ?></td>
							<td class="actions span-2">
								<?php if (user_can('view', 'projects')) : ?>
									<a class="icon view" href="projects/view/<?php echo $p->id; ?>">View</a> 
								<?php endif; ?>
								
								<?php if (user_can('edit', 'projects')) : ?>
									<a class="icon edit" href="projects/edit/<?php echo $p->id; ?>">Edit</a> 
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p><small>* Remaining Invoice amounts do not include retainer projects that are billed on an hourly basis.</small></p>
		<?php else : ?>
			<p>You do not have any open projects.</p>
		<?php endif; ?>
	<?php endif; ?>
	
</div>