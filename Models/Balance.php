<?php

class Balance
{
    public static function createBalanceFile()
    {
        require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load("Files/Plantilla Balance.xlsx");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $activeSheet = $objPHPExcel->getActiveSheet();
        $textStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,), 'font' => array('bold' => true,));
        $grossIncome = $_SESSION["grossIncome"];
        $igicIncome = $_SESSION["igicIncome"];
        $totalIncome = $_SESSION["totalIncome"];
        $grossOutgoing = $_SESSION["grossOutgoing"];
        $igicOutgoing = $_SESSION["igicOutgoing"];
        $fileName = $_POST["balanceFileName"] . ".xlsx";
        $fileURL = 'Files/' . $fileName;
        $_SESSION["balanceExcelFile"] = $fileURL;
        $dateInput = $_POST["balanceFileDate"];
        $date = explode("-", $dateInput);
        $year = $date[0];
        $month = $date[1];
        $selfEmployedTax = $_POST["selfEmployedTax"];

        $activeSheet->setTitle("Balance");
        $activeSheet->setCellValue("D2", "JOSÉ OCTAVIO RODRÍGUEZ GONZÁLEZ");
        $activeSheet->getStyle("D2")->applyFromArray($textStyle);
        $activeSheet->setCellValue("I2", $month);
        $activeSheet->getStyle("I2")->applyFromArray($textStyle);
        $activeSheet->setCellValue("K2", $year);
        $activeSheet->getStyle("K2")->applyFromArray($textStyle);
        $activeSheet->setCellValue("J5", number_format($totalIncome, 2, ",", ".") . " €");
        $activeSheet->getStyle("J5")->applyFromArray($textStyle);
        $activeSheet->setCellValue("D6", "Ventas");
        $activeSheet->getStyle("D6")->applyFromArray($textStyle);
        $activeSheet->setCellValue("G6", number_format($grossIncome, 2, ",", ".") . " €");
        $activeSheet->getStyle("G6")->applyFromArray($textStyle);
        $activeSheet->setCellValue("D7", "IGIC Repercutido");
        $activeSheet->getStyle("D7")->applyFromArray($textStyle);
        $activeSheet->setCellValue("G7", number_format($igicIncome, 2, ",", ".") . " €");
        $activeSheet->getStyle("G7")->applyFromArray($textStyle);
        $activeSheet->setCellValue("G12", number_format($grossOutgoing, 2, ",", ".") . " €");
        $activeSheet->getStyle("G12")->applyFromArray($textStyle);
        $activeSheet->setCellValue("G18", number_format($selfEmployedTax, 2, ",", ".") . " €");
        $activeSheet->getStyle("G18")->applyFromArray($textStyle);
        $activeSheet->setCellValue("F20", number_format($selfEmployedTax, 2, ",", ".") . " €");
        $activeSheet->getStyle("F20")->applyFromArray($textStyle);
        $activeSheet->setCellValue("G60", number_format($igicOutgoing, 2, ",", ".") . " €");
        $activeSheet->getStyle("G60")->applyFromArray($textStyle);
        $activeSheet->setCellValue("C61", "IGIC Soportado");
        $activeSheet->getStyle("C61")->applyFromArray($textStyle);
        $activeSheet->setCellValue("F61", number_format($igicOutgoing, 2, ",", ".") . " €");
        $activeSheet->getStyle("F61")->applyFromArray($textStyle);
        $activeSheet->setCellValue("J64", number_format(($grossOutgoing + $selfEmployedTax), 2, ",", ".") . " €");
        $activeSheet->getStyle("J64")->applyFromArray($textStyle);

        $objWriter->save($fileURL);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=" . $fileName);
        header('Cache-Control: cache, must-revalidate');
        $objWriter->save("php://output");
        exit;
    }
}