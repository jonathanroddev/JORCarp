<?php

class DBConnection
{

    public static function PdoConnection()
    {
        require 'DBConfiguration.php';

        try {
            $pdoConnection = new PDO($driverConnectionString, $userName, $password);
            $pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdoConnection->exec('SET CHARACTER SET utf8');

        } catch (Exception $e) {
            echo '<hr>Error: (' . $e->getMessage() . ')';
            $pdoConnection = 0;
        }
        return $pdoConnection;
    }

}