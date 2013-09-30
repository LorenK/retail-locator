<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');


// Parsing the file that was uploaded
if($_FILES["file"]) {
	$status = updateDB($_GET['store'],$_FILES['file']['tmp_name']);
}


?>
<!DOCTYPE html>
<head>
<title>Import | Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php include ('nav.html'); ?>
	<div id="container" class="content clear">
        <h1>CSV IMPORT</h1>
        <h2 style="text-transform:uppercase;">Store: <?php echo $_GET['store']; ?></h2>
        <p>&nbsp;</p>
        
        <form method="post" enctype="multipart/form-data">
        <label for="file">Filename</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="Submit">
        </form>
        
        <?php echo $status; ?>
    </div>
</body>
</html>