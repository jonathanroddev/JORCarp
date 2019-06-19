<?php
include_once ("../Models/DBUtils.php");
if(isset($_POST["login"])){
    $dbUtils = new DBUtils();
    $dbUtils->login();
}
if(isset($_GET["logout"])){
    $dbUtils = new DBUtils();
    $dbUtils->logout();
}
?>
