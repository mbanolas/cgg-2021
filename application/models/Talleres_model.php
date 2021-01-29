<?php

class Talleres_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('maba');
    }

    

    function getInfoUltimoRecibo(){
        $infoUltimoRecibo=array();
        $lineas=array();
        $lineasEspera=array();
        $sql="SELECT id,devolucion FROM casal_recibos ORDER BY id DESC LIMIT 1";
        $idRecibo=$this->db->query($sql)->row()->id;
        $devolucion=$this->db->query($sql)->row()->devolucion;
        if($devolucion)
            return $infoUltimoRecibo=array('lineas'=>$lineas,'lineasEspera'=>$lineasEspera,'devolucion'=>$devolucion);
        $sql="SELECT lr.id_socio as id_socio,"
                . " lr.id_taller as id_taller,"
                . " lr.tipo_taller as tipo_taller,"
                . " lr.importe as importe,"
                . " lr.tarjeta as tarjeta,"
                . " s.nombre as nombreSocio,"
                . " s.apellidos as apellidosSocio,"
                . " t.nombre as nombreTaller,"
                . " c.nombre as nombreCurso,"
                . " lr.periodos as periodos,"
                . " s.tarjeta_rosa as tarjeta_rosa"
                . " FROM casal_lineas_recibos lr"                      
                . " LEFT JOIN casal_talleres t ON lr.id_taller=t.id"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=lr.id_socio"
                . " LEFT JOIN casal_cursos c ON c.id=t.id_curso"
                
                . " WHERE id_recibo='$idRecibo'";
               
        $result=$this->db->query($sql)->result();
        
        foreach($result as $k=>$v){
            $tarjeta=$v->tarjeta?'tarjeta':'';
            $lineas[]=array('id_socio'=>$v->id_socio, 
                'id_taller'=>$v->id_taller, 
                'tipo_taller'=>$v->tipo_taller,
                'importe'=>$v->importe,
                'tarjeta'=>$v->tarjeta,
                
                'nombreTaller'=>$v->nombreTaller,
                'nombreSocio'=>$v->nombreSocio ,
                'apellidosSocio'=>$v->apellidosSocio,
                'tarjeta_rosa'=>$v->tarjeta_rosa,
                'nombreCurso'=>$v->nombreCurso,
                'periodos'=>$v->periodos,
                'id_recibo'=>$idRecibo
                );
        }
        //talleres en espera
        $sql="SELECT r.id_socio as id_socio,"
                . " r.id_taller as id_taller,"
                . " t.tipo_taller as tipo_taller,"
                . " r.orden as orden,"               
                . " s.nombre as nombreSocio,"
                . " s.apellidos as apellidosSocio,"
                . " t.nombre as nombreTaller,"
                . " c.nombre as nombreCurso,"
                . " r.trimestres as trimestres," 
                . " s.tarjeta_rosa as tarjeta_rosa"             
                . " FROM casal_reservas r"                      
                . " LEFT JOIN casal_talleres t ON r.id_taller=t.id"
                . " LEFT JOIN casal_socios_nuevo s ON s.num_socio=r.id_socio"
                . " LEFT JOIN casal_cursos c ON c.id=t.id_curso"
                
                . " WHERE id_recibo='$idRecibo'";
               
        $result=$this->db->query($sql)->result();
        
        foreach($result as $k=>$v){
            
            $lineasEspera[]=array('id_socio'=>$v->id_socio, 
                'id_taller'=>$v->id_taller, 
                'tipo_taller'=>$v->tipo_taller, 
                'nombreTaller'=>$v->nombreTaller,
                'nombreSocio'=>$v->nombreSocio ,
                'apellidosSocio'=>$v->apellidosSocio,
                'nombreCurso'=>$v->nombreCurso,
                'periodos'=>$v->trimestres,
                'tarjeta_rosa'=>$v->tarjeta_rosa,
                'id_recibo'=>$idRecibo
                );
        }
        $infoUltimoRecibo=array('lineas'=>$lineas,'lineasEspera'=>$lineasEspera,'devolucion'=>$devolucion);
        return $infoUltimoRecibo;
    }
    function getUltimoCurso(){
        $row= $this->db->query("SELECT id,nombre FROM casal_cursos ORDER BY id DESC LIMIT 1")->row();
        $talleres=$this->db->query("SELECT * FROM casal_talleres WHERE id_curso='".$row->id."' ORDER BY nombre")->result();
        return array('ultimoCurso'=>$row->nombre, 'talleres'=>$talleres);
    }
    
    function prepararCasalReservas(){
        //preparar la tabla Grocery
        $sql="SELECT concat(s.apellidos,', ',s.nombre) as socio,"
                . " s.telefono_1 as telefono1, "
                . " s.telefono_2 as telefono2, "
                . " t.nombre as taller,"
                . " c.nombre as curso,"
                . " r.orden as orden,"
                . " r.trimestres as trimestres"
                . " FROM casal_reservas r"
                . " LEFT JOIN casal_talleres t ON t.id=r.id_taller"
                . " LEFT JOIN casal_cursos c ON c.id=t.id_curso"
                . " LEFT JOIN casal_socios_nuevo s ON r.id_socio=s.num_socio";
        
        $result=$this->db->query($sql)->result();
        $this->db->query("DELETE FROM `casal_reservas_grocery` WHERE 1");
        foreach($result as $k=>$v){
            $socio=$v->socio;
            $telefono1=$v->telefono1;
            $telefono2=$v->telefono2;
            $curso=$v->curso;
            $taller=$v->taller;
            $orden=$v->orden;
            $trimestres=$v->trimestres;
            switch($trimestres){
                case 1: $trimestres='T3';break;
                case 2: $trimestres='T2';break;
                case 3: $trimestres='T1';break;
                
                default: $trimestres=$trimestres;
            }
            $this->db->query("INSERT INTO casal_reservas_grocery SET telefono_1='$telefono1',telefono_2='$telefono2', socio='$socio', taller='$taller', curso='$curso',orden='$orden', trimestre='$trimestres'");
        }
        redirect('/basesDatos/casal_reservas');
    }
    
    function putTalleresCurso($curso,$talleres){
        $this->db->query("INSERT INTO casal_cursos SET nombre='$curso'");
        $id_curso= $this->db->query("SELECT id FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
        foreach($talleres as $k=>$v){
            $row=$this->db->query("SELECT * FROM casal_talleres WHERE id='$v'")->row();
            $this->db->query("INSERT INTO casal_talleres SET "
                    . " nombre='".$row->nombre."', "
                    . " nombre_corto='".$row->nombre_corto."', "
                    . " tipo_taller='".$row->tipo_taller."', "
                    . " id_agrupacion='".$row->id_agrupacion."', "
                    . " id_curso='".$id_curso."', "
                    . " id_periodo='".$row->id_periodo."', "
                    . " id_profesor='".$row->id_profesor."', "
                    . " id_actividad='".$row->id_actividad."', "
                    . " id_dia_semana_1='".$row->id_dia_semana_1."', "
                    . " inicio_1='".$row->inicio_1."', "
                    . " final_1='".$row->final_1."', "
                    . " id_espacio_1='".$row->id_espacio_1."', "
                    . " id_dia_semana_2='".$row->id_dia_semana_2."', "
                    . " inicio_2='".$row->inicio_2."', "
                    . " final_2='".$row->final_2."', "
                    . " id_espacio_2='".$row->id_espacio_2."', "
                    . " precio_trimestre='".$row->precio_trimestre."', "
                    . " precio_curso='".$row->precio_curso."', "
                    . " precio_rosa_trimestre='".$row->precio_rosa_trimestre."', "
                    . " precio_rosa_curso='".$row->precio_rosa_curso."', "
                    . " num_maximo='".$row->num_maximo."', "
                    . " num_reservas='".$row->num_reservas."', "
                    . " fecha_inicio='0000-00-00', "
                    . " fecha_final='0000-00-00'");
        }
    }
    
    function getTalleres($curso,$periodo='C',$tipoTaller="") {
       
        $numPeriodo=$this->getNumPeriodo($periodo);
        
        switch($numPeriodo){
            case 1: $idPeriodo=" AND (id_periodo=1 OR id_periodo=3 OR id_periodo=5 OR id_periodo=7) "; break;
            case 2: $idPeriodo=" AND (id_periodo=2 OR id_periodo=3 OR id_periodo=6 OR id_periodo=7) "; break;
            case 4: $idPeriodo=" AND (id_periodo=4 OR id_periodo=5 OR id_periodo=6 OR id_periodo=7) "; break;
            case 7: $idPeriodo="  "; break;
        }
        
        $whereTipoTaller="";
        if($tipoTaller=='tots') $whereTipoTaller="";
        if($tipoTaller=='voluntaris')
            $whereTipoTaller=" AND tipo_taller='voluntari' ";
        if($tipoTaller=='professionals')
            $whereTipoTaller=" AND tipo_taller='professional' ";
        // if($this->session->categoria==3){
        //     $whereTipoTaller=" AND tipo_taller='voluntari' ";
        // }
        $sql = "SELECT * FROM casal_talleres WHERE id_curso='$curso' $idPeriodo  $whereTipoTaller ORDER BY nombre";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    function getTextoCurso($curso){
        return $this->db->query("SELECT nombre FROM casal_cursos WHERE id='$curso'")->row()->nombre;
    }
    
    function getTextoPeriodo($letraPeriodo) {
        switch ($letraPeriodo) {
            case "C":
                return "Curs complet";
                break;
            case "T1":
                return "Trimestre 1 - Tardor";
                break;
            case "T2":
                return "Trimestre 2 - Hivern";
                break;
            case "T3":
                return "Trimestre 3 - Primavera";
                break;
            default:
                return "No definit";
        }
    }

    function getTextoPeriodos($numPeriodo) {
        switch($numPeriodo){
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
            return $trimestres;
    }
    
    function getNumPeriodo($letraPeriodo) {
        switch ($letraPeriodo) {
            case "C":
                return 7;
                break;
            case "T1":
                return 4;
                break;
            case "T2":
                return 2;
                break;
            case "T3":
                return 1;
                break;
            default:
                return 0;
        }
    }

    function getTalleresArray($curso,$numPeriodo="7") {
        //if($numPeriodo==7 || $numPeriodo==0) $wherePeriodo=" ";
        $wherePeriodo=" ";
        
        if($numPeriodo==1) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=1 || id_periodo=3 || id_periodo=5 ) ";
        if($numPeriodo==2) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=2 || id_periodo=3  ) ";
        if($numPeriodo==4) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=4 || id_periodo=5  || id_periodo=6) ";
        if($numPeriodo==7) $wherePeriodo = " AND ( id_periodo=7 ) ";

        
        $sql = "SELECT * FROM casal_talleres WHERE id_curso='$curso' $wherePeriodo ";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getTalleresCursoArray($curso) {
        //if($numPeriodo==7 || $numPeriodo==0) $wherePeriodo=" ";
        // $wherePeriodo=" ";
        
        // if($numPeriodo==1) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=1 || id_periodo=3 || id_periodo=5 ) ";
        // if($numPeriodo==2) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=2 || id_periodo=3  ) ";
        // if($numPeriodo==4) $wherePeriodo = " AND ( id_periodo=7 || id_periodo=4 || id_periodo=5  || id_periodo=6) ";
        // if($numPeriodo==7) $wherePeriodo = " AND ( id_periodo=7 ) ";

        
        $sql = "SELECT * FROM casal_talleres WHERE id_curso='$curso' ";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getTalleresOptions($curso) {
        if($curso==0)
            {
                $curso=$this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
            }
            
        $result = $this->getTalleres($curso);
        $options = array('0'=>"Seleccionar taller");
        foreach ($result as $k => $v) {
            $options[$v->id] = $v->nombre;
        }
        return $options;
    }

    function getCursos() {
        $sql = "SELECT * FROM casal_cursos ORDER BY id DESC";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    function getCursosOptions() {
        $result = $this->getCursos();
        $options = array();
        foreach ($result as $k => $v) {
            $options[$v->id] = $v->nombre;
        }
        return $options;
    }

    

    function getIdCursoActual(){
        $sql = "SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1";
        $idCursoActual = $this->db->query($sql)->row()->id;
        return $idCursoActual;
    }
    
    function getListaNombres($curso) {
        $result = $this->getTalleres($curso);
        $listaNombres = array();
        foreach ($result as $k => $v) {
            $listaNombres[] = '{"id":"' . $v->id . '","nombre":"' . $v->nombre . '"}';
        }
        return $listaNombres;
    }

    function getAsistentesArray($curso, $socio) {
        //echo 'getAsistentesArray /'.$periodo.'/';
        //echo $trimestres;
        
        $sql = "SELECT a.id_taller as taller, a.trimestres as trimestres, t.tipo_taller as tipo_taller
              FROM casal_asistentes a
              LEFT JOIN casal_talleres t ON t.id=a.id_taller 
              LEFT JOIN casal_cursos c ON c.id=t.id_curso
              WHERE a.id_socio='$socio' AND c.id='$curso' ";
        // if($this->session->categoria==3 ) {   
        //     $sql = "SELECT a.id_taller as taller, a.trimestres as trimestres, t.tipo_taller as tipo_taller
        //       FROM casal_asistentes a
        //       LEFT JOIN casal_talleres t ON t.id=a.id_taller 
        //       LEFT JOIN casal_cursos c ON c.id=t.id_curso AND c.id='$curso'
        //       WHERE a.id_socio='$socio'  AND t.tipo_taller='Voluntari'";
            
        // }
        //echo $sql;
        // mensaje($sql);
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

   
    
    function talleresListaEspera($curso, $socio) {
        $sql = "SELECT r.id_taller as taller, t.nombre as nombre
            FROM casal_reservas r
            LEFT JOIN casal_talleres t ON t.id=r.id_taller 
            LEFT JOIN casal_cursos c ON c.id=t.id_curso 
            WHERE r.id_socio='$socio' AND c.id='$curso'";

        // mensaje('talleresListaEspera '.$sql);    
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getListaEsperaArray($curso, $socio,$numPeriodo=0) {
        //echo '$numPeriodo '.$numPeriodo;
        $sql = "SELECT a.id_taller as taller, a.trimestres as trimestres, a.orden as orden 
              FROM casal_reservas a
              LEFT JOIN casal_talleres t ON t.id=a.id_taller 
              LEFT JOIN casal_cursos c ON c.id=t.id_curso 
              WHERE c.id='$curso' AND a.id_socio='$socio' AND a.trimestres='$numPeriodo'";
        //echo $sql;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getMaxAsistentes($taller) {
        return $this->db->query("SELECT num_maximo FROM casal_talleres WHERE id='$taller'")->row()->num_maximo;
    }

    public function setUltimoPeriodo($numPeriodo) {
        $resultado = $this->db->query("UPDATE casal_ultimo_periodo SET num_periodo='$numPeriodo' WHERE id=1");
        return "UPDATE casal_ultimo_periodo SET num_periodo='$numPeriodo' WHERE id=1";
    }

    function getUltimoPeriodo() {
        return $this->db->query("SELECT num_periodo FROM casal_ultimo_periodo WHERE id=1")->row()->num_periodo;
    }

    function getNumInscritos($taller, $periodo) {
        switch ($periodo) {
            case 'C':
                $trimestres = 7;
                break;
            case 'T1':
                $trimestres = 4;
                break;
            case 'T2':
                $trimestres = 2;
                break;
            case 'T3':
                $trimestres = 1;
                break;
        }
        if ($trimestres == 7)
            $trimestres = "";
        else
            $trimestres = " AND ((trimestres & $trimestres) OR trimestres='7')";

        $sql = "SELECT count(*) as numInscritos FROM casal_asistentes a
              WHERE a.id_taller='$taller'  $trimestres";
        //echo $sql.'<br>';
        return $this->db->query($sql)->row()->numInscritos;
    }

    function getAsistentesTaller($taller, $orden = 0,$periodo=0) {
        
        if ($orden == 0)
            $orderBy = " s.apellidos,s.nombre";
        if ($orden == 1)
            $orderBy = " s.id";
        $numPeriodo=$this->getNumPeriodo($periodo);
        
        
        $whereTrimestres=" AND a.trimestres>0 ";
        if($numPeriodo) $whereTrimestres = " AND (a.trimestres & $numPeriodo = $numPeriodo) ";
        $sql = "SELECT s.id as numSocio, s.telefono_1 as telefono_1, s.telefono_2 as telefono_2,
                     concat(s.apellidos,', ',s.nombre) as asistente, 
                     a.trimestres as trimestres,
                     a.pagado as pagado,
                     p.importe as pago_trimestre,
                     s.email as email
              FROM casal_asistentes a
              LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id 
              LEFT JOIN casal_pagos p ON p.trimestre=$numPeriodo AND p.id_taller=$taller AND p.id_socio=s.id
              WHERE a.id_taller='$taller'  $whereTrimestres ORDER BY $orderBy";
        
        // mensaje('Info getAsistentesTaller',$sql);
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    function getTextosCursoPeriodo($curso,$periodo){
        $textoCurso=$this->getNombreCurso($curso);
        $textoPeriodo=$this->getTextoPeriodo($periodo);
        return array('textoCurso'=>$textoCurso,'textoPeriodo'=>$textoPeriodo);
    }


    function getListaEsperaTaller($taller,$orden,$periodo=0) {
        $numPeriodo=$this->getNumPeriodo($periodo);
        $whereTrimestres=" AND r.trimestres>0 ";
        $ordenBy="r.orden";
        if ($orden==1) $ordenBy="s.apellidos, s.nombre";
        if ($orden==2) $ordenBy="s.id";
        if($numPeriodo) $whereTrimestres = " AND (r.trimestres & $numPeriodo = $numPeriodo) ";
        $sql = "SELECT s.id as numSocio, s.telefono_1 as telefono_1, s.telefono_2 as telefono_2,
              concat(s.apellidos,', ',s.nombre) as asistente, r.orden as orden, r.trimestres
              FROM casal_reservas r
              LEFT JOIN casal_socios_nuevo s ON r.id_socio=s.id 
              WHERE r.id_taller='$taller' $whereTrimestres ORDER BY $ordenBy";
        mensaje($orden);
        mensaje($sql);
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getListaEspera($curso,$orden,$periodo=0) {
        $numPeriodo=$this->getNumPeriodo($periodo);
        $whereTrimestres=" AND r.trimestres>0 ";
        $ordenBy="t.nombre, ";
        if($orden==0) $ordenBy.="r.orden";
        if ($orden==1) $ordenBy.="s.apellidos, s.nombre";
        if ($orden==2) $ordenBy.="s.id";
        if($numPeriodo) $whereTrimestres = " AND (r.trimestres & $numPeriodo = $numPeriodo) ";
        $sql = "SELECT s.id as numSocio, s.telefono_1 as telefono_1, s.telefono_2 as telefono_2,
              concat(s.apellidos,', ',s.nombre) as asistente, r.orden as orden, r.trimestres,
              t.nombre as nombreTaller
              FROM casal_reservas r
              LEFT JOIN casal_socios_nuevo s ON r.id_socio=s.id 
              LEFT JOIN casal_talleres t ON r.id_taller=t.id
              WHERE t.id_curso='$curso' $whereTrimestres ORDER BY $ordenBy";
        // mensaje($orden);
        mensaje($sql);
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    function getResumenTalleresSumas($curso,$tipoTaller="",$periodo="") {

        switch($tipoTaller){
            case 'tots':
                $whereTipoTaller="";
                break;
            case 'voluntaris':
                $whereTipoTaller=" AND t.tipo_taller='Voluntari' ";
                break;
            case 'professionals':
                $whereTipoTaller=" AND t.tipo_taller='Professional' ";
                break;
            default :
                $whereTipoTaller="";
        }
        $sql = "SELECT *, a.id as id_agrupacion, t.id as idTaller, t.nombre_corto as nombre_taller, 
                t.precio_trimestre as precio_trimestre,
                t.precio_rosa_trimestre as precio_rosa_trimestre,
                t.precio_curso as precio_curso,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_rosa_curso,
                d1.nombre_catalan as dia_1, 
                d2.nombre_catalan as dia_2,
                a.nombre as nombreAgrupacion,
                a.precio_trimestre as precioTrimestreAgrupacion,
                a.precio_curso as precioCursoAgrupacion
                FROM casal_talleres t
                LEFT JOIN casal_dias_semana d1 ON d1.id=t.id_dia_semana_1
                LEFT JOIN casal_dias_semana d2 ON d2.id=t.id_dia_semana_2
                LEFT JOIN casal_agrupaciones a ON a.id=t.id_agrupacion
                WHERE t.id_curso='$curso' $whereTipoTaller  ORDER BY t.nombre";
        //mensaje($sql);
        $result = $this->db->query($sql)->result();
        $salida = array();
        foreach ($result as $k => $v) {
            $totales = $this->getTotalAsistentesTaller($v->idTaller,$periodo);
            $salida[] = array('agrupado' => $v->id_agrupacion,
                'nombreAgrupacion' => $v->nombreAgrupacion,
                'precioTrimestreAgrupacion' => $v->precioTrimestreAgrupacion,
                'precioCursoAgrupacion' => $v->precioCursoAgrupacion,
                'precioTrimestre' => $v->precio_trimestre,
                'precioRosaTrimestre' => $v->precio_rosa_trimestre,
                'precioCurso' => $v->precio_curso,
                'precioRosaCurso' => $v->precio_rosa_curso,
                'dia1' => $v->dia_1,
                'inicio1' => $v->inicio_1,
                'final1' => $v->final_1,
                'dia2' => $v->dia_2,
                'inicio2' => $v->inicio_2,
                'final2' => $v->final_2,
                'id' => $v->idTaller,
                'nombre' => $v->nombre_taller,
                'tipo_taller'=>$v->tipo_taller,
                'numAsistentes' => $totales['numAsistentes'],
                'total' => $totales['total']);
        }
        return $salida;
    }
    function getResumenTalleres($curso,$tipoTaller="",$periodo="") {
        //log_message('INFO','getResumenTalleres '.$curso.' '.$tipoTaller.' '.$periodo);
        switch($tipoTaller){
            case 'tots':
                $whereTipoTaller="";
                break;
            case 'voluntaris':
                $whereTipoTaller=" AND t.tipo_taller='Voluntari' ";
                break;
            case 'professionals':
                $whereTipoTaller=" AND t.tipo_taller='Professional' ";
                break;
            default :
                $whereTipoTaller="";
        }
        $sql = "SELECT *, a.id as id_agrupacion, t.id as idTaller, t.nombre_corto as nombre_taller, 
                t.precio_trimestre as precio_trimestre,
                t.precio_rosa_trimestre as precio_rosa_trimestre,
                t.precio_curso as precio_curso,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_rosa_curso,
                t.id_periodo as id_periodo,
                d1.nombre_catalan as dia_1, 
                d2.nombre_catalan as dia_2,
                a.nombre as nombreAgrupacion,
                a.precio_trimestre as precioTrimestreAgrupacion,
                a.precio_curso as precioCursoAgrupacion
                FROM casal_talleres t
                LEFT JOIN casal_dias_semana d1 ON d1.id=t.id_dia_semana_1
                LEFT JOIN casal_dias_semana d2 ON d2.id=t.id_dia_semana_2
                LEFT JOIN casal_agrupaciones a ON a.id=t.id_agrupacion
                WHERE t.id_curso='$curso' $whereTipoTaller  ORDER BY t.nombre";

        $result = $this->db->query($sql)->result();
        $salida = array();
        foreach ($result as $k => $v) {
            $totales = $this->getTotalAsistentesTaller($v->idTaller,$periodo);
            $salida[] = array('agrupado' => $v->id_agrupacion,
                'nombreAgrupacion' => $v->nombreAgrupacion,
                'precioTrimestreAgrupacion' => $v->precioTrimestreAgrupacion,
                'precioCursoAgrupacion' => $v->precioCursoAgrupacion,
                'precioTrimestre' => $v->precio_trimestre,
                'precioRosaTrimestre' => $v->precio_rosa_trimestre,
                'precioCurso' => $v->precio_curso,
                'precioRosaCurso' => $v->precio_rosa_curso,
                'dia1' => $v->dia_1,
                'inicio1' => $v->inicio_1,
                'final1' => $v->final_1,
                'dia2' => $v->dia_2,
                'inicio2' => $v->inicio_2,
                'final2' => $v->final_2,
                'id' => $v->idTaller,
                'id_periodo' => $v->id_periodo,
                'nombre' => $v->nombre_taller,
                'tipo_taller'=>$v->tipo_taller,
                'numAsistentes' => $totales['numAsistentes'],
                'periodo' => $v->id_periodo,
                'total' => $totales['total']);
        }
        return $salida;
    }

    function getResumenTalleresPagadoSumas($curso,$tipoTaller="",$periodo="") {
        //log_message('INFO','getResumenTalleresSumas '.$curso.' '.$tipoTaller.' '.$periodo);
        switch($tipoTaller){
            case 'tots':
                $whereTipoTaller="";
                break;
            case 'voluntaris':
                $whereTipoTaller=" AND t.tipo_taller='Voluntari' ";
                break;
            case 'professionals':
                $whereTipoTaller=" AND t.tipo_taller='Professional' ";
                break;
            default :
                $whereTipoTaller="";
        }
        $sql = "SELECT *, a.id as id_agrupacion, t.id as idTaller, t.nombre_corto as nombre_taller, 
                t.precio_trimestre as precio_trimestre,
                t.precio_rosa_trimestre as precio_rosa_trimestre,
                t.precio_curso as precio_curso,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_rosa_curso,
                d1.nombre_catalan as dia_1, 
                d2.nombre_catalan as dia_2,
                a.nombre as nombreAgrupacion,
                a.precio_trimestre as precioTrimestreAgrupacion,
                a.precio_curso as precioCursoAgrupacion
                FROM casal_talleres t
                LEFT JOIN casal_dias_semana d1 ON d1.id=t.id_dia_semana_1
                LEFT JOIN casal_dias_semana d2 ON d2.id=t.id_dia_semana_2
                LEFT JOIN casal_agrupaciones a ON a.id=t.id_agrupacion
                WHERE t.id_curso='$curso' $whereTipoTaller  ORDER BY t.nombre";

        $result = $this->db->query($sql)->result();
        $salida = array();
        foreach ($result as $k => $v) {
            $totales = $this->getTotalAsistentesTaller($v->idTaller,$periodo);
            $salida[] = array('agrupado' => $v->id_agrupacion,
                'nombreAgrupacion' => $v->nombreAgrupacion,
                'precioTrimestreAgrupacion' => $v->precioTrimestreAgrupacion,
                'precioCursoAgrupacion' => $v->precioCursoAgrupacion,
                'precioTrimestre' => $v->precio_trimestre,
                'precioRosaTrimestre' => $v->precio_rosa_trimestre,
                'precioCurso' => $v->precio_curso,
                'precioRosaCurso' => $v->precio_rosa_curso,
                'dia1' => $v->dia_1,
                'inicio1' => $v->inicio_1,
                'final1' => $v->final_1,
                'dia2' => $v->dia_2,
                'inicio2' => $v->inicio_2,
                'final2' => $v->final_2,
                'id' => $v->idTaller,
                'nombre' => $v->nombre_taller,
                'tipo_taller'=>$v->tipo_taller,
                'numAsistentes' => $totales['numAsistentes'],
                'total' => $totales['total']);
        }
        return $salida;
    }
    function getResumenTalleresPagado($curso,$tipoTaller="",$periodo="") {
        //log_message('INFO','getResumenTalleresPagado '.$curso.' '.$tipoTaller.' '.$periodo);
        $salida = array();
        $salida['periodo']=$periodo;
        $salida['tipoTaller']=$tipoTaller;
        
        $salida['textoCurso']=$this->getTextoCurso($curso);
        $salida['textoPeriodo']=$this->talleres_model->getTextoPeriodo($periodo);
        $numPeriodo=$this->talleres_model->getNumPeriodo($periodo);
        
        //log_message('INFO','getResumenTalleresPagado '.$curso.' '.$tipoTaller.' '.$numPeriodo);
        
        switch($tipoTaller){
            case 'tots':
                $whereTipoTaller="";
                $salida['nombreTipoTaller']="Tots: Voluntaris i Professionals";
                break;
            case 'voluntaris':
                $whereTipoTaller=" AND t.tipo_taller='Voluntari' ";
                $salida['nombreTipoTaller']="Voluntaris";
                break;
            case 'professionals':
                $whereTipoTaller=" AND t.tipo_taller='Professional' ";
                $salida['nombreTipoTaller']="Professionals";
                break;
            default :
                $whereTipoTaller="";
                $salida['nombreTipoTaller']="--";
        }
        
        $pvTrimestre=" AND pv.trimestre='$numPeriodo' AND ";
        $ppTrimestre=" AND pp.trimestre='$numPeriodo' AND ";
        if($numPeriodo==7){
            $pvTrimestre=" AND ";
            $ppTrimestre=" AND ";
        }
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $sql = "SELECT 
                        sum(pp.importe) as  importe_profesional, 
                        sum(pv.importe) as  importe_voluntario,
                        count(pp.id_socio) as num_asistentes_profesional,
                        count(pv.id_socio) as num_asistentes_voluntario
                FROM casal_talleres t
                LEFT JOIN casal_pagos pv ON t.id=pv.id_taller $pvTrimestre t.tipo_taller='Voluntari'
                LEFT JOIN casal_pagos pp ON t.id=pp.id_taller $ppTrimestre t.tipo_taller='Professional' 
                WHERE t.id_curso='$curso' $whereTipoTaller  GROUP BY t.nombre ORDER BY t.nombre";

        $result = $this->db->query($sql)->result();
        $sql = "SELECT DISTINCT t.id as id, t.nombre as nombre_taller
                FROM casal_talleres t
                LEFT JOIN casal_pagos pv ON t.id=pv.id_taller $pvTrimestre t.tipo_taller='Voluntari'
                LEFT JOIN casal_pagos pp ON t.id=pp.id_taller $ppTrimestre t.tipo_taller='Professional' 
                WHERE t.id_curso='$curso' $whereTipoTaller  ORDER BY t.nombre";
        $result2 = $this->db->query($sql)->result();
        
        $salida['talleresTotalProfesional']=0;
        $salida['talleresTotalVoluntario']=0;
        $salida['talleresTotalTodos']=0;
        $salida['talleresTotalNumAsisntentesProfesional']=0;
        $salida['talleresTotalNumAsisntentesVoluntario']=0;
        $salida['talleresTotalNumAsisntentesTodos']=0;
        
        $salida['talleres']=array();
        foreach ($result as $k => $v) {
            //$totales = $this->getTotalAsistentesTaller($v->idTaller,$periodo);
            $salida['talleres'][] = array(
                'id' => $result2[$k]->id,
                'nombreTaller' => $result2[$k]->nombre_taller,
                'importeProfesional' => $v->importe_profesional,
                'importeVoluntario' => $v->importe_voluntario,
                'importeTodos' => $this->quitarCero($v->importe_voluntario+$v->importe_profesional),
                'numAsistentesProfesional'=>$this->quitarCero($v->num_asistentes_profesional),
                'numAsistentesVoluntario'=>$this->quitarCero($v->num_asistentes_voluntario),
                'numAsistentesTodos' => $this->quitarCero($v->num_asistentes_voluntario+$v->num_asistentes_profesional),    
                );
            $salida['talleresTotalProfesional']+=$v->importe_profesional;
            $salida['talleresTotalVoluntario']+=$v->importe_voluntario;
            $salida['talleresTotalTodos']+=$v->importe_voluntario+$v->importe_profesional;
            $salida['talleresTotalNumAsisntentesProfesional']+=$v->num_asistentes_profesional;
            $salida['talleresTotalNumAsisntentesVoluntario']+=$v->num_asistentes_voluntario;
            $salida['talleresTotalNumAsisntentesTodos']+=$v->num_asistentes_voluntario+$v->num_asistentes_profesional;
        }
        $salida['talleresNumTalleres']=count($salida['talleres']);
        
        
        
        
        //var_dump($salida);
        return $salida;
    }

    function quitarCero($valor){
        return $valor==0?"":$valor;
    }
    
    
    function getResumenSociosTalleres($curso) {
        $sql = "SELECT  count(`id_taller`) as num_talleres, sum(`pagado`) as totalPagado, sum(trimestres) as totalTrimestres
                FROM `casal_asistentes` a
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_cursos c ON t.id_curso=c.id 
                LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
                WHERE c.id='$curso' GROUP BY id_socio ";
        $result2 = $this->db->query($sql)->result();

        $sql="SELECT DISTINCT c.nombre as nombre_curso, `id_socio`, s.nombre as nombre_socio, s.apellidos
                FROM `casal_asistentes` a
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_cursos c ON t.id_curso=c.id 
                LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
                WHERE c.id='$curso' ORDER BY concat(s.apellidos,s.nombre)";
        $result = $this->db->query($sql)->result();
        
        $salida = array();
        foreach ($result as $k => $v) {
            $sql="SELECT count(id_taller) as num_talleres,
                            sum(pagado) as totalPagado,
                            sum(trimestres) as totalTrimestres
                  FROM  casal_asistentes a         
                  LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_cursos c ON t.id_curso=c.id 
                LEFT JOIN casal_socios_nuevo s ON s.num_socio=a.id_socio
                WHERE c.id='$curso' AND id_socio='". $v->id_socio."'";
                log_message('INFO','--------------- '.$sql);
             $row = $this->db->query($sql)->row();         
            $salida[] = array(
                'nombre_curso' => $v->nombre_curso,
                'id_socio' => $v->id_socio,
                'nombre_socio' => $v->nombre_socio,
                'apellidos' => $v->apellidos,
                'totalPagado'=>$row->totalPagado,
                'num_talleres'=>$row->num_talleres,
            );

        }
        return $salida;
    }

    function getFechasPeriodo($curso,$numPeriodo){
        
        $sql="SELECT * FROM casal_fechas_talleres WHERE id_curso='$curso' AND num_periodo='$numPeriodo'";
        $textoPeriodo=$this->getTextoPeriodos($numPeriodo);
        $textoCurso=$this->getTextoCurso($curso);
        $salida=array('textoPeriodo'=>$textoPeriodo,'textoCurso'=>$textoCurso);
        if($this->db->query($sql)->num_rows()==0) return $salida;
        else {
            return $this->db->query($sql)->row_array();
        }
    }
    
    function grabarFechas(){
        extract($_POST);
        $numPeriodo=$this->getNumPeriodo($periodo);
        if($this->db->query("SELECT * FROM casal_fechas_talleres WHERE id_curso='$curso' AND num_periodo='$numPeriodo'")->num_rows()==0){
            $this->db->query("INSERT INTO casal_fechas_talleres SET id_curso='$curso', num_periodo='$numPeriodo'");
        }
        if(isset($nombre)){
            $sql="UPDATE casal_fechas_talleres SET $nombre='$valor' WHERE id_curso='$curso' AND num_periodo='$numPeriodo'";
            $this->db->query("UPDATE casal_fechas_talleres SET $nombre='$valor' WHERE id_curso='$curso' AND num_periodo='$numPeriodo'");
        }
    }
    
    function getTotalAsistentesTaller($taller,$periodo='C') {
        // $numPeriodo=$this->getNumPeriodo($periodo);
        // if ($numPeriodo==0 || $numPeriodo==7) 
        //     $whereTrimestre=" AND p.trimestre>0 ";
        // else 
        //     $whereTrimestre=" AND p.trimestre = $numPeriodo ";
        
        // $sql = "SELECT count(*) as numAsistentes, sum(importe) as total
        //       FROM casal_pagos p
        //       WHERE p.id_taller='$taller' $whereTrimestre ";
        

        $numPeriodo=$this->getNumPeriodo($periodo);
        
        
        $whereTrimestres=" AND a.trimestres>0 ";
        if($numPeriodo) $whereTrimestres = " AND (a.trimestres & $numPeriodo = $numPeriodo) ";
        $sql = "SELECT count(*) as numAsistentes, sum(p.importe) as total
              FROM casal_asistentes a
              LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id 
              LEFT JOIN casal_pagos p ON p.trimestre=$numPeriodo AND p.id_taller=$taller AND p.id_socio=s.id
              WHERE a.id_taller='$taller'  $whereTrimestres ";
      
    //   mensaje( $sql);



        $result = $this->db->query($sql)->row_array();
        return $result;
    }

    function getTablaResumenCurso($curso,$tipoTaller="",$periodo="") {
        $talleres = $this->talleres_model->getResumenTalleres($curso,$tipoTaller,$periodo);
        
        $talleresSumas = $this->talleres_model->getResumenTalleresSumas($curso,$tipoTaller,$periodo);
 
        
       if($tipoTaller=='tots'){
        $cabeceraTabla='<table class="table table-bordered table-hover"><thead>
        <tr>
            <td colspan="2"></td>
            <th colspan="2" class="text-center">Tallers Voluntaris</th>
            <th colspan="2" class="text-center">Tallers Professionals</th>
            <th colspan="2" class="text-center" style="border-top:2px solid black;border-left:2px solid black;border-right:2px solid black;">Tallers Tots</th>
          </tr>
          <tr>
            <th>Núm.</th>
            <th >Nom Taller</th>
            <th class="text-right">Assistents</th>
            <th class="text-right">Import (€)</th>
            <th class="text-right">Assistents</th>
            <th class="text-right">Import (€)</th>
            <th class="text-right" style="border-left:2px solid black;">Assistents</th>
            <th class="text-right" style="border-right:2px solid black;">Import (€)</th>
          </tr>
          </thead>';
        
        $num = 0;
        $numAsistentesVoluntarios = 0;
        $totalImportesVoluntarios = 0;
        $numAsistentesProfesionales = 0;
        $totalImportesProfesionales = 0;
        
        $body='<tbody>';
        foreach ($talleres as $k => $taller) {
            $num++;
            $body.='<tr>';
            $body.='<td class="text-left">'.$taller['id'].'</td>';
            
            if ($taller['tipo_taller'] == "Voluntari") {
                $body.='<td class="text-left">'.ucfirst(strtolower($taller['nombre'])).'</td>';
                $asistentes=intval($taller['numAsistentes'] == 0 ? "" : $taller['numAsistentes']);
                $body .='<td class="text-right">'.$asistentes.'</td>';
                $body .= '<td class="text-right">' . $taller['total'] . '</td>';
                $body .= '<td class="text-right"></td>';
                $body .= '<td class="text-right"></td>';
                $body .='<td class="text-right" style="border-left:2px solid black;">'.$asistentes.'</td>';
                $body .= '<td class="text-right" style="border-right:2px solid black;">' . $taller['total'] . '</td>';
                $numAsistentesVoluntarios+=$asistentes;
                $totalImportesVoluntarios+=$taller['total'];
            }
            if ($taller['tipo_taller'] == "Professional") {
                $body.='<td class="text-left" style="color:blue;">'.ucfirst(strtolower($taller['nombre'])).'</td>';
                $body .= '<td class="text-right"></td>';
                $body .= '<td class="text-right"></td>';
                $asistentes=$taller['numAsistentes'] == 0 ? "" : $taller['numAsistentes'];
                $body .='<td class="text-right">'.$asistentes.'</td>';
                $body .= '<td class="text-right">' . $taller['total'] . '</td>';
                $body .='<td class="text-right " style="border-left:2px solid black;">'.$asistentes.'</td>';
                $body .= '<td class="text-right " style="border-right:2px solid black;">' . $taller['total'] . '</td>';
                $numAsistentesProfesionales+=intval($asistentes);
                $totalImportesProfesionales+=$taller['total'];
            }

            $body.='</tr>';
        }
        $body.='</tbody>';
 
        
        
        $pieTabla='<tfoot>';
        $pieTabla.='<tr>';
        $pieTabla.='<th>'.$num.'</th>';
        $pieTabla.='<th></th>';
        $pieTabla.='<th class="text-right">'.$numAsistentesVoluntarios.'</th>';
        $pieTabla.='<th class="text-right">'.number_format($totalImportesVoluntarios,2).'</th>';
        $pieTabla.='<th class="text-right">'.$numAsistentesProfesionales.'</th>';
        $pieTabla.='<th class="text-right">'.number_format($totalImportesProfesionales,2).'</th>';
        $pieTabla.='<th class="text-right" style="border-top:4px solid black;border-left:4px solid black;border-bottom:4px solid black;">'.($numAsistentesVoluntarios+$numAsistentesProfesionales).'</th>';
        $pieTabla.='<th class="text-right" style="border-top:4px solid black;border-bottom:4px solid black;border-right:4px solid black;">'.(number_format($totalImportesVoluntarios,2)+number_format($totalImportesProfesionales,2)).'</th>';
        $pieTabla.='</tr>';
        $pieTabla.='</tfoot></table>';
        $tabla  = $cabeceraTabla;
        $tabla .= $body;
        $tabla .= $pieTabla;
        
        return $tabla;
    }
       if($tipoTaller=='voluntaris'){
        $cabeceraTabla='<table class="table table-bordered table-hover"><thead>
        <tr>
            <td colspan="2"></td>
            <th colspan="2">Tallers Voluntaris</th>
            
          </tr>
          <tr>
            <th>Núm.</th>
            <th>Nom Taller</th>
            <th>Assistents</th>
            <th>Import (€)</th>
            
          </tr>
          </thead>';
        
        $num = 0;
        $numAsistentesVoluntarios = 0;
        $totalImportesVoluntarios = 0;
        $numAsistentesProfesionales = 0;
        $totalImportesProfesionales = 0;
        
        $body='<tbody>';
        foreach ($talleres as $k => $taller) {
            if ($taller['tipo_taller'] == "Professional") continue;
            $num++;
            $body.='<tr>';
            $body.='<td class="text-left">'.$taller['id'].'</td>';
            $body.='<td class="text-left">'.$taller['nombre'].'</td>';
            if ($taller['tipo_taller'] == "Voluntari") {
                $asistentes=intval($taller['numAsistentes'] == 0 ? "" : $taller['numAsistentes']);
                $body .='<td class="text-right">'.$asistentes.'</td>';
                $body .= '<td class="text-right">' . $taller['total'] . '</td>';
                $numAsistentesVoluntarios+=$asistentes;
                $totalImportesVoluntarios+=$taller['total'];
            }
            if ($taller['tipo_taller'] == "Professional") {
                $body .= '<td class="text-right"></td>';
                $body .= '<td class="text-right"></td>';
                
            }

            $body.='</tr>';
        }
        $body.='</tbody>';
 
        
        
        $pieTabla='<tfoot>';
        $pieTabla.='<tr>';
        $pieTabla.='<th>'.$num.'</th>';
        $pieTabla.='<th></th>';
        $pieTabla.='<th class="text-right">'.$numAsistentesVoluntarios.'</th>';
        $pieTabla.='<th class="text-right">'.number_format($totalImportesVoluntarios,2).'</th>';
        $pieTabla.='</tr>';
        $pieTabla.='</tfoot></table>';
        $tabla  = $cabeceraTabla;
        $tabla .= $body;
        $tabla .= $pieTabla;
        
        return $tabla;
    }
       if($tipoTaller=='professionals'){
        $cabeceraTabla='<table class="table table-bordered table-hover"><thead>
        <tr>
            <td colspan="2"></td>
            <th colspan="2">Tallers Professionals</th>
          </tr>
          <tr>
            <th>Núm.</th>
            <th>Nom Taller</th>
            
            <th>Assistents</th>
            <th>Import (€)</th>
          </tr>
          </thead>';
        
        $num = 0;
        $numAsistentesVoluntarios = 0;
        $totalImportesVoluntarios = 0;
        $numAsistentesProfesionales = 0;
        $totalImportesProfesionales = 0;
        
        $body='<tbody>';
        foreach ($talleres as $k => $taller) {
            if ($taller['tipo_taller'] == "Voluntari") continue;
            $num++;
            $body.='<tr>';
            $body.='<td class="text-left">'.$taller['id'].'</td>';
            $body.='<td class="text-left">'.$taller['nombre'].'</td>';
            
            if ($taller['tipo_taller'] == "Professional") {
                
                $asistentes=$taller['numAsistentes'] == 0 ? "" : $taller['numAsistentes'];
                $body .='<td class="text-right">'.$asistentes.'</td>';
                $body .= '<td class="text-right">' . $taller['total'] . '</td>';
                $numAsistentesProfesionales+=intval($asistentes);
                $totalImportesProfesionales+=$taller['total'];
            }

            $body.='</tr>';
        }
        $body.='</tbody>';
 
        
        
        $pieTabla='<tfoot>';
        $pieTabla.='<tr>';
        $pieTabla.='<th>'.$num.'</th>';
        $pieTabla.='<th></th>';
        $pieTabla.='<th class="text-right">'.$numAsistentesProfesionales.'</th>';
        $pieTabla.='<th class="text-right">'.number_format($totalImportesProfesionales,2).'</th>';
        $pieTabla.='</tr>';
        $pieTabla.='</tfoot></table>';
        
        $tabla  = $cabeceraTabla;
        $tabla .= $body;
        $tabla .= $pieTabla;
        
        return $tabla;
    }
    }

    function getTablaSociosInscritosCurso($curso) {
        $socios = $this->talleres_model->getResumenSociosTalleres($curso);
        //var_dump($socios);
        // $totalAsistentes=$this->getTotalAsistentesTaller($taller);
        //echo $totalAsistentes['numAsistentes'];
        $cabeceraTabla = '<table class="table table-bordered"><tbody>
                <thead>
                    <tr >
                        <th class="col-sm-1 text-right">Núm</th>
                        <th class="col-sm-6">Usuari / Usuària</th>
                        <th class="col-sm-1 text-right">Nombre Tallers Inscrits</th>
                        <th class="col-sm-2 text-right">Imports (€)</th>';

        $num = 0;
        $numSocios = 0;
        $total = 0;


        $tabla = $cabeceraTabla;

        foreach ($socios as $k => $socio) {

            $tabla .= '<tr>';
            $tabla .= '<td class="text-right">';
            $tabla .= $socio['id_socio'];
            $tabla .= '</td>';
            $tabla .= '<td>';

            $tabla .= $socio['apellidos'] . ', ' . $socio['nombre_socio'];
            $tabla .= '</td>';
            $tabla .= '<td class="text-right">';
            $tabla .= $socio['num_talleres'] == 0 ? "" : $socio['num_talleres'];
            $tabla .= '</td>';
            $tabla .= '<td class="text-right">';
            $tabla .= number_format($socio['totalPagado'],2);
            $tabla .= '</td>';
            $tabla .= '</tr>';
            $num += $socio['num_talleres'];
            $numSocios += 1;
            $total += number_format($socio['totalPagado'],2);
        }
        $pieTabla = '</tr></thead><tfoot><tr>';
        $pieTabla .= '<th>';
        $pieTabla .= '';
        $pieTabla .= '</th>';
        $pieTabla .= '<th>';
        $pieTabla .= $numSocios;
        $pieTabla .= '</th>';
        $pieTabla .= '<th class="text-right">';
        $pieTabla .= $num;
        $pieTabla .= '</th>';
        $pieTabla .= '<th class="text-right">';
        $pieTabla .= number_format($total,2);
        $pieTabla .= '</th>';
        $pieTabla .= '</tr></tfoot></tbody></table>';
        $tabla .= $pieTabla;

        return $tabla;
    }

    function getTablaAsistentesTaller($taller, $orden,$periodo="") {
        $asistentes = $this->getAsistentesTaller($taller, $orden,$periodo);
        $listaEspera = $this->getListaEsperaTaller($taller,$periodo);
        
        $nombreCurso = $this->getNombreCurso($this->getCurso($taller));
        $datosTaller = $this->getDatosTaller($taller);
        $totalAsistentes = $this->getTotalAsistentesTaller($taller,$periodo);
        
        // echo 'taller '.$taller;
        // echo 'periodo '.$periodo;
        // echo $totalAsistentes['numAsistentes'];
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
        $cabeceraTabla = '<h3>Taller: ' . $nombreCurso . ' - ' . $datosTaller->nombreTaller . '<h3>Professor: ' . $datosTaller->nombreProfesor . '</h3>
            <h4>' . $dias . ': ' . $dia1 . $separador . $dia2 . '</h4>
                
            <table class="table table-bordered"><tbody>
                <thead>
                    <tr>
                        <th></th>
                        <th>Núm Usuari/Usuària</th>
                        <th>Nom Usuari/Usuària</th>
                        <th>Telèfon</th>
                        <th>Email</th>
                        <th>Trimestres</th>
                        <th style="text-align:right;padding-right:20px;">Pagat (€)</th></tr>';

        $suma = '<tr>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= $totalAsistentes['numAsistentes'];
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        
        $suma .= '<th style="text-align:right;padding-right:20px;">';
        $suma .= $totalAsistentes['total'] == "" ? 0 : $totalAsistentes['total'];
        $suma .= '</th>';
        $suma .= '</tr></tfoot>';


        $tabla = $cabeceraTabla;
        foreach ($asistentes as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];


            switch ($v['trimestres']) {
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

            $tabla .= '<tr>';
            $tabla .= '<td width="6">'.($k+1).'</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td width="180">';
            $tabla .= $telefono;
            $tabla .= '</td>';
            $tabla .= '<td width="180">';
            $tabla .= '<a href="mailto:'.$v['email'].'">';
            $tabla .= $v['email'];
            $tabla .= '</a>';

            $tabla .= '</td>';
            $tabla .= '<td style="text-align:center;">';
           // $tabla .= $inscrito;
            $tabla .= $periodo;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:right;padding-right:20px;">';
            $tabla .= number_format($v['pago_trimestre'],2);
            $tabla .= '</td>';
            $tabla .= '</tr>';
        }
        $tabla .= $suma;
        foreach ($listaEspera as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];

            $tabla .= '<tr style="background-color:yellow;">';
            $tabla .= '<td>';
            $tabla .= '('.$v['orden'].')';
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td >';
            $tabla .= $telefono;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:center;">';
            $tabla .= $this->talleres_model->getTextoPeriodos($v['trimestres']);
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= 'Lista Espera';
            $tabla .= '</td>';
            
            $tabla .= '</tr>';
        }

        $tabla .= '</thead></tbody></table>';
        return $tabla;
    }

    function getTablaReservasTaller($taller, $orden,$periodo="") {
        // $asistentes = $this->getAsistentesTaller($taller, $orden,$periodo);
        mensaje('orden '.$orden);
        $listaEspera = $this->getListaEsperaTaller($taller,$orden,$periodo);
        
        $nombreCurso = $this->getNombreCurso($this->getCurso($taller));
        $datosTaller = $this->getDatosTaller($taller);
        $totalAsistentes = $this->getTotalAsistentesTaller($taller,$periodo);
        
        // echo 'taller '.$taller;
        // echo 'periodo '.$periodo;
        // echo $totalAsistentes['numAsistentes'];
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
        $cabeceraTabla = '<h3>Taller: ' . $nombreCurso . ' - ' . $datosTaller->nombreTaller . '<h3>Professor: ' . $datosTaller->nombreProfesor . '</h3>
            <h4>' . $dias . ': ' . $dia1 . $separador . $dia2 . '</h4>
                
            <table class="table table-bordered"><tbody>
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Núm Usuari/Usuària</th>
                        <th>Nom Usuari/Usuària</th>
                        <th>Telèfon</th>
                        <th>Trimestres</th>
                        <th style="text-align:right;padding-right:20px;">Pagat (€)</th></tr>';

        $suma = '<tr>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= $totalAsistentes['numAsistentes'];
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        $suma .= '<th>';
        $suma .= '';
        $suma .= '</th>';
        
        $suma .= '<th style="text-align:right;padding-right:20px;">';
        $suma .= $totalAsistentes['total'] == "" ? 0 : $totalAsistentes['total'];
        $suma .= '</th>';
        $suma .= '</tr></tfoot>';


        $tabla = $cabeceraTabla;
        /*
        foreach ($asistentes as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];


            switch ($v['trimestres']) {
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

            $tabla .= '<tr>';
            $tabla .= '<td width="6">'.($k+1).'</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td width="180">';
            $tabla .= $telefono;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:center;">';
           // $tabla .= $inscrito;
            $tabla .= $periodo;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:right;padding-right:20px;">';
            $tabla .= $v['pago_trimestre'];
            $tabla .= '</td>';
            $tabla .= '</tr>';
        }
        */
        // $tabla .= $suma;
        foreach ($listaEspera as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];

            $tabla .= '<tr style="background-color:yellow;">';
            $tabla .= '<td>';
            $tabla .= '('.$v['orden'].')';
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td >';
            $tabla .= $telefono;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:center;">';
            $tabla .= $this->talleres_model->getTextoPeriodos($v['trimestres']);
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= 'Lista Espera';
            $tabla .= '</td>';
            
            $tabla .= '</tr>';
        }

        $tabla .= '</thead></tbody></table>';
        return $tabla;
    }

    function getTablaReservasCurso($curso, $orden,$periodo="") {
        // $asistentes = $this->getAsistentesTaller($taller, $orden,$periodo);
        mensaje('orden '.$orden);
        $listaEspera = $this->getListaEspera($curso,$orden,$periodo);
        
        $nombreCurso = $this->getNombreCurso($curso);

        // $datosTaller = $this->getDatosTaller($taller);
        // $totalAsistentes = $this->getTotalAsistentesTaller($taller,$periodo);
        
        // echo 'taller '.$taller;
        // echo 'periodo '.$periodo;
        // echo $totalAsistentes['numAsistentes'];
        // $dia1 = "";
        
        // if (!is_null($datosTaller->dia1))
        //     $dia1 = $datosTaller->dia1 . '  ' . substr($datosTaller->inicio1, 0, 5) . ' - ' . substr($datosTaller->final1, 0, 5);
        // $dia2 = "";
        // if (!is_null($datosTaller->dia2))
        //     $dia2 = $datosTaller->dia2 . '  ' . substr($datosTaller->inicio2, 0, 5) . ' - ' . substr($datosTaller->final2, 0, 5);
        // $dias = 'Dia';
        // $separador = '  ';
        // if ($dia2) {
        //     $dias = 'Dias';
        //     $separador = ' / ';
        // }
        $cabeceraTabla = '<h3>Curso: ' . $nombreCurso .' - Periodo: '.$this->getNombrePeriodo($periodo).'</h3>
              
            <table class="table table-bordered"><tbody>
                <thead>
                    <tr>
                        <th>Taller</th>
                        <th>Ordre</th>
                        <th>Núm </th>
                        <th>Nom Usuari/Usuària</th>
                        <th>Telèfon</th>
                    </tr>
                </thead>
                <tbody>';

        
        
        


        $tabla = $cabeceraTabla;
        /*
        foreach ($asistentes as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];


            switch ($v['trimestres']) {
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

            $tabla .= '<tr>';
            $tabla .= '<td width="6">'.($k+1).'</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td width="180">';
            $tabla .= $telefono;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:center;">';
           // $tabla .= $inscrito;
            $tabla .= $periodo;
            $tabla .= '</td>';
            $tabla .= '<td style="text-align:right;padding-right:20px;">';
            $tabla .= $v['pago_trimestre'];
            $tabla .= '</td>';
            $tabla .= '</tr>';
        }
        */
        // $tabla .= $suma;
        $nombreTallerAnterior="";
        foreach ($listaEspera as $k => $v) {
            $telefono = "";
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_1'] . " / " . $v['telefono_2'];
            if ((trim($v['telefono_1']) == "") && (trim($v['telefono_2']) != ""))
                $telefono = $v['telefono_2'];
            if ((trim($v['telefono_1']) != "") && (trim($v['telefono_2']) == ""))
                $telefono = $v['telefono_1'];

                if($v['nombreTaller']!=$nombreTallerAnterior){
                    $tabla .= '<tr class="linea" ><td></td><td></td><td></td><td></td><td></td></tr>';
                    $tabla .= '<tr style="background-color:yellow;">';
                    $tabla .= '<th>';
                    $tabla .= $v['nombreTaller'];
                    $nombreTallerAnterior=$v['nombreTaller'];
                }
                else{
                    $tabla .= '<tr style="background-color:yellow;">';
                    $tabla .= '<th>';
                $tabla .= '';
            }
            $tabla .= '</th>';
            $tabla .= '<td>';
            $tabla .= '('.$v['orden'].')';
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['numSocio'];
            $tabla .= '</td>';
            $tabla .= '<td>';
            $tabla .= $v['asistente'];
            $tabla .= '</td>';
            $tabla .= '<td >';
            $tabla .= $telefono;
            $tabla .= '</td>';
            
            
            
            $tabla .= '</tr>';
        }

        $tabla .= '</tbody></table>';
        return $tabla;
    }

    function getTablaTalleresListas($curso,$periodo){
        
        $numPeriodo=$this->getNumPeriodo($periodo);
        $idPeriodo=$numPeriodo && 7;
        $sql="SELECT * FROM casal_talleres WHERE id_curso='$curso' AND id_periodo='$idPeriodo'";
        $talleres=$this->db->query($sql)->result_array();
    }
    
    function getTablaTalleres($curso, $socio, $periodo = "") {
        
        //si se han incrementado num_asistentes, pero no utilizado, restituimos el incremento
        $sql = "SELECT id_taller FROM casal_talleres_incrementados ";
        if ($this->db->query($sql)->num_rows() > 0) {
            //echo 'getTablaTalleres '.$this->db->query($sql)->num_rows();
            $result = $this->db->query($sql)->result();
            foreach ($result as $k => $v) {
                $id_taller = $v->id_taller;
                $sql = "UPDATE casal_talleres SET num_maximo=num_maximo-1 WHERE id='$id_taller'";
                $this->db->query($sql);
            }
            $sql = "DELETE FROM casal_talleres_incrementados WHERE 1";
            $this->db->query($sql);
        }

        $resultAsistentes = $this->getAsistentesArray($curso, $socio);
        //var_dump($resultAsistentes);
        //$resultAsistentes = array ['taller'] , ['trimestres]
        
        
        $numPeriodo=$this->talleres_model->getNumPeriodo($periodo);
        //echo '$numPeriodo '.$numPeriodo;
        $resultListaEspera = $this->getListaEsperaArray($curso, $socio,$numPeriodo);
        //$resultAsistentes = array ['taller' , ['trimestres], ['orden']
        //var_dump($resultListaEspera);

        $asistentes = array();
        $asistencias = array();
        foreach ($resultAsistentes as $k => $v) {
            $asistencias[$v['taller']] = $v['trimestres'];
        }
        foreach ($resultListaEspera as $k => $v) {
            $reservas[$v['taller']] = $v['trimestres'];
            $reservasOrden[$v['taller']] = $v['orden'];
        }
        //var_dump($asistencias);
        $talleres = $this->getTalleresArray($curso,$numPeriodo);
        
        $cabeceraTabla = '<table class="table table-bordered"><tbody>';
        $pieTabla = '</tbody></table>';
        $tabla = $cabeceraTabla;
        $nombres = array();
        $numInsritos = array();
        $numMaximo = array();
        $tipoTaller = array();
        
        foreach ($talleres as $k => $v) {
            //mensaje($this->session->tipoUsuario);
            //mensaje($v['tipo_taller']);
           if($this->session->categoria==3 && $v['tipo_taller']=="Professional") continue;
            $nombres[$v['id']] = $v['nombre'];
            $tipoTaller[$v['id']] = $v['tipo_taller'];
           // echo '$v[id] '.$v['id'].' $periodo '.$periodo.'<br>';
            $numInscritos[$v['id']] = $this->getNumInscritos($v['id'], $periodo);
            $numMaximo[$v['id']] = $v['num_maximo'];
            //echo '<br>'.$v['id'].' - '.$numInscritos[$v['id']].'/'.$numMaximo[$v['id']];
        }
        asort($nombres);

        //colocación tabla con nombres ordenados por columnas
        $medio = round(count($nombres) / 2, 0, PHP_ROUND_HALF_UP);
        $nuevaLista = $nombres;
        $filas = array();
        foreach ($nuevaLista as $k => $v) {
            $filas[] = array($k, $v);
        }
        $columnas = array();
        foreach ($filas as $k => $v) {
            $columnas[$k] = $v;
            if (isset($filas[$k + $medio]) && isset($filas[$k + 1]))
                $columnas[$k + $medio] = $filas[$k + 1];
        }
        $nombres = array();
        //$tipoTaller=array();
        foreach ($columnas as $k => $v) {
            $nombres[$v[0]] = $v[1];
            //$tipoTaller[$v[0]]=$v[1];
        }

        //FIN colocación tabla con nombres ordenados por columnas

        $indice = 0;
        foreach ($nombres as $k => $v) {
            $asisnteciaC = "  ";
            $asisnteciaT1 = "   ";
            $asisnteciaT2 = " ";
            $asisnteciaT3 = "  ";
            $marcadoC = "noMarcado";
            $marcadoT1 = "noMarcado";
            $marcadoT2 = "noMarcado";
            $marcadoT3 = "noMarcado";
            if (array_key_exists($k, $asistencias)) {
                $trimestres = $asistencias[$k];
                switch ($trimestres) {
                    case 7:
                        $asisnteciaC = " checked disabled ";
                        $asisnteciaT1 = " disabled ";
                        $asisnteciaT2 = " disabled ";
                        $asisnteciaT3 = " disabled ";
                        $marcadoC = "marcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "noMarcado";

                        break;
                    case 1:
                        $asisnteciaC = " disabled ";
                        
                        $asisnteciaT1 = "  ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = " checked disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 2:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = "  ";
                        $asisnteciaT2 = " checked disabled ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    case 3:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = "  ";
                        $asisnteciaT2 = " checked disabled ";
                        $asisnteciaT3 = " checked disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 4:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " checked disabled  ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    case 5:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " checked disabled  ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = " checked disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 6:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " checked disabled  ";
                        $asisnteciaT2 = " checked disabled ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    default:
                        $asisnteciaC = "  ";
                        $asisnteciaT1 = "   ";
                        $asisnteciaT2 = " ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "noMarcado";
                }
            }
            $ocupacion = "";
            $verReservas = "hide";
            //echo '$tipoTaller '.$tipoTaller[$k].'<br>';
            if ($numInscritos[$k] < $numMaximo[$k]) {
                // $ocupacion='noCompleto';
                //$verReservas='verReservas';
            } else {
                $ocupacion = 'completo';
                $verReservas = 'verReservas';
            }
            if ($indice % 2 == 0)
                $tabla .= '<tr>';

            $sql = "SELECT count(r.id_taller) as nE, t.num_reservas as maxE
                      FROM casal_talleres t
                      LEFT JOIN casal_reservas r ON t.id=r.id_taller
                      WHERE t.id='$k' AND r.trimestres='$numPeriodo'";
            //echo '<br>$sql nE  '.$sql;
            $result = $this->db->query($sql)->row_array();
            $nE = $result['nE'];
            $maxE = $result['maxE'];
            if ($numInscritos[$k] < $numMaximo[$k] && $nE) {
                $verReservas = 'verReservas';
            }
            $orden = "";
            $sql = "SELECT r.orden as orden,r.trimestres as trimestres
                      FROM casal_reservas r 
                      WHERE r.id_taller='$k' AND r.id_socio='$socio' AND r.trimestres='$numPeriodo'";
            $enListaEspera = $this->db->query($sql)->num_rows();
            $listaEspera = "";
            if ($enListaEspera)
                $listaEspera = 'listaEspera';
            if ($enListaEspera)
                $orden = $this->db->query($sql)->row()->orden;
            if ($enListaEspera)
                $trimestres = $this->db->query($sql)->row()->trimestres;
            /*
              $sql="SELECT count(a.id_taller) as nA, t.num_maximo as maxA
              FROM casal_asistentes a
              INNER JOIN casal_talleres t ON t.id=a.id_taller
              WHERE a.id_taller='$k' AND a.trimestres>0";
             */
            //$result2=$this->db->query($sql)->row_array();
            // $result2=$this->getNumInscritos($k,$periodo);
            $nA = $this->getNumInscritos($k, $periodo);
            $maxA = $this->getMaxAsistentes($k);

            // $reservasInfo="";
            // if($numInscritos[$k]==$numMaximo[$k]){
            $reservasInfo = ' (' . $result['nE'] . '/' . $result['maxE'] . ')' . ($orden != "" ? ' - ' . $orden : "");
            // }
            $asistentesInfo = ' (' . $nA . '/' . $maxA . ')';

            
            if ($listaEspera == 'listaEspera') {
                if ($nA < $maxA) {
                    switch ($trimestres) {
                        case 7:
                            $asisnteciaC = " checked  ";
                            $asisnteciaT1 = "  ";
                            $asisnteciaT2 = "  ";
                            $asisnteciaT3 = "  ";
                            $marcadoC = "marcadoListaEspera";
                            $marcadoT1 = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            $marcadoT3 = "noMarcado";
                            break;
                        case 4:
                            $asisnteciaC = "  ";
                            $asisnteciaT1 = " checked  ";
                            $asisnteciaT2 = "  ";
                            $asisnteciaT3 = "  ";
                            $marcadoT1 = "marcadoListaEspera";
                            $marcadoC = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            $marcadoT3 = "noMarcado";
 
                            break;
                        case 2:
                            $asisnteciaC = "  ";
                            $asisnteciaT1 = "  ";
                            $asisnteciaT2 = " checked  ";
                            $asisnteciaT3 = "  ";
                            $marcadoT2 = "marcadoListaEspera";
                            $marcadoC = "noMarcado";
                            $marcadoT1 = "noMarcado";
                            $marcadoT3 = "noMarcado";
                            break;
                        case 1:
                            $asisnteciaC = "  ";
                            $asisnteciaT1 = "  ";
                            $asisnteciaT2 = "  ";
                            $asisnteciaT3 = " checked  ";
                            $marcadoC = "noMarcado";
                            $marcadoT1 = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            $marcadoT3 = "marcadoListaEspera";
                            break;
                        
                    }
                    
                    
                } else {
                    switch ($trimestres) {
                        case 7:
                            $asisnteciaC = " checked disabled ";
                            $asisnteciaT1 = " disabled ";
                            $asisnteciaT2 = " disabled ";
                            $asisnteciaT3 = " disabled ";
                            $marcadoC = "marcadoListaEspera";
                            $marcadoT1 = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            $marcadoT3 = "noMarcado";
                            break;
                        case 4:
                            $asisnteciaC = " disabled ";
                            $asisnteciaT1 = " checked disabled ";
                            $asisnteciaT2 = " disabled ";
                            $asisnteciaT3 = " disabled ";
                            $marcadoT1 = "marcadoListaEspera";
                            $marcadoC = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            $marcadoT3 = "noMarcado";
                            break;
                        case 2:
                            $asisnteciaC = " disabled ";
                            $asisnteciaT1 = " disabled ";
                            $asisnteciaT2 = " checked disabled ";
                            $asisnteciaT3 = " disabled ";
                            $marcadoT2 = "marcadoListaEspera";
                            $marcadoC = "noMarcado";
                            $marcadoT1 = "noMarcado";
                            $marcadoT3 = "noMarcado";
                            break;
                        case 1:
                            $asisnteciaC = " disabled ";
                            $asisnteciaT1 = " disabled ";
                            $asisnteciaT2 = " disabled ";
                            $asisnteciaT3 = " checked disabled ";
                            $marcadoT3 = "marcadoListaEspera";
                            $marcadoC = "noMarcado";
                            $marcadoT1 = "noMarcado";
                            $marcadoT2 = "noMarcado";
                            break;
                        
                    }
                    
                    
                }
            }

            $tabla .= '<td class="' . $listaEspera . '">';
            // $tabla.='<input type="hidden" name="taller'.$k.'" value="'.$k.'"  disabled>';

            $tabla .= '<label class="nombreTaller ' . $tipoTaller[$k] . '" id="parametros' . $k . '" taller="' . $k . '" nA="' . $nA . '" nE="' . $nE . '" maxA="' . $maxA . '" maxE="' . $maxE . '" enListaEspera="' . $enListaEspera . '">' . $v .
                    '<span class="' . $ocupacion . '" id="idInscritos' . $k . '">' . $asistentesInfo . '</span>' .
                    '<span class="' . $verReservas . '" id="reservasInfo' . $k . '" style="background-color:yellow;font-weight:normal;" > ' . $reservasInfo . '</span>' .
                    '</label>';
            $tabla .= '</td><td style="padding-top:5px;">';
            //log_message('INFO','$tipoTaller[$k]  '.$tipoTaller[$k]);
            //if(true){
            if($tipoTaller[$k]=='Voluntari'){
                if($periodo=='T1' || $periodo=='C') $disabled='';
                else $disabled='disabled';
            }
            else $disabled='disabled';
            //$disabled = ($periodo && $tipoTaller[$k]=='Voluntari') == 'C' ? '' : 'disabled';
            //if($disabled=='disabled') $noDisabled='noDisabled';else $noDisabled='';
            $tabla .= '<span class="' . $marcadoC . '"><label class="'."".'" for="id' . $k . '">C </label>&nbsp;<input disabled '. ' type="checkbox" name="C[]" class="C ' . $ocupacion . '" id="id' . $k . '" ' . $asisnteciaC . ' value="' . $k . '"></span>';
            $tabla .= '&nbsp;&nbsp;'; //&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            /*
            if($tipoTaller[$k]=='Voluntari'){
                if($periodo=='T1') $disabled='';
                else $disabled='disabled';
            }
            else $disabled='disabled';
            */
            
            $disabled = ($periodo ) == 'T1' ? '' : 'disabled';
            $tabla .= '<span class="' . $marcadoT1 . '"><label for="id' . $k . '">T1 </label>&nbsp;<input ' . $disabled . '  type="checkbox" name="T1[]" class="T1 ' . $ocupacion . '" id="idT1' . $k . '" ' . $asisnteciaT1 . ' value="' . $k . '"></span>';
            $tabla .= ''; //&nbsp;';//&nbsp;&nbsp;';
            /*
            if($tipoTaller[$k]=='Voluntari'){
                if($periodo=='T3') $disabled='';
                else $disabled='disabled';
            }
            else if($periodo=='T2') $disabled='';
            */
            $disabled = ($periodo ) == 'T2' ? '' : 'disabled';
            
            //if($disabled=='disabled' && $tipoTaller[$k]=='Voluntari') $disabled="";
            $tabla .= '<span class="' . $marcadoT2 . '"><label for="id' . $k . '">T2 </label>&nbsp;<input ' . $disabled . '  type="checkbox" name="T2[]" class="T2 ' . $ocupacion . '" id="idT2' . $k . '" ' . $asisnteciaT2 . ' value="' . $k . '"></span>';
            $tabla .= ''; //&nbsp;';//&nbsp;&nbsp;';

            $disabled = ($periodo ) == 'T3' ? '' : 'disabled';
            if($tipoTaller[$k]=='Voluntari' && $periodo=='T2') $disabled='';
            
            //if($disabled=='disabled' && $tipoTaller[$k]=='Voluntari') $disabled="";
            $tabla .= '<span class="' . $marcadoT3 . '"><label for="id' . $k . '">T3 </label>&nbsp;<input ' . $disabled . '  type="checkbox" name="T3[]" class="T3 ' . $ocupacion . '" id="idT3' . $k . '" ' . $asisnteciaT3 . ' value="' . $k . '"></span>';

            $tabla .= '</td>';
            //alert(index+' '+value['id']+' '+value['nombre'])
            if ($indice % 2 == 1)
                $tabla .= '</tr>';

            $indice++;
        }
        $tabla .= $pieTabla;
        return $tabla;
    }

    function getTablaTalleresInscritos($curso, $socio) {
        $resultAsistentes=array();
        $talleresListaEspera=array();
        $resultAsistentes = $this->getAsistentesArray($curso, $socio);
        $talleresListaEspera = $this->talleresListaEspera($curso, $socio);
        $asistentes = array();
        $asistencias = array();
        
        $cabeceraTabla = '<table class="table table-bordered"><tbody>';
        $pieTabla = '</tbody></table>';
        $tabla = $cabeceraTabla;
        //log_message('INFO',count($resultAsistentes));
        //log_message('INFO',count(count($talleresListaEspera)));
        //log_message('INFO',(!count($resultAsistentes) && !count($talleresListaEspera)));
        if(!count($resultAsistentes) && !count($talleresListaEspera)) return false; 
        
        if(count($resultAsistentes)) {
        foreach ($resultAsistentes as $k => $v) {
            // if($this->session->categoria==3  && $v['tipo_taller']=="Professional") continue;
            $asistencias[$v['taller']] = $v['trimestres'];
            // mensaje('resultAsistentes '.$v['taller'].' '.$v['trimestres']);
        }
        // mensaje('$v trimestres '.$v['trimestres']);
        $result = $this->getTalleresArray($curso,$v['trimestres']);
        $result = $this->getTalleresCursoArray($curso);

        $nombres = array();
        foreach ($result as $k => $v) {
            if($this->session->categoria==3  && $v['tipo_taller']=="Professional") continue;
            $nombres[$v['id']] = $v['nombre'];
            // mensaje('$result '.$v['id'].' '.$v['nombre']);
            //log_message('INFO',$nombres[$v['id']]);
        }
        asort($nombres);
        }
        
        
       
        $enEspera = array();
        if(count($talleresListaEspera)){
        foreach ($talleresListaEspera as $k => $v) {
            $nombres[$v['taller']] = $v['nombre'];
            $enEspera[$v['taller']] = true;
        }
        }
        
        $indice = 0;

        foreach ($nombres as $k => $v) {
            $asisnteciaC = "  ";
            $asisnteciaT1 = "   ";
            $asisnteciaT2 = " ";
            $asisnteciaT3 = "  ";
            $marcadoC = "noMarcado";
            $marcadoT1 = "noMarcado";
            $marcadoT2 = "noMarcado";
            $marcadoT3 = "noMarcado";
            if (array_key_exists($k, $asistencias)) {
                $trimestres = $asistencias[$k];
                //log_message('INFO',$k.' '.$asistencias[$k].' '.$trimestres);
                switch ($trimestres) {
                    case 7:
                        $asisnteciaC = " disabled  ";
                        $asisnteciaT1 = "  ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "marcado";

                        break;
                    case 1:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " disabled ";
                        $asisnteciaT2 = " disabled ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 2:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " disabled ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = " disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    case 3:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " disabled ";
                        $asisnteciaT2 = "  ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 4:
                        $asisnteciaC = " disabled  ";
                        $asisnteciaT1 = "  ";
                        $asisnteciaT2 = " disabled ";
                        $asisnteciaT3 = " disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    case 5:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = "   ";
                        $asisnteciaT2 = " disabled ";
                        $asisnteciaT3 = "  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "marcado";
                        break;
                    case 6:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = "   ";
                        $asisnteciaT2 = " ";
                        $asisnteciaT3 = " disabled ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "marcado";
                        $marcadoT2 = "marcado";
                        $marcadoT3 = "noMarcado";
                        break;
                    default:
                        $asisnteciaC = " disabled ";
                        $asisnteciaT1 = " disabled  ";
                        $asisnteciaT2 = " disabled ";
                        $asisnteciaT3 = " disabled  ";
                        $marcadoC = "noMarcado";
                        $marcadoT1 = "noMarcado";
                        $marcadoT2 = "noMarcado";
                        $marcadoT3 = "noMarcado";
                }
            }
            if ($marcadoC == 'marcado' || $marcadoT1 == 'marcado' || $marcadoT2 == 'marcado' || $marcadoT3 == 'marcado' || isset($enEspera[$k])) {
                if ($indice % 2 == 0)
                    $tabla .= '<tr>';
                $colorEspera = "";
                $name = "C[]";
                if (isset($enEspera[$k])) {
                    $colorEspera = 'colorEspera';
                    $marcadoC = "marcado";
                    $marcadoT1 = "marcado";
                    $marcadoT2 = "marcado";
                    $marcadoT3 = "marcado";
                    $name = "Espera[]";
                    // $asisnteciaT1 = " disabled  ";
                    // $asisnteciaT2 = " disabled ";
                    // $asisnteciaT3 = " disabled  ";
                }
                $tabla .= '<td class="' . $colorEspera . '">';
                // $tabla.='<input type="hidden" name="taller'.$k.'" value="'.$k.'"  disabled>';
                $tabla .= '<label >' . $v . '</label>';
                $tabla .= '</td><td>';
                if($name=="Espera[]"){
                    $tabla .= '<span class="' . $marcadoC . '"><label for="id' . $k . '">C </label>&nbsp;<input type="checkbox" name="' . $name . '" class="C" id="id' . $k . '" ' . $asisnteciaC . ' value="' . $k . '"></span>';
                    $tabla .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if($name!="Espera[]")
                $tabla .= '<span class="' . $marcadoT1 . '"><label for="id' . $k . '">T1 </label>&nbsp;<input type="checkbox" name="T1[]" class="T1" id="id' . $k . '" ' . $asisnteciaT1 . ' value="' . $k . '"></span>';
                $tabla .= '&nbsp;&nbsp;&nbsp;';
                if($name!="Espera[]")
                $tabla .= '<span class="' . $marcadoT2 . '"><label for="id' . $k . '">T2 </label>&nbsp;<input type="checkbox" name="T2[]" class="T2" id="id' . $k . '" ' . $asisnteciaT2 . ' value="' . $k . '"></span>';
                $tabla .= '&nbsp;&nbsp;&nbsp;';
                if($name!="Espera[]")
                    $tabla .= '<span class="' . $marcadoT3 . '"><label for="id' . $k . '">T3 </label>&nbsp;<input type="checkbox" name="T3[]" class="T3" id="id' . $k . '" ' . $asisnteciaT3 . ' value="' . $k . '"></span>';
                $tabla .= '</td>';
                //alert(index+' '+value['id']+' '+value['nombre'])
                if ($indice % 2 == 1)
                    $tabla .= '</tr>';
                $indice++;
            }
        }
        
        
        $tabla .= $pieTabla;
        
        return $tabla;
    }

    function getPrecioPagadoTaller($socio, $taller) {
        $sql = "SELECT pagado FROM casal_asistentes WHERE id_socio='$socio' AND id_taller='$taller'";
        return $this->db->query($sql)->row()->pagado;
    }

    function registrarBajas() {
        $C = array();
        $T1 = array();
        $T2 = array();
        $T3 = array();
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $totalAPagar = 0;
        //print_r($_POST);
        //echo '<br>';

        extract($_POST);
        //echo '$tarjetaRosa '.$tarjetaRosa;

        $hoy = date('Y-m-d');
        //se crean los registros nuevos según bajas, si es que NO existen
        foreach ($C as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                //$this->db->query($sql);
            }
        }
        foreach ($T1 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }
        foreach ($T2 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }
        foreach ($T3 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }






        //se lee información de las asistencias del socio al curso 
        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller,
                ag.precio_curso as precio_curso_agrupacion,
                ag.precio_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";

      if(isset($tarjetaRosa))
        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_curso_taller,
                t.precio_rosa_trimestre as precio_trimestre_taller,
                ag.precio_rosa_curso as precio_curso_agrupacion,
                ag.precio_rosa_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";

        $resultActual = $this->db->query($sql)->result_array();


        $resultNuevo = $resultActual;

        //actualizamos la información con las bajas 
        foreach ($C as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 7;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T1 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 4;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T2 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 2;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T3 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 1;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }

        //echo $this->verFoto('actual',$resultActual); 
        //echo $this->verFoto('nuevo después de inscripciones',$resultNuevo); 


        $resultNuevo = $this->costesTalleresBajas($resultActual, $resultNuevo);

        //echo $this->verFoto('nuevo después de costes',$resultNuevo); 

        $nombresTallers = array();
        $importes = array();
        $trimestres = array();
        $id_talleres = array();
        $tipos_talleres=array();
        foreach ($resultNuevo as $k => $v) {

            if ($v['trimestres'] != $resultActual[$k]['trimestres'] ||
                    ($v['pagado'] != 0 && $v['pagado'] != $resultActual[$k]['pagado'])) {
                $id_talleres[] = $v['id_taller'];
                $tipos_talleres=$v['tipo_taller'];
                $importes[] = $v['pagado'];
                $nombresTallers[] = $v['nombreTaller'];
                $periodos = $v['trimestres'] ^ $resultActual[$k]['trimestres'];
                $t = array();
                if (($periodos & 7) == 7) {
                    $t[] = 'C';
                } else {
                    if (($periodos & 4) == 4)
                        $t[] = 'T1';
                    if (($periodos & 2) == 2)
                        $t[] = 'T2';
                    if (($periodos & 1) == 1)
                        $t[] = 'T3';
                }
                $trimestres[] = implode(", ", $t);
            }
        }

        //obtenemos talleres en espera
        if (isset($Espera))
            foreach ($Espera as $k => $v) {
                $sql = "SELECT t.nombre_corto as nombreTaller, t.id as id_taller
                    FROM casal_reservas r
                    LEFT JOIN casal_talleres t ON t.id=r.id_taller
                    WHERE r.id_socio='$socio' AND r.id_taller='$v'";
                $row = $this->db->query($sql)->row();
                $nombresTallers[] = $row->nombreTaller;
                $id_talleres[] = $row->id_taller;
                $importes[] = 0;
                $trimestres[] = -1;  //-1 indica lista espera
            }


        $_SESSION['pagado'] = false;

        $this->load->model('socios_model');
        return array(
            'C' => $C,
            'T1' => $T1,
            'T2' => $T2,
            'T3' => $T3,
            'curso' => $curso,
            'socio' => $socio,
            'cursoNombre' => $this->getNombreCurso($curso),
            'socioNombre' => $this->socios_model->getNombreSocio($socio),
            //  'tarjetaRosa'=>$tarjetaRosa,
            'id_talleres' => $id_talleres,
            'tipos_talleres'=>$tipos_talleres,
            'CNombres' => $nombresTallers,
            'importes' => $importes,
            'trimestres' => $trimestres,
            'totalAPagar' => array_sum($importes),
            'resultNuevo' => JSON_encode($resultNuevo),
        );
    }

    function registrarBajasNuevo() {
        $C = array();
        $T1 = array();
        $T2 = array();
        $T3 = array();
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $totalAPagar = 0;
    

        extract($_POST);
        //echo '$tarjetaRosa '.$tarjetaRosa;
        
        $this->db->query('DELETE FROM `casal_asistentes_tmp` WHERE 1');

        $hoy = date('Y-m-d');
        //se crean los registros nuevos según bajas, si es que NO existen
        foreach ($C as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes_tmp a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes_tmp SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }
        foreach ($T1 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes_tmp a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
            //log_message('INFO','sql $T1 '.$sql);
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes_tmp SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }
        foreach ($T2 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes_tmp a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
           // log_message('INFO','sql $T2 '.$sql);
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes_tmp SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }
        foreach ($T3 as $k => $v) {
            $sql = "SELECT * 
                    FROM casal_asistentes_tmp a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
           // log_message('INFO','sql $T3 '.$sql);
            if (!$this->db->query($sql)->num_rows()) {
                $sql = "INSERT INTO casal_asistentes_tmp SET fecha_devolucion='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                $this->db->query($sql);
            }
        }

        //se eliminan de casal_asistentes_tmp los eliminados de lista de espera





        //se lee información de las asistencias del socio al curso 
        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller,
                ag.precio_curso as precio_curso_agrupacion,
                ag.precio_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes_tmp a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";

      if(isset($tarjetaRosa))
        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_curso_taller,
                t.precio_rosa_trimestre as precio_trimestre_taller,
                ag.precio_rosa_curso as precio_curso_agrupacion,
                ag.precio_rosa_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes_tmp a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";

                

        $resultNuevo = $this->db->query($sql)->result_array();


        //$resultNuevo = $resultActual;

        //actualizamos la información con las bajas 
        foreach ($C as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 7;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T1 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 4;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T2 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 2;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T3 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] ^= 1;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }

        //echo $this->verFoto('actual',$resultActual); 
        // echo $this->verFoto('nuevo después de inscripciones',$resultNuevo); 


        $resultNuevo = $this->costesTalleresBajasNuevo($resultNuevo);

        //echo $this->verFoto('nuevo después de costes',$resultNuevo); 

        $nombresTallers = array();
        $importes = array();
        $trimestres = array();
        $id_talleres = array();
        $tipos_talleres=array();
        foreach ($resultNuevo as $k => $v) {

          //  if (($v['pagado'] != 0 && $v['pagado'] != $resultActual[$k]['pagado'])) {
                $id_talleres[] = $v['id_taller'];
                $tipos_talleres=$v['tipo_taller'];
                $importes[] = $v['pagado'];
                $nombresTallers[] = $v['nombreTaller'];
                $periodos = $v['trimestres']; // ^ $resultActual[$k]['trimestres'];
                $t = array();
                if (($periodos & 7) == 7) {
                    $t[] = 'C';
                } else {
                    if (($periodos & 4) == 4)
                        $t[] = 'T1';
                    if (($periodos & 2) == 2)
                        $t[] = 'T2';
                    if (($periodos & 1) == 1)
                        $t[] = 'T3';
                }
                $trimestres[] = implode(", ", $t);
       //    }
        }

        //obtenemos talleres en espera
        if (isset($Espera))
            foreach ($Espera as $k => $v) {
                $sql = "SELECT t.nombre_corto as nombreTaller, t.id as id_taller
                    FROM casal_reservas r
                    LEFT JOIN casal_talleres t ON t.id=r.id_taller
                    WHERE r.id_socio='$socio' AND r.id_taller='$v'";
                $row = $this->db->query($sql)->row();
                $nombresTallers[] = $row->nombreTaller;
                $id_talleres[] = $row->id_taller;
                $importes[] = 0;
                $trimestres[] = -1;  //-1 indica lista espera
            }


        $_SESSION['pagado'] = false;

        $this->load->model('socios_model');
        return array(
            'C' => $C,
            'T1' => $T1,
            'T2' => $T2,
            'T3' => $T3,
            'curso' => $curso,
            'socio' => $socio,
            'cursoNombre' => $this->getNombreCurso($curso),
            'socioNombre' => $this->socios_model->getNombreSocio($socio),
            //  'tarjetaRosa'=>$tarjetaRosa,
            'id_talleres' => $id_talleres,
            'tipos_talleres'=>$tipos_talleres,
            'CNombres' => $nombresTallers,
            'importes' => $importes,
            'trimestres' => $trimestres,
            'totalAPagar' => array_sum($importes),
            'resultNuevo' => JSON_encode($resultNuevo),
        );
    }

    
    function registrarInscripcionesNuevo() {
        $listaEsperaC = array();
        $listaEsperaT1 = array();
        $listaEsperaT2 = array();
        $listaEsperaT3 = array();
        $C = array();
        $T1 = array();
        $T2 = array();
        $T3 = array();
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $totalAPagar = 0;
        $tarjetaRosa = "";

        //var_dump($_POST);
        //echo '<br>';


        extract($_POST);

        //echo '$tarjetaRosa '.$tarjetaRosa;

        $hoy = date('Y-m-d');

        //se crean los registros nuevos según inscripciones
        //registra listas de espera
        $datosListaEspera = array();
        $listaEsperaTaller = array();
        $listaEsperaOrden = array();
        $listaEsperaPeriodo = array();


        foreach ($listaEsperaC as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'C';
        }
        foreach ($listaEsperaT1 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T1';
        }
        foreach ($listaEsperaT2 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T2';
        }
        foreach ($listaEsperaT3 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T3';
        }

        $this->db->query('DELETE FROM `casal_asistentes_tmp` WHERE 1');
        
        //registrar asistente en taller en el caso de que no esté
        if (true) {
            foreach ($C as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes_tmp a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes_tmp SET fecha_pago='$hoy', id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T1 as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes_tmp a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes_tmp SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T2 as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes_tmp a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes_tmp SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T3 as $k => $v) {
                $sql = "SELECT * 
                    FROM casal_asistentes_tmp a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes_tmp SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
        }

        // se elimnan de casal_asistentes_tmp los que están en lista de espera
        foreach ($listaEsperaT1 as $k => $v) {
            // mensaje('$listaEsperaT1 '.$v);
            $sql="DELETE FROM casal_asistentes_tmp WHERE id_socio='$socio' AND id_taller='$v'";
            $this->db->query($sql);
        }
        foreach ($listaEsperaT2 as $k => $v) {
            $this->db->query("DELETE FROM casal_asistentes_tmp WHERE id_socio='$socio' AND id_taller='$v'");
        }
        foreach ($listaEsperaT3 as $k => $v) {
            $this->db->query("DELETE FROM casal_asistentes_tmp WHERE id_socio='$socio' AND id_taller='$v'");
        }
       


        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller,
                ag.precio_curso as precio_curso_agrupacion,
                ag.precio_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes_tmp a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";


        if ($tarjetaRosa)
            $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_curso_taller,
                t.precio_rosa_trimestre as precio_trimestre_taller,
                ag.precio_rosa_curso as precio_curso_agrupacion,
                ag.precio_rosa_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes_tmp a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";

       
        $resultNuevo = $this->db->query($sql)->result_array();
        
        

        
         //$resultNuevo = $resultActual;

        foreach ($C as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 7;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T1 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 4;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T2 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 2;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T3 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 1;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }

        //echo $this->verFoto('actual',$resultActual); 
        //echo $this->verFoto('nuevo después de inscripciones',$resultNuevo); 


        //regulariza precios 
        //se desmarca para siempre considerar los precios VIGENTES sin tener en cuanta inscripciones anteriores.
        $resultNuevo = $this->costesTalleresInscripcionesNuevo($resultNuevo);

        //echo $this->verFoto('nuevo después de costes',$resultNuevo); 

        $nombresTallers = array();
        $importes = array();
        $trimestres = array();
        $id_talleres = array();
        $tipos_talleres=array();
        foreach ($resultNuevo as $k => $v) {

           // if ($v['pagado'] != 0 ) {
                $id_talleres[] = $v['id_taller'];
                $importes[] = $v['pagado'];
                $tipos_talleres[]=$v['tipo_taller'];
                $nombresTallers[] = $v['nombreTaller'];
                $periodos = $v['trimestres']; // ^ $resultActual[$k]['trimestres'];
                $t = array();
                if (($periodos & 7) == 7) {
                    $t[] = 'C';
                } else {
                    if (($periodos & 4) == 4)
                        $t[] = 'T1';
                    if (($periodos & 2) == 2)
                        $t[] = 'T2';
                    if (($periodos & 1) == 1)
                        $t[] = 'T3';
                }
                $trimestres[] = implode(", ", $t);
         //   }
        }

        $_SESSION['pagado'] = false;

        $this->load->model('socios_model');

        
        
        return array('inscripcion'=>array(
            'listaEspera' => $listaEsperaTaller,
            'C' => $C,
            'T1' => $T1,
            'T2' => $T2,
            'T3' => $T3,
            //'curso' => $curso,
            //'socio' => $socio,
            //'tarjetaRosa' => $tarjetaRosa,
            //'cursoNombre' => $this->getNombreCurso($curso),
            //'socioNombre' => $this->socios_model->getNombreSocio($socio),
            'id_talleres' => $id_talleres,
            'tipos_talleres' => $tipos_talleres,
            'CNombres' => $nombresTallers,
            'importes' => $importes,
            //'trimestres' => $trimestres,
            'totalAPagar' => array_sum($importes),
            'datosListaEspera' => $datosListaEspera,
            'listaEsperaTaller' => $listaEsperaTaller,
            'listaEsperaOrden' => $listaEsperaOrden,
            'listaEsperaPeriodo' => $listaEsperaPeriodo,
            //'periodo' => $periodo,
            'letraPeriodo' => $letraPeriodo,
            'resultNuevo' => JSON_encode($resultNuevo),
            ),
            'datosComunes'=>array(
                'curso' => $curso,
                'socio' => $socio,
                'cursoNombre' => $this->getNombreCurso($curso),
                'socioNombre' => $this->socios_model->getNombreSocio($socio),
                'periodo' => $periodo,
                'letraPeriodo' => $letraPeriodo,
                 'trimestres' => $trimestres,
                'tarjetaRosa' => $tarjetaRosa,
            )
        );
    }
    
    function registrarInscripciones() {
        $listaEsperaC = array();
        $listaEsperaT1 = array();
        $listaEsperaT2 = array();
        $listaEsperaT3 = array();
        $C = array();
        $T1 = array();
        $T2 = array();
        $T3 = array();
        $CNombres = array();
        $T1Nombres = array();
        $T2Nombres = array();
        $T3Nombres = array();
        $totalAPagar = 0;
        $tarjetaRosa = "";

        //var_dump($_POST);
        //echo '<br>';


        extract($_POST);

        //echo '$tarjetaRosa '.$tarjetaRosa;

        $hoy = date('Y-m-d');

        //se crean los registros nuevos según inscripciones
        //registra listas de espera
        $datosListaEspera = array();
        $listaEsperaTaller = array();
        $listaEsperaOrden = array();
        $listaEsperaPeriodo = array();


        foreach ($listaEsperaC as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'C';
        }
        foreach ($listaEsperaT1 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T1';
        }
        foreach ($listaEsperaT2 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T2';
        }
        foreach ($listaEsperaT3 as $k => $v) {
            $taller = $v;
            $orden = $this->db->query("SELECT count(*) as num FROM casal_reservas WHERE id_taller='$taller'")->row()->num + 1;
            $nombreTaller = $this->getTaller($taller)['nombreTaller'];
            $datosListaEspera[] = array('taller' => $taller, 'nombreTaller' => $nombreTaller, 'orden' => $orden);
            $listaEsperaTaller[] = $taller;
            $listaEsperaOrden[] = $orden;
            $listaEsperaPeriodo[] = 'T3';
        }

        //registrar asistente en taller en el caso de que no esté
        if (true) {
            foreach ($C as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes SET fecha_pago='$hoy', id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T1 as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T2 as $k => $v) {
                $sql = "SELECT * 
                        FROM casal_asistentes a
                        LEFT JOIN casal_talleres t ON t.id=a.id_taller
                        WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
            foreach ($T3 as $k => $v) {
                $sql = "SELECT * 
                    FROM casal_asistentes a
                    LEFT JOIN casal_talleres t ON t.id=a.id_taller
                    WHERE id_socio='$socio' AND id_taller='$v'";
                if (!$this->db->query($sql)->num_rows()) {
                    $sql = "INSERT INTO casal_asistentes SET fecha_pago='$hoy',id_socio='$socio', id_taller='$v', trimestres = '0' ";
                    $this->db->query($sql);
                }
            }
        }



        $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller,
                ag.precio_curso as precio_curso_agrupacion,
                ag.precio_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";


        if ($tarjetaRosa)
            $sql = "SELECT a.*,t.id as id_taller, t.nombre as nombreTaller, t.id_agrupacion as agrupacion,
                t.tipo_taller as tipo_taller,
                t.precio_rosa_curso as precio_curso_taller,
                t.precio_rosa_trimestre as precio_trimestre_taller,
                ag.precio_rosa_curso as precio_curso_agrupacion,
                ag.precio_rosa_trimestre as precio_trimestre_agrupacion
                
                FROM casal_asistentes a 
                LEFT JOIN casal_talleres t ON t.id=a.id_taller
                LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
                WHERE id_socio='$socio' AND t.id_curso='$curso'";


        $resultActual = $this->db->query($sql)->result_array();
        //echo '$resultActual<br>';
        //var_dump($resultActual);
        
        



        $resultNuevo = $resultActual;
        
        //se actualizan los trimestres anteriores con los nuevos registros
        foreach ($C as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 7;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T1 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 4;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T2 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 2;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }
        foreach ($T3 as $k => $v) {
            foreach ($resultNuevo as $k1 => $v1) {
                if ($v1['id_taller'] == $v) {
                    $v1['trimestres'] |= 1;
                    $resultNuevo[$k1] = $v1;
                }
            }
        }

        //echo $this->verFoto('actual',$resultActual); 
        //echo $this->verFoto('nuevo después de inscripciones',$resultNuevo); 


        //regulariza precios 
        //se desmarca para siempre considerar los precios VIGENTES sin tener en cuanta inscripciones anteriores.
        $resultNuevo = $this->costesTalleresInscripciones($resultActual, $resultNuevo);

        //echo $this->verFoto('nuevo después de costes',$resultNuevo); 

        $nombresTallers = array();
        $importes = array();
        $trimestres = array();
        $id_talleres = array();
        $tipos_talleres=array();
        foreach ($resultNuevo as $k => $v) {

            if ($v['trimestres'] != $resultActual[$k]['trimestres'] ||
                    // ($v['pagado']!=0 && $v['pagado']!=$resultActual[$k]['pagado']) ){
                    ($v['pagado'] != 0 )) {
                $id_talleres[] = $v['id_taller'];
                $importes[] = $v['pagado'];
                $tipos_talleres[]=$v['tipo_taller'];
                $nombresTallers[] = $v['nombreTaller'];
                $periodos = $v['trimestres'] ^ $resultActual[$k]['trimestres'];
                $t = array();
                if (($periodos & 7) == 7) {
                    $t[] = 'C';
                } else {
                    if (($periodos & 4) == 4)
                        $t[] = 'T1';
                    if (($periodos & 2) == 2)
                        $t[] = 'T2';
                    if (($periodos & 1) == 1)
                        $t[] = 'T3';
                }
                $trimestres[] = implode(", ", $t);
            }
        }

        $_SESSION['pagado'] = false;

        $this->load->model('socios_model');

        return array('inscripcion'=>array(
            'listaEspera' => $listaEsperaTaller,
            'C' => $C,
            'T1' => $T1,
            'T2' => $T2,
            'T3' => $T3,
            //'curso' => $curso,
            //'socio' => $socio,
            //'tarjetaRosa' => $tarjetaRosa,
            //'cursoNombre' => $this->getNombreCurso($curso),
            //'socioNombre' => $this->socios_model->getNombreSocio($socio),
            'id_talleres' => $id_talleres,
            'tipos_talleres' => $tipos_talleres,
            'CNombres' => $nombresTallers,
            'importes' => $importes,
            //'trimestres' => $trimestres,
            'totalAPagar' => array_sum($importes),
            'datosListaEspera' => $datosListaEspera,
            'listaEsperaTaller' => $listaEsperaTaller,
            'listaEsperaOrden' => $listaEsperaOrden,
            'listaEsperaPeriodo' => $listaEsperaPeriodo,
            //'periodo' => $periodo,
            'letraPeriodo' => $letraPeriodo,
            'resultNuevo' => JSON_encode($resultNuevo),
            ),
            'datosComunes'=>array(
                'curso' => $curso,
                'socio' => $socio,
                'cursoNombre' => $this->getNombreCurso($curso),
                'socioNombre' => $this->socios_model->getNombreSocio($socio),
                'periodo' => $periodo,
                'letraPeriodo' => $letraPeriodo,
                 'trimestres' => $trimestres,
                'tarjetaRosa' => $tarjetaRosa,
            )
        );
    }

    function getSiguienteNumRegistroPosicion($importe){
        $numRegistroPosicion=0;
        if($importe>0){
            $sql="SELECT id,num_registro,num_registro_posicion FROM casal_lineas_recibos WHERE importe>0 ORDER BY id DESC LIMIT 1";
            if($this->db->query($sql)->num_rows()==0) {
                $numRegistroPosicion=1;
                $numRegistro=getNumeroRegistroCasalIngresos();
                $numRegistro=date('Y').substr($numRegistro,4);
            }
            else{
                $numRegistroPosicion=$this->db->query($sql)->row()->num_registro_posicion;
                $numRegistroPosicion++;
                $numRegistro=$this->db->query($sql)->row()->num_registro;
                $actualYear=date("Y");
                //$actualYear="2020";
                if($actualYear!=substr($numRegistro,0,4)){
                    $numRegistro=$actualYear.substr($numRegistro,4);
                    $numRegistroPosicion=1;
                }
            }
        }
        elseif($importe<0){
            $sql="SELECT id,num_registro,num_registro_posicion FROM casal_lineas_recibos WHERE importe<0 ORDER BY id DESC LIMIT 1";
            if($this->db->query($sql)->num_rows()==0) {
                $numRegistroPosicion=1;
                $numRegistro=getNumeroRegistroCasalDevoluciones();
                $numRegistro=date('Y').substr($numRegistro,4);
            }
            else{
                $numRegistroPosicion=$this->db->query($sql)->row()->num_registro_posicion;
                $numRegistroPosicion++;
                $numRegistro=$this->db->query($sql)->row()->num_registro;
                $actualYear=date("Y");
                //$actualYear="2020";
                if($actualYear!=substr($numRegistro,0,4)){
                    $numRegistro=$actualYear.substr($numRegistro,4);
                    $numRegistroPosicion=1;
                }
            }
        }
        elseif($importe==0){
            $numRegistro="";
            $numRegistroPosicion=0;
        }
        return array('numRegistroPosicion'=>$numRegistroPosicion,'numRegistro'=>$numRegistro,);
    }
    
    function registrarLineasRecibo(
            $id_recibo,
            $id_socio,
            $id_taller,
            $periodos,
            $tipo_taller,
            $importe,
            $tarjeta,
            $metalico){
        
        //log_message('INFO', 'registrarLineasRecibo '.$periodos);
        //si el taller es voluntari y se inscribe en el T1 (4), entonces se inscribe en todo el curso
        if($tipo_taller=='Voluntari' && $periodos==4) $periodos=7;
        $numRegistro='';
        $numRegistroPosicion=0;
        
        $numRegistroPosicion=$this->getSiguienteNumRegistroPosicion($importe)['numRegistroPosicion'];
        $numRegistro=$this->getSiguienteNumRegistroPosicion($importe)['numRegistro'];

        $sql="INSERT INTO casal_lineas_recibos SET id_recibo='$id_recibo',"
                . " id_socio='$id_socio',"
                . " id_taller='$id_taller',"
                . " periodos='$periodos',"
                . " tipo_taller='$tipo_taller',"
                . " importe='$importe',"
                . " tarjeta='$tarjeta',"
                . " metalico='$metalico',"
                . " num_registro='$numRegistro',"
                . " num_registro_posicion='$numRegistroPosicion'";
        $this->db->query($sql);
        
    }
    
    function grabarListaEsperaTalleres($socio, $listaEsperaTaller, $listaEsperaOrden, $listaEsperaPeriodo = array(),$id_recibo) {
        foreach ($listaEsperaTaller as $k => $v) {
            $sql = "SELECT * FROM casal_reservas WHERE id_socio='$socio' AND id_taller='$v'";
            if ($this->db->query($sql)->num_rows() == 0) {
                $orden = $listaEsperaOrden[$k];
                $periodo = $listaEsperaPeriodo[$k];
                switch ($periodo) {
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
                $sql = "INSERT INTO casal_reservas SET id_socio='$socio', id_taller='$v', orden='$orden', trimestres='$numPeriodo', id_recibo='$id_recibo'";
                $this->db->query($sql);
            }
        }
    }

    function bajasListaEspera($socio, $id_talleres, $trimestres,$id_recibo) {
        foreach ($trimestres as $k => $v) {
            if ($v == -1) {
                //-1 indica que es de lista espera
                $this->db->query("DELETE FROM casal_reservas WHERE id_socio='$socio' AND id_taller='$id_talleres[$k]'");
                $sql = "SELECT id, id_socio,orden FROM casal_reservas WHERE id_taller='$id_talleres[$k]' ORDER BY orden";
                $result = $this->db->query($sql)->result();
                $nuevosOrdenes = array();
                foreach ($result as $k => $v) {
                    $orden = $k + 1;
                    $id = $v->id;
                    $this->db->query("UPDATE casal_reservas SET orden='$orden' WHERE id='$id'");
                }
            }
        }
    }

    function anularRecibo($idRecibo){
        $sql="DELETE FROM `casal_recibos` WHERE id='$idRecibo'";
        $this->db->query($sql);
        $sql="SELECT * FROM casal_lineas_recibos WHERE id_recibo='$idRecibo'";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id_socio=$v->id_socio;
            $id_taller=$v->id_taller;
            $trimestres=$v->periodos;
            log_message('INFO','----------------------------------- $v->periodos ' .$v->periodos);
            $importe=$v->importe;
            $sql="DELETE FROM `casal_asistentes` "
                    . " WHERE id_socio='$id_socio'"
                    . " AND id_taller='$id_taller'"
                    . " AND trimestres='$trimestres'"
                    . " AND pagado='$importe'";
            
            $sql="UPDATE `casal_asistentes` SET trimestres= (trimestres - $trimestres), pagado=pagado - $importe "
                    . " WHERE id_socio='$id_socio'"
                    . " AND id_taller='$id_taller'";                            
            $this->db->query($sql);
        }
        $sql="DELETE FROM casal_lineas_recibos WHERE id_recibo='$idRecibo'";
        $this->db->query($sql);
        
        $sql="DELETE FROM casal_reservas WHERE id_recibo='$idRecibo'";
        $this->db->query($sql);
        
        $idSiguiente=$this->db->query("SELECT * FROM casal_asistentes ORDER BY id DESC LIMIT 1")->row()->id+1;
        $this->db->query("ALTER TABLE casal_asistentes AUTO_INCREMENT=$idSiguiente");
        
        $idSiguiente=$this->db->query("SELECT * FROM casal_recibos ORDER BY id DESC LIMIT 1")->row()->id+1;
        $this->db->query("ALTER TABLE casal_recibos AUTO_INCREMENT=$idSiguiente");
        
        $idSiguiente=$this->db->query("SELECT * FROM casal_lineas_recibos ORDER BY id DESC LIMIT 1")->row()->id+1;
        $this->db->query("ALTER TABLE casal_lineas_recibos AUTO_INCREMENT=$idSiguiente");
        
        $idSiguiente=$this->db->query("SELECT * FROM casal_reservas ORDER BY id DESC LIMIT 1")->row()->id+1;
        $this->db->query("ALTER TABLE casal_lineas_recibos AUTO_INCREMENT=$idSiguiente");
        
    }
    
    function grabarAsistentesTalleres($result) {

        if ($_SESSION['pagado'] == true) {
            header('Location: volver/pago');

            //$this->volver('pago');
            return;
        }
        $result = json_decode($result);
        foreach ($result as $k => $v) {
            $id = $v->id;
            $id_socio = $v->id_socio;
            $id_taller = $v->id_taller;
            $pagado = $v->pagado;
            $fecha_pago = $v->fecha_pago;
            $trimestres = $v->trimestres;
            $trimestres_pago = $v->trimestres_pago;
            $fecha_devolucion = $v->fecha_devolucion;

            $sql = "UPDATE casal_asistentes SET id_socio='$id_socio',
                                             id_taller='$id_taller',
                                             pagado=pagado+'$pagado',
                                             fecha_pago='$fecha_pago',
                                             trimestres='$trimestres',                                                trimestres_pago='$trimestres_pago',
                                             fecha_devolucion='fecha_devolucion'
                 WHERE id='$id'";

            if ($trimestres > 0) {
                $this->db->query($sql);
                //echo $sql;
            } else
                $this->db->query("DELETE FROM casal_asistentes WHERE id='$id'");

            $sql = "DELETE FROM casal_reservas WHERE id_socio='$id_socio' AND id_taller='$id_taller'";
            $this->db->query($sql);
        }
    }

    function grabarAsistentesTalleresNuevo($result,$id_recibo) {
        
       

        if ($_SESSION['pagado'] == true) {
            header('Location: volver/pago');

            //$this->volver('pago');
            return;
        }
        $result = json_decode($result);
        foreach ($result as $k => $v) {
            //$id = $v->id;
            $id_socio = $v->id_socio;
            $id_taller = $v->id_taller;
            $pagado = $v->pagado;
            $fecha_pago = $v->fecha_pago;
            $trimestres = $v->trimestres;
            
            
            $tipo_taller=$this->db->query("SELECT tipo_taller FROM casal_talleres WHERE id='$id_taller'")->row()->tipo_taller;
            //si es voluntario se inscribe al primer trimestre, se inscribe TODO el curso
            if($tipo_taller=='Voluntari' && $trimestres==4) $trimestres=7;
            //log_message('INFO', '%%%%%%%%%%%%%%%%%%%%%%%% '.$trimestres);
            
            $trimestres_pago = $v->trimestres_pago;
            
            //log_message('INFO', '%%%%%%%%%%%%%%%%%%%%%%%% $trimestres_pago '.$trimestres_pago);
            
            $fecha_devolucion = $v->fecha_devolucion;
            if($this->db->query("SELECT id FROM casal_asistentes WHERE id_taller='$id_taller' AND id_socio='$id_socio'")->num_rows()!=0){
                $id=$this->db->query("SELECT id FROM casal_asistentes WHERE id_taller='$id_taller' AND id_socio='$id_socio'")->row()->id;
                $sql = "UPDATE casal_asistentes SET id_socio='$id_socio',
                                             id_taller='$id_taller',
                                             pagado=pagado+'$pagado',
                                             fecha_pago='$fecha_pago',
                                             trimestres=trimestres | '$trimestres',                                               
                                             trimestres_pago='$trimestres_pago',
                                             fecha_devolucion='fecha_devolucion',
                                             id_recibo='$id_recibo'
                 WHERE id='$id'";
            }else{
                $sql = "INSERT INTO casal_asistentes SET id_socio='$id_socio',
                                             id_taller='$id_taller',
                                             pagado='$pagado',
                                             fecha_pago='$fecha_pago',
                                             trimestres='$trimestres',                                               
                                             trimestres_pago='$trimestres_pago',
                                             fecha_devolucion='fecha_devolucion',
                                             id_recibo='$id_recibo'
                 ";
            }
           
            //echo $sql;
            if ($trimestres > 0) {
                $this->db->query($sql);
                $this->registrarPagos($trimestres,$id_taller,$id_socio,$pagado);
                //echo $sql;
            } else
                $this->db->query("DELETE FROM casal_asistentes WHERE id='$id'");

            $sql = "DELETE FROM casal_reservas WHERE id_socio='$id_socio' AND id_taller='$id_taller'";
            $this->db->query($sql);
        }
    }
    
    function getPagosRegistrados($trimestres,$id_taller,$id_socio){
        $pagado=0;
        switch ($trimestres){
            case 1:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->row()->importe;
                break;
            case 2:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->row()->importe;
                break;

            case 3:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->row()->importe;
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->row()->importe;
                break;
            case 4:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->row()->importe;
                break;
            case 5:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->row()->importe;
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->row()->importe;
                 break;
            case 6:
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->row()->importe;
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->row()->importe;
                break;
            case 7: 
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1")->row()->importe;
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2")->row()->importe;
                if($this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->num_rows()==1)
                $pagado+=$this->db->query("SELECT importe FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4")->row()->importe;
                }
          return $pagado;      
        
    }
    
    function registrarPagos($trimestres,$id_taller,$id_socio,$pagado){
        if($pagado<0) $pagado=0;
        switch ($trimestres){
            case 1:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=1, importe='$pagado'");
                break;
            case 2:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=2, importe='$pagado'");
                break;

            case 3:
                $pagado=$pagado/2;
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=1, importe='$pagado'");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=2, importe='$pagado'");
                break;
            case 4:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=4, importe='$pagado'");
                break;
            case 5:
                $pagado=$pagado/2;
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=1, importe='$pagado'");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=4, importe='$pagado'");
                break;
            case 6:
                $pagado=$pagado/2;
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=2, importe='$pagado'");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=4, importe='$pagado'");
                break;
            case 7: 
                $pagado=$pagado/3;
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=1, importe='$pagado'");  
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=2, importe='$pagado'");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                $this->db->query("INSERT INTO casal_pagos SET id_socio='$id_socio', id_taller='$id_taller', trimestre=4, importe='$pagado'");
            }
        
    }
    function registrarPagosBorrar($trimestres,$id_taller,$id_socio){
        switch ($trimestres){
            case 1:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                break;
            case 2:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                break;

            case 3:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                break;
            case 4:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                break;
            case 5:
                // $pagado=$pagado/2;
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                break;
            case 6:
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
                break;
            case 7: 
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=1");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=2");
                $this->db->query("DELETE FROM casal_pagos WHERE id_socio='$id_socio' AND id_taller='$id_taller' AND trimestre=4");
            }
        
    }
    
    
    function grabarAsistentesTalleresBajasNuevo($result,$id_recibo) {

        if ($_SESSION['pagado'] == true) {
            header('Location: volver/pago');

            //$this->volver('pago');
            return;
        }
        $result = json_decode($result);
        
        //var_dump($result);
        //return;
        foreach ($result as $k => $v) {
            //$id = $v->id;
            $id_socio = $v->id_socio;
            $id_taller = $v->id_taller;
            $pagado = $v->pagado;
            $fecha_pago = $v->fecha_pago;
            $trimestres = $v->trimestres;
            $trimestres_pago = $v->trimestres_pago;
            $fecha_devolucion = $v->fecha_devolucion;
            if($this->db->query("SELECT id FROM casal_asistentes WHERE id_taller='$id_taller' AND id_socio='$id_socio'")->num_rows()!=0){
                 $id=$this->db->query("SELECT id FROM casal_asistentes WHERE id_taller='$id_taller' AND id_socio='$id_socio'")->row()->id;
                $sql = "UPDATE casal_asistentes SET id_socio='$id_socio',
                                             id_taller='$id_taller',
                                             pagado=pagado+'$pagado',
                                             fecha_pago='$fecha_pago',
                                             trimestres=trimestres ^ '$trimestres',                                               
                                             trimestres_pago='$trimestres_pago',
                                             fecha_devolucion='fecha_devolucion',
                                             id_recibo='$id_recibo'
                 WHERE id='$id'";
            }else{
                $sql = "INSERT INTO casal_asistentes SET id_socio='$id_socio',
                                             id_taller='$id_taller',
                                             pagado='$pagado',
                                             fecha_pago='$fecha_pago',
                                             trimestres='$trimestres',                                               
                                             trimestres_pago='$trimestres_pago',
                                             fecha_devolucion='fecha_devolucion',
                                             id_recibo='$id_recibo'
                 ";
            }
           
            //echo $sql;
            if ($trimestres > 0) {
                $this->db->query($sql);
                $this->registrarPagosBorrar($trimestres,$id_taller,$id_socio);
                //echo $sql;
            } else
                $this->db->query("DELETE FROM casal_asistentes WHERE id='$id'");

            $sql = "DELETE FROM casal_reservas WHERE id_socio='$id_socio' AND id_taller='$id_taller'";
            $this->db->query($sql);
        }
    }
    function verFoto($titulo, $result) {
        $salida = "$titulo<br>";
        foreach ($result as $k => $v) {
            $salida .= "taller " . $v['id_taller'] . " pagado " . $v['pagado'] . " trimestres " . $v['trimestres'] . "<br>";
        }
        $salida .= '<br><br>';
        return $salida;
    }

    function console_log($data) {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    function costesTalleresInscripciones($resultActual, $resultNuevo) {


        foreach ($resultNuevo as $k => $v) {

            $v['pagado'] = 0;
            if ($v['agrupacion'] > 0) {
                //es un taller con agrupaciones
                $agrupacion = $v['agrupacion'];

                $i = 7;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_curso_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_curso_agrupacion'];


                $i = 4;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];

                $i = 2;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];

                $i = 1;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];
                /*
                  echo 'tarifas -----------';
                  echo '<br>'.$tarifa[1][7];
                  echo '<br>'.$tarifa[1][4];
                  echo '<br>'.$tarifa[1][2];
                  echo '<br>'.$tarifa[1][1];
                  echo '<br>tarifas -----------<br>';
                 * 
                 */
                /*
                  $i=4;
                  while ($i>0){
                  $talleresAgrupados=0;
                  foreach($resultNuevo as $k1=>$v1){
                  if(($v1['agrupacion']==$agrupacion) && ($i & $v1['trimestres'])==$i ) $talleresAgrupados++;
                  }
                  if($talleresAgrupados==0) $tarifa[$agrupacion][$i]=0;
                  if($talleresAgrupados==1) $tarifa[$agrupacion][$i]=$v['precio_trimestre_taller'];
                  if($talleresAgrupados>1)  {
                  $tarifa[$agrupacion][$i]=$v['precio_trimestre_agrupacion'];
                  }
                  $i=intval($i/2);
                  }
                 */
            }
            else {
                $tarifa[0][7] = $v['precio_curso_taller'];
                $tarifa[0][4] = $v['precio_trimestre_taller'];
                $tarifa[0][2] = $v['precio_trimestre_taller'];
                $tarifa[0][1] = $v['precio_trimestre_taller'];
            }
            $this->console_log($tarifa);
            // aplicamos tarifas a cada trimestre

            if ($v['agrupacion'] > 0) {
                /*
                  $v=7;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];
                 */
                /*
                  $v=4;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];

                  $v=2;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];

                  $v=1;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];
                 */


                $i = 4;
                while ($i > 0) {
                    if (($v['trimestres'] & $i) == $i)
                        $v['pagado'] += $tarifa[$agrupacion][$i];
                    $i = intval($i / 2);
                }

                $i = 7;
                if (($v['trimestres'] & $i) == $i)
                    $v['pagado'] += $tarifa[$agrupacion][$i];
            }
            else {
                //echo "<br>v['trimestres']".$v['trimestres'];
                //echo "<br>v['precio_trimestre_taller']".$v['precio_trimestre_taller'];
                //echo "<br>v['precio_curso_taller']".$v['precio_curso_taller'];
                if ($v['trimestres'] == 7)
                    $v['pagado'] = $v['precio_curso_taller'];
                else
                    $v['pagado'] = $v['precio_trimestre_taller'] * bitcount($v['trimestres']);
            }

            //se actualiza los valores a pagar, restando los valores ya pagados
            $v['pagado'] -= $resultActual[$k]['pagado'];
            $resultNuevo[$k] = $v;
        }
        return $resultNuevo;
    }

    function costesTalleresInscripcionesNuevo($resultNuevo) {


        foreach ($resultNuevo as $k => $v) {

            $v['pagado'] = 0;
            if ($v['agrupacion'] > 0) {
                //es un taller con agrupaciones
                $agrupacion = $v['agrupacion'];

                $i = 7;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_curso_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_curso_agrupacion'];


                $i = 4;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];

                $i = 2;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];

                $i = 1;
                $talleresAgrupados = 0;
                foreach ($resultNuevo as $k1 => $v1) {
                    if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                        $talleresAgrupados++;
                }
                if ($talleresAgrupados == 0)
                    $tarifa[$agrupacion][$i] = 0;
                if ($talleresAgrupados == 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                if ($talleresAgrupados > 1)
                    $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];
                /*
                  echo 'tarifas -----------';
                  echo '<br>'.$tarifa[1][7];
                  echo '<br>'.$tarifa[1][4];
                  echo '<br>'.$tarifa[1][2];
                  echo '<br>'.$tarifa[1][1];
                  echo '<br>tarifas -----------<br>';
                 * 
                 */
                /*
                  $i=4;
                  while ($i>0){
                  $talleresAgrupados=0;
                  foreach($resultNuevo as $k1=>$v1){
                  if(($v1['agrupacion']==$agrupacion) && ($i & $v1['trimestres'])==$i ) $talleresAgrupados++;
                  }
                  if($talleresAgrupados==0) $tarifa[$agrupacion][$i]=0;
                  if($talleresAgrupados==1) $tarifa[$agrupacion][$i]=$v['precio_trimestre_taller'];
                  if($talleresAgrupados>1)  {
                  $tarifa[$agrupacion][$i]=$v['precio_trimestre_agrupacion'];
                  }
                  $i=intval($i/2);
                  }
                 */
            }
            else {
                $tarifa[0][7] = $v['precio_curso_taller'];
                $tarifa[0][4] = $v['precio_trimestre_taller'];
                $tarifa[0][2] = $v['precio_trimestre_taller'];
                $tarifa[0][1] = $v['precio_trimestre_taller'];
            }
            $this->console_log($tarifa);
            // aplicamos tarifas a cada trimestre

            if ($v['agrupacion'] > 0) {
                /*
                  $v=7;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];
                 */
                /*
                  $v=4;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];

                  $v=2;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];

                  $v=1;
                  if(($v['trimestres']&$i)==$i) $v['pagado']+=$tarifa[$agrupacion][$i];
                 */


                $i = 4;
                while ($i > 0) {
                    if (($v['trimestres'] & $i) == $i)
                        $v['pagado'] += $tarifa[$agrupacion][$i];
                    $i = intval($i / 2);
                }

                $i = 7;
                if (($v['trimestres'] & $i) == $i)
                    $v['pagado'] += $tarifa[$agrupacion][$i];
            }
            else {
                //echo "<br>v['trimestres']".$v['trimestres'];
                //echo "<br>v['precio_trimestre_taller']".$v['precio_trimestre_taller'];
                //echo "<br>v['precio_curso_taller']".$v['precio_curso_taller'];
                if ($v['trimestres'] == 7)
                    $v['pagado'] = $v['precio_curso_taller'];
                else
                    $v['pagado'] = $v['precio_trimestre_taller'] * bitcount($v['trimestres']);
            }

            //se actualiza los valores a pagar, restando los valores ya pagados
            ///N $v['pagado'] -= $resultActual[$k]['pagado'];
            $resultNuevo[$k] = $v;
        }
        return $resultNuevo;
    }

    function costesTalleresBajas($resultActual, $resultNuevo) {
        foreach ($resultNuevo as $k => $v) {
            $v['pagado'] = 0;
            if ($v['agrupacion'] > 0) {
                //es un taller con agrupaciones
                $agrupacion = $v['agrupacion'];
                $i = 4;
                while ($i > 0) {
                    $talleresAgrupados = 0;
                    foreach ($resultNuevo as $k1 => $v1) {
                        if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                            $talleresAgrupados++;
                    }
                    if ($talleresAgrupados == 0)
                        $tarifa[$agrupacion][$i] = 0;
                    if ($talleresAgrupados == 1)
                        $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                    if ($talleresAgrupados > 1)
                        $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];
                    $i = intval($i / 2);
                }
            }
            else {
                $tarifa[0][4] = $v['precio_trimestre_taller'];
                $tarifa[0][2] = $v['precio_trimestre_taller'];
                $tarifa[0][1] = $v['precio_trimestre_taller'];
            }
            $this->console_log($tarifa);
            //aplicamos tarifas a cada trimestre
            if ($v['agrupacion'] > 0) {
                $i = 4;
                while ($i > 0) {
                    if (($v['trimestres'] & $i) == $i)
                        $v['pagado'] += $tarifa[$agrupacion][$i];
                    $i = intval($i / 2);
                }
            }
            else {
                if ($v['trimestres'] == 7)
                    $v['pagado'] = $v['precio_curso_taller'];
                else
                    $v['pagado'] = $v['precio_trimestre_taller'] * bitcount($v['trimestres']);
            }

            $v['pagado'] = $v['precio_trimestre_taller'] * bitcount($v['trimestres']);

            $v['pagado'] -= $resultActual[$k]['pagado'];
            $resultNuevo[$k] = $v;
        }
        foreach($resultNuevo as $k=>$v){
            mensaje('resultNuevo '.$k.' '.$v);
        }
        return $resultNuevo;
    }
    
    function costesTalleresBajasNuevo($resultNuevo) {
        foreach ($resultNuevo as $k => $v) {
            $v['pagado'] = 0;
            if ($v['agrupacion'] > 0) {
                //es un taller con agrupaciones
                $agrupacion = $v['agrupacion'];
                $i = 4;
                while ($i > 0) {
                    $talleresAgrupados = 0;
                    foreach ($resultNuevo as $k1 => $v1) {
                        if (($v1['agrupacion'] == $agrupacion) && ($i & $v1['trimestres']) == $i)
                            $talleresAgrupados++;
                    }
                    if ($talleresAgrupados == 0)
                        $tarifa[$agrupacion][$i] = 0;
                    if ($talleresAgrupados == 1)
                        $tarifa[$agrupacion][$i] = $v['precio_trimestre_taller'];
                    if ($talleresAgrupados > 1)
                        $tarifa[$agrupacion][$i] = $v['precio_trimestre_agrupacion'];
                    $i = intval($i / 2);
                }
            }
            else {
                $tarifa[0][4] = $v['precio_trimestre_taller'];
                $tarifa[0][2] = $v['precio_trimestre_taller'];
                $tarifa[0][1] = $v['precio_trimestre_taller'];
            }
            $this->console_log($tarifa);
            //aplicamos tarifas a cada trimestre
            if ($v['agrupacion'] > 0) {
                $i = 4;
                while ($i > 0) {
                    if (($v['trimestres'] & $i) == $i)
                        $v['pagado'] += $tarifa[$agrupacion][$i];
                    $i = intval($i / 2);
                }
            }
            else {
                if ($v['trimestres'] == 7)
                    $v['pagado'] = $v['precio_curso_taller'];
                else
                    $v['pagado'] = $v['precio_trimestre_taller'] * bitcount($v['trimestres']);
            }
            
            //var_dump($v);
            //$v['pagado'] = -$v['precio_trimestre_taller'] * bitcount($v['trimestres']);
            
            $v['pagado']=-$this->getPagosRegistrados($v['trimestres'], $v['id_taller'],$v['id_socio']);

           // $v['pagado'] -= $resultActual[$k]['pagado'];
            $resultNuevo[$k] = $v;
        }
        return $resultNuevo;
    }

    function getNombrePeriodo($letraPeriodo){
        if($letraPeriodo=='T1') return "Trimestre 1";
        if($letraPeriodo=='T2') return "Trimestre 2";
        if($letraPeriodo=='T3') return "Trimestre 3";
        return "";
    }
    function getNombreCursoTaller($taller) {
        $sql = "SELECT t.nombre_corto as nombre FROM casal_talleres t
                LEFT JOIN casal_cursos c ON c.id=t.id_curso
                WHERE t.id='$taller'";
        return $this->db->query($sql)->row()->nombre;
    }

    function getCurso($taller) {
        $sql = "SELECT c.id as idCurso, c.nombre as nombre FROM casal_talleres t"
                . " LEFT JOIN casal_cursos c ON c.id=t.id_curso "
                . " WHERE t.id='$taller'";
        return $this->db->query($sql)->row()->idCurso;
    }

    function getNombreCurso($curso) {
        $sql = "SELECT nombre FROM casal_cursos WHERE id='$curso'";
        return $this->db->query($sql)->row()->nombre;
    }

    function getNombreTaller($taller) {
        $sql = "SELECT nombre_corto as nombre FROM casal_talleres WHERE id='$taller'";
        return $this->db->query($sql)->row()->nombre;
    }

    function getDatosTaller($taller) {
        $sql = "SELECT t.nombre as nombreTaller, p.nombre as nombreProfesor, 
             ds1.nombre_catalan as dia1,
             ds2.nombre_catalan as dia2,
             t.inicio_1 as inicio1,
             t.inicio_2 as inicio2,
             t.final_1 as final1,
             t.final_2 as final2,
             e.nombre as espacio,
             fecha_inicio as fecha_inicio,
             fecha_final as fecha_final,
             t.tipo_taller as tipo_taller,
             t.precio_curso as precio_curso,
             t.precio_rosa_curso as precio_rosa_curso,
             t.precio_trimestre as precio_trimestre,
             t.precio_rosa_trimestre as precio_rosa_trimestre,
             c.nombre as nombre_curso
             
             FROM casal_talleres t
               LEFT JOIN casal_espacios e ON t.id_espacio_1=e.id
               LEFT JOIN casal_profesores p ON p.id=t.id_profesor
              LEFT JOIN casal_dias_semana ds1 ON ds1.id=t.id_dia_semana_1
              LEFT JOIN casal_dias_semana ds2 ON ds2.id=t.id_dia_semana_2
              LEFT JOIN casal_cursos c ON t.id_curso=c.id
               WHERE t.id='$taller'";
        return $this->db->query($sql)->row();
    }

    function getPrecioTallerCurso($taller) {
        $sql = "SELECT precio_curso FROM casal_talleres WHERE id='$taller'";
        return $this->db->query($sql)->row()->precio_curso;
    }

    function getPrecioTallerSocio($taller, $socio, $periodo) {
        $talleresPrecios = array();
        $sql = "SELECT * FROM casal_talleres WHERE id='$taller'";
        $result = $this->db->query($sql)->row();
        if (!$result->id_agrupacion) {
            //no es un taller con agrupaciones, se paga lo normal del curso
            switch ($periodo) {
                case 7:
                    //$precio=$result->precio_curso;
                    $talleresPrecios[] = array('taller' => $taller, 'precio' => $result->precio_curso, 'tipo' => 0);
                    break;
                default:
                    //$precio=$result->precio_trimestre;
                    $talleresPrecios[] = array('taller' => $taller, 'precio' => $result->precio_trimestre, 'tipo' => 0);
            }
            return $talleresPrecios;
        }

        //analizamos si ya está inscrito para un taller de esta agrupacion y perido
        $agrupacion = $result->id_agrupacion;
        $sql = "SELECT a.pagado as pagado, t.id as taller,
                ag.precio_trimestre as precio_trimestre_agrupacion, 
                ag.precio_curso as precio_curso_agrupacion,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller
               FROM casal_asistentes a
               INNER JOIN casal_agrupaciones ag ON ag.id='$agrupacion'
               INNER JOIN casal_talleres t ON t.id=a.id_taller AND t.id_agrupacion='$agrupacion'
               WHERE a.id_socio='$socio' AND (($periodo=(trimestres & $periodo)) OR  ($periodo=(trimestres_pago & $periodo)))  
               ";
        //echo $sql;
        if ($this->db->query($sql)->num_rows() == 0) {
            //si no hay ninguno, de la misma agrupación, se aplican los precios normales
            switch ($periodo) {
                case 7:
                    //$precio=$result->precio_curso;
                    $talleresPrecios[] = array('taller' => $taller, 'precio' => $result->precio_curso, 'tipo' => 0);
                    break;
                default:
                    //$precio=$result->precio_trimestre;
                    $talleresPrecios[] = array('taller' => $taller, 'precio' => $result->precio_trimestre, 'tipo' => 0);
            }
            return $talleresPrecios;
        }

        //existen talleres de la misma agrupación, socio y periodo
        $result = $this->db->query($sql)->result();
        //$diferencia=0;
        foreach ($result as $k => $v) {
            switch ($periodo) {
                case 7:
                    //$diferencia+=$v->precio_curso_agrupacion-$v->pagado;
                    $talleresPrecios[] = array('taller' => $v->taller, 'precio' => $v->precio_curso_agrupacion - $v->pagado, 'tipo' => 2);
                    break;
                default:
                    //$diferencia+=$v->precio_trimestre_agrupacion-$v->pagado;
                    $talleresPrecios[] = array('taller' => $v->taller, 'precio' => $v->precio_trimestre_agrupacion - $v->precio_trimestre_taller, 'tipo' => 2);
            }
        }
        switch ($periodo) {
            case 7:
                //$diferencia+=$v->precio_curso_agrupacion-$v->pagado;
                $talleresPrecios[] = array('taller' => $taller, 'precio' => $v->precio_curso_agrupacion, 'tipo' => 1);
                break;
            default:
                //$diferencia+=$v->precio_trimestre_agrupacion-$v->pagado;
                $talleresPrecios[] = array('taller' => $taller, 'precio' => $v->precio_trimestre_agrupacion, 'tipo' => 1);
        }

        return $talleresPrecios;
    }

    //averigua qué precio se ha aplicado en la inscripción y los guarda en $talleresPrecios
    function getPrecioTallerSocioBaja($taller, $socio, $periodo) {
        $talleresPrecios = array();
        $sql = "SELECT * FROM casal_talleres WHERE id='$taller'";
        $result = $this->db->query($sql)->row();
        if (!$result->id_agrupacion) {
            //no es un taller con agrupaciones, se paga lo normal del curso
            switch ($periodo) {
                case 7:
                    $talleresPrecios[] = array('taller' => $taller,
                        'precio' => $result->precio_curso,
                        'diferencia' => 0,
                        'tipo' => 0);
                    break;
                default:
                    $talleresPrecios[] = array('taller' => $taller,
                        'precio' => $result->precio_curso,
                        'diferencia' => 0,
                        'tipo' => 0);
                    break;
            }
            return $talleresPrecios;
        }

        //¿en cuantos talleres del grupo está inscritos para el pediod
        $agrupacion = $result->id_agrupacion;
        $sql = "SELECT count(t.id) as num,
                t.precio_curso as precio_curso_taller,
                t.precio_trimestre as precio_trimestre_taller,
                ag.precio_curso as precio_curso_agrupacion,
                ag.precio_trimestre as precio_trimestre_agrupacion
		FROM casal_asistentes a
               LEFT JOIN casal_talleres t ON t.id=a.id_taller   
               LEFT JOIN casal_agrupaciones ag ON ag.id=t.id_agrupacion
               WHERE a.id_socio='$socio'
                     AND ag.id='$agrupacion'
                     AND (($periodo=(trimestres & $periodo)) OR  ($periodo=(trimestres_pago & $periodo)));    
               ";
        $result = $this->db->query($sql)->row();
        $num = $result->num;
        switch ($num) {
            case 1:
                switch ($periodo) {
                    case 7:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_curso_taller,
                            'diferencia' => 0,
                            'tipo' => 1);
                        break;
                    default:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_trimestre_taller,
                            'diferencia' => 0,
                            'tipo' => 1);
                        break;
                }
                break;
            case 2:
                switch ($periodo) {
                    case 7:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_curso_agrupacion,
                            'diferencia' => $result->precio_curso_agrupacion - $result->precio_curso_taller,
                            'tipo' => 2);
                        break;
                    default:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_trimestre_agrupacion,
                            'diferencia' => $result->precio_trimestre_agrupacion - $result->precio_trimestre_taller,
                            'tipo' => 2);
                        break;
                }
                break;
            case 3:
                switch ($periodo) {
                    case 7:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_curso_agrupacion,
                            'diferencia' => 0,
                            'tipo' => 3);
                        break;
                    default:
                        $talleresPrecios = array('taller' => $taller,
                            'precio' => $result->precio_trimestre_agrupacion,
                            'diferencia' => 0,
                            'tipo' => 3);
                        break;
                }
                break;
        }
        return $talleresPrecios;
    }

    function getPrecioTallerTrimestre($taller) {
        $sql = "SELECT precio_trimestre FROM casal_talleres WHERE id='$taller'";
        return $this->db->query($sql)->row()->precio_trimestre;
    }

    function getTalleresFiltro($filtro = " ") {
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();

        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
        $talleres = array();
        $sql = "SELECT id,nombre FROM casal_talleres WHERE $like ORDER BY nombre";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $taller = array(
                'id' => $v->id,
                'nombre' => $v->nombre,
            );
            $talleres[] = $taller;
        }
        foreach ($talleres as $k => $v) {
            $id = $v['id'];
            $nombre = $v['nombre'];
            $options[$id] = "$nombre";
        }
        return $options;
    }

    function getTalleresFiltroCurso($filtro = " ", $curso) {
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();

        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
        $talleres = array();
        $sql = "SELECT id,nombre FROM casal_talleres WHERE $like AND id_curso=$curso ORDER BY nombre";
        if($this->db->query($sql)->num_rows()==0) {
            $options[0]=' - Seleccionar un taller';
            return $options;
        }
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $taller = array(
                'id' => $v->id,
                'nombre' => $v->nombre,
            );
            $talleres[] = $taller;
        }
        foreach ($talleres as $k => $v) {
            $id = $v['id'];
            $nombre = $v['nombre'];
            $options[$id] = "$nombre";
        }
        return $options;
    }

    function getTaller($numTaller) {
        $row = $this->getDatosTaller($numTaller);
        $dias = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
        $meses = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
        $fecha_inicio = "";
        $fecha_final = "";
        if ($row->fecha_inicio !== '0000-00-00') {
            $fecha = date_create($row->fecha_inicio);
            $fecha_inicio = $dias[date_format($fecha, 'w')] . ', ' . date_format($fecha, 'd') . ' ' . $meses[date_format($fecha, 'm') - 1] . ' ' . date_format($fecha, 'Y');
        }
        if ($row->fecha_final !== '0000-00-00') {
            $fecha = date_create($row->fecha_final);
            $fecha_final = $dias[date_format($fecha, 'w')] . ', ' . date_format($fecha, 'd') . ' ' . $meses[date_format($fecha, 'm') - 1] . ' ' . date_format($fecha, 'Y');
        }
        $tipoTaller = $row->tipo_taller;
        $precioCurso = $row->precio_curso;
        $precioRosaCurso = $row->precio_rosa_curso;
        $precioTrimestre = $row->precio_trimestre;
        $precioRosaTrimestre = $row->precio_rosa_trimestre;

        return array('precioCurso' => $precioCurso,
            'precioRosaCurso' => $precioRosaCurso,
            'precioTrimestre' => $precioTrimestre,
            'precioRosaTrimestre' => $precioRosaTrimestre,
            'tipoTaller' => $tipoTaller, 
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
            'espacio' => $row->espacio,
            'nombreTaller' => $row->nombreTaller,
            'nombreProfesor' => $row->nombreProfesor,
            'dia1' => $row->dia1,
            'dia2' => $row->dia2,
            'final1' => $row->final1,
            'final2' => $row->final2,
            'inicio1' => $row->inicio1,
            'inicio2' => $row->inicio2,
            'nombreCurso'=>$row->nombre_curso);
    }

    function incrementarNumMaximo($taller) {
        $sql = "UPDATE casal_talleres SET num_maximo=num_maximo+1 WHERE id=$taller";
        $this->db->query($sql);
        $sql = "INSERT INTO casal_talleres_incrementados SET id_taller='$taller'";
        $this->db->query($sql);
        return true;
    }

    function checkIncrementarNumMaximo($taller) {
        $incrementada = $this->db->query("SELECT id_taller FROM casal_talleres_incrementados WHERE id_taller='$taller'")->num_rows();
        if ($incrementada) {
            $sql = "DELETE FROM casal_talleres_incrementados WHERE id_taller='$taller'";
            $this->db->query($sql);
            $sql = "UPDATE casal_talleres SET num_maximo=num_maximo-1 WHERE id='$taller'";
            $this->db->query($sql);
        }
        return $incrementada;
    }

}
