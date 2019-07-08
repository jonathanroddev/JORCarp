<?php

class InvoicesUtils
{

    function uploadCustomersData()
    {
        $uploadTo = '../Files/';
        $uploadCustomersFile = $uploadTo . basename($_FILES['customersFile']['name']);
        move_uploaded_file($_FILES['customersFile']['tmp_name'], $uploadCustomersFile);
        $_SESSION["fileUploaded"] = true;
        $_SESSION["fileDirectory"] = $uploadCustomersFile;
        $_SESSION["customersData"] = $this->exportCustomersFromExcel($uploadCustomersFile);
    }

    function exportCustomersFromExcel($customersFileName)
    {
        include_once 'Customer.php';
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

            if ($customer->getNif() != null) array_push($customers, $customer);
            if ($customer2->getNif() != null) array_push($customers, $customer2);
        }
        $customers = array_filter($customers);
        return $customers;
    }

}
