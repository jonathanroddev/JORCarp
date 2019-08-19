<?php
include_once "DBConnection.php";
include_once "Invoice.php";

class Income
{
    function createIncomeSheet($objPHPExcel)
    {
        $invoice = new Invoice();
        $datas = $invoice->getReferencedInvoices();
        $incomeSheet = $objPHPExcel->createSheet(1);
        $incomeSheet->setTitle("Ingresos");
        $incomeSheet->getDefaultColumnDimension()->setWidth(20);
        $incomeSheet->getColumnDimension('C')->setWidth(50);
        $borderStyle = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => array('argb' => '#000'),)));
        $headerStyle = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $textAlignStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $grossTotalReferenced = 0;
        $igicTotalReferenced = 0;
        $totalReferenced = 0;
        $headerCoordinate = 3;
        $recordCoordinate = 4;
        $incomeSheet->setCellValue("C1", "DETALLE DE VENTAS E INGRESOS");
        $incomeSheet->getStyle("C1")->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("A" . $headerCoordinate, "REF. FACT.");
        $incomeSheet->getStyle("A" . $headerCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("B" . $headerCoordinate, "DNI/CIF");
        $incomeSheet->getStyle("B" . $headerCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("C" . $headerCoordinate, "NOMBRE CLIENTE");
        $incomeSheet->getStyle("C" . $headerCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("D" . $headerCoordinate, "TOTAL BRUTO");
        $incomeSheet->getStyle("D" . $headerCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("E" . $headerCoordinate, "IGIC");
        $incomeSheet->getStyle("E" . $headerCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("F" . $headerCoordinate, "TOTAL");
        $incomeSheet->getStyle("F" . $headerCoordinate)->applyFromArray($headerStyle);

        for ($i = 0; $i < sizeof($datas); $i++) {
            $invoice = unserialize($datas[$i]["inv_obj"]);
            $incomeSheet->setCellValue("A" . $recordCoordinate, $datas[$i]["inv_ref"]);
            $incomeSheet->getStyle("A" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $incomeSheet->setCellValue("B" . $recordCoordinate, $datas[$i]["cus_nif"]);
            $incomeSheet->getStyle("B" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $incomeSheet->setCellValue("C" . $recordCoordinate, $datas[$i]["cus_name"]);
            $incomeSheet->getStyle("C" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $incomeSheet->setCellValue("D" . $recordCoordinate, number_format($invoice["totals"]["grossTotal"], 2, ",", ".") . " €");
            $incomeSheet->getStyle("D" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $incomeSheet->setCellValue("E" . $recordCoordinate, number_format($invoice["totals"]["igic"], 2, ",", ".") . " €");
            $incomeSheet->getStyle("E" . $recordCoordinate)->applyFromArray($textAlignStyle);
            $incomeSheet->setCellValue("F" . $recordCoordinate, number_format($invoice["totals"]["total"], 2, ",", ".") . " €");
            $incomeSheet->getStyle("F" . $recordCoordinate)->applyFromArray($textAlignStyle);

            $grossTotalReferenced += $invoice["totals"]["grossTotal"];
            $igicTotalReferenced += $invoice["totals"]["igic"];
            $totalReferenced += $invoice["totals"]["total"];

            $recordCoordinate++;
        }
        $incomeSheet->setCellValue("C" . $recordCoordinate, "SUMA");
        $incomeSheet->getStyle("C" . $recordCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("D" . $recordCoordinate, number_format($grossTotalReferenced, 2, ",", ".") . " €");
        $incomeSheet->getStyle("D" . $recordCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("E" . $recordCoordinate, number_format($igicTotalReferenced, 2, ",", ".") . " €");
        $incomeSheet->getStyle("E" . $recordCoordinate)->applyFromArray($headerStyle);
        $incomeSheet->setCellValue("F" . $recordCoordinate, number_format($totalReferenced, 2, ",", ".") . " €");
        $incomeSheet->getStyle("F" . $recordCoordinate)->applyFromArray($headerStyle);

        $incomeSheet->getStyle("A" . $headerCoordinate . ":F" . $recordCoordinate)->applyFromArray($borderStyle);
    }
}
