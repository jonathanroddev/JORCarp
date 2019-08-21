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
                if (!$this->getOutgoing($reference)) {
                    $sql = "INSERT INTO outgoing (sup_id, out_ref, out_date, out_gross, out_igic, out_total) VALUES (
                    " . $supplier . ", '" . $reference . "', '" . $date . "',
                    " . floatval($gross) . ", " . floatval($igic) . ", " . floatval($total) . ")";
                }
                else{
                    $sql = "UPDATE outgoing SET sup_id =" . $supplier . ", out_date = '" . $date . "',
                    out_gross = " . floatval($gross) . ", out_igic = " . floatval($igic) . ", out_total = " . floatval($total) .
                    " WHERE out_ref = '" . $reference . "'";
                }
                $prepareQuery = $pdoConnection->prepare($sql);
                $prepareQuery->execute();
            }
            header("Location:?page=gastos");
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    function getOutgoing($reference)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        $result = false;
        try {
            $sql = "SELECT * FROM outgoing WHERE out_ref = '" . $reference . "'";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $outgoing = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
            if (isset($outgoing[0])) $result = true;
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function getAllFromOutgoing()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT * FROM outgoing";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }
}