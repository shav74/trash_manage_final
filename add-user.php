<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php
// checking if a user is logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: index.php');
}

$errors = array();
$first_name = '';
$last_name = '';
$email = '';
$password = '';

if (isset($_POST['submit'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$user_type = $_POST['user_type'];


	// checking required fields
	$req_fields = array('first_name', 'last_name', 'email', 'password');
	$errors = array_merge($errors, check_req_fields($req_fields));

	// checking max length
	$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100, 'password' => 40);
	$errors = array_merge($errors, check_max_len($max_len_fields));

	// checking email address
	if (!is_email($_POST['email'])) {
		$errors[] = 'Email address is invalid.';
	}

	// checking if email address already exists
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$query = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	if ($result_set) {
		if (mysqli_num_rows($result_set) == 1) {
			$errors[] = 'Email address already exists';
		}
	}

	if (empty($errors)) {
		// no errors found... adding new record
		$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
		$password = mysqli_real_escape_string($connection, $_POST['password']);
		// email address is already sanitized
		$hashed_password = sha1($password);

		$query = "INSERT INTO user ( ";
		$query .= "first_name, last_name, email, password, is_deleted,user_type";
		$query .= ") VALUES (";
		$query .= "'{$first_name}', '{$last_name}', '{$email}', '{$hashed_password}', 0,'{$user_type}'";
		$query .= ")";

		$result = mysqli_query($connection, $query);

		if ($result) {
			// query successful... redirecting to users page
			header('Location: users.php?user_added=true');
		} else {
			$errors[] = 'Failed to add the new record.';
		}


	}



}



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/navStyle.css" />
	<link rel="stylesheet" href="css/mainStyle.css" />
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
	<title>Add New User</title>
	<link rel="stylesheet" href="css/main.css">
	<script src="js/navjs.js" defer></script>


	<style>
		.navl a {
			color: #eee;
			text-decoration: none;
		}

		a {
			color: black;
		}
	</style>
</head>

<body>
	<?php include_once('inc/nav.php') ?>

	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome
			<?php echo $_SESSION['first_name']; ?>! <a href="logout.php">Log Out</a>
		</div>
	</header>

	<main>
		<h1>Add New User<span> <a href="users.php">
					< Back to User List</a></span></h1>

		<?php

		if (!empty($errors)) {
			display_errors($errors);
		}

		?>

		<form action="add-user.php" method="post" class="userform">

			<p>
				<label for="first_name">First Name:</label>
				<input type="text" name="first_name" <?php echo 'value="' . $first_name . '"'; ?>>
			</p>

			<p>
				<label for="last_name">Last Name:</label>
				<input type="text" name="last_name" <?php echo 'value="' . $last_name . '"'; ?>>
			</p>

			<p>
				<label for="email">Email Address:</label>
				<input type="text" name="email" <?php echo 'value="' . $email . '"'; ?>>
			</p>

			<p>
				<label for="password">New Password:</label>
				<input type="password" name="password">
			</p>

			<p>
				<label for="user_type">User type:</label>
				<input type="text" name="user_type">
			</p>

			<p>
				<label for="">&nbsp;</label>
				<button type="submit" name="submit">Save</button>
			</p>

		</form>



	</main>
</body>

</html>