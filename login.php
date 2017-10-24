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
					case "error":
						echo "Username or password are invalid.";
						break;

					default:
						echo "Please login using your valid credentials.";
						break;
				}
				?>
			</div>

			<form method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo parse_arg('username',$_GET); ?>" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>

				<div class="form-group login-btn-container">
					<input type="hidden" name="action" value="login">
					<button class="btn btn-login">Login</button>
				</div>
			</form>

			<div class="foot">
				<a href="register.php">Don't have an account? Register.</a>
			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>