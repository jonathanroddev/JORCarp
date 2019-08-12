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
                    $dbUtils->insertDatas($sql);
                }
            }
            header("Location:?page=proveedores");
        }
    }
}