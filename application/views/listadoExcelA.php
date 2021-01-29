<?php

if(false){
// echo $numRegistros;
var_dump($socios);
foreach($socios as $k=>$v){
    echo $v['nombre'].' '.$v['apellidos'];
}
}
if(true){
$hoja = 1;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);

$this->excel->getActiveSheet()->setTitle('Usuaris');

$this->excel->getActiveSheet()->getCell("A1")->setValueExplicit(' ' . $texto_titulo . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("A2")->setValueExplicit(' Usuaris/Usuàries inscrits a tallers ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("A3")->setValueExplicit(' Data: ' . date('d/m/Y') . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet($hoja)->getStyle("A3")->getFont()->setSize(14);

$this->excel->getActiveSheet($hoja)->getStyle("A1:A2")->getFont()->setSize(20);
$this->excel->getActiveSheet($hoja)->getStyle("A1")->getFont()->setBold(true);
$fila = 4;
$this->excel->getActiveSheet()->getCell("A$fila")->setValueExplicit(' Cognoms ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("B$fila")->setValueExplicit(' Nom ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("C$fila")->setValueExplicit(' Telèfon 1 ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("D$fila")->setValueExplicit(' Telèfon 2 ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("E$fila")->setValueExplicit(' Email ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("F$fila")->setValueExplicit(' Edat ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("G$fila")->setValueExplicit(' Tallers ', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet($hoja)->getStyle("A$fila:G$fila")->getFont()->setSize(18);
$this->excel->getActiveSheet($hoja)->getStyle("A$fila:G$fila")->getFont()->setBold(true);

$fila++;
foreach ($socios as $k => $v) {
    if(trim($v['talleres']=="")) continue;
    $this->excel->getActiveSheet()->getCell("A$fila")->setValueExplicit(' ' . $v['apellidos'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("B$fila")->setValueExplicit(' ' . $v['nombre'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("C$fila")->setValueExplicit(' ' . $v['telefono_1'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("D$fila")->setValueExplicit(' ' . $v['telefono_2'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("E$fila")->setValueExplicit(' ' . $v['email'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("F$fila")->setValueExplicit(' ' . $v['edad'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("G$fila")->setValueExplicit(' ' . $v['talleres'] . ' ', PHPExcel_Cell_DataType::TYPE_STRING);

    $fila++;
}
$this->excel->getActiveSheet($hoja)->getStyle("A5:G$fila")->getFont()->setSize(14);

$this->excel->getActiveSheet($hoja)->getColumnDimension('A')->setWidth(30);
$this->excel->getActiveSheet($hoja)->getColumnDimension('B')->setWidth(30);
$this->excel->getActiveSheet($hoja)->getColumnDimension('C')->setWidth(18);
$this->excel->getActiveSheet($hoja)->getColumnDimension('D')->setWidth(18);
$this->excel->getActiveSheet($hoja)->getColumnDimension('E')->setWidth(40);
$this->excel->getActiveSheet($hoja)->getColumnDimension('F')->setWidth(10);
$this->excel->getActiveSheet($hoja)->getColumnDimension('G')->setWidth(100);



$filename = "Usuarios.xls";
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');


//force user to download the Excel file without writing it to server's HD
//
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');

// header("Location: ".base_url()."index.php/reporte/listasUsuariosTelefonosEmail");
}