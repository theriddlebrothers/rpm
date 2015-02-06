<?php include('header.php'); ?>

<div id="project">

	<form>
		
		<h1>Create Project</h1>
			
			
		<div class="span-24 last">
		
				<fieldset> 
				
		            <legend>Project Information</legend> 
		             
		            <p>
		            	<label>Project Name</label><br />
		            	<input type="text" class="title" />
		            </p>
		            
		            <p>
		            	<label>Project Number</label><br />
		            	<input type="text" class="text" />
		            </p>
		            
		            <p>
		            	<label>Company</label><br />
		            	<select>
		            		<option value="">Acme, Inc.</option>
		            	</select>
		            </p>
		                        
		            <p>
		            	<label>Status</label><br />
		            	<select>
		            		<option value="">Pending Approval</option>
		            		<option value="">In Progress</option>
		            		<option value="">Closed</option>
		            		<option value="">Inactive</option>
		            	</select>
		            </p>
		            
		            <p>
		            	<label>Target Start Date</label><br />
		            	<input type="text" class="text" />
		            </p>
		            
		            <p>
		            	<label>Target End Date</label><br />
		            	<input type="text" class="text" />
		            </p>
		            
		            <p>
		            	<label>Dropbox Folder</label><br />
		            	<input type="text" class="text" /><br />
		            	<span class="quiet">Folders should be created in /Dropbox/Public/[Client Name]/[Project Name] format.</span>
		            </p>
		            
		            <p>
		            	<label>General Description</label><br />
		            	<textarea class="text span-24" /></textarea>
		            </p>
		            
		      	</fieldset>
		</div>
			
	    <button type="submit" class="button positive">
			<img src="/css/blueprint/plugins/buttons/icons/tick.png" alt=""/> Save
		</button>
		
			
	</form>
	
</div>

<?php include('footer.php'); ?>