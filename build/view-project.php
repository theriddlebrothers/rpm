<?php include('header.php'); ?>

<div id="viewProject">

	<form>
		
		<h1>Acme, Inc. Website <span id="projectNumber">(10-RID-003)</span></h1>
			
		<div id="projectHeader" class="span-24 last">
			<div class="span-12">
				<h3>Project Information</h3>
				<p>Company: Acme, Inc.</p>
				<p>Status: Pending Approval</p>
				<p>Target Start Date: 12/15/2010</p>
				<p>Target Start Date: 03/15/2011</p>
			</div>
			<div class="span-12 last">
				<h3>Project Description</h3>
				
				<div id="projectDescription">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vehicula cursus nibh, sit amet tristique sem tempus nec. Duis turpis diam, porta et egestas non, iaculis at mauris. Nullam massa dui, placerat et commodo sit amet, mollis cursus quam. Praesent scelerisque suscipit dolor, ornare elementum augue tempus quis. Nulla bibendum ullamcorper mi. Sed nec erat at sem porttitor egestas. Sed congue accumsan tortor sit amet sagittis. Sed nec nibh quis magna molestie facilisis. Suspendisse sem eros, aliquam vel tempus porttitor, volutpat sit amet nisi. Sed felis velit, semper nec fermentum id, faucibus sit amet nibh.Duis interdum libero non risus pellentesque ac fermentum odio pretium. Sed sit amet libero justo.</p>
					<p>Proin facilisis purus sed velit rutrum ullamcorper mattis turpis feugiat. Duis ut quam tellus, sit amet suscipit dui. Donec quis justo ut mauris rhoncus elementum. Suspendisse neque turpis, pellentesque et elementum non, imperdiet ut lectus. In orci nisi, placerat eget fringilla eget, pretium id felis. Suspendisse eget interdum felis. Vestibulum vehicula suscipit metus, quis faucibus risus eleifend a. Vivamus lectus sem, interdum sed malesuada a, blandit vel tortor. Praesent vitae risus eu libero sollicitudin sodales ac eu nulla. Mauris eros libero, rhoncus at viverra et, hendrerit et diam. Mauris malesuada ultricies dolor, sed semper metus varius nec. Integer luctus posuere lectus et fringilla. Nullam odio nisi, volutpat non ultricies vel, faucibus in enim. Etiam viverra nunc erat, a eleifend dui. Nullam condimentum metus eget magna viverra hendrerit. Maecenas volutpat nisi scelerisque nisl cursus tristique. Nullam arcu quam, venenatis eget malesuada in, mollis vel sem.Nam faucibus porta dolor id placerat.</p>
					<p>Donec et auctor velit. Vivamus sodales fringilla dui, auctor sollicitudin nunc pharetra non. Etiam id tincidunt tellus. Vivamus molestie, ante eu lacinia scelerisque, ipsum elit vulputate lectus, ut varius ante nisl eget orci. Aliquam porta est vitae nunc lobortis venenatis. Curabitur ante magna, sollicitudin in vulputate sit amet, egestas vel leo. Fusce sapien ante, auctor eget sollicitudin non, volutpat non erat. Nulla quis dui sem, convallis semper purus. Nunc ut sem vitae odio sollicitudin ultricies. Ut a ipsum eget dui elementum aliquet non fringilla ligula. Aenean eu nulla nec nisi tristique feugiat at ut massa. Curabitur et arcu ipsum, eget commodo nisi. Proin rhoncus sem laoreet urna placerat facilisis. Nullam porta egestas sem. Praesent tempor felis nunc. Integer nulla nisi, blandit in consectetur in, fringilla a enim.</p>
				</div>
			</div>
		</div>
		
		<div class="span-24">
		
			<fieldset>
			
				<legend>Recent Time Logs</legend>
				
				<table>
					<thead>
						<tr>
							<th>Date</th>
							<th>User</th>
							<th>Description</th>
							<th>Time (h:m)</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="3">Total</th>
							<th>03:30</th>
							<th></th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>12/01/2010</td>
							<td>Joshua Riddle</td>
							<td>Fixed bugs in IE7, revised totals to include state taxes, added categories list to blog.</td>
							<td>02:30</td>
						</tr>
						<tr>
							<td>12/04/2010</td>
							<td>Aaron Riddle</td>
							<td>Design work for homepage comp.</td>
							<td>01:30</td>
						</tr>
					</tbody>
				</table>
				
				<a href="#">Create new</a>
			
			</fieldset>
			
		</div>
		
		<div class="span-12">

			<fieldset>
				
				<legend>Files</legend>
				
				<p>To manage files, install <a href="#">Dropbox</a> using the Riddle Brothers account.</p>
				
				<table>
					<thead>
						<tr>
							<th>File Date</th>
							<th>File Name</th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
					</tfoot>
					<tbody>
						<tr>
							<td>12/10/2010 09:00pm</td>
							<td>Phase 1 Production Schedule.pdf</td>
							<td><a href="#">Download</a></td>
						</tr>
						<tr>
							<td>12/12/2010 09:00pm</td>
							<td>Phase 1 Requirements.pdf</td>
							<td><a href="#">Download</a></td>
						</tr>
					</tbody>
				</table>
				
			</fieldset>

		</div>
		
		
		<div class="span-12 last">

			<fieldset>
				
				<legend>Tasks</legend>
				
				<table>
					<thead>
						<tr>
							<th>Due Date</th>
							<th>Title</th>
							<th>Assigned To</th>
							<th>Status</th>
						</tr>
					</thead>
					<tfoot>
					</tfoot>
					<tbody>
						<tr>
							<td>12/10/2010</td>
							<td>Fix IE6 bugs</td>
							<td>Joshua Riddle</td>
							<td>In Progress</td>
						</tr>
						<tr>
							<td>12/12/2010</td>
							<td>Deploy to Production</td>
							<td>Joshua Riddle</td>
							<td>Pending</td>
						</tr>
						<tr>
							<td>12/25/2010</td>
							<td>Send retainer billing reports</td>
							<td>Aaron Riddle</td>
							<td>Pending</td>
						</tr>
					</tbody>
				</table>
				
				<a href="/task.php">Create new</a>
				
			</fieldset>

		</div>
		
		<div class="span-12">
			<fieldset>
				
				<legend>Estimates</legend>
				
				<table>
					<thead>
						<tr>
							<th>Date</th>
							<th>Estimate Name</th>
							<th>Status</th>
							<th>Total Billed</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="4">Total</th>		
							<th>$5,100.00</th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>12/10/2010</td>
							<td>Phase 1 Preproduction</td>
							<td>Approved</td>
							<td>100%</td>
							<td>$2,050.00</td>
						</tr>
						<tr>
							<td>12/10/2010</td>
							<td>Phase 1 Production</td>
							<td>Approved</td>
							<td>50%</td>
							<td>$3,050.00</td>
						</tr>
					</tbody>
				</table>
				
				<p><a href="/estimate.php">Create new</a></p>
				
			</fieldset>
		</div>
		<div class="span-12 last">
			<fieldset>
				
				<legend>Invoice Requests</legend>
				
				<table>
					<thead>
						<tr>
							<th>Date</th>
							<th>Description</th>
							<th>Percent</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="3">Total</th>
							<th>$12,302.50</th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>12/10/2010</td>
							<td>Phase 1 Preproduction</td>
							<td>50%</td>
							<td>$1,025.00</td>
						</tr>
						<tr>
							<td>12/20/2010</td>
							<td>Phase 1 Preproduction</td>
							<td>25%</td>
							<td>$750.00</td>
						</tr>
						<tr>
							<td>01/15/2011</td>
							<td>Phase 1 Preproduction</td>
							<td>25%</td>
							<td>$750.00</td>
						</tr>
						<tr>
							<td>01/16/2011</td>
							<td>Phase 1 Production</td>
							<td>50%</td>
							<td>$1,075.00</td>
						</tr>
					</tbody>
				</table>
				
				<p><a href="/invoice-request.php">Create new</a></p>
				
			</fieldset>

		</div>


			
	</form>
	
</div>
	
<script type="text/javascript">

$(function() {


});

</script>

<?php include('footer.php'); ?>