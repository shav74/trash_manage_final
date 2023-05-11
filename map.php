<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php

$sql = "SELECT * FROM report ";
$result = $connection->query($sql);

$locations = array();

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$location = array(
			"title" => $row["title"],
			"date" => $row["date"],
			"loc_name" => $row["loc_name"],
			"latitude" => $row["latitude"],
			"longitude" => $row["longitude"]
		);
		array_push($locations, $location);
	}
}

$connection->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Map</title>
	<link rel="stylesheet" href="css/navStyle.css" />
	<link rel="stylesheet" href="css/mainStyle.css" />
	<link rel="stylesheet" type="text/css" href="css/map.css">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
	<script src="js/navjs.js" defer></script>

</head>

<?php include_once('inc/nav.php') ?>

<body onload="initMap()">

	<div id="map"></div>

	<div id="list">
		<?php foreach ($locations as $location) { ?>
			<div class="card"
				onclick="clickCard('<?php echo $location['loc_name']; ?>', <?php echo $location['latitude']; ?>, <?php echo $location['longitude']; ?>)">
				<h3>
					<?php echo $location['title']; ?>
				</h3>
				<p>
					<?php echo $location['loc_name']; ?>
				</p>
				<p>
					<?php echo $location['date']; ?>
				</p>
			</div>
		<?php } ?>
	</div>

	<script>
		var map;
		var markers = [];

		function initMap() {
			map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 6.83, lng: 80.04 },
				zoom: 12,
				mapTypeId: 'satellite'
			});

			var bounds = new google.maps.LatLngBounds();

			var locations = <?php echo json_encode($locations); ?>;

			for (var i = 0; i < locations.length; i++) {
				addMarker({ lat: parseFloat(locations[i].latitude), lng: parseFloat(locations[i].longitude) }, locations[i].loc_name);
				bounds.extend({ lat: parseFloat(locations[i].latitude), lng: parseFloat(locations[i].longitude) });
			}
			map.fitBounds(bounds);
		}


		function addMarker(location, name) {
			var marker = new google.maps.Marker({
				position: location,
				map: map,
				title: name
			});

			markers.push(marker);
		}

		function clickCard(name, lat, lng) {
			for (var i = 0; i < markers.length; i++) {
				if (markers[i].title == name) {
					map.setCenter(markers[i].getPosition());
					map.setZoom(35);
					break;
				}
			}
		}
	</script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7n_UgQbNJXHYkBxyKTLKTfQtG_pdZllc"></script>
</body>

</html>