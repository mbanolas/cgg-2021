<?php

class Estadisticas_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function htmlTabla($sexos,$titulo,$taller){
        
        $total=$sexos['otros']+$sexos['hombres']+$sexos['mujeres'];
        if($total){
            $porcientoOtros=number_format($sexos['otros']/$total*100,1);
            $porcientoHombres=number_format($sexos['hombres']/$total*100,1);
            $porcientoMujeres=number_format($sexos['mujeres']/$total*100,1);
        }
        $html=$titulo;
        $html.='<table class="table table-striped" style="width:60%">';
        $html.='    <tr>';
        $html.='       <th class="col-md-7" style="text-align: left;">Sexe Desconegut:</th>';
        $html.='        <th class="col-md-2" style="text-align: right;" id="ins_otros'.$taller.'">'.$sexos["otros"].'</th>';
        $html.='        <th class="col-md-3" style="text-align: right;">'.$porcientoOtros.' %</th>       ';
        $html.='        <th rowspan="3" class="col-sm-2" style="text-align: right;"><span id="chart_div2'.$taller.'"></span></th>';
        $html.='    </tr>';
        $html.='      <tr>';
        $html.='       <th class="col-md-7" style="text-align: left;">Sexe Homes:</th>';
        $html.='        <th class="col-md-2" style="text-align: right;" id="ins_hombres'.$taller.'">'.$sexos["hombres"].'</th>';
        $html.='        <th class="col-md-3" style="text-align: right;"> '.$porcientoHombres.' %</th>       ';
        $html.='    </tr>';
        $html.='    <tr>';
        $html.='       <th class="col-md-7" style="text-align: left;">Sexe Dones:</th>';
        $html.='        <th class="col-md-2" style="text-align: right;" id="ins_mujeres'.$taller.'">'.$sexos["mujeres"].'</th>';
        $html.='        <th class="col-md-3" style="text-align: right;"> '.$porcientoMujeres.' %</th>       ';
        $html.='    </tr>';
        $html.='    </tr>';
        $html.='    <tr>';
        $html.='        <th class="col-md-7" >Total:</th>';
        $html.='        <th class="col-md-2" style="text-align: right;">'.$total.'</th>';
        $html.='        <th class="col-md-3" > </th>';      
        $html.='    </tr>';
        
