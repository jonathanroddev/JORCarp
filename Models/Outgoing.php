<?php
class Outgoing
{
    public static function saveOutgoingInDB()
    {
        $pdoConnection = DBConnection::PdoConnection();
        if (isset($_POST["suppliers"])) $suppliers = $_POST["suppliers"];
        if (isset($_POST["outgoingreferences"])) $references = $_POST["outgoingreferences"];
        if (isset($_POST["outgoingdates"])) $dates = $_POST["outgoingdates"];
        if (isset($_POST["outgoinggross"])) $allGross = $_POST["outgoinggross"];
        if (isset($_POST["outgoingigic"])) $allIgic = $_POST["outgoingigic"];
        if (isset($_POST["outgoingtotals"])) $allTotals = $_POST["outgoingtotals"];
        try {
            for ($i = 0; $i < sizeof($references); $i++) {
                $supplier = $suppliers[$i];
                $reference = $references[$i];
                $date = date('d-m-Y', strtotime($dates[$i]));
                $gross = $allGross[$i];
                $igic = $allIgic[$i];
                $total = $allTotals[$i];
                if (!Outgoing::getOutgoing($reference)) {
                    $sql = "INSERT INTO outgoing (sup_id, out_ref, out_date, out_gross, out_igic, out_total) VALUES (
                    " . $supplier . ", '" . $reference . "', '" . $date . "',
                    " . floatval($gross) . ", " . floatval($igic) . ", " . floatval($total) . ")";
                }
                else{
                    $sql = "UPDATE outgoing SET sup_id =" . $supplier . ", out_date = '" . $date . "',
                    out_gross = " . floatval($gross) . ", out_igic = " . floatval($igic) . ", out_total = " . floatval($total) .
                    " WHERE out_ref = '" . $reference . "'";
                }
                $prepareQuery = $pdoConnection->prepare($sql);
                $prepareQuery->execute();
            }
            Outgoing::exportOutgoingToExcel();
            header("Location:?page=gastos");
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }

    public static function getOutgoing($reference)
    {
        $pdoConnection = DBConnection::PdoConnection();
        $result = false;
        try {
            $sql = "SELECT * FROM outgoing WHERE out_ref = '" . $reference . "'";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $outgoing = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
            if (isset($outgoing[0])) $result = true;
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function getAllFromOutgoing()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT * FROM outgoing";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function exportOutgoingToExcel(){
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $fileName = $_POST["outgoingFileName"] . ".xlsx";
        $fileURL = 'Files/' . $fileName;
        $_SESSION["outgoingExcelFile"] = $fileURL;
        $objPHPExcel->setActiveSheetIndex(0);
        $datas = Outgoing::getAllFromOutgoingAndSuppliers();
        $activeSheet = $objPHPExcel->getActiveSheet();
        $activeSheet->setTitle("Compras");
        $activeSheet->getDefaultColumnDimension()->setWidth(20);
        $activeSheet->getColumnDimension('C')->setWidth(50);
        $activeSheet->getColumnDimension('D')->setWidth(35);
        $borderStyle = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => array('argb' => '#000'),)));
        $headerStyle = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $textAlignStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $allGross = 0;
        $allIgic = 0;
        $allTotal = 0;
        $headerCoordinate = 3;
        $recordCoordinate = 4;
        $activeSheet->setCellValue("C1", "COMPRAS DEL MES");
        $activeSheet->getStyle("C1")->applyFromArray($headerStyle);
        $activeSheet->setCellValue("A" . $headerCoordinate, "FECHA");
        $activeSheet->getStyle("A" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("B" . $headerCoordinate, "DNI/CIF");
        $activeSheet->getStyle("B" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("C" . $headerCoordinate, "PROVEEDOR");
        $activeSheet->getStyle("C" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("D" . $headerCoordinate, "REF. FACT.");
        $activeSheet->getStyle("D" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("E" . $headerCoordinate, "TOTAL BRUTO");
        $activeSheet->getStyle("E" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("F" . $headerCoordinate, "IGIC");
        $activeSheet->getStyle("F" . $headerCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("G" . $headerCoordinate, "TOTAL");
        $activeSheet->getStyle("G" . $headerCoordinate)->applyFromArray($headerStyle);

        for ($i = 0; $i < sizeof($datas); $i++) {
            $activeSheet->setCellValue("A" . $recordCoordinate, $datas[$i]["out_date"]);
            $activeSheet->getStyle("A" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("B" . $recordCoordinate, $datas[$i]["sup_cif"]);
            $activeSheet->getStyle("B" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("C" . $recordCoordinate, $datas[$i]["sup_name"]);
            $activeSheet->getStyle("C" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("D" . $recordCoordinate, $datas[$i]["out_ref"]);
            $activeSheet->getStyle("D" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("E" . $recordCoordinate, number_format($datas[$i]["out_gross"], 2, ",", ".") . " €");
            $activeSheet->getStyle("E" . $recordCoordinate)->applyFromArray($textAlignStyle);
            if($datas[$i]["out_igic"] == 0)  $activeSheet->setCellValue("F" . $recordCoordinate, "");
            else $activeSheet->setCellValue("F" . $recordCoordinate, number_format($datas[$i]["out_igic"], 2, ",", ".") . " €");
            $activeSheet->getStyle("F" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $activeSheet->setCellValue("G" . $recordCoordinate, number_format($datas[$i]["out_total"], 2, ",", ".") . " €");
            $activeSheet->getStyle("G" . $recordCoordinate)->applyFromArray($textAlignStyle);

            $allGross += $datas[$i]["out_gross"];
            $allIgic += $datas[$i]["out_igic"];
            $allTotal += $datas[$i]["out_total"];

            $recordCoordinate++;
        }

        $activeSheet->setCellValue("D" . $recordCoordinate, "SUMA");
        $activeSheet->getStyle("D" . $recordCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("E" . $recordCoordinate, number_format($allGross, 2, ",", ".") . " €");
        $activeSheet->getStyle("E" . $recordCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("F" . $recordCoordinate, number_format($allIgic, 2, ",", ".") . " €");
        $activeSheet->getStyle("F" . $recordCoordinate)->applyFromArray($headerStyle);
        $activeSheet->setCellValue("G" . $recordCoordinate, number_format($allTotal, 2, ",", ".") . " €");
        $activeSheet->getStyle("G" . $recordCoordinate)->applyFromArray($headerStyle);

        $activeSheet->getStyle("A" . $headerCoordinate . ":G" . $recordCoordinate)->applyFromArray($borderStyle);

        $objWriter->save($fileURL);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=" . $fileName);
        header('Cache-Control: cache, must-revalidate');
        $objWriter->save("php://output");
        exit;
    }

    public static function getAllFromOutgoingAndSuppliers()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SELECT * FROM outgoing INNER JOIN suppliers ON outgoing.sup_id = suppliers.sup_id";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
            $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
        return $result;
    }

    public static function deleteOutgoingTable()
    {
        $pdoConnection = DBConnection::PdoConnection();
        try {
            $sql = "SET FOREIGN_KEY_CHECKS = 0; 
            TRUNCATE table outgoing; 
            SET FOREIGN_KEY_CHECKS = 1;";
            $prepareQuery = $pdoConnection->prepare($sql);
            $prepareQuery->execute();
        } catch (Exception $e) {
            echo '<hr>Reading Error: (' . $e->getMessage() . ')';
            return false;
        }
    }
}