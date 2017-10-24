<!DOCTYPE HTML>

<html>
<head>
	<title>DeLorry | Book a Mover Now</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<div class="top-bar">
		<a class="menu-bars"></a>
		<a class="logo" href="./"></a>

		<div class="user-control">
			<ul>
			<?php if ( !is_loggedin() ): ?>
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
			<?php elseif (is_loggedin() && is_admin()): ?>
				<li><a href="./admin/">Administrator</a></li>
				<li><a href="process.php?action=logout">Logout</a></li>
			<?php elseif (is_loggedin()): ?>
				<li><a href="process.php?action=logout">Logout</a></li>
			<?php endif; ?>
			</ul>
		</div>
	</div>