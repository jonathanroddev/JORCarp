<?php
include_once ("Income.php");
class Invoice
{
    public static function getAllFromInvoices()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT * FROM invoices";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function getInvoiceFromInvoices($cusId)
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT inv_obj FROM invoices WHERE cus_id=" . $cusId;
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function getReferenceFromInvoices($cusId)
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT inv_ref FROM invoices WHERE cus_id=" . $cusId;
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function getReferencedInvoices()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT * FROM invoices INNER JOIN customers ON invoices.cus_id = customers.cus_id WHERE inv_ref != '' ";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function getAllFromInvoicesAndCustomers()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT * FROM invoices INNER JOIN customers ON invoices.cus_id = customers.cus_id";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function uploadInvoiceData()
    {
        if (isset($_GET["idcliente"])) $idCustomer = $_GET["idcliente"];
        if (isset($_POST["reference"])) $reference = $_POST["reference"];
        if (isset($_POST["quantities"])) $quantities = $_POST["quantities"];
        if (isset($_POST["descriptions"])) $descriptions = $_POST["descriptions"];
        if (isset($_POST["unitprices"])) $unitPrices = $_POST["unitprices"];
        if (isset($_POST["amounts"])) $amounts = $_POST["amounts"];
        if (isset($_POST["grosstotal"])) $grossTotal = $_POST["grosstotal"];
        if (isset($_POST["igic"])) $igic = $_POST["igic"];
        if (isset($_POST["total"])) $total = $_POST["total"];
        $invoice = array();
        $invoice["reference"] = $reference;
        for ($i = 0; $i < sizeof($amounts); $i++) {
            if ($amounts[$i] != "" && $amounts[$i] > 0 || $descriptions[$i] != "") {
                $notion = [
                    "quantity" => $quantities[$i], "description" => $descriptions[$i],
                    "unitPrice" => $unitPrices[$i], "amount" => $amounts[$i]
                ];
                $invoice["notions"][$i] = $notion;
            }
        }
        if ($grossTotal != "" && $grossTotal > 0) {
            $totals = ["grossTotal" => $grossTotal, "igic" => $igic, "total" => $total];
            $invoice["totals"] = $totals;
        }
        Invoice::insertInvoiceData($idCustomer, $invoice);
        header("Location:?page=ingresos");
    }

    public static function insertInvoiceData($idCustomer, $invoice)
    {
        $pdoConnection = DBConnection::PdoConnection();
        $reference = $invoice["reference"];
        if ($invoice["notions"] > 0) {
            $invoiceSerialized = serialize($invoice);
            $sql = "DELETE FROM invoices WHERE cus_id = '" . $idCustomer . "';
                INSERT INTO invoices (cus_id,inv_obj,inv_ref) VALUES ('" . $idCustomer . "','" . $invoiceSerialized . "','" . $reference . "')";
            try {
                $prepareQuery = $pdoConnection->prepare($sql);
                $prepareQuery->execute();
            } catch (Exception $e) {
                echo '<hr>Reading Error: (' . $e->getMessage() . ')';
                return false;
            }
        }
    }

