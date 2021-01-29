<?php
extract($datosCabecera);
//var_dump($ingresos);
extract($ingresos);
extract($devoluciones);

$hoja=0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);

$objDrawing = new Drawing();
$objDrawing->setWorksheet($this->excel->getActiveSheet($hoja));    
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('././img/AyuntamientoExcel.png');
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(49.6);
$objDrawing->setWidth(158.8);

$this->excel->getActiveSheet()->setCellValue('I1', 'DISTRICTE DE SANT MARTÍ' );
$this->excel->getActiveSheet()->setCellValue('I2', 'Plaça Valenti Almirrall, 1' );
$this->excel->getActiveSheet()->setCellValue('I3', '08018 Barcelona' );
$this->excel->getActiveSheet()->setCellValue('I4', 'Tel 93 291 60 37' );

$this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('I1:I4')->getFont()->setSize(6);
$this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(2)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(3)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(4)->setRowHeight(8) ;

$this->excel->getActiveSheet()->setTitle("INGRESSOS");

$this->excel->getActiveSheet()->setCellValue('A5', "INFORME DETALLAT INGRESSOS");
$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setUnderline(true);
$this->excel->getActiveSheet()->getRowDimension(5)->setRowHeight(30) ;

$this->excel->getActiveSheet()->setCellValue('A7', $equipament);
$this->excel->getActiveSheet()->setCellValue('A8', $adjudicatari);
$this->excel->getActiveSheet()->setCellValue('A9', $nif);
$this->excel->getActiveSheet()->setCellValue('A10', $contarcte);
$this->excel->getActiveSheet()->setCellValue('A11', $periodo);


$this->excel->getActiveSheet()->getStyle('A7:A11')->getFont()->setSize(9);

$this->excel->getActiveSheet()->setCellValue('A13', 'Data');
$this->excel->getActiveSheet()->setCellValue('B13', 'Núm Registre');
$this->excel->getActiveSheet()->setCellValue('C13', 'DNI Usuari');
$this->excel->getActiveSheet()->setCellValue('D13', 'Nom Activitat');
$this->excel->getActiveSheet()->setCellValue('E13', 'Núm Registre Ingrés');
$this->excel->getActiveSheet()->setCellValue('F13', 'Preu/hora');
$this->excel->getActiveSheet()->setCellValue('G13', 'Import Base');
$this->excel->getActiveSheet()->setCellValue('H13', '% IVA (Exempt)');
$this->excel->getActiveSheet()->setCellValue('I13', 'IMPORT TOTAL');
$this->excel->getActiveSheet()->setCellValue('J13', 'TIPOLOGIA INGRES');
$this->excel->getActiveSheet()->getStyle('A7:A11')->getFont()->setSize(9);

$this->excel->getActiveSheet()->getStyle('A13:J13')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getFont()->setSize(8);

$this->excel->getActiveSheet()->getRowDimension('13')->setRowHeight(20);

$ancho=12;
$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth($ancho);

$this->excel->getActiveSheet()->getStyle('A13:J13')->getAlignment()->setWrapText(true);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

$row=14;
foreach($ingresos as $k=>$v){
    $num=strval($v['numeroRegistroPosicion']);
    while(strlen($num)<5) {$num='0'.$num;}
    $this->excel->getActiveSheet()->setCellValue('A'.$row, $v['fecha']);
    $this->excel->getActiveSheet()->setCellValue('B'.$row, $v['numeroRegistroCasal'].$num);
    $this->excel->getActiveSheet()->setCellValue('C'.$row, strtoupper($v['dni']));
    $this->excel->getActiveSheet()->setCellValue('D'.$row, $v['nombre']);
    $this->excel->getActiveSheet()->setCellValue('E'.$row, $v['recibo']);
    $this->excel->getActiveSheet()->setCellValue('F'.$row, $v['preu_hora']);
    $this->excel->getActiveSheet()->setCellValue('G'.$row, $v['importe']);
    $this->excel->getActiveSheet()->setCellValue('H'.$row, $v['iva']);
    $this->excel->getActiveSheet()->setCellValue('I'.$row, $v['importeTotal']);
    $this->excel->getActiveSheet()->setCellValue('J'.$row, $v['tipologia']);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getFont()->setSize(8);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $row++;
}
    $this->excel->getActiveSheet()->setCellValue('H'.$row, 'TOTAL');
   //$this->excel->getActiveSheet()->setCellValue('I'.$row, $importeTotal);
    $this->excel->getActiveSheet()->setCellValue('I'.$row, '=SUM(G14:G'.($row-1).')');
    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getFont()->setSize(10);

    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
   
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


$hoja=1;
////// DEVOLUCIONES en Segunda hoja
$this->excel->setActiveSheetIndex($hoja);
$this->excel->getActiveSheet()->setTitle("DEVOLUCIONS");
$objDrawing = new Drawing();
$objDrawing->setWorksheet($this->excel->getActiveSheet($hoja));    
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('././img/AyuntamientoExcel.png');
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(49.6);
$objDrawing->setWidth(158.8);

