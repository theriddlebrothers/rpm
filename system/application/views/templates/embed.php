<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title><?php echo $page_title; ?></title> 
		
		<?php echo $this->load->view('shared/header-includes'); ?>
		<link rel="stylesheet" href="<?php echo site_url('/css/embed.css'); ?>" type="text/css" />
		
		<?php if ($stylesheet) : ?>
			<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css" />
		<?php endif; ?>
		
	</head> 
	<body class="embed">
		<div id="dialog"></div>
		<?php $this->load->view('shared/debug'); ?>
		<div class="container">
					
			<div class="span-20 last">

				<!-- System Message -->
				<?php echo $message; ?>
				
				<!-- Page Content -->
				<?php echo $content; ?>
			
			</div>
			<div class="span-20 last">
				<!-- footer -->
			</div>
		</div>
	</body>
</html>