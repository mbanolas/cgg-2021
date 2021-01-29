<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Parc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');	
    }

    public function index__() {
        
        
        $this->session->sess_destroy();
        $_SESSION['tituloCasal']= getTituloCasal();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal']; //getTituloCasal();
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/cabecera');
        $this->load->view('parcSandaruInicio', array('error' => ' '));
        $this->load->view('myModal');
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
    }
    
    public function index() {
        $this->session->sess_destroy();
        $_SESSION['tituloCasal']= getTituloCasal();
        $dato['autor'] = 'Miguel Angel Bañolas';
        //$dato['host']=host();
        //$dato['tituloAplicacion']=tituloAplicacion();
        $dato['titulo']=getTituloCasal();

        $this->load->view('templates/header', $dato);
        $this->load->view('templates/cabecera', $dato);
        $this->load->view('parc', array('error' => ' '));
        $this->load->view('templates/footer', $dato);
    }
    

   public function verificarEntrada(){
       $resultado=$this->utilidades->verificarEntrada();
       echo  json_encode($resultado);
   } 
   
}
