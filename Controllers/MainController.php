<?php
include_once("Models/DBUtils.php");
include_once("Models/Invoice.php");
include_once("Models/Outgoing.php");
include_once("Models/Customer.php");
include_once("Models/Supplier.php");
if (isset($_GET["page"]) && $_GET["page"] != "") {
    $page = $_GET["page"];
    $oneCusAddress = "";
    if (isset($_GET["idcliente"])) {
        $idCustomer = $_GET["idcliente"];
        $customer = new Customer();
        $cusAdress1 = $customer->getAddressFromCustomer($idCustomer);
        $oneCusAddress = $cusAdress1[0]["cus_address1"];
    }
    $titles = array("login" => "Login", "contabilidad" => "Contabilidad", "ingresos" => "Ingresos", "factura" => "Cliente: " . $oneCusAddress,
        "registro" => "Cliente: " . $oneCusAddress, "gastos" => "Gastos", "proveedores" => "Proveedores", "compras" => "Compras del mes");
    $title = $titles[$page];
    switch ($page) {
        case "ingresos":
            $customer = new Customer();
            $customers = $customer->getAllFromCustomers();
            $invoice = new Invoice();
            $invoices = $invoice->getAllFromInvoices();
            break;
        case "factura":
            $customer = new Customer();
            $invoice = new Invoice();
            if (isset($_GET["idcliente"])) {
                $cusId = $_GET["idcliente"];
                $customer = $customer->getAllFromSingleCustomer($cusId);
                $invoiceSerialized = $invoice->getInvoiceFromInvoices($cusId);
                $referenceSavedDB = $invoice->getReferenceFromInvoices($cusId);
                $referenceSaved = "";
                if ($referenceSavedDB != null) $referenceSaved = $referenceSavedDB[0]["inv_ref"];
                $title = "Cliente: " . $customer[0]["cus_address1"];
            }
            if ($invoiceSerialized == null) $page = "factura";
            else $page = "registro";
            break;
        case "proveedores":
        case "compras":
            $supplier = new Supplier();
            $suppliers = $supplier->getAllFromSuppliers();
            $supplier->setCookieSuppliers($suppliers);
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
    $customer = new Customer();
    $customer->uploadCustomersData();
}
if (isset($_POST["invoice"])) {
    $invoice = new Invoice();
    $invoice->uploadInvoiceData();
    $_POST["invoice"] = null;
}
if (isset($_POST["exportToExcel"])) {
    $invoice = new Invoice();
    $invoice->exportInvoicesToExcel();
}
if (isset($_POST["supplier"])) {
    $supplier = new Supplier();
    $supplier->createNewSupplier();
}
if (isset($_POST["newSupplier"])) {
    $supplier = new Supplier();
    $supplier->createNewSupplier();
}
if (isset($_POST["modSupplier"])) {
    $supplier = new Supplier();
    $supplier->modifySupplier();
}
if (isset($_GET["deleteSup"])) {
    $supplier = new Supplier();
    $supplier->deleteSupplier();
}
?>

