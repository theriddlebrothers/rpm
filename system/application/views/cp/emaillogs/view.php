<div id="emailLog">

	<h1>Email Log ID <?php echo $emaillog->id; ?></h1>
	
	<div class="span-24 last">
	
		<fieldset>
			<legend>Log Information</legend>
			
			<table>
				<tr>
					<th>Date Sent</th>
					<td><?php echo date("m/d/Y h:ia", strtotime($emaillog->send_date)); ?></td>
				</tr>
				<tr>
					<th>To</th>
					<td><?php echo $emaillog->to; ?></td>
				</tr>
				<tr>
					<th>CC</th>
					<td><?php echo $emaillog->cc; ?></td>
				</tr>
				<tr>
					<th>BCC</th>
					<td>
						<?php echo $emaillog->bcc; ?>
					</td>
				</tr>
				<tr>
					<th>Log Dump</th>
					<td>
						<div class="overflowBox">
							<?php echo $emaillog->dump; ?>
						</div>
					</td>
				</tr>
			</table>
		
		</fieldset>
	</div></div>
