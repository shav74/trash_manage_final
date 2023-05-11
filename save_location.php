<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$locationName = $data['locationName'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO location (loc_name, latitude, longitude,u_id) VALUES (?, ?, ?,'$user_id')";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sdd", $locationName, $latitude, $longitude);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . $connection->error]);
}

$stmt->close();
$connection->close();
?>