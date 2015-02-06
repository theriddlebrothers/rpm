<div id="login">

	<form action="" method="post">
	
		<h2>Reset Your Password</h2>
		<fieldset>
			<?php if ($success) : ?>
				<div class="success message">
					<?php echo $success; ?>
				</div>
			<?php elseif($errors) : ?>
				<div class="error message">
					<ul>
					<?php foreach($errors as $error) : ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
					</ul>
				</div>
			<?php else : ?>
				<p>Fill out the information below to create a new password and log in to RPM.</p>
			<?php endif; ?>
			
			<?php if (!$success) : ?>
				<p>
					<label>Username</label>
					<input type="text" name="username" class="text" value="" />
				</p>
				
				<p>
					<label>New Password</label>
					<input type="password" name="password" class="text" />
				</p>
				
				<p>
					<label>Confirm Password</label>
					<input type="password" name="confirm_password" class="text" />
				</p>
				
				<button type="submit" class="button primary" name="submit">
					Reset Password
				</button>
				
			<?php endif; ?>
				
			<a id="login-option" href="/">&laquo; Back to Login</a>
			
		</fieldset>
	
	</form>
	
</div>