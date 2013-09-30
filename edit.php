<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');

$store = $_GET['store'];
$id = $_GET['id'];
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); // Connect to the database
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; // If there is an error speak up!
	}

// If there is an ID, we're editing. If not, we're creating a new entry
if ($_GET['id']) {
	$edit = "Edit";
	// Since there's an ID, get all the data from the database
	$result = $mysqli->query("SELECT * FROM $store WHERE id = '$id'");
	$row = $result->fetch_array();
	$name = $row[1];
	$add1 = $row[2];
	$add2 = $row[3];
	$city = $row[4];
	$state = $row[5];
	$zip = $row[6];
	$country = $row[7];
	$phone = $row[8];
	$fax = $row[9];
	$email = $row[10];
	$website = $row[11];
	$lat = $row[12];
	$lng = $row[13];
}
else {
	$edit = "New";
}
if ($_POST['attempt'] == "1") {
	$name = $_POST['name'];
	$add1 = $_POST['address1'];
	$add2 = $_POST['address2'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$country = $_POST['country'];
	$phone = $_POST['phone'];
	$fax = $_POST['fax'];
	$email = $_POST['email'];
	$website = $_POST['website'];
	$geocode = $_POST['geocode'];
	$storeData = array( 0 => $id, 1 => $name, 2 => $add1, 3 => $add2, 4 => $city, 5 => $state, 6 => $zip, 7 => $country, 8 => $phone, 9 => $fax, 10 => $email, 11 => $website, 12 => $lat, 13 => $lng );
	 if(updateStore($storeData,$store,$geocode)) {
		 $result = $mysqli->query("SELECT * FROM $store WHERE id = '$id'");
		 $row = $result->fetch_array();
		 $lat = $row[12];
		 $lng = $row[13];
		 $status = "<div class=\"success\">Store updated successfully!</div>";
	 }
	 else {
		 $status = "<div class=\"error\">Store update failed :(</div>";
	 }
}
$mysqli->close();


?>
<!DOCTYPE html>
<head>
<title><?php echo $edit; ?> Store | Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php include ('nav.html'); ?>
	<div id="container" class="content clear">
        <h1><?php echo strtoupper($edit); ?> STORE</h1>
        <p>&nbsp;</p>
        <form id="edit-store" method="post">
        <p><label>Name</label><input name="name" id="name" type="text" value="<?php echo stripslashes($name);?>"></p>
        <p><label>Address 1</label><input name="address1" id="address1" type="text" value="<?php echo stripslashes($add1);?>"></p>
        <p><label>Address 2</label><input name="address2" id="address2" type="text" value="<?php echo stripslashes($add2);?>"></p>
        <p><label>City</label><input name="city" id="city" type="text" value="<?php echo stripslashes($city);?>"></p>
        <p><label>State</label><input name="state" id="state" type="text" value="<?php echo stripslashes($state);?>"></p>
        <p><label>Zip</label><input name="zip" id="zip" type="text" value="<?php echo stripslashes($zip);?>"></p>
        <p><label>Country</label><input name="country" id="country" type="text" value="<?php echo stripslashes($country);?>"></p>
        <p><label>Phone</label><input name="phone" id="phone" type="text" value="<?php echo stripslashes($phone);?>"></p>
        <p><label>Fax</label><input name="fax" id="fax" type="text" value="<?php echo stripslashes($fax);?>"></p>
        <p><label>Email</label><input name="email" id="email" type="email" value="<?php echo stripslashes($email);?>"></p>
        <p><label>Website</label><input name="website" id="website" type="text" value="<?php echo stripslashes($website);?>"></p>
        <input type="text" name="attempt" id="attempt" style="display: none;" value="1">
        <p><input name="geocode" type="checkbox" id="geocode" value="1"> Geocode this location</p>
        <p>Latitude: <?php echo $lat; ?>, Longitude: <?php echo $lng; ?></p>
        <input type="submit" value="SUBMIT" class="button medium">
        <?php echo $status; ?>
        </form>
    </div>
</body>
</html>