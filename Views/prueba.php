<?php
$title = "Prueba";
include_once("Layouts/header.php");

$dbUtils = new DBUtils();
$dbUtils->exportCustomersFromExcel();


include_once("Layouts/footer.php");