<?php if (user_can('view', 'estimates')) : ?>
				
	<div id="tab-estimates">
		<?php if ($project->estimate->all) : ?>
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Estimate Name</th>
						<th class="span-3">Status</th>
						<th class="span-3 money">Total Billed</th>
						<th class="span-3 money">Amount</th>
						<th class="span-2"></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th colspan="4">Total</th>		
						<th class="money">$<?php echo number_format($project->getTotalEstimated(), 2); ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($project->estimate->all as $estimate) : ?>
						<tr>
							<td class="span-2"><?php echo date("m/d/Y", strtotime($estimate->estimate_date)); ?></td>
							<td><?php echo $estimate->name; ?></td>
							<td><?php echo $estimate->status; ?></td>
							<td class="money">$<?php echo number_format($estimate->getTotalBilled(), 2); ?></td>
							<td class="money">$<?php echo number_format($estimate->getTotalEstimated(), 2); ?></td>
							<td>
								<?php if (user_can('view', 'invoices')) : ?>
									<a class="icon view" href="<?php echo site_url('estimates/view/' . $estimate->id); ?>">View</a>
								<?php endif; ?>
								
								<?php if (user_can('edit', 'invoices')) : ?>
									<a class="icon edit" href="<?php echo site_url('estimates/edit/' . $estimate->id); ?>">Edit</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p>No estimates listed. </p>
		<?php endif; ?>
		
		<p class="actions">
			<?php if (user_can('view', 'estimates')) : ?>
				<a href="<?php echo site_url('estimates/index/?project=' . $project->id); ?>">View All</a>
			<?php endif; ?>
			
			<?php if (user_can('create', 'estimates')) : ?>
				<a href="<?php echo site_url('estimates/create/?project=' . $project->id . '&company=' . urlencode($project->company->name)); ?>">Create new</a>
			<?php endif; ?>
		</p>
			
	</div>
<?php endif; ?>