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
        if(isset($_POST["descriptions"])){
            echo $_GET["idcliente"];
            echo $_POST["descriptions"][0];
        }
    }
}
?>
