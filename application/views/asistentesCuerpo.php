<?php

$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
//* es un caracter no válido para PHPExcel ??
$nombre_lista=str_replace ( "*" , "", $nombre_lista );
$nombre_lista=str_replace ( ":" , " ", $nombre_lista );

$this->excel->getActiveSheet()->setTitle(substr($nombre_lista,0,30));

//encabezado titulares
$this->excel->getActiveSheet()->mergeCells('D1:T1');
$this->excel->getActiveSheet()->getStyle('D1:T1')
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$this->excel->getActiveSheet()->getStyle('D1:T1')
        ->getAlignment()
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objDrawing = new Drawing();
$objDrawing->setWorksheet($this->excel->getActiveSheet($hoja));    
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('././img/incoop.png');
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(100);
$objDrawing->setWidth(100);

$widthCol = array(30, 100, 200, 100, 250);
for ($d = 0; $d < count($dias) + 3; $d++)
    $widthCol[] = 18;

$widthCol[] = 100;

//$descripcion = array('Nom', 'Cognoms', 'Telèfon',' Pagat(€) '); 
$descripcion = array('Nom', 'Cognoms', 'Telèfon','Email  '); 
if($listados_sin) $descripcion = array('Nom', 'Cognoms', ' ','Email '); 

$this->excel->getDefaultStyle()->getFont()->setName('Arial');
$this->excel->getDefaultStyle()->getFont()->setSize(11);
    
$c = 'G';

