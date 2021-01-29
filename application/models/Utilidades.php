<?php

//file: application/models/test_model.php

/*
 * @author: Alysson Ajackson
 * @date: Wed Apr 10 22:11:22 BRT 2013
 * */
class Utilidades extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function verificarEntrada(){

        extract($_POST);
        $password=MD5($password);
        $nombre="";
        $tipoUsuario="0";
        $sql="SELECT nombre,tipoUsuario FROM casal_users WHERE username='$usuario' AND password='$password'";
        if($this->db->query($sql)->num_rows()) $resultado=true; else $resultado=false;
        if($resultado){
            $nombre=$this->db->query($sql)->row()->nombre;
            $tipoUsuario=$this->db->query($sql)->row()->tipoUsuario;
            switch($tipoUsuario){
                    case 0:
                        $categoria='Administrador Tienda';
                        break;
                    case 1:
                        $categoria='Usuari aplicació';
                        break;
                    case 2:
                        $categoria='Reading access';
                        break;
                    case 3:
                        $categoria='User, full access';
                        break;
                    case 10:
                        $categoria='Programador aplicació';
                        break;
                    case 50:
                        $categoria='Servicios jurídicos';
                        break;
                    default:
                        $categoria='No categorized';
                }
                $datosLogin = array(
                    'username'  => $usuario,
                    'nombre'     => $nombre,
                    'logged_in' => $resultado,
                    'tipoUsuario' => $tipoUsuario,
                    'categoria' => $categoria,
                );
                $datosLogin=compact('usuario','nombre','tipoUsuario','categoria','logged_in');
                
                $this->session->set_userdata($datosLogin);
            
        }
        return $resultado;
    }

    function registro(){
        $usuario=$this->session->nombre;
        $fecha=date("Y-m-d H:i");
        $sql="INSERT INTO casal_web_registros SET usuario='$usuario', fecha='$fecha'";
        //$this->db->query($sql);
    }
    
    // Function to get the client ip address
public function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
 
function fechaMovimientoWeb($fecha,$nombre){
    $ip=$this->get_client_ip_env();  
    $categoria=$this->session->categoria;  
    mensaje('$tipoUsuario '.$this->session->tipoUsuario);
    $sql="INSERT INTO casal_web_registros SET usuario='$nombre',fecha='$fecha',IP='$ip',registro='$categoria' ";
    $this->db->query($sql);
    return '$fecha';
}
    
    
    
}

