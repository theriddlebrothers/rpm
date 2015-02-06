<?php include('header.php'); ?>

<div id="estimate">

	<h1>Create Estimate</h1>
		
	<div class="span-24 last">
	
		<form>
		
			<fieldset> 
			
	            <legend>Estimate Info</legend> 
	             
	            <p>
	            	<label>Estimate Name</label><br />
	            	<input type="text" class="title" />
	            </p>
	            
	            <p>
	            	<label>Estimate Date</label><br />
	            	<input type="text" class="text" />
	            </p>
	            
	            <p>
	            	<label>Estimate Number</label><br />
	            	<input type="text" class="text" />
	            </p>
	            
	            <p>
	            	<label>Client Name</label><br />
	            	<input type="text" class="text" />
	            </p>
	                       
	            <p>
	            	<label>Company</label><br />
	            	<select>
	            		<option value="">Acme, Inc.</option>
	            	</select>
	            </p>
	            
	            <p>
	            	<label>Project</label><br />
	            	<select>
	            		<option value="">10-RID-003 Acme Website</option>
	            	</select>
	            </p>
	            
	            <p>
	            	<label>Status</label><br />
	            	<select>
	            		<option value="">Pending Approval</option>
	            	</select>
	            </p>
	            
	      	</fieldset>
	      	
	      	<fieldset>
	      	
	      		<legend>Estimate Content</legend>   
	      		
	      		<p>
	      			<textarea class="span-23 text" rows="5" cols="20">= Overview =
	
	....
	
	= Preproduction =
	
	Preproduction is a phase dedicated to planning and discovery. Documentation will be developed during the stages of this phase that will define the requirements of the site, proposed solutions and overall architecture information necessary for a successful site.
	
	Many documents developed during this phase are "Living Documents", meaning they will change as the requirements and objectives of the site evolve over time. They will be updated as new requirements are proposed and accepted, or revisions are made to existing aspects of the site.
	
	== Project Specifications ==
	
	The Project Specifications document will define all functional and technical requirements necessary for site launch to meet business, creative and operational objectives. Typically this document contains the following sections:
	
	* Specifications Overview
	* Project Overview
	* Hardware and Hosting
	* Information Architecture
	* Site Design Requirements
	* System Architecture
	* Phases & Scheduling
	
	'''Deliverables''': At the end of this stage you will receive a document outlining all collected requirements as a PDF. To view a sample Project Specifications document, refer to http://www.theriddlebrothers.com/sample-project.
	
	== Information Architecture ==
	
	The Information Architecture (IA) diagram will serve as a conceptual map of organizational relationships within the site. These relationships may include the hierarchy of individual pages, the types of content to be included within those pages, external resources, and other models. This diagram is also referred to as a visual sitemap.
	
	'''Deliverables''': The Information Architecture document will be delivered to the client as a PDF. To view a sample Information Architecture document, refer to http://www.theriddlebrothers.com/sample-project.
	
	== Wireframes ==
	
	Wireframes will be created to allow for a consistent layout and design throughout the site, and to establish the language, content, and structure of itneractions users will have within the project. These diagrams will also serve as the basis for the site design during the "Design Comps" stage of the Production phase.
	
	'''Deliverables''': Wireframes will be provided for each major section of the site as a PDF. This typically includes the home page, account dashboards, dynamic pages, forms, and login and registration panels. To view a sample Wireframe document, refer to http://www.theriddlebrothers.com/sample-project.
	
	== Database Architecture ==
	
	A Database Architecture diagram will be designed to serve as documentation of the site's underlying data structure. This document will be used to communicate the relationships between data structures within the site, types of information being stored, and the constraints placed on these structures.
	
	'''Deliverables''': The Database Architecture document will be delivered to the client as a PDF. To view a sample Database Architecture document, refer to http://www.theriddlebrothers.com/sample-project.
	
	== Class Diagrams ==
	
	Class diagrams are documentation used during development to represent the main objects and interactions in the application. In addition, this diagrams contains attributes pertaining to these objects, association with other objects, and how the overall codebase of the site will be architected.
	
	'''Deliverables''': Class Diagrams will be delivered to the client as PDFs. To view a sample Class Diagram, refer to http://www.theriddlebrothers.com/sample-project.
	
	= Production =
	
	== Logo Design ==
	
	For each logo, various rough concepts will be provided with the incorporation of ideas and feedback provided by the client. One of the initial drafts will be selected and refined further to create a finalized logo version in black and white. The logo will be revised until the client is satisfied with the final version. The final logo file will then be colorized based on the client'?s feedback and input from the designer. Various file formats (both vector and bitmap) of the logo will be delivered to the client via e-mail or online file transfer for use on company stationery, Web pages, promotional materials and signage.
	
	== Design Comps ==
	
	Design Comps will be provided for each major section of the site based on the wireframes from the Preproduction phase.
	
	All designs will take into consideration the visual elements of new Web site design trends and model itself after client-provided examples. The client's constant feedback will be continually incorporated into the design drafts throughout the proofing process. Upon approval of the design, the Photoshop file will passed on for production into an HTML build.
	
	== HTML Builds ==
	
	Each Design Comp will be developed into a Web-compatible format, typically a flat HTML file. These builds will serve as templates for integration into the site during the "Application Development" stage. While they will contain interface functionality (such as JavaScript animation, hover effects, and other user interaction events) they will only act as placeholders and will not contain database connectivity or other application-level logic.
	
	HTML builds will be developed using valid XHTML and CSS that complies with W3C standards and section 508 accessibility requirements. JavaScript may be used for interactive elements in order to provide a rich user experience. Attention will be given to page load times and search engine visibility. The Web site will be compatible with all current browsers including Internet Explorer (7+), Mozilla Firefox 3.0+ and Safari.
	
	== Application Development ==
	
	Upon completion of the Preproduction phase, the Application Development stage will begin. During this stage the application framework will be developed in order to create a fully functional, dynamic site. This stage involves the bulk of the software construction. HTML builds will be incorporated into the application framework after the design process has been completed.
	
	The Application Development stage includes all software construction, database connectivity, developer (unit) testing and third party integrations. These updates are made in iterations and will be available for client review on a development server during this process. A development server will be configured to mimic the hosting environment of the production server as closely as possible. This development environment will be provided by the Riddle Brothers unless otherwise specified.
	
	== Testing / Quality Assurance ==
	
	While Quality Assurance tests will be take place during each development iteration, a final stage of overall site acceptance will be performed at the completion of the Production phase. These tests will ensure the site is functional and meets all objectives and requirements of the site as determined during the Preproduction phase.
	
	While this phase will catch a majority of the issues on the site, unforeseen bugs and errors are a part of development. A method for logging and tracking these bugs and errors will be made available to the client in order to organize and collect information to help resolve these issues in a timely manner.
	
	= Deployment =
	
	After final client approval the site will be migrated to the production environment. This will include all site files, databases, file and folder permissions and other applications or configurations necessary for the site to run. A deployment plan will be included in the Preproduction phase to determine how these assets will be delivered (FTP, SSH, compressed files) and what level of server access will be necessary to complete this phase.</textarea>
	      		</p>
	      		
	 		</fieldset>
	 		
			 	<fieldset>
			 	
			 		<legend>Estimate Budget</legend>
			 		
			 		<table>
			 			<thead>
			 				<tr>
			 					<th>Description</th>
			 					<th>Amount/Description</th>
			 					<th>Column Type</th>
			 				</tr>
			 			</thead>
			 			<tfoot>
			 				<tr>
			 					<td><a href="#">Add more</a></td>
			 					<td class="total">
			 						Total: <span id="total">$3,000.00</span>
			 					</td>
			 				</tr>
			 			</tfoot>
			 			<tbody>
			 				<tr>
			 					<td><input type="text" class="text" /></td>
			 					<td><input type="text" class="text" /></td>
			 					<td>
			 						<select>
			 							<option value="">Price</option>
			 							<option value="">Heading</option>
			 						</select>
			 					</td>
			 				</tr>
	
			 				<tr>
			 					<td><input type="text" class="text" /></td>
			 					<td><input type="text" class="text" /></td>
			 					<td>
			 						<select>
			 							<option value="">Price</option>
			 							<option value="">Heading</option>
			 						</select>
			 					</td>
			 				</tr>
	
			 				<tr>
			 					<td><input type="text" class="text" /></td>
			 					<td><input type="text" class="text" /></td>
			 					<td>
			 						<select>
			 							<option value="">Price</option>
			 							<option value="">Heading</option>
			 						</select>
			 					</td>
			 				</tr>
			 			</tbody>
			 		</table>
			 		
			 	</fieldset>
	  		
	  		<fieldset>
	  		
	  			<legend>Terms</legend>
	  		
	  			<p>
	  				<input type="radio" name="terms" /> 
	  				<select>
	  					<option value="">50/25/25 Terms</option>
	  				</select>
	  			</p>
	  			
	  			<p>
	  				<input type="radio" name="terms" value="custom" /> Custom Terms
	  				
	  				<div id="customTermsPanel">
	  					<textarea id="customTerms" name="customTerms" class="span-23 text"></textarea>
	  				</div>
	  			</p>
	  		
	  		</fieldset>
	  		
	  		<fieldset>
	  		
	  			<legend>Estimate Actions</legend>
	  			
	  			<p>When this estimate is accepted:</p>
	  			
	  			<p>
	  				<input type="checkbox" /> Email a copy to <input type="text" class="text" value="email addresses separated by a comma" />
	  			</p>
	  			
	  			<p>
	  				<input type="checkbox" /> Create an invoice request for <input type="text" class="text" /> 
	  				<select>
	  					<option value="">Percent</option>
	  					<option value="">Dollars</option>
	  				</select> 
	  				and email to 
	  				<select>
	  					<option value="">Account User</option>
	  				</select>
	  			</p>
	  			
	  		</fieldset>
	  		
	        <button type="submit" class="button positive">
				<img src="/css/blueprint/plugins/buttons/icons/tick.png" alt=""/> Save
			</button>
			
	 		
		</form>

	</div>

</div>

<script type="text/javascript">

$(function() {

	// Custom Terms
	$('#customTermsPanel').hide();
	$('input[name="terms"]').change(function() {
		if ($(this).val() == 'custom') {
			$('#customTermsPanel').slideDown();
		} else {
			$('#customTermsPanel').slideUp();		
		}
	});

});

</script>

<?php include('footer.php'); ?>