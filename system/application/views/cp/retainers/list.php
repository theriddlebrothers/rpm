<div id="listRetainers">

	<h1>Retainers</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'retainers')) : ?>
			<p><a href="<?php echo site_url('retainers/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($retainers) : ?>
			<table>
				<thead>
					<tr>
						<th>ID</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Hours</th>
                        <th class="money">Billable Rate</th>
                        <th class="money">Total Monthly</th>
						<th></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="3">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($retainers as $retainer) : ?>
							<tr>
								<td><?php echo $retainer->id; ?></td>
								<td><?php echo $retainer->company->name; ?></td>
								<td><?php echo $retainer->status; ?></td>
								<td><?php echo $retainer->hours; ?></td>
								<td class="money">$<?php echo number_format($retainer->billable_rate, 2); ?></td>
								<td class="money">$<?php echo number_format($retainer->monthlyTotal(), 2); ?></td>
								<td>
									<?php if (user_can('view', 'retainers')) : ?>
										<a class="icon view" href="<?php echo site_url('retainers/view/' . $retainer->id); ?>">View</a> 
									<?php endif; ?>
										<?php if (user_can('edit', 'retainers')) : ?>
										<a class="icon edit" href="<?php echo site_url('retainers/edit/' . $retainer->id); ?>">Edit</a>
										<?php endif; ?>
											<?php if (user_can('delete', 'retainers')) : ?>
											<a class="icon delete popup" href="<?php echo site_url('retainers/delete/' . $retainer->id); ?>">Delete</a>
											<?php endif; ?>								
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No retainers listed.</p>
		<?php endif; ?>

	</div>
	
</div>