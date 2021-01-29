<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GenerarListas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        ini_set('max_execution_time', 300);
    }

    public function index() {

        //log_message('INFO','------------------------------paso por generar listas');

        $this->load->library('excel');

        $this->load->helper('maba');
        $this->load->library('drawing');
        $this->load->model('talleres_model');
        $hoja = 0;
        
        
       
      
        extract($_POST);
        if(isset($listados)) mensaje('$listados '.$listados) ;
        if(isset($listados_sin)) mensaje('$listados_sin '.$listados_sin) ;

       
        $periodoInicialNum=$this->talleres_model->getNumPeriodo($periodoInicial);

        $titulo = "Curs " . $this->talleres_model->getNombreCurso($curso);
        $inicio = $inicio;
        $inicio = substr($inicio, 8, 2) . '-' . substr($inicio, 5, 2) . '-' . substr($inicio, 0, 4) . ' 12:00';

        $final = $finaliza;
        $final = substr($final, 8, 2) . '-' . substr($final, 5, 2) . '-' . substr($final, 0, 4) . ' 12:00';

        $fiesta1 = $festivo0;
        $fiesta1 = substr($fiesta1, 8, 2) . '-' . substr($fiesta1, 5, 2) . '-' . substr($fiesta1, 0, 4) . ' 12:00';

        $fiesta2 = $festivo1;
        $fiesta2 = substr($fiesta2, 8, 2) . '-' . substr($fiesta2, 5, 2) . '-' . substr($fiesta2, 0, 4) . ' 12:00';

        $fiesta3 = $festivo2;
        $fiesta3 = substr($fiesta3, 8, 2) . '-' . substr($fiesta3, 5, 2) . '-' . substr($fiesta3, 0, 4) . ' 12:00';

        $fiesta4 = $festivo3;
        $fiesta4 = substr($fiesta4, 8, 2) . '-' . substr($fiesta4, 5, 2) . '-' . substr($fiesta4, 0, 4) . ' 12:00';

        $fiesta5 = $festivo4;
        $fiesta5 = substr($fiesta5, 8, 2) . '-' . substr($fiesta5, 5, 2) . '-' . substr($fiesta5, 0, 4) . ' 12:00';

        $fiesta6 = $festivo5;
        $fiesta6 = substr($fiesta6, 8, 2) . '-' . substr($fiesta6, 5, 2) . '-' . substr($fiesta6, 0, 4) . ' 12:00';

        $fiesta7 = $festivo6;
        $fiesta7 = substr($fiesta7, 8, 2) . '-' . substr($fiesta7, 5, 2) . '-' . substr($fiesta7, 0, 4) . ' 12:00';

        $fiesta8 = $festivo7;
        $fiesta8 = substr($fiesta8, 8, 2) . '-' . substr($fiesta8, 5, 2) . '-' . substr($fiesta8, 0, 4) . ' 12:00';

        $fiesta9 = $festivo8;
        $fiesta9 = substr($fiesta9, 8, 2) . '-' . substr($fiesta9, 5, 2) . '-' . substr($fiesta9, 0, 4) . ' 12:00';

        $fiesta10 = $festivo9;
        $fiesta10 = substr($fiesta10, 8, 2) . '-' . substr($fiesta10, 5, 2) . '-' . substr($fiesta10, 0, 4) . ' 12:00';

        $fiesta11 = $festivo10;
        $fiesta11 = substr($fiesta11, 8, 2) . '-' . substr($fiesta11, 5, 2) . '-' . substr($fiesta11, 0, 4) . ' 12:00';

        $fiesta12 = $festivo11;
        $fiesta12 = substr($fiesta12, 8, 2) . '-' . substr($fiesta12, 5, 2) . '-' . substr($fiesta12, 0, 4) . ' 12:00';

        

        $festivos = array();
        if ($fiesta1 != "00-00-0000 12:00")
            $festivos[] = $fiesta1;
        if ($fiesta2 != "00-00-0000 12:00")
            $festivos[] = $fiesta2;
        if ($fiesta3 != "00-00-0000 12:00")
            $festivos[] = $fiesta3;
        if ($fiesta4 != "00-00-0000 12:00")
            $festivos[] = $fiesta4;
        if ($fiesta5 != "00-00-0000 12:00")
            $festivos[] = $fiesta5;
        if ($fiesta6 != "00-00-0000 12:00")
            $festivos[] = $fiesta6;
        if ($fiesta7 != "00-00-0000 12:00")
            $festivos[] = $fiesta7;
        if ($fiesta8 != "00-00-0000 12:00")
            $festivos[] = $fiesta8;
        if ($fiesta9 != "00-00-0000 12:00")
            $festivos[] = $fiesta9;
        if ($fiesta10 != "00-00-0000 12:00")
            $festivos[] = $fiesta10;
        if ($fiesta11 != "00-00-0000 12:00")
            $festivos[] = $fiesta11;
        if ($fiesta12 != "00-00-0000 12:00")
            $festivos[] = $fiesta12;

        
        
        //ini_set('MAX_EXECUTION_TIME', -1);
        if (array_key_exists('listados', $_POST) || array_key_exists('listados_sin', $_POST)) {

            if(isset($_POST['listados'])) unset($_POST['listados']);
            if(isset($_POST['listados_sin'])) unset($_POST['listados_sin']);

            $listadoTalleres = array();
            foreach ($_POST as $k => $v) {
                if (intval($k))
                    $listadoTalleres[] = $k;
            }
            //var_dump($listadoTalleres);

            if (!empty($listadoTalleres)) {
                foreach ($listadoTalleres as $k => $v) {
                    //set_time_limit(20);
                    //echo $k.'<br />';

                    $query = "SELECT t.nombre as nombre, d1.nombre_catalan as dia1, d2.nombre_catalan as dia2, "
                            . " t.inicio_1 as inicio1, t.inicio_2 as inicio2, "
                            . " t.final_1 as final1, t.final_2 as final2, "
                            . " p.nombre_apellidos as nombre_apellidos, t.tipo_taller as tipoTaller "
                            . " FROM casal_talleres t "
                            . " LEFT JOIN  casal_dias_semana d1 ON t.id_dia_semana_1=d1.id "
                            . " LEFT JOIN  casal_dias_semana d2 ON t.id_dia_semana_2=d2.id "
                            . " LEFT JOIN casal_profesores p ON t.id_profesor=p.id "
                            . " WHERE t.id='$v'";
                    //echo '<br>' . $query;
                    //return;
                    $resultTaller = $this->db->query($query);
                    $taller = $resultTaller->row()->nombre;

                    //echo $taller.'<br />';
                    $grup = "grup";
                    $dia1 = $resultTaller->row()->dia1;
                    $dia2 = $resultTaller->row()->dia2;
                    $datos['dia1'] = $dia1;
                    $datos['dia1_de'] = substr($resultTaller->row()->inicio1,0,5);
                    $datos['dia1_hasta'] = substr($resultTaller->row()->final1,0,5);
                    $datos['dia2'] = $dia2;
                    $datos['dia2_de'] = substr($resultTaller->row()->inicio2,0,5);
                    $datos['dia2_hasta'] = substr($resultTaller->row()->final2,0,5);
                    $datos['tipoTaller']=$resultTaller->row()->tipoTaller;
                    //echo $taller.' --- '.$grup;
                    // $festivos = array('16-05-2016 12:00');
                    $dias = dias($inicio, $final, $dia1, $dia2, $festivos);


                    $query = "SELECT s.nombre as nom, s.apellidos as cognoms,"
                            . " s.telefono_1 as telefono_1,"
                            . " s.telefono_2 as telefono_2,"
                            . " s.email as email,"
                            . " a.pagado as pagado "
                            . " FROM casal_asistentes a"
                            . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio"
                            . " WHERE id_taller='$v' AND $periodoInicialNum=(a.trimestres & $periodoInicialNum) ORDER BY s.apellidos";
                    $resultAsistentes = $this->db->query($query);
                    $datos['resultTaller'] = $resultTaller;
                    $datos['resultAsistentes'] = $resultAsistentes;
                    $datos['hoja'] = $hoja;
                    $datos['dias'] = $dias;
                    $datos['profesor'] = $resultTaller->row()->nombre_apellidos;
                    // echo $resultTaller->row()->profesor.'<br />';
                    $nombre_lista = $resultTaller->row()->nombre;
                    $nombre_lista = str_replace('/', ' ', $nombre_lista);
                    $nombre_lista = str_replace('.', ' ', $nombre_lista);
                    $datos['nombre_lista'] = $nombre_lista;
                    $datos['textoPeriodo']=$textoPeriodo;
                    $datos['titulo'] = $titulo;
                    $datos['listados_sin'] = isset($listados_sin)?1:0;

                    $this->load->view('asistentesCuerpo', $datos);
                    $hoja++;
                }
                $this->load->view('asistentesFinal', $datos);
            } else {
                $datos['autor'] = 'Miguel Angel Bañolas';
                $datos['titulo'] = $_SESSION['tituloCasal'];
                 $this->load->view('templates/header', $datos);
                 $this->load->view('templates/barraNavegacion', $datos);
                $this->load->view('templates/header');
                $this->load->view('noSeleccionado');
                $datos['pie']='';
                $this->load->view('templates/footer', $datos);
            }
        }
    }

}

?>
