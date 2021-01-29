<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No permitido el acceso directo a la URL</h2>");

class Estadisticas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('utilidades');
        $this->load->model('estadisticas_model');
        $this->load->model('socios_model');
        $this->load->model('talleres_model');
    }
    
    public function sexosSocios($reload = "") {
        $this->load->helper('form');
        $this->load->model('talleres_model');
        $datos['optionsCursos']=$this->talleres_model->getCursosOptions();
        $datos['periodo']=$this->talleres_model->getUltimoPeriodo();
        $datos['optionsTalleres']=array(); //$this->talleres_model->getTalleresOptions();
        $datos['sexos'] = $this->socios_model->getSexosSocios();
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['reload'] = $reload;
        $datos['titulo'] = $_SESSION['tituloCasal'];
        $datos['hoy'] = date('j/n/Y');
        $this->load->view('templates/header',$datos);
        $this->load->view('templates/barraNavegacion',$datos);
        $this->load->view('estadisticas_sexos',$datos);
        $datos['pie']='';
        $this->load->view('templates/footer',$datos);
    }

    public function getTablaUsuariosInscritosSexos(){
        $table=$this->estadisticas_model->getTablaUsuariosInscritosSexos($_POST['curso'],$_POST['periodo']);    
        echo json_encode($table);
    }



    function pdfCuadro($pdf,$sexos,$taller,$titulo,$borde){
        if($pdf->GetY()>230) $pdf->AddPage();
        $borde=0;
        $total = $sexos['otros'] + $sexos['hombres'] + $sexos['mujeres'];
        $porcientoOtros = 0;
        $porcientoHombres = 0;
        $porcientoMujeres = 0;
        if ($total != 0) {
            $porcientoOtros = number_format($sexos['otros'] * 100 / $total, 1);
            $porcientoHombres = number_format($sexos['hombres'] * 100 / $total, 1);
            $porcientoMujeres = number_format($sexos['mujeres'] * 100 / $total, 1);
        }
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 6, utf8_decode($titulo) , $borde, '1', 'L');
        $pdf->Cell(6, 1, '    ' , $borde, '2', 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(6, 1, '    ' , $borde, '0', 'L');
        $pdf->Cell(40, 6, utf8_decode('Sexe Desconigut:') , $borde, '0', 'L');
        $pdf->Cell(20, 6, $sexos['otros'] , $borde, '0', 'R');
        $pdf->Cell(20, 6, $porcientoOtros. ' %' , $borde, '1', 'R');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->Cell(6, 1, '    ' , $borde, '0', 'L');
        $pdf->Cell(40, 6, utf8_decode('Sexe Homes:') , $borde, '0', 'L');
        $pdf->Cell(20, 6, $sexos['hombres'] , $borde, '0', 'R');
        $pdf->Cell(20, 6, $porcientoHombres. ' %' , $borde, '1', 'R');
        $pdf->Cell(6, 1, '    ' , $borde, '0', 'L');
        $pdf->Cell(40, 6, utf8_decode('Sexe Dones:') , $borde, '0', 'L');
        $pdf->Cell(20, 6, $sexos['mujeres'] , $borde, '0', 'R');
        $pdf->Cell(20, 6, $porcientoMujeres. ' %' , $borde, '1', 'R');
        $pdf->Cell(6, 1, '    ' , $borde, '0', 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 6, utf8_decode('Total:') , $borde, '0', 'L');
        $pdf->Cell(20, 6, $total , $borde, '1', 'R');   
        $pdf->SetX($x+126);
        $pdf->SetY($y-6);
        if ($taller=="todos" ){
            $urlImagen=$_POST['chart_div'];
        }
        else{
            if (!$taller)
                $urlImagen=$_POST['chart_div2'];
            else{
                $urlImagen=$_POST['chart_div2'.$taller];
            }
        }
        $pdf->Image($urlImagen,105,null,60,0,'PNG');
    }


    public function pdfSexos(){

        $optionsCursos=$this->talleres_model->getCursosOptions();
        $periodo=$this->talleres_model->getUltimoPeriodo();
        $sexos = $this->socios_model->getSexosSocios();
        
        $resultados=$this->estadisticas_model->getDatosUsuariosInscritosSexos($_POST['curso'],$_POST['periodo'][0]);    


        switch($periodo){
            case 1:
                $nombrePeriodo="Trimestre 3 (Primavera)";
            break;
            case 2:
                $nombrePeriodo="Trimestre 2 (Hivern)";
            break;
            case 4:
                $nombrePeriodo="Trimestre 1 (Tardor)";
            break;
            default:
                $nombrePeriodo="";
        }

        

        $this->load->library('pdf');
        $this->pdf = new Pdf();
        $this->pdf->AliasNbPages();
        
        $this->load->model('talleres_model');
        $nombreCurso=$this->talleres_model->getNombreCurso($_POST['curso']);
        
        $this->pdf->setSubtitulo(iconv('UTF-8', 'CP1252','Curs: '.$nombreCurso.' - '.$nombrePeriodo));
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();


        $this->pdf->SetTitle(utf8_decode("Estadístiques usuaris/usuàries per sexes" ));
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);

        $borde=0;
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'I', 16);
        $this->pdf->Cell(90, 10, utf8_decode('Estadístiques usuaris/usuàries per sexes') , $borde, '1', 'L');
        
        $this->pdfCuadro($this->pdf,$sexos,'todos','Distribució usuaris/usuàries (TOTS) per sexe ('.date('j/n/Y').')',$borde);
       
        $this->pdf->SetFont('Arial', 'I', 16);
        $this->pdf->Cell(90, 10, utf8_decode('Curs: '.$nombreCurso.' - '.$nombrePeriodo), $borde, '1', 'L');
        
        foreach($resultados as $k=>$v){
            $sexos=array('otros'=>$v['otros'],'hombres'=>$v['hombres'],'mujeres'=>$v['mujeres']);
            // mensaje('taller '.$v['taller']);
            $this->pdfCuadro($this->pdf,$sexos,$v['taller'],$v['titulo'],$borde);                
        }
        

        $this->pdf->SetFont('Arial', 'B', 12);
        

        $this->pdf->Output("Sexos usuaris ".".pdf", 'D');
    }

}