foreach($dias as $k=>$v){
    
    $this->excel->getActiveSheet()->getCell($c . "5")->setValueExplicit(' '.$v.' ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getStyle($c."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle($c."5")->getAlignment()->setTextRotation(90);
    $c++;
}
$columnaFinalDias=$c;

//$this->excel->getActiveSheet()->getCell('E' . "5")->setValueExplicit(' PagaPagat(€) ', PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getStyle('E'."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // $this->excel->getActiveSheet()->getStyle('E'."5")->getAlignment()->setTextRotation(90);


$this->excel->getActiveSheet($hoja)->getStyle('A2:E2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$this->excel->getActiveSheet($hoja)->getStyle('A2:E2')->getFont()->setSize(18);
$this->excel->setActiveSheetIndex($hoja)
        ->setCellValue('A2', "Taller: $nombre_lista");
$this->excel->getActiveSheet($hoja)->getStyle("A2")->getFont()->setBold(true);
$this->excel->setActiveSheetIndex($hoja)
        ->setCellValue('D3', "Professor/a: $profesor");
$this->excel->setActiveSheetIndex($hoja)
        ->setCellValue('D2', "$titulo  $textoPeriodo");
$this->excel->getActiveSheet($hoja)->getStyle('D2:D3')->getFont()->setSize(14);
$this->excel->getActiveSheet($hoja)->getStyle('D3:D4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$this->excel->setActiveSheetIndex($hoja)
        ->setCellValue('D4', "Taller: $tipoTaller");
$this->excel->getActiveSheet($hoja)->setCellValue('D1', getTituloCasal()."\nLLISTA D'ASSISTÈNCIA");
$this->excel->getActiveSheet($hoja)->getStyle('D1')->getAlignment()->setWrapText(true);

$diaCatalan=strtolower($dia1);
switch($diaCatalan){
    case 'lunes': 
        $diaCatalan="Dilluns";
        break;
    case 'martes': 
        $diaCatalan="Dimarts";
        break;
    case 'miércoles': 
    case 'miercoles':     
        $diaCatalan="Dimecres";
        break;
    case 'jueves': 
        $diaCatalan="Dijous";
        break;
    case 'viernes': 
        $diaCatalan="Divendres";
        break;
    case 'sábado': 
    case 'sabado':     
        $diaCatalan="Dissabte";
        break;
    case 'domingo': 
        $diaCatalan="Diumenge";
        break;
}
$dia1Catalan=$diaCatalan;

$diaCatalan=strtolower($dia2);
switch($diaCatalan){
    case 'lunes': 
        $diaCatalan="Dilluns";
        break;
    case 'martes': 
        $diaCatalan="Dimarts";
        break;
    case 'miércoles': 
    case 'miercoles':     
        $diaCatalan="Dimecres";
        break;
    case 'jueves': 
        $diaCatalan="Dijous";
        break;
    case 'viernes': 
        $diaCatalan="Divendres";
        break;
    case 'sábado': 
    case 'sabado':     
        $diaCatalan="Dissabte";
        break;
    case 'domingo': 
        $diaCatalan="Diumenge";
        break;
}
$dia2Catalan=$diaCatalan;







$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('A3', "Dias:");
$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('A4', "Horari:");
$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('B3', $dia1Catalan);
$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('B4', "de $dia1_de a $dia1_hasta");
if($dia2!=""){
$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('C3', $dia2Catalan);
$this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('C4', "de $dia2_de a $dia2_hasta");
}

$this->excel->getActiveSheet($hoja)->getCell('A5')->setValueExplicit('Núm', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet($hoja)->getCell('A5')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$c++;$c++;$c++;
$nc=$c;
$this->excel->getActiveSheet($hoja)->getCell($nc . '5')->setValueExplicit('Observacions', PHPExcel_Cell_DataType::TYPE_STRING);
$observaciones=$nc;
$this->excel->getActiveSheet($hoja)->getCell($nc . '5')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



//poner datos asistentes
$fila=6;
foreach($resultAsistentes->result() as $k=>$v){
   // if($v->estat!='Devolució'){
    $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('A'.$fila, $fila-5);
    $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('B'.$fila, ucwords(strtolower($v->nom)));
    $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('C'.$fila, ucwords(strtolower($v->cognoms)));
    
    
    
    
    $telefono1=trim($v->telefono_1);
    $telefono2=trim($v->telefono_2);
    $telefono=$telefono1;
    if(substr($telefono1,0,1)==9 && $telefono2!="") $telefono=$telefono2; else $telefono=$telefono1;
    if($listados_sin) $telefono="";
    
    $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('D'.$fila, $telefono);
    /*
    $this->excel->setActiveSheetIndex($hoja)
            
            ->setCellValue('E'.$fila, $v->identificador);
     * */
  
  //  $this->excel->setActiveSheetIndex($hoja)
  //          ->setCellValue('E'.$fila, $v->pagado);
    
      $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('E'.$fila, $v->email);

            $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue('F'.$fila, '');      
    
    $this->excel->setActiveSheetIndex($hoja)
            ->setCellValue($observaciones.$fila, "");  //$v->estat=='Cobrada'?'':$v->estat);
    $fila++;
  //  }
}


$styleArray = array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );
    $styleArray2 = array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );
    $styleArray3 = array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );
    $fd=$c++;
    $fd++;
    $this->excel->getActiveSheet()->getStyle('A5:' . $fd . '5')->applyFromArray($styleArray);
   
    $this->excel->getActiveSheet($hoja)->getStyle("A4:$fd"."4")->applyFromArray($styleArray2);
    
    for ($i = 6; $i <= 50; $i++) {
        $this->excel->getActiveSheet($hoja)->getStyle("A$i:$fd$i")->applyFromArray($styleArray2);
    }
    $c = 'A';
    for ($co = 0; $co <100; $co++) {
        $this->excel->getActiveSheet($hoja)->getStyle($c . '5:' . $c . '50')->applyFromArray($styleArray3);
        if($c==$observaciones)            break;
        $c++;
        
    }
    $this->excel->getActiveSheet($hoja)->getStyle($c . '5:' . $c . '50')->applyFromArray($styleArray3);
    $c++;
    //$this->excel->getActiveSheet($hoja)->getStyle($c . '5:' . $c . '50')->applyFromArray($styleArray3);
    $c++;
    $this->excel->getActiveSheet($hoja)->getStyle($c . '5:' . $c . '50')->applyFromArray($styleArray3);

    $fd=$c;
    $r = 5;
    $c = 'B';
    //Se ponen las cabeceras
    foreach ($descripcion as $k => $v) {
            $this->excel->getActiveSheet($hoja)->getCell($c . $r)->setValueExplicit($v, PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet($hoja)->getCell($c . $r)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $c++;
    }

    $this->excel->getActiveSheet($hoja)->getStyle("A3:$fd" . "5")->getFont()->setBold(true);
    $this->excel->getActiveSheet($hoja)->getStyle("D3:D4")->getFont()->setBold(false);

for ($col = 0; $col < count($widthCol); $col++) {
        $this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn($col)->setWidth($widthCol[$col] * 11 / 81);
    }
    
    //$this->excel->setActiveSheetIndex($hoja)->setCellValue('D3', $observaciones);
    $this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn('U')->setWidth(1000 * 11 / 81);
    $u=$observaciones;
    
    
    $r=$fila+10;
    $this->excel->getActiveSheet($hoja)->getStyle('C1:C' . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $this->excel->getActiveSheet()->getStyle('G1:G' . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$this->excel->getActiveSheet($hoja)->getStyle("A5:A$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //$instancia = DB::getInstance();

    $this->excel->getActiveSheet($hoja)->getStyle("G5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->excel->getActiveSheet($hoja)->getRowDimension(1)->setRowHeight(60);
    $this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn('A')->setWidth(30 * 11 / 81);
    //$this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn('U')->setWidth(300 * 11 / 81);
    
    //$this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn($u)->setWidth(580 * 11 / 81);
    

    $this->excel->getActiveSheet($hoja)->getPageSetup()->setRowsToRepeatAtTop(array(1, 5));
    //$instancia->getQuery("INSERT INTO archivos (archivo) values('$nombreArchivo')");
    // Set Orientation, size and scaling
    $this->excel->setActiveSheetIndex($hoja);
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    $ultima=$observaciones;
    $ultima++;
    $ultima++;
    if($fila<30) $fila=30;
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setPrintArea("A1:" . $ultima . $fila);
//$excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setFitToWidth(1);
    $this->excel->getActiveSheet($hoja)->getPageSetup()->setFitToHeight(0);

    $this->excel->getActiveSheet($hoja)->getColumnDimensionByColumn('A')->setWidth(7);
    $this->excel->getActiveSheet($hoja)->setSelectedCell('A1');