        $html.='</table>';
        return $html;
    }
    function getTablaUsuariosInscritosSexos($curso,$periodoTexto){
        $otros=0;
        $hombres=0;
        $mujeres=0;
        
        $porcientoOtros=0;
        $porcientoHombres=0;
        $porcientoMujeres=0;
        $total=0;
        switch ($periodoTexto){
            case 'T1':
                $periodo=4;
                $nombrePeriodo="Trimestre 1 (Tardor)";
            break;
            case 'T2':
                $nombrePeriodo="Trimestre 2 (Hivern)";
                $periodo=2;
            break;
            case 'T3':
                $nombrePeriodo="Trimestre 3 (Primavera)";
                $periodo=1;
                break;
            default: 
                $periodo=7;    
        }
        $sql="SELECT nombre FROM casal_cursos WHERE id='$curso'";
        $nombreCurso=$this->db->query($sql)->row()->nombre;
        $sql="SELECT sexo as sexo, count(sexo) as num FROM casal_asistentes a
                    LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id
                    LEFT JOIN casal_talleres t ON a.id_taller=t.id
                    WHERE (a.trimestres & $periodo=$periodo ) 
                          AND t.id_curso='$curso'
                    GROUP BY sexo";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            if ($v->sexo=="Home") $hombres=$v->num;
            if ($v->sexo=="Dona") $mujeres=$v->num;
            if ($v->sexo=="") $otros=$v->num;
        }  
        $sexos=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres); 
        $titulo='<h3>Curs: '.$nombreCurso.' - '.$nombrePeriodo.'</h3>';
        $titulo.='<h3>Distribució usuaris/usuàries INSCRITS a tallers per sexe </h3>';
        $html=$this->htmlTabla($sexos,$titulo,"");

        $sql="SELECT id,nombre FROM casal_talleres WHERE id_curso='$curso' AND (id_periodo & $periodo =$periodo) ORDER BY nombre";
        // mensaje($sql);
        $talleres=$this->db->query($sql)->result();
        foreach($talleres as $k=>$v){
            $taller=$v->id;
            $nombreTaller=$v->nombre;
            // mensaje($nombreTaller);   
            $sql="SELECT sexo as sexo, count(sexo) as num FROM casal_asistentes a
                    LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id
                    LEFT JOIN casal_talleres t ON a.id_taller=t.id
                    WHERE (a.trimestres & $periodo = $periodo) 
                          AND t.id_curso='$curso'
                          AND t.id='$taller'
                    GROUP BY sexo";
            // mensaje($sql); 
            if($this->db->query($sql)->num_rows()==0) continue;      
            $result=$this->db->query($sql)->result();
            $otros = 0;
            $hombres = 0;
            $mujeres = 0;
            foreach($result as $k=>$v){
                if ($v->sexo=="Home") $hombres=$v->num;
                if ($v->sexo=="Dona") $mujeres=$v->num;
                if ($v->sexo=="") $otros=$v->num;
            }  
            $sexos=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres); 
            $titulo='<h3 class="taller" taller="'.$taller.'">'.$nombreTaller.'</h3>';
            $html.=$this->htmlTabla($sexos,$titulo,$taller);        
        }
        return $html;
    }

    function getDatosUsuariosInscritosSexos($curso,$periodoTexto){
        $otros=0;
        $hombres=0;
        $mujeres=0;
        
        $porcientoOtros=0;
        $porcientoHombres=0;
        $porcientoMujeres=0;
        $total=0;
        switch ($periodoTexto){
            case 'T1':
                $periodo=4;
                $nombrePeriodo="Trimestre 1 (Tardor)";
            break;
            case 'T2':
                $periodo=2;
                $nombrePeriodo="Trimestre 2 (Hivern)";
            break;
            case 'T3':
                $periodo=1;
                $nombrePeriodo="Trimestre 3 (Primavera)";
            break;
            default: 
            $periodo=7;
            $nombrePeriodo="";

        }
        $sql="SELECT nombre FROM casal_cursos WHERE id='$curso'";
        $nombreCurso=$this->db->query($sql)->row()->nombre;
        $sql="SELECT sexo as sexo, count(sexo) as num FROM casal_asistentes a
                    LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id
                    LEFT JOIN casal_talleres t ON a.id_taller=t.id
                    WHERE (a.trimestres='$periodo' OR a.trimestres='7') 
                          AND t.id_curso='$curso'
                    GROUP BY sexo";
        $result=$this->db->query($sql)->result();
        $hombres=0;
        $mujeres=0;
        $otros=0;
        foreach($result as $k=>$v){
            if ($v->sexo=="Home") $hombres=$v->num;
            if ($v->sexo=="Dona") $mujeres=$v->num;
            if ($v->sexo=="") $otros=$v->num;
        }  
        $sexos=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres); 
        $titulo='Distribució usuaris/usuàries INSCRITS a tallers per sexe';
        $resultados=array();
        $resultados[]=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres,'titulo'=>$titulo, 'taller'=>"");

        // $html=$this->htmlTabla($sexos,$titulo,"");

        $sql="SELECT id,nombre FROM casal_talleres WHERE id_curso='$curso' AND (id_periodo='$periodo' OR id_periodo='7') ORDER BY nombre ";
        mensaje('talleres en orden '.$sql);
        $talleres=$this->db->query($sql)->result();
        foreach($talleres as $k=>$v){
            $taller=$v->id;
            $nombreTaller=$v->nombre;
            $sql="SELECT sexo as sexo, count(sexo) as num FROM casal_asistentes a
                    LEFT JOIN casal_socios_nuevo s ON a.id_socio=s.id
                    LEFT JOIN casal_talleres t ON a.id_taller=t.id
                    WHERE (a.trimestres & $periodo = $periodo) 
                          AND t.id_curso='$curso'
                          AND t.id='$taller'
                    GROUP BY sexo";
            // mensaje($sql); 
            if($this->db->query($sql)->num_rows()==0) continue;      
            $result=$this->db->query($sql)->result();
            $hombres=0;
            $mujeres=0;
            $otros=0;
            foreach($result as $k=>$v){
                if ($v->sexo=="Home") $hombres=$v->num;
                if ($v->sexo=="Dona") $mujeres=$v->num;
                if ($v->sexo=="") $otros=$v->num;
            }  
            $sexos=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres); 
            $titulo='Taller: '.$nombreTaller;
            // $html.=$this->htmlTabla($sexos,$titulo,$taller); 
            $resultados[]=array('otros'=>$otros, 'hombres'=>$hombres,'mujeres'=>$mujeres,'titulo'=>$titulo, 'taller'=>$taller);
        }
        
        return $resultados;
    }
}



