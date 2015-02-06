<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title><?php echo $page_title; ?></title> 
		
		<?php $this->load->view('shared/header-includes'); ?>
		
		<?php $this->load->view('templates/header-scripts'); ?>
		
	</head> 
	<body class="<?php if (isset($body_class)) echo $body_class; ?>">
		<div id="dialog"></div>
		<?php $this->load->view('shared/debug'); ?>
		<div class="wrapper">
			<div id="header">
				<div class="container">
				
					<div class="span-2">
						<a class="printhide" id="logo" href="<?php echo site_url('dashboard'); ?>"><img src="/images/logo.png" alt="the Riddle Brothers Project Manager" /></a>
					</div>

					<h1 class="printonly" id="printheader">
						the Riddle Brothers
					</h1>
					
					<ul id="navigation" class="printhide">
						<?php $this->load->view('templates/navigation'); ?>
					</ul>
				</div>
			</div>
			
			<?php $this->load->view('templates/includes/timelog-panel'); ?>
			
			<?php if (in_role(ROLE_ADMINISTRATOR)) : ?>
				<?php $this->load->view('templates/includes/activity-panel'); ?>
				<?php $this->load->view('templates/includes/link-panel'); ?>
			<?php endif; ?>

						
			<div class="container">
						
				<div class="span-24 last">
	
					<!-- System Message -->
					<?php echo $message; ?>
					
					<!-- Page Content -->
					<?php echo $content; ?>
				
				</div>
			</div>
			
			<div id="push"></div>
		</div>
		<div id="footer">
		
			<div class="container">
				<?php $this->load->view('templates/footer'); ?>
			</div>
		</div>
	</body>
</html>