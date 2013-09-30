<?php
session_start(); // Always keep the session active!
require ('protect.php');
require ('functions.php');

$store = $_GET['store'];
backup($store);
$file_path = "backup/" . $store . ".csv";



header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=$store.csv");
header("Content-Transfer-Encoding: binary");
readfile($file_path);


?>