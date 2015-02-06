<div id="retainer">

	<h1><?php echo $retainer->company->name; ?> Retainer</h1>
		
	<p class="actions printhide">
		<button id="print" class="secondary button">
			Print		
		</button>
		<a href="<?php echo site_url('retainers/download/' . $retainer->id); ?>" class="secondary button">
			Download as PDF		
		</a>
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
    	
		<legend>Retainer Approval</legend>
		
		<?php if ($retainer->status == 'Approved') : ?>
			
			<p>Agreed to by: <strong><?php echo $retainer->signature; ?></strong></p>

			<p>Signed electronically on <?php echo date("m/d/Y @ H:i:s", strtotime($retainer->signature_date)); ?> from IP address <?php echo $retainer->signature_ip; ?>.</p>
			
		<?php elseif($retainer->status == 'Pending Approval') : ?>

			<form action="" method="post">
				<div class="printhide">
					<p>Fill in your full name below and click "I agree to these terms" to accept the terms of this agreement. By clicking "Accept" you hereby to the terms outlined above.</p>
				
					<p>
						<label>Agreed to by:</label><br /> 
				
						<input type="text" class="text" name="signature" /> from IP address <?php echo $this->input->ip_address(); ?>
					</p>
				
					<p>If you do not want to sign this agreement electronically you may <a href="javascript:window.print();">print this page</a>. A signature line will be available on the last page of the agreement.</p>
						
					<button type="submit" class="button primary" name="submit">I agree to these terms</button>
				</div>
				<div class="printonly">
					<p>
						Agreed to by: ___________________________________________<br />
						<br />
						Printed name: ___________________________________________<br />
						<br />
						Date: ___________________________________________________
					</p>
				</div>		
			</form>		
		<?php else : ?>
			<p>This retainer has expired or has been rejected.</p>
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