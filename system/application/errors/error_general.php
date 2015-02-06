<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title>Dashboard | RPM</title> 
		
		<!-- Blueprint -->
	<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print" />
	<!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<link rel="icon" type="image/ico" href="/favicon.ico" />  
	<style type="text/css" media="print">img { display:none; }</style>  
	
	
	<!-- LESS -->

	<link rel="stylesheet/less" href="/css/main.less" type="text/css" />
	<script src="/js/less-1.0.21.min.js" type="text/javascript"></script>
	
	<!-- jQuery -->
	<link rel="stylesheet" href="/js/jquery/ui/smoothness/jquery-ui-1.8.6.custom.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/js/jquery/sexy-combo/lib/sexy-combo.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/js/jquery/sexy-combo/skins/sexy/sexy.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/css/process.css" type="text/css" />
	
	<script type="text/javascript" src="/js/jquery/jquery-1.4.3.min.js"></script>

	<script type="text/javascript" src="/js/jquery/ui/jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery/jquery.cookie.js"></script>
	<script type="text/javascript" src="/js/jquery/autocomplete/jquery.autocomplete.pack.js"></script>
	<script type="text/javascript" src="/js/jquery/sexy-combo/lib/jquery.sexy-combo-2.1.2.pack.js"></script>
	<script type="text/javascript" src="/js/jquery/jquery.tablednd.min.js"></script>
	<script type="text/javascript" src="/js/jquery/jquery.timepicker.js"></script>

	
	<!-- Application -->
	<script type="text/javascript" src="/js/utility.js"></script>
	
	<script type="text/javascript">
		$(function() {
			$('.popup').click(function() {
				var answer = confirm('Are you sure you want to delete this item?');
				if (answer) {
					return true;
				}
				return false;
			});
			
			$('.datepicker').datepicker();
			
			$('.datetimepicker').datetimepicker({
				ampm: true,
				timeFormat: 'hh:mmtt',
				separator: ' @ ',
				stepMinute: 15
				
			});
			
			$("select.editable").sexyCombo();
			
			$('.help').click(function() {
				var id = $(this).attr("href");
				var content = $(id).html();
				$('#dialog').html(content);
				$('#dialog').dialog({
					modal : true,
					width: '500px'
				});
			});
			
		});
	</script>		
		<script type="text/javascript">
	function checkLoginInterval() {
		$.getJSON('/cp/ajax/users/checklogin', function(data) {
			var res = false;
			if (data.value != null) {
				if (data.value) {
					res = true;
				}
			}
			if (!res) {
				window.location.href = '/welcome/index/?logout=true&force=true';
			}
		});
	}
	
	$(function() {
		// periodically ensure user is still logged in - every 10 minutes
		var interval = 1 * (60 * 1000) 
		setInterval('checkLoginInterval();', interval)
		
		// Ajax search
		$('#query').focus(function() { $(this).val('') } );
		
		$('#query').autocomplete('/cp/ajax/search');
		
		// navigate to selected item
		$("#query").result(function(event, data, formatted) {
			window.location.href = data[1];
		});
		
	});
	
</script>		
	</head> 
	<body class="">
		<div id="dialog"></div>
		<div class="wrapper">
			<div id="header">
				<div class="container">
				
					<div class="span-2">
						<a class="printhide" id="logo" href="http://sandbox.projectmanager.com/cp/dashboard"><img src="/images/logo.png" alt="the Riddle Brothers Project Manager" /></a>
					</div>

					<h1 class="printonly" id="printheader">
						the Riddle Brothers
					</h1>

					
					<ul id="navigation" class="printhide">
						

	<li><a href="http://sandbox.projectmanager.com/cp/companies">Companies</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/contacts">Contacts</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/projects">Projects</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/estimates">Estimates</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/invoices">Invoices</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/reports">Reports</a></li>

	<li><a href="http://sandbox.projectmanager.com/cp/admin">Admin</a></li>


