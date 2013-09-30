<?php

require ('protect.php');
require ('functions.php');

$table = $_GET['store'];

$stores = explode(' ',$_GET['stores']);
foreach ($stores as $store) {
	deleteStore($store,$table);
}
header('Location: http://s33796.gridserver.com/newlocator/manage.php?action=deleted&count='.count($stores)."&store=$table");

?>