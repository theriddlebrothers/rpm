<div id="reports">

	<h1>Reports</h1>

	<h3>Invoices</h3>

	<ul>
		<li>
			<a href="<?php echo site_url('invoices/index/?status=Unpaid'); ?>">Unpaid Invoices</a>
		</li>
	</ul>
	
	<h3>Time Logs</h3>
	<ul>
		<li>
			<a href="<?php echo site_url('reports/timelogs_monthly'); ?>">Current Monthly Timelog Report</a>
		</li>
		<li>
			<a href="<?php echo site_url('reports/timelogs_monthly/?when=last_month'); ?>">Last Month's Timelog Report</a>
		</li>
		<li>
			<a href="<?php echo site_url('reports/timelogs'); ?>">Time Log Report</a>
		</li>
	</ul>
			
</div>