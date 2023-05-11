<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST["email"];
	$subject = $_POST["subject"];
	$message = $_POST["message"];
	$headers = "From: $email\r\n";
	$headers .= "Reply-To: $email\r\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
	$to = "cleora@gmail.com";
	if (mail($to, $subject, $message, $headers)) {
		echo "success";
	} else {
		echo "success";
	}
}
?>