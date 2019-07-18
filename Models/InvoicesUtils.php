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
        $activeSheet = $objPHPExcel->getActiveSheet();
        $activeSheet->getColumnDimension('A')->setWidth(15);
        $activeSheet->getColumnDimension('B')->setWidth(50);
        $activeSheet->getColumnDimension('C')->setWidth(15);
        $activeSheet->getColumnDimension('D')->setWidth(15);
        $borderStyle = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => array('argb' => '#000'),)));
        $headerStyle = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $textAlignStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        for ($i = 0; $i < sizeof($datas); $i++) {
            $invoice = unserialize($datas[$i]["inv_obj"]);
            $datas[$i]["inv_obj"] = $invoice;
            $nameNifCoordinate = $dateCoordinate + 1;
            $addressNumberInvoiceCoordinate = $dateCoordinate + 2;
            $headerInvoiceCoordinate = $dateCoordinate + 3;
            $activeSheet->setCellValue("A" . $dateCoordinate, "Fecha:");
            $activeSheet->getStyle("A" . $dateCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("B" . $dateCoordinate, $date);
            $activeSheet->setCellValue("A" . $nameNifCoordinate, "Nombre:");
            $activeSheet->getStyle("A" . $nameNifCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("B" . $nameNifCoordinate, $datas[$i]["cus_name"]);
            $activeSheet->setCellValue("C" . $nameNifCoordinate, "NIF:");
            $activeSheet->getStyle("C" . $nameNifCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $nameNifCoordinate, $datas[$i]["cus_nif"]);
            $activeSheet->getStyle("D" . $nameNifCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("A" . $addressNumberInvoiceCoordinate, "Dirección:");
            $activeSheet->getStyle("A" . $addressNumberInvoiceCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("B" . $addressNumberInvoiceCoordinate, $datas[$i]["cus_address1"] . ", " . $datas[$i]["cus_address2"]);
            $activeSheet->setCellValue("C" . $addressNumberInvoiceCoordinate, "Nº Factura");
            $activeSheet->getStyle("C" . $addressNumberInvoiceCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $addressNumberInvoiceCoordinate, "");
            $activeSheet->getStyle("D" . $addressNumberInvoiceCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("A" . $headerInvoiceCoordinate, "Cantidad");
            $activeSheet->getStyle("A" . $headerInvoiceCoordinate)->applyFromArray($headerStyle);
            $activeSheet->setCellValue("B" . $headerInvoiceCoordinate, "Descripción");
            $activeSheet->getStyle("B" . $headerInvoiceCoordinate)->applyFromArray($headerStyle);
            $activeSheet->setCellValue("C" . $headerInvoiceCoordinate, "P. Unidad");
            $activeSheet->getStyle("C" . $headerInvoiceCoordinate)->applyFromArray($headerStyle);
            $activeSheet->setCellValue("D" . $headerInvoiceCoordinate, "Importe");
            $activeSheet->getStyle("D" . $headerInvoiceCoordinate)->applyFromArray($headerStyle);
            for ($j = 0; $j < sizeof($invoice["notions"]); $j++) {
                $notion = $invoice["notions"][$j];
                $coordinateNotion = $dateCoordinate + 4 + $j;
                $activeSheet->setCellValue("A" . $coordinateNotion, number_format($notion["quantity"],2,",","."));
                $activeSheet->getStyle("A" . $coordinateNotion)->applyFromArray($textAlignStyle);
                $activeSheet->setCellValue("B" . $coordinateNotion, $notion["description"]);
                $activeSheet->setCellValue("C" . $coordinateNotion, number_format($notion["unitPrice"],2,",","."));
                $activeSheet->getStyle("C" . $coordinateNotion)->applyFromArray($textAlignStyle);
                $activeSheet->setCellValue("D" . $coordinateNotion, number_format($notion["amount"],2,",",".") . " €");
                $activeSheet->getStyle("D" . $coordinateNotion)->applyFromArray($textAlignStyle);
            }
            $sizeOfNotions = sizeof($invoice["notions"]);
            $lastCoordinateNotion = $coordinateNotion;
            $nextCoordinateNotion = $lastCoordinateNotion + 1;
            while ($sizeOfNotions < 14) {
                $activeSheet->setCellValue("A" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("B" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("C" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("D" . $nextCoordinateNotion, "");
                $nextCoordinateNotion++;
                $sizeOfNotions++;
            }
            $grossTotalCoordinate = $nextCoordinateNotion + 1;
            $igicCoordinate = $nextCoordinateNotion + 2;
            $totalCoordinate = $nextCoordinateNotion + 3;
            $activeSheet->setCellValue("C" . $grossTotalCoordinate, "Total Bruto:");
            $activeSheet->getStyle("C" . $grossTotalCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $grossTotalCoordinate, number_format($invoice["totals"]["grossTotal"],2,",",".") . " €");
            $activeSheet->getStyle("D" . $grossTotalCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("C" . $igicCoordinate, "IGIC 6,5%:");
            $activeSheet->getStyle("C" . $igicCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $igicCoordinate, number_format($invoice["totals"]["igic"],2,",",".") . " €");
            $activeSheet->getStyle("D" . $igicCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("C" . $totalCoordinate, "TOTAL");
            $activeSheet->getStyle("C" . $totalCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $totalCoordinate, number_format($invoice["totals"]["total"],2,",",".") . " €");
            $activeSheet->getStyle("D" . $totalCoordinate)->applyFromArray($textAlignStyle);

            $activeSheet->getStyle("A" . $dateCoordinate . ":D" . $totalCoordinate)->applyFromArray($borderStyle);

            $dateCoordinate = $dateCoordinate + 25;
        }
        $objWriter->save($fileURL);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=" . $fileName);
        header('Cache-Control: cache, must-revalidate');
        $objWriter->save("php://output");
        exit;
    }
}
