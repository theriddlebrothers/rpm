<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title>Login | RPM</title> 
		
		<?php echo $this->load->view('shared/header-includes'); ?>
		
	</head> 
	<body class="<?php if (isset($body_class)) echo $body_class; ?>">
		<div id="dialog"></div>
		<?php $this->load->view('shared/debug'); ?>
		<div id="header">
			<div class="container">
			
				<div class="span-2">
					<a id="logo" href="/"><img src="/images/logo.png" alt="the Riddle Brothers RPM" /></a>
				</div>

				<h1 class="printonly" id="printheader">
					the Riddle Brothers
				</h1>

			</div>
		
		</div>
			
		<div class="container">
					
			<div class="span-24 last">

				<!-- System Message -->
				<?php echo $message; ?>
				
				<!-- Page Content -->
				<?php echo $content; ?>
			
			</div>
			<div class="span-24 last">
				<!-- footer -->
			</div>
		</div>
	</body>
</html>