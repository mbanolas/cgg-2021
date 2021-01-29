<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
//if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class CronJobs extends CI_Controller {
   
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('socios_model');
        $this->load->model('talleres_model');
        $this->load->model('cobros_model');
        
    }
    
    function informeCobros(){
   
    $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('informeCobrosCronJobs',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos); 
        $this->load->view('myModal'); 
    }
}