<?php
session_start(); // Always keep the session active!
require ('connect.php');

// Parse variables and see if user is attempting to login
if($_POST['username'] && $_POST['password']) {
	$error = false;
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	if ($mysqli->connect_errno) {
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	$result = $mysqli->query('SELECT * FROM admin WHERE id = 1');
	$row = $result->fetch_row();
	if ($_POST['username'] == $row[1] && $_POST['password'] == $row[2]) {
		$_SESSION['store-locator'] = "logged-in";
		header('Loation: http://s33796.gridserver.com/newlocator/index.php');
		die();
	}
	else {
		$error = true;
	}
	$mysqli->close();
}

?>
<!DOCTYPE html>
<head>
<title>Log In | Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
	<div id="login-box">
    	<h1>STORE LOCATOR</h1>
    	<form method="post">
        <p><label for="username">Username</label><input id="username" name="username" type="text"></p>
        <p><label for="password">Password</label><input id="password" name="password" type="password"></p>
        <p><input id="submitButton" type="submit" value="Log In"></p>
        </form>
        <?php if($error) { ?><div class="error">Your username or password is incorrect.</div><?php } ?>
    </div>
</div>
</body>
</html>