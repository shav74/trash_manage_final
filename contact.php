<?php session_start(); ?>
<?php require('inc/connection.php'); ?>
<?php require('inc/functions.php');
if (!isset($_SESSION['user_id'])) {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Contact Us</title>
	<link rel="stylesheet" type="text/css" href="css/contact.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/navStyle.css" />
	<link rel="stylesheet" href="css/mainStyle.css" />
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
</head>

<body>
	<?php include_once('inc/nav.php') ?>
	<div class="container">
		<div class="contact-info">
			<h2>Contact Us</h2>
			<p>123 Main Street</p>
			<p>Anytown, USA 12345</p>
			<p>Phone: (123) 456-7890</p>
			<p>Email: info@example.com</p>
		</div>
		<div class="ask-query">
			<form action="send-email.php" method="post">
				<label for="email">Your Email:</label>
				<input type="email" id="email" name="email" required>
				<label for="subject">Subject:</label>
				<input type="text" id="subject" name="subject" required>
				<label for="message">Message:</label>
				<textarea id="message" name="message" required></textarea> <br>
				<input type="submit" value="Send">
			</form>
			<div id="status"></div>
		</div>
	</div>
	<script src="js/contact.js"></script>
</body>

</html>