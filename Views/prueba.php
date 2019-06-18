<?php
$title = "Prueba";
include_once("Layouts/header.php");
$query = "SELECT * FROM customers";
$dbUtils = new DBUtils();
$data = $dbUtils->getDatas($query);
var_dump($data);
include_once("Layouts/footer.php");