<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reporte extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
       // $this->load->model('utilidades');
       // $this->load->model('socios_model');
       // $this->load->model('talleres_model');
        
    }
    
   public function index(){
        $this->load->helper('form');
        $this->load->model('talleres_model');
       // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
       // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $datos['tipoTaller']='tots';
        $datos['periodo']=$this->talleres_model->getTextoPeriodos($this->talleres_model->getUltimoPeriodo());
        $datos['textoPeriodo']=$this->talleres_model->getTextoPeriodo($datos['periodo']);
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='Talleres';
        $datos['activeSubmenu']='Resumen socios inscritos taller';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('resumenTalleres',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
   } 
   
 
       

   public function seleccionarSociosInscritos(){
        $this->load->helper('form');
        $this->load->model('talleres_model');
       // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
       // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='Talleres';
        $datos['activeSubmenu']='Lista Socios con Inscripciones Talleres';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('resumenSociosInscritos',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
   } 
   public function listadoTalleres(){
        $this->load->helper('form');
        $this->load->model('talleres_model');
       // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
       // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $datos['tipoTaller']='tots';
        $datos['periodo']=$this->talleres_model->getTextoPeriodos($this->talleres_model->getUltimoPeriodo());
        $datos['textoPeriodo']=$this->talleres_model->getTextoPeriodo($datos['periodo']);
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='Talleres';
        $datos['activeSubmenu']='Resumen socios inscritos taller';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('resumenListadoTalleres',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
   } 
   
   public function informes(){
       if(isset($_POST['pdf']))
           $this->pdf();
       if(isset($_POST['excel']))
           $this->excel();
   }
   
  public function excel(){
      $this->load->library('excel');

        $this->load->helper('maba');
        $this->load->library('drawing');
        $this->load->model('talleres_model');
        $hoja = 0;
        
        $datos['talleres']=$this->talleres_model->getResumenTalleresPagado($_POST['curso'],$_POST['tipoTaller'][0],$_POST['periodo'][0]);
        
        $this->load->view('resumenListadoTalleresExcel',$datos);
    
  }  
   
  public function pdf()
  {
    // Se carga el modelo alumno
    $this->load->model('talleres_model');
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
    $curso=$_POST['curso'];
    $tipoTaller=$_POST['tipoTaller'][0];
    $periodo="";//$_POST['periodoCheckbox'][0];
    /*
    echo '<br>$curso '.$curso;
    echo '<br>$tipoTaller '.$tipoTaller;
    echo '<br>$periodo '.$periodo;
     * 
     */
    //log_message('INFO',$curso.'          '.$tipoTaller.'                  '.$periodo);
    //$
    //
    $textoPeriodo=""; //$this->talleres_model->getTextoPeriodo($periodo);
    // Se obtienen los alumnos de la base de datos
    $talleres = $this->talleres_model->getResumenTalleres($curso,$tipoTaller,$periodo);
    //var_dump($talleres);
     
    
    
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    
    $this->pdf->setSubtitulo(utf8_decode('Tallers '.$tipoTaller.' curs: '.$this->talleres_model->getNombreCurso($curso).' '.$textoPeriodo));
    
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle("Resumen talleres ".$this->talleres_model->getNombreCurso($curso));
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
 
    $this->pdf->Cell(15,7,'NUM','TBL',0,'C','1');
    $this->pdf->Cell(75,7,'TALLER','TBR',0,'L','1');
    $this->pdf->Cell(25,7,'ASSISTENTS','TBR',0,'L','1');
    $this->pdf->Cell(25,7,'TOTALS EUR','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'ASISTENTES','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'TOTALES EUR','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'ASISTENTES','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'TOTALES EUR','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'ASISTENTES','TBR',0,'L','1');
    //$this->pdf->Cell(25,7,'TOTALES EUR','TBR',0,'L','1');
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
    foreach ($talleres as $k=>$taller) {
        $this->pdf->Cell(15,5,$taller['id'],'BL',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
      $this->pdf->Cell(75,5,utf8_decode($taller['nombre']),'BLR',0,'L',0);
      $this->pdf->Cell(25,5,$taller['numAsistentes']==0?'':$taller['numAsistentes'].'  ','BLR',0,'R',0);
      $this->pdf->Cell(25,5,$taller['total']==0?'':$taller['total'].'  ','BLR',0,'R',0);
      //Se agrega un salto de linea
      $this->pdf->Ln(5);
      $num++;
      $numAsistentes+=$taller['numAsistentes'];
      $total+=$taller['total'];
    }
    $this->pdf->Ln(1);
    $this->pdf->Cell(15,5,'','TBLR',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
      $this->pdf->Cell(75,5,$num,'TBLR',0,'L',0);
      $this->pdf->Cell(25,5,$numAsistentes.'  ','TBLR',0,'R',0);
      $this->pdf->Cell(25,5,$total.'   ','TBLR',0,'R',0);
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
    $this->pdf->Output("Talleres ".$this->talleres_model->getNombreCurso($curso).".pdf", 'D');
  }
  
  public function listasUsuariosTelefonosEmail(){
    $this->load->helper('form');
    $this->load->model('talleres_model');
   // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
    $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
   // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
    $datos['autor'] = 'Miguel Angel Bañolas';
    $datos['titulo']=$_SESSION['tituloCasal'];
    $datos['tipoTaller']='tots';
    $datos['periodo']=$this->talleres_model->getTextoPeriodos($this->talleres_model->getUltimoPeriodo());
    // $datos['textoPeriodo']=$this->talleres_model->getTextoPeriodo($datos['periodo']);
    $datos['textoPeriodo']="Curs complet";
    $this->load->view('templates/header',$datos);
    //$datos['activeMenu']='Talleres';
    //$datos['activeSubmenu']='Resumen socios inscritos taller';
    $this->load->view('templates/barraNavegacion',$datos);
    $this->load->view('listasUsuariosExcelTelefonosEmail',$datos);
    $datos['pie']='';
    $this->load->view('templates/footer',$datos);
    $this->load->view('myModal');
  }
  
  public function listasTalleresExcel(){
        $this->load->helper('form');
        $this->load->model('talleres_model');
       // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
       // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $datos['tipoTaller']='tots';
        $datos['periodo']=$this->talleres_model->getTextoPeriodos($this->talleres_model->getUltimoPeriodo());
        $datos['textoPeriodo']=$this->talleres_model->getTextoPeriodo($datos['periodo']);
        $this->load->view('templates/header',$datos);
        //$datos['activeMenu']='Talleres';
        //$datos['activeSubmenu']='Resumen socios inscritos taller';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('listasTalleresExcel',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
        $this->load->view('myModal');
  }
  
  public function pdfSociosInscritos()
  {
    // Se carga el modelo alumno
    $this->load->model('talleres_model');
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
    $curso=$_POST['curso'];
    // Se obtienen los alumnos de la base de datos
    $socios = $this->talleres_model->getResumenSociosTalleres($curso);
    //var_dump($socios);
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    
    $this->pdf->setSubtitulo('Inscrits en Tallers curs: '.$this->talleres_model->getNombreCurso($curso));
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle("Resumen socios inscritos curso ".$this->talleres_model->getNombreCurso($curso));
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
    
    //encabezado
    
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(40,7,'','TB',0,'C','1');
    //$this->pdf->Cell(25,7,'','TB',0,'L','1');
    //$this->pdf->Cell(25,7,'','TBR',0,'C','1');
    $this->pdf->Ln(0);
    // La variable $x se utiliza para mostrar un número consecutivo
    $x = 0;
    $num=0;
    $numSocios=0;
    $total=0;
    foreach ($socios as $k=>$socio) {
        if($x % 46 ==0 ){
            
            $this->pdf->Cell(15,7,utf8_decode('NÚM'),'TBL',0,'C','1');
            $this->pdf->Cell(75,7,utf8_decode('USUARI / USUÀRIA'),'TBR',0,'L','1');
            $this->pdf->Cell(30,7,'NOMBRE TALLERS','TBR',0,'C','1');
            $this->pdf->Cell(25,7,iconv('UTF-8', 'windows-1252', 'PAGAT (€)'),'TBR',0,'C',1);
            $this->pdf->Ln(7);
        }
      $this->pdf->Cell(15,5,$socio['id_socio'],'BL',0,'C',0);
      //$this->pdf->Cell(15,5,$x,'BL',0,'C',0);
      $x++;
       // Se imprimen los datos de cada alumno
      $this->pdf->Cell(75,5,utf8_decode($socio['apellidos'].', '.$socio['nombre_socio']),'BLR',0,'L',0);
      $this->pdf->Cell(30,5,$socio['num_talleres']==0?'':$socio['num_talleres'].'  ','BLR',0,'R',0);
      $this->pdf->Cell(25,5,$socio['totalPagado']==0?'0  ':$socio['totalPagado'].'  ','BLR',0,'R',0);
      //Se agrega un salto de linea
      $this->pdf->Ln(5);
      $num+=$socio['num_talleres'];
      $numSocios++;
      $total+=$socio['totalPagado'];
    }
    $this->pdf->Ln(1);
    $this->pdf->Cell(15,5,'','TBLR',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
      $this->pdf->Cell(75,5,$numSocios,'TBLR',0,'L',0);
      $this->pdf->Cell(30,5,$num.'  ','TBLR',0,'R',0);
      $this->pdf->Cell(25,5,$total.'   ','TBLR',0,'R',0);
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
    $this->pdf->Output("Socios Inscritos ".$this->talleres_model->getNombreCurso($curso).".pdf", 'D');
    echo json_encode('hola');
  }
  
  
  
   public function pdfLista()
  {
    // Se carga el modelo alumno
    $this->load->model('talleres_model');
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
    $curso=$_POST['curso'];
    // Se obtienen los alumnos de la base de datos
    $talleres = $this->talleres_model->getResumenTalleres($curso);
    //var_dump($talleres);
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    
    $this->pdf->setSubtitulo(iconv('UTF-8', 'CP1252','Tallers curs: '.$this->talleres_model->getNombreCurso($curso)));
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle(utf8_decode("Resumen talleres ".$this->talleres_model->getNombreCurso($curso)));
    $this->pdf->SetLeftMargin(15);
    $this->pdf->SetRightMargin(15);
    $this->pdf->SetFillColor(200,200,200);
 
    // Se define el formato de fuente: Arial, negritas, tamaño 9
    $this->pdf->SetFont('Arial', 'B', 12);
    /*
     * TITULOS DE COLUMNAS
     *
     * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
     */
 /*
    $this->pdf->Cell(15,7,'NUM','TBL',0,'C','1');
    $this->pdf->Cell(75,7,'TALLER','TBR',0,'L','1');
    $this->pdf->Cell(75,7,'DIA 1','TBR',0,'L','1');
    $this->pdf->Cell(75,7,'DIA 2','TBR',0,'L','1');
  * 
  */
    
    $this->pdf->Ln(0);
    // La variable $x se utiliza para mostrar un número consecutivo
    $x = 1;
    
    $agrupados=false;
     $this->pdf->Cell(75,5,'','',0,'L',0);
     $this->pdf->Cell(25,5,'','',0,'L',0);
     $this->pdf->Cell(30,5,'','',0,'L',0);
     $this->pdf->Cell(25,5,'Normal','',0,'R',0);
     $this->pdf->Cell(25,5,'Rosa','',0,'R',0);
            $this->pdf->Ln(8);
    
    
    foreach ($talleres as $k=>$taller) {
        //$this->pdf->Cell(15,5,$taller['id'],'BL',0,'C',0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

      // Se imprimen los datos de cada alumno
        $dia1="";
        $dia2="";
        $horario1="";
         $horario2="";
       if($taller['dia1']) $dia1=$taller['dia1'];
       if($taller['dia2']) $dia2=$taller['dia2'];
       if($taller['dia1']) $horario1=substr($taller['inicio1'],0,5).'-'.substr($taller['final1'],0,5);
       if($taller['dia2']) $horario2=substr($taller['inicio2'],0,5).'-'.substr($taller['final2'],0,5);
       
       if($taller['agrupado']>0) {$taller['nombre'].='*'; $agrupados=true;}
       //$taller['nombre']=iconv('UTF-8', 'CP1252',$taller['nombre']);
       //if(strlen($taller['nombre']>25)) $taller['nombre']=substr($taller['nombre'],0,25)."...";
      switch($taller['id_periodo']){
         case 7: $periodo=""; break;
         case 1: $periodo="(T3)"; break;
         case 2: $periodo="(T2)"; break;
         case 3: $periodo="(T2 T3)"; break;
         case 4: $periodo="(T1)"; break;
         case 5: $periodo="(T1 T3)"; break;
         case 6: $periodo="(T1 T2)"; break;
         default: $periodo="(--)";
      }
      $this->pdf->Cell(75,5,iconv('UTF-8', 'CP1252',$taller['nombre'].' '.$periodo),'',0,'L',0);
      $this->pdf->Cell(25,5,utf8_decode($dia1),'',0,'L',0);
      $this->pdf->Cell(30,5,utf8_decode($horario1),'',0,'L',0);
      $precioTrimestre=utf8_decode("Gratuït");
      $precioCurso=utf8_decode("Gratuït");
      $precioRosaTrimestre=utf8_decode("Gratuït");
      $precioRosaCurso=utf8_decode("Gratuït");
      $periodo=$taller['id_periodo'];
      mensaje($periodo);
      if($taller['precioTrimestre']>0) 
           $precioTrimestre=iconv('UTF-8', 'CP1252',$taller['precioTrimestre'].' €');
          //$precioTrimestre=utf8_decode($taller['precioTrimestre']).iconv('UTF-8', 'CP1252', ' €/Trim');
      if($taller['precioRosaTrimestre']>0) 
          $precioRosaTrimestre=utf8_decode($taller['precioRosaTrimestre']).iconv('UTF-8', 'CP1252', ' €');
      $this->pdf->Cell(25,5,$precioTrimestre,'',0,'R',0);
      $this->pdf->Cell(25,5,$precioRosaTrimestre,'',0,'R',0);
      //Se agrega un salto de linea
      $this->pdf->Ln(5);
      if($dia2){
          $this->pdf->Cell(75,5,'','',0,'L',0);
          $this->pdf->Cell(25,5,utf8_decode($dia2),'',0,'L',0);
        $this->pdf->Cell(30,5,utf8_decode($horario2),'',0,'L',0);
        $this->pdf->Ln(5);
      }
      $this->pdf->Ln(3);
      
    }
    
    $this->pdf->Ln(5);
    if($agrupados){
        $this->pdf->Cell(150,5,utf8_decode('(*) Talleres agrupados. Consultar precios asistencia a 2 o más talleres.'),'',0,'L',0);
        
    }
    
    
     
    
    /*
     * Se manda el pdf al navegador
     *
     * $this->pdf->Output(nombredelarchivo, destino);
     *
     * I = Muestra el pdf en el navegador
     * D = Envia el pdf para descarga
     *
     */
    $this->pdf->Output("Talleres ".$this->talleres_model->getNombreCurso($curso).".pdf", 'D');
  }
  
  
  public function pdfSocio($socio)
   {
    // Se carga el modelo alumno
    $this->load->model('socios_model');
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
    //$curso=2;//$_POST['curso'];
    // Se obtienen los alumnos de la base de datos
    $resultSocio = $this->socios_model->getSocio($socio);
    //var_dump($talleres);
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    
    $this->pdf->setSubtitulo('Ficha socio');
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle("Datos socio=");
    $this->pdf->SetLeftMargin(15);
    $this->pdf->SetRightMargin(15);
    $this->pdf->SetFillColor(200,200,200);
 
    // Se define el formato de fuente: Arial, negritas, tamaño 9
    $this->pdf->SetFont('Arial', '', 14);
    
    
    //$this->pdf->Ln(2);
    // La variable $x se utiliza para mostrar un número consecutivo
    $h = 8;
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Número socio'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($socio),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Nombre'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->nombre),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Apellidos'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->apellidos),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Dirección'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->direccion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Códico Postal'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->codigo_postal),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Poblacion'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->poblacion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Provincia'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->provincia),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('DNI/NIF/PAS'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->dni),'',1,'L',0);
   
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Teléfono 1'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $telefono=trim($resultSocio->telefono_1);
    $telefono=str_replace('-','',$telefono);
    $telefono=str_replace('.','',$telefono);
    $telefono=str_replace(',','',$telefono);
    if(strlen($telefono)==9) $telefono=substr($telefono,0,3).' '.substr($telefono,3,3).' '.substr($telefono,6,3);
    $this->pdf->Cell(75,$h,utf8_decode($telefono),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Teléfono 2'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $telefono=trim($resultSocio->telefono_2);
    $telefono=str_replace('-','',$telefono);
    $telefono=str_replace('.','',$telefono);
    $telefono=str_replace(',','',$telefono);
    if(strlen($telefono)==9) $telefono=substr($telefono,0,3).' '.substr($telefono,3,3).' '.substr($telefono,6,3);
    $this->pdf->Cell(75,$h,utf8_decode($telefono),'',1,'L',0);
    
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha nacimiento'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_nacimiento=$resultSocio->fecha_nacimiento;
    $fecha_nacimiento=substr($fecha_nacimiento,8,2).'/'.substr($fecha_nacimiento,5,2).'/'.substr($fecha_nacimiento,0,4);
    if($fecha_nacimiento=='//') $fecha_nacimiento='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_nacimiento),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha alta'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_alta=$resultSocio->fecha_alta;
    $fecha_alta=substr($fecha_alta,8,2).'/'.substr($fecha_alta,5,2).'/'.substr($fecha_alta,0,4);
    if($fecha_alta=='//') $fecha_alta='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_alta),'',1,'L',0);
    
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha modificación'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_modificacion=$resultSocio->fecha_modificacion;
    $fecha_modificacion=substr($fecha_modificacion,8,2).'/'.substr($fecha_modificacion,5,2).'/'.substr($fecha_modificacion,0,4);
    if($fecha_modificacion=='//') $fecha_modificacion='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_modificacion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha baja'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_baja=$resultSocio->fecha_baja;
    $fecha_baja=substr($fecha_baja,8,2).'/'.substr($fecha_baja,5,2).'/'.substr($fecha_baja,0,4);
    if($fecha_baja=='//' || $fecha_baja=='00/00/0000') $fecha_baja='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_baja),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Comentarios'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->comentarios),'',1,'L',0);
   
    
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
    $this->pdf->Output("Socio ".$socio.".pdf", 'D');
  }
  
  public function pdfSocioAniversario($socio)
  {
    // Se carga el modelo alumno
    $this->load->model('socios_model');
    // Se carga la libreria fpdf
    $this->load->library('pdf');
 
    //$curso=2;//$_POST['curso'];
    // Se obtienen los alumnos de la base de datos
    $resultSocio = $this->socios_model->getSocio($socio);
    //var_dump($talleres);
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    
    $this->pdf->setSubtitulo('Ficha socio');
    // Agregamos una página
    $this->pdf->AddPage();
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();
 
    /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
    $this->pdf->SetTitle("Datos socio ");
    $this->pdf->SetLeftMargin(15);
    $this->pdf->SetRightMargin(15);
    $this->pdf->SetFillColor(200,200,200);
 
    // Se define el formato de fuente: Arial, negritas, tamaño 9
    $this->pdf->SetFont('Arial', '', 14);
    
    
    //$this->pdf->Ln(2);
    // La variable $x se utiliza para mostrar un número consecutivo
    $h = 8;
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Número socio'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($socio),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Nombre'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->nombre),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Apellidos'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->apellidos),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Dirección'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->direccion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Códico Postal'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->codigo_postal),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Poblacion'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->poblacion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Provincia'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->provincia),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('DNI/NIF/PAS'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->dni),'',1,'L',0);
   
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Teléfono 1'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $telefono=trim($resultSocio->telefono_1);
    $telefono=str_replace('-','',$telefono);
    $telefono=str_replace('.','',$telefono);
    $telefono=str_replace(',','',$telefono);
    if(strlen($telefono)==9) $telefono=substr($telefono,0,3).' '.substr($telefono,3,3).' '.substr($telefono,6,3);
    $this->pdf->Cell(75,$h,utf8_decode($telefono),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Teléfono 2'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $telefono=trim($resultSocio->telefono_2);
    $telefono=str_replace('-','',$telefono);
    $telefono=str_replace('.','',$telefono);
    $telefono=str_replace(',','',$telefono);
    if(strlen($telefono)==9) $telefono=substr($telefono,0,3).' '.substr($telefono,3,3).' '.substr($telefono,6,3);
    $this->pdf->Cell(75,$h,utf8_decode($telefono),'',1,'L',0);
    
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha nacimiento'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_nacimiento=$resultSocio->fecha_nacimiento;
    $fecha_nacimiento=substr($fecha_nacimiento,8,2).'/'.substr($fecha_nacimiento,5,2).'/'.substr($fecha_nacimiento,0,4);
    if($fecha_nacimiento=='//') $fecha_nacimiento='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_nacimiento),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha alta'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_alta=$resultSocio->fecha_alta;
    $fecha_alta=substr($fecha_alta,8,2).'/'.substr($fecha_alta,5,2).'/'.substr($fecha_alta,0,4);
    if($fecha_alta=='//') $fecha_alta='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_alta),'',1,'L',0);
    
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha modificación'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_modificacion=$resultSocio->fecha_modificacion;
    $fecha_modificacion=substr($fecha_modificacion,8,2).'/'.substr($fecha_modificacion,5,2).'/'.substr($fecha_modificacion,0,4);
    if($fecha_modificacion=='//') $fecha_modificacion='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_modificacion),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Fecha baja'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $fecha_baja=$resultSocio->fecha_baja;
    $fecha_baja=substr($fecha_baja,8,2).'/'.substr($fecha_baja,5,2).'/'.substr($fecha_baja,0,4);
    if($fecha_baja=='//' || $fecha_baja=='00/00/0000') $fecha_baja='';
    $this->pdf->Cell(75,$h,utf8_decode($fecha_baja),'',1,'L',0);
    
    $this->pdf->SetFont('Arial', '', 14);
    $this->pdf->Cell(75,$h,utf8_decode('Comentarios'),'',0,'L',0);
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(75,$h,utf8_decode($resultSocio->comentarios),'',1,'L',0);
   
    
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
    $this->pdf->Output("Socio ".$socio.".pdf", 'D');
  }
}