<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No permitido el acceso directo a la URL</h2>");


class Talleres extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('socios_model');
        $this->load->model('talleres_model');
    }

    public function anularRecibo($idRecibo)
    {
        $this->talleres_model->anularRecibo($idRecibo);
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('exitoReciboAnulado', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function prepararCasalReservas()
    {
        $this->talleres_model->prepararCasalReservas();
    }

    public function anadirCurso()
    {
        $curso = $this->talleres_model->getUltimoCurso();
        $datos['ultimoCurso'] = $curso['ultimoCurso'];
        $datos['talleres'] = $curso['talleres'];
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('anadirCurso', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function anadirTalleresCurso()
    {

        //echo "<h3>Talleres nuevos para curso: ".$_POST['nuevoCurso']."</h3>";
        //echo implode(' - ',$_POST['talleres']);
        $talleres = $_POST['talleres'];
        $curso = $_POST['nuevoCurso'];
        $this->load->model('talleres_model');
        $this->talleres_model->putTalleresCurso($curso, $talleres);

        $curso = $this->talleres_model->getUltimoCurso();
        $datos['ultimoCurso'] = $curso['ultimoCurso'];
        $datos['talleres'] = $curso['talleres'];

        $this->load->view('templates/header', $datos);
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('talleresAnadidos', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function getFechasPeriodo()
    {
        $curso = $_POST['curso'];
        $periodo = $_POST['periodo'];
        $numPeriodo = $this->talleres_model->getNumPeriodo($periodo);
        $textoPeriodo = $this->talleres_model->getTextoPeriodo($periodo);
        $fechas = $this->talleres_model->getFechasPeriodo($curso, $numPeriodo);
        echo json_encode($fechas);
    }

    public function anulacion()
    {
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $this->load->view('templates/barraNavegacion', $datos);
        $datos['infoUltimoRecibo'] = $this->talleres_model->getInfoUltimoRecibo();
        if (!$datos['infoUltimoRecibo']['devolucion']) {
            $this->load->view('anulacionUltimoRecibo', $datos);
        } else {
            $this->load->view('anulacionUltimoReciboNo', $datos);
        }

        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function grabarFechas()
    {
        $fechas = $this->talleres_model->grabarFechas();
        echo json_encode($fechas);
    }

    public function getTablaAsistentesTaller()
    {

        $table = $this->talleres_model->getTablaAsistentesTaller($_POST['taller'], $_POST['orden'], $_POST['periodo']);
        echo json_encode($table);
    }

    public function getTablaReservasTaller()
    {

        $table = $this->talleres_model->getTablaReservasTaller($_POST['taller'], $_POST['orden'], $_POST['periodo']);
        echo json_encode($table);
    }
    public function getTablaReservasCurso()
    {

        $table = $this->talleres_model->getTablaReservasCurso($_POST['curso'], $_POST['orden'], $_POST['periodo']);
        echo json_encode($table);
    }

    public function getTextosCursoPeriodo()
    {
        $textos = $this->talleres_model->getTextosCursoPeriodo($_POST['curso'], $_POST['periodo']);
        echo json_encode($textos);
    }

    public function getTablaTalleresListas()
    {
        $result = $this->talleres_model->getTalleres($_POST['curso'], $_POST['periodo'], $_POST['tipoTaller']);
        $tabla = '<table>';
        $tabla .= '<tr><th><input  type="checkbox" id="marcarTodos" ></th><th >&nbsp;&nbsp;Nombre Taller</th></tr>';
        $tabla .= '<tr><td> </td></tr>';
        $lista = array();
        foreach ($result as $k => $v) {
            $lista[] = array('id' => $v->id, 'nombre' => $v->nombre, 'tipo_taller' => $v->tipo_taller);
        }
        foreach ($lista as $k => $v) {
            //log_message('INFO', $v['id'].' '.$v['nombre']);
        }

        //colocación tabla con nombres ordenados por columnas
        $medio = round(count($lista) / 4, 0, PHP_ROUND_HALF_UP);
        $medio++;
        //FIN colocación tabla con nombres ordenados por columnas

        // foreach($lista as $k=>$v){
        for ($k = 0; $k < $medio; $k++) {
            $tabla .= '<tr>';
            $tabla .= '<td ><input class="taller" type="checkbox" name="' . $lista[$k]['id'] . '"></td>';
            if ($lista[$k]['tipo_taller'] == 'Professional') $class = "class='profesional'";
            else $class = "";
            $tabla .= '<td ' . $class . '>&nbsp;&nbsp;' . $lista[$k]['nombre'] . '</td>';
            $tabla .= '<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
            if (isset($lista[$k + $medio]['id'])) {
                $tabla .= '<td><input class="taller" type="checkbox" name="' . $lista[$k + $medio]['id'] . '"></td>';
                if ($lista[$k + $medio]['tipo_taller'] == 'Professional') $class = "class='profesional'";
                else $class = "";
                $tabla .= '<td ' . $class . '>&nbsp;&nbsp;' . $lista[$k + $medio]['nombre'] . '</td>';
            }
            $tabla .= '<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
            if (isset($lista[$k + $medio * 2]['id'])) {
                $tabla .= '<td><input class="taller" type="checkbox" name="' . $lista[$k + $medio * 2]['id'] . '"></td>';
                if ($lista[$k + $medio * 2]['tipo_taller'] == 'Professional') $class = "class='profesional'";
                else $class = "";
                $tabla .= '<td ' . $class . '>&nbsp;&nbsp;' . $lista[$k + $medio * 2]['nombre'] . '</td>';
            }
            $tabla .= '<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
            if (isset($lista[$k + $medio * 3]['id'])) {
                $tabla .= '<td><input class="taller" type="checkbox" name="' . $lista[$k + $medio * 3]['id'] . '"></td>';
                if ($lista[$k + $medio * 3]['tipo_taller'] == 'Professional') $class = "class='profesional'";
                else $class = "";
                $tabla .= '<td ' . $class . '>&nbsp;&nbsp;' . $lista[$k + $medio * 3]['nombre'] . '</td>';
            }
            $tabla .= '</tr>';
        }
        $tabla .= '</table>';
        echo json_encode($tabla);
    }

    public function getTablaResumenCurso()
    {
        $textoPeriodo = $this->talleres_model->getTextoPeriodo($_POST['periodo']);

        //log_message('INFO', 'paso 1 Talleres getTablaResumenCurso $_POST[periodo] '.$_POST['periodo']);
        //log_message('INFO', 'paso 1 Talleres getTablaResumenCurso $_POST[tipoTaller] '.$_POST['tipoTaller']);
        //log_message('INFO', 'paso 1 Talleres getTablaResumenCurso $_POST[curso] '.$_POST['curso']);

        $table = $this->talleres_model->getTablaResumenCurso($_POST['curso'], $_POST['tipoTaller'], $_POST['periodo']);
        //log_message('INFO', 'paso 2 Talleres getTablaResumenCurso $_POST[periodo] '.$_POST['periodo']);
        echo json_encode(array('table' => $table, 'textoPeriodo' => $textoPeriodo));
    }
    public function getTablaSociosInscritosCurso()
    {
        $table = $this->talleres_model->getTablaSociosInscritosCurso($_POST['curso']);

        echo json_encode($table);
    }




    public function pdfTalleresSocio()
    {

        $socio = $_POST['socio'];
        $curso = $_POST['curso'];
        $this->load->model('socios_model');

        $informacion = $this->socios_model->getDatosTalleresSocio($curso, $socio);
        //var_dump($informacion);
        //echo $informacion['nombre_curso'].'<br>';
        // echo $informacion['nombre_socio'].'<br>';


        // Se carga la libreria fpdf
        $this->load->library('pdf');
        $this->pdf = new Pdf();
        $this->pdf->setSubtitulo('');

        // Agregamos una página
        $this->pdf->AddPage('L');
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);

        $marco = 0;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(90, 7, 'Curs: ' . utf8_decode($informacion['nombre_curso']), $marco, 1, 'L', '0');
        //$this->pdf->Cell(15,3,'    Espacio: '.utf8_decode($datosTaller->espacio),$marco,1,'L','0');
        $this->pdf->Cell(90, 7, 'Usuari/Usuària: ' . utf8_decode($informacion['nombre_socio']), $marco, 1, 'L', '0');
        $this->pdf->Ln(3);
        $this->pdf->SetFont('Arial', 'B', 12);
        /*
     * TITULOS DE COLUMNAS
     *
     * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
     */

        $h = 9;
        $this->pdf->Cell(75, $h, 'TALLER', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(30, $h, 'PROFESOR', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(30, $h, 'ESPACIO', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(25, $h, 'DIA', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(25, $h, 'INICIO', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(25, $h, 'FINAL', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(25, $h, 'TRIMES.', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(25, $h, iconv('UTF-8', 'windows-1252', 'PAG. (€)'), 'TBRL', 0, 'L', '1');
        $this->pdf->Ln($h);
        $this->pdf->SetFont('Arial', '', 12);
        $h1 = 8;
        $numeroTalleres = 0;
        $totalPagado = 0;
        if (!is_null($informacion['resultado'])) {
            foreach ($informacion['resultado'] as $k => $v) {
                $numeroTalleres++;
                $totalPagado += $v->pagado;
                $inicio_1 = substr($v->inicio_1, 0, 5);
                $final_1 = substr($v->final_1, 0, 5);
                $nombre_espacio_1 = substr($v->nombre_espacio_1, 0, 10);
                switch ($v->trimestres) {
                    case 1:
                        $trimestres = "T3";
                        break;
                    case 2:
                        $trimestres = "T2";
                        break;
                    case 3:
                        $trimestres = "T2, T3";
                        break;
                    case 4:
                        $trimestres = "T1";
                        break;
                    case 5:
                        $inscrito = "T1, T3";
                        break;
                    case 6:
                        $trimestres = "T1, T2";
                        break;
                    case 7:
                        $trimestres = "C";
                        break;
                    default:
                        $trimestres = "";
                }


                $this->pdf->Cell(75, $h1, iconv('UTF-8', 'windows-1252', $v->nombre_taller), 'TBRL', 0, 'L', '0');
                $this->pdf->Cell(30, $h1, iconv('UTF-8', 'windows-1252', $v->nombre_profesor), 'TBRL', 'L', '0');
                $this->pdf->Cell(30, $h1, iconv('UTF-8', 'windows-1252', $nombre_espacio_1), 'TBRL', 0, 'L', '0');
                $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $v->dia_semana_1), 'TBRL', 0, 'L', '0');
                $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $inicio_1), 'TBRL', 0, 'C', '0');
                $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $final_1), 'TBRL', 0, 'C', '0');
                $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $trimestres), 'TBRL', 0, 'L', '0');
                $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $v->pagado), 'TBRL', 0, 'L', '0');
                $this->pdf->Ln($h1);
            }
        }
        $this->pdf->Ln(1);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(75, $h1, iconv('UTF-8', 'windows-1252', $numeroTalleres), 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(30, $h1, '', 'TBRL', 'L', '0');
        $this->pdf->Cell(30, $h1, '', 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(25, $h1, '', 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(25, $h1, '', 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(25, $h1, '', 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(25, $h1, '', 'TBRL', 0, 'L', '0');
        $this->pdf->Cell(25, $h1, iconv('UTF-8', 'windows-1252', $totalPagado), 'TBRL', 0, 'L', '0');
        $this->pdf->Ln($h1);




        $this->pdf->Output("Talleres Socio.pdf", 'D');
    }

    public function formatearTelefono($telefono){
        mensaje($telefono);
        mensaje(strlen($telefono));
        if(strlen($telefono)!=9) return $telefono;
        else return substr($telefono,0,3).' '.substr($telefono,3,3).' '.substr($telefono,6);
    }

    public function pdfAsistentes()
    {


        $numPeriodo = $this->talleres_model->getNumPeriodo($_POST['periodo'][0]);
        $textoPeriodo = $this->talleres_model->getTextoPeriodo($_POST['periodo'][0]);
        //echo $numPeriodo;
        $orden = 0;
        $tipoInforme = 1;
        if (isset($_POST['pdfAsistentesSin'])) $tipoInforme = 0;
        if (isset($_POST['pdfAsistentesSinTelefono'])) $tipoInforme = 2;
        if (isset($_POST['pdfAsistentesConEmail'])) $tipoInforme = 3;
        if ($_POST['orden'] == "ordenNumSocio") $orden = 1;
        // Se carga el modelo alumno
        $this->load->model('talleres_model');
        // Se carga la libreria fpdf
        $this->load->library('pdf');

        $taller = $_POST['taller'];
        // Se obtienen los alumnos de la base de datos
        $asistentes = $this->talleres_model->getAsistentesTaller($taller, $orden, $numPeriodo);
        //var_dump($asistentes) ;
        //$curso=$this->talleres_model->getNombreCursoTaller($taller);
        $curso = $this->talleres_model->getCurso($taller);
        $datosTaller = $this->talleres_model->getDatosTaller($taller);
        //$totalAsistentes=$this->getTotalAsistentesTaller($taller);
        //var_dump($talleres);

        // Creacion del PDF

        /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
        $this->pdf = new Pdf();
        $cursoNombre = $this->talleres_model->getNombreCurso($curso);
        $this->pdf->AddLink();

        //$this->pdf->setSubtitulo('Taller: '.$curso.'  '.$this->talleres_model->getNombreTaller($taller));
        $this->pdf->setSubtitulo('');
        $this->pdf->setSubtitulo(iconv('UTF-8', 'CP1252', 'Taller curs: ' . $cursoNombre . ' ' . $textoPeriodo));
        // Agregamos una página

        if ($tipoInforme == 3) $this->pdf->AddPage('L');
        else $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Asistentes taller " . $this->talleres_model->getNombreTaller($taller));
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(90, 3, 'Taller: ' . utf8_decode($datosTaller->nombreTaller), '', 0, 'L', '0');
        $this->pdf->Cell(15, 3, '    Espacio: ' . utf8_decode($datosTaller->espacio), '', 1, 'L', '0');
        $this->pdf->Cell(15, 7, 'Profesor: ' . utf8_decode($datosTaller->nombreProfesor), '', 1, 'L', '0');

        $dia1 = "";
        if (!is_null($datosTaller->dia1))
            $dia1 = $datosTaller->dia1 . '  ' . substr($datosTaller->inicio1, 0, 5) . ' - ' . substr($datosTaller->final1, 0, 5);
        $dia2 = "";
        if (!is_null($datosTaller->dia2))
            $dia2 = $datosTaller->dia2 . '  ' . substr($datosTaller->inicio2, 0, 5) . ' - ' . substr($datosTaller->final2, 0, 5);
        $dias = 'Dia';
        $separador = '  ';
        if ($dia2) {
            $dias = 'Dias';
            $separador = ' / ';
        }

        $this->pdf->Cell(15, 9, utf8_decode($dias . ': ' . $dia1 . $separador . $dia2), '', 1, 'L', '0');





        $this->pdf->SetFont('Arial', 'B', 9);
        /*
     * TITULOS DE COLUMNAS
     *
     * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
     */

        $this->pdf->Cell(15, 7, 'NUM', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(75, 7, 'ASISTENTE', 'TBRL', 0, 'L', '1');
        if ($tipoInforme == 0 || $tipoInforme == 1 || $tipoInforme == 3) $this->pdf->Cell(40, 7, 'TELEFONOS', 'TBRL', 0, 'L', '1');

        if ($tipoInforme == 3) {
            $this->pdf->Cell(60, 7, 'EMAIL', 'TBRL', 0, 'L', '1');
            $this->pdf->Cell(25, 7, 'TRIMESTRES', 'TBRL', 0, 'L', '1');
        } else $this->pdf->Cell(25, 7, 'TRIMESTRES', 'TBRL', 0, 'L', '1');

        if ($tipoInforme == 1) $this->pdf->Cell(25, 7, 'PAGADO EUR', 'TBRL', 0, 'L', '1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(40,7,'','TB',0,'C','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TBR',0,'C','1');
        $this->pdf->Ln(7);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        $num = 0;
        $numAsistentes = 0;
        $total = 0;
        foreach ($asistentes as $k => $asistente) {
            switch ($asistente['trimestres']) {
                case 1:
                    $inscrito = "T3";
                    break;
                case 2:
                    $inscrito = "T2";
                    break;
                case 3:
                    $inscrito = "T2, T3";
                    break;
                case 4:
                    $inscrito = "T1";
                    break;
                case 5:
                    $inscrito = "T1, T3";
                    break;
                case 6:
                    $inscrito = "T1, T2";
                    break;
                case 7:
                    $inscrito = "C";
                    break;
                default:
                    $inscrito = "";
            }

            $this->pdf->Cell(15, 5, $asistente['numSocio'], 'BL', 0, 'C', 0);
            //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

            // Se imprimen los datos de cada alumno
            $telefono1 = trim($asistente['telefono_1']);
            $telefono2 = trim($asistente['telefono_2']);
            $telefono = "";
            $separador="/";
            if($tipoInforme == 3) {
                $telefono1=$this->formatearTelefono($telefono1);
                $telefono2=$this->formatearTelefono($telefono2);
                $separador=" / ";
            }
            if ($telefono1 && $telefono2) $telefono = $telefono1 . $separador . $telefono2;
            if (!$telefono1 && $telefono2) $telefono = $this->formatearTelefono($telefono2);
            if ($telefono1 && !$telefono2) $telefono = $this->formatearTelefono($telefono1);

            $this->pdf->Cell(75, 5, utf8_decode($asistente['asistente']), 'BLR', 0, 'L', 0);
            if ($tipoInforme == 0 || $tipoInforme == 1 || $tipoInforme == 3) $this->pdf->Cell(40, 5, utf8_decode($telefono), 'BLR', 0, 'L', 0);
            if ($tipoInforme == 3) {
                // $this->pdf->Cell(60, 5, $asistente['email'], 'BLR', 0, 'L', 0);
                // $this->pdf->Link(60,5 ,10,10,"maito:".$asistente['email'], 'BLR', 0, 'L', 0);
                $this->pdf->Cell(60, 5, $asistente['email'], 'BLR', 0, 'L', 0,"mailto:".$asistente['email']);
                $this->pdf->Cell(25, 5, $inscrito, 'BLR', 0, 'L', 0);
            } else   $this->pdf->Cell(25, 5, $inscrito, 'BLR', 0, 'L', 0);

            if ($tipoInforme == 1) $this->pdf->Cell(25, 5, $asistente['pagado'] == 0 ? '' : number_format($asistente['pagado'],2) . '   ', 'BLR', 0, 'R', 0);
            //Se agrega un salto de linea
            $this->pdf->Ln(5);
            $num++;
            $numAsistentes++;
            $total += $asistente['pagado'];
        }
        $this->pdf->Ln(1);
        $this->pdf->Cell(15, 5, '', 'TBLR', 0, 'C', 0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

        // Se imprimen los datos de cada alumno
        $this->pdf->Cell(75, 5, $num, 'TBLR', 0, 'L', 0);
        if ($tipoInforme == 0 || $tipoInforme == 1 || $tipoInforme == 3) $this->pdf->Cell(40, 5, '  ', 'TBLR', 0, 'R', 0);
       
        

        if ($tipoInforme == 3) {
            $this->pdf->Cell(60, 5, '  ', 'TBLR', 0, 'R', 0);
            $this->pdf->Cell(25, 5, '  ', 'TBLR', 0, 'R', 0);
        } else    $this->pdf->Cell(25, 5, '  ', 'TBLR', 0, 'R', 0);

        if ($tipoInforme == 1) $this->pdf->Cell(25, 5, number_format($total,2) . '   ', 'TBLR', 0, 'R', 0);

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
        $this->pdf->Output("Taller " . iconv('UTF-8', 'CP1252', $this->talleres_model->getNombreTaller($taller)) . ".pdf", 'D');
    }

    public function pdfReservasTodos()
    {
        $numPeriodo = $this->talleres_model->getNumPeriodo($_POST['periodo'][0]);
        $textoPeriodo = $this->talleres_model->getTextoPeriodo($_POST['periodo'][0]);
        $curso = $_POST['curso'];
        //echo $numPeriodo;
        $orden = 0;
        $tipoInforme = 1;
        if (isset($_POST['pdfReservasSin'])) $tipoInforme = 0;
        if (isset($_POST['pdfReservasSinTelefono'])) $tipoInforme = 2;
        if ($_POST['orden'] == "ordenNombreSocio") $orden = 1;
        if ($_POST['orden'] == "ordenNumSocio") $orden = 2;
        // Se carga el modelo alumno
        $this->load->model('talleres_model');
        // Se carga la libreria fpdf
        $this->load->library('pdf');

        // $taller=$_POST['taller'];
        // Se obtienen los alumnos de la base de datos
        $reservas = $this->talleres_model->getListaEspera($curso, $orden, $numPeriodo);
        //var_dump($asistentes) ;
        //$curso=$this->talleres_model->getNombreCursoTaller($taller);
        // $curso=$this->talleres_model->getCurso($taller);
        // $datosTaller=$this->talleres_model->getDatosTaller($taller);
        //$totalAsistentes=$this->getTotalAsistentesTaller($taller);
        //var_dump($talleres);

        // Creacion del PDF

        /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
        $this->pdf = new Pdf();
        $cursoNombre = $this->talleres_model->getNombreCurso($curso);

        //$this->pdf->setSubtitulo('Taller: '.$curso.'  '.$this->talleres_model->getNombreTaller($taller));
        $this->pdf->setSubtitulo('');
        $this->pdf->setSubtitulo(iconv('UTF-8', 'CP1252', 'Taller curs: ' . $cursoNombre . ' ' . $textoPeriodo));
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Llista espera curso " . $cursoNombre);
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        // $this->pdf->SetFillColor(255,253,56);
        $this->pdf->SetFillColor(200, 200, 200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 12);
        // $this->pdf->Cell(90,3,'Taller: '.utf8_decode($datosTaller->nombreTaller),'',0,'L','0');
        // $this->pdf->Cell(15,3,'    Espacio: '.utf8_decode($datosTaller->espacio),'',1,'L','0');
        // $this->pdf->Cell(15,7,'Profesor: '.utf8_decode($datosTaller->nombreProfesor),'',1,'L','0');

        // $dia1="";
        //     if(!is_null($datosTaller->dia1))
        //         $dia1= $datosTaller->dia1.'  '.substr($datosTaller->inicio1,0,5).' - '.substr($datosTaller->final1,0,5);
        //     $dia2="";
        //     if(!is_null($datosTaller->dia2))
        //         $dia2=$datosTaller->dia2.'  '.substr($datosTaller->inicio2,0,5).' - '.substr($datosTaller->final2,0,5);
        //     $dias='Dia';$separador='  ';
        //     if($dia2){$dias='Dias';$separador=' / ';}

        // $this->pdf->Cell(15,9,utf8_decode($dias.': '.$dia1.$separador.$dia2),'',1,'L','0');





        $this->pdf->SetFont('Arial', 'B', 9);
        /*
    * TITULOS DE COLUMNAS
    *
    * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
    */

        $this->pdf->Cell(45, 7, 'TALLER', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(15, 7, 'ORDRE', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(15, 7, 'NUM', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(75, 7, 'EN LLISTA ESPERA', 'TBRL', 0, 'L', '1');
        if ($tipoInforme == 0 || $tipoInforme == 1) $this->pdf->Cell(40, 7, 'TELEFONOS', 'TBRL', 0, 'L', '1');
        // $this->pdf->Cell(25,7,'TRIMESTRES','TBRL',0,'L','1');

        //    if($tipoInforme==1) $this->pdf->Cell(25,7,'PAGADO EUR','TBRL',0,'L','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(40,7,'','TB',0,'C','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TBR',0,'C','1');
        $this->pdf->Ln(7);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        $num = 0;
        $numAsistentes = 0;
        $total = 0;
        $nombreTallerAnterior = "";
        // $this->pdf->SetFillColor(150,150,150);
        $this->pdf->SetFillColor(200, 200, 200);

        foreach ($reservas as $k => $reserva) {
            // Se imprimen los datos de cada alumno
            $telefono1 = trim($reserva['telefono_1']);
            $telefono2 = trim($reserva['telefono_2']);
            $telefono = "";
            if ($telefono1 && $telefono2) $telefono = $telefono1 . "/" . $telefono2;
            if (!$telefono1 && $telefono2) $telefono = $telefono2;
            if ($telefono1 && !$telefono2) $telefono = $telefono1;

            if ($reserva['nombreTaller'] != $nombreTallerAnterior) {
                $this->pdf->Ln(2);
                $this->pdf->SetFillColor(255, 253, 56);

                $this->pdf->Cell(45, 5, utf8_decode($reserva['nombreTaller']), 1, 0, 'L', 1);
                $this->pdf->Cell(15, 5, '(' . $reserva['orden'] . ')', 1, 0, 'C', 1);
                $this->pdf->Cell(15, 5, $reserva['numSocio'], 1, 0, 'C', 1);
                $this->pdf->Cell(75, 5, utf8_decode($reserva['asistente']), 1, 0, 'L', 1);
                if ($tipoInforme == 0 || $tipoInforme == 1) $this->pdf->Cell(40, 5, utf8_decode($telefono), 1, 0, 'L', 1);
                $nombreTallerAnterior = $reserva['nombreTaller'];
            } else {
                $this->pdf->SetFillColor(255, 253, 56);
                $this->pdf->Cell(45, 5, '', 1, 0, 'L', 1);
                $this->pdf->Cell(15, 5, '(' . $reserva['orden'] . ')', 1, 0, 'C', 1);
                $this->pdf->Cell(15, 5, $reserva['numSocio'], 1, 0, 'C', 1);
                $this->pdf->Cell(75, 5, utf8_decode($reserva['asistente']), 1, 0, 'L', 1);
                if ($tipoInforme == 0 || $tipoInforme == 1) $this->pdf->Cell(40, 5, utf8_decode($telefono), 1, 0, 'L', 1);
                // $this->pdf->Cell(45,5,'',1,0,'L',0);


            }
            // $this->pdf->Cell(45,5,utf8_decode($reserva['nombreTaller']),'BL',0,'L',0);
            //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);



            //   $this->pdf->Cell(25,5,$inscrito,'BLR',0,'L',0);
            //   if($tipoInforme==1) $this->pdf->Cell(25,5,$reserva['pagado']==0?'':$reserva['pagado'].'   ','BLR',0,'R',0);
            //Se agrega un salto de linea
            $this->pdf->Ln(5);
            $num++;
            $numAsistentes++;
            //   $total+=$reserva['pagado'];
        }
        $this->pdf->Ln(1);
        $this->pdf->Cell(150, 5, utf8_decode(' Núm usuaris/usuàrias en llista espera ') . $num, '', 0, 'L', 0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

        //   $this->pdf->Cell(75,5,$num,'TBLR',0,'L',0);
        //   if($tipoInforme==0 || $tipoInforme==1) $this->pdf->Cell(40,5,'  ','TBLR',0,'R',0);
        //   $this->pdf->Cell(25,5,'  ','TBLR',0,'R',0);
        //  if($tipoInforme==1) $this->pdf->Cell(25,5,$total.'   ','TBLR',0,'R',0);
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
        $this->pdf->Output("Llista espera " . iconv('UTF-8', 'CP1252', $this->talleres_model->getNombreCurso($curso) . ' ' . $textoPeriodo) . ".pdf", 'D');
    }

    public function pdfReservas()
    {
        $numPeriodo = $this->talleres_model->getNumPeriodo($_POST['periodo'][0]);
        $textoPeriodo = $this->talleres_model->getTextoPeriodo($_POST['periodo'][0]);
        //echo $numPeriodo;
        $orden = 0;
        $tipoInforme = 1;
        if (isset($_POST['pdfReservasSin'])) $tipoInforme = 0;
        if (isset($_POST['pdfReservasSinTelefono'])) $tipoInforme = 2;
        if ($_POST['orden'] == "ordenNombreSocio") $orden = 1;
        if ($_POST['orden'] == "ordenNumSocio") $orden = 2;
        // Se carga el modelo alumno
        $this->load->model('talleres_model');
        // Se carga la libreria fpdf
        $this->load->library('pdf');

        $taller = $_POST['taller'];
        // Se obtienen los alumnos de la base de datos
        $reservas = $this->talleres_model->getListaEsperaTaller($taller, $orden, $numPeriodo);
        //var_dump($asistentes) ;
        //$curso=$this->talleres_model->getNombreCursoTaller($taller);
        $curso = $this->talleres_model->getCurso($taller);
        $datosTaller = $this->talleres_model->getDatosTaller($taller);
        //$totalAsistentes=$this->getTotalAsistentesTaller($taller);
        //var_dump($talleres);

        // Creacion del PDF

        /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
        $this->pdf = new Pdf();
        $cursoNombre = $this->talleres_model->getNombreCurso($curso);

        //$this->pdf->setSubtitulo('Taller: '.$curso.'  '.$this->talleres_model->getNombreTaller($taller));
        $this->pdf->setSubtitulo('');
        $this->pdf->setSubtitulo(iconv('UTF-8', 'CP1252', 'Taller curs: ' . $cursoNombre . ' ' . $textoPeriodo));
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Llista espera taller " . $this->talleres_model->getNombreTaller($taller));
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        // $this->pdf->SetFillColor(200,200,200);
        $this->pdf->SetFillColor(255, 253, 56);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(90, 3, 'Taller: ' . utf8_decode($datosTaller->nombreTaller), '', 0, 'L', '0');
        $this->pdf->Cell(15, 3, '    Espacio: ' . utf8_decode($datosTaller->espacio), '', 1, 'L', '0');
        $this->pdf->Cell(15, 7, 'Profesor: ' . utf8_decode($datosTaller->nombreProfesor), '', 1, 'L', '0');

        $dia1 = "";
        if (!is_null($datosTaller->dia1))
            $dia1 = $datosTaller->dia1 . '  ' . substr($datosTaller->inicio1, 0, 5) . ' - ' . substr($datosTaller->final1, 0, 5);
        $dia2 = "";
        if (!is_null($datosTaller->dia2))
            $dia2 = $datosTaller->dia2 . '  ' . substr($datosTaller->inicio2, 0, 5) . ' - ' . substr($datosTaller->final2, 0, 5);
        $dias = 'Dia';
        $separador = '  ';
        if ($dia2) {
            $dias = 'Dias';
            $separador = ' / ';
        }

        $this->pdf->Cell(15, 9, utf8_decode($dias . ': ' . $dia1 . $separador . $dia2), '', 1, 'L', '0');





        $this->pdf->SetFont('Arial', 'B', 9);
        /*
     * TITULOS DE COLUMNAS
     *
     * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
     */

        $this->pdf->Cell(15, 7, 'ORDRE', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(15, 7, 'NUM', 'TBRL', 0, 'C', '1');
        $this->pdf->Cell(75, 7, 'EN LLISTA ESPERA', 'TBRL', 0, 'L', '1');
        if ($tipoInforme == 0 || $tipoInforme == 1) $this->pdf->Cell(40, 7, 'TELEFONOS', 'TBRL', 0, 'L', '1');
        $this->pdf->Cell(25, 7, 'TRIMESTRES', 'TBRL', 0, 'L', '1');

        //    if($tipoInforme==1) $this->pdf->Cell(25,7,'PAGADO EUR','TBRL',0,'L','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(40,7,'','TB',0,'C','1');
        //$this->pdf->Cell(25,7,'','TB',0,'L','1');
        //$this->pdf->Cell(25,7,'','TBR',0,'C','1');
        $this->pdf->Ln(7);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        $num = 0;
        $numAsistentes = 0;
        $total = 0;
        foreach ($reservas as $k => $reserva) {
            switch ($reserva['trimestres']) {
                case 1:
                    $inscrito = "T3";
                    break;
                case 2:
                    $inscrito = "T2";
                    break;
                case 3:
                    $inscrito = "T2, T3";
                    break;
                case 4:
                    $inscrito = "T1";
                    break;
                case 5:
                    $inscrito = "T1, T3";
                    break;
                case 6:
                    $inscrito = "T1, T2";
                    break;
                case 7:
                    $inscrito = "C";
                    break;
                default:
                    $inscrito = "";
            }
            // $this->pdf->SetFillColor(255,253,56);    
            $this->pdf->Cell(15, 5, '(' . $reserva['orden'] . ')', 'BL', 0, 'C', 0);
            $this->pdf->Cell(15, 5, $reserva['numSocio'], 'BL', 0, 'C', 0);
            //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

            // Se imprimen los datos de cada alumno
            $telefono1 = trim($reserva['telefono_1']);
            $telefono2 = trim($reserva['telefono_2']);
            $telefono = "";
            if ($telefono1 && $telefono2) $telefono = $telefono1 . "/" . $telefono2;
            if (!$telefono1 && $telefono2) $telefono = $telefono2;
            if ($telefono1 && !$telefono2) $telefono = $telefono1;

            $this->pdf->Cell(75, 5, utf8_decode($reserva['asistente']), 'BLR', 0, 'L', 0);
            if ($tipoInforme == 0 || $tipoInforme == 1) $this->pdf->Cell(40, 5, utf8_decode($telefono), 'BLR', 0, 'L', 0);
            $this->pdf->Cell(25, 5, $inscrito, 'BLR', 0, 'L', 0);
            //   if($tipoInforme==1) $this->pdf->Cell(25,5,$reserva['pagado']==0?'':$reserva['pagado'].'   ','BLR',0,'R',0);
            //Se agrega un salto de linea
            $this->pdf->Ln(5);
            $num++;
            $numAsistentes++;
            //   $total+=$reserva['pagado'];
        }
        $this->pdf->Ln(1);
        $this->pdf->Cell(150, 5, utf8_decode(' Núm usuaris/usuàrias en llista espera ') . $num, '', 0, 'L', 0);
        //$this->pdf->Cell(20,5,'2016-2017','BL',0,'C',0);

        //   $this->pdf->Cell(75,5,$num,'TBLR',0,'L',0);
        //   if($tipoInforme==0 || $tipoInforme==1) $this->pdf->Cell(40,5,'  ','TBLR',0,'R',0);
        //   $this->pdf->Cell(25,5,'  ','TBLR',0,'R',0);
        //  if($tipoInforme==1) $this->pdf->Cell(25,5,$total.'   ','TBLR',0,'R',0);
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
        $this->pdf->Output("Taller " . iconv('UTF-8', 'CP1252', $this->talleres_model->getNombreTaller($taller)) . ".pdf", 'D');
    }

    public function asistentes()
    {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $datos['periodo'] = $this->talleres_model->getUltimoPeriodo();
        $datos['optionsTalleres'] = array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);

        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('asistentes', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function en_lista_espera()
    {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $datos['periodo'] = $this->talleres_model->getUltimoPeriodo();
        $datos['optionsTalleres'] = array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);

        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('en_lista_espera', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function en_lista_espera_todos()
    {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $datos['periodo'] = $this->talleres_model->getUltimoPeriodo();
        $datos['optionsTalleres'] = array(); //$this->talleres_model->getTalleresOptions();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);

        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('en_lista_espera_todos', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }



    public function registrarInscripciones()
    {
        $this->load->helper('form');
        var_dump($_POST);

        $registrarInscripciones = $this->talleres_model->registrarInscripciones();
        echo 'registrarInscripciones';

        var_dump($registrarInscripciones);
        $datos['inscripcion'] = $registrarInscripciones['inscripcion'];
        $datos['datosComunes'] = $registrarInscripciones['datosComunes'];
        // var_dump($datos['inscripcion']);

        //var_dump($datos['inscripcion']);
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('registrarInscripciones', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }


    public function registrarInscripcionesNuevo()
    {
        $this->load->helper('form');
        //var_dump($_POST);
        $C = array();
        $T1 = array();
        $T2 = array();
        $T3 = array();

        extract($_POST);
        $datos['curso'] = $curso;
        $datos['socio'] = $socio;
        $datos['periodo'] = $periodo;
        $datos['numPeriodo'] = $numPeriodo;
        $datos['letraPeriodo'] = $letraPeriodo;

        $datos['C'] = $C;
        $datos['T1'] = $T1;
        $datos['T2'] = $T2;
        $datos['T3'] = $T3;



        $registrarInscripciones = $this->talleres_model->registrarInscripcionesNuevo();
        //echo 'registrarInscripciones';

        //var_dump($registrarInscripciones);
        $datos['inscripcion'] = $registrarInscripciones['inscripcion'];
        $datos['datosComunes'] = $registrarInscripciones['datosComunes'];

        //var_dump($datos['inscripcion']);

        //var_dump($datos['inscripcion']);
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('registrarInscripcionesNuevo', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    //  public function registrarBajas(){
    //     $datos['inscripcion']=$this->talleres_model->registrarBajas();

    //     $datos['autor'] = 'Miguel Angel Bañolas';
    //     $datos['titulo']=$_SESSION['tituloCasal'];
    //     $this->load->view('templates/header',$datos);
    //     $datos['activeMenu']='Talleres';
    //     $datos['activeSubmenu']='Inscripciones a talleres';
    //     $this->load->view('templates/barraNavegacion',$datos);
    //     $this->load->view('registrarBajas',$datos);
    //     $datos['pie']='';
    //     $this->load->view('templates/footer',$datos);
    // }

    public function registrarBajasNuevo()
    {
        $datos['inscripcion'] = $this->talleres_model->registrarBajasNuevo();

        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('registrarBajasNuevo', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function inscripcionesListaTalleres()
    {
        extract($_POST);
        $datos['curso'] = $this->socios_model->getNombreCurso($curso);
        $datos['socio'] = $this->socios_model->getSocio($socio);;
        $datos['talleres'] = $this->talleres_model->getTablaTalleres($curso, $socio);
        $datos['idCurso'] = $curso;
        $datos['idSocio'] = $socio;

        $this->load->helper('form');

        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('inscripcionesListaTalleres', $datos);
        $this->load->view('myModal');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function inscripcionesListaTalleresSocio()
    {
        extract($_POST);
        $datos['curso'] = $this->socios_model->getNombreCurso($curso);
        $datos['socio'] = $this->socios_model->getSocio($socio);;
        $datos['talleres'] = $this->talleres_model->getTablaTalleresInscritos($curso, $socio);
        $datos['idCurso'] = $curso;
        $datos['idSocio'] = $socio;

        $this->load->helper('form');

        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('inscripcionesListaTalleresSocio', $datos);
        $this->load->view('myModal');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    function validar_dni_($dni)
    {
        //Esun DNI?
        $dni = strtoupper(trim($dni));
        $dni =  str_replace(" ", "", $dni);

        $pas = strtolower(trim(substr($dni, -3, 3)));
        if ($pas == 'pas') return true;

        $letra = substr($dni, -1, 1);
        //if($letra=='_') return true;

        $numero = substr($dni, 0, 8);
        $numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);
        $modulo = $numero % 23;

        $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letra_correcta = substr($letras_validas, $modulo, 1);

        if ($letra_correcta != $letra) {
            //$this->form_validation->set_message('validar_dni', "EL DNI, NIE o Pasaporte NO es válido. Nota: para entrar núm pasaporte, se debe terminar con '_'");
            return false;
        } else {
            //$this->get_form_validation()->set_message('validar_dni',"EL DNI, NIE o Pasaporte NO es válido. Nota Para entrar núm pasaporte, se debe terminar con '_'");
            return true;
        }
    }



    public function inscripciones()
    {
        //var_dump($_POST);
        extract($_POST); //->$curso,$socio,$periodo[0]

        $datos['idCurso'] = $curso;
        $datos['idSocio'] = $socio;
        $datos['curso'] = $this->socios_model->getNombreCurso($curso);
        $datos['socio'] = $this->socios_model->getSocio($socio);
        //echo $datos['socio']->dni;
        $validez = $this->socios_model->validar_dni($datos['socio']->dni);
        //mensaje('dni '.$datos['socio']->dni);
        //mensaje('validez '.$validez);
        //echo 'dni '.$dni;
        if ($validez != 1) {
            // mensaje('validez dif 1='.$validez); 
            $dni = trim($datos['socio']->dni);
            $numSocio = $datos['socio']->num_socio;
            header('Location: ' . base_url() . 'index.php/' . 'talleres/seleccionar/3/' . $numSocio);
        }

        $datos['letraPeriodo'] = $periodo[0];
        $datos['periodo'] = $this->talleres_model->getTextoPeriodo($periodo[0]);
        $datos['numPeriodo'] = $this->talleres_model->getNumPeriodo($periodo[0]);
        //log_message('INFO','$datos[numPeriodo]  '.' '.$datos['numPeriodo']);
        if (!isset($socio)) {
            header('Location: ' . base_url() . 'index.php/' . 'talleres/seleccionar/1');
        }
        if (!isset($periodo[0])) {
            header('Location: ' . base_url() . 'index.php/' . 'talleres/seleccionar/2');
        }
        //echo  '$periodo[0] '.$periodo[0]; 
        $datos['talleres'] = $this->talleres_model->getTablaTalleres($curso, $socio, $periodo[0]);


        $this->load->helper('form');
        //var_dump($datos['talleres']);
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        // $datos['activeMenu']='Talleres';
        // $datos['activeSubmenu']='Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);

        $this->load->view('inscripciones', $datos);
        $this->load->view('myModalPregunta');
        $this->load->view('myModal');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function bajas()
    {
        extract($_POST);

        if (!isset($socio)) {
            header('Location: ' . base_url() . 'index.php/' . 'talleres/seleccionarBajas/1');
        }
        $datos['idCurso'] = $curso;
        $datos['idSocio'] = $socio;
        $datos['curso'] = $this->socios_model->getNombreCurso($curso);
        $datos['socio'] = $this->socios_model->getSocio($socio);

        if (!$this->socios_model->validar_dni($datos['socio']->dni)) {
            $dni = trim($datos['socio']->dni);
            $numSocio = $datos['socio']->num_socio;
            header('Location: ' . base_url() . 'index.php/' . 'talleres/seleccionarBajas/3/' . $numSocio);
        }

        $datos['curso'] = $this->socios_model->getNombreCurso($curso);
        $datos['socio'] = $socio . ' - ' . $this->socios_model->getNombreSocio($socio);
        $datos['tarjeta_rosa'] = $this->socios_model->getTarjetaRosa($socio);
        $datos['talleres'] = $this->talleres_model->getTablaTalleresInscritos($curso, $socio);

        // mensaje("desde bajas datos['socio'] ".$datos['socio']);
        // mensaje("desde bajas socio ".$socio);

        $datos['idCurso'] = $curso;
        $datos['idSocio'] = $socio;

        $this->load->helper('form');

        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Bajas de talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('bajas', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
        $this->load->view('myModal', $datos);
    }

    public function incrementarNumMaximo($taller)
    {
        $resultado = $this->talleres_model->incrementarNumMaximo($taller);
        echo json_encode($resultado);
    }

    public function checkIncrementarNumMaximo($taller)
    {
        $resultado = $this->talleres_model->checkIncrementarNumMaximo($taller);
        echo json_encode($resultado);
    }

    public function setUltimoPeriodo()
    {

        switch ($_POST['periodo']) {
            case 'C':
                $numPeriodo = 7;
                break;
            case 'T1':
                $numPeriodo = 4;
                break;
            case 'T2':
                $numPeriodo = 2;
                break;
            case 'T3':
                $numPeriodo = 1;
                break;
            default:
                $numPeriodo = 0;
        }
        $resultado = $this->talleres_model->setUltimoPeriodo($numPeriodo);
        echo json_encode($resultado);
    }

    public function seleccionar($reload = '', $numSocio = '', $dni = '')
    {
        //log_message('INFO',"seleccionar($reload='')");
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $datos['periodo'] = $this->talleres_model->getUltimoPeriodo();

        $this->load->model('socios_model');
        $datos['optionsSocios'] = $this->socios_model->getSociosOptions();
        $datos['reload'] = $reload;
        $datos['dni'] = $dni;
        $datos['numSocio'] = $numSocio;
        $datos['nombreSocio'] = "";
        if ($numSocio) {
            $socio = $this->socios_model->getSocio($numSocio);
            $datos['nombreSocio'] = $socio->nombre . ' ' . $socio->apellidos . ' (' . $socio->dni . ')';
            $datos['dni'] = $socio->dni;
        }
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        // $datos['activeMenu']='Talleres';
        // $datos['activeSubmenu']='Inscripción a talleres';
        //$datos['curso']=
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('seleccionar', $datos);
        $this->load->view('myModal', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }


    public function whatsapp($reload = '', $numSocio = '', $dni = '')
    {
        //log_message('INFO',"seleccionar($reload='')");
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $this->load->model('socios_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $datos['periodo'] = $this->talleres_model->getUltimoPeriodo();
        $datos['ultimoMensaje'] = $this->socios_model->getUltimoMensaje();

        $this->load->model('socios_model');
        $datos['optionsSocios'] = $this->socios_model->getSociosOptions();
        $datos['reload'] = $reload;
        $datos['dni'] = $dni;
        $datos['numSocio'] = $numSocio;
        $datos['nombreSocio'] = "";
        if ($numSocio) {
            $socio = $this->socios_model->getSocio($numSocio);
            $datos['nombreSocio'] = $socio->nombre . ' ' . $socio->apellidos . ' (' . $socio->dni . ')';
            $datos['dni'] = $socio->dni;
        }
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        // $datos['activeMenu']='Talleres';
        // $datos['activeSubmenu']='Inscripción a talleres';
        //$datos['curso']=
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('whatsapp', $datos);
        $this->load->view('myModal', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function seleccionarTalleresSocio()
    {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $this->load->model('socios_model');
        $datos['optionsSocios'] = $this->socios_model->getSociosOptions();

        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Listado inscripción talleres por socio';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('seleccionarTalleresSocio', $datos);
        $this->load->view('myModal', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function seleccionarBajas($reload = '', $numSocio = '', $dni = '')
    {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos'] = $this->talleres_model->getCursosOptions();
        $this->load->model('socios_model');
        $datos['optionsSocios'] = $this->socios_model->getSociosOptions();
        $datos['reload'] = $reload;
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['dni'] = $dni;
        $datos['numSocio'] = $numSocio;
        $datos['nombreSocio'] = "";
        if ($numSocio) {
            $socio = $this->socios_model->getSocio($numSocio);
            $datos['nombreSocio'] = $socio->nombre . ' ' . $socio->apellidos;
        }
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Seleccionar Bajas';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('seleccionarBajas', $datos);
        $this->load->view('myModal', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function getSociosFiltro()
    {
        $filtro = $_POST['filtro'];
        $this->load->model('socios_model');
        $optionsSocios = $this->socios_model->getSociosFiltro($filtro);
        echo json_encode($optionsSocios);
    }
    public function getSociosFiltroTodos()
    {
        $filtro = $_POST['filtro'];
        $this->load->model('socios_model');
        $optionsSocios = $this->socios_model->getSociosFiltroTodos($filtro);
        echo json_encode($optionsSocios);
    }

    function getTablaTalleresSocio()
    {
        $curso = $_POST['curso'];
        $socio = $_POST['socio'];
        $informacion = $this->socios_model->getTablaTalleresSocio($curso, $socio);
        echo json_encode($informacion);
    }

    public function getTalleresFiltro()
    {
        $filtro = $_POST['filtro'];
        $this->load->model('talleres_model');
        $optionsSocios = $this->talleres_model->getTalleresFiltro($filtro);
        echo json_encode($optionsSocios);
    }

    public function getTalleresFiltroCurso($curso)
    {
        $filtro = $_POST['filtro'];
        $this->load->model('talleres_model');
        $optionsSocios = $this->talleres_model->getTalleresFiltroCurso($filtro, $curso);
        echo json_encode($optionsSocios);
    }

    public function getTalleres()
    {
        $curso = $_POST['curso'];
        $this->load->model('talleres_model');
        $listaNombres = $this->talleres_model->getListaNombres($curso);
        echo "[" . implode(",", $listaNombres) . "]"; //json_encode($listaNombres);
    }

    public function horarioTaller($taller)
    {
        $sql = "SELECT d1.nombre_catalan as dia1,t.inicio_1,t.final_1, d2.nombre_catalan as dia2 ,t.inicio_2,t.final_2
                  FROM casal_talleres t
                  LEFT JOIN casal_dias_semana d1 ON d1.id=t.id_dia_semana_1
                  LEFT JOIN casal_dias_semana d2 ON d2.id=t.id_dia_semana_2
                  WHERE t.id='$taller'
                  ";
        $result = $this->db->query($sql)->row();
        $dia1 = "";
        $dia2 = "";
        if (!is_null($result->dia1))
            $dia1 = $result->dia1 . ' ' . substr($result->inicio_1, 0, 5) . '-' . substr($result->final_1, 0, 5);
        if (!is_null($result->dia2))
            $dia2 = $result->dia2 . ' ' . substr($result->inicio_2, 0, 5) . '-' . substr($result->final_2, 0, 5);
        return "$dia1 $dia2";
    }

    public function inicioFinalTaller($taller)
    {
        $sql = "SELECT CONCAT(day(t.fecha_inicio),' ', m1.mes_catalan,' ', year(t.fecha_inicio)) as fechaInicio,
                     CONCAT(day(t.fecha_final),' ', m2.mes_catalan,' ', year(t.fecha_final)) as fechaFinal
                  FROM casal_talleres t
                  LEFT JOIN casal_meses m1 ON m1.id=month(t.fecha_inicio)
                  LEFT JOIN casal_meses m2 ON m2.id=month(t.fecha_final)
                  WHERE t.id='$taller'
                  ";
        $result = $this->db->query($sql)->row();
        $fechaInicio = $result->fechaInicio;
        $fechaFinal = $result->fechaFinal;
        if (!is_null($fechaInicio) && !is_null($fechaFinal))
            return ""; //"Inici: $fechaInicio - Final: $fechaFinal"; 
        else
            return "";
    }

    public function inicioFinalTallerNuevo($taller, $letraPeriodo)
    {

        //buscamos curso del taller
        $row = $this->db->query("SELECT id_curso,id_dia_semana_1,id_dia_semana_2 FROM casal_talleres WHERE id='$taller'")->row();
        $idCurso = $row->id_curso;
        $dia1 = $row->id_dia_semana_1;
        $dia2 = $row->id_dia_semana_2;
        switch ($letraPeriodo) {
            case 'T1':
                $numPeriodo = 4;
                break;
            case 'T2':
                $numPeriodo = 2;
                break;
            case 'T3':
                $numPeriodo = 1;
                break;
            case 'C':
                $numPeriodo = 7;
                break;
            default:
                $numPeriodo = 0;
        }
        switch ($dia1) {
            case 1:
                $diaSemana1 = 'Dilluns';
                break;
            case 2:
                $diaSemana1 = 'Dimarts';
                break;
            case 3:
                $diaSemana1 = 'Dimecres';
                break;
            case 4:
                $diaSemana1 = 'Dijous';
                break;
            case 5:
                $diaSemana1 = 'Divendres';
                break;
            case 6:
                $diaSemana1 = 'Dissabte';
                break;
            case 5:
                $diaSemana1 = 'Diumenge';
                break;
            default:
                $diaSemana1 = '';
        }
        switch ($dia2) {
            case 1:
                $diaSemana2 = 'Dilluns';
                break;
            case 2:
                $diaSemana2 = 'Dimarts';
                break;
            case 3:
                $diaSemana2 = 'Dimecres';
                break;
            case 4:
                $diaSemana2 = 'Dijous';
                break;
            case 5:
                $diaSemana2 = 'Divendres';
                break;
            case 6:
                $diaSemana2 = 'Dissabte';
                break;
            case 5:
                $diaSemana2 = 'Diumenge';
                break;
            default:
                $diaSemana2 = '';
        }
        if ($numPeriodo == 7) {
            $sql = "SELECT * FROM casal_fechas_talleres WHERE id_curso='$idCurso' AND num_periodo='4' ";
            if ($this->db->query($sql)->num_rows() == 0) return "";
            $datosDias = $this->db->query($sql)->row();
            $dias = dias($datosDias->inicio, $datosDias->finaliza, $diaSemana1, $diaSemana2, [
                $datosDias->festivo0,
                $datosDias->festivo1,
                $datosDias->festivo2,
                $datosDias->festivo3,
                $datosDias->festivo4,
                $datosDias->festivo5,
                $datosDias->festivo6,
                $datosDias->festivo7,
                $datosDias->festivo8,
                $datosDias->festivo9,
                $datosDias->festivo10,
                $datosDias->festivo11,
            ]);
            if (count($dias)) {
                $fechaInicio = $dias[0];
                $sql = "SELECT * FROM casal_fechas_talleres WHERE id_curso='$idCurso' AND num_periodo='1' ";
                if ($this->db->query($sql)->num_rows() == 0) return "";
                $datosDias = $this->db->query($sql)->row();
                $dias = dias($datosDias->inicio, $datosDias->finaliza, $diaSemana1, $diaSemana2, [
                    $datosDias->festivo0,
                    $datosDias->festivo1,
                    $datosDias->festivo2,
                    $datosDias->festivo3,
                    $datosDias->festivo4,
                    $datosDias->festivo5,
                    $datosDias->festivo6,
                    $datosDias->festivo7,
                    $datosDias->festivo8,
                    $datosDias->festivo9,
                    $datosDias->festivo10,
                    $datosDias->festivo11,
                ]);
                if (count($dias)) {
                    $fechaFinal = $dias[count($dias) - 1];
                    return "Inici: $fechaInicio - Final: $fechaFinal";
                } else {
                    return "";
                }
            } else {
                return "";
            }
        }
        $sql = "SELECT * FROM casal_fechas_talleres WHERE id_curso='$idCurso' AND num_periodo='$numPeriodo' ";
        if ($this->db->query($sql)->num_rows() == 0) return "";
        $datosDias = $this->db->query($sql)->row();
        $dias = dias($datosDias->inicio, $datosDias->finaliza, $diaSemana1, $diaSemana2, [
            $datosDias->festivo0,
            $datosDias->festivo1,
            $datosDias->festivo2,
            $datosDias->festivo3,
            $datosDias->festivo4,
            $datosDias->festivo5,
            $datosDias->festivo6,
            $datosDias->festivo7,
            $datosDias->festivo8,
            $datosDias->festivo9,
            $datosDias->festivo10,
            $datosDias->festivo11,
        ]);


        foreach ($dias as $k => $v) {
            // mensaje($k.' '.$v);
        }
        if (count($dias)) {
            $fechaInicio = $dias[0];
            $fechaFinal = $dias[count($dias) - 1];
            return "Inici: $fechaInicio - Final: $fechaFinal";
        } else {
            return "";
        }
    }




    public function num2letrasCatalan($num, $fem = false, $dec = true)
    {
        log_message('INFO', '------------------------' . $num);

        $matuni[2]  = "dos";
        $matuni[3]  = "tres";
        $matuni[4]  = "quatre";
        $matuni[5]  = "cinc";
        $matuni[6]  = "sis";
        $matuni[7]  = "set";
        $matuni[8]  = "vuit";
        $matuni[9]  = "nou";

        $matuni[10] = "deu";
        $matuni[11] = "onze";
        $matuni[12] = "dotze";
        $matuni[13] = "tretze";
        $matuni[14] = "catorze";
        $matuni[15] = "quinze";
        $matuni[16] = "setze";
        $matuni[17] = "disset";
        $matuni[18] = "divuit";
        $matuni[19] = "dinou";
        $matuni[20] = "vint";

        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "quatre";
        $matunisub[5] = "cinc";
        $matunisub[6] = "sis";
        $matunisub[7] = "set";
        $matunisub[8] = "vuit";
        $matunisub[9] = "nou";

        $matdec[2] = "vint";
        $matdec[3] = "trenta";
        $matdec[4] = "quaranta";
        $matdec[5] = "cincuanta";
        $matdec[6] = "seixanta";
        $matdec[7] = "setanta";
        $matdec[8] = "vuitanta";
        $matdec[9] = "noranta";

        $matsub[3]  = 'mill';
        $matsub[5]  = 'bill';
        $matsub[7]  = 'mill';
        $matsub[9]  = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4]  = 'millions';
        $matmil[6]  = 'billions';
        $matmil[7]  = 'de billions';
        $matmil[8]  = 'millios de billions';
        $matmil[10] = 'trillions';
        $matmil[11] = 'de trillions';
        $matmil[12] = 'millions de trillions';
        $matmil[13] = 'de billions';
        $matmil[14] = 'billions de billions';
        $matmil[15] = 'de billions de trillions';
        $matmil[16] = 'millions de billions de trillions';

        //Zi hack

        $inicial = $num;
        if (strpos($num, '.')) {
            $float = explode('.', (string)$num);
        } else {
            $float[0] = $num;
        }

        $num = $float[0];
        if (isset($float[1])) {
            $float[1] = $inicial * 100 - $num * 100;  //obtenemos los dos primeros digitos decimales
        }

        $num = trim((string)$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else
            $neg = '';
        while (isset($num[0]) && $num[0] == '0') $num = substr($num, 1);
        if (isset($num[0]) && ($num[0] < '1' or $num[0] > 9)) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt) break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                } else

                    $ent .= $n;
            } else

                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' zero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        } else
            $fin = '';
        if ((int)$ent === 0) return 'zero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'un';
                $subcent = 's';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = '-i-' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            } else {
                $n3 = $num[2];
                if ($n3 != 0) $t = '-' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' cent' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . '-cent' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . '-cent' . $subcent . $t;
            }
            if ($sub == 1) {
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . 'ó';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ons';
            }
            if ($num == '000') $mils++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        //echo $float[1];
        $parteDecimal = isset($float[1]) ? $float[1] : 0;
        if ($parteDecimal > 0) {

            $parteDecimal = $this->num2letrasCatalan($parteDecimal);
        } else
            $parteDecimal = '';


        //$end_num=ucfirst($tex);
        if ($parteDecimal != '') {
            $tex = $tex . ' Euros, ' . substr($parteDecimal, 0,  strlen($parteDecimal) - 6) . ' cèntims';
        } else $tex = $tex . ' Euros';

        return ($tex);
    }

    public function volver($tipo)
    {
        switch ($tipo) {
            case 'pago':
                $texto = "AQUEST REBUT JA HA ESTAT EMÈS I, ES SUPOSA, COBRAT";
                break;
            case 'devolucion':
                $texto = "AQUEST COBRAMENT JA HA ESTAT EMÈS I, &nbsp; ES SUPOSA, &nbsp; TORNAT";
                break;
            default:
                $texto = "ERROR. &nbsp; CONSULTAR ADMINISTRADOR";
        }
        $datos['tipo'] = $texto;
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $this->load->view('templates/header', $datos);
        $datos['activeMenu'] = 'Talleres';
        $datos['activeSubmenu'] = 'Inscripciones a talleres';
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('volver', $datos);
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
        /*
        echo "<h3>$texto</h3>";
        echo "<h4>NO se puede emitir un duplicado</h4>";
        echo form_open('talleres/seleccionar');
        echo '<button type="submit" class="btn btn-success" id="volver">Volver</button>';
        echo form_close();
        return;
         * 
         */
    }

    public function registrarPago($taller, $socio, $valor, $importe)
    {

        if (strpos($taller, '(ad)') !== false) {

            echo 'true';
        }


        $sql = "SELECT trimestres, trimestres_pago,pagado FROM casal_asistentes WHERE id_socio='$socio' AND id_taller='$taller'";
        if ($this->db->query($sql)->num_rows()) {
            $trimestres_pago = intval($this->db->query($sql)->row()->trimestres_pago);
            $trimestres = intval($this->db->query($sql)->row()->trimestres);
            $pagado = $this->db->query($sql)->row()->pagado;
            if (!($trimestres_pago & $valor) == $valor) {
                $this->volver('pago');
                return false;
            } else {
                $trimestres = $trimestres | $trimestres_pago;
                $trimestres_pago = $trimestres_pago ^ $valor;
                $pagado += $importe;
                $hoy = date("Y-m-d");
                $sql = "UPDATE casal_asistentes SET trimestres='$trimestres', pagado='$pagado', fecha_pago='$hoy', trimestres_pago='$trimestres_pago' WHERE id_socio='$socio' AND id_taller='$taller'";
                $this->db->query($sql);
            }
        } else {
        }
        return true;
    }

    public function registrarPagoAgrupacion($nombreTaller, $taller, $socio, $valor, $importe)
    {
        if ($_SESSION['pagado'] == true) {
            $this->volver('pago');
            return;
        }
        $hoy = date('Y-m-d');


        if (strpos($nombreTaller, '(ad)') !== false) {
            //se trata de un taller en que se modifica el precio
            //no se chequea se esta emitido el recibo
            //la comprobación se hace con el primer taller que es el nuevo
            $sql = "SELECT trimestres, trimestres_pago,pagado FROM casal_asistentes WHERE id_socio='$socio' AND id_taller='$taller' AND ($valor=(trimestres & $valor) OR $valor=(trimestres_pago & $valor))";
            //echo $sql.'  (ad)<br>';

            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes SET pagado='$importe', id_socio='$socio', id_taller='$taller', trimestres='$valor', fecha_pago='$hoy'";
                $this->db->query($sql);
                return true;
            } else {
                $trimestres_pago = intval($this->db->query($sql)->row()->trimestres_pago);
                $trimestres = intval($this->db->query($sql)->row()->trimestres);
                $trimestres = $trimestres | $valor;
                $pagado = $this->db->query($sql)->row()->pagado;
                $pagado += $importe;
                $trimestres_pago_nuevo = $trimestres_pago ^ $valor;
                $sql = "UPDATE casal_asistentes SET trimestres='$trimestres', pagado='$pagado',fecha_pago='$hoy' WHERE id_socio='$socio' AND id_taller='$taller' AND ($valor=(trimestres & $valor) OR $valor=(trimestres_pago & $valor))";
                $this->db->query($sql);
                return true;
            }
        }

        $sql = "SELECT trimestres, trimestres_pago,pagado FROM casal_asistentes WHERE id_socio='$socio' AND id_taller='$taller' AND ($valor=(trimestres & $valor) OR $valor=(trimestres_pago & $valor))";
        //echo $sql.'<br>';

        if ($this->db->query($sql)->num_rows()) {
            $trimestres_pago = intval($this->db->query($sql)->row()->trimestres_pago);
            $trimestres = intval($this->db->query($sql)->row()->trimestres);
            $pagado = $this->db->query($sql)->row()->pagado;
            $trimestres = $trimestres | $valor;
            $trimestres_pago_nuevo = $trimestres_pago ^ $valor;
            $pagado += $importe;
            $hoy = date("Y-m-d");
            $sql = "UPDATE casal_asistentes SET trimestres='$trimestres', pagado='$pagado', fecha_pago='$hoy', trimestres_pago='$trimestres_pago_nuevo' WHERE id_socio='$socio' AND id_taller='$taller' AND ($valor=(trimestres & $valor) OR $valor=(trimestres_pago & $valor))";
            $this->db->query($sql);
        } else {
            $trimestres = $valor;
            $sql = "INSERT INTO casal_asistentes SET trimestres='$trimestres', pagado='$importe', fecha_pago='$hoy', id_socio='$socio', id_taller='$taller' ";
            $this->db->query($sql);
            //echo 'ERROR. Informar al administrador';
        }

        return true;
    }




    public function registrarDevolucion($taller, $socio, $valor, $importe)
    {
        $sql = "SELECT trimestres,trimestres_pago,pagado FROM casal_asistentes WHERE id_socio='$socio' AND id_taller='$taller'";
        //echo $sql.'<br>';
        if ($this->db->query($sql)->num_rows()) {
            $trimestres_pago = intval($this->db->query($sql)->row()->trimestres_pago);
            $trimestres = intval($this->db->query($sql)->row()->trimestres);
            $pagado = $this->db->query($sql)->row()->pagado;
            if (!($trimestres_pago & $valor) == $valor) {
                $this->volver('devolucion');
                return false;
            } else {
                $hoy = date("Y-m-d");
                //echo 'Hola'.$hoy.' '.$trimestres.' '.$trimestres_pago. ' <br>';
                $trimestres = $trimestres ^ $trimestres_pago;
                //echo $trimestres;
                $trimestres_pago = $trimestres_pago ^ $valor;
                $pagado -= $importe;
                $sql = "UPDATE casal_asistentes SET trimestres='$trimestres', pagado='$pagado', fecha_devolucion='$hoy', trimestres_pago='$trimestres_pago' WHERE id_socio='$socio' AND id_taller='$taller'";
                $this->db->query($sql);
            }
        } else {
        }
        return true;
    }

    /*
     * Se ha puesto en maba_helper 
     
    public function getLetraCasal(){
        $casal=$_SESSION["tituloCasal"];
       
        if(strpos($casal,"ISIDRET")) return "I";
        if(strpos($casal,"JOAN CASANELLES")) return "J";
        if(strpos($casal,"MARAGALL")) return "M";
        if(strpos($casal,"CLOT")) return "C";
        if(strpos($casal,"QUATRE CANTONS")) return "Q";
        if(strpos($casal,"SANT MARTÍ")) return "S";
        if(strpos($casal,"TAULAT CAN SALADRIGAS")) return "T";
        if(strpos($casal,"VERNEDA")) return "V";
        return "-";
        
    }
     * */


    public function getNumRecibo()
    {
        if ($this->db->query("SELECT id FROM casal_recibos ORDER BY id DESC LIMIT 1")->num_rows() == 0) return 1;
        return $this->db->query("SELECT id FROM casal_recibos ORDER BY id DESC LIMIT 1")->row()->id + 1;
    }

    public function recibosInscripcionesNuevo()
    {
        //var_dump($_POST);
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $listaEsperaTaller = array();
        $listaEsperaOrden = array();
        $listaEsperaPeriodo = array();
        $numLineas = 0;

        extract($_POST);
        //var_dump($tipos_talleres);
        //echo '$cobroTarjetaProfesionales = '.isset($cobroTarjetaProfesionales);
        //echo '$cobroTarjetaVoluntarios = '.isset($cobroTarjetaVoluntarios);

        //var_dump($_POST);
        //echo 'hola';
        //echo '$resultNuevo '.$resultNuevo;


        //var_dump($listaEsperaTaller);
        //var_dump($listaEsperaOrden);

        //como se han registrado, se borra los registros de incrementos
        $sql = "DELETE FROM casal_talleres_incrementados WHERE 1";
        $this->db->query($sql);

        $id_recibo = $this->db->query("SELECT id FROM `casal_recibos` ORDER BY id DESC LIMIT 1")->row()->id + 1;

        //grabar listas espera
        if (isset($listaEsperaTaller))
            $this->talleres_model->grabarListaEsperaTalleres($socio, $listaEsperaTaller, $listaEsperaOrden, $listaEsperaPeriodo, $id_recibo);

        //var_dump($resultNuevo);
        $this->talleres_model->grabarAsistentesTalleresNuevo($resultNuevo, $id_recibo);
        // var_dump($resultNuevo);

        $datosSocio = $this->socios_model->getSocio($socio);
        // echo '$tarjetaRosa '.$tarjetaRosa;

        $tR = ($tarjetaRosa) ? 'Sí' : 'No';
        //echo '$tarjetaRosa $tR '.$tR;
        $this->socios_model->setTarjetaRosa($socio, $tR);

        define('EURO', chr(128));

        //$this->load->library('pdf');
        //$this->pdf = new Pdf();

        $this->load->library('PDF_JavaScript');
        $this->pdf = new PDF_JavaScript();



        $this->pdf->setSubtitulo('Talleres curs: ' . utf8_decode($cursoNombre));
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Recibo");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);
        $m = false;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);

        $dias = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
        $mesos = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
        //echo "".$dias[date('w')]." ".date('d')." de ".$mesos[date('n')-1]." '".date('Y');
        $pag = 128;
        $maxNumLineas = 12;
        for ($i = 0; $i < 2; $i++) {
            $hoy = date('d') . " de " . $mesos[date('n') - 1] . " de " . date('Y');
            if ($numLineas < $maxNumLineas) {
                $this->pdf->SetY(26 + $i * $pag);
            } else {
                $this->pdf->SetY(26 + 0 * $pag);
            }

            $letra = getLetraCasal();
            $numRecibo = $this->getNumRecibo();
            $this->pdf->Cell(0, 7, utf8_decode('Rebut núm: ' . $letra . ' ' . $numRecibo), $m, 1, 'R', '0');

            $this->pdf->Cell(0, 1, utf8_decode('Barcelona, ' . $hoy), $m, 1, 'R', '0');

            if ($numLineas < $maxNumLineas)
                $this->pdf->SetY(36 + $i * $pag);
            else
                $this->pdf->SetY(36 + 0
                    * $pag);
            $this->pdf->SetFont('Arial', '', 18);
            $this->pdf->Cell(38, 10, 'REBUT de: ', $m, 0, 'L', '0');

            $this->pdf->SetFont('Arial', 'B', 18);
            $this->pdf->Cell(20, 10, utf8_decode($socioNombre), $m, 0, 'L', 0);
            $this->pdf->SetFont('Arial', '', 12);
            $this->pdf->Ln();
            $telefono = "";
            $telefono_1 = $datosSocio->telefono_1;
            $telefono_2 = $datosSocio->telefono_2;
            if ($telefono_1 || $telefono_2) $telefono = 'Teléfono: ';
            if ($telefono_1) $telefono .= $telefono_1;
            if ($telefono_2) $telefono .= ' - ' . $telefono_2;
            $email=$datosSocio->email;
            $telefono.=' email: '.$email;
            $tR = "";
            $this->pdf->Cell(0, 6, utf8_decode($telefono), 0, 1, 'L', 0);
            if ($tarjetaRosa) {
                $tR = "Tarjeta Rosa";
                $this->pdf->Cell(0, 5, utf8_decode($tR), 0, 1, 'L', 0);
            }


            $this->pdf->Cell(38, 8, 'la quantitat de: ', $m, 0, 'L', 0);
            $this->pdf->SetFont('Arial', '', 14);

            $this->pdf->Cell(0, 8, utf8_decode(ucfirst($this->num2letrasCatalan($totalAPagar))), $m, 1, 'L');
            $this->pdf->Cell(0, 8, utf8_decode('en concepte de INSCRIPCIÓ tallers: '), $m, 1, 'L');
            foreach ($CNombres as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $id_talleres[$k];
                $v = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;
                $tipo_taller = $this->db->query("SELECT tipo_taller FROM casal_talleres WHERE id='$id'")->row()->tipo_taller;
                if ($tipo_taller == 'Voluntari' && $trimestres[$k] == 'T1') $trimestres[$k] = 'C';
                $nombre = $v . " - (" . $trimestres[$k] . ")  " . number_format($importes[$k],2) . " €";

                $this->pdf->SetFont('Arial', '', 14);
                $v = iconv('UTF-8', 'CP1252', $v);

                $this->pdf->Cell(80, 8, '- ' . $v, $m, 0, 'L');
                $this->pdf->Cell(15, 8, $trimestres[$k], $m, 0, 'L');
                $this->pdf->Cell(20, 8, iconv('UTF-8', 'CP1252', number_format($importes[$k],2) . ' €'), $m, 0, 'R');
                $numLineas++;

                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($id_talleres[$k]) . ' ', $m, 1, 'R');


                $inicioFinal = $this->inicioFinalTallerNuevo($id_talleres[$k], $trimestres[$k]);
                // mensaje('$id_talleres[$k] '.$id_talleres[$k]);
                // mensaje('$trimestres[$k] '.$trimestres[$k]);
                // mensaje('$inicioFinal '.$inicioFinal);
                if ($inicioFinal != "") {
                    $this->pdf->Cell(0, 4, iconv('UTF-8', 'CP1252', $inicioFinal . ' '), $m, 1, 'R');
                    $numLineas++;
                }
            }
            foreach ($T1Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T1[$k],$socio,4,$T1Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 1: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T1[$k]) . ' ', $m, 1, 'R');
                $numLineas++;
            }
            foreach ($T2Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T2[$k],$socio,2,$T2Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 2: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T2[$k]) . ' ', $m, 1, 'R');
                $numLineas++;
            }
            foreach ($T3Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T3[$k],$socio,1,$T3Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 3: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T3[$k]) . ' ', $m, 1, 'R');
                $numLineas++;
            }
            $this->pdf->SetFont('Arial', '', 14);
            if (count($listaEsperaTaller))
                $this->pdf->Cell(80, 8, 'En lista espera ', $m, 1, 'L');
            foreach ($listaEsperaTaller as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $v;
                $nombre = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;

                $this->pdf->SetFont('Arial', '', 14);
                $nombre = iconv('UTF-8', 'CP1252', $nombre);
                $this->pdf->Cell(80, 8, '- ' . $nombre, $m, 0, 'L');
                $this->pdf->Cell(15, 8, 'orden: ' . $listaEsperaOrden[$k], $m, 0, 'L');

                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($id) . ' ', $m, 1, 'R');
                $numLineas++;
                $inicioFinal = "";
                if (isset($id_talleres[$k]))
                    $inicioFinal = $this->inicioFinalTallerNuevo($id_talleres[$k], $trimestres[$k]);
                if ($inicioFinal != "") {
                    $this->pdf->Cell(0, 4, iconv('UTF-8', 'CP1252', $inicioFinal . ' '), $m, 1, 'R');
                    // if($this->inicioFinalTaller($id)!="")
                    //     $this->pdf->Cell(0,4,iconv('UTF-8', 'CP1252',$this->inicioFinalTaller($id)).' ',$m,1,'R');
                    $numLineas++;
                }
            }
            $this->pdf->SetFont('Arial', 'BI', 18);
            $this->pdf->Cell(40, 10, utf8_decode('Euros: ' . $totalAPagar), '0', 1, 'L', '0');


            if ($cobroTarjeta != 0) {
                $this->pdf->SetFont('Arial', 'I', 14);
                $this->pdf->Cell(40, 10, iconv('UTF-8', 'CP1252', '(Metàl.lic: ' . $cobroMetalico . ' €; Targeta: ' . $cobroTarjeta . ' €)'), '0', 0, 'L', '0');
            }
            $this->pdf->SetFont('Arial', '', 10);
            if ($i) $r = " - Casal";
            else $r = " - Usuari/usuària";
            $this->pdf->Cell(0, 10, utf8_decode('COBRAT/INSCRIT' . $r), '$m', 0, 'R', '0');
            // $this->pdf->Ln();
            // $this->pdf->Ln();

            if (!$i && $numLineas < $maxNumLineas)
                $this->pdf->Line(0, 148, 250, 148); //154
            elseif (!$i) {
                $this->pdf->addPage();
            }
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
        //$this->pdf->Output("Recibo", 'D');

        $porciones = explode(" ", utf8_decode($socioNombre));
        if (isset($porciones[0])) $porciones[0] = substr($porciones[0], 0, 5);
        else $porciones[0] = "";
        if (isset($porciones[1])) $porciones[1] = substr($porciones[1], 0, 5);
        else $porciones[1] = "";

        $recibo =  'RP ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[0]) . ' ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[1]);
        if (trim($recibo) == "") $recibo = "RP Recibo";
        $recibo .= ' ' . date("d-m-Y His");

        $recibo .= $id_recibo;
        $recibo .= '.pdf';
        $recibo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $recibo);

        $recibo = utf8_decode($recibo);

        if ($_SESSION['pagado'] == false) {
            $id_recibo = $this->socios_model->registrarRecibo($socio, $totalAPagar, $recibo);
            $datosLineas = json_decode($resultNuevo);
            //echo '$datosLineas<br>';
            //var_dump($datosLineas);

            foreach ($datosLineas as $k => $v) {
                $tarjeta = 0;
                $metalico = 0;
                if ($v->tipo_taller == "Voluntari") {
                    if (isset($cobroTarjetaVoluntarios)) {
                        $tarjeta = $v->pagado;
                    } else {
                        $metalico = $v->pagado;
                    }
                }
                if ($v->tipo_taller == "Professional") {
                    if (isset($cobroTarjetaProfesionales)) {
                        $tarjeta = $v->pagado;
                    } else {
                        $metalico = $v->pagado;
                    }
                }


                $this->talleres_model->registrarLineasRecibo(
                    $id_recibo,
                    $socio,
                    $v->id_taller,
                    $v->trimestres,
                    $v->tipo_taller,
                    $v->pagado,
                    $tarjeta,
                    $metalico
                );
            }
        }

        $this->pdf->AutoPrint();
        $this->pdf->Output('recibos/' . $recibo, 'F');
        $this->pdf->Output($recibo, 'D');

        $_SESSION['pagado'] = true;

        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }



    public function recibosInscripciones()
    {
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $listaEsperaTaller = array();
        $listaEsperaOrden = array();
        $listaEsperaPeriodo = array();

        extract($_POST);
        //var_dump($tipos_talleres);
        //echo '$cobroTarjetaProfesionales = '.isset($cobroTarjetaProfesionales);
        //echo '$cobroTarjetaVoluntarios = '.isset($cobroTarjetaVoluntarios);

        //var_dump($_POST);
        //echo 'hola';
        //echo '$resultNuevo '.$resultNuevo;


        //var_dump($listaEsperaTaller);
        //var_dump($listaEsperaOrden);

        //como se han registrado, se borra los registros de incrementos
        $sql = "DELETE FROM casal_talleres_incrementados WHERE 1";
        $this->db->query($sql);

        //grabar listas espera
        if (isset($listaEsperaTaller))
            $this->talleres_model->grabarListaEsperaTalleres($socio, $listaEsperaTaller, $listaEsperaOrden, $listaEsperaPeriodo);

        $this->talleres_model->grabarAsistentesTalleres($resultNuevo);
        // var_dump($resultNuevo);

        $datosSocio = $this->socios_model->getSocio($socio);
        // echo '$tarjetaRosa '.$tarjetaRosa;

        $tR = ($tarjetaRosa) ? 'Sí' : 'No';
        //echo '$tarjetaRosa $tR '.$tR;
        $this->socios_model->setTarjetaRosa($socio, $tR);

        define('EURO', chr(128));

        $this->load->library('pdf');

        $this->pdf = new Pdf();


        $this->pdf->setSubtitulo('Talleres curs: ' . utf8_decode($cursoNombre . ' / ' . $periodo));
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Recibo");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);
        $m = false;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);

        $dias = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
        $mesos = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
        //echo "".$dias[date('w')]." ".date('d')." de ".$mesos[date('n')-1]." '".date('Y');
        $pag = 132;
        for ($i = 0; $i < 2; $i++) {
            $hoy = date('d') . " de " . $mesos[date('n') - 1] . " de " . date('Y');
            $this->pdf->SetY(26 + $i * $pag);
            $this->pdf->Cell(0, 7, utf8_decode('Barcelona, ' . $hoy), $m, 1, 'R', '0');
            $this->pdf->SetY(36 + $i * $pag);
            $this->pdf->SetFont('Arial', '', 18);
            $this->pdf->Cell(38, 10, 'REBUT de: ', $m, 0, 'L', '0');

            $this->pdf->SetFont('Arial', 'B', 18);
            $this->pdf->Cell(20, 10, utf8_decode($socioNombre), $m, 0, 'L', 0);
            $this->pdf->SetFont('Arial', '', 12);
            $this->pdf->Ln();
            $telefono = "";
            $telefono_1 = $datosSocio->telefono_1;
            $telefono_2 = $datosSocio->telefono_2;
            if ($telefono_1 || $telefono_2) $telefono = 'Teléfono: ';
            if ($telefono_1) $telefono .= $telefono_1;
            if ($telefono_2) $telefono .= ' - ' . $telefono_2;
            $email=$datosSocio->email;
            $telefono.=' email: '.$email;
            $tR = "";
            $this->pdf->Cell(0, 6, utf8_decode($telefono), 0, 1, 'L', 0);
            if ($tarjetaRosa) {
                $tR = "Tarjeta Rosa";
                $this->pdf->Cell(0, 5, utf8_decode($tR), 0, 1, 'L', 0);
            }


            $this->pdf->Cell(38, 8, 'la quantitat de: ', $m, 0, 'L', 0);
            $this->pdf->SetFont('Arial', '', 14);

            $this->pdf->Cell(0, 8, utf8_decode(ucfirst($this->num2letrasCatalan($totalAPagar))), $m, 1, 'L');
            $this->pdf->Cell(0, 8, utf8_decode('en concepte de INSCRIPCIÓ tallers: '), $m, 1, 'L');
            foreach ($CNombres as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $id_talleres[$k];
                $v = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;
                $nombre = $v . " - (" . $trimestres[$k] . ")  " . number_format($importes[$k],2) . " €";

                $this->pdf->SetFont('Arial', '', 14);
                $v = iconv('UTF-8', 'CP1252', $v);

                $this->pdf->Cell(80, 8, '- ' . $v, $m, 0, 'L');
                $this->pdf->Cell(15, 8, $trimestres[$k], $m, 0, 'L');
                $this->pdf->Cell(20, 8, iconv('UTF-8', 'CP1252', number_format($importes[$k],2) . ' €'), $m, 0, 'R');


                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($id_talleres[$k]) . ' ', $m, 1, 'R');

                $inicioFinal = $this->inicioFinalTallerNuevo($id_talleres[$k], $trimestres[$k]);
                if ($inicioFinal != "")
                    $this->pdf->Cell(0, 4, iconv('UTF-8', 'CP1252', $inicioFinal . ' '), $m, 1, 'R');
                // if($this->inicioFinalTaller($id_talleres[$k])!="")
                //     $this->pdf->Cell(0,4,iconv('UTF-8', 'CP1252',$this->inicioFinalTaller($id_talleres[$k])).' ',$m,1,'R');
            }
            foreach ($T1Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T1[$k],$socio,4,$T1Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 1: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T1[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T2Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T2[$k],$socio,2,$T2Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 2: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T2[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T3Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T3[$k],$socio,1,$T3Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 3: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T3[$k]) . ' ', $m, 1, 'R');
            }
            $this->pdf->SetFont('Arial', '', 14);
            if (count($listaEsperaTaller))
                $this->pdf->Cell(80, 8, 'En lista espera ', $m, 1, 'L');
            foreach ($listaEsperaTaller as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $v;
                $nombre = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;

                $this->pdf->SetFont('Arial', '', 14);
                $nombre = iconv('UTF-8', 'CP1252', $nombre);
                $this->pdf->Cell(80, 8, '- ' . $nombre, $m, 0, 'L');
                $this->pdf->Cell(15, 8, 'orden: ' . $listaEsperaOrden[$k], $m, 0, 'L');

                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($id) . ' ', $m, 1, 'R');

                $inicioFinal = $this->inicioFinalTallerNuevo($id_talleres[$k], $trimestres[$k]);
                if ($inicioFinal != "")
                    $this->pdf->Cell(0, 4, iconv('UTF-8', 'CP1252', $inicioFinal . ' '), $m, 1, 'R');
                // if($this->inicioFinalTaller($id)!="")
                //     $this->pdf->Cell(0,4,iconv('UTF-8', 'CP1252',$this->inicioFinalTaller($id)).' ',$m,1,'R');
            }
            $this->pdf->SetFont('Arial', 'BI', 18);
            $this->pdf->Cell(40, 10, utf8_decode('Euros: ' . $totalAPagar), '0', 1, 'L', '0');


            if ($cobroTarjeta != 0) {
                $this->pdf->SetFont('Arial', 'I', 14);
                $this->pdf->Cell(40, 10, iconv('UTF-8', 'CP1252', '(Metàl.lic: ' . $cobroMetalico . ' €; Targeta: ' . $cobroTarjeta . ' €)'), '0', 0, 'L', '0');
            }
            $this->pdf->SetFont('Arial', '', 10);
            if ($i) $r = " - Casal";
            else $r = " - Usuari/usuària";
            $this->pdf->Cell(0, 10, utf8_decode('COBRAT/INSCRIT' . $r), '$m', 0, 'R', '0');
            $this->pdf->Ln();
            $this->pdf->Ln();

            if (!$i) $this->pdf->Line(0, 154, 250, 154);
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
        //$this->pdf->Output("Recibo", 'D');

        $porciones = explode(" ", utf8_decode($socioNombre));
        if (isset($porciones[0])) $porciones[0] = substr($porciones[0], 0, 5);
        else $porciones[0] = "";
        if (isset($porciones[1])) $porciones[1] = substr($porciones[1], 0, 5);
        else $porciones[1] = "";

        $recibo =  'RP ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[0]) . ' ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[1]);
        if (trim($recibo) == "") $recibo = "RP Recibo";
        $recibo .= ' ' . date("d-m-Y His");
        $lastId = $this->db->query("SELECT id FROM `casal_recibos` ORDER BY id DESC LIMIT 1")->row()->id;
        $recibo .= $lastId++;
        $recibo .= '.pdf';
        $recibo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $recibo);

        $recibo = utf8_decode($recibo);

        if ($_SESSION['pagado'] == false) {
            $id_recibo = $this->socios_model->registrarRecibo($socio, $totalAPagar, $recibo);
            $datosLineas = json_decode($resultNuevo);
            //echo '$datosLineas<br>';
            //var_dump($datosLineas);

            foreach ($datosLineas as $k => $v) {
                $tarjeta = 0;
                $metalico = 0;
                if ($v->tipo_taller == "Voluntari") {
                    if (isset($cobroTarjetaVoluntarios)) {
                        $tarjeta = $v->pagado;
                    } else {
                        $metalico = $v->pagado;
                    }
                }
                if ($v->tipo_taller == "Professional") {
                    if (isset($cobroTarjetaProfesionales)) {
                        $tarjeta = $v->pagado;
                    } else {
                        $metalico = $v->pagado;
                    }
                }


                $this->talleres_model->registrarLineasRecibo(
                    $id_recibo,
                    $socio,
                    $v->id_taller,
                    $v->trimestres,
                    $v->tipo_taller,
                    $v->pagado,
                    $tarjeta,
                    $metalico
                );
            }
        }


        $this->pdf->Output('recibos/' . $recibo, 'F');


        $this->pdf->Output($recibo, 'D');
        $_SESSION['pagado'] = true;
    }

    public function recibosBajas()
    {
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();

        extract($_POST);

        $this->talleres_model->grabarAsistentesTalleres($resultNuevo);

        if (isset($id_talleres))
            $this->talleres_model->bajasListaEspera($socio, $id_talleres, $trimestres);

        $datosSocio = $this->socios_model->getSocio($socio);

        define('EURO', chr(128));

        $this->load->library('pdf');

        $this->pdf = new Pdf();


        $this->pdf->setSubtitulo('Talleres curs: ' . $cursoNombre);
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Recibo");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);
        $m = false;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);

        $dias = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
        $mesos = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
        //echo "".$dias[date('w')]." ".date('d')." de ".$mesos[date('n')-1]." '".date('Y');
        $pag = 132;
        for ($i = 0; $i < 2; $i++) {
            $hoy = date('d') . " de " . $mesos[date('n') - 1] . " de " . date('Y');
            $this->pdf->SetY(26 + $i * $pag);
            $this->pdf->Cell(0, 7, utf8_decode('Barcelona, ' . $hoy), $m, 1, 'R', '0');
            $this->pdf->SetY(36 + $i * $pag);
            $this->pdf->SetFont('Arial', '', 18);
            $this->pdf->Cell(48, 10, iconv('UTF-8', 'CP1252', 'DEVOLUCIÓ A: '), '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', 'B', 18);
            $this->pdf->Cell(20, 10, utf8_decode($socioNombre), '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 14);
            $this->pdf->Ln();
            $this->pdf->Cell(38, 10, 'la quantitat de: ', '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 14);
            $cantidad = -$totalAPagar;
            $this->pdf->Cell(0, 10, utf8_decode(ucfirst($this->num2letrasCatalan($cantidad))), $m, 1, 'L');
            $this->pdf->Cell(0, 10, utf8_decode('en concepte de BAIXA dels tallers: '), $m, 1, 'L');
            foreach ($CNombres as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $id_talleres[$k];
                $v = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;
                $nombre = $v . " - (" . $trimestres[$k] . ")  " . (number_format(-$importes[$k],2)) . " €";

                $this->pdf->SetFont('Arial', '', 14);
                $v = iconv('UTF-8', 'CP1252', $v);
                //$nombre=iconv('UTF-8', 'CP1252',$nombre);
                $this->pdf->Cell(80, 8, '- ' . $v, $m, 0, 'L');
                if ($trimestres[$k] == -1) $trimestres[$k] = "Espera";
                $this->pdf->Cell(15, 8, $trimestres[$k], $m, 0, 'L');
                $this->pdf->Cell(20, 8, iconv('UTF-8', 'CP1252', number_format(-$importes[$k],2) . ' €'), $m, 0, 'R');

                $this->pdf->SetFont('Arial', '', 9);

                $this->pdf->Cell(0, 8, $this->horarioTaller($id_talleres[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T1Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T1[$k],$socio,4,$T1Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 1: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T1[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T2Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T2[$k],$socio,2,$T2Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 2: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T2[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T3Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T3[$k],$socio,1,$T3Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 3: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T3[$k]) . ' ', $m, 1, 'R');
            }

            $this->pdf->SetFont('Arial', 'BI', 18);
            $this->pdf->Cell(40, 10, utf8_decode('Euros: ' . $cantidad), '0', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 10);
            if ($i) $r = " - Casal";
            else $r = " - Usuari/usuària";
            $this->pdf->Cell(0, 10, iconv('UTF-8', 'CP1252', 'DEVOLUCIÓ' . $r), '$m', 0, 'R', '0');
            $this->pdf->Ln();
            $this->pdf->Ln();

            if (!$i) $this->pdf->Line(0, 154, 250, 154);
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
        //$this->pdf->Output("Recibo", 'D');

        $porciones = explode(" ", utf8_decode($socioNombre));
        if (isset($porciones[0])) $porciones[0] = substr($porciones[0], 0, 5);
        else $porciones[0] = "";
        if (isset($porciones[1])) $porciones[1] = substr($porciones[1], 0, 5);
        else $porciones[1] = "";

        $recibo =  'RD ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[0]) . ' ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[1]);
        if (trim($recibo) == "") $recibo = "RD Recibo";
        $recibo .= ' ' . date("d-m-Y His");
        $lastId = $this->db->query("SELECT id FROM `casal_recibos` ORDER BY id DESC LIMIT 1")->row()->id;
        $recibo .= $lastId++;
        $recibo .= '.pdf';
        $recibo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $recibo);

        $recibo = utf8_decode($recibo);
        if ($_SESSION['pagado'] == false) {
            $id_recibo = $this->socios_model->registrarRecibo($socio, $totalAPagar, $recibo);
            $datosLineas = json_decode($resultNuevo);


            //echo '$datosLineas<br>';
            //var_dump($datosLineas);

            foreach ($datosLineas as $k => $v) {
                $this->talleres_model->registrarLineasRecibo(
                    $id_recibo,
                    $socio,
                    $v->id_taller,
                    $v->trimestres,
                    $v->tipo_taller,
                    $v->pagado,
                    $v->tarjeta,
                    $v->pagado
                );
            }
        }
        $this->pdf->Output('recibos/' . $recibo, 'F');

        $this->pdf->Output($recibo, 'D');
        $_SESSION['pagado'] = true;
    }

    public function recibosBajasNuevo()
    {
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();

        extract($_POST);

        $id_recibo = $this->db->query("SELECT id FROM `casal_recibos` ORDER BY id DESC LIMIT 1")->row()->id + 1;
        $this->talleres_model->grabarAsistentesTalleresBajasNuevo($resultNuevo, $id_recibo);

        if (isset($id_talleres))
            $this->talleres_model->bajasListaEspera($socio, $id_talleres, $trimestres, $id_recibo);

        $datosSocio = $this->socios_model->getSocio($socio);

        define('EURO', chr(128));

        //$this->load->library('pdf');
        //$this->pdf = new Pdf();

        $this->load->library('PDF_JavaScript');
        $this->pdf = new PDF_JavaScript();


        $this->pdf->setSubtitulo('Talleres curs: ' . $cursoNombre);
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
     * el color de relleno predeterminado
     */
        $this->pdf->SetTitle("Recibo");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);
        $m = false;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);

        $dias = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
        $mesos = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
        //echo "".$dias[date('w')]." ".date('d')." de ".$mesos[date('n')-1]." '".date('Y');
        $pag = 132;
        for ($i = 0; $i < 2; $i++) {
            $hoy = date('d') . " de " . $mesos[date('n') - 1] . " de " . date('Y');
            $this->pdf->SetY(26 + $i * $pag);

            $letra = getLetraCasal();
            $numRecibo = $this->getNumRecibo();
            $this->pdf->Cell(0, 7, utf8_decode('Rebut núm: ' . $letra . ' ' . $numRecibo), $m, 1, 'R', '0');

            $this->pdf->Cell(0, 1, utf8_decode('Barcelona, ' . $hoy), $m, 1, 'R', '0');
            $this->pdf->SetY(36 + $i * $pag);
            $this->pdf->SetFont('Arial', '', 18);
            $this->pdf->Cell(48, 10, iconv('UTF-8', 'CP1252', 'DEVOLUCIÓ A: '), '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', 'B', 18);
            $this->pdf->Cell(20, 10, utf8_decode($socioNombre), '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 14);
            $this->pdf->Ln();
            $this->pdf->Cell(38, 10, 'la quantitat de: ', '$m', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 14);
            $cantidad = -$totalAPagar;
            $this->pdf->Cell(0, 10, utf8_decode(ucfirst($this->num2letrasCatalan($cantidad))), $m, 1, 'L');
            $this->pdf->Cell(0, 10, utf8_decode('en concepte de BAIXA dels tallers: '), $m, 1, 'L');
            foreach ($CNombres as $k => $v) {
                //if(strlen($v)>25) $v=substr($v,0,25)."...";
                $id = $id_talleres[$k];
                $v = $this->db->query("SELECT nombre_corto FROM casal_talleres WHERE id='$id'")->row()->nombre_corto;
                $nombre = $v . " - (" . $trimestres[$k] . ")  " . (number_format(-$importes[$k],2)) . " €";

                $this->pdf->SetFont('Arial', '', 14);
                $v = iconv('UTF-8', 'CP1252', $v);
                //$nombre=iconv('UTF-8', 'CP1252',$nombre);
                $this->pdf->Cell(80, 8, '- ' . $v, $m, 0, 'L');
                if ($trimestres[$k] == -1) $trimestres[$k] = "Espera";
                $this->pdf->Cell(15, 8, $trimestres[$k], $m, 0, 'L');
                $this->pdf->Cell(20, 8, iconv('UTF-8', 'CP1252', number_format(-$importes[$k],2) . ' €'), $m, 0, 'R');

                $this->pdf->SetFont('Arial', '', 9);

                $this->pdf->Cell(0, 8, $this->horarioTaller($id_talleres[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T1Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T1[$k],$socio,4,$T1Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 1: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T1[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T2Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T2[$k],$socio,2,$T2Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 2: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T2[$k]) . ' ', $m, 1, 'R');
            }
            foreach ($T3Nombres as $k => $v) {
                if ($i == 0) {
                    // if(!$this->registrarDevolucion($T3[$k],$socio,1,$T3Precios[$k])) return;  
                }
                $this->pdf->SetFont('Arial', '', 14);
                $v = utf8_decode(substr(trim($v), 0,  strlen($v) - 4)) . ' ' . EURO . ' )';
                $this->pdf->Cell(0, 8, 'Trimestre 3: ' . $v, $m, 0, 'L');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(0, 8, $this->horarioTaller($T3[$k]) . ' ', $m, 1, 'R');
            }

            $this->pdf->SetFont('Arial', 'BI', 18);
            $this->pdf->Cell(40, 10, utf8_decode('Euros: ' . $cantidad), '0', 0, 'L', '0');
            $this->pdf->SetFont('Arial', '', 10);
            if ($i) $r = " - Casal";
            else $r = " - Usuari/usuària";
            $this->pdf->Cell(0, 10, iconv('UTF-8', 'CP1252', 'DEVOLUCIÓ' . $r), '$m', 0, 'R', '0');
            $this->pdf->Ln();
            $this->pdf->Ln();

            if (!$i) $this->pdf->Line(0, 154, 250, 154);
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
        //$this->pdf->Output("Recibo", 'D');

        $porciones = explode(" ", utf8_decode($socioNombre));
        if (isset($porciones[0])) $porciones[0] = substr($porciones[0], 0, 5);
        else $porciones[0] = "";
        if (isset($porciones[1])) $porciones[1] = substr($porciones[1], 0, 5);
        else $porciones[1] = "";

        $recibo =  'RD ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[0]) . ' ' . preg_replace('/[^A-Za-z0-9\-]/', '_', $porciones[1]);
        if (trim($recibo) == "") $recibo = "RD Recibo";
        $recibo .= ' ' . date("d-m-Y His");

        $recibo .= $id_recibo;
        $recibo .= '.pdf';
        $recibo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $recibo);

        $recibo = utf8_decode($recibo);
        if ($_SESSION['pagado'] == false) {
            $id_recibo = $this->socios_model->registrarRecibo($socio, $totalAPagar, $recibo);
            $datosLineas = json_decode($resultNuevo);


            //echo '$datosLineas<br>';
            //var_dump($datosLineas);

            foreach ($datosLineas as $k => $v) {
                $this->talleres_model->registrarLineasRecibo(
                    $id_recibo,
                    $socio,
                    $v->id_taller,
                    $v->trimestres,
                    $v->tipo_taller,
                    $v->pagado,
                    $v->tarjeta,
                    $v->pagado
                );
            }
        }

        $this->pdf->AutoPrint();
        $this->pdf->Output('recibos/' . $recibo, 'F');

        $this->pdf->Output($recibo, 'D');
        $_SESSION['pagado'] = true;
    }

    function getTaller($numTaller)
    {
        $this->load->model('talleres_model');
        $salida = $this->talleres_model->getTaller($numTaller);
        echo json_encode($salida);
    }
}
