<?php
class DBUtils
{
    public static function login()
    {
        if (isset($_POST["usermail"])) $userMail = $_POST["usermail"];
        if (isset($_POST["password"])) $password = md5($_POST["password"]);
        $sql = "SELECT * FROM users WHERE user_mail='" . $userMail . "' AND user_password='" . $password . "'";
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->rowCount();
            if ($result == 1) {
                $_SESSION["userStatus"] = 1;
                $_SESSION["userPrivileges"] = 1;
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
        Customer::deleteCustomersTable();
        Invoice::deleteInvoicesTable();
        Outgoing::deleteOutgoingTable();
    }

    public static function getDatas($sql)
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
