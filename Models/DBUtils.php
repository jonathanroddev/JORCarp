<?php
require 'DBConnection.php';

class DBUtils
{
    function login()
    {
        if (isset($_POST["usermail"])) $userMail = $_POST["usermail"];
        if (isset($_POST["password"])) $password = md5($_POST["password"]);
        $sql = "SELECT * FROM users WHERE user_mail='" . $userMail . "' AND user_password='" . $password . "'";
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->rowCount();
            if ($result == 1) {
                $_SESSION["userStatus"] = 1;
                $_SESSION["userPrivileges"] = 1;
                header("Location:adminInterface.php");
                exit();
            } else {
                $_POST["errorLogin"] = true;
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    function logout()
    {
        $_SESSION["userStatus"] = 0;
        $_SESSION["userPrivileges"] = 0;
    }

    function importCustomersToDB()
    {
        $customers = $this->exportCustomersFromExcel();
        foreach ($customers as $k => $v) {
                echo "<pre>";
                var_dump($v);
                echo "</pre>";
        }
    }

    function exportCustomersFromExcel()
    {
        include_once 'Customer.php';
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load("../Files/clientes.xlsx");
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $rows = array();
        $customers = array();
        for ($row = 2; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                array_push($rows, $rows[$col]);
            }
            $customer = new Customer();
            $customer2 = new Customer();

            $customer->setName($rows[1]);
            $customer2->setName($rows[6]);
            $customer->setNif($rows[2]);
            $customer2->setNif($rows[7]);
            $customer->setAddress1($rows[0]);
            $customer2->setAddress1($rows[5]);
            $customer->setAddress2($rows[3]);
            $customer2->setAddress2($rows[8]);

            if($customer->getNif()!= null) array_push($customers, $customer);
            if($customer2->getNif()!= null) array_push($customers, $customer2);
        }
        $customers = array_filter($customers);
        return $customers;
    }

    function getDatas($sql)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }
}
