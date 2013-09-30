<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');
?>
<!DOCTYPE html>
<head>
<title>Import | Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php 
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode('200 EAST CYPRESS AVE, Burbank, CA').'&sensor=false';
	$data = file_get_contents($url); // Send the request for geocoding
	$loc = json_decode($data); // Decode the json result
	$geometry = $loc->results[0];
	$lat = $geometry->geometry->location->lat;
	$long = $geometry->geometry->location->lng;
	echo "Latitude: $lat, Longitude $long";
	?>
    </div>
</body>
</html>