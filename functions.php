<?php

require('connect.php');

function getStores($store) {
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$result = $mysqli->query("SELECT * FROM $store");
	$i = 0;
	while ($row = $result->fetch_array()) {
        $i++;
    }
	$mysqli->close();
	echo $i;
}

function updateDB($store,$file) {
	ini_set("auto_detect_line_endings", true); // Be able to detect line breaks
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	$rows = 0;// Keep track of how many rows are added
	$errors = 0; // Keep track of errors
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	
	if (($handle = fopen("$file", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if ($data[0] != "ID") {
				for ($c=0; $c < 14; $c++) {
					$data[$c] = $mysqli->real_escape_string($data[$c]);
					echo $data[c];
				}
				
				$compiledAddress = $data[2].', '.$data[4].', '.$data[5].' '.$data[6].', '.$data[7]; // Address1, City, Sate Zip, Country
				$geocode = geocode($compiledAddress);
				$lat = $geocode['lat'];
				$long = $geocode['lng'];
				
				if ($data[0] == "") { // No ID was declared, this means it's a new entry
					$query = "INSERT INTO $store VALUES ('','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$lat','$long')";
					if($mysqli->query($query)) {
						$rows++;
					}
					else {
						$errors++;
					}
				}
				else { // and ID was declared, this means update the existing entry
					$query = "UPDATE $store SET store = '$data[1]', address1 = '$data[2]', address2 = '$data[3]', city = '$data[4]', state = '$data[5]', zip = '$data[6]', country = '$data[7]', phone = '$data[8]', fax = '$data[9]', email = '$data[10]', website = '$data[11]', lat = '$lat', lng = '$long' WHERE id = '$data[0]'";
					if($mysqli->query($query)) {
						$rows++;
					}
					else {
						$errors++;
					}
				}
			}
		}
		$mysqli->query("OPTIMIZE TABLE $store");
		fclose($handle);
		$mysqli->close();
		return "<div class=\"import-status\"><span style=\"color:#0F0;\">$rows</span> rows successfully imported with <span style=\"color:#F00;\">$errors</span> errors!</div>";
	}
}

function updateStore($data,$store,$reGeo) {
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	
	$i=1;
	while($i<12) {
		$data[$i] = $mysqli->real_escape_string($data[$i]);
		$i++;
	}
	
	if ($data[0] == "") { // This is a new addition
		$compiledAddress = $data[2].', '.$data[4].', '.$data[5].' '.$data[6].', '.$data[7]; // Address1, City, Sate Zip, Country
		$geocode = geocode($compiledAddress);
		$lat = $geocode['lat'];
		$long = $geocode['lng'];
		$query = "INSERT INTO $store VALUES ('','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$lat','$long')";
	}
	else {
	
		if ($reGeo == "1") { // User check to re-geocode this address
			$compiledAddress = $data[2].', '.$data[4].', '.$data[5].' '.$data[6].', '.$data[7]; // Address1, City, Sate Zip, Country
			$geocode = geocode($compiledAddress);
			$lat = $geocode['lat'];
			$long = $geocode['lng'];
			$query = "UPDATE $store SET store = '$data[1]', address1 = '$data[2]', address2 = '$data[3]', city = '$data[4]', state = '$data[5]', zip = '$data[6]', country = '$data[7]', phone = '$data[8]', fax = '$data[9]', email = '$data[10]', website = '$data[11]', lat = '$lat', lng = '$long' WHERE id = '$data[0]'";
		}
		else {
			$query = "UPDATE $store SET store = '$data[1]', address1 = '$data[2]', address2 = '$data[3]', city = '$data[4]', state = '$data[5]', zip = '$data[6]', country = '$data[7]', phone = '$data[8]', fax = '$data[9]', email = '$data[10]', website = '$data[11]' WHERE id = '$data[0]'";
		}

	}
	if ($mysqli->query($query)) {
		$success = true;
	}
	else {
		$success = false;
	}
	$mysqli->close();
	if($success == true)
		return true;
	else
		return false;
}

function showDatabase($store,$show) {
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	$numStores = $mysqli->query("SELECT * FROM $store");
	if ($show != "ALL") {
		$result = $mysqli->query("SELECT * FROM $store LIMIT $show");
	}
	else {
		$result = $mysqli->query("SELECT * FROM $store");
	}
	if ($show > $numStores->num_rows)
		$newShow = $numStores->num_rows;
	else
		$newShow = $show;
	if ($result) {
		echo "<form id=\"db-form\"method=\"post\">";
		
		echo "<div id=\"manage-tools\">";
		echo "<a href=\"edit.php?store=$store\" class=\"button small\">ADD NEW STORE</a>";
		echo "<span class=\"show-stores\">Show <select name=\"store-number\" id=\"store-number\">";
		echo ($show == "25" ? "<option value=\"25\" selected=\"selected\">25</option>" : "<option value=\"25\">25</option>");
		echo ($show == "50" ? "<option value=\"50\" selected=\"selected\">50</option>" : "<option value=\"50\">50</option>");
		echo ($show == "100" ? "<option value=\"100\" selected=\"selected\">100</option>" : "<option value=\"100\">100</option>");
		echo ($show == "250" ? "<option value=\"250\" selected=\"selected\">250</option>" : "<option value=\"250\">250</option>");
		echo ($show == "500" ? "<option value=\"500\" selected=\"selected\">500</option>" : "<option value=\"500\">500</option>");
		echo ($show == "1000" ? "<option value=\"1000\" selected=\"selected\">1000</option>" : "<option value=\"1000\">1000</option>");
		echo ($show == "ALL" ? "<option value=\"ALL\" selected=\"selected\">ALL</option>" : "<option value=\"ALL\">ALL</option>");
		echo "</select> stores at a time</span>";
		echo "</div>";
		
		echo "<table class=\"db-table\" cellspacing=\"0\">";
		echo "<tr>
			<td><input type=\"checkbox\" name=\"check-all\" id=\"check-all\"></td>
			<td><a href=\"#\" id=\"delete-stores\">Delete Selected</a></td>
			<td>Displaying $newShow of $numStores->num_rows stores</td>
			<td></td>
			<td></td>
			</tr>";
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td class=\"db-col1\"><input type=\"checkbox\" name=\"$row[0]\" class=\"store-checkbox\"></td>";
			echo "<td class=\"db-col2\">".stripslashes($row[1])."</td>";
			echo "<td class=\"db-col3\">".stripslashes($row[2])."</td>";
			if ($row[12] != "" && $row[13] != "") {
				echo "<td class=\"db-col4\"><img src=\"images/globe.png\" /></td>";
			}
			else {
				echo "<td class=\"db-col4\"></td>";
			}
			echo "<td class=\"db-col5\"><a href=\"edit.php?id=$row[0]&store=$store\">Edit</a></td>";
			echo "</tr>";			
		}
		echo "</table>";
	}
	else {
		echo "There are no stores in this database";
	}
	echo "</form>";
	$mysqli->close();
}

