<?php
include_once "DBConnection.php";

class Supplier
{
    function getAllFromSuppliers()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT * FROM suppliers";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function createNewSupplier()
    {
        if (isset($_POST["supCifs"])) $cifs = $_POST["supCifs"];
        if (isset($_POST["supNames"])) $names = $_POST["supNames"];
        $dbUtils = new DBUtils();
        for ($i = 0; $i < sizeof($cifs); $i++) {
            if ($cifs[$i] != "" && $names[$i] != "") {
                $datas = $this->getAllFromSingleSupplier($cifs[$i]);
                if (!isset($datas[0])) {
                    $dbConn = new DBConnection();
                    $pdoConnection = $dbConn->PdoConnection();
                    try {
                        $sql = "INSERT INTO suppliers (sup_cif, sup_name) VALUES ('" . $cifs[$i] . "', '" . $names[$i] . "')";
                        $prepareQuery = $pdoConnection->prepare($sql);
                        $prepareQuery->execute();
                    } catch (Exception $e) {
                        echo '<hr>Reading Error: (' . $e->getMessage() . ')';
                        return false;
                    }
                }
            }
        }
        header("Location:?page=proveedores");
    }

    function getAllFromSingleSupplier($cif)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT * FROM suppliers WHERE sup_cif = '" . $cif . "'";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function modifySupplier()
    {
        if (isset($_POST["cifs"])) $cifs = $_POST["cifs"];
        if (isset($_POST["names"])) $names = $_POST["names"];
        foreach ($cifs as $id => $value) {
            $dbConn = new DBConnection();
            $pdoConnection = $dbConn->PdoConnection();
            try {
                $sql = "UPDATE suppliers SET sup_cif = '" . $value . "', sup_name = '" . $names[$id] . "' WHERE sup_id = " . $id;
                $prepareQuery = $pdoConnection->prepare($sql);
                $prepareQuery->execute();
            } catch (Exception $e) {
                echo '<hr>Reading Error: (' . $e->getMessage() . ')';
                return false;
            }
        }
        header("Location:?page=proveedores");
    }

    function deleteSupplier()
    {
        if (isset($_POST["del"])) $del = $_POST["del"];
        foreach ($del as $id => $value) {
            $dbConn = new DBConnection();
            $pdoConnection = $dbConn->PdoConnection();
            try {
                $sql = "DELETE FROM suppliers WHERE sup_id = " . $id;
                $prepareQuery = $pdoConnection->prepare($sql);
                $prepareQuery->execute();
            } catch (Exception $e) {
                echo '<hr>Reading Error: (' . $e->getMessage() . ')';
                return false;
            }
        }
        header("Location:?page=proveedores");
    }

    function setCookieSuppliers($suppliers){
        setcookie("suppliers",json_encode($suppliers));
    }
}