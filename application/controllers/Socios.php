<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");

class Socios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('socios_model');
        $this->load->model('talleres_model');
    }

    function getTalleresOptions($curso=0){
        $curso=$_POST['curso'];
        $optionsTalleresCurso=$this->talleres_model->getTalleresOptions($curso); 
        $salida=array();
        foreach($optionsTalleresCurso as $k=>$v){
            $salida[]=array('taller'=>$k,'nombre'=>$v);
        }
       
        echo json_encode($salida);
    }


    public function whatsapp($reload='',$numSocio='',$dni='') {
        //log_message('INFO',"seleccionar($reload='')");
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $this->load->model('socios_model');
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
        $datos['periodo']=$this->talleres_model->getUltimoPeriodo();
        $datos['ultimoMensaje']=$this->socios_model->getUltimoMensaje();
        
        $this->load->model('socios_model');
        $datos['optionsSocios']=$this->socios_model->getSociosOptions();
        $cursoActual=$this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
        $datos['optionsTalleresCursoActual']=$this->talleres_model->getTalleresOptions($cursoActual);

        // $datos['optionsTalleresCursoAnterior']=$this->talleres_model->getTalleresOptions($cursoActual--);
        $datos['reload']=$reload;
        $datos['dni']=$dni;
        $datos['numSocio']=$numSocio;
        $datos['nombreSocio']="";
        if ($numSocio){
            $socio=$this->socios_model->getSocio($numSocio);
            $datos['nombreSocio']=$socio->nombre.' '.$socio->apellidos.' ('.$socio->dni.')';
            $datos['dni']=$socio->dni;
        }
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        // $datos['activeMenu']='Talleres';
        // $datos['activeSubmenu']='Inscripción a talleres';
        //$datos['curso']=
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('whatsapp',$datos);
        $this->load->view('myModal',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
    }
    function getSocioAjax() {
        $socio = $_POST['socio'];
        $result = $this->socios_model->getSocio($socio);
        echo json_encode(array('id' => $result->id, 'nombre' => $result->nombre, 'apellidos' => $result->apellidos));
        //return array('sql'=>$sql,'id'=>$result->id,'nombre'=>$result->nombre,'nombre'=>$result->apellidos);
    }
    function getMovilSocio() {
        $socio = $_POST['socio'];
        $result = $this->socios_model->getSocio($socio);
        $telefono_1=str_replace(" ","",trim($result->telefono_1));
        $telefono_2=str_replace(" ","",trim($result->telefono_2));
        $movil="No té mòbil";
        if(substr($telefono_1,0,1)!=9) 
            $movil=$telefono_1;
        if(substr($telefono_2,0,1)!=9)     
            $movil=$telefono_2;
        if(!$movil)    $movil="No té mòbil";            
        echo json_encode(array('id' => $result->id, 'movil'=>$movil,'nombre' => $result->nombre, 'apellidos' => $result->apellidos));
        //return array('sql'=>$sql,'id'=>$result->id,'nombre'=>$result->nombre,'nombre'=>$result->apellidos);
    }

    function getMovilesInscritosTaller() {
        $taller = $_POST['taller'];
        $result = $this->socios_model->getMovilesInscritosTaller($taller);
        $socios=array();
        foreach($result as $k=>$v){
        $telefono_1=str_replace(" ","",trim($v->telefono_1));
        $telefono_2=str_replace(" ","",trim($v->telefono_2));
        $movil="No té mòbil";
        if(substr($telefono_1,0,1)!=9) 
            $movil=$telefono_1;
        if(substr($telefono_2,0,1)!=9)     
            $movil=$telefono_2;
        if(!$movil)    $movil="No té mòbil"; 
            $socios[]=array('num_socio'=>$v->num_socio,'nombre'=>$v->nombre,'apellidos'=>$v->apellidos,'movil'=>$movil);
        }           
        echo json_encode($socios);
    }

    function grabarWhatsApp(){
        // $sql="INSERT INTO casal_whatsapp SET
        //                 socio='".$_POST['socio']."',
        //                 mensaje='".$_POST['mensaje']."',
        //                 movil='".$_POST['movil']."',
        //                 fecha='".date('Y-m-d h:i')."'";
        $sql='INSERT INTO casal_whatsapp SET
                        socio="'.$_POST['socio'].'",
                        mensaje="'.$_POST['mensaje'].'",
                        movil="'.$_POST['movil'].'",
                        fecha="'.date('Y-m-d h:i').'"';
        mensaje($sql);
        $resultado=$this->db->query($sql);

        echo json_encode($resultado);
    }
    
    // envio email propiamente dicho (pendiente)
    function enviarEmail(){

        $this->load->library('email');

        $titulo = $_POST['titulo'];
        $mensaje = $_POST['mensaje'];
        $direccionesPara=explode(',', $_POST['direccionesPara']);
        $adjunto1 = base_url() . "uploads/" . $_POST['adjunto_1'];
        $adjunto2 = base_url() . "uploads/" . $_POST['adjunto_2'];
        $adjunto3 = base_url() . "uploads/" . $_POST['adjunto_3'];

        $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
        $this->email->subject($titulo);
        $this->email->message($mensaje);
        $this->email->attach($adjunto1);
        $this->email->attach($adjunto2);
        $this->email->attach($adjunto3);

        foreach ($direccionesPara as $k => $v) {
            $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
            $this->email->subject($titulo);
            $this->email->message($mensaje);
            $this->email->bcc($v);
            // $this->email->send();
        }
    }
    
    function getEmailsTodos(){
        // mensaje('getEmailsTodos');
        // $this->db->query("UPDATE casal_socios_nuevo SET recibir_emails='Sí' WHERE email<>''");

        $result=$this->db->query("SELECT email,nombre,apellidos,sexo FROM casal_socios_nuevo WHERE email!='' and email not like '%yahoo%' and recibir_emails='Sí' and email_no_valido='No'" )->result();
        $resultYahoo=$this->db->query("SELECT email,nombre,apellidos,sexo FROM casal_socios_nuevo WHERE email!='' and email like '%yahoo%' and recibir_emails='Sí' " )->result();
        $emails=array();
        $nombres=array();
        $apellidos=array();
        $yahoo=array();
        foreach($resultYahoo as $k=>$v){
            $yahoo[]=$v->email;
        }
        foreach($result as $k=>$v){
            $emails[]=$v->email;
            $nombres[]=$v->nombre;
            $apellidos[]=$v->apellidos;
        }

        echo json_encode(array(
            'direccionesPara'=>$emails,
            'emails'=>$emails,
            'nombres'=>$nombres,
            'apellidos'=>$apellidos,
            'yahoo'=>implode(",",$yahoo)
            )
        );
    }
    function getEmailsTaller(){
        $id_taller=$_POST['taller'];
        $periodo=$_POST['periodo'];
        mensaje($periodo);
        $sql="SELECT email,s.nombre as nombre,apellidos,sexo FROM casal_asistentes a 
        LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
        WHERE id_taller='$id_taller' and email!='' and email not like '%yahoo%' and recibir_emails='Sí' and email_no_valido='No' and (a.trimestres & $periodo > 0)";
        mensaje('getEmailsTaller '.$sql);
        
        $sqlYahoo="SELECT email,s.nombre as nombre,apellidos,sexo FROM casal_asistentes a 
        LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
        WHERE id_taller='$id_taller' and email!='' and email like '%yahoo%' and recibir_emails='Sí' and email_no_valido='No' and (a.trimestres & $periodo > 0)";
        $resultYahoo=$this->db->query($sqlYahoo)->result();

        $result=$this->db->query($sql)->result();
         $emails=array();
         $nombres=array();
         $apellidos=array();
         
        foreach($result as $k=>$v){
            $emails[]=$v->email;
            $nombres[]=$v->nombre;
            $apellidos[]=$v->apellidos;
        }
        $yahoo=array();
        foreach($resultYahoo as $k=>$v){
            $yahoo[]=$v->email;
        }
        $sql="SELECT t.nombre as nombre_taller, c.nombre as nombre_curso FROM casal_talleres t
                LEFT JOIN casal_cursos c ON c.id=t.id_curso
                WHERE t.id='$id_taller'";
        $row=$this->db->query($sql)->row();
        $nombre=$row->nombre_taller." (".$row->nombre_curso.")";
        $this->db->query("DELETE FROM casal_provisional WHERE 1");
        $this->db->query("INSERT INTO casal_provisional SET datos='".$nombre."'");
        echo json_encode(array(
            'direccionesPara'=>$emails,
            'emails'=>$emails,
            'nombres'=>$nombres,
            'apellidos'=>$apellidos,
            'yahoo'=>implode(",",$yahoo)
            )
        );
    }

    function getEmailsTodosTalleres($tipo_taller=0){
        $id_curso=$_POST['curso'];
        $tipo="";
        if($tipo_taller==1) $tipo=" and tipo_taller='Voluntari'";
        if($tipo_taller==2) $tipo=" and tipo_taller='Professional'";
        // $id_curso=$this->db->query("SELECT id FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
        
        $sql="SELECT email,s.nombre as nombre,apellidos,sexo FROM casal_asistentes a 
        LEFT JOIN casal_talleres t ON t.id=a.id_taller
        LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
        WHERE t.id_curso='$id_curso'and email!='' and email not like '%yahoo%' and recibir_emails='Sí' and email_no_valido='No' $tipo ";
        mensaje($sql);

        $sqlYahoo="SELECT email,s.nombre as nombre,apellidos,sexo FROM casal_asistentes a 
        LEFT JOIN casal_talleres t ON t.id=a.id_taller
        LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
        WHERE t.id_curso='$id_curso'and email!='' and email like '%yahoo%' and recibir_emails='Sí' and email_no_valido='No' $tipo ";
        
        $result=$this->db->query($sql)->result();
        $resultYahoo=$this->db->query($sqlYahoo)->result();
         $emails=array();
         $nombres=array();
         $apellidos=array();
        foreach($result as $k=>$v){
            $emails[]=$v->email;
            $nombres[]=$v->nombre;
            $apellidos[]=$v->apellidos;
        }
        $yahoo=array();
        foreach($resultYahoo as $k=>$v){
            $yahoo[]=$v->email;
        }
        echo json_encode(array(
            'direccionesPara'=>$emails,
            'emails'=>$emails,
            'nombres'=>$nombres,
            'apellidos'=>$apellidos,
            'yahoo'=>implode(",",$yahoo)
            )
        );
    }
    


    function copiar_email($id=0) {


        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'png';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {

        $sql = "SELECT * FROM casal_emails WHERE id='$id'";
        $row = $this->db->query($sql)->row();

        $datos['id'] = $id;
        $datos['titulo'] = $row->titulo;
        $datos['mensaje'] = $row->mensaje;
        $datos['adjunto_1'] = $row->adjunto_1;
        $datos['adjunto_2'] = $row->adjunto_2;
        $datos['adjunto_3'] = $row->adjunto_3;

        $direcciones = array();
        $nombres = array();
        $apellidos = array();

        $result=$this->db->query("SELECT nombre,apellidos,email FROM casal_socios_nuevo WHERE email!=''")->result();

        foreach($result as $k=>$v){
            $direcciones[]=$v->email;
            $nombres[]=$v->nombre;
            $apellidos[]=$v->apellidos;
        }

        

        $datos['direcciones'] = $direcciones;
        $datos['nombres'] = $nombres;
        $datos['apellidos'] = $apellidos;

        $id_curso=$this->db->query("SELECT id FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
        $datos['talleres']=$this->db->query("SELECT id,nombre, tipo_taller FROM casal_talleres WHERE id_curso='$id_curso' ORDER BY nombre ")->result();


        $this->load->view('templates/header.php');
        $this->load->view('templates/barraNavegacion');
        $this->load->view('paraEmails', $datos);
    }else{
        // $this->enviarEmail();
    }

    }

    function enviar_mail_asistetes_talleres($id) {
        $sql = "SELECT * FROM casal_emails WHERE id='$id'";
        $row = $this->db->query($sql)->row();

        $direcciones = array();


        $direcciones[] = 'mcarmenespanol@gmail.com';
        $direcciones[] = 'mbanolas@uoc.edu';
        $direcciones[] = 'mbanolas@gmail.com';

        $titulo = $row->titulo;
        $mensaje = $row->mensaje;
        $this->load->library('email');
        $adjunto1 = base_url() . "assets/uploads/files/" . $row->adjunto_1;
        $adjunto2 = base_url() . "assets/uploads/files/" . $row->adjunto_2;
        $adjunto3 = base_url() . "assets/uploads/files/" . $row->adjunto_3;

        $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
        $this->email->subject($titulo);
        $this->email->message($mensaje);
        $this->email->attach($adjunto1);
        $this->email->attach($adjunto2);
        $this->email->attach($adjunto3);

        foreach ($direcciones as $k => $v) {
            $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
            $this->email->subject($titulo);
            $this->email->message($mensaje);
            $this->email->to($v);
            $this->email->send();
        }
        $datos['direcciones'] = $direcciones;

        $this->load->view('templates/header');
        $this->load->view('templates/barraNavegacion');
        $this->load->view('templates/emailsEnviados', $datos);
    }
    
    function enviar_mail_comisiones($id) {
        $sql = "SELECT * FROM casal_emails WHERE id='$id'";
        $row = $this->db->query($sql)->row();

        $direcciones = array();


        $direcciones[] = 'mcarmenespanol@gmail.com';
        $direcciones[] = 'mbanolas@uoc.edu';
        $direcciones[] = 'mbanolas@gmail.com';

        $titulo = $row->titulo;
        $mensaje = $row->mensaje;
        $this->load->library('email');
        $adjunto1 = base_url() . "assets/uploads/files/" . $row->adjunto_1;
        $adjunto2 = base_url() . "assets/uploads/files/" . $row->adjunto_2;
        $adjunto3 = base_url() . "assets/uploads/files/" . $row->adjunto_3;

        $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
        $this->email->subject($titulo);
        $this->email->message($mensaje);
        $this->email->attach($adjunto1);
        $this->email->attach($adjunto2);
        $this->email->attach($adjunto3);

        foreach ($direcciones as $k => $v) {
            $this->email->from('info@gestiocggsantmarti.com', 'Casal Gent Gran');
            $this->email->subject($titulo);
            $this->email->message($mensaje);
            $this->email->to($v);
            $this->email->send();
        }
        $datos['direcciones'] = $direcciones;

        $this->load->view('templates/header');
        $this->load->view('templates/barraNavegacion');
        $this->load->view('templates/emailsEnviados', $datos);
    }

    function getSociosNuevosAjax() {

        $datos = $this->socios_model->getSociosNuevos();
        echo json_encode($datos);

        /*
          foreach($datos as $k=>$v){
          $result=$v;
          echo json_encode(array('id'=>$result->id,'nombre'=>$result->nombre,'apellidos'=>$result->apellidos));
          }
         */
    }

    public function carnets($reload = "") {
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['optionsSocios'] = $this->socios_model->getSociosOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['reload'] = $reload;
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Carnets';
        $datos['activeSubmenu'] = 'Imprimir carnets socis/sòcies';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('carnets', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
        $this->load->view('myModal');
    }

    public function sexosTalleres($reload = "") {
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['sexos'] = $this->socios_model->getSexosSociosTalleres();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['reload'] = $reload;
        $datos['titulo'] = $_SESSION['tituloCasal'];

        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Estadísticas';
        $datos['activeSubmenu'] = 'Estadísticas sexos talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('sexosTalleres', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
        $this->load->view('myModal');
    }

    public function sexosSocios($reload = "") {
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['sexos'] = $this->socios_model->getSexosSocios();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['reload'] = $reload;
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $datos['hoy'] = date('j/n/Y');
        mensaje($datos['hoy']);
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Estadísticas';
        $datos['activeSubmenu'] = 'Estadísticas sexos socios';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('sexosSocios', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
        $this->load->view('myModal');
    }

    public function pdfCarnets() {
        // var_dump($_POST);
        $socios = array();
        // echo '$_POST[carnets]';
        // var_dump($_POST['carnets']);
        if (isset($_POST['carnets'])) {
            foreach ($_POST['carnets'] as $k => $v) {
                $socios[] = $this->socios_model->getSocio($v);
            }
        }
        if (count($socios) == 0) {

            header('Location: ' . base_url() . 'index.php/' . 'socios/carnets/1');
        }
        //añadimos nuevos socios 
        /*
          $sql="SELECT s.num_socio, s.nombre, s.apellidos,s.direccion, s.dni,s.telefono_1, s.id, s.fecha_alta
          FROM casal_nuevos_socios_sin_carnet sc
          LEFT JOIN casal_socios_nuevo s ON s.num_socio=sc.num_socio";
          if($this->db->query($sql)->num_rows()>0){
          $result=$this->db->query($sql)->result();
          foreach($result as $k=>$v){
          //$socios[]=$v;
          }
          }
         */
        // var_dump($socios[0]);

        $this->load->helper('maba');
        $this->load->library('carnet');
        $this->pdf = new Carnet();

        $pdf = $this->pdf;

        $dia = date('d');
        $mes = utf8_decode(nombreMesCatalan(date('m')));
        $año = (date('Y'));
        $fecha = $dia . " del " . $mes . " de " . $año;


        $n = count($socios);  //numero carnets
        //echo 'numero carnets)'.$n.'<br>';
        $np = ceil($n / 10);     //numero paginas
        //echo 'numero páginas)'.$np.'<br>';
        //var_dump($socios);
        for ($p = 0; $p < $np; $p++) {
            $pdf->AddPage();
            for ($i = 0; $i < 10; $i++) {
                if (($p + $np * $i) < $n) {
                    //if (($p + $np * $i) % 2) {
                    if (( $i) % 2) {
                        $posicionH = 89;
                        $posicion = 55 * floor($i / 2);
                        //if(floor($i/2)>0) $posicion=$posicion+5;
                    } else {
                        $posicionH = 0;
                        $posicion = 55 * floor($i / 2);
                        // if(floor($i/2)>0) $posicion=$posicion+5;
                    }
                    // echo ($p + $np * $i).'<br>';
                    //  echo ($socios[0][0]->id);

                    $this->printCarnet($pdf, $posicion, $posicionH, $socios[$p + $np * $i]->nombre, $socios[$p + $np * $i]->apellidos, $socios[$p + $np * $i]->direccion, trim($socios[$p + $np * $i]->dni), $socios[$p + $np * $i]->telefono_1, $socios[$p + $np * $i]->id, $socios[$p + $np * $i]->fecha_alta
                    );
                }
            }
        }


        $res = $pdf->Output("Carnets.pdf", 'D');
        $this->db->query("DELETE FROM casal_nuevos_socios_sin_carnet WHERE 1 ");

        //  echo json_encode($res);
    }

    function printCarnet($pdf, $posicion, $posicionH, $nombre, $apellidos, $direccion, $dni, $telefono, $numCasal, $fecha) {

        $margenI = 10;
        $margenV = 3;

        $m = false; //para ver marco de las cell- depuración
        //if ($posicion==0) $pdf->AddPage();
        $margenSup = $margenV + 5;
        $pdf->SetFont('Arial', '', 10);



        //$pdf->Image('images/Ajuntament_de_Barcelona.gif',$margenI+2+$posicionH,$margenSup-3+$posicion,0,0,'GIF');
        $pdf->Image('images/Logo_Ayuntamiento.gif', $margenI + 2 + $posicionH, $margenSup - 3 + $posicion, 0, 0, 'GIF');

        $pdf->SetXY($margenI + 56 + $posicionH, $posicion + $margenSup + 2);  //$posicion=+$h*$r
        // $x=$margenI+56+$posicionH; $y=$posicion+$margenSup+2;
        // $pdf->Cell(0,0,$x.' '.$y,$m,1,'L');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(0, 0, utf8_decode('Casal Gent Grand'), $m, 1, 'L');
        $pdf->SetXY($margenI + 56 + $posicionH, $posicion + $margenSup + 5);  //$posicion=+$h*$r
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(0, 0, utf8_decode(getTituloCasalCorto()), $m, 1, 'L');
        $pdf->SetY($margenSup + 12 + $posicion);
        $h = 6;
        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->Cell(15, $h, utf8_decode("Nom "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, $h, utf8_decode($nombre), $m, 1, 'L');

        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, $h, utf8_decode("Cognoms "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, $h, utf8_decode($apellidos), $m, 1, 'L');

        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, $h, utf8_decode("Adreça "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        if (strlen($direccion) > 32) {
            $pdf->MultiCell(42, 3, utf8_decode($direccion), $m, 'L', false);
        } else {
            $pdf->Cell(15, $h, utf8_decode($direccion), $m, 1, 'L');
        }

        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, $h, utf8_decode("Telèfon "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, $h, utf8_decode($telefono), $m, 1, 'L');

        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, $h, utf8_decode("Núm. Soci "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $dni = strtoupper($dni);
        $tipoDocumento = "PAS";
        if (!is_numeric(substr($dni, -1)))
            $tipoDocumento = "DNI";
        if (in_array(substr($dni, 0, 1), array('X', 'Y', 'Z')))
            $tipoDocumento = "NIE";


        $pdf->Cell(15, $h, utf8_decode($numCasal . ' - ' . $tipoDocumento . ' ' . $dni), $m, 1, 'L');

        $fecha = substr($fecha, 8, 2) . ' / ' . substr($fecha, 5, 2) . ' / ' . substr($fecha, 0, 4);
        //$fecha=date("d / m / Y");
        $pdf->SetX($margenI + 3 + $posicionH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, $h, utf8_decode("Data "), $m, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, $h, utf8_decode($fecha), $m, 1, 'L');

        $pdf->SetXY($margenI + 60 + $posicionH, 11 + 3 + $posicion + $margenSup);
        $pdf->Cell(22, 32 - 3, utf8_decode(""), true, 1, 'R');

        $pdf->Line($margenI + $posicionH, $pdf->GetY() + $margenSup - 55, $margenI + $posicionH, $pdf->GetY() + $margenSup);
        $pdf->Line($margenI + $posicionH + 89, $pdf->GetY() + $margenSup - 55, $margenI + $posicionH + 89, $pdf->GetY() + $margenSup);

        $pdf->Line($margenI + $posicionH, $pdf->GetY() + $margenSup - 55, $margenI + $posicionH + 89, $pdf->GetY() + $margenSup - 55);

        $pdf->Line($margenI + $posicionH, $pdf->GetY() + $margenSup, $margenI + $posicionH + 89, $pdf->GetY() + $margenSup);

        //borramos num_soci de pendientes de imprimir carnet
        $this->socios_model->borrarSociosNuevos($numCasal);
    }

}