function geocode($address) { // This is really awesome!
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	$result = $mysqli->query("SELECT api FROM admin WHERE id = '1'");
	$row = $result->fetch_array();
	$apiKey = $row[0]; // Got the API key form the database!
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';
	$data = file_get_contents($url); // Send the request for geocoding
	$loc = json_decode($data); // Decode the json result
	$mysqli->close();
	$geometry = $loc->results[0];
	$lat = $geometry->geometry->location->lat;
	$long = $geometry->geometry->location->lng;
	$stuff = array('lat' => $lat, 'lng' => $long);
	return $stuff;
}

function backup($store) {
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	$result = $mysqli->query("SELECT * FROM $store");
	$path = "backup/".$store.".csv";
	$file = fopen($path, 'w+');
	fputcsv($file,array('ID','STORE NAME','ADDRESS1','ADDRESS2','CITY','STATE','ZIP','COUNTRY','PHONE','EMAIL','FAX','WEBSITE','LATITUDE','LONGITUDE'));
	while ($row = $result->fetch_assoc()) {
		fputcsv($file,$row);
	}
	fclose($file);
	$mysqli->close();
}

function deleteStore($store,$table) {
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}
	$query = "DELETE FROM $table WHERE id = '$store'";
	if ($mysqli->query($query))
		return true;
	else
		return false;
}
?>