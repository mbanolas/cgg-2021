<?php
ini_set('MAX_EXECUTION_TIME', '-1');
defined('BASEPATH') or exit('No direct script access allowed');

class GenerarListasUsuarios extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('excel');

        $this->load->helper('maba');
        $this->load->library('drawing');
        $this->load->model('talleres_model');
        ini_set('max_execution_time', 600);
        ini_set("memory_limit", "1024M");
    }

   

    public function emitirListaUsuarios()
    {

        $hoja = 0;
        extract($_POST);
        
        $periodoInicialNum = $this->talleres_model->getNumPeriodo($periodoInicial);

        $sql = "SELECT s.num_socio,s.nombre,s.apellidos as apellidos,s.telefono_1, s.telefono_2, s.email, s.fecha_nacimiento FROM casal_socios_nuevo s WHERE fecha_baja='0000-00-00' ORDER BY apellidos";
        $result = $this->db->query($sql)->result();
        $datos['titulo'] = urldecode($texto_titulo);
        $datos['texto_titulo'] = urldecode($texto_titulo);
        $datos['socios'] = [];
        $whereTipoTaller = "";
        switch ($tipoTaller[0]) {
            case 'professionals':
                $whereTipoTaller = " AND t.tipo_taller='Professional' ";
                break;
            case 'voluntaris':
                $whereTipoTaller = " AND t.tipo_taller='Voluntari' ";
                break;
            default:
                $whereTipoTaller = "";
        }

        $whereTipoTrimestres = "";
        switch ($periodoInicialNum) {
            case 1:
            case 2:
            case 4:
                $whereTipoTrimestres = " AND a.trimestres='$periodoInicialNum'";
                break;
            case 7:
                $whereTipoTrimestres = " AND (a.trimestres='$periodoInicialNum' || a.trimestres='1' || a.trimestres='2' || a.trimestres='4')  ";
                break;
            default:
                $whereTipoTrimestres = "";
        }

        $sql = "SELECT s.num_socio,s.nombre as nombre, s.apellidos as apellidos, s.telefono_1 as telefono_1, s.telefono_2 as telefono_2, s.email as email, t.nombre as taller, s.fecha_nacimiento as fecha_nacimiento
        FROM casal_asistentes a
        LEFT JOIN casal_talleres t ON t.id=a.id_taller
        LEFT JOIN casal_cursos c ON t.id_curso=c.id
        LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.num_socio
        WHERE  c.id='$curso'  $whereTipoTaller $whereTipoTrimestres   
        ORDER BY s.apellidos,s.nombre";

        $result = $this->db->query($sql)->result();
        $datos['talleres'] = [];
        foreach ($result as $k => $v) {
            $num_socio = $v->num_socio;
            $taller = $v->taller;
            if (isset($datos['talleres'][$num_socio])) {
                $datos['talleres'][$num_socio] .= ', ' . $taller;
            } else {
                $datos['talleres'][$num_socio] = $taller;
            }
        }
        
        foreach ($datos['talleres'] as $k3 => $v3) {
            $sql = "SELECT s.num_socio,s.nombre as nombre, s.apellidos as apellidos, s.telefono_1 as telefono_1, s.telefono_2 as telefono_2, s.email as email, s.fecha_nacimiento as fecha_nacimiento
                    FROM casal_socios_nuevo s WHERE s.num_socio='$k3'";
            $row = $this->db->query($sql)->row();
            $edad = date_diff(date_create($row->fecha_nacimiento), date_create('today'))->format('%y');

            $telefono_1 = trim($row->telefono_1);
            $telefono_1 = str_replace(" ", "", $telefono_1);
            if (strlen($telefono_1) == 9) $telefono_1 = substr($telefono_1, 0, 3) . " " . substr($telefono_1, 3, 3) . " " . substr($telefono_1, 6);
            $telefono_2 = trim($row->telefono_2);
            $telefono_2 = str_replace(" ", "", $telefono_2);
            $datos['socios'][] = [
                'nombre' => $row->nombre,
                'apellidos' => $row->apellidos,
                'telefono_1' => $telefono_1,
                'telefono_2' => $telefono_2,
                'email' => $row->email,
                'fecha_nacimiento' => $row->fecha_nacimiento,
                'edad' => $edad,
                'talleres' => $v3
            ];
        }
        $this->load->view('listadoExcelA', $datos);
    }
}
