<?php include('header.php'); ?>

<div id="estimate">

	<h1>Create Task</h1>
		
	<div class="span-24 last">
	
		<form>
		
	  		<fieldset>
	  		
	  			<legend>Task Information</legend>
	  			
	  			<p>
	  				<label>Task Date</label><br />
	  				<input type="text" class="text" />
	  			</p>
	  			
	  			<p>
	  				<label>Due Date</label><br />
	  				<input type="text" class="text" />
	  			</p>
	  			
	  			<p>
	  				<label>Task Title</label><br />
	  				<input type="text" class="text" />
	  			</p>
	  			
	  			<p>
	  				<label>Assigned To</label><br />
	  				<select>
	  					<option value="">Joshua Riddle</option>
	  				</select>
	  			</p>
	  			
	  			<p>
	  				<label>Description</label><br />
	  				<textarea class="span-24 text"></textarea>
	  			</p>
	  			
	  		</fieldset>
	  		
	        <button type="submit" class="button positive">
				<img src="/css/blueprint/plugins/buttons/icons/tick.png" alt=""/> Save
			</button>
			
	 		
		</form>

	</div>

</div>

<?php include('footer.php'); ?>