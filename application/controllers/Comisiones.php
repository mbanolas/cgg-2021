<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class Comisiones extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('socios_model');
        $this->load->model('comisiones_model');
        
    }
    
    
    function miembrosComisiones(){
       
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->model('comisiones_model');
        $datos['comisiones']=$this->comisiones_model->getDatosComisiones();
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('miembrosComisiones',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos); 
        $this->load->view('myModal'); 
       
    }
    
    function envioEmails(){
       
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('envioEmails',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos); 
        $this->load->view('myModal'); 
     
    }
    
    function getDatosComision(){
        $datos=$this->comisiones_model->getDatosComision($_POST['idComision']);
        echo json_encode($datos);
    }
    
    function eliminarMiembro($id_comision,$id_socio){
        $datos=$this->comisiones_model->eliminarMiembro($id_comision,$id_socio);
        echo json_encode($datos);
    }
    function ponerMiembro($id_comision,$id_socio){
        $datos=$this->comisiones_model->ponerMiembro($id_comision,$id_socio);
        echo json_encode($datos);
    }
}