<?php require_once('header.php'); ?>

<div class="global-login">
	<div>
		<a class="logo" href="./"></a>

		<div class="login-container">
			<div class="text">
				Please login using your valid credential.
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="username" placeholder="Username">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" name="password" placeholder="Password">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" name="confirm_password" placeholder="Confirm password">
			</div>
			<div class="form-group">
				<input type="tel" class="form-control" name="phone" placeholder="Phone">
			</div>
			<div class="form-group">
				<input type="email" class="form-control" name="username" placeholder="Email">
			</div>

			<div class="form-group login-btn-container">
				<button class="btn btn-login">Register</button>
			</div>

			<div class="foot">
				<a href="login.php">Already have an account? Login.</a>
			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>