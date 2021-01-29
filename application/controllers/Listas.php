<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class Listas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        // $this->load->model('utilidades');
        // $this->load->model('socios_model');
        // $this->load->model('talleres_model');
        // $this->load->model('cobros_model');
        
    }

    public function bajarExcel(){
        $result=$this->db->query("SELECT * FROM casal_temporal LIMIT 10")->result_array();
        // $datosSocios['socios']=array();
        // foreach($result as $k=>$v){
        //     $datos['socios'][]=$v;
        // }
        // $this->load->view('listadoExcelA', $datos);
        echo json_encode(true);
    }

}