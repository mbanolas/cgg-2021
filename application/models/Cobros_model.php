<?php

class Cobros_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('maba');
    }
    
    function marcarDevoluciones(){
        $sql="UPDATE casal_recibos SET devolucion=0 WHERE 1";
        $this->db->query($sql);
        $sql="SELECT * FROM casal_recibos WHERE 1";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){ 
            if(substr($v->recibo,0,2)=="RD"){
              $id=$v->id;
              $sql="UPDATE casal_recibos SET devolucion=1 WHERE id='$id'";
              $this->db->query($sql);
            }
        }
    }
    function getExcelInformeAyuntamiento($desde,$hasta){
        $tituloCasal=strtoupper(getTituloCasal());
        $datosCabecera=array();
        $datosCabecera['equipament']='EQUIPAMENT MUNICIPAL: '.$tituloCasal;
        $datosCabecera['adjudicatari']='ADJUDICATARI: SERVEIS A LES PERSONES INCOOP, SCCL';
        $datosCabecera['nif']='NIF ADJUDICATARI: F60137411';
        $datosCabecera['contarcte']='NÚM CONTRACTE: 18001022';
        $datosCabecera['periodo']='Periode: '.substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' - '.substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4);
        
        $letra=getLetraCasal();
        $numeroRegistroCasalIngresos=getNumeroRegistroCasalIngresos();
        $numeroRegistroCasalDevoluciones=getNumeroRegistroCasalDevoluciones();
        
        $sql="SELECT id FROM casal_recibos WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY id ASC LIMIT 1";
        $primero=0;
        if($this->db->query($sql)->num_rows()>0) 
            $primero=$this->db->query($sql)->row()->id;
        
        $sql="SELECT id FROM casal_recibos WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY id DESC LIMIT 1";
        $ultimo=0;
        if($this->db->query($sql)->num_rows()>0) 
            $ultimo=$this->db->query($sql)->row()->id;
        
         $sql="SELECT r.fecha as fecha,"
                . " lr.id_recibo as recibo,"
                . " lr.importe as importe, "
                . " t.nombre_corto as nombre,"
                . " t.horas_taller_T1 as horas_taller_T1,"
                . " t.horas_taller_T2 as horas_taller_T2,"
                . " t.horas_taller_T3 as horas_taller_T3,"
                . " s.dni as dni,"
                . " lr.tarjeta as tarjeta,"
                . " lr.periodos as periodos,"
                . " lr.id_taller as id_taller,"
                . " lr.id_socio as id_socio,"
                . " lr.id as id,"
                . " lr.num_registro as num_registro,"
                . " lr.num_registro_posicion as num_registro_posicion"
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id"
                . " LEFT JOIN casal_talleres t ON t.id=lr.id_taller"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=lr.id_socio"
                . " WHERE lr.importe>0 AND lr.id_recibo>='$primero' AND lr.id_recibo<='$ultimo'  ORDER BY lr.num_registro_posicion";
         
        $recibos=array();
        if($this->db->query($sql)->num_rows()>0) 
            $recibos=$this->db->query($sql)->result();
        $ingresos=array();
        
        $importeTotal=0;
        foreach ($recibos as $k=>$v) {
            $linea=array();
            $fecha=$v->fecha;
            $fecha=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
            $linea['fecha']=$fecha;
            $linea['numeroRegistroCasal']=$v->num_registro;
            $linea['numeroRegistroPosicion']=$v->num_registro_posicion;
            $linea['dni']=$v->dni; 
            $linea['nombre']=$v->nombre; 
            $linea['recibo']=$letra.' '.$v->recibo;      
            if($v->periodos==4) $horas=$v->horas_taller_T1;
            if($v->periodos==2) $horas=$v->horas_taller_T2;
            if($v->periodos==1) $horas=$v->horas_taller_T3;      
            $preu_hora=number_format($v->importe/floatval($horas)*100,2);   
            $linea['preu_hora']=$preu_hora;
            $linea['importe']=number_format($v->importe,2);
            $linea['iva']=0.00;
            $linea['importeTotal']=number_format($v->importe,2);
            $importeTotal+=number_format($v->importe,2);
            $tarjeta=number_format($v->tarjeta,2);
            if($tarjeta==0) $pago="Efectiu"; else $pago="TPV fisic";
            $linea['tipologia']=$pago;
            $ingresos[]=$linea;
        }
        
        $sql="SELECT r.fecha as fecha,"
                . " lr.id_recibo as recibo,"
                . " lr.importe as importe, "
                . " t.nombre_corto as nombre,"
                . " t.horas_taller_T1 as horas_taller_T1,"
                . " t.horas_taller_T2 as horas_taller_T2,"
                . " t.horas_taller_T3 as horas_taller_T3,"
                . " s.dni as dni,"
                . " lr.tarjeta as tarjeta,"
                . " lr.periodos as periodos,"
                . " lr.id_taller as id_taller,"
                . " lr.id_socio as id_socio,"
                . " lr.id as id,"
                . " lr.num_registro as num_registro,"
                . " lr.num_registro_posicion as num_registro_posicion"
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id"
                . " LEFT JOIN casal_talleres t ON t.id=lr.id_taller"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=lr.id_socio"
                . " WHERE lr.importe<0 AND lr.id_recibo>='$primero' AND lr.id_recibo<='$ultimo' ORDER BY lr.num_registro_posicion";
        
        $recibos=array();
        if($this->db->query($sql)->num_rows()>0) 
            $recibos=$this->db->query($sql)->result();
        
        $devoluciones=array();
        
        $importeTotalDevoluciones=0;
        foreach ($recibos as $k=>$v) {
            $linea=array();
            $fecha=$v->fecha;
            $fecha=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
            $linea['fecha']=$fecha;
            $linea['numeroRegistroCasal']=$v->num_registro;
            $linea['numeroRegistroPosicion']=$v->num_registro_posicion;
            $linea['dni']=$v->dni; 
            $linea['nombre']=$v->nombre; 
            $linea['recibo']=$letra.' '.$v->recibo;      
            if($v->periodos==4) $horas=$v->horas_taller_T1;
            if($v->periodos==2) $horas=$v->horas_taller_T2;
            if($v->periodos==1) $horas=$v->horas_taller_T3;      
            $preu_hora=number_format($v->importe/$horas*100,2);   
            $linea['preu_hora']=$preu_hora;
            $linea['importe']=-number_format($v->importe,2);
            $linea['iva']=0;
            $linea['importeTotal']=-number_format($v->importe,2);
            $importeTotalDevoluciones+=$v->importe;
            $tarjeta=$v->tarjeta;
            if($tarjeta==0) $pago="Efectiu"; else $pago="TPV fisic";
            $linea['tipologia']=$pago;
            $devoluciones[]=$linea;
        }
        $total=number_format($importeTotal,2)+number_format($importeTotalDevoluciones,2);
        
        return array('datosCabecera'=>$datosCabecera,'ingresos'=>$ingresos,'devoluciones'=>$devoluciones,'importeTotal'=>$importeTotal,'importeTotalDevoluciones'=>$importeTotalDevoluciones);
    }
    
    
    
    function getPdfInformeCobros($desde,$hasta){
        $sql="SELECT  r.id, r.fecha as fecha ,  r.id_socio as id_socio ,  r.importe as importe ,  r.recibo as recibo, s.nombre as nombre,s.apellidos as apellidos FROM casal_recibos r
              LEFT JOIN casal_socios_nuevo s  ON s.num_socio=r.id_socio
              WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY r.id";
        $recibos=$this->db->query($sql)->result();
        
    
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
   
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    $fechaDesde=substr($desde,8,2).'-'.substr($desde,5,2).'-'.substr($desde,0,4);  
    $fechaHasta=substr($hasta,8,2).'-'.substr($hasta,5,2).'-'.substr($hasta,0,4);  
    $this->pdf->setSubtitulo('Llista rebuts des '.$fechaDesde.' fins '.$fechaHasta );
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle('Llista rebuts des '.$fechaDesde.' fins '.$fechaHasta );
    $this->pdf->SetLeftMargin(15);
    $this->pdf->SetRightMargin(15);
    $this->pdf->SetFillColor(200,200,200);
 
    // Se define el formato de fuente: Arial, negritas, tamaño 9
    $this->pdf->SetFont('Arial', 'B', 9);
    /*
     * TITULOS DE COLUMNAS
     *
     * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
     */
 
    $this->pdf->Cell(20,7,'Fecha','TBL',0,'C','1');
   // $this->pdf->Cell(35,7,'Recibo ','TBRL',0,'L','1');
   // $this->pdf->Cell(20,7,utf8_decode('Núm Socio'),'TBRL',0,'C','1');
    $this->pdf->Cell(75,7,'Nombre','TBRL',0,'L','1');
    $this->pdf->Cell(18,7,iconv('UTF-8', 'CP1252',' Volun. (€)'.' '),'TBRL',0,'R','1');
    $this->pdf->Cell(18,7,iconv('UTF-8', 'CP1252',' Profe. (€)'.' '),'TBRL',0,'R','1');
    $this->pdf->Cell(18,7,iconv('UTF-8', 'CP1252',' Total. (€)'.' '),'TBRL',0,'R','1');
    $this->pdf->Cell(18,7,iconv('UTF-8', 'CP1252',' Metal. (€)'.' '),'TBRL',0,'R','1');
    $this->pdf->Cell(18,7,iconv('UTF-8', 'CP1252',' Targ. (€)'.' '),'TBRL',0,'R','1');
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(40,7,'','TB',0,'C','1');
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(25,7,'','TBR',0,'C','1');
    $this->pdf->Ln(7);
    // La variable $x se utiliza para mostrar un número consecutivo
    $x = 1;
    $num=0;
    $numAsistentes=0;
    $total=0;
    $totalVoluntarios=0;
    $totalProfesionales=0;
    $totalMetalico=0;
    $totalTarjetas=0;
    
    
    foreach ($recibos as $k=>$v) {
        $fecha=substr($v->fecha,8,2).'/'.substr($v->fecha,5,2).'/'.substr($v->fecha,0,4);
        $this->pdf->Cell(20,5,$fecha,'BL',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
     // $this->pdf->Cell(20,5,utf8_decode($recibo->id_socio),'BLR',0,'C',0);
      $nombre=$v->apellidos.', '.$v->nombre;
      $this->pdf->Cell(75,5,iconv('UTF-8', 'CP1252',$nombre),'BLR',0,'L',0);
      $id=$v->id;
      $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
      $row=$this->db->query($sql)->row();
      $this->pdf->Cell(18,5,number_format($row->importe,2).'   ','BLR',0,'R',0);
      $totalVoluntarios+=$row->importe;
      
      $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
      $row=$this->db->query($sql)->row();
      $this->pdf->Cell(18,5,number_format($row->importe,2).'   ','BLR',0,'R',0);
      $totalProfesionales+=number_format($row->importe,2);
      
      $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
      $row=$this->db->query($sql)->row();
      $this->pdf->Cell(18,5,number_format($row->importe,2).'   ','BLR',0,'R',0);
      $total+=number_format($row->importe,2);
      
      $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
      $row=$this->db->query($sql)->row();
      $this->pdf->Cell(18,5,number_format($row->importe,2).'   ','BLR',0,'R',0);
      $totalMetalico+=number_format($row->importe,2);
      
      $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
      $row=$this->db->query($sql)->row();
      $this->pdf->Cell(18,5,number_format($row->importe,2).'   ','BLR',0,'R',0);
      $totalTarjetas+=number_format($row->importe,2);
       
      
      //$textoMostarRecibo=$recibo->recibo;
      //if(strlen($textoMostarRecibo)>25)
        $textoMostarRecibo=substr($v->recibo,0,12).'... .pdf';
      //$this->pdf->Cell(35,5,$textoMostarRecibo,'BLR',0,'L',0);
      //Se agrega un salto de linea
      $this->pdf->Ln(5);
      $num++;
     
      //$total+=$v->importe;
    }
    $this->pdf->Ln(1);
    $this->pdf->Cell(20,5,$num,'TBLR',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
      //$this->pdf->Cell(20,5,$num,'TBLR',0,'C',0);
      $this->pdf->Cell(75,5,'','TBLR',0,'C',0);
      $this->pdf->Cell(18,5,number_format($totalVoluntarios,2).'   ','TBLR',0,'R',0);
      $this->pdf->Cell(18,5,number_format($totalProfesionales,2).'   ','TBLR',0,'R',0);
      $this->pdf->Cell(18,5,number_format($total,2).'   ','TBLR',0,'R',0);
      $this->pdf->Cell(18,5,number_format($totalMetalico,2).'   ','TBLR',0,'R',0);
      $this->pdf->Cell(18,5,number_format($totalTarjetas,2).'   ','TBLR',0,'R',0);
      //$this->pdf->Cell(35,5,'','TBLR',0,'C',0);
      //Se agrega un salto de linea
      $this->pdf->Ln(5);
     
    
    /*
     * Se manda el pdf al navegador
     *
     * $this->pdf->Output(nombredelarchivo, destino);
     *
     * I = Muestra el pdf en el navegador
     * D = Envia el pdf para descarga
     *
     */


$this->pdf->Output("Llista rebuts ".$fechaDesde.' a '.$fechaHasta.".pdf", 'D');
  
    }
    
    function ponerNumRegistro(){
        $sql="SELECT lr.id as id,r.fecha as fecha, lr.importe as importe "
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id "
                . " WHERE r.fecha>='2018-09-01'";
        //log_message('INFO',$sql);
        $result=$this->db->query($sql)->result();
        $num_registroIngresos= getNumeroRegistroCasalIngresos();
        $num_registroDevoluciones= getNumeroRegistroCasalDevoluciones();
        $contadorIngreso=0;
        $contadorDevolucion=0;
        foreach($result as $k=>$v){
            $id=$v->id;
            if($v->importe>0){
                $num_registro=$num_registroIngresos;
                $contadorIngreso++;
                $sql="UPDATE casal_lineas_recibos SET num_registro='$num_registro', num_registro_posicion='$contadorIngreso' WHERE id='$id'";
                $this->db->query($sql);
                //log_message('INFO',$id.' '.$v->fecha.' '.$v->importe.' '.$contadorIngreso);
            }
            else if ($v->importe<0){
                $num_registro=$num_registroDevoluciones;
                $contadorDevolucion++;
                $sql="UPDATE casal_lineas_recibos SET num_registro='$num_registro', num_registro_posicion='$contadorDevolucion' WHERE id='$id'";
                $this->db->query($sql);
            }           
        }
    }

    function ponerNumRegistro2019(){
        $sql="SELECT lr.id as id,r.fecha as fecha, lr.importe as importe "
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id "
                . " WHERE r.fecha>='2019-01-01'";
        //log_message('INFO',$sql);
        $result=$this->db->query($sql)->result();
        $num_registroIngresos= getNumeroRegistroCasalIngresos();
        $num_registroDevoluciones= getNumeroRegistroCasalDevoluciones();
        $contadorIngreso=0;
        $contadorDevolucion=0;
        foreach($result as $k=>$v){
            $id=$v->id;
            if($v->importe>0){
                $num_registro=$num_registroIngresos;
                $contadorIngreso++;
                $sql="UPDATE casal_lineas_recibos SET num_registro='$num_registro', num_registro_posicion='$contadorIngreso' WHERE id='$id'";
                $this->db->query($sql);
                //log_message('INFO',$id.' '.$v->fecha.' '.$v->importe.' '.$contadorIngreso);
            }
            else if ($v->importe<0){
                $num_registro=$num_registroDevoluciones;
                $contadorDevolucion++;
                $sql="UPDATE casal_lineas_recibos SET num_registro='$num_registro', num_registro_posicion='$contadorDevolucion' WHERE id='$id'";
                $this->db->query($sql);
            }           
        }
        return "ok";
    }
    
    function ponerHorasTaller(){
        //Se asumen 11 sesiones por trimestre
        // y precios 15 1h, 21, 1.5h, 31 2h.
        $sql="SELECT id,precio_trimestre FROM casal_talleres WHERE id_curso=4 AND tipo_taller='Professional'";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $horasSesion=0;
            $id=$v->id;
            if($v->precio_trimestre=='21') $horasSesion=1.5*1100;
            if($v->precio_trimestre=='31') $horasSesion=2*1100;
            if($v->precio_trimestre=='15') $horasSesion=1*1100;
            $sql="UPDATE casal_talleres SET horas_taller_T1='$horasSesion',  horas_taller_T2='$horasSesion', horas_taller_T3='$horasSesion'WHERE id='$id'";
            $this->db->query($sql);
        }
    }
    
    function getTablaInformeAjuntament($desde,$hasta){
        //para poner horas teller
        
        // $this->ponerHorasTaller();
        // $this->ponerNumRegistro();
        
        
        $letra=getLetraCasal();
        $numeroRegistroCasalIngresos=getNumeroRegistroCasalIngresos();
        $numeroRegistroCasalDevoluciones=getNumeroRegistroCasalDevoluciones();
        
        $sql="SELECT id FROM casal_recibos WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY id ASC LIMIT 1";
        if($this->db->query($sql)->num_rows()==0){
            $primero=0;
        }
        else {
            $primero=$this->db->query($sql)->row()->id;
        }
        
        $sql="SELECT id FROM casal_recibos WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY id DESC LIMIT 1";
        if($this->db->query($sql)->num_rows()==0){
            $ultimo=0;
        }
        else {
            $ultimo=$this->db->query($sql)->row()->id;
        }
        
        $sql="SELECT  r.id as id, r.fecha as fecha ,  r.id_socio as id_socio ,  r.importe as importe ,  r.recibo as recibo, s.nombre as nombre,s.apellidos as apellidos 
              FROM casal_recibos r
              LEFT JOIN casal_socios_nuevo s  ON s.num_socio=r.id_socio
              WHERE  fecha>='$desde' AND fecha<='$hasta' ORDER BY r.id";
        
            $sql="SELECT r.fecha as fecha,"
                . " lr.id_recibo as recibo,"
                . " lr.importe as importe, "
                . " t.nombre_corto as nombre,"
                . " t.horas_taller_T1 as horas_taller_T1,"
                . " t.horas_taller_T2 as horas_taller_T2,"
                . " t.horas_taller_T3 as horas_taller_T3,"
                . " s.dni as dni,"
                . " lr.tarjeta as tarjeta,"
                . " lr.periodos as periodos,"
                . " lr.id_taller as id_taller,"
                . " lr.id_socio as id_socio,"
                . " lr.id as id,"
                . " lr.num_registro as num_registro,"
                . " lr.num_registro_posicion as num_registro_posicion,"
                . " s.num_socio as num_socio"
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id"
                . " LEFT JOIN casal_talleres t ON t.id=lr.id_taller"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=lr.id_socio"
                . " WHERE lr.importe>0 AND lr.id_recibo>='$primero' AND lr.id_recibo<='$ultimo' ORDER BY lr.num_registro_posicion";
        
       //log_message('INFO',$sql);
       
       $recibos=array(); 
       if($this->db->query($sql)->num_rows()>0) 
            $recibos=$this->db->query($sql)->result();
       
        
        $cabeceraTabla='<table class="table table-bordered table-hover"><tbody>
                <thead>
                    <tr >
                        <th class="col-sm-1 text-center">Data</th>
                        <th class="col-sm-1 text-center">Num Registre</th>
                        <th class="col-sm-1 text-center">DNI Usuari</th>
                        <th class="col-sm-1 text-center">Nom Actividad</th>
                        <th class="col-sm-1 text-center">Num Registre Ingrés</th>
                        <th class="col-sm-1 text-center" >Preu/hora</th>
                        <th class="col-sm-1 text-center">Import Base</th>
                        <th class="col-sm-1 text-center">% IVA (exempt)</th>
                        <th class="col-sm-1 text-center" style="border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;">IMPORT TOTAL</th>
                        <th class="col-sm-1 text-center">TIPOLOGIA INGRES</th>
                        
                    </tr>';
       
        
                
        $tabla=$cabeceraTabla;
        
        $importeTotal=0;
        foreach ($recibos as $k=>$v) {
            $fecha=$v->fecha;
            $fecha=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
            $tabla.='<tr>';
            $tabla.='<td class="text-center">';
            $tabla.= $fecha;
            $tabla.='</td>';
            
            $num=strval($v->num_registro_posicion);
            while(strlen($num)<5) {
                $num='0'.$num;
            }
            $tabla.='<td class="text-center">';
            $tabla.= $v->num_registro.$num;
            $tabla.='</td>';
            
            
            $dni=$v->dni;
            if($this->socios_model->validar_dni($dni)){
                $tabla.='<td class="text-center">';
                $tabla.= strtoupper($dni);
                $tabla.='</td>';
            }
            else{
                $tabla.='<td class="text-center" style="color:red">';
                $tabla.= strtoupper($dni)."(".$v->num_socio.")";
                $tabla.='</td>';
            }
            
            $nombre=$v->nombre;
            $tabla.='<td class="text-center">';
            $tabla.= $nombre;
            $tabla.='</td>';
            
            $recibo=$letra.' '.$v->recibo;      
            $tabla.='<td class="text-center">';
            $tabla.= $recibo;
            $tabla.='</td>';
            
            if($v->periodos==4) $horas=floatval($v->horas_taller_T1);
            if($v->periodos==2) $horas=floatval($v->horas_taller_T2);
            if($v->periodos==1) $horas=floatval($v->horas_taller_T3);  
            //log_message('INFO', '===================='.$v->nombre.' '.$horas);
            
            if($horas>0)
                $preu_hora=number_format($v->importe/$horas*100,2);  
            else 
                $preu_hora=0;

            $tabla.='<td class="text-center">';
            $tabla.= $preu_hora;
            $tabla.='</td>';
            
            
            
            $importe=$v->importe;
            $tabla.='<td class="text-center" >';
            $tabla.= number_format($importe,2);
            $tabla.='</td>';
            
            $importe=$v->importe;
            $tabla.='<td class="text-center">';
            $tabla.= '0.00';
            $tabla.='</td>';
            
            $importe=number_format($v->importe,2);
            $tabla.='<td class="text-center" style="border-right:2px solid black;border-left:2px solid black;">';
            $tabla.= number_format($importe,2);
            $tabla.='</td>';
            $importeTotal+=number_format($importe,2);
            
            $tarjeta=number_format($v->tarjeta,2);
            if($tarjeta==0) $pago="Efectiu"; else $pago="TPV fisic";
            $tabla.='<td class="text-center">';
            $tabla.= $pago;
            $tabla.='</td>';
            
            $tabla.='</tr>';
        }
        
        
        $pieTabla='</tr></thead><thead><tr>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center" style="border-bottom:1px solid white;border-right:0px solid white;border-left:1px solid white;"></th>';
        $pieTabla.='<th class="text-center">T O T A L S</th>';
        $pieTabla.='<th class="text-center" style="border-bottom:2px solid black;border-right:2px solid black;border-left:2px solid black;">';
        $pieTabla.=number_format($importeTotal,2);
        $pieTabla.='</th>';
        $pieTabla.='</tr></thead></tody></table>';
        
        $tabla.=$pieTabla;
        
        
        $cabeceraTablaDevoluciones='<table class="table table-bordered table-hover"><tbody>
                <thead>
                    <tr >
                        <th class="col-sm-1 text-center">Data</th>
                        <th class="col-sm-1 text-center">Num Registre</th>
                        <th class="col-sm-1 text-center">DNI Usuari</th>
                        <th class="col-sm-1 text-center">Nom Actividad</th>
                        <th class="col-sm-1 text-center">Num Registre Devolució</th>
                        <th class="col-sm-1 text-rigcenterht" >Preu/hora</th>
                        <th class="col-sm-1 text-center">Import Base</th>
                        <th class="col-sm-1 text-center">% IVA (exempt)</th>
                        <th class="col-sm-1 text-center" style="border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;">IMPORT TOTAL</th>
                        <th class="col-sm-1 text-center">TIPOLOGIA DEVOLUCIÓ</th>
                        
                    </tr>';
        
        $tituloCasal=strtoupper(getTituloCasal());
        $salida='<h4>INFORME DETALLAT INGRESSOS</h4>'
                . ''
                . 'EQUIPAMENT MUNICIPA: <STRONG>'.$tituloCasal.'</STRONG>'
                . '<BR>'
                . 'ADJUDICATARI: <STRONG>'.'SERVEIS A LES PERSONES INCOOP, SCCL</STRONG>'
                . '<BR>'
                . 'NIF ADJUDICATARI: <STRONG>F60137411</STRONG>'
                . '<BR>'
                . 'NÚM CONTRACTE: <STRONG>18001022</STRONG>'
                . '<BR>'
                . 'Periode: <STRONG>'.substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' - '.substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).'</STRONG>'
                . '<BR>'
                . '<BR>'
                
                .$tabla.'<br>';
        
        $sql="SELECT r.fecha as fecha,"
                . " lr.id_recibo as recibo,"
                . " lr.importe as importe, "
                . " t.nombre_corto as nombre,"
                . " t.horas_taller_T1 as horas_taller_T1,"
                . " t.horas_taller_T2 as horas_taller_T2,"
                . " t.horas_taller_T3 as horas_taller_T3,"
                . " s.dni as dni,"
                . " lr.tarjeta as tarjeta,"
                . " lr.periodos as periodos,"
                . " lr.id_taller as id_taller,"
                . " lr.id_socio as id_socio,"
                . " lr.id as id,"
                . " lr.num_registro as num_registro,"
                . " lr.num_registro_posicion as num_registro_posicion,"
                . " s.num_socio"
                . " FROM casal_lineas_recibos lr"
                . " LEFT JOIN casal_recibos r ON lr.id_recibo=r.id"
                . " LEFT JOIN casal_talleres t ON t.id=lr.id_taller"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=lr.id_socio"
                . " WHERE lr.importe<0 AND lr.id_recibo>='$primero' AND lr.id_recibo<='$ultimo' ORDER BY lr.num_registro_posicion";
        
        
        
        $recibos=array(); 
       if($this->db->query($sql)->num_rows()>0) 
            $recibos=$this->db->query($sql)->result();
        
        $tabla=$cabeceraTablaDevoluciones;
        $importeTotalDevoluciones=0;
        foreach ($recibos as $k=>$v) {
            $fecha=$v->fecha;
            $fecha=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
            $tabla.='<tr>';
            $tabla.='<td class="text-center">';
            $tabla.= $fecha;
            $tabla.='</td>';
            
            $num=strval($v->num_registro_posicion);
            while(strlen($num)<5) {
                $num='0'.$num;
            }     
            $tabla.='<td class="text-center">';
            $tabla.= $v->num_registro.$num;;
            $tabla.='</td>';
            
            $dni=$v->dni;
            if($this->socios_model->validar_dni($dni)){
                $tabla.='<td class="text-center">';
                $tabla.= strtoupper($dni);
                $tabla.='</td>';
            }
            else{
                $tabla.='<td class="text-center" style="color:red">';
                $tabla.= strtoupper($dni)."(".$v->num_socio.")";
                $tabla.='</td>';
            }
            
            $nombre=$v->nombre;
            $tabla.='<td class="text-center">';
            $tabla.= $nombre;
            $tabla.='</td>';
            
            $recibo=$letra.' '.$v->recibo;       
            $tabla.='<td class="text-center">';
            $tabla.= $recibo;
            $tabla.='</td>';
            
            
            
            /*
            $id_taller=$v->id_taller;
            $periodos=$v->periodos;
            $id_socio=$v->id_socio;
            $importe=-$v->importe;
            $id=$v->id;
            $sql="SELECT * FROM casal_lineas_recibos WHERE id<'$id' AND id_taller='$id_taller' AND id_socio='$id_socio' AND periodos='$periodos' AND importe='$importe' ORDER BY id DESC LIMIT 1";
            //log_message('INFO',$sql);
            if($this->db->query($sql)->num_rows()==1) {
                $recibo=$letra.' '.$this->db->query($sql)->row()->id_recibo;
            }
            else $recibo='';
            $tabla.='<td class="text-center">';
            $tabla.= $recibo;
            $tabla.='</td>';
            */
            
            
            if($v->periodos==4) $horas=$v->horas_taller_T1;
            if($v->periodos==2) $horas=$v->horas_taller_T2;
            if($v->periodos==1) $horas=$v->horas_taller_T3;  
            
            //log_message('INFO', '++=================='.$v->nombre.' '.$horas);
            
            $preu_hora=number_format($v->importe/$horas*100,2);          
            $tabla.='<td class="text-center">';
            $tabla.= -$preu_hora;
            $tabla.='</td>';
            
            
            
            $importe=number_format($v->importe,2);
            $tabla.='<td class="text-center" >';
            $tabla.= -$importe;
            $tabla.='</td>';
            
            $importe=number_format($v->importe,2);
            $tabla.='<td class="text-center">';
            $tabla.= '0.00';
            $tabla.='</td>';
            
            $importe=number_format($v->importe,2);
            $tabla.='<td class="text-center" style="border-right:2px solid black;border-left:2px solid black;">';
            $tabla.= -$importe;
            $tabla.='</td>';
            $importeTotalDevoluciones+=$importe;
            
            $tarjeta=number_format($v->tarjeta,2);
            if($tarjeta==0) $pago="Efectiu"; else $pago="TPV fisic";
            $tabla.='<td class="text-center">';
            $tabla.= $pago;
            $tabla.='</td>';
            
            $tabla.='</tr>';
        }
       
        
        
        
        $pieTablaDevoluciones='</tr></thead><thead><tr>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:1px solid white;border-right:0px solid white;border-left:1px solid white;"></th>';
        $pieTablaDevoluciones.='<th class="text-center">T O T A L S</th>';
        $pieTablaDevoluciones.='<th class="text-center" style="border-bottom:2px solid black;border-right:2px solid black;border-left:2px solid black;">';
        $pieTablaDevoluciones.=-number_format($importeTotalDevoluciones,2);
        $pieTablaDevoluciones.='</th>';
        $pieTablaDevoluciones.='</tr></thead></tody></table>';
        
        
        
       
        
        $salida.='<h4>INFORME DETALLAT DEVOLUCIONS</h4>'
                . ''
                . 'EQUIPAMENT MUNICIPA: <STRONG>'.$tituloCasal.'</STRONG>'
                . '<BR>'
                . 'ADJUDICATARI: <STRONG>'.'SERVEIS A LES PERSONES INCOOP, SCCL</STRONG>'
                . '<BR>'
                . 'NIF ADJUDICATARI: <STRONG>F60137411</STRONG>'
                . '<BR>'
                . 'NÚM CONTRACTE: <STRONG>18001022</STRONG>'
                . '<BR>'
                . 'Periode: <STRONG>'.substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' - '.substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).'</STRONG>'
                . '<BR>'
                . '<BR>';
                
               
        
        
        
        
        $salida.=$tabla;
        $salida.=$pieTablaDevoluciones;
        $salida.='<br><h4>RESUM TOTAL</h4>';
        
        $importeResumen=number_format($importeTotal,2)+number_format($importeTotalDevoluciones,2);
        $resumenTotal='<table class="table table-bordered table-hover"><tbody>
                <thead>
                    <tr >
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;">IMPORT TOTAL</th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        
                    </tr>
                    <tr >
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-left:1px solid white;"></th>
                        <th class="col-sm-1 text-center" style="border-bottom:2px solid #DDDDDD;border-top:2px solid #DDDDDD;border-left:1px solid #DDDDDD;">T O T A L S</th>
                        <th class="col-sm-1 text-center" style="border-bottom:2px solid black;border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;">'.number_format($importeResumen,2).'</th>
                        <th class="col-sm-1 text-center" style="border-bottom:1px solid white;border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;"></th>
                        
                    </tr>



                </thead></tbody></table>';
        
        $salida.=$resumenTotal;
        
        
        
       
        return $salida;
        
    }
    
    
    
    
    function getTablaInformeCobros($desde,$hasta){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
        $sql="SELECT  r.id as id, r.fecha as fecha ,  r.id_socio as id_socio ,  r.importe as importe ,  r.recibo as recibo, s.nombre as nombre,s.apellidos as apellidos 
              FROM casal_recibos r
              LEFT JOIN casal_socios_nuevo s  ON s.num_socio=r.id_socio
              WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY  r.id ASC LIMIT 1";
        
        $primero=0;
        if($this->db->query($sql)->num_rows()>0)
            $primero=$this->db->query($sql)->row()->id;
        
        $sql="SELECT  r.id as id, r.fecha as fecha ,  r.id_socio as id_socio ,  r.importe as importe ,  r.recibo as recibo, s.nombre as nombre,s.apellidos as apellidos 
              FROM casal_recibos r
              LEFT JOIN casal_socios_nuevo s  ON s.num_socio=r.id_socio
              WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY  r.id DESC LIMIT 1";
        
        
        $ultimo=0;
        if($this->db->query($sql)->num_rows()>0)
            $ultimo=$this->db->query($sql)->row()->id;
        
        $cabeceraTabla='<table class="table table-bordered table-hover"><tbody>
                <thead>
                    <tr >
                        <th class="col-sm-2 text-center">Data</th>
                        <th class="col-sm-2 text-left">Rebut</th>
                        <th class="col-sm-3 text-left">Nom</th>
                        <th class="col-sm-1 text-right">Imports Voluntaris(€)</th>
                        <th class="col-sm-1 text-right">Imports Professionals(€)</th>
                        <th class="col-sm-1 text-right" style="border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;">Imports Total(€)</th>
                        <th class="col-sm-1 text-right">Voluntaris Imports Metàl.lic(€)</th>
                        <th class="col-sm-1 text-right">Voluntaris Imports Targetas(€)</th>
                        <th class="col-sm-1 text-right">Professionals Imports Metàl.lic (€)</th>
                        <th class="col-sm-1 text-right">Professionals Imports Targetas(€)</th>
                        <th class="col-sm-1 text-right">Total Imports Metàl.lic (€)</th>
                        <th class="col-sm-1 text-right">Total Imports Targetas(€)</th>
                        
                    </tr>';
       
        $numDocumentos=0;
        $total=0;
        $totalVoluntarios=0;
        $totalProfesionales=0;
        $totalMetalico=0;
        $totalTarjetas=0;
        $totalMetalicoVoluntarios=0;
        $totalTarjetasVoluntarios=0;
        $totalMetalicoProfesionales=0;
        $totalTarjetasProfesionales=0;
        /*
         foreach ($recibos as $k=>$v) {
            $id=$v->id;
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalVoluntarios+= $row->importe;
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalProfesionales+= $row->importe;
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id'";
            $row=$this->db->query($sql)->row();
            $total+= $row->importe;
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalMetalicoVoluntarios+= $row->importe;
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalTarjetasVoluntarios+= $row->importe;
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalMetalicoProfesionales+= $row->importe;
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalTarjetasProfesionales+= $row->importe;
             
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
            $row=$this->db->query($sql)->row();
            $totalMetalico+= $row->importe;
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
            $row=$this->db->query($sql)->row();
            $totalTarjetas+= $row->importe;
            $numDocumentos++;
            
        }
        */
         
        if($primero) {   
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalVoluntarios+= number_format($row->importe,2);
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalProfesionales+= number_format($row->importe,2);
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo' ";
            $row=$this->db->query($sql)->row();
            $total+= number_format($row->importe,2);
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalMetalicoVoluntarios+= number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            $totalTarjetasVoluntarios+= number_format($row->importe,2);
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalMetalicoProfesionales+= number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            $totalTarjetasProfesionales+= number_format($row->importe,2);
             
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  ";
            $row=$this->db->query($sql)->row();
            $totalMetalico+= number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo>='$primero' AND id_recibo<='$ultimo'  ";
            $row=$this->db->query($sql)->row();
            $totalTarjetas+= number_format($row->importe,2);
            $numDocumentos++;
        }   
        
                
        $tabla=$cabeceraTabla;
        
        $pieTabla='</tr></thead><thead><tr>';
        $pieTabla.='<th colspan="3" class="text-center">T O T A L S</th>';
        
        
        
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right" style="border-top:4px solid black;border-bottom:4px solid black;border-left:4px solid black;border-right:4px solid black;">';
        $pieTabla.=number_format($total,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalicoVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetasVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalicoProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetasProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalico,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetas,2);
        $pieTabla.='</th>';
        $pieTabla.='</tr></thead>';
        
        $tabla.=$pieTabla;
        
        $numDocumentos=0;
        $total=0;
        $totalVoluntarios=0;
        $totalProfesionales=0;
        $totalMetalico=0;
        $totalTarjetas=0;
        $totalMetalicoVoluntarios=0;
        $totalTarjetasVoluntarios=0;
        $totalMetalicoProfesionales=0;
        $totalTarjetasProfesionales=0;
        
        $sql="SELECT  r.id as id, r.fecha as fecha ,  r.id_socio as id_socio ,  r.importe as importe ,  r.recibo as recibo, s.nombre as nombre,s.apellidos as apellidos 
              FROM casal_recibos r
              LEFT JOIN casal_socios_nuevo s  ON s.num_socio=r.id_socio
              WHERE fecha>='$desde' AND fecha<='$hasta' ORDER BY  r.id ASC ";
        
        if($this->db->query($sql)->num_rows()>0){
        $recibos=$this->db->query($sql)->result();
        
        foreach ($recibos as $k=>$v) {
            
            $fecha=$v->fecha;
            $fecha=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
            $tabla.='<tr>';
            $tabla.='<td class="text-center">';
            $tabla.= $fecha;
            $tabla.='</td>';
            
            $tabla.='<td class="text-left">';
            $documento='<a href="'.base_url().'recibos/'.$v->recibo.'" target="_blank">'.'Baixar rebut'.'</a>';
            $tabla.= $documento;
            $tabla.='</td>';
             
            $tabla.='<td class="text-left">';
            $tabla.= $v->apellidos.', '.$v->nombre;
            $tabla.='</td>';
            
            $id=$v->id;
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.= number_format($row->importe,2);
            $tabla.='</td>';
            $totalVoluntarios+=  number_format($row->importe,2);
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalProfesionales+=  number_format($row->importe,2);
            
            $sql="SELECT sum(importe) as importe FROM casal_lineas_recibos WHERE id_recibo='$id'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right" style="border-left:2px solid black;border-right:2px solid black;">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $total+=  number_format($row->importe,2);
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalMetalicoVoluntarios+=  number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Voluntari'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalTarjetasVoluntarios+= $row->importe;
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalMetalicoProfesionales+=  number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' AND tipo_taller='Professional'";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalTarjetasProfesionales+=  number_format($row->importe,2);
            
            
            $sql="SELECT sum(metalico) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
            $totalMetalico+=  number_format($row->importe,2);
            
            $sql="SELECT sum(tarjeta) as importe FROM casal_lineas_recibos WHERE id_recibo='$id' ";
            $row=$this->db->query($sql)->row();
            
            $tabla.='<td class="text-right">';
            $tabla.=  number_format($row->importe,2);
            $tabla.='</td>';
             $totalTarjetas+=  number_format($row->importe,2);
             
            $tabla.='</tr>';
            $numDocumentos++;
            
        }
        }
        
        $pieTabla='</tr></thead><tfoot><tr>';
        $pieTabla.='<th class="text-center">';
        $pieTabla.=$numDocumentos;
        $pieTabla.='</th>';
        $pieTabla.='<th>';
        $pieTabla.='';
        $pieTabla.='</th>';
        
        $pieTabla.='<th class="text-right">';
        $pieTabla.='';
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right" style="border-top:4px solid black;border-bottom:4px solid black;border-left:4px solid black;border-right:4px solid black;">';
        $pieTabla.=number_format($total,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalicoVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetasVoluntarios,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalicoProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetasProfesionales,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalMetalico,2);
        $pieTabla.='</th>';
        $pieTabla.='<th class="text-right">';
        $pieTabla.=number_format($totalTarjetas,2);
        $pieTabla.='</th>';
        $pieTabla.='</tr></tfoot></tbody></table>';
        
        if($primero)
            $tabla.=$pieTabla;
        
        return $tabla;
        
        
       
        
    }
    
}