<div id="login">

	<form action="" method="post">
	
		<h2>Forgotten Password</h2>
		<fieldset>
			<?php if ($success) : ?>
				<div class="success message">
					<?php echo $success; ?>
				</div>
			<?php else : ?>
				<p>Enter your username below and a link to reset your password will be sent to you.</p>
			<?php endif; ?>
			
			<?php if (!$success) : ?>
				<p>
					<label>Username</label>
					<input type="text" name="username" class="text" />
				</p>
				
				<button type="submit" class="button primary" name="submit">
					Send Link
				</button>
			
			<?php endif; ?>
				
			<a id="login-option" href="/">&laquo; Back to Login</a>
			
		</fieldset>
	
	</form>
	
</div>