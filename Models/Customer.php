<?php
include_once "DBConnection.php";

class Customer
{
    function getAddressFromCustomer($idCustomer)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT cus_address1 FROM customers WHERE cus_id = " . $idCustomer;
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function getAllFromCustomers()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT * FROM customers";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function getAllFromSingleCustomer($cusId)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SELECT * FROM customers WHERE cus_id=" . $cusId;
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function uploadCustomersData()
    {
        $uploadTo = 'Files/';
        $uploadCustomersFile = $uploadTo . basename($_FILES['customersFile']['name']);
        move_uploaded_file($_FILES['customersFile']['tmp_name'], $uploadCustomersFile);
        $_SESSION["fileDirectory"] = $uploadCustomersFile;
        $customers = $this->exportCustomersFromExcel($uploadCustomersFile);
        $this->insertCustomersDatas($customers);
        $_SESSION["fileUploaded"] = true;
    }

    function exportCustomersFromExcel($customersFileName)
    {
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($customersFileName);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $rows = array();
        $customers = array();
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                array_push($rows, $rows[$col]);
            }

            $customer = ["name" => $rows[1], "nif" => $rows[2], "address1" => $rows[0], "address2" => $rows[3]];
            //Comentado porque en una versión previa los datos se recogían de dos bloques de columnas diferentes.
            //$customer2 = ["name" => $rows[6], "nif" => $rows[7], "address1" => $rows[5], "address2" => $rows[8]];

            if ($customer["nif"] != null) array_push($customers, $customer);
            //if ($customer2["nif"] != null) array_push($customers, $customer2);
        }
        $customers = array_filter($customers);
        return $customers;
    }

    function insertCustomersDatas($customers)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $customersData = $this->getAllFromCustomers();
            if ($customersData == null) {
                for ($i = 0; $i < sizeof($customers); $i++) {
                    $name = $customers[$i]["name"];
                    $nif = $customers[$i]["nif"];
                    $address1 = $customers[$i]["address1"];
                    $address2 = $customers[$i]["address2"];
                    $sql = "INSERT INTO customers (cus_name,cus_nif,cus_address1,cus_address2) VALUES ('" . $name . "','" . $nif . "','" . $address1 . "','" . $address2 . "')";
                    $prepareQuery = $pdoConnection->prepare($sql);
                    $prepareQuery->execute();
                }
                header("Location:?page=ingresos");
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    function deleteCustomersTable()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql = "SET FOREIGN_KEY_CHECKS = 0; 
            TRUNCATE table customers; 
            SET FOREIGN_KEY_CHECKS = 1;";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }
}