<?php
include_once ("../Models/DBUtils.php");
include_once ("../Models/InvoicesUtils.php");
if(isset($_POST["login"])){
    $dbUtils = new DBUtils();
    $dbUtils->login();
}
if(isset($_GET["logout"])){
    $dbUtils = new DBUtils();
    $dbUtils->logout();
}
if(isset($_POST["uploadFile"])){
    $invUtils = new InvoicesUtils();
    $invUtils->uploadCustomersData();
}
if(isset($_POST["invoice"])){
    if(isset($_GET["idcliente"])){
        if(isset($_POST["quantities"])) $quantities = $_POST["quantities"];
        if(isset($_POST["descriptions"])) $descriptions = $_POST["descriptions"];
        if(isset($_POST["unitprices"])) $unitPrices = $_POST["unitprices"];
        if(isset($_POST["amounts"])) $amounts = $_POST["amounts"];
        var_dump($quantities);
        var_dump($descriptions);
        var_dump($unitPrices);
        var_dump($amounts);
    }
}
?>
