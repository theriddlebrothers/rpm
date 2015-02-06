<div id="estimate">

	<h1><?php echo $retainer->name; ?></h1>
  		
	<fieldset>
		<legend>Retainer Information</legend>
		
		<table>
			<tr>
				<th>Hours</th>
				<td><?php echo $retainer->hours; ?></td>
			</tr>
			<tr>
				<th>Hourly Rate</th>
				<td>$<?php echo number_format($retainer->billable_rate, 2); ?></td>
			</tr>
			<tr>
				<th>Per Month</th>
				<td>$<?php echo number_format($retainer->monthlyTotal(), 2); ?></td>
			</tr>
			<tr>
				<th>Status</th>
				<td><?php echo $retainer->status; ?></td>
			</tr>
			<tr>
				<th>Start Date</th>
				<td><?php if ($retainer->start_date) echo date("m/d/Y", strtotime($retainer->start_date)); ?></td>
			</tr>
			<tr>
				<th>End Date</th>
				<td><?php if ($retainer->end_date) echo date("m/d/Y", strtotime($retainer->end_date)); ?></td>
			</tr>
		</table>
	
	</fieldset>

    <fieldset>
        <legend>Terms</legend>
        <?php echo $retainer->terms; ?>
    </fieldset>

	
	<fieldset>
	
		<legend>Estimate Approval</legend>
	
		<p>Agreed to by: <strong><?php echo $retainer->signature; ?></strong></p>

		<p>Signed electronically on <?php echo date("m/d/Y @ H:i:s", strtotime($retainer->signature_date)); ?> from IP address <?php echo $retainer->signature_ip; ?>.</p>
		
	</fieldset>
	
</div>