<?php

class InvoicesUtils
{

    function uploadCustomersData()
    {
        include_once "DBUtils.php";
        $dbUtils = new DBUtils();
        $uploadTo = '../Files/';
        $uploadCustomersFile = $uploadTo . basename($_FILES['customersFile']['name']);
        move_uploaded_file($_FILES['customersFile']['tmp_name'], $uploadCustomersFile);
        $_SESSION["fileDirectory"] = $uploadCustomersFile;
        $customers = $this->exportCustomersFromExcel($uploadCustomersFile);
        $dbUtils->insertCustomersDatas($customers);
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

            $customer = ["name"=>$rows[1],"nif"=>$rows[2],"address1"=>$rows[0],"address2"=>$rows[3]];
            $customer2 = ["name"=>$rows[6],"nif"=>$rows[7],"address1"=>$rows[5],"address2"=>$rows[8]];

            if ($customer["nif"] != null) array_push($customers, $customer);
            if ($customer2["nif"] != null)  array_push($customers, $customer2);
        }
        $customers = array_filter($customers);
        return $customers;
    }

}
