<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No permitido el acceso directo a la URL</h2>");
date_default_timezone_set('Europe/Madrid');

// require 'vendor/autoload.php';

// use SMTPValidateEmail\Validator as SmtpEmailValidator;



class BasesDatos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
        ini_set('memory_limit', '-1');
        
    }

    public function _template_output_aniversarios($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];

        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $this->load->view('basesDatosAniversarios.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }


    public function _output_emails($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        // $datos['titulo'] = $_SESSION['tituloCasal'];


        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('emails.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }
    public function _output_emails_2($output = null, $table)
    {


        // $datos['titulo'] = $_SESSION['tituloCasal'];
        // $datos['emailCasal'] = getEmailCasal();
        // $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/barraNavegacion', $datos);
        // $this->load->view('emailsIncompleto');
        // $this->load->view('myModal');

        // return;

        $datos['autor'] = 'Miguel Angel Bañolas';
        // $datos['titulo'] = $_SESSION['tituloCasal'];

        $datos['titulo'] = $_SESSION['tituloCasal'];

        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('emails_2.php', $output);
        $this->load->view('myModalEmails.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }



    public function _example_output($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];


        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('basesDatos.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }
    public function _example_output_talleres($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];


        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('basesDatosTalleres.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function _output_comisiones($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];

        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('comisiones.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function _example_output_socios($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];


        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('basesDatos.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    public function _output_reservas($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = $_SESSION['tituloCasal'];


        $this->load->view('templates/headerGrocery', $output);
        // $this->load->view('templates/cabecera');
        $datos['activeMenu'] = 'Bases de datos';
        $datos['activeSubmenu'] = $table;
        $this->load->view('templates/barraNavegacion', $datos);
        $salida = array('output' => $output, 'table' => $table);
        $this->load->view('basesDatosReservas.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer', $datos);
    }

    function putDireccionesPara()
    {
        $datos = $_POST['direcciones_para'];
        $resultado = $this->db->query("INSERT INTO casal_provisional SET datos='$datos'");
        echo json_encode($resultado);
    }

    function afterInsertEmail($post_array, $primary_key)
    {
        $grupo = "";
        switch ($post_array['grupo']) {
            case 'option2':
                $taller = $this->db->query("SELECT datos FROM casal_provisional LIMIT 1")->row()->datos;
                $grupo = "Taller: " . $taller;
                break;
            case 'option3':
                $grupo = "Usuaris / usuàries de tots els tallers";
                break;
            case 'option4':
                $grupo = "Usuaris / usuàries de tots els tallers VOLUNTARIS ";
                break;
            case 'option5':
                $grupo = "Usuaris / usuàries de tots els tallers PROFESSIONALS ";
                break;
            case 'option6':
                $grupo = "Altres ";
                break;
            case 'option10':
                $grupo = "Tots els usuaris / usuàries casal ";
                break;
            default:
                $grupo = "------------";
        }

        ignore_user_abort(true);

        $row = $this->db->query("SELECT * FROM casal_emails WHERE id='$primary_key'")->row();

        $this->sendEmail($row->para, $row->titulo, $row->mensaje, $row->adjunto_1, $row->adjunto_2, $row->adjunto_3);

        $ahora = date("Y-m-d H:i");
        // $this->db->query("UPDATE casal_emails SET  grupo='$grupo',fecha='$ahora' WHERE id='$primary_key'");

        return true;
    }

    public function getLastBloques(){
        $query="SELECT * FROM casal_emails ORDER BY id DESC LIMIT 1";
        $row=$this->db->query($query)->row();
        if($row){
        $bloques = $row->bloques;
        return $bloques?$bloques:15;
        }
        return 15;
    }


    public function emails($curso = 0)
    {

        $id_curso = $this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
        if ($curso == 1) $id_curso--; //con datos del curso anterior (ver callback para )

        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");

        // $crud->add_action('Copiar', '', '', 'ui-icon-image', array($this, 'copiar_email'));
        // $crud->add_action('Enviar email usuaris/usuàrias inscrits a tallers', '', '', 'ui-icon-image', array($this, 'enviar_mail_asistetes_talleres'));
        // $crud->add_action('Enviar email comissions', '', '', 'ui-icon-image', array($this, 'enviar_mail_comisiones'));

        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_emails');
        $crud->order_by('id', 'desc');

        $crud->set_subject("Emails");

        $crud->columns(['grupo', 'titulo', 'mensaje','resultado' ,'fecha']);
        $crud->add_fields(array('grupo', 'para','para_yahoo','bloques', 'titulo', 'mensaje', 'adjunto_1', 'adjunto_2', 'adjunto_3'));

        $crud->callback_after_insert(array($this, 'afterInsertEmail'));


        $crud->unset_edit();
        $crud->unset_delete();

        $crud->unset_texteditor('para');
        $crud->unset_texteditor('para_yahoo');

        $crud->display_as('titulo', 'Títol');
        $crud->display_as('para', 'Destinataris');
        $crud->display_as('envio_error', 'Adreces amb error (no enviats)');
        $crud->display_as('grupo', 'Grup');
        $crud->display_as('mensaje', 'Missatge');
        $crud->display_as('adjunto_1', 'Adjunt 1');
        $crud->display_as('adjunto_2', 'Adjunt 2');
        $crud->display_as('adjunto_3', 'Adjunt 3');
        $crud->display_as('bloques', "Blocs emails");
        $crud->display_as('resultado', "Núm. emails");
        $crud->display_as('para_yahoo', "Emails yahoo (no enviados)");

        if ($curso == 0) {
            $crud->callback_add_field('grupo', function () {
                $emailCasal = getEmailCasal();
                $emailOneCasal = getCorreoServidorCasal();
                $id_curso = $this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
                $talleres = $this->db->query("SELECT id,nombre, tipo_taller FROM casal_talleres WHERE id_curso='$id_curso' ORDER BY nombre ")->result();
                $optionsTalleres = "";
                foreach ($talleres as $k => $v) {
                    $optionsTalleres .= "<option value='$v->id'>$v->nombre</option>";
                }
                $cursos = $this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC ")->result();
                $optionsCursos = "";
                foreach ($cursos as $k => $v) {
                    $optionsCursos .= "<option value='$v->id'>$v->nombre</option>";
                }
                $bloques=$this->getLastBloques();

                return '
            <br />
            <input type="hidden" value="' . $emailCasal . '" id="emailCasal">
            <input type="hidden" value="' . $emailOneCasal . '" id="emailOneCasal">

            <input type="hidden" value="' . $bloques . '" id="bloques">
            <div id="opcionesPara">
                
                <div class="form-check">
                    
                    <input class="form-check-input" type="radio" name="grupo" id="para2" value="option2">
                    <label class="form-check-label" for="para2">
                        Usuaris / usuàries d´un taller
                    </label>
                    
                <select style="width:150px;" id="cursos" class="hide">' .
                    $optionsCursos .
                    '</select>
                    <span id="periodos" class="hide">
                    <input type="radio"  name="x" value="7" id="C_" checked > <label  > C </label>  
                    <input type="radio"  name="x" value="4" id="T1_"> <label  > T1 </label>  
                    <input type="radio"  name="x" value="2" id="T2_"> <label  > T2 </label>  
                    <input type="radio"  name="x" value="1" id="T3_"> <label  > T3 </label>  
                    </span>
                    <div style="margin-left: 255px;">
                <select style="width:150px;" id="talleres" class="hide">
                        <option value="0">Seleccionar un taller</option>' .
                    $optionsTalleres .
                    '</select>
                    </div>
                    
                    <label id="direcciones2" class="direcciones"></label>
                </div>

             

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="grupo" id="para3" value="option3">
                    <label class="form-check-label" for="para3">
                        Usuaris / usuàries de tots els tallers
                    </label>
                    <select style="width:150px;" id="cursos0" class="hide">' .
                    $optionsCursos .
                    '</select>
                    <label id="direcciones3" class="direcciones"></label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="grupo" id="para4" value="option4">
                    <label class="form-check-label" for="para4">
                        Usuaris / usuàries de tots els tallers VOLUNTARIS
                    </label>
                    <select style="width:150px;" id="cursos1" class="hide">' .
                    $optionsCursos .
                    '</select>
                    <label id="direcciones4" class="direcciones"></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="grupo" id="para5" value="option5">
                    <label class="form-check-label" for="para5">
                        Usuaris / usuàries de tots els tallers PROFESSIONALS
                    </label>
                    <select style="width:150px;" id="cursos2" class="hide">' .
                    $optionsCursos .
                    '</select>
                    <label id="direcciones5" class="direcciones"></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="grupo" id="para10" value="option10" >
                    <label class="form-check-label" for="para10">
                        Tots els usuaris / usuàries casal
                    </label>
                    <label id="direcciones10" class="direcciones"></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="grupo" id="para6" value="option6">
                    <label class="form-check-label" for="para6">
                        Altres
                    </label>
                    <label id="direcciones6" class="direcciones"></label>
                </div>
                <div class="">
                    <input type="text" class="form-control hide" id="otros" placeholder="Indicar una o diverses adreces email separades per comes">
                </div>
           </div>
           ';
            });
        } else {
            $crud->callback_add_field('grupo', function () {
                $emailCasal = getEmailCasal();
                $emailOneCasal = getCorreoServidorCasal();
                $id_curso = $this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC LIMIT 1")->row()->id;
                $id_curso--;  //talleres del curso anterior
                $talleres = $this->db->query("SELECT id,nombre, tipo_taller FROM casal_talleres WHERE id_curso='$id_curso' ORDER BY nombre ")->result();
                $optionsTalleres = "";
                foreach ($talleres as $k => $v) {
                    $optionsTalleres .= "<option value='$v->id'>$v->nombre</option>";
                }
                $bloques=$this->getLastBloques();

                return '
                <br />
                <input type="hidden" value="' . $emailCasal . '" id="emailCasal">
                <input type="hidden" value="' . $emailOneCasal . '" id="emailOneCasal">
                <input type="hidden" value="' . $bloques . '" id="bloques">

                <div id="opcionesPara">
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para2" value="option2">
                        <label class="form-check-label" for="para2">
                            Usuaris / usuàries d´un taller
                        </label>
                        <select id="talleres" class="hide">
                            <option value="0">Seleccionar un taller</option>' .
                    $optionsTalleres .
                    '</select>
                        <label id="direcciones2" class="direcciones"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para3" value="option3">
                        <label class="form-check-label" for="para3">
                            Usuaris / usuàries de tots els tallers
                        </label>
                        <label id="direcciones3" class="direcciones"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para4" value="option4">
                        <label class="form-check-label" for="para4">
                            Usuaris / usuàries de tots els tallers VOLUNTARIS
                        </label>
                        <label id="direcciones4" class="direcciones"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para5" value="option5">
                        <label class="form-check-label" for="para5">
                            Usuaris / usuàries de tots els tallers PROFESSIONALS
                        </label>
                        <label id="direcciones5" class="direcciones"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para10" value="option10" >
                        <label class="form-check-label" for="para10">
                            Tots els usuaris / usuàries casal
                        </label>
                        <label id="direcciones10" class="direcciones"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grupo" id="para6" value="option6">
                        <label class="form-check-label" for="para6">
                            Altres
                        </label>
                        <label id="direcciones6" class="direcciones"></label>
                    </div>
                    <div class="">
                        <input type="text" class="form-control hide" id="otros" placeholder="Indicar una o diverses adresses email separades per comes">
                    </div>
               </div>
               ';
            });
        }

        $crud->required_fields(array('para', 'bloques','grupo', 'titulo', 'mensaje'));

        $crud->set_field_upload('adjunto_1', 'assets/uploads/files');
        $crud->set_field_upload('adjunto_2', 'assets/uploads/files');
        $crud->set_field_upload('adjunto_3', 'assets/uploads/files');

        $output = $crud->render();


        // $options="";
        // $result=$this->db->query("SELECT * FROM casal_cursos ORDER BY id DESC")->result();
        // foreach($result as $k=>$v){
        //     $options.='<option value="'.$v->id.'">Amb usuaris/usuàries inscrits en tallers curs '.$v->nombre.'</options>';
        // }
        // $output->cursos=$options;



        // $this->_output_emails($output, 'Emails');
        $this->_output_emails_2($output, 'Emails');
    }

    public function comisiones()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        // $crud->add_action('Enviar email', '', '','ui-icon-image',array($this,'enviar_mail'));
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_comisiones');
        $crud->set_subject("Comissions");

        $output = $crud->render();
        $this->_output_comisiones($output, 'casal_comisiones');
    }

    function copiar_email($primary_key, $row)
    {
        return site_url('socios/copiar_email/' . $row->id);
    }
    function enviar_mail_asistetes_talleres($primary_key, $row)
    {
        return site_url('socios/enviar_mail_asistetes_talleres/' . $row->id);
    }
    function enviar_mail_comisiones($primary_key, $row)
    {
        return site_url('socios/enviar_mail_comisiones/' . $row->id);
    }

    public function casal_reservas()
    {

        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_reservas_grocery');
        $crud->order_by('curso', 'desc');
        $crud->order_by('trimestre', 'desc');

        $crud->display_as('socio', 'Usuari/Usuària');
        $crud->display_as('curso', 'Curs');
        $crud->display_as('orden', 'Ordre');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');

        $crud->callback_column('telefono_1', array($this, '_callback_telefono'));
        $crud->callback_column('telefono_2', array($this, '_callback_telefono'));

        $crud->set_subject("En llista d´espera");

        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_read();

        $output = $crud->render();
        $this->_output_reservas($output, "En llista d´espera");
    }
    public function _callback_telefono($value, $row)
    {
        $value = trim($value);
        if (strlen($value) == 9) {
            $value = substr($value, 0, 3) . ' ' . substr($value, 3, 3) . ' ' . substr($value, 6, 3);
        }
        return $value;
    }

    function validar_email($email, $texto)
    {
        if (!$email) return true;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        $this->form_validation->set_message('validar_email', "EL " . $texto . " NO és vàlid.");
        return false;
    }

    public function _callback_email($value, $row)
    {
        return "<a href='mailto:" . $value . "'>" . $value . "</a>";
    }

    public function casal_socios()
    {

        

        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_socios_nuevo');

        $crud->display_as('id', 'Núm');
        $crud->display_as('num_socio', 'Núm');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('apellidos', 'Cognoms');
        $crud->display_as('direccion', 'Direcció');
        $crud->display_as('poblacion', 'Població');
        $crud->display_as('codigo_postal', 'Codi Postal');
        $crud->display_as('dni', 'DNI/NIE/PASP');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');
        $crud->display_as('fecha_nacimiento_local', 'Data Naixement');
        $crud->display_as('fecha_nacimiento', 'Data Naixement');
        $crud->display_as('fecha_alta_local', "Data d'alta");
        $crud->display_as('fecha_baja_local', "Data de baixa");
        $crud->display_as('fecha_alta', "Data d'alta");
        $crud->display_as('fecha_baja', "Data de baixa");
        $crud->display_as('comentarios', "Comentaris");
        $crud->display_as('usuario', "Usuari");
        $crud->display_as('socio', "Soci/Sòcia");
        $crud->display_as('email', "Email");
        
        $crud->display_as('recibir_emails', "Vol rebre emails");
        $crud->unset_delete();

        if ($this->session->categoria == 40) {
            $crud->unset_add();
            $crud->unset_edit();
        }

        $crud->add_action('Pdf', '', '', 'ui-icon-image', array($this, 'just_a_test'));

        $campos_no_read = array('fecha_alta', 'fecha_baja', 'fecha_nacimiento');
        $crud->unset_read_fields($campos_no_read);

        $crud->set_rules('email', '', 'callback_validar_email[email]');

        $crud->unique_fields(array('dni'));
        $crud->columns('nombre', 'apellidos', 'telefono_1', 'telefono_2', 'email', 'recibir_emails','fecha_nacimiento_local'); //, 'fecha_baja_local', 'comentarios');
        //segment(3)=="export" ó segment(4)=="export" depende de la route config
        $crud->callback_column('email', array($this, '_callback_email'));

        if ($this->uri->segment(3) == "export") {
            if ($this->session->categoria == 40)
                $this->sendEmail('mbanolas@gmail.com', 'Exportada Base Datos Usuarios', 'Base Datos Usuarios ' . getTituloCasal());

            $crud->columns('id', 'nombre', 'apellidos', 'telefono_1', 'telefono_2', 'email', 'fecha_nacimiento_local', 'fecha_baja_local', 'comentarios');
            // mensaje("'num_socio', 'nombre', 'apellidos', 'sexo', 'direccion', 'telefono_1','telefono_2','fecha_nacimiento'");
        }
        if ($this->uri->segment(3) == "print") {
            if ($this->session->categoria == 40)
                $this->sendEmail('mbanolas@gmail.com', 'Imprimida Base Datos Usuarios', 'Base Datos Usuarios ' . getTituloCasal());

            $crud->columns('id', 'nombre', 'apellidos', 'telefono_1', 'telefono_2', 'email', 'fecha_nacimiento_local', 'fecha_alta_local', 'fecha_baja_local', 'comentarios');

            // mensaje("'num_socio', 'nombre', 'apellidos', 'sexo', 'direccion', 'telefono_1','telefono_2','fecha_nacimiento'");
        }



        // $noUsados=array('id_agrupacion','direccion','poblacion','provincia','codigo_postal','dni','fecha_alta','fecha_baja','comentarios');
        // $crud->unset_columns($noUsados);

        $crud->unset_add_fields('usuario', 'fecha_nacimiento_local', 'edad', 'dias_next_aniversario', 'fecha_alta_local', 'fecha_baja_local', 'fecha_baja', 'fecha_modificacion');
        $crud->unset_edit_fields('usuario', 'fecha_nacimiento_local', 'edad', 'dias_next_aniversario', 'fecha_alta_local', 'fecha_baja_local', 'fecha_modificacion');



        $crud->set_subject("Usuaris/Usuàrias");

        $crud->callback_column('telefono_1', array($this, '_telefono'));
        $crud->callback_column('telefono_2', array($this, '_telefono'));
        // $crud->callback_column('fecha_nacimiento_local', array($this, '_fecha_nacimiento_local'));

        // $crud->set_rules($field, $label, $rules)

        $crud->callback_add_field('num_socio', function () {
            $sql = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE  TABLE_NAME   = 'casal_socios_nuevo'";
            $num_socio = $this->db->query($sql)->row()->AUTO_INCREMENT;

            return "<label  class='control-label'>$num_socio</label>";
        });




        $crud->callback_edit_field('num_socio', function ($value, $primary_key) {
            return "<label  class='control-label'>$value</label>";
        });

        $crud->callback_add_field('poblacion', function () {
            return '<input id="field-poblacion" class="form-control" name="poblacion" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('provincia', function () {
            return '<input id="field-provincia" class="form-control" name="provincia" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('fecha_alta_', function () {
            return '<input id="field-fecha_alta" name="fecha_alta" type="text" value="" maxlength="10" class="datepicker-input form-control hasDatepicker">
		<a class="datepicker-input-clear ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" tabindex="-1" role="button" aria-disabled="false"><span class="ui-button-text">Resetejar</span></a> (dd/mm/yyyy)                                    ';
        });


        $crud->required_fields('nombre', 'apellidos', 'dni', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'fecha_nacimiento', 'fecha_alta', 'tarjeta_rosa', 'socio', 'sexo');

        $crud->callback_before_insert(array($this, 'nombresPropios'));
        $crud->callback_before_update(array($this, 'nombresPropios'));

        $crud->callback_after_insert(array($this, '_fecha_alta_socios'));
        $crud->callback_after_update(array($this, '_fecha_modificacion_socios'));

        $crud->set_rules('dni', 'DNI', 'callback_validar_dni');
        $crud->set_rules('codigo_postal', 'Código Postal', 'callback_validar_codigo_postal');

        $state = $crud->getState();

        $output = $crud->render();

        if ($state == 'add') {
            $js = '<script> $(\'select[name="tarjeta_rosa"] option[value="No"]\').attr("selected","selected");</script>';
            $output->output .= $js;
        }

        if ($state == 'add') {
            $js2 = '<script> $(\'select[name="socio"] option[value="Sí"]\').attr("selected","selected");</script>';
            $output->output .= $js2;
        }

        if ($state == 'add') {

            $js3 = '<script> var today = new Date();
                           var dd = today.getDate();
                           var mm = today.getMonth()+1; //January is 0!
                           var yyyy = today.getFullYear();
                            if(dd<10) {
                                dd = "0"+dd
                            } 
                            if(mm<10) {
                                mm = "0"+mm
                            } 
                            today = dd + "/" + mm + "/" + yyyy;
                            $(\'input#field-fecha_alta\').attr("value",today);</script>';
            $output->output .= $js3;
        }

        $this->_example_output_socios($output, 'Usuaris/Usuàrias');
    }



    public function nombresPropios($post_array)
    {
        $post_array['nombre'] = mb_convert_case($post_array['nombre'], MB_CASE_TITLE, "UTF-8");
        $post_array['apellidos'] = mb_convert_case($post_array['apellidos'], MB_CASE_TITLE, "UTF-8");
        $post_array['direccion'] = mb_convert_case($post_array['direccion'], MB_CASE_TITLE, "UTF-8");
        $post_array['poblacion'] = mb_convert_case($post_array['poblacion'], MB_CASE_TITLE, "UTF-8");
        $post_array['provincia'] = mb_convert_case($post_array['provincia'], MB_CASE_TITLE, "UTF-8");
        $dni = $post_array['dni'];
        $dni = str_replace(' ', '', $dni);
        $dni = str_replace('-', '', $dni);
        $dni = str_replace('.', '', $dni);
        $dni = strtoupper($dni);
        $post_array['dni'] = $dni;
        return $post_array;
    }
/*
    // function enviarEmails__() //$to = 'mbanolas@gmail.com', $subject = "Sense títol", $message = "Sense missatge", $adjunto_1="", $adjunto_2="",$adjunto_3="")
    // {

    //     // $this->load->library('email');


    //     //         $this->email->from('info@gestiocggsantmarti.com', 'Your Name');
    //     // $this->email->to('mbanolas@gmail.com');
    //     // // $this->email->cc('another@another-example.com');
    //     // // $this->email->bcc('them@their-example.com');

    //     // $this->email->subject('Email Test');
    //     // $this->email->message('Testing the email class.');

    //     // $resultado=$this->email->send();
    //     // mensaje('resultado '.$resultado);
    //     // echo json_encode(array('emailNoEnviadosYahoo' => '', 'enviados' => $emailEnviados, 'contador' =>  1, 'finalizado' => 'finalizado'));
    //     // return;





    //     $primero = $_POST['primero'];
    //     $ultimo = $_POST['ultimo'];
    //     $para = $_POST['para'];
    //     $paraArray = explode(',', $para);
    //     $finalizado = false;

    //     mensaje($primero);
    //     mensaje($ultimo);
    //     mensaje($para);

    //     if ($ultimo == count($paraArray) - 1) $finalizado = true;
        
    //     // mensaje('ultimo '.$ultimo);
    //     // mensaje('count($paraArray) '.count($paraArray));
    //     $rango = array();
    //     for ($i = $primero; $i <= $ultimo; $i++) {
    //         $rango[] = $paraArray[$i];
    //     }
    //     $to = implode(",", $rango);
    //     // mensaje('primero '.$primero);
    //     // mensaje('ultimo '.$ultimo);
    //     // mensaje('para '.$para);
    //     $subject = str_replace("'", "´", $_POST['titulo']);
    //     $mensaje = str_replace("'", "´", $_POST['mensaje']);

    //     // mensaje('to '.$to);
    //     $adjunto_1 = $_POST['adjunto_1'];
    //     $adjunto_2 = $_POST['adjunto_2'];
    //     $adjunto_3 = $_POST['adjunto_3'];
    //     $grupo = $_POST['grupo'];

    //     // echo json_encode(array('para'=>$para,'to'=>$to));

    //     // return;
    //     switch ($_POST['grupo']) {
    //         case 'option2':
    //             $taller = $this->db->query("SELECT datos FROM casal_provisional LIMIT 1")->row()->datos;
    //             $grupo = "Taller: " . $taller;
    //             break;
    //         case 'option3':
    //             $grupo = "Usuaris / usuàries de tots els tallers";
    //             break;
    //         case 'option4':
    //             $grupo = "Usuaris / usuàries de tots els tallers VOLUNTARIS ";
    //             break;
    //         case 'option5':
    //             $grupo = "Usuaris / usuàries de tots els tallers PROFESSIONALS ";
    //             break;
    //         case 'option6':
    //             $grupo = "Altres ";
    //             break;
    //         case 'option10':
    //             $grupo = "Tots els usuaris / usuàries casal ";
    //             break;
    //         default:
    //             $grupo = "------------";
    //     }



    //     $message = $mensaje;
    //     $message .= "<p style='box-sizing: border-box; margin: 0px 0px 10px; color: rgb(33, 33, 33); font-family: 'Segoe UI', 'egoe WP', 'Segoe UI WPC', Tahoma, Arial, sans-serif; font-size: 4px;'>" . "Per qualsevol dubte, aclariment o per no rebre més informació sobre les activitats del Casal de Gent Gran" . getTituloCasal() . ", podeu enviar-nos un correu electrònic a " . getEmailCasal();
    //     $message .= "<p style='box-sizing: border-box; margin: 0px 0px 10px; color: rgb(33, 33, 33); font-family: 'Segoe UI', 'egoe WP', 'Segoe UI WPC', Tahoma, Arial, sans-serif; font-size: 4px;'>" . "** AVÍS **<br>
    //     Aquest correu electrònic és confidencial i per a ús exclusiu del seu destinatari. Si vostè ha rebut aquest correu per error, li agrairem que ens ho comuniqui, abstenir-se de difondre’l, distribuir-lo, copiar-lo o emmagatzemar-lo. La seva adreça de correu electrònic juntament amb la informació que ens faciliti són tractades per INCOOP, en qualitat de responsable del tractament, amb la finalitat de gestionar i mantenir els contactes i relacions que es produeixin com a conseqüència de la relació que manté amb nosaltres. La base jurídica que legitima aquest tractament serà el seu consentiment, l'interès legítim o la necessitat per gestionar una relació contractual o similar. El termini de conservació de les seves dades vindrà determinat per la relació que manté amb nosaltres.";
    //     $message .= "<br>" . "Per a més informació al respecte, o per exercir els seus drets d'accés, rectificació, supressió, limitació del tractament, oposició i portabilitat pot enviar una notificació per escrit, adjuntant còpia d'un document que acrediti la seva identitat, a la següent adreça: C. Mallorca, 53 (Local 5) 08029 Barcelona o per correu electrònic a través de proteccio.dades@incoop.cat . En el cas que no hagi obtingut satisfacció en l'exercici dels seus drets pot presentar una reclamació davant l'Agència Espanyola de Protecció de Dades.
    //     Si desitja obtenir més informació sobre el tractament de les seves dades que duem a terme, consulti els nostres <a href='https://incoop.cat/politica-privacitat/?utm_source=Unknown+List&utm_campaign=0871c1013f-EMAIL_CAMPAIGN_2019_06_18_11_19&utm_medium=email&utm_term=0_-0871c1013f-' target='_blank' >Termes legals</a>.";
    //     $message .= "<br><br>";
    //     //$host = $_SERVER['HTTP_HOST'];
    //     $this->load->library('email');
    //     // if ($adjunto_1 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_1);
    //     if ($adjunto_1 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_1, 'attachment', 'Adjunt 1');
    //     if ($adjunto_2 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_2, 'attachment', 'Adjunt 2');
    //     if ($adjunto_3 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_3, 'attachment', 'Adjunt 3');

    //     $contador = 1;
    //     $enviados = array();
    //     $noEnviadosYahoo = array();
    //     // $contadorNo=1;
    //     // $noEnviados=array();

    //     $hoy = date('Y-m-d H:i');
    //     if ($primero == 0) {
    //         $this->db->query("INSERT INTO casal_emails SET titulo='$subject', mensaje='$mensaje', fecha='$hoy', adjunto_1='$adjunto_1', adjunto_2='$adjunto_2',  adjunto_3='$adjunto_3', grupo='$grupo'");
    //     }
    //     $id = $this->db->query("SELECT id FROM casal_emails ORDER BY id DESC LIMIT 1")->row()->id;

    //     // propuesta bcc
    //     $this->email->from(getCorreoServidorCasal(), getTituloCasal());
    //     $this->email->subject($subject);
    //     $this->email->message($message);
    //     // $this->email->bcc($to);
    //     $noEnviadosYahoo=[];
    //     $enviados=[];
    //     foreach (explode(',', $to) as $k => $v) {
    //         if (!filter_var($v, FILTER_VALIDATE_EMAIL)) continue;
    //         if (strpos($v, "yahoo")) {
    //             $noEnviadosYahoo[] = $v;
    //             continue;
    //         }
    //         $enviados[]=$v;
    //         $result = $this->db->query("SELECT nombre,apellidos FROM casal_socios_nuevo WHERE email='$v'")->result();
    //         $nombres = array();
    //         foreach ($result as $k1 => $v1) {
    //             $nombres[] = $v1->nombre . ' ' . $v1->apellidos;
    //         }
    //         $nombre = $v;
    //         if (count($nombres)) { $nombre = implode(' i ', $nombres);}
    //         $enviados[] = $v . ' (' . $nombre . ')';
    //     }

    //     // $resultadoEnvio = $this->email->send();
    //     // mensaje('resultadoEnvio BCC ' . $resultadoEnvio);


    //     // echo json_encode(array('emailNoEnviadosYahoo' => '', 'enviados' => '', 'contador' => $contador - 1, 'finalizado' => $finalizado));
    //     // return;


    //     foreach (explode(',', $to) as $k => $v) {
    //         if (!filter_var($v, FILTER_VALIDATE_EMAIL)) continue;
    //         if (strpos($v, "yahoo")) {
    //             $noEnviadosYahoo[] = $v;
    //             continue;
    //         }

    //         $result = $this->db->query("SELECT nombre,apellidos FROM casal_socios_nuevo WHERE email='$v'")->result();
    //         $nombres = array();
    //         foreach ($result as $k1 => $v1) {
    //             $nombres[] = $v1->nombre . ' ' . $v1->apellidos;
    //         }
    //         $nombre = $v;
    //         if (count($nombres))
    //             $nombre = implode(' i ', $nombres);
    //         // mensaje($nombre);
    //         // $this->email->from('mbanolas@gmail.com', getTituloCasal());
    //         $this->email->from(getCorreoServidorCasal(), getTituloCasal());
    //         // $this->email->from('info@olivaret.com', getTituloCasal());

    //         // $this->email->subject($subject.' - '.$contador);
    //         // mensaje($nombre.$subject);
    //         $this->email->subject($subject);
    //         $this->email->message($message);
    //         // $this->email->reply_to(getEmailCasal());
    //         // $this->email->bcc(getEmailCasal());
    //         if ($v == getEmailCasal()) {
    //             $this->email->bcc('mbanolas@gmail.com');
    //         }

    //         // mensaje($nombre.'<'.$v.'>');
    //         // mensaje($v);

    //         $this->email->to($nombre . ' <' . $v . '>');
    //         // quitar comentario para enviar el email.
    //         $resultadoEnvio = $this->email->send();
    //         mensaje('resultadoEnvio ' . $resultadoEnvio);
    //         if ($resultadoEnvio) {
    //             // if(true){
    //             $enviados[] = $v . ' (' . $nombre . ')';
    //             $contador++;
    //         }
    //         // mensaje('después de enviar '.$v.' '.$contador);
    //         sleep(1);
    //     }



    //     // $para=implode ( ', ' , $enviados );
    //     // $this->db->query("UPDATE casal_emails SET para='$para' WHERE id='$id'");
    //     $emailEnviados = "<p>";
    //     $emailEnviados = "";
    //     foreach ($enviados as $k) {
    //         $emailEnviados .= $k . ", ";
    //     }
    //     // $emailEnviados .= "</p>";

    //     $emailNoEnviadosYahoo = "";
    //     if (count($noEnviadosYahoo) > 0) {
    //         $emailNoEnviadosYahoo = "<span class='rojo_negrita'><hr><p>Emails de Yahoo que NO HAN ESTAT INCLOSOS (Perquè el programa no ho permet):</p></span><span class='rojo'><p>";
    //         foreach ($noEnviadosYahoo as $k) {
    //             $emailNoEnviadosYahoo .= $k . ", ";
    //         }
    //         $emailNoEnviadosYahoo .= "</p><hr></span>";
    //     }
    //     // mensaje('implode enviados '.implode(", ", $enviados));
    //     $para = $this->db->query("SELECT para FROM casal_emails WHERE id='$id'")->row()->para;
    //     // mensaje('para '.$para);
    //     // $para .= implode(", ", $enviados);
    //     if (trim($para)) $para .= ", ";
    //     $para .= implode(", ", $enviados);
    //     // mensaje('para despues de añadir implode emvaidos '.$para);
    //     // $emailNoEnviados.="</p>";
    //     // $envioError=$this->db->query("SELECT envio_error FROM casal_emails WHERE id='$id'")->row()->envio_error;
    //     // $envioError.=implode (", ", $noEnviados);


    //     $this->db->query("UPDATE casal_emails SET para='$para' WHERE id='$id'");

    //     echo json_encode(array('emailNoEnviadosYahoo' => $emailNoEnviadosYahoo, 'enviados' => $emailEnviados, 'contador' => $contador - 1, 'finalizado' => $finalizado));
    // }
*/

    


    function enviarEmails_2() //$to = 'mbanolas@gmail.com', $subject = "Sense títol", $message = "Sense missatge", $adjunto_1="", $adjunto_2="",$adjunto_3="")
    {
        
        $this->load->library('email');

        $primero = $_POST['primero'];
        $ultimo = $_POST['ultimo'];
        $para = $_POST['para'];
        $bloques = $_POST['bloques'];
        $para=str_replace(" ","",$para);

        $grupo = $_POST['grupo'];
        $noValidos=[];
        $paraArray=explode(",",$para);
        $paraTramo=[];
        for($i=$primero;$i<=$ultimo;$i++){
            if (!filter_var($paraArray[$i], FILTER_VALIDATE_EMAIL)) {
                $noValidos[]=$paraArray[$i];
                continue;
            }
            $paraTramo[]=$paraArray[$i];
        }
        
        $para=implode(", ",$paraTramo);

        $finalizado = false;

        mensaje($primero);
        mensaje($ultimo);
        mensaje($para);


        $subject = str_replace("'", "´", $_POST['titulo']);
        $mensaje = str_replace("'", "´", $_POST['mensaje']);

        // mensaje('to '.$to);
        $adjunto_1 = $_POST['adjunto_1'];
        $adjunto_2 = $_POST['adjunto_2'];
        $adjunto_3 = $_POST['adjunto_3'];

        $message=$mensaje;
        $message .= "<p style='box-sizing: border-box; margin: 0px 0px 10px; color: rgb(33, 33, 33); font-family: 'Segoe UI', 'egoe WP', 'Segoe UI WPC', Tahoma, Arial, sans-serif; font-size: 2px;'>" . "Per qualsevol dubte, aclariment o per no rebre més informació sobre les activitats del Casal de Gent Gran" . getTituloCasal() . ", podeu enviar-nos un correu electrònic a " . getEmailCasal();
        $message .= "<p style='box-sizing: border-box; margin: 0px 0px 10px; color: rgb(33, 33, 33); font-family: 'Segoe UI', 'egoe WP', 'Segoe UI WPC', Tahoma, Arial, sans-serif; font-size: 2px;'>" . "** AVÍS **<br>
        Aquest correu electrònic és confidencial i per a ús exclusiu del seu destinatari. Si vostè ha rebut aquest correu per error, li agrairem que ens ho comuniqui, abstenir-se de difondre’l, distribuir-lo, copiar-lo o emmagatzemar-lo. La seva adreça de correu electrònic juntament amb la informació que ens faciliti són tractades per INCOOP, en qualitat de responsable del tractament, amb la finalitat de gestionar i mantenir els contactes i relacions que es produeixin com a conseqüència de la relació que manté amb nosaltres. La base jurídica que legitima aquest tractament serà el seu consentiment, l'interès legítim o la necessitat per gestionar una relació contractual o similar. El termini de conservació de les seves dades vindrà determinat per la relació que manté amb nosaltres.";
        $message .= "<br>" . "Per a més informació al respecte, o per exercir els seus drets d'accés, rectificació, supressió, limitació del tractament, oposició i portabilitat pot enviar una notificació per escrit, adjuntant còpia d'un document que acrediti la seva identitat, a la següent adreça: C. Mallorca, 53 (Local 5) 08029 Barcelona o per correu electrònic a través de proteccio.dades@incoop.cat . En el cas que no hagi obtingut satisfacció en l'exercici dels seus drets pot presentar una reclamació davant l'Agència Espanyola de Protecció de Dades.
        Si desitja obtenir més informació sobre el tractament de les seves dades que duem a terme, consulti els nostres <a href='https://incoop.cat/politica-privacitat/?utm_source=Unknown+List&utm_campaign=0871c1013f-EMAIL_CAMPAIGN_2019_06_18_11_19&utm_medium=email&utm_term=0_-0871c1013f-' target='_blank' >Termes legals</a>.";
        $message .= "<br><br>";
        //$host = $_SERVER['HTTP_HOST'];
        // $this->load->library('email');
        // if ($adjunto_1 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_1);
        if ($adjunto_1 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_1, 'attachment', 'Adjunt 1');
        if ($adjunto_2 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_2, 'attachment', 'Adjunt 2');
        if ($adjunto_3 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_3, 'attachment', 'Adjunt 3');

        $this->email->from(getCorreoServidorCasal(), getTituloCasal());
        $this->email->subject($subject);
        $this->email->message($message);
       
        $this->email->bcc($para);
        mensaje("emails que se van a enviar con send() ".$para.'<br>'.count(explode(", ",$para)));

        // $resultadoEnvio=1;
        // activa lines siguiente para enviar
        $resultadoEnvio = $this->email->send();
            
        mensaje('resultadoEnvio después de enviarlo'.$resultadoEnvio);
            
        // $resultadoEnvio=false;
        // if($resultadoEnvio){
        switch ($_POST['grupo']) {
            case 'option2':
                $taller = $this->db->query("SELECT datos FROM casal_provisional LIMIT 1")->row()->datos;
                $grupo = "Taller: " . $taller;
                break;
            case 'option3':
                $grupo = "Usuaris / usuàries de tots els tallers";
                break;
            case 'option4':
                $grupo = "Usuaris / usuàries de tots els tallers VOLUNTARIS ";
                break;
            case 'option5':
                $grupo = "Usuaris / usuàries de tots els tallers PROFESSIONALS ";
                break;
            case 'option6':
                $grupo = "Altres ";
                break;
            case 'option10':
                $grupo = "Tots els usuaris / usuàries casal ";
                break;
            default:
                $grupo = "------------";
        }
        
        $hoy = date('Y-m-d H:i');
        $textoResultado=($primero+1)."-".($ultimo+1);
        $resultado="";
        if($resultadoEnvio){
            $resultado=$textoResultado;
        }else{
            $resultado="Error ".$textoResultado;
        }
        // if ($primero == 0) {
        $this->db->query("INSERT INTO casal_emails SET bloques='$bloques', para='$para',resultado='$resultado', titulo='$subject', mensaje='$mensaje', fecha='$hoy', adjunto_1='$adjunto_1', adjunto_2='$adjunto_2',  adjunto_3='$adjunto_3', grupo='$grupo'");
        // }
        // $id = $this->db->query("SELECT id FROM casal_emails ORDER BY id DESC LIMIT 1")->row()->id;


        // $paraEnviados = $this->db->query("SELECT para FROM casal_emails WHERE id='$id'")->row()->para;
        
        // if (trim($para)) $paraEnviados .= $para.", ";
       
        // $this->db->query("UPDATE casal_emails SET para='$paraEnviados' WHERE id='$id'");
        echo json_encode(array('resultadoEnvio'=>$resultadoEnvio,'enviados'=>$para,'noValidos'=>implode(",",$noValidos)));
    // }else{
        // echo json_encode(array('resultadoEnvio'=>$resultado,'enviados'=>$para,'noValidos'=>implode(",",$noValidos)));
    // }

    // }
}


    function sendEmail($to = 'mbanolas@gmail.com', $subject = "Sense títol", $message = "Sense missatge", $adjunto_1 = "", $adjunto_2 = "", $adjunto_3 = "")
    {
        $message .= "<p>" . "Per qualsevol dubte, aclariment o per no rebre més informació sobre les activitats del Casal de Gent Gran" . getTituloCasal() . ", podeu enviar-nos un correu electrònic a " . getEmailCasal();
        $message .= "<p>" . "** AVÍS **<br>
        Aquest correu electrònic és confidencial i per a ús exclusiu del seu destinatari. Si vostè ha rebut aquest correu per error, li agrairem que ens ho comuniqui, abstenir-se de difondre’l, distribuir-lo, copiar-lo o emmagatzemar-lo. La seva adreça de correu electrònic juntament amb la informació que ens faciliti són tractades per INCOOP, en qualitat de responsable del tractament, amb la finalitat de gestionar i mantenir els contactes i relacions que es produeixin com a conseqüència de la relació que manté amb nosaltres. La base jurídica que legitima aquest tractament serà el seu consentiment, l'interès legítim o la necessitat per gestionar una relació contractual o similar. El termini de conservació de les seves dades vindrà determinat per la relació que manté amb nosaltres.";
        $message .= "<br>" . "Per a més informació al respecte, o per exercir els seus drets d'accés, rectificació, supressió, limitació del tractament, oposició i portabilitat pot enviar una notificació per escrit, adjuntant còpia d'un document que acrediti la seva identitat, a la següent adreça: C. Mallorca, 53 (Local 5) 08029 Barcelona o per correu electrònic a través de proteccio.dades@incoop.cat . En el cas que no hagi obtingut satisfacció en l'exercici dels seus drets pot presentar una reclamació davant l'Agència Espanyola de Protecció de Dades.
        Si desitja obtenir més informació sobre el tractament de les seves dades que duem a terme, consulti els nostres <a href='https://incoop.cat/politica-privacitat/?utm_source=Unknown+List&utm_campaign=0871c1013f-EMAIL_CAMPAIGN_2019_06_18_11_19&utm_medium=email&utm_term=0_-0871c1013f-' target='_blank' >Termes legals</a>.<br><br>";

        //$host = $_SERVER['HTTP_HOST'];
        $this->load->library('email');
        $contador = 1;
        foreach (explode(',', $to) as $k => $v) {
            // $this->email->from('mbanolas@gmail.com', getTituloCasal());
            $this->email->from('info@gestiocggsantmarti.com', getTituloCasal());
            // $this->email->from('info@olivaret.com', getTituloCasal());

            $this->email->subject($subject . ' - ' . $contador);
            $this->email->message($message);
            $this->email->reply_to(getEmailCasal());
            // $this->email->bcc(getEmailCasal());
            // $this->email->bcc('mbanolas@gmail.com');

            if ($adjunto_1 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_1);
            if ($adjunto_2 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_2);
            if ($adjunto_3 != "") $this->email->attach(base_url() . 'assets/uploads/files/' . $adjunto_3);

            $this->email->to($v);
            // mensaje('antes enviar '.$v);
            mensaje($this->email->send());
            mensaje('después de enviar ' . $v . ' ' . $contador);
            $contador++;
            sleep(1);
        }
    }

    public function casal_socios_aniversarios()
    {
        $sql = "SELECT id,fecha_nacimiento FROM casal_socios_nuevo WHERE 1";
        foreach ($this->db->query($sql)->result() as $k => $v) {
            $fecha_nacimiento = $v->fecha_nacimiento;
            $id = $v->id;
            $edad = date_diff(date_create($v->fecha_nacimiento), date_create('today'))->format('%y');
            $proximo = $edad + 1;
            $next = date('Y-m-d', strtotime("+$proximo years", strtotime($fecha_nacimiento)));
            $faltan = floor((strtotime($next) - time('Y-m-d')) / (60 * 60 * 24)) + 1;
            $next = $this->db->query("UPDATE casal_socios_nuevo SET edad='$edad', dias_next_aniversario='$faltan' WHERE id='$id'");
        }



        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_socios_nuevo');

        $crud->display_as('id', 'Núm');
        $crud->display_as('num_socio', 'Núm');
        $crud->display_as('direccion', 'Direcció');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('apellidos', 'Cognoms');
        $crud->display_as('sexo', 'Sexe');
        $crud->display_as('poblacion', 'Població');
        $crud->display_as('codigo_postal', 'Codi Postal');
        $crud->display_as('dni', 'DNI/NIE/PASP');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');
        $crud->display_as('fecha_nacimiento_local', 'Data naixement');
        $crud->display_as('dias_next_aniversario', 'Dies que falten per a nou Aniversari');
        $crud->unset_delete();
        $crud->unset_add();
        $crud->unset_edit();

        $crud->add_action('Pdf', '', '', 'ui-icon-image', array($this, 'pdf_aniversario'));

        $crud->columns('id', 'nombre', 'apellidos', 'sexo', 'telefono_1', 'telefono_2', 'fecha_nacimiento_local', 'edad', 'dias_next_aniversario');

        $noUsados = array('id_agrupacion', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'dni', 'fecha_alta', 'fecha_baja', 'comentarios');
        $crud->unset_columns($noUsados);

        $crud->unset_add_fields('fecha_nacimiento_local');
        $crud->unset_fields('fecha_nacimiento_local');

        $crud->set_subject('Socis/Sòcies');
        $crud->callback_column('telefono_1', array($this, '_telefono'));
        $crud->callback_column('telefono_2', array($this, '_telefono'));
        $crud->callback_column('fecha_nacimiento_local', array($this, '_fecha_nacimiento_local'));

        $crud->callback_add_field('num_socio', function () {
            $sql = "SELECT id FROM casal_socios_nuevo ORDER BY id DESC LIMIT 1";
            $num_socio = 1 + $this->db->query($sql)->row()->id;
            //return '<input type="text" maxlength="50" value="'.$num_socio.'" name="num_socio">';
            return "<label  class='control-label'>$num_socio</label>";
        });

        $crud->callback_add_field('poblacion', function () {
            return '<input id="field-poblacion" class="form-control" name="poblacion" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('provincia', function () {
            return '<input id="field-provincia" class="form-control" name="provincia" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->required_fields('sexo', 'nombre', 'apellidos', 'dni', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'fecha_nacimiento', 'fecha_alta');

        $crud->callback_after_insert(array($this, '_fecha_alta_socios'));
        $crud->callback_after_update(array($this, '_fecha_modificacion_socios'));

        $crud->set_rules('dni', 'DNI', 'callback_validar_dni');
        $crud->set_rules('codigo_postal', 'Código Postal', 'callback_validar_codigo_postal');

        $output = $crud->render();
        $this->_template_output_aniversarios($output, 'Socis/Sòcies');
    }

    function just_a_test($primary_key, $row)
    {
        return site_url('reporte/pdfSocio/' . $primary_key);
    }

    function pdf_aniversario($primary_key, $row)
    {
        return site_url('reporte/pdfSocioAniversario/' . $primary_key);
    }

    public function casal_socios_completa()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_socios_nuevo');

        $crud->display_as('id', 'Núm');
        $crud->display_as('num_socio', 'Núm');
        $crud->display_as('direccion', 'Direcció');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('apellidos', 'Cognoms');
        $crud->display_as('sexo', 'Sexe');
        $crud->display_as('poblacion', 'Població');
        $crud->display_as('codigo_postal', 'Codi Postal');
        $crud->display_as('dni', 'DNI/NIE/PASP');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');


        // $crud->columns('id','nombre','apellidos','telefono_1','telefono_2');

        $noUsados = array('id_agrupacion', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'dni', 'fecha_nacimiento', 'fecha_alta', 'fecha_baja', 'comentarios');
        // $crud->unset_columns($noUsados);

        $crud->set_subject('Socis/Sòcies');
        $crud->callback_column('telefono_1', array($this, '_telefono'));
        $crud->callback_column('telefono_2', array($this, '_telefono'));

        $crud->callback_add_field('num_socio', function () {
            $sql = "SELECT id FROM casal_socios_nuevo ORDER BY id DESC LIMIT 1";
            $num_socio = 1 + $this->db->query($sql)->row()->id;
            //return '<input type="text" maxlength="50" value="'.$num_socio.'" name="num_socio">';
            return "<label  class='control-label'>$num_socio</label>";
        });

        $crud->callback_add_field('poblacion', function () {
            return '<input id="field-poblacion" class="form-control" name="poblacion" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('provincia', function () {
            return '<input id="field-provincia" class="form-control" name="provincia" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->required_fields('nombre', 'apellidos', 'dni', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'fecha_nacimiento', 'fecha_alta');

        $crud->callback_after_insert(array($this, '_fecha_alta_socios'));
        $crud->callback_after_update(array($this, '_fecha_modificacion_socios'));

        $crud->set_rules('dni', 'DNI', 'callback_validar_dni');
        $crud->set_rules('codigo_postal', 'Código Postal', 'callback_validar_codigo_postal');

        $output = $crud->render();
        $this->_example_output($output, 'Socios');
    }

    public function casal_profesores()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");

        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_profesores');

        $crud->display_as('id', 'Núm. professor/a');
        $crud->display_as('direccion', 'Direcció');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('apellidos', 'Cognoms');
        $crud->display_as('poblacion', 'Població');
        $crud->display_as('codigo_postal', 'Codi Postal');
        $crud->display_as('dni', 'DNI/NIE/PASP');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');
        $crud->display_as('fecha_nacimiento', 'Data naixement');
        $crud->display_as('fecha_modificacion', 'Data modificació');
        $crud->display_as('fecha_baja', 'Data baixa');
        $crud->display_as('comentarios', 'Comentaris');

        $crud->columns('id', 'nombre', 'apellidos', 'telefono_1', 'telefono_2');
        $noUsados = array('direccion', 'poblacion', 'provincia', 'codigo_postal', 'dni', 'fecha_nacimiento', 'fecha_alta', 'fecha_baja', 'comentarios');
        $crud->unset_columns($noUsados);
        $crud->set_subject('Professors/Professores');




        $crud->callback_add_field('poblacion', function () {
            return '<input id="field-poblacion" class="form-control" name="poblacion" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('provincia', function () {
            return '<input id="field-provincia" class="form-control" name="provincia" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->required_fields('nombre', 'apellidos',  'fecha_alta');
        //$crud->set_rules('dni', 'DNI', 'callback_validar_dni');
        //$crud->set_rules('codigo_postal', 'Código Postal', 'callback_validar_codigo_postal');

        $crud->callback_column('telefono_1', array($this, '_telefono'));
        $crud->callback_column('telefono_2', array($this, '_telefono'));

        $crud->unset_fields('num_socio', 'fecha_alta', 'nombre_apellidos');

        $crud->callback_before_insert(array($this, '_prepararTextoProfesores'));
        $crud->callback_before_update(array($this, '_prepararTextoProfesores'));

        $crud->callback_after_insert(array($this, '_fecha_alta_profesores'));
        $crud->callback_after_update(array($this, '_fecha_modificacion_profesores'));


        $output = $crud->render();
        $this->_example_output($output, 'Professors/Professores');
    }

    public function casal_colaboradores()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        $crud->set_language("spanish");
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_colaboradores');

        $crud->display_as('id', 'Núm');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('apellidos', 'Cognoms');
        $crud->display_as('direccion', 'Direcció');
        $crud->display_as('poblacion', 'Població');
        $crud->display_as('codigo_postal', 'Codi Postal');
        $crud->display_as('dni', 'DNI/NIE/PASP');
        $crud->display_as('telefono_1', 'Telèfon 1');
        $crud->display_as('telefono_2', 'Telèfon 2');

        $crud->columns('id', 'nombre', 'apellidos', 'telefono_1', 'telefono_2');

        $noUsados = array('direccion', 'poblacion', 'provincia', 'codigo_postal', 'dni', 'fecha_nacimiento', 'fecha_alta', 'fecha_baja', 'comentarios');
        $crud->unset_columns($noUsados);
        $crud->set_subject('Col·laboradors');

        $crud->callback_add_field('poblacion', function () {
            return '<input id="field-poblacion" class="form-control" name="poblacion" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->callback_add_field('provincia', function () {
            return '<input id="field-provincia" class="form-control" name="provincia" type="text" value="Barcelona" maxlength="10">';
        });

        $crud->required_fields('nombre', 'apellidos', 'dni', 'direccion', 'poblacion', 'provincia', 'codigo_postal', 'fecha_nacimiento', 'fecha_alta');

        $crud->callback_column('telefono_1', array($this, '_telefono'));
        $crud->callback_column('telefono_2', array($this, '_telefono'));

        $crud->unset_fields('num_socio', 'fecha_alta');
        $crud->callback_after_insert(array($this, '_fecha_alta_colaboradores'));
        $crud->callback_after_update(array($this, '_fecha_modificacion_colaboradores'));


        $output = $crud->render();
        $this->_example_output($output, 'col·laboradors/col·laboradores');
    }

    public function casal_talleres()
    {
        //si se han incrementado num_asistentes, pero no utilizado, restituimos el incremento
        $sql = "SELECT id_taller FROM casal_talleres_incrementados ";
        if ($this->db->query($sql)->num_rows() > 0) {
            $result = $this->db->query($sql)->result();
            foreach ($result as $k => $v) {
                $id = $v->id_taller;
                $sql = "UPDATE casal_talleres SET num_maximo=num_maximo-1 WHERE id='$id'";
                $this->db->query($sql);
            }
            $sql = "DELETE FROM casal_talleres_incrementados WHERE 1";
            $this->db->query($sql);
        }


        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_talleres');
        // $crud->order_by('nombre', 'asc');
        // $crud->where('id_curso', '3');

        $crud->unset_fields('id_agrupacion');



        $crud->columns('id_curso', 'nombre', 'id_profesor');


        $crud->display_as('nombre', 'Nom del taller');
        $crud->display_as('nombre_corto', 'Nom curt del taller');
        $crud->display_as('id_profesor', 'Professor/a');
        $crud->set_relation('id_profesor', 'casal_profesores', 'nombre_apellidos');
        $crud->display_as('id_curso', 'Curs');
        $crud->set_relation('id_curso', 'casal_cursos', 'nombre');
        $crud->set_relation('id_periodo', 'casal_periodos', 'nombre_periodo');
        $crud->display_as('id_dia_semana_1', 'Dia 1');
        $crud->display_as('id_dia_semana_2', 'Dia 2');
        $crud->set_relation('id_dia_semana_1', 'casal_dias_semana', '{nombre_castellano}', null, 'id ASC');
        $crud->set_relation('id_dia_semana_2', 'casal_dias_semana', '{nombre_castellano}', null, 'id ASC');
        $crud->display_as('id_espacio_1', 'Espai 1');
        $crud->set_relation('id_espacio_1', 'casal_espacios', '{nombre}', null, 'nombre ASC');
        $crud->display_as('id_espacio_2', 'Espai 2');
        $crud->set_relation('id_espacio_2', 'casal_espacios', '{nombre}', null, 'nombre ASC');
        $crud->display_as('precio_trimestre', 'Preu Trimestre (€)');
        $crud->display_as('precio_curso', 'Preu Curs (€)');
        $crud->display_as('id_actividad', 'Tipus Activitad');
        $crud->set_relation('id_actividad', 'casal_tipos_actividad', '{nombre}', array('status_actividad' => '1'), 'nombre ASC');
        $crud->display_as('num_maximo', 'Núm. Màxim assistents');
        $crud->display_as('num_reservas', 'Núm. Màxim en llista espera');
        $crud->display_as('fecha_inicio', 'Data inici taller');
        $crud->display_as('fecha_final', 'Data final taller');
        $crud->display_as('inicio_1', 'Des 1');
        $crud->display_as('final_1', 'Fins 1');
        $crud->display_as('inicio_2', 'Des 2');
        $crud->display_as('final_2', 'Fins 2');
        $crud->display_as('id_periodo', 'Període del taller');
        $crud->display_as('horas_taller_T1_mostrar', 'Hores Taller T1');
        $crud->display_as('horas_taller_T2_mostrar', 'Hores Taller T2');
        $crud->display_as('horas_taller_T3_mostrar', 'Hores Taller T3');
        $crud->set_rules('horas_taller_T1_mostrar', 'Hores Taller T1', 'numeric');
        $crud->set_rules('horas_taller_T2_mostrar', 'Hores Taller T2', 'numeric');
        $crud->set_rules('horas_taller_T3_mostrar', 'Hores Taller T3', 'numeric');

        // $crud->unset_delete();
        $crud->unset_add_fields('fecha_inicio', 'fecha_final', 'horas_taller_T1', 'horas_taller_T2', 'horas_taller_T3');
        $crud->unset_fields('id_agrupacion', 'fecha_inicio', 'fecha_final', 'horas_taller_T1', 'horas_taller_T2', 'horas_taller_T3');



        //log_message('INFO', '======================='.$this->uri->segment(3));

        if ($this->uri->segment(3) == "edit") {
            $crud->required_fields('precio_rosa_curso', 'precio_curso', 'precio_rosa_trimestre', 'precio_trimestre',  'nombre', 'nombre_corto', 'id_profesor', 'id_dia_semana_1', 'id_espacio_1', 'inicio_1', 'final_1', 'horas_taller_T1_mostrar', 'horas_taller_T2_mostrar', 'horas_taller_T3_mostrar');
        } else {
            $crud->required_fields('tipo_taller', 'id_periodo', 'id_curso', 'precio_rosa_curso', 'precio_curso', 'precio_rosa_trimestre', 'precio_trimestre',  'nombre', 'nombre_corto', 'id_profesor', 'id_dia_semana_1', 'id_espacio_1', 'inicio_1', 'final_1', 'horas_taller_T1_mostrar', 'horas_taller_T2_mostrar', 'horas_taller_T3_mostrar');
        }

        $crud->set_lang_string('alert_delete', "Segur que vols esborrar aquest registre?<br><br><strong>Si el registre ja s'ha utilitzat, no s'esborrarà</strong>");
        // $crud->set_lang_string('alert_delete', "Segur que vols esborrar aquest registre?");        

        $crud->callback_before_delete(array($this, '_before_delete_taller'));
        // $crud->callback_delete(array($this, '_delete_taller'));
        $crud->callback_before_insert(array($this, '_prepararTexto'));
        $crud->callback_before_update(array($this, '_prepararTexto'));
        $crud->callback_after_insert(array($this, '_updateFields'));
        $crud->callback_after_update(array($this, '_updateFields'));


        // $crud->columns('id_curso','nombre','id_profesor');

        $crud->set_subject('Tallers (tots)');
        $state = $crud->getState();

        $output = $crud->render();

        if ($state == 'edit') {
            $js = '<script> var anyo=$("#field-id_curso").children("[selected]").html();
                $("#field-id_curso").parent().addClass("hidden");
                $("#field-id_curso").parent().before( "<div class=\'col-sm-1 literal\'>"+anyo+"</div>" );</script>';
            $output->output .= $js;
        }

        if ($state == 'edit') {
            $js2 = '<script> var tipo=$("#field-tipo_taller").children("[selected]").html();
                $("#field-tipo_taller").parent().addClass("hidden");
                $("#field-tipo_taller").parent().before( "<div class=\'col-sm-1 literal\'>"+tipo+"</div>" );</script>';
            $output->output .= $js2;
        }

        if ($state == 'edit') {
            $js3 = '<script> var periodo=$("#field-id_periodo").children("[selected]").html();
                $("#field-id_periodo").parent().addClass("hidden");
                $("#field-id_periodo").parent().before( "<div class=\'col-sm-2 literal\'>"+periodo+"</div>" );</script>';
            $output->output .= $js3;
        }

        $this->_example_output_talleres($output, 'Tallers (tots');
    }

    public function casal_talleres_curso_actual()
    {
        //si se han incrementado num_asistentes, pero no utilizado, restituimos el incremento
        $sql = "SELECT id_taller FROM casal_talleres_incrementados ";
        if ($this->db->query($sql)->num_rows() > 0) {
            $result = $this->db->query($sql)->result();
            foreach ($result as $k => $v) {
                $id = $v->id_taller;
                $sql = "UPDATE casal_talleres SET num_maximo=num_maximo-1 WHERE id='$id'";
                $this->db->query($sql);
            }
            $sql = "DELETE FROM casal_talleres_incrementados WHERE 1";
            $this->db->query($sql);
        }


        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_talleres');
        $crud->order_by('nombre', 'asc');

        $this->load->model('talleres_model');
        $idCursoActual = $this->talleres_model->getIdCursoActual();
        $crud->where('id_curso', $idCursoActual);

        $crud->unset_fields('id_agrupacion');



        $crud->columns('id_curso', 'nombre', 'id_profesor');



        $crud->display_as('nombre', 'Nom del taller');
        $crud->display_as('nombre_corto', 'Nom curt del taller');
        $crud->display_as('id_profesor', 'Professor/a');
        $crud->set_relation('id_profesor', 'casal_profesores', 'nombre_apellidos');
        $crud->display_as('id_curso', 'Curs');
        $crud->set_relation('id_curso', 'casal_cursos', 'nombre');
        $crud->set_relation('id_periodo', 'casal_periodos', 'nombre_periodo');
        $crud->display_as('id_dia_semana_1', 'Dia 1');
        $crud->display_as('id_dia_semana_2', 'Dia 2');
        $crud->set_relation('id_dia_semana_1', 'casal_dias_semana', '{nombre_castellano}', null, 'id ASC');
        $crud->set_relation('id_dia_semana_2', 'casal_dias_semana', '{nombre_castellano}', null, 'id ASC');
        $crud->display_as('id_espacio_1', 'Espai 1');
        $crud->set_relation('id_espacio_1', 'casal_espacios', '{nombre}', null, 'nombre ASC');
        $crud->display_as('id_espacio_2', 'Espai 2');
        $crud->set_relation('id_espacio_2', 'casal_espacios', '{nombre}', null, 'nombre ASC');
        $crud->display_as('precio_trimestre', 'Preu Trimestre (€)');
        $crud->display_as('precio_curso', 'Preu Curs (€)');
        $crud->display_as('id_actividad', 'Tipus Activitad');
        $crud->set_relation('id_actividad', 'casal_tipos_actividad', '{nombre}', array('status_actividad' => '1'), 'nombre ASC');
        $crud->display_as('num_maximo', 'Núm. Màxim assistents');
        $crud->display_as('num_reservas', 'Núm. Màxim en llista espera');
        $crud->display_as('fecha_inicio', 'Data inici taller');
        $crud->display_as('fecha_final', 'Data final taller');
        $crud->display_as('inicio_1', 'Des 1');
        $crud->display_as('final_1', 'Fins 1');
        $crud->display_as('inicio_2', 'Des 2');
        $crud->display_as('final_2', 'Fins 2');
        $crud->display_as('id_periodo', 'Període del taller');
        $crud->display_as('horas_taller_T1_mostrar', 'Hores Taller T1');
        $crud->display_as('horas_taller_T2_mostrar', 'Hores Taller T2');
        $crud->display_as('horas_taller_T3_mostrar', 'Hores Taller T3');
        $crud->set_rules('horas_taller_T1_mostrar', 'Hores Taller T1', 'numeric');
        $crud->set_rules('horas_taller_T2_mostrar', 'Hores Taller T2', 'numeric');
        $crud->set_rules('horas_taller_T3_mostrar', 'Hores Taller T3', 'numeric');

        // $crud->unset_delete();
        $crud->set_lang_string('alert_delete', "Segur que vols esborrar aquest registre?<br><br><strong>Si el registre ja s'ha utilitzat, no s'esborrarà</strong>");
        // $crud->set_lang_string('alert_delete', "Segur que vols esborrar aquest registre?");        

        $crud->callback_before_delete(array($this, '_before_delete_taller'));

        $crud->unset_add_fields('fecha_inicio', 'fecha_final', 'horas_taller_T1', 'horas_taller_T2', 'horas_taller_T3');
        $crud->unset_fields('id_agrupacion', 'fecha_inicio', 'fecha_final', 'horas_taller_T1', 'horas_taller_T2', 'horas_taller_T3');

        //log_message('INFO', '======================='.$this->uri->segment(3));

        if ($this->uri->segment(3) == "edit") {
            $crud->required_fields('precio_rosa_curso', 'precio_curso', 'precio_rosa_trimestre', 'precio_trimestre',  'nombre', 'nombre_corto', 'id_profesor', 'id_dia_semana_1', 'id_espacio_1', 'inicio_1', 'final_1', 'horas_taller_T1_mostrar', 'horas_taller_T2_mostrar', 'horas_taller_T3_mostrar');
        } else {
            $crud->required_fields('tipo_taller', 'id_periodo', 'id_curso', 'precio_rosa_curso', 'precio_curso', 'precio_rosa_trimestre', 'precio_trimestre',  'nombre', 'nombre_corto', 'id_profesor', 'id_dia_semana_1', 'id_espacio_1', 'inicio_1', 'final_1', 'horas_taller_T1_mostrar', 'horas_taller_T2_mostrar', 'horas_taller_T3_mostrar');
        }

        // $crud->callback_delete(array($this, '_delete'));
        $crud->callback_before_insert(array($this, '_prepararTexto'));
        $crud->callback_before_update(array($this, '_prepararTexto'));
        $crud->callback_after_insert(array($this, '_updateFields'));
        $crud->callback_after_update(array($this, '_updateFields'));
        // $crud->columns('id_curso','nombre','id_profesor');

        $crud->set_subject('Tallers curs actual');
        $state = $crud->getState();

        $output = $crud->render();

        if ($state == 'edit') {
            $js = '<script> var anyo=$("#field-id_curso").children("[selected]").html();
                $("#field-id_curso").parent().addClass("hidden");
                $("#field-id_curso").parent().before( "<div class=\'col-sm-1 literal\'>"+anyo+"</div>" );</script>';
            $output->output .= $js;
        }

        if ($state == 'edit') {
            $js2 = '<script> var tipo=$("#field-tipo_taller").children("[selected]").html();
                $("#field-tipo_taller").parent().addClass("hidden");
                $("#field-tipo_taller").parent().before( "<div class=\'col-sm-1 literal\'>"+tipo+"</div>" );</script>';
            $output->output .= $js2;
        }

        if ($state == 'edit') {
            $js3 = '<script> var periodo=$("#field-id_periodo").children("[selected]").html();
                $("#field-id_periodo").parent().addClass("hidden");
                $("#field-id_periodo").parent().before( "<div class=\'col-sm-2 literal\'>"+periodo+"</div>" );</script>';
            $output->output .= $js3;
        }

        $this->_example_output_talleres($output, 'Tallers curs actual');
    }

    function _updateFields($post_array, $primary_key)
    {
        $valores = array(
            'horas_taller_T1' => floatval($post_array['horas_taller_T1_mostrar']) * 100,
            'horas_taller_T2' => floatval($post_array['horas_taller_T2_mostrar']) * 100,
            'horas_taller_T3' => floatval($post_array['horas_taller_T3_mostrar']) * 100,
        );
        $this->db->update('casal_talleres', $valores, array('id' => $primary_key));
    }

    function _prepararTexto($post_array)
    {
        $post_array['nombre'] = str_replace('"', "`", $post_array['nombre']);
        $post_array['nombre'] = str_replace("'", "`", $post_array['nombre']);
        $post_array['nombre'] = mb_convert_case($post_array['nombre'], MB_CASE_TITLE, "UTF-8");
        $post_array['nombre_corto'] = str_replace('"', "`", $post_array['nombre_corto']);
        $post_array['nombre_corto'] = str_replace("'", "`", $post_array['nombre_corto']);
        $post_array['nombre_corto'] = mb_convert_case($post_array['nombre_corto'], MB_CASE_TITLE, "UTF-8");

        if ($post_array['tipo_taller'] == "Voluntari") {
            $post_array['precio_curso'] = 0;
            $post_array['precio_trimestre'] = 0;
            $post_array['precio_rosa_curso'] = 0;
            $post_array['precio_rosa_trimestre'] = 0;
        }
        //convertir , por . 
        $post_array['precio_trimestre'] = str_replace(',', ".", $post_array['precio_trimestre']);
        $post_array['precio_curso'] = str_replace(',', ".", $post_array['precio_curso']);
        $post_array['precio_rosa_trimestre'] = str_replace(',', ".", $post_array['precio_rosa_trimestre']);
        $post_array['precio_rosa_curso'] = str_replace(',', ".", $post_array['precio_rosa_curso']);

        
        $post_array['horas_taller_T1'] = floatval($post_array['horas_taller_T1_mostrar']) * 100;
        $post_array['horas_taller_T2'] = floatval($post_array['horas_taller_T2_mostrar']) * 100;
        $post_array['horas_taller_T3'] = floatval($post_array['horas_taller_T3_mostrar']) * 100;
        return $post_array;
    }

    function _prepararTextoProfesores($post_array)
    {
        $post_array['nombre'] = str_replace('"', "`", $post_array['nombre']);
        $post_array['nombre'] = str_replace("'", "`", $post_array['nombre']);
        $post_array['nombre'] = mb_convert_case($post_array['nombre'], MB_CASE_TITLE, "UTF-8");
        $post_array['apellidos'] = str_replace('"', "`", $post_array['apellidos']);
        $post_array['apellidos'] = str_replace("'", "`", $post_array['apellidos']);
        $post_array['apellidos'] = mb_convert_case($post_array['apellidos'], MB_CASE_TITLE, "UTF-8");
        return $post_array;
    }

    function _prepararTextoEspacios($post_array)
    {
        $post_array['nombre'] = str_replace('"', "'", $post_array['nombre']);
        $post_array['nombre'] = mb_convert_case($post_array['nombre'], MB_CASE_TITLE, "UTF-8");
        return $post_array;
    }





    public function casal_espacios()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_espacios');
        $crud->display_as('nombre', 'Nom');
        $crud->display_as('comentarios', 'Comentaris');

        $crud->callback_before_insert(array($this, '_prepararTextoEspacios'));
        $crud->callback_before_update(array($this, '_prepararTextoEspacios'));

        $crud->set_subject('Espais');

        $output = $crud->render();
        $this->_example_output($output, 'Espais');
    }

    public function casal_cursos()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_cursos');
        $crud->display_as('nombre', 'Nom del curs');

        $crud->unset_delete();


        $crud->set_subject('Curs');

        $output = $crud->render();
        $this->_example_output($output, 'Cursos');
    }


    public function casal_tipos_actividad()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');

        $crud->set_table('casal_tipos_actividad');
        $crud->where('status_actividad', '1');

        $crud->display_as('nombre', 'Activitat');
        $crud->unset_fields('status_actividad');
        $crud->unset_columns('status_actividad');
        $crud->callback_delete(array($this, 'delete_actividad'));

        $crud->set_subject('Activitats');

        $output = $crud->render();
        $this->_example_output($output, 'Activitats');
    }

    public function delete_actividad($primary_key)
    {
        return $this->db->update('casal_tipos_actividad', array('status_actividad' => '0'), array('id' => $primary_key));
    }

    public function casal_recibos()
    {
        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');
        //$crud->set_theme('flexigrid');
        // $crud->set_theme('datatables');
        $crud->set_table('casal_recibos');
        $crud->order_by('fecha', 'desc');
        $crud->display_as('id_socio', 'Usuari/Usuària');
        $crud->display_as('importe', 'Inport (€)');
        $crud->display_as('recibo', 'Nom arxiu rebut');
        $crud->display_as('fecha', 'Data');
        $crud->unset_edit();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->callback_column('importe', array($this, '_callback_importe'));
        // $crud->callback_column('recibo',array($this,'_callback_recibo'));
        $crud->columns('fecha', 'id_socio', 'importe', 'recibo');


        $crud->set_relation('id_socio', 'casal_socios_nuevo', '{apellidos}, {nombre}');
        $crud->set_subject('Rebuts');
        $crud->set_field_upload('recibo', 'recibos');

        $output = $crud->render();
        $this->_example_output($output, 'Recibos');
    }

    public function _before_delete($primary_key)
    {
        // $this->crud->set_lang_string('alert_delete', 'My message for delete on error'); 
        $this->db->where('id_taller', $primary_key);
        $asistentes = $this->db->get('casal_asistentes')->num_rows();
        mensaje('asistentes en _before_delete ' . $asistentes);
        if (!$asistentes) {
            $sql = "DELETE FROM casal_talleres WHERE id='$primary_key'";
            $this->db->query($sql);
            return true;
        } else {

            return false;
        }
    }

    public function _before_delete_taller($primary_key)
    {
        // $this->crud->set_lang_string('alert_delete', 'My message for delete on error'); 
        $this->db->where('id_taller', $primary_key);
        $asistentes = $this->db->get('casal_asistentes')->num_rows();
        // mensaje('asistentes en _before_delete '.$asistentes);
        if (!$asistentes) {
            $sql = "DELETE FROM casal_talleres WHERE id='$primary_key'";
            $this->db->query($sql);
            return true;
        } else {

            return false;
        }
    }
    public function _after_delete($primary_key)
    {
        $this->db->where('id', $primary_key);
        $talleres = $this->db->get('casal_talleres')->num_rows();
        $show_modal = false;

        if ($talleres) {
            $show_modal = true;
        } else {
            $show_modal = true;
        }
    }

    public function _callback_importe($value, $row)
    {
        return "<span class='derecha'>$value € </span>";
    }

    public function _callback_recibo($value, $row)
    {
        return "<span class='izquierda'>$value</span>";
    }

    function _telefono($value, $row)
    {
        $value = trim($value);
        $value = str_replace(' ', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace('-', '', $value);
        $value = str_replace('_', '', $value);
        if (strlen($value) == 9)
            $value = substr($value, 0, 3) . ' ' . substr($value, 3, 3) . ' ' . substr($value, 6, 3);
        return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _fecha_nacimiento_local($value, $row)
    {
        $color = 'black';
        if (substr($value, 0, 6) == substr(date("d/m/Y"), 0, 6))
            $color = 'red';
        return "<span  style='width:100%;color:$color;text-align:left;display:block;'>$value</span>";
    }

    function fechaNacimientoLocal($primary_key)
    {

        $row = $this->db->query("SELECT id,fecha_nacimiento FROM casal_socios_nuevo WHERE id='$primary_key'")->row();
        //foreach($result as $k=>$v){
        $time = strtotime($row->fecha_nacimiento);
        $fecha = date('d/m/Y', $time);
        //echo $fecha.'<br>';
        //$fecha_nacimiento_local=substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.$fecha($value,0,4);
        $id = $row->id;
        //$fecha_nacimiento_local=substr($fecha_nacimiento,8,2).'/'.substr($fecha_nacimiento,5,2).'/'.$fecha_nacimiento($value,0,4);
        $this->db->query("UPDATE casal_socios_nuevo SET fecha_nacimiento_local='$fecha' WHERE id='$id'");

        // } 
    }

    function fechaAltaLocal($primary_key)
    {
        $row = $this->db->query("SELECT id,fecha_alta FROM casal_socios_nuevo WHERE id='$primary_key'")->row();
        if ($row->fecha_alta == "0000-00-00")
            $fecha = "";
        else {
            $time = strtotime($row->fecha_alta);
            $fecha = date('d/m/Y', $time);
        }
        $id = $row->id;
        $this->db->query("UPDATE casal_socios_nuevo SET fecha_alta_local='$fecha' WHERE id='$id'");
    }

    function fechaBajaLocal($primary_key)
    {
        $row = $this->db->query("SELECT id,fecha_baja FROM casal_socios_nuevo WHERE id='$primary_key'")->row();
        if ($row->fecha_baja == "0000-00-00")
            $fecha = "";
        else {
            $time = strtotime($row->fecha_baja);
            $fecha = date('d/m/Y', $time);
        }
        $id = $row->id;
        $this->db->query("UPDATE casal_socios_nuevo SET fecha_baja_local='$fecha' WHERE id='$id'");
    }

    function _fecha_alta_socios($post_array, $primary_key)
    {
        $fecha_alta = array(
            "num_socio" => $primary_key,
            // "fecha_alta" => date('Y-m-d'),
            "fecha_modificacion" => date('Y-m-d'),
        );

        $this->fechaNacimientoLocal($primary_key);
        $this->fechaAltaLocal($primary_key);
        $this->fechaBajaLocal($primary_key);

        $this->db->update('casal_socios_nuevo', $fecha_alta, array('id' => $primary_key));
        $this->db->insert('casal_nuevos_socios_sin_carnet', array('num_socio' => $primary_key));

        $this->db->insert('casal_web_registros', array('registro' => 1, 'fecha' => date('Y-m-d')));
        return true;
    }

    function _fecha_modificacion_socios($post_array, $primary_key)
    {
        $fecha_alta = array(
            "fecha_modificacion" => date('Y-m-d'),
        );
        $this->fechaNacimientoLocal($primary_key);
        $this->fechaAltaLocal($primary_key);
        $this->fechaBajaLocal($primary_key);

        $this->db->update('casal_socios_nuevo', $fecha_alta, array('id' => $primary_key));
        $this->db->insert('casal_nuevos_socios_sin_carnet', array('num_socio' => $primary_key));
        $this->db->insert('casal_web_registros', array('registro' => 2, 'fecha' => date('Y-m-d')));
        return true;
    }

    function _fecha_modificacion_colaboradores($post_array, $primary_key)
    {
        $fecha_alta = array(
            "fecha_modificacion" => date('Y-m-d'),
        );
        $this->db->update('casal_colaboradores', $fecha_alta, array('id' => $primary_key));
        return true;
    }

    function _fecha_alta_colaboradores($post_array, $primary_key)
    {
        $fecha_alta = array(
            "num_socio" => $primary_key,
            "fecha_alta" => date('Y-m-d'),
            "fecha_modificacion" => date('Y-m-d')
        );
        $this->db->update('casal_colaboradores', $fecha_alta, array('id' => $primary_key));
        return true;
    }

    function _fecha_alta_profesores($post_array, $primary_key)
    {
        $data = array(
            "num_socio" => $primary_key,
            "fecha_alta" => date('Y-m-d'),
            "fecha_modificacion" => date('Y-m-d'),
            "nombre_apellidos" => $post_array['nombre'] . ' ' . $post_array['apellidos'],
        );
        $this->db->update('casal_profesores', $data, array('id' => $primary_key));
        return true;
    }

    function _fecha_modificacion_profesores($post_array, $primary_key)
    {
        $data = array(
            "fecha_modificacion" => date('Y-m-d'),
            "nombre_apellidos" => $post_array['nombre'] . ' ' . $post_array['apellidos'],
        );
        $this->db->update('casal_profesores', $data, array('id' => $primary_key));
        return true;
    }

    function _fecha_nacimiento_local____($value, $row)
    {
        $fecha_nacimiento = strtotime($row->fecha_nacimiento);
        $fecha_nacimiento = date("d/m/Y", $fecha_nacimiento);
        return $fecha_nacimiento;
        return substr($fecha_nacimiento, 8, 2) . '/' . substr($fecha_nacimiento, 5, 2) . '/' . $fecha_nacimiento($value, 0, 4);
    }

    function _edad($value, $row)
    {

        return date_diff(date_create($row->fecha_nacimiento), date_create('today'))->format('%y');
    }

    function validar_curso($curso)
    {


        if (!$curso) {
            $this->form_validation->set_message('validar_curso', "Se debe introducir el curso del tallers");
            return false;
        } else {
            //$this->get_form_validation()->set_message('validar_dni',"EL DNI, NIE o Pasaporte NO es válido. Nota Para entrar núm pasaporte, se debe terminar con '_'");
            return true;
        }
    }

    function validar_cambio_precio($precio)
    {
        $this->load->model('talleres_model');
        $this->form_validation->set_message('validar_cambio_precio', "No está permitido modificar el precio porque existen usuarios asistentes a este taller ");
        return false;
    }





    function validar_codigo_postal($codigoPostal)
    {
        //Esun DNI?
        $codigoPostal = strtoupper(trim($codigoPostal));
        $codigoPostal = str_replace(" ", "", $codigoPostal);

        if (substr($codigoPostal, -1, 1) == '_')
            return true;

        $check = true;
        for ($i = 0; $i < strlen($codigoPostal); $i++) {
            $n = substr($codigoPostal, $i, 1);
            if (!is_numeric($n))
                $check = false;
        }

        if (!$check) {
            $this->form_validation->set_message('validar_codigo_postal', "EL Código Postal DEBE contener sólo números. Nota: para evitar que se haga esta comprobación, se debe terminar con '_'");
            return false;
        }

        if (strlen($codigoPostal) != 5) {
            $this->form_validation->set_message('validar_codigo_postal', "EL Código Postal NO es válido, debe tener 5 números. Nota: para evitar que se haga esta comprobación, se debe terminar con '_'");
            return false;
        } else {
            //$this->get_form_validation()->set_message('validar_dni',"EL DNI, NIE o Pasaporte NO es válido. Nota Para entrar núm pasaporte, se debe terminar con '_'");
            return true;
        }
    }



    function validar_dni($dni)
    {
        //Esun DNI?
        $dni = strtoupper(trim($dni));
        $dni =  str_replace(" ", "", $dni);
        $dni = str_replace('-', '', $dni);
        $dni = str_replace('.', '', $dni);
        $dni = strtoupper($dni);



        $pas = strtolower(trim(substr($dni, -3, 3)));
        if ($pas == 'pas') return true;

        if (strlen($dni) != 9) {
            $this->form_validation->set_message('validar_dni', "EL DNI, NIE NO és vàlid. Ha de tenir 9 digits (numeros mes lletra / s)");
            return false;
        }

        $letra = substr($dni, -1, 1);

        $numero = substr($dni, 0, 8);
        $numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);
        $modulo = $numero % 23;

        $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letra_correcta = substr($letras_validas, $modulo, 1);

        if ($letra_correcta != $letra) {
            $this->form_validation->set_message('validar_dni', "EL DNI, NIE NO és vàlid. Nota: Si el número és un passaport, s'ha d'acabar amb PAS");
            return false;
        } else {
            //$this->get_form_validation()->set_message('validar_dni',"EL DNI, NIE o Pasaporte NO es válido. Nota Para entrar núm pasaporte, se debe terminar con '_'");
            return true;
        }
    }
}