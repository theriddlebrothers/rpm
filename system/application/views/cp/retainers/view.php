<div id="retainer">

	<h1><?php echo $retainer->project->company->name; ?> Retainer</h1>
		
	<?php if (user_can('create', 'retainers')) : ?>
		<p>
			<a href="<?php echo site_url('retainers/edit/' . $retainer->id); ?>">Edit</a> |
			<a href="<?php echo site_url('/client/retainers/view/' . $retainer->id); ?>">View Client Retainer</a>
		</p>	
	<?php endif; ?>
	
	<p class="actions">
		<button id="print" class="printhide secondary button">
			Print		
		</button>
	</p>
	
	<div class="span-23 last">
	
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
	</div>
	
        <div id="terms" class="span-24 last">
            <fieldset>
                <legend>Terms</legend>
                <?php echo $retainer->terms; ?>
            </fieldset>
        </div>
        

        <fieldset>

                <legend>Retainer Actions</legend>

                
                <?php if ($retainer->send_email_notification) : ?>
                    
                    <p>When this estimate is accepted:</p>

                    <p><input type="checkbox" checked="checked" /> Email a copy to <strong><?php echo $retainer->send_email_recipient; ?></strong></p>
               
                <?php else : ?>
                        <p>No action set.</p>
                <?php endif; ?>
                        

        </fieldset>	
</div>
<script type="text/javascript">
	$(function() {
		$('#print').click(function() {
			window.print();
		});
	});
</script>
