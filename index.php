<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php

// check for form submission
if (isset($_POST['submit'])) {

	$errors = array();

	// check if the username and password has been entered
	if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
		$errors[] = 'Username is Missing / Invalid';
	}

	if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
		$errors[] = 'Password is Missing / Invalid';
	}

	// check if there are any errors in the form
	// TODO err balanno
	if (empty($errors)) {
		// save username and password into variables securely hre
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$password = mysqli_real_escape_string($connection, $_POST['password']);
		$hashed_password = sha1($password);

		// prepare database query
		$query = "SELECT * FROM user 
						WHERE email = '{$email}' 
						AND password = '{$hashed_password}' 
						LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		verify_query($result_set);

		if (mysqli_num_rows($result_set) == 1) {
			// valid user found
			$user = mysqli_fetch_assoc($result_set);
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['first_name'] = $user['first_name'];
			$_SESSION['user_type'] = $user['user_type'];

			// updating last login
			$query = "UPDATE user SET last_login = NOW() ";
			$query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

			$result_set = mysqli_query($connection, $query);

			verify_query($result_set);

			// redirect to home
			header("Location: homepage.php");


		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/navStyle.css" />
	<link rel="stylesheet" href="css/mainStyle.css" />
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

	<title>Log In - User Management System</title>
	<style>
		body {
			margin-left: 5%;
			margin-right: 5%;
		}

		input {
			display: block;
			width: 100%;
			padding: 5px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			font-size: 14px;
		}

		.submit_btn {
			background-color: rgb(0, 200, 0);
			color: white;
			padding-left: 3%;
			padding-right: 3%;
			padding-top: 0.75%;
			padding-bottom: 0.75%;
			width: 100%;
		}

		#image-des {
			font-size: small;
			/* background-color: yellow; */
			/* border-radius: 4px; */
		}

		fieldset {
			padding: 2%;
			margin: 2%;
		}

		#image-btn {
			margin-top: 1%;
			color: red;
		}

		.error {
			color: red;
			font-size: small;
		}
	</style>
</head>

<body>
	<?php include_once('inc/nav.php') ?>

	<div class="login">


		<form action="index.php" method="post">

			<fieldset>
				<legend>
					<h1>Log In</h1>
				</legend>

				<?php
				if (isset($errors) && !empty($errors)) {
					echo '<p class="error">Invalid Username / Password</p>';
				}
				?>

				<?php
				if (isset($_GET['logout'])) {
					echo '<p class="info">You have successfully logged out from the system</p>';
				}
				if (!isset($_SESSION['user_id'])) {
					echo '<p class="info">Log in to write reports & get other features</p><br>';
				}
				if (isset($_GET['first_login'])) {
					echo '<p class="info">Registration sucsessful. Login to continue</p><br>';
				}
				?>

				<p>
					<label for="">Username:</label>
					<input type="text" name="email" id="" placeholder="Email Address">
				</p><br>

				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="Password">
				</p><br>

				<p>
					<button class="submit_btn" type="submit" name="submit">Log In</button>
				</p><br>

				<p>Dont have an account? <a href="register.php" style="color: blue;"> &nbsp; Register</a> </p>

			</fieldset>

		</form>

	</div> <!-- .login -->
</body>

</html>
<?php mysqli_close($connection); ?>