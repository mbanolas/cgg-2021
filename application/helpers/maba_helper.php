<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('host'))
{
	function host()
	{
                $host=isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

		//$host=  $_SERVER['HTTP_HOST'];
 		return $host;
	}
}

if ( ! function_exists('mensaje'))
{
	function mensaje($mensaje,$identificacion='------------------------ ') {
            log_message('INFO', $identificacion.$mensaje);      
       }
}

if (!function_exists('getCorreoServidorCasal')) {
    function getCorreoServidorCasal() {
        return "cggcalisidret@gestiocggsantmarti.com";
    }
}

if (!function_exists('getEmailCasal')) {
    function getEmailCasal() {
        return "casalgg.calisidret@bcn.cat";
    }
}
if (!function_exists('getPassword')) {
    function getPassword() {
        return ""; 
    }
}

if (!function_exists('getNumeroRegistroCasalIngresos')) {
    function getNumeroRegistroCasalIngresos() {
        return "201910CGGR1001";
    }
}

if (!function_exists('getNumeroRegistroCasalDevoluciones')) {
    function getNumeroRegistroCasalDevoluciones() {
        return "201910CGGR1099";
    }
}

if ( ! function_exists('dias'))
{
    function dias($desde,$hasta,$dia1,$dia2,$festivos)
    {
        $dias=array();
        $primerDia=strtotime($desde);
        $ultimoDia=strtotime($hasta);
        
        $dia=  trim(strtolower($dia1));
        switch($dia){
            case 'lunes':
            case 'dilluns':    
                $dia=1;
                break;
            case 'martes':
            case 'dimarts':    
                $dia=2;
                break;
            case 'miércoles':
            case 'miercoles':
            case 'dimecres':    
                $dia=3;
                break;
            case 'jueves':
            case 'dijous':    
                $dia=4;
                break;
            case 'viernes':
            case 'divendres':    
                $dia=5;
                break;
            case 'sábado':
            case 'sabado':
            case 'dissabte':    
                $dia=6;
                break;
            case 'domingo':
            case 'diumenge':    
                $dia=0;
                break;
            default:
                $dia='';
        }
        if($dia!=''){
        $primerDiaSemana=$primerDia+($dia-date('w',$primerDia))*24*60*60;
        
        $dias[]=$primerDiaSemana;
        $siguienteDia=$primerDiaSemana;
        while($siguienteDia+24*60*60*7<=$ultimoDia){
            $siguienteDia=$siguienteDia+24*60*60*7;
            $dias[]=$siguienteDia;
        }
        }
      
        $dia=  trim(strtolower($dia2));
        switch($dia){
            case 'lunes':
            case 'dilluns':    
                $dia=1;
                break;
            case 'martes':
            case 'dimarts':    
                $dia=2;
                break;
            case 'miércoles':
            case 'miercoles':
            case 'dimecres':
                $dia=3;
                break;
            case 'jueves':
            case 'dijous':    
                $dia=4;
                break;
            case 'viernes':
            case 'divendres':    
                $dia=5;
                break;
            case 'sábado':
            case 'sabado':
            case 'dissabte':        
                $dia=6;
                break;
            case 'domingo':
            case 'diumenge':     
                $dia=0;
                break;
            default:
                $dia='';
        }
        if($dia!=''){
        $primerDiaSemana=$primerDia+($dia-date('w',$primerDia))*24*60*60;
        
        $dias[]=$primerDiaSemana;
        $siguienteDia=$primerDiaSemana;
        while($siguienteDia+24*60*60*7<=$ultimoDia){
            $siguienteDia=$siguienteDia+24*60*60*7;
            $dias[]=$siguienteDia;
        }
        }
        
       foreach ($festivos as $k => $v) {
            if (($key = array_search(strtotime($v), $dias)) !== false) {
                unset($dias[$key]);
            }
        }
        
        
        sort($dias);
        
        $fechas=array();
        foreach($dias as $k=>$v){
            $fechas[]=date('d/m/Y',$v);
        }
        
        
        
        return $fechas;
        
    }  
}


if (!function_exists('getTituloCasal')) {
    function getTituloCasal() {
        return "Casal Gent Gran CA L'ISIDRET";
    }
}

if (!function_exists('getLetraCasal')) {
    function getLetraCasal() {
        return "I";
    }
}

if (!function_exists('getTituloCasalCorto')) {
    function getTituloCasalCorto() {
        return "Ca l'Isidret";
    }
}

if (!function_exists('nombreMesCatalan')) {

    function nombreMesCatalan($n) {
        switch ($n) {
            case 1: return 'gener';
                break;
            case 2: return 'febrer';
                break;
            case 3: return 'març';
                break;
            case 4: return 'abril';
                break;
            case 5: return 'maig';
                break;
            case 6: return 'juny';
                break;
            case 7: return 'juliol';
                break;
            case 8: return 'agost';
                break;
            case 9: return 'setembre';
                break;
            case 10: return 'octubre';
                break;
            case 11: return 'novembre';
                break;
            case 12: return 'desembre';
                break;
        }
    }

}

if (!function_exists('bitcount')) {
    function bitcount($n){
        $count = 0;
        while($n > 0){
            $count = $count + 1;
            $n = $n & ($n-1);
        }
        return $count;
       }
    
}