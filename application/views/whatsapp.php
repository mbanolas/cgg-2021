<style>
    .usuario,
    .nombre,
    .movil {
        font-size: large;
        margin-bottom: 0;
    }

    .movil {
        color: red;
        font-size: xx-large;
    }

    .radio {
        width: 140px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 5px;
    }

    .clearable {
        background: #fff url(http://i.stack.imgur.com/mJotv.gif) no-repeat right -10px center;
        border: 1px solid #999;
        padding: 3px 18px 3px 4px;
        /* Use the same right padding (18) in jQ! */
        border-radius: 3px;
        /*transition: background 0.4s;*/
    }

    .clearable.x {
        background-position: right 5px center;
    }

    /* (jQ) Show icon */
    .clearable.onX {
        cursor: pointer;
    }

    /* (jQ) hover cursor style */
    .clearable::-ms-clear {
        display: none;
        width: 0;
        height: 0;
    }

    #whatsappTalleres>table {
        width: 30%;
    }

    .whatsapp>img {
        /* position: fixed; */
        width: 25px !important;
        height: 25px !important;
        margin-top: -5px;
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

    /* Remove IE default X */

    #whatsappTalleres>table>tbody>tr>td {
        padding: 8px !important;
    }

    #whatsappTalleres>table>tbody>tr>td:nth-child(3) {
        width: auto !important;
    }

    #whatsappTalleres>table>thead>tr>th:nth-child(3) {
        width: auto !important;
    }

    .glyphicon-refresh-animate {
        -animation: spin .7s infinite linear;
        -webkit-animation: spin2 .7s infinite linear;
    }

    @-webkit-keyframes spin2 {
        from {
            -webkit-transform: rotate(0deg);
        }

        to {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        from {
            transform: scale(1) rotate(0deg);
        }

        to {
            transform: scale(1) rotate(360deg);
        }
    }

    #numSeleccionados {
        color: blue;
    }

    .floatt {
        /* position: fixed; */
        width: 25px !important;
        height: 25px !important;
        margin-top: 5px !important;
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

    #n{
        font-weight: bold !important;
        margin-left: 18px;
        border-radius: 50%;
    }
   button#n:hover{
        border-radius: 50%;
    }
    #i,#s{
        margin-left: 5px;
        border-radius: 50%;

    }
    #i>i{
        padding-left:2px;
        padding-right:2px;
    }
    #borrar{
        margin-left: 5px;
        border-radius: 50%;
    }
    .btn_mensaje{
        font-weight: normal;
        border: 1px solid blue;
        color:black;

    }
</style>

