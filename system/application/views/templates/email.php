<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title><?php echo $email_title; ?></title>
	</head>
	<body style="background:#fff; font-family:Arial, Helvetica, sans-serif">
		<table width="100%" border="0" align="center">
			<tr>
				<td align="center">
					<h1><img src="http://<?php echo $this->input->server('HTTP_HOST'); ?>/images/email-header.png" alt="the Riddle Brothers" title="the Riddle Brothers" /></h1>
					<table width="582px" border="0" style="background:#DFF4FF" cellpadding="10">
						<tr>
							<td>
								<?php if ($email_title) : ?>
									<h2><?php echo $email_title; ?></h2>
								<?php endif; ?>
								
								<?php echo $email_message; ?>
								
								<?php // statements below are for new useage of "brandedemail" class ?>
								<?php if (isset($email_link_url) && isset($email_link_text)) : ?>
									<?php if ($email_link_url && $email_link_text) : ?>
										<p><a href="<?php echo $email_link_url; ?>" style="color:#2F657F"><?php echo $email_link_text; ?></a></p>									
									<?php endif; ?>
								<?php endif; ?>
								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center">
					<p style="color:#333; font-size:11px">
						Trouble viewing this email? Link not working? <a href="mailto:contact@theriddlebrothers.com">Contact us</a>
					</p>
				</td>
			</tr>
		</table>
	</body>
</html>