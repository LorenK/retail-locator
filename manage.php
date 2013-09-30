<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');
// Get the store name as always
$store = $_GET['store'];
if($_GET['show']) {
	$show = $_GET['show'];
}
else {
	$show = 25;
}
if ($_GET['action'] == "deleted") {
	$status = "<div class=\"success\">".($_GET['count']-1)." stores successfully deleted!</div>";
}
else {
}


?>
<!DOCTYPE html>
<head>
<title>Manage Database | Store Locator</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
</head>
<body>
	<?php include ('nav.html'); ?>
	<div id="container" class="content clear">
        <h1>MANAGE DATABASE</h1>
        <h2 style="text-transform:uppercase;">Store: <?php echo $_GET['store']; ?></h2>
        <?php echo $status; ?>
        <p>&nbsp;</p>
        <?php showDatabase($store,$show); ?>
    </div>
<script>
	$('#store-number').change(function(){
		window.location = 'manage.php?store=<?php echo $store; ?>&show='+$(this).val();
	});
	
	$('#check-all').change(function() {
		if($('#check-all').prop('checked')) {
			$('.store-checkbox').each(function() {
				$(this).prop('checked','true');
			});
		}
		else {
			$('.store-checkbox').each(function() {
				$(this).removeProp('checked');
			});
		}
	});
	
	$('#delete-stores').click(function() {
		var num = 0;
		var noStores = "";
		$('.store-checkbox').each(function() {
			if ($(this).prop('checked')) {
				num++;
				noStores += $(this).prop('name')+"+";
			}
		});
		if (confirm("Really delete " + num + " stores from the database?")) {
			window.location = "delete.php?store=<?php echo $store; ?>&stores="+noStores;
		}
	});
</script>
</body>
</html>