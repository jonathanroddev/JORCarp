<?php

class DBUtils
{
    public static function login($userMail,$password)
    {
        if (isset($_POST["usermail"])) $userMail = $_POST["usermail"];
        if (isset($_POST["password"])) $password = md5($_POST["password"]);
        $sql = "SELECT * FROM users WHERE user_mail='" . $userMail . "' AND user_password='" . $password . "'";
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $datas = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
            $result = $prepareQuery->rowCount();
            if ($result == 1) {
                $_SESSION["userStatus"] = $datas[0]["user_status"];
                $_SESSION["userPrivileges"] = $datas[0]["user_privileges"];
                header("Location:?page=contabilidad");
                exit();
            } else {
                $_POST["errorLogin"] = true;
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    public static function logout()
    {
        $_SESSION["userStatus"] = 0;
        $_SESSION["userPrivileges"] = 0;
        $_SESSION["fileUploaded"] = false;
        if (isset($_SESSION["fileDirectory"])) unlink($_SESSION["fileDirectory"]);
        $_SESSION["fileDirectory"] = null;
        if (isset($_SESSION["invoiceExcelFile"])) unlink($_SESSION["invoiceExcelFile"]);
        $_SESSION["invoiceExcelFile"] = null;
        if (isset($_SESSION["outgoingExcelFile"])) unlink($_SESSION["outgoingExcelFile"]);
        $_SESSION["outgoingExcelFile"] = null;
        if (isset($_SESSION["balanceExcelFile"])) unlink($_SESSION["balanceExcelFile"]);
        $_SESSION["balanceExcelFile"] = null;
        if (isset($_SESSION["grossIncome"])) $_SESSION["grossIncome"] = null;
        if (isset($_SESSION["igicIncome"])) $_SESSION["grossIncome"] = null;
        if (isset($_SESSION["totalIncome"])) $_SESSION["grossIncome"] = null;
        if (isset($_SESSION["grossOutgoing"])) $_SESSION["grossIncome"] = null;
        if (isset($_SESSION["igicOutgoing"])) $_SESSION["grossIncome"] = null;
        Customer::deleteCustomersTable();
        Invoice::deleteInvoicesTable();
        Outgoing::deleteOutgoingTable();
    }

    public static function getUser($userMail)
    {
        $pdoConnection = DBConnection::PdoConnection();
        $result = false;
        try {
            $sql = "SELECT * FROM users WHERE user_mail = '" . $userMail . "'";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $outgoing = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
            if (isset($outgoing[0])){
                $result = true;
                $_POST["errorUser"] = true;
            }
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function createNewUser()
    {
        if (isset($_POST["username"])) $userName = $_POST["username"];
        if (isset($_POST["usermail"])) $userMail = $_POST["usermail"];
        if (isset($_POST["userpass"])) $userPass = md5($_POST["userpass"]);
        $status = 1;
        $privileges = 1;
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "INSERT INTO users (user_name, user_mail, user_password, user_status, user_privileges) VALUES (
            '" . $userName . "', '" . $userMail . "', '" . $userPass . "'," . $status . "," . $privileges . ")";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            DBUtils::login($userName,$userPass);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    public
    static function getDatas($sql)
    {
        $pdoConnection = DBConnection::PdoConnection();
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
}