$this->excel->getActiveSheet()->setCellValue('I1', 'DISTRICTE DE SANT MARTÍ' );
$this->excel->getActiveSheet()->setCellValue('I2', 'Plaça Valenti Almirrall, 1' );
$this->excel->getActiveSheet()->setCellValue('I3', '08018 Barcelona' );
$this->excel->getActiveSheet()->setCellValue('I4', 'Tel 93 291 60 37' );

$this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('I1:I4')->getFont()->setSize(6);
$this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(2)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(3)->setRowHeight(8) ;
$this->excel->getActiveSheet()->getRowDimension(4)->setRowHeight(8) ;

$this->excel->getActiveSheet()->setCellValue('A5', "INFORME DETALLAT DEVOLUCIONS"); 


$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setUnderline(true);
$this->excel->getActiveSheet()->getRowDimension(5)->setRowHeight(30) ;

$this->excel->getActiveSheet()->setCellValue('A7', $equipament);
$this->excel->getActiveSheet()->setCellValue('A8', $adjudicatari);
$this->excel->getActiveSheet()->setCellValue('A9', $nif);
$this->excel->getActiveSheet()->setCellValue('A10', $contarcte);
$this->excel->getActiveSheet()->setCellValue('A11', $periodo);


$this->excel->getActiveSheet()->getStyle('A7:A11')->getFont()->setSize(9);

$this->excel->getActiveSheet()->setCellValue('A13', 'Data');
$this->excel->getActiveSheet()->setCellValue('B13', 'Núm Registre');
$this->excel->getActiveSheet()->setCellValue('C13', 'DNI Usuari');
$this->excel->getActiveSheet()->setCellValue('D13', 'Nom Activitat');
$this->excel->getActiveSheet()->setCellValue('E13', 'Núm Registre Devolució');
$this->excel->getActiveSheet()->setCellValue('F13', 'Preu/hora');
$this->excel->getActiveSheet()->setCellValue('G13', 'Import Base');
$this->excel->getActiveSheet()->setCellValue('H13', '% IVA (Exempt)');
$this->excel->getActiveSheet()->setCellValue('I13', 'IMPORT TOTAL');
$this->excel->getActiveSheet()->setCellValue('J13', 'TIPOLOGIA DEVOLUCIÓ');
$this->excel->getActiveSheet()->getStyle('A7:A11')->getFont()->setSize(9);

$this->excel->getActiveSheet()->getStyle('A13:J13')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getFont()->setSize(8);

$this->excel->getActiveSheet()->getRowDimension('13')->setRowHeight(20);

$ancho=12;
$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth($ancho);
$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth($ancho);

$this->excel->getActiveSheet()->getStyle('A13:J13')->getAlignment()->setWrapText(true);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$this->excel->getActiveSheet()->getStyle('A13:J13')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

$row=14;
foreach($devoluciones as $k=>$v){
    $num=strval($v['numeroRegistroPosicion']);
    while(strlen($num)<5) {$num='0'.$num;}
    $this->excel->getActiveSheet()->setCellValue('A'.$row, $v['fecha']);
    $this->excel->getActiveSheet()->setCellValue('B'.$row, $v['numeroRegistroCasal'].$num);
    $this->excel->getActiveSheet()->setCellValue('C'.$row, strtoupper($v['dni']));
    $this->excel->getActiveSheet()->setCellValue('D'.$row, $v['nombre']);
    $this->excel->getActiveSheet()->setCellValue('E'.$row, $v['recibo']);
    $this->excel->getActiveSheet()->setCellValue('F'.$row, -$v['preu_hora']);
    $this->excel->getActiveSheet()->setCellValue('G'.$row, $v['importe']);
    $this->excel->getActiveSheet()->setCellValue('H'.$row, $v['iva']);
    $this->excel->getActiveSheet()->setCellValue('I'.$row, $v['importeTotal']);
    $this->excel->getActiveSheet()->setCellValue('J'.$row, $v['tipologia']);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getFont()->setSize(8);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $row++;
}
    $this->excel->getActiveSheet()->setCellValue('H'.$row, 'TOTAL');
    //$this->excel->getActiveSheet()->setCellValue('I'.$row, -$importeTotalDevoluciones);
    $this->excel->getActiveSheet()->setCellValue('I'.$row, '=SUM(G14:G'.($row-1).')');
    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getFont()->setSize(10);

    $this->excel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
   


$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setUnderline(true);
$this->excel->getActiveSheet()->getRowDimension(5)->setRowHeight(30) ;

$this->excel->getActiveSheet()->setCellValue('A7', $equipament);
$this->excel->getActiveSheet()->setCellValue('A8', $adjudicatari);
$this->excel->getActiveSheet()->setCellValue('A9', $nif);
$this->excel->getActiveSheet()->setCellValue('A10', $contarcte);
$this->excel->getActiveSheet()->setCellValue('A11', $periodo);


$this->excel->getActiveSheet()->getStyle('A7:A11')->getFont()->setSize(9);


$this->excel->getActiveSheet($hoja)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$this->excel->getActiveSheet($hoja)->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$this->excel->setActiveSheetIndex(0);

$filename='Informe Ajuntament '.getTituloCasalCorto().' .xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
            
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  


//force user to download the Excel file without writing it to server's HD
//
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');