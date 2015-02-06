<?php if (user_can('view', 'invoices')) : ?>
						
	<div id="tab-invoices">
	
	<?php if ($project->invoice->all) : ?>
		<table>
			<thead>
				<tr>
					<th class="span-2">Date</th>
					<th>Description</th>
					<th class="span-3 money">Amount</th>
					<th class="span-2"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="2">Total</th>
					<th class="money">$<?php echo number_format($invoice_totals, 2); ?></th>
					<th></th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach($project->invoice->all as $invoice) : ?>
				<tr>
					<td><?php echo date("m/d/Y", strtotime($invoice->invoice_date)); ?></td>
					<td><?php echo $invoice->description; ?></td>
					<td class="money">$<?php echo number_format($invoice->getTotal(), 2); ?></td>
					<td>
						<?php if (user_can('view', 'invoices')) : ?>
							<a class="icon view" href="<?php echo site_url('invoices/view/' . $invoice->id); ?>">View</a>
						<?php endif; ?>
						
						<?php if (user_can('edit', 'invoices')) : ?>
							<a class="icon edit" href="<?php echo site_url('invoices/edit/' . $invoice->id); ?>">Edit</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php else : ?>
			<p>No invoices to list.</p>
		<?php endif; ?>
		
		<p class="actions">
			<?php if (user_can('view', 'invoices')) : ?>
				<a href="<?php echo site_url('invoices/index/?project=' . $project->id); ?>">View All</a>
			<?php endif; ?>
			
			<?php if (user_can('create', 'invoices')) : ?>
				<a href="<?php echo site_url('invoices/create/?project=' . $project->id . '&company=' . urlencode($project->company->name)); ?>">Create new</a>
			<?php endif; ?>
		</p>
		
	</div>

<?php endif; ?>