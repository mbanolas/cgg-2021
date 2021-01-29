<style>
    .floattmenu {
        /* position: fixed; */
        width: 25px;
        height: 25px;
        /* bottom: 40px;
        right: 40px; */
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 100;
    }

    .destacado_ {
        border: 2px solid blue !important;
        color: blue !important;
    }

    .destacado_>ul>li>a {
        border: 2px solid blue !important;
        color: blue !important;
        background-color: lightblue !important;
    }

    .destacado_:focus {
        background-color: lightblue !important;
    }

    .destacado_:hover {
        border: 2px solid blue !important;
        color: blue !important;
        background-color: lightblue !important;
    }

    body_>div>div.btn-group.open>ul>li>a {
        color: blue !important;
        background-color: lightblue !important;
    }

    body_>div>div.btn-group.open>ul {
        background-color: lightblue !important;
    }
    body > div > div  {
        margin:5px;
    }
    body > div > div > div{
        margin-top: 5px;
    }
</style>
<?php if ($this->session->nombre == "") header("Location: " . base_url()); ?>

<div class="container">
    <div><b><?php echo $titulo ?></b><div>
    <!-- inicio -->
    <div class="btn-group">
        <?php echo anchor('inicio', 'Inici', array('class' => 'btn btn-default')) ?>
    </div>

    <?php if ($this->session->categoria == 40) { ?>
        <div class="btn-group">
            <?php echo anchor('basesDatos/casal_socios', 'Usuaris/Usuàries', array('class' => 'btn btn-default')) ?>
        </div>
    <?php } ?>
    <?php if ($this->session->categoria != 40) { ?>
        <!-- Bases de datos -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle destacado" data-toggle="dropdown">
                Usuaris/Usuàries <span class="caret"></span>
            </button>
            <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                <li><?php echo anchor('basesDatos/casal_socios', 'Usuaris/Usuàries') ?></li>
                <!-- <li><?php echo anchor('reporte/listasUsuariosTelefonosEmail', 'Llistat usuaris/usuàries amb telefòns i email') ?></li> -->
                <li><?php echo anchor('reporte/listasUsuariosTelefonosEmail', 'Llistat usuaris/usuàries inscripts amb telefòns i email') ?></li>
                <?php if ($this->session->categoria == 10) { ?>
                <li><a href="<?php echo base_url() ?>index.php/socios/whatsapp">
                        <img src="<?php echo base_url() ?>img/whatsapp.jpg" class="floattmenu">
                        </i>
                    </a></li>
                <?php } ?>

            </ul>
        </div>
        <?php if ($this->session->categoria == 10 || $this->session->categoria == 1) { ?>
        <div class="btn-group">
            <a href="<?php echo base_url() ?>index.php/socios/whatsapp">
                <img src="<?php echo base_url() ?>img/whatsapp.jpg" class="floattmenu">
                </i>
            </a>
        </div>
        <?php } ?>

        <!-- Bases de datos -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Base de dades <span class="caret"></span>
            </button>
            <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                <li><?php echo anchor('basesDatos/casal_socios', 'Usuaris/Usuàries') ?></li>
                <!--
        <li><?php echo anchor('basesDatos/casal_socios_aniversarios', 'Aniversaris socis') ?></li>
        <li><?php echo anchor('basesDatos/casal_socios_completa', 'Socios completa') ?></li>
        -->
                <?php if ($this->session->tipoUsuario != 2) { ?>
                    <li><?php echo anchor('basesDatos/casal_colaboradores', 'Col·laboradors') ?></li>
                    <li><?php echo anchor('basesDatos/casal_profesores', 'Professors') ?></li>
                    <li><?php echo anchor('basesDatos/casal_espacios', 'Espais') ?></li>
                    <li><?php echo anchor('basesDatos/casal_tipos_actividad', 'Activitats') ?></li>
                    <li><?php echo anchor('basesDatos/casal_recibos', 'Rebuts') ?></li>
                    <!--    <li class="divider"></li>
        <li><?php echo anchor('talleres/prepararCasalReservas', 'En Llista espera') ?></li> -->
                    <li class="divider"></li>
                    <li><?php echo anchor('basesDatos/casal_cursos', 'Cursos') ?></li>
                    <li><?php echo anchor('basesDatos/casal_talleres', 'Tallers (tots)') ?></li>
                    <li><?php echo anchor('basesDatos/casal_talleres_curso_actual', 'Tallers curs actual') ?></li>
                <?php   } ?>
            </ul>
        </div>
        <?php if ($this->session->tipoUsuario != 2) { ?>
            <!-- Inscripció Talleres -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Inscripció Tallers <span class="caret"></span>
                </button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('talleres/seleccionar', 'Inscripció a tallers') ?></li>
                    <li><?php echo anchor('talleres/seleccionarBajas', 'Baixes de tallers', array('id' => 'bajas')) ?></li>
                    <!--<li><?php echo anchor('basesDatos/casal_talleres_curso_actual', 'Tallers curs actual') ?></li> -->
                    <li class="divider" style="color:LightGrey "></li>
                    <li style="background-color:LightGrey "><?php echo anchor('talleres/anulacion', 'Anul·lar últim rebut') ?></li>
                    <!--
        <li ><?php echo anchor('talleres/asistentes', 'Llista assistents per taller') ?></li>
        <li ><?php echo anchor('talleres/en_lista_espera', 'En llista espera per taller') ?></li>
       
        <li><?php echo anchor('reporte/index', 'Resum usuaris inscrits taller') ?></li>
        <li><?php echo anchor('reporte/listasTalleresExcel', 'Llistas Excel Inscripcions Tallers') ?></li>
        -->
                    <!--
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Resum socis inscrits taller</a>
            <ul class="dropdown-menu">
               <li><?php echo anchor('reporte/index', 'Tots els tallers') ?></li>
               <li><?php echo anchor('reporte/profesionales', 'Tallers Professionals') ?></li>
               <li><?php echo anchor('reporte/voluntarios', 'Tallers Voluntaris') ?></li>
            </ul>
        </li>
        -->
                    <!--
        <li><?php echo anchor('reporte/listadoTalleres', 'Resum tallers - data, horari, import') ?></li>
        <li><?php echo anchor('talleres/seleccionarTalleresSocio', 'Llistat inscripció tallers per soci') ?></li>
        <li><?php echo anchor('reporte/seleccionarSociosInscritos', 'Llistat usuaris amb Inscripcions Tallers') ?></li>
        <li><?php echo anchor('basesDatos/casal_recibos', 'Rebuts') ?></li>
        <li><?php echo anchor('basesDatos/casal_talleres_curso_actual', 'Tallers curs actual') ?></li>
        -->
                </ul>
            </div>
            <!-- Llistats tallers -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Llistats Tallers <span class="caret"></span>
                </button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">

                    <li><?php echo anchor('talleres/asistentes', 'Llista usuaris assistents per taller') ?></li>
                    <li><?php echo anchor('talleres/en_lista_espera', 'En llista espera per taller') ?></li>
                    <li><?php echo anchor('talleres/en_lista_espera_todos', 'Usuaris/usuàrias en llista espera') ?></li>
                    <li><?php echo anchor('reporte/index', 'Resum usuaris inscrits a tallers') ?></li>
                    <li><?php echo anchor('reporte/listasTalleresExcel', 'Llistas EXCEL Inscripcions Tallers') ?></li>

                    <!--
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Resum socis inscrits taller</a>
            <ul class="dropdown-menu">
               <li><?php echo anchor('reporte/index', 'Tots els tallers') ?></li>
               <li><?php echo anchor('reporte/profesionales', 'Tallers Professionals') ?></li>
               <li><?php echo anchor('reporte/voluntarios', 'Tallers Voluntaris') ?></li>
            </ul>
        </li>
        -->
                    <li><?php echo anchor('reporte/listadoTalleres', 'Resum tallers - data, horari, import') ?></li>
                    <li><?php echo anchor('talleres/seleccionarTalleresSocio', 'Llistat inscripció tallers per usuari') ?></li>
                    <li><?php echo anchor('reporte/seleccionarSociosInscritos', 'Llistat usuaris amb Inscripcions Tallers') ?></li>
                    <!--    <li><?php echo anchor('basesDatos/casal_recibos', 'Rebuts') ?></li> -->
                    <!--    <li><?php echo anchor('basesDatos/casal_talleres', 'Tallers (Tots)') ?></li> -->

                </ul>
            </div>
        <?php   } ?>

        <?php if ($this->session->categoria == 10) { ?>
            <!-- Comisiones -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Comisiones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('basesDatos/comisiones', 'Gestió comissions') ?></li>
                    <li><?php echo anchor('comisiones/miembrosComisiones', 'Gestió membres comissions') ?></li>
                    <li><?php echo anchor('comisiones/envioEmails', 'Envio emails a Comissions') ?></li>
                </ul>
            </div>
        <?php   } ?>

        <!-- Carnets -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Carnets <span class="caret"></span>
            </button>
            <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                <li><?php echo anchor('socios/carnets', 'Imprimir carnets usuaris/usuàrias') ?></li>
            </ul>

        </div>
        <?php if ($this->session->categoria == 10 || $this->session->categoria == 1) { ?>
        <div class="btn-group">
            <?php echo anchor('basesDatos/emails', 'Emails', array('class' => 'btn btn-default')) ?>
        </div>
        <?php } ?>
        <!-- <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle destacado" data-toggle="dropdown">
                E mails <span class="caret"></span>
            </button>
            <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                <li><?php echo anchor('basesDatos/emails/0', 'Inscrits tallers curs actual') ?></li>
                <li><?php echo anchor('basesDatos/emails/1', 'Inscrits tallers curs anterior') ?></li>
            </ul>
        </div> -->

        <?php if ($this->session->tipoUsuario != 2) { ?>
            <!-- Cobros -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Cobraments <span class="caret"></span>
                </button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('cobros/informeCobros', 'Informe cobraments') ?></li>
                    <!--       <li><?php echo anchor('cobros/cobrosTalleresCurso', 'Cobraments per tallers curs') ?></li> -->
                    <li><?php echo anchor('reporte', 'Cobraments per tallers curs') ?></li>
                    <?php   //if($this->session->tipoUsuario==1 ) { 
                    ?>
                    <li class="divider"></li>
                    <li><?php echo anchor('cobros/informeAjuntament', 'Informe Ajuntament') ?></li>
                    <?php //  } 
                    ?>
                </ul>
            </div>
            <!-- Estadístiques -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Estadístiques <span class="caret"></span>
                </button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('estadisticas/sexossocios', 'Estadistica sexes usuaris/usuàries') ?></li>
                </ul>
            </div>
        <?php   } ?>
        <?php if ($this->session->categoria == 10) { ?>
            <!-- Utilidades -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown">
                    Utilidades <span class="caret"></span></button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('socios/whatsapp', 'Enviar WhatsApp') ?></li>
                    <li><a href="<?php echo base_url() ?>index.php/socios/whatsapp">
                            <img src="<?php echo base_url() ?>img/whatsapp.jpg" class="floattmenu">
                            <i class="fa fa-whatsapp my-float" aria-hidden="true"></i>
                        </a></li>

                    <li><?php echo anchor('talleres/anadirCurso', 'Añadir Nuevo Curso Copiando talleres seleccionados') ?></li>
                </ul>
            </div>
            <!-- Pruebas -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown">
                    Pruebas <span class="caret"></span></button>
                <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                    <li><?php echo anchor('talleres/anadirCurso', 'Añadir Nuevo Curso Copiando talleres seleccionados') ?></li>
                    <li><?php echo anchor('pruebas/prueba', 'Cambia vocales mayúscilas con acento a minúsculas, Ñ por ñ, Ç por ç') ?></li>
                    <li><?php echo anchor('pruebas/md5', 'MD5') ?></li>
                    <li><?php echo anchor('pruebas/ponerFechasAsistentes', 'ponerFechasAsistentes') ?></li>
                    <li><?php echo anchor('pruebas/pruebaA', 'Completar casal_pagos a partir de casal_asistentes') ?></li>
                    <li><?php echo anchor('pruebas/copiarTalleres', 'Copiar Talleres') ?></li>
                    <li><?php echo anchor('pruebas/pruebaB', 'Comprobación núm socios') ?></li>
                    <li><?php echo anchor('pruebas/encontrarDuplicados', 'Encontrar Usuarios Duplicados') ?></li>
                    <li><?php echo anchor('pruebas/ponerNumRegistro', 'Poner Num Registro') ?></li>
                </ul>
            </div>
        <?php } ?>
    <?php } ?>

    <?php echo anchor('', 'Sortir', array('class' => 'btn btn-default pull-right')) ?>



    <style>
        ul.dropdown-menu.multi-level>li>a {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        ul.dropdown-menu.multi-level>li.dropdown-submenu>a {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        ul.dropdown-menu.multi-level>li.dropdown-submenu>ul.dropdown-menu>li>a {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        ul.dropdown-menu.multi-level>li.dropdown-submenu>ul.dropdown-menu>li>ul>li>a {
            margin-top: 0px;
            margin-bottom: 0px;
        }



        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            -webkit-border-radius: 0 6px 6px 6px;
            -moz-border-radius: 0 6px 6px;
            border-radius: 0 6px 6px 6px;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        .dropdown-submenu>a:after {
            display: block;
            content: " ";
            float: right;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
            border-width: 5px 0 5px 5px;
            border-left-color: #ccc;
            margin-top: 5px;
            margin-right: -10px;
        }

        .dropdown-submenu:hover>a:after {
            border-left-color: #fff;
        }

        .dropdown-submenu.pull-left {
            float: none;
        }

        .dropdown-submenu.pull-left>.dropdown-menu {
            left: -100%;
            margin-left: 10px;
            -webkit-border-radius: 6px 0 6px 6px;
            -moz-border-radius: 6px 0 6px 6px;
            border-radius: 6px 0 6px 6px;
        }
    </style>

    <script>
        $(document).ready(function() {


        })
    </script>



    <style>
        #bajas {
            color: red;
        }

        .dropdown-menu .divider {
            background-color: blue;
            margin-bottom: 0px;
            margin-top: 0px;
        }

        .dropdown-menu>li>a[href$="Reservas"] {
            color: blue;
            font-weight: bold;
            background-color: lightblue;

        }
    </style>