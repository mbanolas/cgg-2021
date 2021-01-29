<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No permitido el acceso directo a la URL</h2>");

class Pruebas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        
    }

    public function MD5(){

        $this->db->query("ALTER TABLE `casal_socios_nuevo` ADD `recibir_emails` ENUM('Sí','No') NOT NULL DEFAULT 'No' AFTER `email`");  
        $this->db->query("ALTER TABLE `casal_socios_nuevo` ADD `email_no_valido` ENUM('Sí','No') NOT NULL DEFAULT 'No' AFTER `recibir_emails`");  
        $this->db->query("UPDATE `casal_socios_nuevo` SET `recibir_emails`='No',`email_no_valido`='No' WHERE 1");  
        $this->db->query("ALTER TABLE `casal_emails` ADD `resultado` VARCHAR(15) NULL DEFAULT NULL AFTER `fecha`");  
        $this->db->query("ALTER TABLE `casal_emails` ADD `bloques` INT NULL DEFAULT NULL AFTER `resultado`");     




        //temporal para poner recibir_emails en Sí los que tengan email
        $result=$this->db->query("SELECT * FROM casal_socios_nuevo WHERE email!=''")->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $this->db->query("UPDATE casal_socios_nuevo SET recibir_emails='Sí' WHERE id='$id'");
            $this->db->query("UPDATE casal_socios_nuevo SET email_no_valido='No' WHERE id='$id'");
            // $this->db->query("UPDATE casal_socios_nuevo SET email_no_valido = 'Sí'  WHERE id='$id' and email like '%yahoo%' ");
        }
        $result=$this->db->query("SELECT * FROM casal_asistentes WHERE fecha_devolucion='0000-00-00'")->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $this->db->query("UPDATE casal_asistentes SET fecha_devolucion='1970-01-01' WHERE id='$id'");
        }
        $result=$this->db->query("SELECT * FROM casal_asistentes WHERE fecha_pago='0000-00-00'")->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $this->db->query("UPDATE casal_asistentes SET fecha_pago='1970-01-01' WHERE id='$id'");
        }
        $this->db->query("ALTER TABLE `casal_asistentes` CHANGE `pagado` `pagado` DECIMAL(10,2) NOT NULL;
        ");
        $this->db->query("ALTER TABLE `casal_asistentes` CHANGE `tarjeta` `tarjeta` DECIMAL(10,2) NOT NULL;
        ");

        $primerTaller=$this->db->query("SELECT  id FROM casal_talleres WHERE id_curso=6 LIMIT 1")->row()->id;
        echo $primerTaller;
        echo '<br>';
        $this->db->query("UPDATE casal_asistentes SET pagado='17.85' WHERE id_taller>='$primerTaller' AND pagado='18'");
        $this->db->query("UPDATE casal_asistentes SET pagado='11.90' WHERE id_taller>='$primerTaller' AND pagado='12'");
        $this->db->query("UPDATE casal_asistentes SET pagado='12.75' WHERE id_taller>='$primerTaller' AND pagado='13'");
        $this->db->query("UPDATE casal_asistentes SET pagado='8.50' WHERE id_taller>='$primerTaller' AND pagado='9'");


        // termina temporal
        echo 'TERMINADO';
    }

}
