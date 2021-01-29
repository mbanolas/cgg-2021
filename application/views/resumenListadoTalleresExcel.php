<?php
extract($talleres);

$hoja=0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle("Resumen talleres");
$this->excel->getActiveSheet()->setCellValue('A1', "Resum Tallers");
$this->excel->getActiveSheet()->setCellValue('A2', "Curs: ".$textoCurso.' - '.$textoPeriodo);
//$this->excel->getActiveSheet()->setCellValue('A3', "Periodo: ".$textoPeriodo);
$this->excel->getActiveSheet()->setCellValue('A4', $nombreTipoTaller);

//change the font size
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);

$this->excel->getActiveSheet()->setCellValue('A6', "Núm");
$this->excel->getActiveSheet()->setCellValue('b6', "Taller");
$this->excel->getActiveSheet()->setCellValue('c6', "Asistents");
$this->excel->getActiveSheet()->setCellValue('d6', "Imports (€)");
$this->excel->getActiveSheet()->getStyle('A6:D6')->getFont()->setBold(true);

if($tipoTaller=='tots'){
    $this->excel->getActiveSheet()->mergeCells('C5:D5');
    $this->excel->getActiveSheet()->setCellValue('c5', "Tallers Voluntaris");
    $this->excel->getActiveSheet()->mergeCells('E5:F5');
    $this->excel->getActiveSheet()->setCellValue('e5', "Tallers Profesionals");
    $this->excel->getActiveSheet()->mergeCells('G5:H5');
    $this->excel->getActiveSheet()->setCellValue('G5', "Tots els Tallers");
    
    $this->excel->getActiveSheet()->setCellValue('c6', "Asistents");
    $this->excel->getActiveSheet()->setCellValue('d6', "Imports (€)");
    $this->excel->getActiveSheet()->setCellValue('e6', "Asistents");
    $this->excel->getActiveSheet()->setCellValue('f6', "Imports (€)");
    $this->excel->getActiveSheet()->setCellValue('g6', "Asistents");
    $this->excel->getActiveSheet()->setCellValue('h6', "Imports (€)");
    $this->excel->getActiveSheet()->getStyle('A5:h6')->getFont()->setBold(true);
}


//make the font become bold
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


$row=7;

    
    foreach ($talleres as $k => $taller) {
    $this->excel->getActiveSheet()->setCellValue('A' . $row, $taller['id'] . "  ");
    $this->excel->getActiveSheet()->setCellValue('B' . $row, $taller['nombreTaller']);

    if ($tipoTaller == 'voluntaris') {
        $this->excel->getActiveSheet()->setCellValue('C' . $row, $taller['numAsistentesVoluntario']);
        $this->excel->getActiveSheet()->setCellValue('D' . $row, $taller['importeVoluntario']);
        $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');

        // $this->excel->getActiveSheet()->setCellValue('E' . $row, $taller['numAsistentesProfesional']);
       // $this->excel->getActiveSheet()->setCellValue('F' . $row, $taller['importeProfesional']);
       // $this->excel->getActiveSheet()->setCellValue('G' . $row, $taller['numAsistentesTodos']);
       // $this->excel->getActiveSheet()->setCellValue('H' . $row, $taller['importeTodos']);
    }
    
    if ($tipoTaller == 'professionals') {
       // $this->excel->getActiveSheet()->setCellValue('C' . $row, $taller['numAsistentesVoluntario']);
       // $this->excel->getActiveSheet()->setCellValue('D' . $row, $taller['importeVoluntario']);
        $this->excel->getActiveSheet()->setCellValue('C' . $row, $taller['numAsistentesProfesional']);
        $this->excel->getActiveSheet()->setCellValue('D' . $row, $taller['importeProfesional']);
        $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');


        // $this->excel->getActiveSheet()->setCellValue('G' . $row, $taller['numAsistentesTodos']);
       // $this->excel->getActiveSheet()->setCellValue('H' . $row, $taller['importeTodos']);
    }
    
    if ($tipoTaller == 'tots') {
        $this->excel->getActiveSheet()->setCellValue('D' . $row, $taller['importeVoluntario']);
        $this->excel->getActiveSheet()->setCellValue('C' . $row, $taller['numAsistentesVoluntario']);
        $this->excel->getActiveSheet()->setCellValue('E' . $row, $taller['numAsistentesProfesional']);
        $this->excel->getActiveSheet()->setCellValue('F' . $row, $taller['importeProfesional']);
        $this->excel->getActiveSheet()->setCellValue('G' . $row, $taller['numAsistentesTodos']);
        $this->excel->getActiveSheet()->setCellValue('H' . $row, $taller['importeTodos']);
        $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');
        $this->excel->getActiveSheet()->getStyle('F' . $row)->getNumberFormat()->setFormatCode('###0.00');
        $this->excel->getActiveSheet()->getStyle('H' . $row)->getNumberFormat()->setFormatCode('###0.00');
    }
    
    $row++;
}

    $this->excel->getActiveSheet()->setCellValue('B'.$row, $talleresNumTalleres);
    if ($tipoTaller == 'voluntaris') {
    $this->excel->getActiveSheet()->setCellValue('C'.$row, $talleresTotalNumAsisntentesVoluntario);
    $this->excel->getActiveSheet()->setCellValue('D'.$row, $talleresTotalVoluntario);
    $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');

    }
    if ($tipoTaller == 'professionals') {
       $this->excel->getActiveSheet()->setCellValue('C'.$row, $talleresTotalNumAsisntentesProfesional);
    $this->excel->getActiveSheet()->setCellValue('D'.$row, $talleresTotalProfesional);
    $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');

    
    }
    if ($tipoTaller == 'tots') {
        $this->excel->getActiveSheet()->setCellValue('C'.$row, $talleresTotalNumAsisntentesVoluntario);
    $this->excel->getActiveSheet()->setCellValue('D'.$row, $talleresTotalVoluntario);
    $this->excel->getActiveSheet()->setCellValue('E'.$row, $talleresTotalNumAsisntentesProfesional);
    $this->excel->getActiveSheet()->setCellValue('F'.$row, $talleresTotalProfesional);
    $this->excel->getActiveSheet()->setCellValue('G'.$row, $talleresTotalNumAsisntentesTodos);
    $this->excel->getActiveSheet()->setCellValue('H'.$row, $talleresTotalTodos);
    $this->excel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode('###0.00');
    $this->excel->getActiveSheet()->getStyle('F' . $row)->getNumberFormat()->setFormatCode('###0.00');
    $this->excel->getActiveSheet()->getStyle('H' . $row)->getNumberFormat()->setFormatCode('###0.00');

    }

    $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $this->excel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $this->excel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $this->excel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            if(false && $tipoTaller=='tots'){
                $this->excel->getActiveSheet()->setCellValue('C'.$row, $numAsistentesVoluntarios);
                $this->excel->getActiveSheet()->setCellValue('D'.$row, $totalVoluntarios);
                $this->excel->getActiveSheet()->setCellValue('E'.$row, $numAsistentesProfesionales);
                $this->excel->getActiveSheet()->setCellValue('F'.$row, $totalProfesionales);
                $this->excel->getActiveSheet()->setCellValue('G'.$row, $numAsistentes);
                $this->excel->getActiveSheet()->setCellValue('H'.$row, number_format($total),2);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $this->excel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);
                
            }
            
           
$row++;$row++;
            $this->excel->getActiveSheet()->setCellValue('A'.$row, date("d/m/Y"));
            $this->excel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
         


$filename='Resum Tallers.xls'; //save our workbook as this file name
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