<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');
?>
<!DOCTYPE html>
<head>
<title>Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php include ('nav.html'); ?>
	<div id="container" class="content clear">
        <h1>STORE LOCATOR</h1>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <div class="column">
            <h2>Miss Me</h2>
            <p class="stats"><?php getStores("missme"); ?> Stores</p>
            <a href="manage.php?store=missme" class="button medium">Manage Database</a><br>
            <a href="import.php?store=missme" class="button medium">Import from CSV</a><br>
            <a href="export.php?store=missme" class="button medium">Export as CSV</a>
        </div>
        <div class="column">
            <h2>Rock Revival</h2>
            <p class="stats"><?php getStores("rockrevival"); ?> Stores</p>
            <a href="manage.php?store=rockrevival" class="button medium">Manage Database</a><br>
            <a href="import.php?store=rockrevival" class="button medium">Import from CSV</a><br>
            <a href="export.php?store=rockrevival" class="button medium">Export as CSV</a>
        </div>
    </div>
</body>
</html>