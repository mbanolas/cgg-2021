<?php

$this->excel->removeSheetByIndex(-1);
$filename="Lista Talleres.xls";
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