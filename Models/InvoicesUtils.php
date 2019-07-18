<?php
include_once "DBUtils.php";

class InvoicesUtils
{
    function uploadCustomersData()
    {
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

            $customer = ["name" => $rows[1], "nif" => $rows[2], "address1" => $rows[0], "address2" => $rows[3]];
            $customer2 = ["name" => $rows[6], "nif" => $rows[7], "address1" => $rows[5], "address2" => $rows[8]];

            if ($customer["nif"] != null) array_push($customers, $customer);
            if ($customer2["nif"] != null) array_push($customers, $customer2);
        }
        $customers = array_filter($customers);
        return $customers;
    }

    function uploadInvoiceData()
    {
        if (isset($_GET["idcliente"])) $idCustomer = $_GET["idcliente"];
        if (isset($_POST["quantities"])) $quantities = $_POST["quantities"];
        if (isset($_POST["descriptions"])) $descriptions = $_POST["descriptions"];
        if (isset($_POST["unitprices"])) $unitPrices = $_POST["unitprices"];
        if (isset($_POST["amounts"])) $amounts = $_POST["amounts"];
        if (isset($_POST["grosstotal"])) $grossTotal = $_POST["grosstotal"];
        if (isset($_POST["igic"])) $igic = $_POST["igic"];
        if (isset($_POST["total"])) $total = $_POST["total"];
        $invoice = array();
        for ($i = 0; $i < sizeof($amounts); $i++) {
            if ($amounts[$i] != "" && $amounts[$i] > 0) {
                $notion = ["quantity" => $quantities[$i], "description" => $descriptions[$i],
                    "unitPrice" => $unitPrices[$i], "amount" => $amounts[$i]];
                $invoice["notions"][$i] = $notion;
            }
        }
        if ($grossTotal != "" && $grossTotal > 0) {
            $totals = ["grossTotal" => $grossTotal, "igic" => $igic, "total" => $total];
            $invoice["totals"] = $totals;
        }
        $dbUtils = new DBUtils();
        $dbUtils->insertInvoiceData($idCustomer, $invoice);
        header("Location:facturas.php");
    }

    function exportInvoicesToExcel()
    {
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $fileName = "prueba2.xlsx";
        $fileURL = '../Files/' . $fileName;
        $objPHPExcel->setActiveSheetIndex(0);
        $date = date("d-m-Y");
        $dbUtils = new DBUtils();
        $sql = "SELECT * FROM invoices INNER JOIN customers ON invoices.cus_id = customers.cus_id";
        $datas = $dbUtils->getDatas($sql);
        $dateCoordinate = 1;
        for ($i = 0; $i < sizeof($datas); $i++) {
            $invoice = unserialize($datas[$i]["inv_obj"]);
            $datas[$i]["inv_obj"] = $invoice;
            $nameNifCoordinate = $dateCoordinate + 1;
            $addressNumberInvoiceCoordinate = $dateCoordinate + 2;
            $headerInvoiceCoordinate = $dateCoordinate + 3;
            $coordinateNotion = $dateCoordinate + 4;
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $dateCoordinate, "Fecha:");
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $dateCoordinate, $date);
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $nameNifCoordinate, "Nombre:");
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $nameNifCoordinate, $datas[$i]["cus_name"]);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $nameNifCoordinate, "NIF:");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $nameNifCoordinate, $datas[$i]["cus_nif"]);
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $addressNumberInvoiceCoordinate, "Dirección:");
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $addressNumberInvoiceCoordinate, $datas[$i]["cus_address1"] . ", " . $datas[$i]["cus_address2"]);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $addressNumberInvoiceCoordinate, "Nº Factura");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $addressNumberInvoiceCoordinate, 1);
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $headerInvoiceCoordinate, "Cantidad");
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $headerInvoiceCoordinate, "Descripción");
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $headerInvoiceCoordinate, "P. Unidad");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $headerInvoiceCoordinate, "Importe");
            for ($j = 0; $j < sizeof($invoice["notions"]); $j++) {
                $notion = $invoice["notions"][$j];
                $coordinateNotion = $coordinateNotion + $j;
                $objPHPExcel->getActiveSheet()->setCellValue("A" . $coordinateNotion, $notion["quantity"]);
                $objPHPExcel->getActiveSheet()->setCellValue("B" . $coordinateNotion, $notion["description"]);
                $objPHPExcel->getActiveSheet()->setCellValue("C" . $coordinateNotion, $notion["unitPrice"]);
                $objPHPExcel->getActiveSheet()->setCellValue("D" . $coordinateNotion, $notion["amount"]);
            }
            $sizeOfNotions = sizeof($invoice["notions"]);
            $lastCoordinateNotion = $coordinateNotion;
            $nextCoordinateNotion = $lastCoordinateNotion+1;
            while ($sizeOfNotions < 14) {
                $objPHPExcel->getActiveSheet()->setCellValue("A" . $nextCoordinateNotion, "");
                $objPHPExcel->getActiveSheet()->setCellValue("B" . $nextCoordinateNotion, "");
                $objPHPExcel->getActiveSheet()->setCellValue("C" . $nextCoordinateNotion, "");
                $objPHPExcel->getActiveSheet()->setCellValue("D" . $nextCoordinateNotion, "");
                $nextCoordinateNotion++;
                $sizeOfNotions++;
            }
            $grossTotalCoordinate = $nextCoordinateNotion+1;
            $igicCoordinate = $nextCoordinateNotion+2;
            $totalCoordinate = $nextCoordinateNotion+3;
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $grossTotalCoordinate, "Total Bruto:");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $grossTotalCoordinate, $invoice["totals"]["grossTotal"]);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $igicCoordinate, "IGIC 6,5%:");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $igicCoordinate, $invoice["totals"]["igic"]);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $totalCoordinate, "TOTAL");
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $totalCoordinate, $invoice["totals"]["total"]);
            $dateCoordinate = $dateCoordinate + 25;
            /*echo "<pre>";
            var_dump($datas);
            echo "</pre>";*/
        }
        $objWriter->save($fileURL);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=" . $fileName);
        header('Cache-Control: cache, must-revalidate');
        $objWriter->save("php://output");
        exit;
    }
}
