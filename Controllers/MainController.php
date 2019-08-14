<?php
include_once("Models/DBUtils.php");
include_once("Models/InvoicesUtils.php");
include_once("Models/OutgoingUtils.php");
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
    $titles = array("login" => "Login", "contabilidad" => "Contabilidad", "ingresos" => "Ingresos", "factura" => "Cliente: " . $oneCusAddress,
        "registro" => "Cliente: " . $oneCusAddress, "gastos" => "Gastos", "proveedores" => "Proveedores", "compras" => "Compras del mes");
    $title = $titles[$page];
    switch ($page) {
        case "ingresos":
            $dbUtils = new DBUtils();
            $sql = "SELECT * FROM customers";
            $customers = $dbUtils->getDatas($sql);
            $sql2 = "SELECT * FROM invoices";
            $invoices = $dbUtils->getDatas($sql2);
            break;
        case "factura":
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
                if ($referenceSavedDB != null) $referenceSaved = $referenceSavedDB[0]["inv_ref"];
                $title = "Cliente: " . $customer[0]["cus_address1"];
            }
            if ($invoiceSerialized == null) $page = "factura";
            else $page = "registro";
            break;
        case "proveedores":
        case "compras":
            $dbUtils = new DBUtils();
            $sql = "SELECT * FROM suppliers";
            $suppliers = $dbUtils->getDatas($sql);
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
if (isset($_POST["supplier"])) {
    $outUtils = new OutgoingUtils();
    $outUtils->createNewSupplier();
}
if (isset($_POST["newSupplier"])) {
    $outUtils = new OutgoingUtils();
    $outUtils->createNewSupplier();
}
if (isset($_POST["modSupplier"])) {
    $outUtils = new OutgoingUtils();
    $outUtils->modifySupplier();
}
if (isset($_GET["deleteSup"])) {
    $outUtils = new OutgoingUtils();
    $outUtils->deleteSupplier();
}
?>

