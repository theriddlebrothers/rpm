<div id="listEmaillogs">

	<h1>Email Logs</h1>
			
	<div class="span-24 last">
		
		<?php if ($emaillogs) : ?>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Date Sent</th>
						<th>To</th>
						<th>Success?</th>
						<th class="span-1"></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="7">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($emaillogs as $emaillog) : ?>
							<tr>
								<td><?php echo $emaillog->id; ?></td>
								<td><?php echo date("m/d/Y h:ia", strtotime($emaillog->send_date)); ?></td>
								<td><?php echo $emaillog->to; ?></td>
								<td>
									<?php if ($emaillog->success) : ?>
										<span style="color:green">Y</span>
									<?php else : ?>
										<span style="color:red">N</span>
									<?php endif; ?>
								</td>
								<td class="actions span-2">
									<a class="icon view" href="<?php echo site_url('emaillogs/view/' . $emaillog->id); ?>">View</a> 
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No emaillogs listed.</p>
		<?php endif; ?>

	</div>
	
</div>