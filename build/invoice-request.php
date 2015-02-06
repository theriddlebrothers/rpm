<?php include('header.php'); ?>

<div id="estimate">

	<h1>Create Invoice Request</h1>
		
	<div class="span-12">
	
		<form>
		
	  		<fieldset>
	  		
	  			<legend>Request Information</legend>
	  			
	  			<p>
	  				<label>Estimate</label><br />
	  				<select>
	  					<option value=""></option>
	  					<option value="1">#242 Acme, Inc. Website Redesign</option>
	  				</select>
	  			</p>
	  			
	  			<p>
	  				<label>Amount to Bill</label><br />
	  				<input type="text" class="text" /> 
	  				<select>
	  					<option value="">Percent</option>
	  				</select>
	  			</p>
	  			
	  			<p>
	  				<label>Notes</label><br />
	  				<textarea class="span-11 text"></textarea>
	  			</p>
	  			
	  			<p>
	  				<label>Request Recipient</label><br />
	  				<select>
	  					<option value="">Accounting User</option>
	  				</select>
	  			</p>
	  			
	  		</fieldset>
	  		
	        <button type="submit" class="button positive">
				<img src="/css/blueprint/plugins/buttons/icons/tick.png" alt=""/> Save
			</button>
			
	 		
		</form>

	</div>
	
	<div class="span-12 last">
		<fieldset>
			<legend>Estimate Details</legend>
			<p id="detailCaption">Select an estimate to view the details here.</p>
				
			<div id="estimateDetails">
				<table>
					<thead>
						<tr>
							<th>Description</th>
							<th>Estimated Amount</th>
							<th>Billed to Date</th>
							<th>Percent</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Total</th>
							<th>$5,500.00</th>
							<th>$2,750.00</th>
							<th>50%</th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>Preproduction</th>
							<th></th>
							<th></th>
							<th>50%</th>
						</tr>
						<tr>
							<td>Site Design Comps</td>
							<td>$1,000.00</td>
							<td>$500.00</td>
							<td>50%</td>
						</tr>
						<tr>
							<td>Wireframes</td>
							<td>$1,500.00</td>
							<td>$750.00</td>
							<td>50%</td>
						</tr>
						<tr>
							<th>Production</th>
							<th></th>
							<th></th>
							<th>50%</th>
						</tr>
						<tr>
							<td>Application Development</td>
							<td>$3,000.00</td>
							<td>$1,500.00</td>
							<td>50%</td>
						</tr>
					</tbody>
				</table>
			</div>
		</fieldset>
	</div>

</div>

<script type="text/javascript">
	$(function() {
		// Estimate details
		$('#estimateDetails').hide();

		$('#estimate').change(function() {
			$('#estimateDetails').show();
			$('#detailCaption').hide();
		});
	});
</script>

<?php include('footer.php'); ?>