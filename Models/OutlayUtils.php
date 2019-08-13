<?php
include_once "DBUtils.php";

class OutlayUtils
{
    function createNewSupplier()
    {
        if (isset($_POST["supCifs"])) $cifs = $_POST["supCifs"];
        if (isset($_POST["supNames"])) $names = $_POST["supNames"];
        $dbUtils = new DBUtils();
        for ($i = 0; $i < sizeof($cifs); $i++) {
            if ($cifs[$i] != "" && $names[$i] != "") {
                $sqlVerification = "SELECT * FROM suppliers WHERE sup_cif = '" . $cifs[$i] . "'";
                $datas = $dbUtils->getDatas($sqlVerification);
                if (!isset($datas[0])) {
                    $sql = "INSERT INTO suppliers (sup_cif, sup_name) VALUES ('" . $cifs[$i] . "', '" . $names[$i] . "')";
                    $dbUtils->modifyTableDB($sql);
                }
            }
        }
        header("Location:?page=proveedores");
    }

    function modifySupplier()
    {
        if (isset($_POST["cifs"])) $cifs = $_POST["cifs"];
        if (isset($_POST["names"])) $names = $_POST["names"];
        $dbUtils = new DBUtils();
        foreach ($cifs as $id => $value){
            $sql = "UPDATE suppliers SET sup_cif = '" .$value. "', sup_name = '" .$names[$id]. "' WHERE sup_id = " .$id;
            $dbUtils->modifyTableDB($sql);
        }
        header("Location:?page=proveedores");
    }

    function deleteSupplier()
    {
        if (isset($_POST["del"])) $del = $_POST["del"];
        $dbUtils = new DBUtils();
        foreach ($del as $id => $value){
            $sql = "DELETE FROM suppliers WHERE sup_id = " .$id;
            $dbUtils->modifyTableDB($sql);
        }
        header("Location:?page=proveedores");
    }
}