<h1>Dashboard</h1>

<div class="span-24 last">
	
	
	
	
	<div class="tabs">
	
		<ul>
			<?php if (user_can('edit', 'companies')) : ?>
				<li><a href="#tab-leads">New Leads</a></li>
			<?php endif; ?>
			
			<?php if (user_can('view', 'activities')) : ?>
				<li><a href="#tab-activities">Latest Activities</a></li>
			<?php endif; ?>
			
			<?php if (user_can('view', 'projects')) : ?>
				<li><a href="#tab-open-projects">Open Projects</a></li>
			<?php endif; ?>
		</ul>
		
		<div id="tab-leads">
			<?php if (user_can('edit', 'companies')) : ?>
				<?php if ($leads) : ?>
					<table>
						<thead>
							<tr>
								<th>Company</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Website</th>
								<th></th>
							</tr>
						</thead>
						<tfoot>
						</tfoot>
						<tbody>
							<?php foreach($leads as $l) : ?>
								<tr>
									<td><?php echo $l->name; ?></td>
									<td><a href="mailto:<?php echo $l->email; ?>"><?php echo $l->email; ?></a></td>
									<td><?php echo $l->phone; ?></td>
									<td><a href="<?php echo $l->website; ?>"><?php echo $l->website; ?></a></td>
									<td class="actions span-2">
										<?php if (user_can('view', 'companies')) : ?>
											<a class="icon view" href="companies/view/<?php echo $l->id; ?>">View</a> 
										<?php endif; ?>
										
										<?php if (user_can('edit', 'companies')) : ?>
											<a class="icon edit" href="companies/edit/<?php echo $l->id; ?>">Edit</a> 
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p>No new business leads at this time.</p>
				<?php endif; ?>
				
			<?php endif; ?>
		</div>
		
		<?php if (user_can('view', 'activities')) : ?>
			<div id="tab-activities">
				<?php if ($leads) : ?>
					<table>
						<thead>
							<tr>
								<th class="span-4">Activity Date</th>
								<th class="span-6">Company</th>
								<th class="span-3">Type</th>
								<th>Subject</th>
								<th class="actions span-2"></th>
							</tr>
						</thead>	
						<tbody>
								<?php foreach($activities as $activity) : ?>
									<tr>
										<td><?php echo date("m/d/Y @ h:ia", strtotime($activity->activity_date)); ?></td>
										<td><?php echo $activity->company->name; ?></td>
										<td><?php echo $activity->activity_type; ?></td>
										<td><?php echo $activity->subject; ?></td>
										<td class="actions span-2">
											<?php if (user_can('view', 'activities')) : ?>
												<a class="icon view activity" href="<?php echo site_url('activities/view/' . $activity->id); ?>">View</a>
											<?php endif; ?>
											<?php if (user_can('edit', 'activities')) : ?>
												<a class="icon edit activity" href="<?php echo site_url('activities/edit/' . $activity->id); ?>">Edit</a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p>No new activities at this time.</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	
		<?php if (user_can('view', 'projects')) : ?>
			<div id="tab-open-projects">
				<p>
					<?php if ($show_retainer) : ?>
						<a href="dashboard">Hide Retainer Projects</a>
					<?php else : ?>
						<a href="dashboard/index/?show_retainer=1">Show Retainer Projects</a>
					<?php endif; ?>
				</p>
				
				<?php if ($projects) : ?>
					<table>
						<thead>
							<tr>
								<th>Project Name</th>
								<th>Project Number</th>
								<th>Estimated Amount</th>
								<th>Total Invoiced</th>
								<th>Remaining Invoice</th>
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
				<?php else : ?>
					<p>You do not have any open projects assigned to you.</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

</div>








<script type="text/javascript">
	$(function() {
	
		// Tabs
		$( ".tabs" ).tabs({
			cookie: {
				// store cookie for a day, without, it would be a session cookie
				expires: 1
			}
		});

	});
</script>