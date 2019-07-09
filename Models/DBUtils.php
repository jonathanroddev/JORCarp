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
                header("Location:contabilidad.php");
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
        $_SESSION["fileUploaded"] = false;
        if(isset($_SESSION["fileDirectory"])) unlink($_SESSION["fileDirectory"]);
        $_SESSION["fileDirectory"] = null;
        $this->deleteCustomersTable();
    }

    function insertCustomersDatas($customers)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sqlVerification = "SELECT * FROM customers";
            $customersData = $this->getDatas($sqlVerification);
            if($customersData==null) {
            for($i=0;$i<sizeof($customers);$i++){
                $name = $customers[$i]["name"];
                $nif = $customers[$i]["nif"];
                $address1 = $customers[$i]["address1"];
                $address2 = $customers[$i]["address2"];
                    $sql = "INSERT INTO customers (cus_name,cus_nif,cus_address1,cus_address2) VALUES ('" . $name . "','" . $nif . "','" . $address1 . "','" . $address2 . "')";
                    $prepareQuery = $pdoConnection->prepare($sql);
                    $prepareQuery->execute();
                }
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    function getDatas($sql)
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    function deleteCustomersTable()
    {
        $dbConn = new DBConnection();
        $pdoConnection = $dbConn->PdoConnection();
        try {
            $sql= "TRUNCATE TABLE customers";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }
}
