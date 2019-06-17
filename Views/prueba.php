<?php
include_once ("../Models/DBUtils.php");
$query = "SELECT * FROM customers";
$dbUtils = new DBUtils();
$data = $dbUtils->getData($query);
var_dump($data);