    public static function exportInvoicesToExcel()
    {
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $fileName = $_POST["fileName"] . ".xlsx";
        $fileURL = 'Files/' . $fileName;
        $_SESSION["invoiceExcelFile"] = $fileURL;
        $objPHPExcel->setActiveSheetIndex(0);
        $date = date('d-m-Y', strtotime($_POST["fileDate"]));
        $datas = Invoice::getAllFromInvoicesAndCustomers();
        $dateCoordinate = 1;
        $activeSheet = $objPHPExcel->getActiveSheet();
        $activeSheet->setTitle("Facturas");
        $activeSheet->getColumnDimension('A')->setWidth(15);
        $activeSheet->getColumnDimension('B')->setWidth(50);
        $activeSheet->getColumnDimension('C')->setWidth(15);
        $activeSheet->getColumnDimension('D')->setWidth(15);
        $activeSheet->getColumnDimension('K')->setWidth(20);
        $borderStyle = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => array('argb' => '#000'),)));
        $headerStyle = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $textAlignStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $allGrossTotal = 0;
        $allTotal = 0;
        $grossTotalReferenced = 0;
        $igicTotalReferenced = 0;
        $totalReferenced = 0;
        for ($i = 0; $i < sizeof($datas); $i++) {
            $invoice = unserialize($datas[$i]["inv_obj"]);
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
            $activeSheet->setCellValue("D" . $addressNumberInvoiceCoordinate, $datas[$i]["inv_ref"]);
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
                $activeSheet->setCellValue("A" . $coordinateNotion, $notion["quantity"]);
                $activeSheet->getStyle("A" . $coordinateNotion)->applyFromArray($textAlignStyle);
                $activeSheet->setCellValue("B" . $coordinateNotion, $notion["description"]);
                if ($notion["unitPrice"] != "")
                    $activeSheet->setCellValue("C" . $coordinateNotion, number_format($notion["unitPrice"], 2, ",", "."));
                else
                    $activeSheet->setCellValue("C" . $coordinateNotion, $notion["unitPrice"]);
                $activeSheet->getStyle("C" . $coordinateNotion)->applyFromArray($textAlignStyle);
                if ($notion["amount"] != "")
                    $activeSheet->setCellValue("D" . $coordinateNotion, number_format($notion["amount"], 2, ",", ".") . " €");
                else
                    $activeSheet->setCellValue("D" . $coordinateNotion, $notion["amount"]);
                $activeSheet->getStyle("D" . $coordinateNotion)->applyFromArray($textAlignStyle);
            }
            $sizeOfNotions = sizeof($invoice["notions"]);
            $lastCoordinateNotion = $coordinateNotion;
            $nextCoordinateNotion = $lastCoordinateNotion + 1;
            /*while ($sizeOfNotions < 15) {
                $activeSheet->setCellValue("A" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("B" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("C" . $nextCoordinateNotion, "");
                $activeSheet->setCellValue("D" . $nextCoordinateNotion, "");
                $nextCoordinateNotion++;
                $sizeOfNotions++;
            }*/
            $grossTotalCoordinate = $dateCoordinate + 19;
            $igicCoordinate = $dateCoordinate + 20;
            $totalCoordinate = $dateCoordinate + 21;
            $activeSheet->setCellValue("C" . $grossTotalCoordinate, "Total Bruto:");
            $activeSheet->getStyle("C" . $grossTotalCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $grossTotalCoordinate, number_format($invoice["totals"]["grossTotal"], 2, ",", ".") . " €");
            $activeSheet->getStyle("D" . $grossTotalCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("C" . $igicCoordinate, "IGIC 6,5%:");
            $activeSheet->getStyle("C" . $igicCoordinate)->getFont()->setBold(true);
            if ($datas[$i]["inv_ref"] == "")
                $activeSheet->setCellValue("D" . $igicCoordinate, "");
            else
                $activeSheet->setCellValue("D" . $igicCoordinate, number_format($invoice["totals"]["igic"], 2, ",", ".") . " €");
            $activeSheet->getStyle("D" . $igicCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("C" . $totalCoordinate, "TOTAL");
            $activeSheet->getStyle("C" . $totalCoordinate)->getFont()->setBold(true);
            $activeSheet->setCellValue("D" . $totalCoordinate, number_format($invoice["totals"]["total"], 2, ",", ".") . " €");
            $activeSheet->getStyle("D" . $totalCoordinate)->applyFromArray($textAlignStyle);

            $activeSheet->getStyle("A" . $dateCoordinate . ":D" . $totalCoordinate)->applyFromArray($borderStyle);

            $allGrossTotal += $invoice["totals"]["grossTotal"];
            $allTotal += $invoice["totals"]["total"];
            if ($datas[$i]["inv_ref"] != "") {
                $grossTotalReferenced += $invoice["totals"]["grossTotal"];
                $igicTotalReferenced += $invoice["totals"]["igic"];
                $totalReferenced = $grossTotalReferenced + $igicTotalReferenced;
            }
            $dateCoordinate = $dateCoordinate + 25;
        }
        $activeSheet->setCellValue("K1", "TOTAL BRUTO:");
        $activeSheet->setCellValue("L1", $allGrossTotal);
        $activeSheet->setCellValue("K2", "TOTAL:");
        $activeSheet->setCellValue("L2", $allTotal);
        $activeSheet->setCellValue("K3", "TOTAL BRUTO REF.:");
        $activeSheet->setCellValue("L3", $grossTotalReferenced);
        $activeSheet->setCellValue("K4", "TOTAL IGIC:");
        $activeSheet->setCellValue("L4", $igicTotalReferenced);
        $activeSheet->setCellValue("K5", "TOTAL REF.:");
        $activeSheet->setCellValue("L5", $totalReferenced);

        /*$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $activeSheet->getPageSetup()->setFitToPage(true);*/
        //$activeSheet->getPageSetup()->setPrintArea('A1:D47;A51:D98');

        Income::createIncomeSheet($objPHPExcel);

        $objWriter->save($fileURL);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=" . $fileName);
        header('Cache-Control: cache, must-revalidate');
        $objWriter->save("php://output");
        exit;
    }

    public static function deleteInvoicesTable()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = " TRUNCATE table invoices;";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }
}