<li><input class="text" id="query" type="text" name="q" value="Search..." /></li>
					</ul>

				</div>
			</div>
			
			<div id="timelogPanel">
	<div class="panel">
		
		<form action="http://sandbox.projectmanager.com/cp/timelogs/create" method="post">

			<fieldset>
				
				<legend>Add Timelog</legend>
	
				<div class="span-5">

					<p>
						<label>Log Date</label><br />
						<input style="width:100px;" name="log_date" type="text" class="datepicker text" value="04/13/2011" />
					</p>
				</div>
				
				<div class="span-5 last">
					<p>
						<label>Time (Hr:Min)</label><br />

						<input style="width:50px;" name="hours" type="text" class="number text" value="" /><br />
					</p>
				</div>
						
				<div class="span-10 last">
					<p>
						<label>Task</label><br />
						<input type="text" class="taskSearch text" value="" /><br />
						<span class="quiet">Type a project or task name.</span>

						<input id="timelogPanelTask" name="task_id" type="hidden" value="" />
					</p>
				</div>
				
				<div class="span-10 last">
					<label>Description</label><br />
					
					<p>
						<textarea id="quickDescription" name="description" class="text"></textarea>
					</p>

				</div>				
				<input type="hidden" name="user" value="2" />
					
				<button type="submit" class="button primary" name="submit">
					Save
				</button>
					
			</fieldset>
		</form>
		
		<div style="clear:both;"></div>
	
	</div>

	<a class="trigger" href="#"><img src="/images/icons/time_add.png" alt="Time log" /></a>
</div>


<script type="text/javascript">
	$(function() {
		
		// open panel
		$("#timelogPanel .trigger").click(function(){
			$("#timelogPanel .panel").toggle("fast");
			$(this).toggleClass("active");
			return false;
		})
		
		// search tasks
		$('#timelogPanel .taskSearch').autocomplete('/cp/tasks/ajax/search/').result(function(event, data, formatted) {
			var hidden = $('#timelogPanelTask');
			hidden.val(data[1]);
		});

	});
</script>			
			<div id="activityPanel">
	<div class="panel">
		
		<form action="http://sandbox.projectmanager.com/cp/activities/create" method="post">

			<fieldset>
				
				<legend>Add Activity</legend>

	
				<div class="span-9">
				<p>
					<label>Subject</label><br />
					<input name="subject" type="text" class="text" value="" />
				</p>
				
				<p>
					<label>Company</label><br />
					<input style="width:330px;" id="activityPanelCompany" name="company" type="text" class="text" value="" />

				</p>
				
				<p>
					<label>Activity Type</label><br />
					<select name="activity_type">
						<option selected="selected" value="">Select a type...</option>
						<option value="Email">Email</option>
						<option value="Phone Call">Phone Call</option>

						<option value="Meeting">Meeting</option>
						<option value="Other">Other</option>
					</select>
				</p>
				
				<p>
					<label>Activity Date/Time</label><br />
					<input name="activity_date" type="text" class="datetimepicker text" value="" />

				</p>
				
				<p>
					<label>Description</label><br />
					<textarea name="description" class="text span-9"></textarea><br />
					<span class="quiet">Activity description content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
					
				</p>

							
				<div class="span-9 last">
					
					<button id="activityPanelGo" type="submit" class="button primary" name="submit">
						Save
					</button>
				</div>
			</fieldset>
		</form>
		
		<div style="clear:both;"></div>
	
	</div>

	<a class="trigger" href="#"><img src="/images/icons/book_add.png" alt="Add Activity" /></a>
</div>


<script type="text/javascript">
	$(function() {
		
		// open panel
		$("#activityPanel .trigger").click(function(){
			$("#activityPanel .panel").toggle("fast");
			$(this).toggleClass("active");
			return false;
		})
		
		// Autocomplete company name
		$('#activityPanelCompany').autocomplete('/cp/companies/ajax/search');	

	});
</script>			
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
						<input style="width:330px;" id="linkPanelLink" name="linkPanelLink" type="text" class="text" value="/cp/dashboard" />
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
						
			<div class="container">
						
				<div class="span-24 last">
	
					<!-- System Message -->
					

					
					<!-- Page Content -->

					<h1><?php echo $heading; ?></h1>

					<div class="span-24 last">
						<?php echo $message; ?>	
					</div>






		
				</div>

			</div>
			
			<div id="push"></div>
		</div>
		<div id="footer">
		
			<div class="container">	</div>
		</div>
	</body>

</html>