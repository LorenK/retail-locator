<?php

// This page receives a request from the Google maps page and sends back a JSON oject with all the store data. ALL FORMATTING OCCURS HERE

require('connect.php');

function allStores($store,$length) { // This function accepts the store name and returns all the stores in the database
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	if($length) {
		$result = $mysqli->query("SELECT * FROM $store LIMIT $length");
	}
	else {
		$result = $mysqli->query("SELECT * FROM $store");
	}
	$i = 0;
	while($row = $result->fetch_array()) { // Get the data from the rows and put it in an array
		$stores[$i]['name'] = stripslashes($row[1]);
		$stores[$i]['description'] = makeDescription(
											stripslashes($row[1]),
											stripslashes($row[2]),
											stripslashes($row[3]),
											stripslashes($row[4]),
											stripslashes($row[5]),
											$row[6],
											$row[7],
											$row[8],
											$row[9],
											$row[10],
											$row[11],
											$row[12],
											$row[13]);
		// $stores[$i]['country'] = $row[7];
		$stores[$i]['lat'] = $row[12];
		$stores[$i]['long'] = $row[13];
		$i++;
	}
	$storeList = json_encode($stores); // Put all the data into a JSON object
	return $storeList; // Send that list to the map!
}

function makeDescription($name,$address1,$address2,$city,$state,$zip,$country,$phone,$fax,$email,$website,$lat,$long) {
	if ($website != "") {
		$description = "<h1><a href=\"http://".$website."\" target=\"_blank\">$name</a></h1>";
	}
	else {
		$description = "<h1>$name</h1>";
	}
	$description = $description."<p>$address1</p>";
	$description = $description."<p>$address2</p>";
	if ($state != "") {
		$stateZip = "$state $zip,";
	}
	else {
		$stateZip = "";
	}
	$description = $description."<p class=\"last\">$city, $stateZip $country</p>";
	if ($phone != "")
		$description = $description."<p>P | $phone</p>";
	if ($fax != "")
		$description = $description."<p>F | $fax</p>";
	if ($email != "")
		$description = $description."<p>E | <a href=\"mailto:$email\" target=\"_blank\">$email</a></p>";
	$description = $description."<p><a href=\"http://maps.google.com/maps?saddr=current+location&daddr=$address1+$city+$state+$zip\" target=\"_blank\">Get Directions</a></p>";
	return $description;
}
echo allStores($_GET['store'],$_GET['length']);

?>