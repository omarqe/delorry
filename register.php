<?php
require_once('includes/load.php');

if ( is_loggedin() )
	redirect( is_admin() ? './admin/' : './' );

require_once('process.php');
require_once('header.php');
?>

<div class="global-login">
	<div>
		<a class="logo" href="./"></a>

		<div class="login-container">
			<div class="text">
				<?php
				switch(parse_arg('msg', $_GET)){
					case "fields_empty":
						echo "Some fields are empty. Please fill all the fields.";
						break;

					case "password_mismatch":
						echo "Your password are mismatch.";
						break;

					case "user_exists":
						echo "The username that you've chosen is exists. Please choose another.";
						break;

					case "error":
						echo "We can't register you right now. Please try again later.";
						break;

					default:
						echo "Please fill all the fields below.";
						break;
				}
				?>
			</div>
			<form method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo parse_arg('username', $_GET); ?>" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="confirm_pass" placeholder="Confirm password" required>
				</div>
				<div class="form-group">
					<input type="tel" class="form-control" name="phone" placeholder="Phone" value="<?php echo parse_arg('phone', $_GET); ?>" required>
				</div>
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo parse_arg('email', $_GET); ?>" required>
				</div>

				<div class="form-group login-btn-container">
					<input type="hidden" name="action" value="register">
					<button class="btn btn-login">Register</button>
				</div>
			</form>

			<div class="foot">
				<a href="login.php">Already have an account? Login.</a>
			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>