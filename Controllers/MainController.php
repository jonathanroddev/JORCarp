<?php
include_once("Models/DBUtils.php");
include_once("Models/InvoicesUtils.php");
if (isset($_GET["page"]) && $_GET["page"] != "") {
    $page = $_GET["page"];
    $oneCusAddress = "";
    if (isset($_GET["idcliente"])) {
        $idCustomer = $_GET["idcliente"];
        $dbUtils = new DBUtils();
        $sql = "SELECT cus_address1 FROM customers WHERE cus_id = " . $idCustomer;
        $cusAdress1 = $dbUtils->getDatas($sql);
        $oneCusAddress = $cusAdress1[0]["cus_address1"];
    }
    $titles = array("login" => "Login", "contabilidad" => "Contabilidad", "facturas" => "Facturas", "cliente" => "Cliente: " . $oneCusAddress, "registro" => "Cliente: " . $oneCusAddress);
    $title = $titles[$page];
    switch ($page) {
        case "facturas":
            $dbUtils = new DBUtils();
            $sql = "SELECT * FROM customers";
            $customers = $dbUtils->getDatas($sql);
            $sql2 = "SELECT * FROM invoices";
            $invoices = $dbUtils->getDatas($sql2);
            break;
        case "cliente":
            $dbUtils = new DBUtils();
            if (isset($_GET["idcliente"])) {
                $cusId = $_GET["idcliente"];
                $sql = "SELECT * FROM customers WHERE cus_id=" . $cusId;
                $customer = $dbUtils->getDatas($sql);
                $sql2 = "SELECT inv_obj FROM invoices WHERE cus_id=" . $cusId;
                $invoiceSerialized = $dbUtils->getDatas($sql2);
                $sql3 = "SELECT inv_ref FROM invoices WHERE cus_id=" . $cusId;
                $referenceSavedDB = $dbUtils->getDatas($sql3);
                $referenceSaved = "";
                if($referenceSavedDB!=null) $referenceSaved = $referenceSavedDB[0]["inv_ref"];
                $title = "Cliente: " . $customer[0]["cus_address1"];
            }
            if ($invoiceSerialized == null) $page = "cliente";
            else $page = "registro";
            break;
    }
} else {
    $page = "login";
}
if (isset($_POST["login"])) {
    $dbUtils = new DBUtils();
    $dbUtils->login();
}
if (isset($_GET["logout"])) {
    $dbUtils = new DBUtils();
    $dbUtils->logout();
}
if (isset($_POST["uploadFile"])) {
    $invUtils = new InvoicesUtils();
    $invUtils->uploadCustomersData();
}
if (isset($_POST["invoice"])) {
    $invUtils = new InvoicesUtils();
    $invUtils->uploadInvoiceData();
    $_POST["invoice"] = null;
}
if (isset($_POST["exportToExcel"])) {
    $invUtils = new InvoicesUtils();
    $invUtils->exportInvoicesToExcel();
}
?>

