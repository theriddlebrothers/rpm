<div id="linkPanel">
	<div class="panel">
		
		<form action="" method="post">

			<fieldset>
				
				<legend>Generate Client Link</legend>
	
				<div class="span-9">
					<p>
						Generate a publicly viewable link with the client's company view key embedded in the URL.
					</p>
					
					<p>
						<label>Company</label><br />
						<input style="width:330px;" id="linkPanelCompany" name="linkPanelCompany" type="text" class="text" value="" />
					</p>
				</div>
				
				<div class="span-9 last">
					<p>
						<label>Link</label><br />
						<input style="width:330px;" id="linkPanelLink" name="linkPanelLink" type="text" class="text" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
						<span class="quiet">Paste a URL here.</span>
					</p>
				</div>
				
				<div class="span-9 last">
					
					<button id="linkPanelGo" type="submit" class="button primary" name="submit">
						Generate
					</button>
				</div>
				
				<div id="linkPanelContents" class="span-5 last">
				</div>
			</fieldset>
		</form>
		
		<div style="clear:both;"></div>
	
	</div>
	<a class="trigger" href="#"><img src="/images/icons/link.png" alt="Client Link" /></a>
</div>


<script type="text/javascript">
	$(function() {
		
		// open panel
		$("#linkPanel .trigger").click(function(){
			$("#linkPanel .panel").toggle("fast");
			$(this).toggleClass("active");
			return false;
		})
		
		// Autocomplete company name
		$('#linkPanelCompany').autocomplete('/cp/companies/ajax/search/');	

		$('#linkPanelGo').click(function() {
			var content = $('#linkPanelContents');
			content.html('Generating link...');
			
			var company = $('#linkPanelCompany').val();
			var link = $('#linkPanelLink').val();
			
			if (!company || !link) {
				content.html('Company and link are required.');
				return false;
			}
			
			link = encodeURIComponent(link).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').                                                                    replace(/\)/g, '%29').replace(/\*/g, '%2A');
			
			// ajax retrieve link
			$.getJSON('/cp/ajax/companies/geturl/', { company: company, link : link}, function(resp) {
				
				if (!resp.value) {
					content.html('Company not found.');
					return false;		
				}
				
				content.html('<p><label>Client URL</label><br /><input type="text" class="text" style="width:330px;" value="' + resp.value + '" />');
				$('input', content).focus();
		
			});
			return false;
		});
	});
</script>