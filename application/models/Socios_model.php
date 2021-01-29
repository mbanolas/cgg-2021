<?php

class Socios_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    function getSexosSocios(){
        $sql="SELECT count(sexo) as hombres FROM casal_socios_nuevo WHERE sexo='Home' and fecha_baja='0000-00-00'";
        $hombres=$this->db->query($sql)->row()->hombres;  
        $sql="SELECT count(sexo) as mujeres FROM casal_socios_nuevo WHERE sexo='Dona' and fecha_baja='0000-00-00'";
        $mujeres=$this->db->query($sql)->row()->mujeres;  
        $sql="SELECT count(sexo) as otros FROM casal_socios_nuevo WHERE sexo!='Dona' AND sexo!='Home' and fecha_baja='0000-00-00'";
        $otros=$this->db->query($sql)->row()->otros; 
        return array('hombres'=>$hombres,'mujeres'=>$mujeres,'otros'=>$otros);
    }
    
    function getSocios(){
        $sql="SELECT * FROM casal_socios_nuevo WHERE fecha_baja='0000-00-00' ORDER BY apellidos,nombre" ;    
        $result=$this->db->query($sql)->result();
        return $result;
    }
    function getMovilesInscritosTaller($taller){
        $sql="SELECT s.num_socio,s.nombre,s.apellidos,s.telefono_1,s.telefono_2 FROM casal_asistentes a
                LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.num_socio
                WHERE id_taller='$taller' ORDER BY s.apellidos" ;    
        $result=$this->db->query($sql)->result();
        return $result;
    }


    function getUltimoMensaje(){
        $sql="SELECT * FROM casal_whatsapp order by fecha DESC LIMIT 1";
        if($this->db->query($sql)->num_rows()==0) return "";
        return $this->db->query($sql)->row()->mensaje;
    }
    function getSociosTodos(){
        //$sql="SELECT * FROM casal_socios_nuevo WHERE fecha_baja='0000-00-00' ORDER BY apellidos,nombre" ;
        $sql="SELECT * FROM casal_socios_nuevo  WHERE 1 ORDER BY apellidos,nombre" ;
        
        $result=$this->db->query($sql)->result();
        return $result;
    }
    
    function getSocio($socio){
        $sql="SELECT * FROM casal_socios_nuevo WHERE  id='$socio'" ;
        $result=$this->db->query($sql)->row();
        return $result;
    }
    
    function setTarjetaRosa($socio,$tarjetaRosa){
        $sql="UPDATE casal_socios_nuevo SET tarjeta_rosa='$tarjetaRosa' WHERE id='$socio'" ;
        //log_message('INFO','sql tarjeta rosa------------------------------------------------- '.$sql);
        $result=$this->db->query($sql);
        return $result;
    }
    
    
    function getCumplen90(){
        $hoy=date('Y');
        
        $date=strtotime($hoy.' -90 years');
        $year90=date('Y-01-01',$date);
        $sql="SELECT num_socio,fecha_nacimiento, num_socio,nombre,apellidos,telefono_1,telefono_2 FROM casal_socios_nuevo WHERE fecha_baja='0000-00-00' AND fecha_nacimiento='$year90'" ;
        $result=$this->db->query($sql)->result();
        $cumplen90='<table><tbody>';
        $cumplen90.='<tr>';
            $cumplen90.='<th >'.'Núm Socio'.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th>'.'Nombre Socio'.'</th>';
            $cumplen90.='<th>'.'Fecha Nacimiento'.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th >'.'Teléfono 1 '.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th >'.'Teléfono 2 '.'</th>';
            $cumplen90.='</tr>';
        foreach($result as $k=>$v){
            $cumplen90.='<tr>';
            $cumplen90.='<td>'.$v->num_socio.'</td>';
            $cumplen90.='<td >'.' '.'</td>';
            $cumplen90.='<td>'.$v->nombre.' '.$v->apellidos.'&nbsp;&nbsp; '.'</td>';
            $date=strtotime($v->fecha_nacimiento);
            $cumplen90.='<td>'.date('d-m-Y', $date).'</td>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<td>'.$v->telefono_1.'</td>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<td>'.$v->telefono_2.'</td>';
            $cumplen90.='</tr>';
        }
       // $cumplen90.='</tbody></table>';
       
        return $cumplen90;
    }
    
    function validar_dni($dni){
         //Esun DNI?
         $dni=strtoupper(trim($dni));
         $dni=  str_replace(" ", "", $dni);
         
        $pas = strtolower(trim(substr($dni, -3,3)));
        if($pas=='pas') return true;
        
	    $letra = substr($dni, -1,1);
        //if($letra=='_') return true;
        
	    $numero = substr($dni, 0, 8);
        $numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);
        $modulo = intval($numero) % 23;
        
        $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
	    $letra_correcta = substr($letras_validas, $modulo, 1);
        
	    if($letra_correcta!=$letra) {
		//$this->form_validation->set_message('validar_dni', "EL DNI, NIE o Pasaporte NO es válido. Nota: para entrar núm pasaporte, se debe terminar con '_'");
		    return false;
	    }else{
               //$this->get_form_validation()->set_message('validar_dni',"EL DNI, NIE o Pasaporte NO es válido. Nota Para entrar núm pasaporte, se debe terminar con '_'");
            return true;
	    }
    }
    
    
    function getCumplen90proximos30dias(){
        $hoy=date('Y-m-d');
        $date=strtotime($hoy.' -90 years');
        $desde=date('Y-m-d',$date);
        $date2=strtotime($hoy.' 1 month');
        $mes=date('Y-m-d',$date2);
        $date=strtotime($mes.' -90 years');
        $hasta=date('Y-m-d',$date);
        
        $year90=date('Y-01-01',$date);
        $sql="SELECT num_socio,fecha_nacimiento, num_socio,nombre,apellidos,telefono_1,telefono_2 FROM casal_socios_nuevo WHERE fecha_baja='0000-00-00' AND fecha_nacimiento>='$desde' AND fecha_nacimiento<='$hasta' ORDER BY fecha_nacimiento" ;
      
        $result=$this->db->query($sql)->result();
        $cumplen90="";
        //$cumplen90='<table><tbody>';
        /*
        $cumplen90.='<tr>';
            $cumplen90.='<th >'.'Núm Socio'.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th>'.'Nombre Socio'.'</th>';
            $cumplen90.='<th>'.'Fecha Nacimiento'.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th>'.'Teléfono 1'.'</th>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<th>'.'Teléfono 2'.'</th>';
            $cumplen90.='</tr>';
         */   
        foreach($result as $k=>$v){
            $cumplen90.='<tr>';
            $cumplen90.='<td>'.$v->num_socio.'</td>';
            $cumplen90.='<td >'.' '.'</td>';
            $cumplen90.='<td>'.$v->nombre.' '.$v->apellidos.'&nbsp;&nbsp; '.'</td>';
            $date=strtotime($v->fecha_nacimiento);
            $cumplen90.='<td>'.date('d-m-Y', $date).'</td>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<td>'.$v->telefono_1.'</td>';
            $cumplen90.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumplen90.='<td>'.$v->telefono_2.'</td>';
            $cumplen90.='</tr>';
        }
        $cumplen90.='</tbody></table>';
       
        return $cumplen90;
    }
    
    function getCumpleanos(){
        $hoy=date('Y-m-d');
        $date=date('m-d');
        
        $sql="SELECT num_socio,fecha_nacimiento, num_socio,nombre,apellidos,telefono_1,telefono_2,edad FROM casal_socios_nuevo WHERE fecha_baja='0000-00-00' AND fecha_nacimiento like '%$date%' ORDER BY fecha_nacimiento" ;
        if ($this->db->query($sql)->num_rows()==0)  return "";   
        
        $result=$this->db->query($sql)->result();
        $cumpleanos="";
        $cumpleanos='<table><tbody>';
        
        $cumpleanos.='<tr>';
            $cumpleanos.='<th >'.'Núm Socio'.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th>'.'Nombre Socio'.'</th>';
            $cumpleanos.='<th>'.'Fecha Nacimiento'.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th>'.'Teléfono 1'.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th>'.'Teléfono 2'.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th>'.'Cumple'.'</th>';
            $cumpleanos.='</tr>';
           
        foreach($result as $k=>$v){
            $cumpleanos.='<tr>';
            $cumpleanos.='<td>'.$v->num_socio.'</td>';
            $cumpleanos.='<td >'.' '.'</td>';
            $cumpleanos.='<td>'.$v->nombre.' '.$v->apellidos.'&nbsp;&nbsp; '.'</td>';
            $date=strtotime($v->fecha_nacimiento);
            $cumpleanos.='<td>'.date('d-m-Y', $date).'</td>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<td>'.$v->telefono_1.'</td>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<td>'.$v->telefono_2.'</td>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $cumpleanos.='<th >'.'&nbsp;&nbsp; '.'</th>';
            $edad=$v->edad+1;
            $cumpleanos.='<td>'.$edad.' años</td>';
            $cumpleanos.='</tr>';
        }
        $cumpleanos.='</tbody></table>';
       
        return $cumpleanos;
    }
    
    function getSociosNuevos(){
        $result=array();
        $sql="SELECT num_socio FROM casal_nuevos_socios_sin_carnet";
        if($this->db->query($sql)->num_rows()==0) return $result;
        foreach($this->db->query($sql)->result() as $k=>$v){
            $socio=$v->num_socio;
            $sql="SELECT * FROM casal_socios_nuevo WHERE id='$socio'" ;
            if($this->db->query($sql)->num_rows()==0) continue;
            //log_message('INFO', $socio.' '.$this->db->query($sql)->row()->id);
            $row=$this->db->query($sql)->row();
            $result[]=array('id'=>$row->id,'nombre'=>$row->nombre,'apellidos'=>$row->apellidos);
        }
        return $result;
    }
    
    function borrarSociosNuevos($num_socio){
       $sql="DELETE FROM `casal_nuevos_socios_sin_carnet` WHERE num_socio='$num_socio'";
       //log_message('INFO',$sql);
       $this->db->query($sql);
    }
    
    function duplicados(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
        $duplicados=array();
        $sql="SELECT * FROM casal_socios_nuevo WHERE 1";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $dni=trim($v->dni);
            $dni9=substr($dni,0,9);
            if($dni=="") continue; 
            $sql="SELECT * FROM casal_socios_nuevo WHERE id>='$id' AND (TRIM(dni)='$dni' OR LEFT(TRIM(dni),9)= '$dni9')";
                //log_message('INFO',$sql);
                if($this->db->query($sql)->num_rows()>1){
                    //log_message('INFO',$sql);
                    $result2=$this->db->query($sql)->result();
                    foreach($result2 as $k2=>$v2){
                        $duplicados[]=array('num_socio'=>$v2->num_socio,
                                            'nombre'=>$v2->nombre,
                                            'apellidos'=>$v2->apellidos,
                                            'dni'=>$v2->dni);
                        
                    }
                        $duplicados[]=array('num_socio'=>'------------------',
                                            'nombre'=>'',
                                            'apellidos'=>'',
                                            'dni'=>'');
                }
        }
        //var_dump($duplicados);
        return $duplicados;
    }
    
    function getTarjetaRosa($num_socio){
        return $this->db->query("SELECT tarjeta_rosa FROM casal_socios_nuevo WHERE num_socio=$num_socio")->row()->tarjeta_rosa;
    }
    
    function getTablaTalleresSocio($curso,$socio){
        $tabla="";
        $sql="SELECT c.nombre as nombre_curso,  a.trimestres, a.pagado, s.num_socio as num_socio, s.nombre as nombre_socio, s.apellidos as apellidos,
            t.nombre_corto as nombre_taller, p.nombre as nombre_profesor, 
            ds1.nombre_castellano as dia_semana_1, t.inicio_1 as inicio_1, t.final_1 as final_1, e1.nombre as nombre_espacio_1,
            ds2.nombre_castellano as dia_semana_2, t.inicio_2 as inicio_2, t.final_2 as final_2, e2.nombre as nombre_espacio_2
            FROM casal_asistentes a
            LEFT JOIN casal_talleres t ON t.id=a.id_taller
            LEFT JOIN casal_socios_nuevo s ON s.id=a.id_socio
            LEFT JOIN casal_profesores p ON p.id=t.id_profesor
            LEFT JOIN casal_dias_semana ds1 ON ds1.id=t.id_dia_semana_1
            LEFT JOIN casal_espacios e1 ON e1.id=t.id_espacio_1
            LEFT JOIN casal_espacios e2 ON e2.id=t.id_espacio_2
            LEFT JOIN casal_dias_semana ds2 ON ds2.id=t.id_dia_semana_2
            LEFT JOIN casal_cursos c ON c.id='$curso'
            WHERE id_socio='$socio' and id_curso='$curso' AND a.trimestres>0";
        
        $numFilas=$this->db->query($sql)->num_rows();
        if($numFilas>0){
        $resultado=$this->db->query($sql)->result();
        $nombre_curso=$resultado[0]->nombre_curso;
        $nombre_socio=$resultado[0]->nombre_socio.' '.$resultado[0]->apellidos.' ('.$resultado[0]->num_socio.')';
        
        $tabla.='<div class="col-sm-12">
        <div id="tablaAsistentes"><h4>Curs: '.$nombre_curso.'</h4><h3>Usuari/Usuària: '.$nombre_socio.'</h3>
            <table class="table table-bordered">
                <tbody>
                </tbody>
                <thead>
                    <tr>
                        <th>Taller</th>
                        <th>Professor</th>
                        <th>Espai</th>
                        <th>Dia setmana</th>
                        <th>Inici</th>
                        <th>Final</th>
                        <th>Trimestres</th>
                        <th class="numDerecha" style="padding-right:20px;">Pagat (€)</th>
                    </tr>';
                $numTalleres=0;
                $totalPagado=0;
                foreach($resultado as $k=>$v){
                    $numTalleres++;
                    $totalPagado+=$v->pagado;
                     switch($v->trimestres){
                case 1:
                    $trimestres="T3";
                    break;
                case 2:
                    $trimestres="T2";
                    break;
                case 3:
                    $trimestres="T2, T3";
                    break;
                case 4:
                    $trimestres="T1";
                    break;
                case 5:
                    $inscrito="T1, T3";
                    break;
                case 6:
                    $trimestres="T1, T2";
                    break;
                case 7:
                    $trimestres="C";
                    break;
                default:
                    $trimestres="";
            }
                    $tabla.='<tr>
                        <td>'.$v->nombre_taller.'</td>
                        <td>'.$v->nombre_profesor.'</td>
                        <td>'.$v->nombre_espacio_1.'</td>
                        <td>'.$v->dia_semana_1.'</td>
                        <td>'.substr($v->inicio_1,0,5).'</td>
                        <td>'.substr($v->final_1,0,5).'</td>
                        <td>'.$trimestres.'</td>
                        <td class="numDerecha" style="padding-right:20px;">'.number_format($v->pagado,2).'</td>
                    </tr>';
                };
        
        $tabla.='
                </thead>
                
                    <tr>
                        <th>'.$numTalleres.'</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="numDerecha" style="padding-right:20px;">'.number_format($totalPagado,2).'</th>
                    </tr>
                
            
      ';
    }  
       else{
           $sql="SELECT c.nombre as nombre_curso, s.num_socio as num_socio, s.nombre as nombre_socio, s.apellidos as apellidos
            FROM casal_socios_nuevo s
            LEFT JOIN casal_cursos c ON c.id='$curso'
            WHERE num_socio='$socio'";
           $resultado=$this->db->query($sql)->result();
            $nombre_curso=$resultado[0]->nombre_curso;
            $nombre_socio=$resultado[0]->nombre_socio.' '.$resultado[0]->apellidos.' ('.$resultado[0]->num_socio.')';
            $tabla='<div class="col-sm-12">
        <div id="tablaAsistentes"><h4>Curs: '.$nombre_curso.'</h4><h3>Usuari/Usuària: '.$nombre_socio.'</h3>
            <table class="table table-bordered">
                <tbody>
                </tbody>
                <thead>
                    <tr>
                        <th>Taller</th>
                        <th>Professor</th>
                        <th>Espai</th>
                        <th>Dia setmana</th>
                        <th>Inici</th>
                        <th>Final</th>
                        <th>Trimestres</th>
                        <th>Pagat (€)</th>
                    </tr>';
                $numTalleres=0;
                $totalPagado=0;
                
        $tabla.='
                </thead>
                
                    <tr>
                        <th>'.$numTalleres.'</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>'.number_format($totalPagado,2).'</th>
                    </tr>
                
               ';
        
       } 
    
    $sql="SELECT c.nombre as nombre_curso,  r.orden as orden, r.trimestres,  s.num_socio as num_socio, s.nombre as nombre_socio, s.apellidos as apellidos,
            t.nombre_corto as nombre_taller, p.nombre as nombre_profesor, 
            ds1.nombre_castellano as dia_semana_1, t.inicio_1 as inicio_1, t.final_1 as final_1, e1.nombre as nombre_espacio_1,
            ds2.nombre_castellano as dia_semana_2, t.inicio_2 as inicio_2, t.final_2 as final_2, e2.nombre as nombre_espacio_2
            FROM casal_reservas r
            LEFT JOIN casal_talleres t ON t.id=r.id_taller
            LEFT JOIN casal_socios_nuevo s ON s.id=r.id_socio
            LEFT JOIN casal_profesores p ON p.id=t.id_profesor
            LEFT JOIN casal_dias_semana ds1 ON ds1.id=t.id_dia_semana_1
            LEFT JOIN casal_espacios e1 ON e1.id=t.id_espacio_1
            LEFT JOIN casal_espacios e2 ON e2.id=t.id_espacio_2
            LEFT JOIN casal_dias_semana ds2 ON ds2.id=t.id_dia_semana_2
            LEFT JOIN casal_cursos c ON c.id=t.id_curso
            WHERE id_socio='$socio' and id_curso='$curso' AND r.trimestres>0";
    
    
    if($this->db->query($sql)->num_rows()==0) {
        
        return $tabla;
    }
    $result=$this->db->query($sql)->result();
    foreach($result as $k=>$v){
        switch($v->trimestres){
                case 1:
                    $trimestres="T3";
                    break;
                case 2:
                    $trimestres="T2";
                    break;
                case 3:
                    $trimestres="T2, T3";
                    break;
                case 4:
                    $trimestres="T1";
                    break;
                case 5:
                    $inscrito="T1, T3";
                    break;
                case 6:
                    $trimestres="T1, T2";
                    break;
                case 7:
                    $trimestres="C";
                    break;
                default:
                    $trimestres="";
            }
        $tabla.='<tr style="background-color:yellow;">
                        <td>'.$v->nombre_taller.'</td>
                        <td>'.$v->nombre_profesor.'</td>
                        <td>'.$v->nombre_espacio_1.'</td>
                        <td>'.$v->dia_semana_1.'</td>
                        <td>'.substr($v->inicio_1,0,5).'</td>
                        <td>'.substr($v->final_1,0,5).'</td>
                        <td>'.$trimestres.'</td>
                        <td>En llista d´espera ('.$v->orden.')</td>
                    </tr>';    
            
            
    }   
       
       $tabla.='</div>
                </div></table>';
       
        
        return $tabla; 
    }
    
    function getDatosTalleresSocio($curso,$socio){
        $sql="SELECT c.nombre as nombre_curso, s.num_socio as num_socio, s.nombre as nombre_socio, s.apellidos as apellidos
            FROM casal_socios_nuevo s
            LEFT JOIN casal_cursos c ON c.id='$curso'
            WHERE num_socio='$socio'";
            $resultado=$this->db->query($sql)->result();
            $nombre_curso=$resultado[0]->nombre_curso;
            $nombre_socio=$resultado[0]->nombre_socio.' '.$resultado[0]->apellidos.' ('.$resultado[0]->num_socio.')';
        
        
        $sql="SELECT c.nombre as nombre_curso,  a.trimestres, a.pagado, s.num_socio as num_socio, s.nombre as nombre_socio, s.apellidos as apellidos,
            t.nombre_corto as nombre_taller, p.nombre as nombre_profesor, 
            ds1.nombre_castellano as dia_semana_1, t.inicio_1 as inicio_1, t.final_1 as final_1, e1.nombre as nombre_espacio_1,
            ds2.nombre_castellano as dia_semana_2, t.inicio_2 as inicio_2, t.final_2 as final_2, e2.nombre as nombre_espacio_2
            FROM casal_asistentes a
            LEFT JOIN casal_talleres t ON t.id=a.id_taller
            LEFT JOIN casal_socios_nuevo s ON s.id=a.id_socio
            LEFT JOIN casal_profesores p ON p.id=t.id_profesor
            LEFT JOIN casal_dias_semana ds1 ON ds1.id=t.id_dia_semana_1
            LEFT JOIN casal_espacios e1 ON e1.id=t.id_espacio_1
            LEFT JOIN casal_espacios e2 ON e2.id=t.id_espacio_2
            LEFT JOIN casal_dias_semana ds2 ON ds2.id=t.id_dia_semana_2
            LEFT JOIN casal_cursos c ON c.id='$curso'
            WHERE id_socio='$socio' and id_curso='$curso'";
        
        $numFilas=$this->db->query($sql)->num_rows();
        if($numFilas>0){
            $resultado=$this->db->query($sql)->result();
        } 
        else $resultado=null;
        
        return array('nombre_curso'=>$nombre_curso,'nombre_socio'=>$nombre_socio,'resultado'=>$resultado);
         
    }
    
    function getNombreSocio($id){
        $sql="SELECT * FROM casal_socios_nuevo WHERE id='$id'" ;
        $result=$this->db->query($sql)->row();
        return $result->apellidos.', '.$result->nombre.' ('.$id.')';
    }
    
    function getNombreCurso($id){
        $sql="SELECT * FROM casal_cursos WHERE id='$id'" ;
        $result=$this->db->query($sql)->row();
        return $result->nombre;
    }
    
    function getSociosOptions(){
        $result=$this->getSocios();
        $options=array();
        foreach($result as $k=>$v){
            $options[$v->id]=$v->apellidos.', '.$v->nombre.' ('.$v->dni.')';
        }
        return $options;
    }
    function getSociosOptionsTodos(){
        $result=$this->getSociosTodos();
        $options=array();
        foreach($result as $k=>$v){
            $options[$v->id]=$v->apellidos.', '.$v->nombre;
        }
        return $options;
    }
    
    
    function getSociosFiltro($filtro=" "){
        $palabras=explode(" ",trim($filtro));
        $like="";
        $resultado=array();
        
        foreach($palabras as $k=>$v){
                $resultado[]="concat(nombre,apellidos,id,dni) LIKE '%$v%'";
        }
        $like=implode(' AND ',$resultado);
        $socios=array();
        $sql="SELECT id,nombre,apellidos,dni FROM casal_socios_nuevo WHERE  fecha_baja='0000-00-00' AND $like ORDER BY apellidos,nombre";
        //log_message('INFO',$sql);
         if($this->db->query($sql)->num_rows()>0){
         $result=$this->db->query($sql)->result();
         foreach($result as $k=>$v){
             $socio=array(
                 'id'=>$v->id,
                 'nombre'=>$v->nombre,
                 'apellidos'=>$v->apellidos,
                 'dni'=>$v->dni,
             );
             $socios[]=$socio;
         }
         
         
         $options=array();
         foreach($socios as $k=>$v){
            $id=$v['id']; 
            $nombre=$v['nombre'];
            $apellidos=$v['apellidos'];
            $dni=$v['dni'];
            $options[$id]="$apellidos, $nombre ($id) DNI $dni";
        }
        return $options;
         }
        else return false;
    }
    
    function getSociosFiltroTodos($filtro=" "){
        $palabras=explode(" ",trim($filtro));
        $like="";
        $resultado=array();
        
        foreach($palabras as $k=>$v){
                $resultado[]="concat(nombre,apellidos,id) LIKE '%$v%'";
        }
        $like=implode(' AND ',$resultado);
        $socios=array();
        //$sql="SELECT id,nombre,apellidos FROM casal_socios_nuevo WHERE  fecha_baja='0000-00-00' AND $like ORDER BY apellidos,nombre";
        $sql="SELECT id,nombre,apellidos FROM casal_socios_nuevo WHERE  1 AND $like ORDER BY apellidos,nombre";

         if($this->db->query($sql)->num_rows()>0){
         $result=$this->db->query($sql)->result();
         foreach($result as $k=>$v){
             $socio=array(
                 'id'=>$v->id,
                 'nombre'=>$v->nombre,
                 'apellidos'=>$v->apellidos,
             );
             $socios[]=$socio;
         }
         
         
         $options=array();
         foreach($socios as $k=>$v){
            $id=$v['id']; 
            $nombre=$v['nombre'];
            $apellidos=$v['apellidos'];
            $options[$id]="$apellidos, $nombre ($id)";
        }
        return $options;
         }
        else return false;
    }
    
    function registrarRecibo($socio,$importe,$recibo){
        $hoy=date('Y-m-d');
        $devolucion=0;
        if(substr($recibo,0,2)=="RD") {$devolucion=1;}
        $sql="INSERT INTO casal_recibos SET id_socio='$socio',importe='$importe',devolucion='$devolucion', recibo='$recibo', fecha='$hoy'";
        $this->db->query($sql);
        $sql="SELECT * FROM casal_recibos ORDER BY id DESC LIMIT 1";
        $id_recibo=$this->db->query($sql)->row()->id;
        return $id_recibo;
    }
    
    
}



