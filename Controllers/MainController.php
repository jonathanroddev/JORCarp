<?php
include_once("Models/DBUtils.php");
include_once("Models/Invoice.php");
include_once("Models/Outgoing.php");
include_once("Models/Customer.php");
include_once("Models/Supplier.php");
include_once("Models/Balance.php");
if (isset($_GET["page"]) && $_GET["page"] != "") {
    $page = $_GET["page"];
    $oneCusAddress = "";
    if (isset($_GET["idcliente"])) {
        $idCustomer = $_GET["idcliente"];
        $cusAdress1 = Customer::getAddressFromCustomer($idCustomer);
        $oneCusAddress = $cusAdress1[0]["cus_address1"];
    }
    $titles = array("login" => "Login", "contabilidad" => "Contabilidad", "ingresos" => "Ingresos", "factura" => "Cliente: " . $oneCusAddress,
        "registrofactura" => "Cliente: " . $oneCusAddress, "gastos" => "Gastos", "proveedores" => "Proveedores", "compras" => "Compras del mes",
        "registrocompras" => "Compras del mes", "balance" => "Balance del mes", "registrousuario" => "Nuevo Usuario");
    $title = $titles[$page];
    switch ($page) {
        case "ingresos":
            $customers = Customer::getAllFromCustomers();
            $invoices = Invoice::getAllFromInvoices();
            break;
        case "factura":
            if (isset($_GET["idcliente"])) {
                $cusId = $_GET["idcliente"];
                $customer = Customer::getAllFromSingleCustomer($cusId);
                $invoiceSerialized = Invoice::getInvoiceFromInvoices($cusId);
                $referenceSavedDB = Invoice::getReferenceFromInvoices($cusId);
                $referenceSaved = "";
                if ($referenceSavedDB != null) $referenceSaved = $referenceSavedDB[0]["inv_ref"];
                $title = "Cliente: " . $customer[0]["cus_address1"];
            }
            if ($invoiceSerialized == null) $page = "factura";
            else $page = "registrofactura";
            break;
        case "proveedores":
            $suppliers = Supplier::getAllFromSuppliers();
            break;
        case "compras":
            $suppliers = Supplier::getAllFromSuppliers();
            Supplier::setCookieSuppliers($suppliers);
            $allOutgoingSaved = Outgoing::getAllFromOutgoing();
            if ($allOutgoingSaved == null) $page = "compras";
            else $page = "registrocompras";
            break;
    }
} else {
    $page = "login";
}
if (isset($_POST["login"])) {
    if (isset($_POST["usermail"])) $userMail = $_POST["usermail"];
    if (isset($_POST["password"])) $password = md5($_POST["password"]);
    DBUtils::login($userMail,$password);
}
if (isset($_GET["logout"])) {
    DBUtils::logout();
}
if (isset($_POST["uploadFile"])) {
    Customer::uploadCustomersData();
}
if (isset($_POST["invoice"])) {
    Invoice::uploadInvoiceData();
    $_POST["invoice"] = null;
}
if (isset($_POST["exportToExcel"])) {
    Invoice::exportInvoicesToExcel();
}
if (isset($_POST["supplier"])) {
   Supplier::createNewSupplier();
}
if (isset($_POST["newSupplier"])) {
    Supplier::createNewSupplier();
}
if (isset($_POST["modSupplier"])) {
    Supplier::modifySupplier();
}
if (isset($_GET["deleteSup"])) {
    Supplier::deleteSupplier();
}
if (isset($_POST["saveOutgoing"])) {
    Outgoing::saveOutgoingInDB();
}
if (isset($_POST["createBalance"])) {
   Balance::createBalanceFile();
}
if (isset($_POST["newUser"])) {
    $userMail = $_POST["usermail"];
    $userRegistered = DBUtils::getUser($userMail);
    if(!$userRegistered) {
        DBUtils::createNewUser();
    }
    else {
        $page = "registrousuario";
    }
}
?>