<div class="container">
    <h3>Enviar mensajes WhatsApp</h3>
    <h5>(Posar el missatge abans de seleccionar telèfons)</h5>

    <?php $optionsSocios = array() ?>
    <input type="hidden" id="reload" value="<?php echo $reload ?>">
    <input type="hidden" id="dni" value="<?php echo $dni ?>">
    <input type="hidden" id="numSocio" value="<?php echo $numSocio ?>">
    <input type="hidden" id="nombreSocio" value="<?php echo $nombreSocio ?>">
    <br />

    <div class="row ">
        <div class="form-group">
            <label for="comment">Missatge:</label>
            <textarea class="form-control" rows="5" id="mensaje"><?php echo $ultimoMensaje ?></textarea>
        </div>
    </div>
    <div class="row ">
        <div class="form-group">
            <div class="col-sm-2">
                <label class="control-label" for="socio">Filtrar Usuari/Usuària:</label>
            </div>
            <div class="col-sm-2">
                <?php echo form_input(array('class' => 'clearable form-control searchable-input', 'name' => 'buscarSocio', 'id' => 'buscarSocio', 'placeholder' => 'Cercar usuari/usuària')); ?>
            </div>
            <div class="col-sm-1">
                <button class="btn  btn-default" id="buscarSocios"> Cercar</button>
                <button class="btn  btn-default  buscando hide"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cercant...</button>
            </div>
            <div class="col-sm-4 ">
                <?php echo form_dropdown('socio', $optionsSocios, null, array('class' => 'form-control hide socios', 'id' => 'socios')); ?>
            </div>
            <div class="col-sm-3 socios">
                <button type="text" class="btn btn-default hide socios" id="seleccionarSocio">Seleccionar</button>
            </div>

            <div class="col-sm-1">
            </div>
        </div>
    </div>

    <div class="row socios hide">
        <div class="form-group">
            <div class="col-sm-5">
            </div>
            <div class="col-sm-6">
                <label id="numSeleccionados"></label>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="form-group">
            <div class="col-sm-2">
                <label class="control-label" for="socio"></label>
            </div>
            <div class="col-sm-3">

            </div>
            <div class="col-sm-1">

            </div>


            <div class="col-sm-1">
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="form-group">
            <div class="col-sm-2">
                <label class="control-label" for="taller">o seleccionar curs i taller:</label>
            </div>
            <div class="col-sm-2 ">
                <?php echo form_dropdown('curso', $optionsCursos, null, array('class' => 'form-control talleres', 'id' => 'cursos')); ?>
            </div>
            <div class="col-sm-4 ">
                <?php echo form_dropdown('taller', $optionsTalleresCursoActual, null, array('class' => 'form-control talleres', 'id' => 'talleres')); ?>
            </div>
        </div>
    </div>



    <div class="row ">
        <div class="form-group">
            <div class="col-sm-2">
                <label class="control-label" for="socio">o indicar telèfon mòbil:</label>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => '', 'id' => 'telMovil', 'placeholder' => 'Telèfon mòbil')); ?>
            </div>
            <div class="col-sm-1 whatsappManual hide">
                <a href="https://wa.me/34603048184?text=Bona nit" id="whatsappManual" target="_blank" class="social-icon whatsapp">
                    <img src="<?php echo base_url() ?>img/whatsapp.jpg" class="floatt">
                    <i class="fa fa-whatsapp my-float" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
    <hr>
    <div class="row whatsappTalleres hide" id="whatsappTalleres">
        <table class="table">
            <thead>
                <tr>
                    
                    <th>Usuari/Usuària</th>
                    <th>Móbil</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>


</div>



<!-- <?php echo form_close(); ?> -->
</div>







