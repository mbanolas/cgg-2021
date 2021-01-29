<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class Cobros extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('socios_model');
        $this->load->model('talleres_model');
        $this->load->model('cobros_model');
        
    }
    
    public function informeCobros(){
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('informeCobros',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos); 
        $this->load->view('myModal'); 
    }
    
    public function informeAjuntament(){
        $this->load->helper('form');
        //$this->load->model('talleres_model');
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('informeAjuntament',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos); 
        $this->load->view('myModal'); 
    }
    
    
    
    function getInformeCobros(){
        extract($_POST);
        $tablaInforme=$this->cobros_model->getTablaInformeCobros($desde,$hasta);
        
        echo json_encode($tablaInforme);
    }
    
    function getInformeAjuntament(){
        extract($_POST);
        $tablaInforme=$this->cobros_model->getTablaInformeAjuntament($desde,$hasta);
        
        echo json_encode($tablaInforme);
    }
    
    
    
    function pdfInformeCobros(){
        extract($_POST);
        $this->cobros_model->getPdfInformeCobros($desde,$hasta);
    }
    
    function sendEmail($to='mbanolas@gmail.com',$subject="",$message="vacío"){
            //$host = $_SERVER['HTTP_HOST'];
        $this->load->library('email');
        $this->email->from('info@gestiocggsantmarti.com', getTituloCasal());
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
        
    }
    
    
    function excelInformeAyuntamiento(){
        extract($_POST);
        $datos=$this->cobros_model->getExcelInformeAyuntamiento($desde,$hasta);  
        
        $this->db->insert('casal_web_registros', array('registro'=>10, 'usuario'=>$this->session->nombre, 'fecha'=>date('Y-m-d')));
        if($this->session->categoria!=10)
            $this->sendEmail('mbanolas@gmail.com','Informe para Ayuntamiento','Generado informe '.getTituloCasal().'<br><br>'.$datos['datosCabecera']['periodo']);
        
        $this->load->library('excel');

        $this->load->library('drawing');
        
        $hoja = 0;
        
        $this->load->view('excelInformeAyuntamiento',$datos);
    }
    
     public function cobrosTalleresCurso(){
        $this->load->helper('form');
        $this->load->model('talleres_model');
       // $datos['talleres'] = $this->talleres_model->getResumenTalleres(2);
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
       // $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['tipoTaller']="";
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo']=$_SESSION['tituloCasal'];
        $this->load->view('templates/header',$datos);
        $datos['activeMenu']='Cobros';
        $datos['activeSubmenu']='Cobros por talleres curso';
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('resumenTalleres',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
   } 
    
}