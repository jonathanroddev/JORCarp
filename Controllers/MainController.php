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
    $invUtils = new InvoicesUtils();
    $invUtils->uploadInvoiceData();
}
?>
