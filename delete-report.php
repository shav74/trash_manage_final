<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	if (isset($_GET['id'])) {
		// getting the user information
		$report_id = mysqli_real_escape_string($connection, $_GET['id']);
			// deleting the user
			$query = "UPDATE report SET is_deleted = 1 WHERE id = {$report_id} LIMIT 1";
			$result = mysqli_query($connection, $query);

			if ($result) {
				// user deleted
				header('Location: my-reports.php?msg=report_deleted');
			} else {
				header('Location: my-reports.php?err=delete_failed');
			}
	} else {
		header('Location: my-reports.php');
	}
?>