
<?php

class Comisiones_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getDatosComisiones() {
        $sql = "SELECT * FROM casal_comisiones WHERE 1";
        $comisiones = $this->db->query($sql)->result_array();
        return $comisiones;
    }
    
    function getDatosComision($id) {
        $sql = "SELECT *  FROM casal_comisiones c
                    LEFT JOIN `casal_miembros_comisiones` m ON m.id_comision=c.id
                    LEFT JOIN casal_socios_nuevo s ON s.num_socio=m.id_socio
                    WHERE m.id_comision='$id'";
         
        $result=$this->db->query($sql)->result_array();
        $datos=array();
        foreach($result as $k=>$v){
           $datos[]=$v;
        }
        return $datos;
    }
    
    function eliminarMiembro($id_comision,$id_socio){
        $sql="DELETE FROM `casal_miembros_comisiones` WHERE id_comision='$id_comision' AND id_socio='$id_socio'";
        return $this->db->query($sql);
    }
    
    function ponerMiembro($id_comision,$id_socio){
        $sql="INSERT INTO `casal_miembros_comisiones` SET id_comision='$id_comision', id_socio='$id_socio'";
        return $this->db->query($sql);
    }

}