<script>
    $(document).ready(function() {

        $('body > div.container > div.container > div:nth-child(8) > div > label').append('<button class="btn_mensaje" id="n" type="text">N</button><button class="btn_mensaje" id="i" type="text"><i>I</i></button><button class="btn_mensaje" id="s" type="text"><del>abc</del></button><button class="btn_mensaje" id="borrar" type="text">Esborrar</button>')


        
        $('#borrar').click(function(){
            $('#mensaje').val('')
        })
        
        function textoMarcado(marcado){
            var mensaje=$('#mensaje').val()
            var seleccion=window.getSelection().toString()

            console.log('seleccion'+seleccion)
            if(mensaje.indexOf(seleccion)>0){

                console.log(mensaje.substr(0,mensaje.indexOf(seleccion)))
                while(mensaje.substr(0,mensaje.indexOf(seleccion))==" "){
                    seleccion=seleccion.substr(1)
                    console.log('seleccion'+seleccion)
                }
                var anterior=mensaje.substr(0,mensaje.indexOf(seleccion))
                var posterior=mensaje.substr((mensaje.indexOf(seleccion)+seleccion.length))
                $('#mensaje').val(anterior+" "+marcado+seleccion.trim()+marcado+" "+posterior)
                return
            }
            $('.modal-title').html('Informació')
            $('#myModal').css('color', 'black')
            $('.modal-body p').html('Marcar el text a què es vol posar estil prement amb el ratolí sobre el mateix i arrossegant. El text marcat queda amb fons blau')
            $('#myModal').modal('show')
        }

        $('#n').click(function(){
            textoMarcado('*')
        })
        $('#i').click(function(){
            textoMarcado('_')
        })
        $('#s').click(function(){
            textoMarcado('~')
        })


        $('#mensaje').keyup(function() {
            $('#socios').addClass('hide')
            $('#seleccionarSocio').addClass('hide')
            $('#numSeleccionados').addClass('hide')
            $('#buscarSocio').val('')
            $('#telMovil').val("")
            $('.whatsappManual').addClass('hide')
            $('#whatsappTalleres').addClass('hide')
            $('#talleres').val("0")
        })

        $('#cursos').change(function() {
            console.log($(this).val())
            $('#talleres').html("")
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getTalleresOptions",
                data: {
                    curso: $(this).val()
                },
                success: function(datos) {
                    // alert(datos)
                    var datosJSON = $.parseJSON(datos);
                    $('#talleres').html("")
                    $('#socios').addClass('hide')
                    $('#seleccionarSocio').addClass('hide')
                    $('#numSeleccionados').addClass('hide')
                    $('#buscarSocio').val('')
                    $('#telMovil').val("")
                    $('.whatsappManual').addClass('hide')
                    $('.whatsappTalleres').addClass('hide')
                    $.each(datosJSON, function(index, value) {
                        $("#talleres").append('<option value="' + value['taller'] + '">' + value['nombre'] + '</option>');
                    })

                    // alert(datosJSON[0]['nombre'])

                },
                error: function() {
                    alert("Error en el proceso de getTalleresOptions. Informar");
                }
            })
        })

        $('#telMovil').keyup(function() {
            $('#socios').addClass('hide')
            $('#seleccionarSocio').addClass('hide')
            $('#numSeleccionados').addClass('hide')
            $('#buscarSocio').val('')
            $('#whatsappTalleres').addClass('hide')
            $('#talleres').val('0')

            // console.log($(this).val())
            var reg = /^[6-7]{1}[0-9]{8}$/;
            if (reg.test($(this).val())) {
                $('.whatsappManual').removeClass('hide')
                $('.whatsapp').attr('href', "https://wa.me/34" + $(this).val() + "?text=" + $('#mensaje').val())
                // console.log($(this).val() + ' ES válido')
            } else {
                $('.whatsappManual').addClass('hide')
                if ($(this).val().length >= 9) {
                    // console.log($(this).val() + ' NO ES válido')
                    $('.modal-title').html('Informaciónn')
                    $('#myModal').css('color', 'red')
                    $('.modal-body p').html('El telèfon mòbil ha de tenir 9 dígits, sense espais, sent el primer 6 o 7')
                    $('#myModal').modal('show')
                }

            }
        })




        $('select#talleres').change(function() {
            $('#socios').addClass('hide')
            $('#seleccionarSocio').addClass('hide')
            $('#numSeleccionados').addClass('hide')
            $('#buscarSocio').val('')
            $('#telMovil').val("")
            $('.whatsappManual').addClass('hide')
            var taller = $(this).val()
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getMovilesInscritosTaller",
                data: {
                    taller: taller
                },
                success: function(datos) {
                    // alert(datos)
                    var datosJSON = $.parseJSON(datos);
                    // alert(datosJSON[0]['nombre'])
                    $("#whatsappTalleres > table > tbody").html("")
                    var mensaje = $('#mensaje').val()
                    $.each(datosJSON, function(index, value) {
                        console.log(index + ": " + value['nombre']);
                        var movil = value['movil']
                        var id = value['num_socio']
                        var wa = ""
                        if ($.isNumeric(movil)) {
                            wa = '<a href="https://wa.me/34' + movil + '?text=' + mensaje + '" id="' + id + '" target="_blank" class="social-icon whatsapp"><img src="<?php echo base_url() ?>img/whatsapp.jpg" class=""><i class="fa fa-whatsapp my-float" aria-hidden="true"></i></a>'
                        }
                        $("#whatsappTalleres > table > tbody").append("<tr><td>" + value['apellidos'] + ', ' + value['nombre'] + "</td><td>" + value['movil'] + "</td><td>" + wa + "</td></tr>");
                    });

                    $("#whatsappTalleres").removeClass('hide');
                },
                error: function() {
                    alert("Error en el proceso de getMovilesInscritosTaller. Informar");
                }
            })

        })

        $('#seleccionarSocio').click(function() {
            $('.mostrarUsuario').removeClass('hide')
            $('.usuario').removeClass('hide')
            $('.nombre').removeClass('hide')
            $('.movil').removeClass('hide')
            $('.whatsappManual').addClass('hide')
            $('#telMovil').val('')

            var socio = $('select#socios').val()

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getMovilSocio",
                data: {
                    socio: socio
                },
                success: function(datos) {
                    var datosJSON = $.parseJSON(datos);
                    $("#whatsappTalleres > table > tbody").html("")
                    var mensaje = $('#mensaje').val()
                    var movil = datosJSON['movil']
                    var id = datosJSON['id']
                    var wa = ""
                    if ($.isNumeric(movil)) {
                        wa = '<a href="https://wa.me/34' + movil + '?text=' + mensaje + '" id="' + id + '" target="_blank" class="social-icon whatsapp"><img src="<?php echo base_url() ?>img/whatsapp.jpg" class=""><i class="fa fa-whatsapp my-float" aria-hidden="true"></i></a>'
                    }
                    $("#whatsappTalleres > table > tbody").append("<tr><td>" + datosJSON['apellidos'] + ', ' + datosJSON['nombre'] + "</td><td>" + datosJSON['movil'] + "</td><td>" + wa + "</td></tr>");

                    $("#whatsappTalleres").removeClass('hide');
                },
                error: function() {
                    alert("Error en el proceso de getMovilSocio. Informar");
                }
            })
        })

        $("#whatsappTalleres").delegate('.whatsapp', "click", function() {
            console.log('#whatsappTalleres delegate paso')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/grabarWhatsApp",
                data: {
                    mensaje: $('#mensaje').val(),
                    socio: $(this).attr('id'),
                    movil: $(this).parent().parent().children().eq(1).html()                    
                },
                success: function(datos) {
                    console.log(datos)
                    //var datosJSON=$.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso grabarWhatsApp. Informar");
                }
            })
        });

        $('.whatsapp').click(function() {
            // console.log($('#mensaje').val())
            // console.log($('select#socios').val())
            // console.log($('#movil').text())
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/grabarWhatsApp",
                data: {
                    mensaje: $('#mensaje').val(),
                    socio: $('select#socios').val(),
                    movil: $('#movil').text()
                },
                success: function(datos) {
                    console.log(datos)
                    //var datosJSON=$.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso grabarWhatsApp. Informar");
                }
            })
        })

        $('#inscripcionesTalleres').addClass('hide');

        if ($('#reload').val() == 1) {
            $('.modal-title').html('Informaciónn')
            $('#myModal').css('color', '')
            $('.modal-body p').html('Buscar y seleccionar un socio')
            $('#myModal').modal('show')
        }
        if ($('#reload').val() == 2) {
            $('.modal-title').html('Informaciónn')
            $('#myModal').css('color', '')
            $('.modal-body p').html('Se debe seleccionar un perido')
            $('#myModal').modal('show')
        }

        if ($('#reload').val() == 3) {
            $('.modal-title').html('Informació')
            $('#myModal').css('color', 'red')
            dni = $("#dni").val()
            numSocio = $("#numSocio").val()
            nombreSocio = $("#nombreSocio").val()
            $('.modal-body p').html("El DNI (" + dni + ") de <strong>" + nombreSocio + "</strong> (" + numSocio + ") no és vàlid.<br>Heu d'anar a la base de dades d'usuaris/ usuàries i modificar-ho abans de fer la inscripció al taller.")
            $('#myModal').modal('show')
        }

        $('input[name="periodo[]"]').click(function() {
            var periodo = $(this).val()
            //alert(periodo)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/setUltimoPeriodo",
                data: {
                    periodo: periodo
                },
                success: function(datos) {

                    return
                },
                error: function() {
                    alert("Error en el proceso de registrar ultimo periodo. Informar");
                }
            })
        })

        $('.C').click(function() {
            $(this).parent().children('.T1, .T2, .T3').prop('checked', false);
        });

        $('.T1, .T2, .T3').click(function() {
            if ($(this).is(':checked')) {
                $(this).parent().children('.C').prop('checked', false);
            } else {
                $(this).parent().children('.C').prop('checked', true);
                //alert("Checkbox Master must be checked, but it's not!");
            }
        });




        // CLEARABLE INPUT
        function tog(v) {
            return v ? 'addClass' : 'removeClass';
        }
        $(document).on('input', '.clearable', function() {
            $('#numSeleccionados').html('')
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function(e) {
            $('#numSeleccionados').html('')
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function(ev) {
            ev.preventDefault();
            $('#numSeleccionados').html('')
            $(this).removeClass('x onX').val('').change();
            $(this).css('border', '1px solid #ccc')
            $('select#socios').addClass('hide');
            $('inscripcionesTalleres').addClass('hide');
            // filtroSocios(" ")
        });

        $('#curso').val($('select#cursos').val())


        $('input.searchable-input_').keyup(function() {
            if ($(this).val()) {
                $(this).css('border-color', '#444')
                $(this).css('border-style', 'dashed')
                var filtro = $(this).val()
                filtroSocios(filtro)
            } else {
                $(this).css('border', '1px solid #ccc')
                filtroSocios(" ")
            }

        })

        $('input.searchable-input').click(function() {
            $('select#socios').addClass('hide');
            $('inscripcionesTalleres').addClass('hide');
        })

        $('#buscarSocios').click(function(e) {
            $('#inscripcionesTalleres').attr('disabled', 'disabled')
            $('#numSeleccionados').html('')
            $('.socios').removeClass('hide')
            $('#numSeleccionados').removeClass('hide')
            $('.usuario').addClass('hide')
            $('.nombre').addClass('hide')
            $('.movil').addClass('hide')



            e.preventDefault()
            if ($('input.searchable-input').val()) {
                $('input.searchable-input').css('border-color', '#444')
                $('input.searchable-input').css('border-style', 'dashed')
                var filtro = $('input.searchable-input').val()
                filtroSocios(filtro)
            } else {
                $('input.searchable-input').css('border', '1px solid #ccc')
                filtroSocios(" ")
            }

        })

        $('.buscando').click(function(e) {
            e.preventDefault()
        })

        function sortSelectOptions(selectElement) {
            var options = $(selectElement + " option");
            options.sort(function(a, b) {
                if (a.text.toUpperCase() > b.text.toUpperCase())
                    return 1;
                else if (a.text.toUpperCase() < b.text.toUpperCase())
                    return -1;
                else
                    return 0;
            });
            $(selectElement).empty().append(options);
            $(selectElement + " option").first().attr('selected', 'selected')
        }

        $('#socios').click(function() {
            $('.usuario').addClass('hide')
            $('.nombre').addClass('hide')
            $('.movil').addClass('hide')
        })

        $('#inscripcionesTalleres').attr('disabled', 'disabled')

        $('#buscarSocio').click(function() {
            $('#numSeleccionados').html('')
            $('#inscripcionesTalleres').attr('disabled', 'disabled')
            $('.socios').addClass('hide');
            $('.nombre').addClass('hide');
            $('.movil').addClass('hide');
            $('#whatsappTalleres').addClass('hide')
            $('#talleres').val('0')

        })

        function filtroSocios(filtro) {
            $('.buscando').removeClass('hide')
            $('.buscarSocios').addClass('hide')
            $('#inscripcionesTalleres').addClass('hide');
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/getSociosFiltro",
                data: {
                    filtro: filtro
                },
                success: function(datos) {
                    $('select#socios option').remove();
                    //alert(datos)
                    if (datos == 'false') {
                        //alert('hola')
                        $('#socios').append('<option value="0">No existen socios</option>')
                        $('#inscripcionesTalleres').attr('disabled', 'disabled')
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                        $('select#socios').removeClass('hide');
                    } else {

                        var datos = $.parseJSON(datos);
                        $('#inscripcionesTalleres').removeAttr('disabled')
                        $.each(datos, function(index, value) {
                            var option = '<option value="' + index + '">' + value + '</option>'
                            $('#socios').append(option)
                        })
                        $('#numSeleccionados').html('')
                        $('select#socios').css('height', '')
                        if (Object.keys(datos).length > 1) {
                            $('#numSeleccionados').html(Object.keys(datos).length + " en la llista. Trieu l'apropiat")
                        }
                        sortSelectOptions('#socios')
                        $('select#socios').removeClass('hide');
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                        $('#seleccionarSocio').removeClass('hide');

                    }
                },
                error: function() {
                    alert("Error en el proceso getSociosFiltro. Informar");
                }
            })
        }

    })
</script>