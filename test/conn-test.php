<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'userdb';

$connection = mysqli_connect('localhost', 'root', '', 'userdb');
// Checking the connection
if (mysqli_connect_errno()) {
    die('Database connection failed ' . mysqli_connect_error());
} else {
    echo "<h1>connection success</h1>";
    echo "<script>console.log('success')</script>";
}

?>