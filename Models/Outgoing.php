<?php
include_once "DBConnection.php";

class Outgoing
{
    function saveOutgoingInDB()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        if (isset($_POST["suppliers"])) $suppliers = $_POST["suppliers"];
        if (isset($_POST["outgoingreferences"])) $references = $_POST["outgoingreferences"];
        if (isset($_POST["outgoingdates"])) $dates = $_POST["outgoingdates"];
        if (isset($_POST["outgoinggross"])) $allGross = $_POST["outgoinggross"];
        if (isset($_POST["outgoingigic"])) $allIgic = $_POST["outgoingigic"];
        if (isset($_POST["outgoingtotals"])) $allTotals = $_POST["outgoingtotals"];
        try {
            for ($i = 0; $i < sizeof($references); $i++) {
                $supplier = $suppliers[$i];
                $reference = $references[$i];
                $date = date('d-m-Y', strtotime($dates[$i]));
                $gross = $allGross[$i];
                $igic = $allIgic[$i];
                $total = $allTotals[$i];
                if ($references[$i] != "") {
                    //Poner aquí condición para que en caso de que la referencia ya esté en la BBDD, haga UPDATE y no INSERT
                    $sql = "INSERT INTO outgoing (sup_id, out_ref, out_date, out_gross, out_igic, out_total) VALUES (
                    " . $supplier . ", '" . $reference . "', '" . $date . "',
                    " . floatval($gross) . ", " . floatval($igic) . ", " . floatval($total) . ")";
                    //echo $sql;
                    $prepareQuery = $pdoConnection->prepare($sql);
                    $prepareQuery->execute();
                }
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }
}