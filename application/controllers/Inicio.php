<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class Inicio extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');	
    }

    
    public function index() {
        $_SESSION['tituloCasal']= getTituloCasal();
        $this->utilidades->registro();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='Inicio';
        $datos['activeSubmenu']='';
        $this->load->model('socios_model');
        $datos['cumplen90']=$this->socios_model->getCumplen90();

        
        //Provisional efectuar sólo 1 vez
        $this->load->model('cobros_model');
        $this->cobros_model->marcarDevoluciones();
        
        $datos['cumplen90proximos30dias']=$this->socios_model->getCumplen90proximos30dias();
        $datos['cumpleanos']=$this->socios_model->getCumpleanos();
        $ahora=date('d/m/Y H:i:s');

        // mensaje('categoría '.$_SESSION['categoria']);
    //    if($_SESSION['categoria']!=10)
    //          $this->sendEmail('mbanolas@gmail.com','Entrada aplicación ',getTituloCasal().'<br><br>Ha iniciado la aplicación<br>Usuario: <strong>'.$this->session->nombre.'</strong><br>Fecha: <strong>'.$ahora.'</strong>' );

        //$datos['psw']=md5('gentgran');
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('inicio',$datos);
        $this->load->view('myModal');
        $this->load->view('myModal2');
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
    }

    function sizePantallaEmail(){
        $ancho=$_POST['ancho'];
        $alto=$_POST['alto'];
        $ahora=date('d/m/Y H:i:s');
        // if(true){
        // if($this->session->categoria!=1){
        //     enviarEmail($this->email, 'Entrada aplicación',host().' - Pernil181','Sesión iniciada por: <br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora."<br>Ancho pantalla: $ancho <br>Alto: $alto",3);
        if($this->session->categoria!=10)
            $this->sendEmail('mbanolas@gmail.com','Entrada aplicación ',getTituloCasal().'<br><br>Ha iniciado la aplicación<br>Usuario: <strong>'.$this->session->nombre.'</strong><br>Fecha: <strong>'.$ahora.'</strong><br>Ancho pantalla: '.$ancho.' <br>Alto: '.$alto );

        echo  json_encode(0);
    }
    
    function sendEmail($to='mbanolas@gmail.com',$subject="",$message="vacío"){
        //$host = $_SERVER['HTTP_HOST'];
    $this->load->library('email');
    $this->email->from(getCorreoServidorCasal(), getTituloCasal());
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    $this->email->send();
    
}
    
    public function seleccionarMenu() {
        
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='';
        $datos['activeSubmenu']='';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('seleccionarMenu');
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
    }
    
    
    function fechaMovimientoWeb(){
     $fecha=$_POST['fecha'];
     $nombre=$_POST['nombre'];
     
     $fecha=$this->utilidades->fechaMovimientoWeb($fecha,$nombre);
     echo  json_encode($_POST);
     
 }
   
}
