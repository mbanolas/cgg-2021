<!--<div style='height:15px;'></div>-->

<style>
#cursos,
#cursos0,
#cursos1,
#cursos2 {
    margin-left: 50px;
}

#talleres {
    margin-left: 8px;
}

.direcciones {
    margin-left: 50px;
    color: blue;
}

#crudForm>div>div.form-group.mensaje_form_group>div {
    width: 70% !important;
}

#field-para {
    width: 100% !important;
}

.envio {
    margin-right: 5px;
    margin-top: 5px;
    margin-bottom: 0;
}

#field-bloques {
    width: 50px;
}



button.envioEmails:not([disabled]) {
    border: 8px solid darkblue;
}

#selCurso {
    margin-left: 5px;
}

#destacado {
    font-size: 20px;
    font-weight: bold;
    color: #2d38be;
}

#destacado-error {
    font-size: 20px;
    font-weight: bold;
    color: red;
}

#linea1 {
    font-size: 20px;
    /* font-weight: bold; */
    color: #2d38be;
}


.rojo {
    color: red;
}

.rojo_negrita,
#descartados {
    color: red;
    font-weight: bold;
}

.envioEmails,
.enviados {
    min-width: 200px;
}

/* #direcciones2 {
        margin-left: 50px;
    } */
</style>

<div class="container">

    <?php echo $output; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>




<script>
$(document).ready(function() {



    //control cambios
    var cambios = false;
    // Se cambia el título
    $('body > div.container > div > div > div.container > div > div > div > div > div.table-label > div.floatL.l5').html('Enviar Emails')

    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    }

    function copyToClipboard(elemento) {
        var $temp = $("<input>")
        $("body").append($temp);
        $temp.val($(elemento).val()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    $('#field-bloques').val($('#bloques').val())

    $(window).bind('beforeunload', function() {
        if (cambios) return '';
    });

    //botones página
    $('<button class="btn btn-info b10" type="button" id="save-and-go-back-button-2"><i class="fa fa-rotate-left"></i> Preparar envio emails <img class="loading hide" src="http://localhost:8888/casal_gent_gran/images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   </button>')
        .insertAfter($('#save-and-go-back-button'))
    $('#cancel-button').addClass('envio');
    $('#save-and-go-back-button-2').addClass('envio');
    $('#gcrud-search-form > div.header-tools > div:nth-child(1) > a').html(
        '<i class="fa fa-plus"></i> &nbsp; Nou correu electrònic')
    $('#crudForm > div.form-group.para_form_group > div').removeClass('col-sm-9')
    $('#crudForm > div.form-group.para_form_group > div').addClass('col-sm-8')
    $('body > div.container > div > div > div.container > div > div > div > div > div.form-container.table-container > form > div.form-group.para_yahoo_form_group > div').removeClass('col-sm-9')
    $('body > div.container > div > div > div.container > div > div > div > div > div.form-container.table-container > form > div.form-group.para_yahoo_form_group > div').addClass('col-sm-8')
    
    $('<span><button id="copiar">Copiar</button></span>').insertAfter($(
        '#crudForm > div.form-group.para_form_group > div'))
    $('<span><button id="copiar_yahoo">Copiar</button></span>').insertAfter($(
        '#crudForm > div.form-group.para_yahoo_form_group > div'))

    $('#field-para_yahoo').attr('disabled','disabled')    
    // $('<span><button id="copiar">Copiar</button></span>').insertAfter($(
    //     '#crudForm > div.form-group.para_yahoo_form_group > div'))

    
    // desactiva la acción de grocery crud cuando se pulsa Copiar
    $('#crudForm').removeAttr('id')


    var actual = $('#save-and-go-back-button').html()
    $('#save-and-go-back-button').html(actual +
        ' <img class="loading hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   '
    )
    // var actual2=$('#form-button-save').html()
    // $('#form-button-save').html(actual2+' <img class="loading hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   ')
    $('#form-button-save').addClass('hide')

    $('#save-and-go-back-button').addClass('hide')
    // $('#save-and-go-back-button').click(function() {
    //   $('#save-and-go-back-button img').removeClass('hide')
    // })

    $('#field-para').keyup(function() {
        $('#save-and-go-back-button-2').removeClass('hide')
        $('.envioEmails').remove()
    })

    $('#field-titulo').click(function() {
        if (cambios) {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("No es poden fer modificacions si s'estan enviant emails")
            $("#myModalEmails").modal()
        }
    })
    $('#field-para').click(function() {
        if (cambios) {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("No es poden fer modificacions si s'estan enviant emails")
            $("#myModalEmails").modal()
        }
    })

    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.instances['field-mensaje'].on('contentDom', function() {
            this.document.on('click', function(event) {
                if (cambios) {
                    $('.modal-title').html('Informació')
                    $('.modal-body>p').html(
                        "No es poden fer modificacions si s'estan enviant emails")
                    $("#myModalEmails").modal()
                }
            });
        });
    }

    $('#save-and-go-back-button-2').click(function() {
        var numBloques = parseInt($('#field-bloques').val().trim())
        if (numBloques < 1 || numBloques > 30) {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("El nombre de blocs ha de ser entre 1 i 30")
            $("#myModalEmails").modal()
            return false
        }
        if ($('#field-titulo').val().trim() == "") {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("S'ha d'introduir un títol de l'email")
            $("#myModalEmails").modal()
            return false
        }
        if ($('#field-para').val().trim() == "") {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("S'ha d'introduir una o més adreces email")
            $("#myModalEmails").modal()
            return false
        }
        if ($('#field-para').val().indexOf("yahoo") !== -1) {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("No es poden enviar correus electrònics a adreces yahoo. Eliminar abans de prosseguir.")
            $("#myModalEmails").modal()
            return false
        }
        if ($('#field-mensaje').val().trim() == "") {
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("l'email no conté cap missatge")
            $("#myModalEmails").modal()
            return false
        }

        var para = $('#field-para').val()
        para = para.trim()
        para = para.replaceAll(" ", "");
        var lastChar = para.slice(-1);
        // console.log('lastChar '+lastChar)
        // console.log(para)
        if (lastChar == ",") {
            para = para.substring(0, para.length - 1);
        }
        //se añade email casal al final
        para = para + "," + $('#emailOneCasal').val() // + ","+$('#emailCasal').val()

        $('#field-para').val(para)
        var paraArrayLeidos = para.split(',');
        // ver solución 9 en  https://stackoverflow.com/questions/10191941/jquery-unique-on-an-array-of-strings
        // elimina correos duplicados
        var paraArray = Array.from(new Set(paraArrayLeidos));

        console.log('paraArrayLeidos ' + paraArrayLeidos.length);
        console.log('paraArray ' + paraArray.length);

        var nuevoPara = paraArray.join(",");
        $('#field-para').val(nuevoPara)

        var noCorrectos = "<span style='color:red'>"
        var contador = 0;
        paraArray.forEach(function(item, index) {
            // console.log(item);
            if (validateEmail(item) == false) {
                noCorrectos += " " + item
                contador++
            }
        })
        if (contador != 0) {
            noCorrectos += "</span>"
            $('.modal-title').html('Informació')
            var texto = contador == 1 ? "l'email " + noCorrectos + " no es una adreça de email" :
                "Los emails " + noCorrectos + " no son adresses de email"
            $('.modal-body>p').html(texto)
            $("#myModalEmails").modal()
            return
        }
        var bloques = $('#field-bloques').val()
        // $('#field-bloques').val(bloques)
        // preparan grupos de envío de email
        var i;
        var max = bloques;
        for (i = paraArray.length; i > 0; i = i - max) {
            var primero = i - max < 0 ? 0 : i - max
            var ultimo = i - 1 > paraArray.length ? paraArray.length : i - 1
            var disabled = 'disabled'
            if (primero == 0) disabled = ""

            $('<button class="btn btn-info envio envioEmails" ' + disabled +
                ' type="button" primero="' + (primero) + '" ultimo="' + (ultimo) +
                '" id="save-and-go-back-button-2' + i +
                '"> <i class="fa fa-rotate-left"></i> Enviar emails (' + (primero + 1) + '-' + (
                    ultimo + 1) +
                ') <img class="loading hide" src="http://localhost:8888/casal_gent_gran/images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   </button>'
            ).insertAfter($('#save-and-go-back-button'))
        }
        $(this).addClass('hide')
        cambios = true
    })


    $("body").delegate("#copiar", "click", function(e) {
        // console.log('copiando')
        // $('#field-para').css('background-color','#B3D8FD')
        copyToClipboard('#field-para')
        return
    })

    $("body").delegate("#copiar_yahoo", "click", function(e) {
        // console.log('copiando')
        // $('#field-para').css('background-color','#B3D8FD')
        copyToClipboard('#field-para_yahoo')
        return
    })


    $("body").delegate(".envioEmails", "click", function(e) {
        // console.log('paso por .envioEmails')
        var botonEnvio = $(this)
        var primero = $(this).attr('primero');
        var ultimo = $(this).attr('ultimo');

        // console.log(primero)
        // console.log(ultimo)
        botonEnvio.children().removeClass('hide')
        botonEnvio.attr('disabled', 'disabled')
        // verificar que existe título
        // console.log('al empezar '+$(".envioEmails").length)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>" + "index.php/basesDatos/enviarEmails_2",
            data: $('form').serialize() + '&primero=' + primero + '&ultimo=' + ultimo,
            success: function(datos) {

                // console.log("success - datos")
                // console.log(datos)
                botonEnvio.children().addClass('hide')
                var siguiente = botonEnvio.next()
                siguiente.prop('disabled', false)
                var resultado = $.parseJSON(datos)
                // console.log(resultado)
                // console.log(resultado['domains'])
                var finalizado = "Tancar i prémer següent botó Enviar emails remarcat"

                // botonEnvio.remove()
                botonEnvio.removeClass('envioEmails')
                botonEnvio.addClass('enviados')
                if ($(".envioEmails").length == 0) {
                    finalizado = "Finalitzat"
                    cambios = false
                    $('#field-para').val("")
                    $('#field-titulo').val('')
                    $('#field-mensaje').val('')
                    $('#para6').prop('checked', true);

                    $('.enviados').remove()
                    $('#save-and-go-back-button-2').removeClass('hide')


                    // console.log('al removerlo '+$(".envioEmails").length)

                }
                var noValidos = ""
                if (resultado['noValidos'] != "") {
                    noValidos =
                        '<p id="descartados">Emails descartats per no ser vàlids: ' +
                        resultado['noValidos'] + '</p>'
                }
                console.log(resultado['resultadoEnvio'])
                var style = ""
                var resultadoEnvio =
                    "<span id='linea1'>S'han enviat els següents emails: </span>"
                if (resultado['resultadoEnvio'] == 0) {
                    resultadoEnvio =
                        "<span style='color:red' id='destacado-error' >ERROR - NO s'han pogut enviar les següents emails: </span>"
                    style = "style='color:red'"
                }

                $('.modal-title').html('Informació')
                $('.modal-body>p').html(resultadoEnvio + '<p ' + style + '>' + resultado[
                        'enviados'] + '</p><p id="destacado">' + finalizado + '</p>' +
                    noValidos)
                $("#myModalEmails").modal()
            },
            error: function() {
                // console.log('Error ' + status)
                $('#save-and-go-back-button-2 img').addClass('hide')
                $('.modal-title').html('Informació')
                $('.modal-body>p').html(
                    "Emails enviats. Per sobrecàrrega del servidor el procés tardarà un temps."
                )
                $("#myModalEmails").modal()
            }
        })

    })


    $('#save-and-go-back-button-2-').click(function() {
        $('#save-and-go-back-button-2 img').removeClass('hide')
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>" + "index.php/basesDatos/enviarEmails",
            data: $('form').serialize(),
            success: function(datos) {
                var resultado = $.parseJSON(datos)
                $('#save-and-go-back-button-2 img').addClass('hide')
                $('.modal-title').html('Informació')
                $('.modal-body>p').html("S'han enviat els següents " + resultado[
                        'contador'] + ' emails:' + '<p>' + resultado['enviados'] +
                    '</p>')
                $("#myModalEmails").modal()
            },
            error: function() {
                //  console.log('Error ' + status)
                $('#save-and-go-back-button-2 img').addClass('hide')
                $('.modal-title').html('Informació')
                $('.modal-body>p').html(
                    "Emails enviats. Per sobrecàrrega del servidor el procés tardarà un temps."
                )
                $("#myModalEmails").modal()
            }
        })
    })


    var emails = ""
    var nombres = ""
    var apellidos = ""
    var direccionesPara = ""

    $('#cursos').change(function() {
        // console.log($(this).val())
        // $('#talleres').html("")
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
                $.each(datosJSON, function(index, value) {
                    $("#talleres").append('<option value="' + value['taller'] +
                        '">' + value['nombre'] + '</option>');
                })

                // alert(datosJSON[0]['nombre'])

            },
            error: function() {
                alert("Error en el proceso de getTalleresOptions. Informar");
            }
        })
    })



    function todos_() {
        console.log('paso todos')
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodos",
            data: {},
            success: function(datos) {
                // alert(datos)
                var datos = $.parseJSON(datos);
                direccionesPara = datos['emails']
                console.log('direcciones Yahoo ' + datos['yahoo'])
                if (datos['emails'].length == 1) emails = datos['emails'][0]
                if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos[
                    'emails'].length + ' més'
                if (datos['emails'].length = 0) emails = 'cap email trobat '
                $('#direcciones1').html(emails)
                $('#field-para').val(datos['emails'])
            },
            error: function() {
                alert("Error en el proceso getEmailsTodos. Informar");
            }
        })
    }

    $('#cursos0').change(function() {
        todosTalleres(0, '#direcciones3')
    })
    $('#cursos1').change(function() {
        todosTalleres(1, '#direcciones4')
    })
    $('#cursos2').change(function() {
        todosTalleres(2, '#direcciones5')
    })

    function todosTalleres(tipo_taller = 0, target) {
        $('#cursos0, #cursos1, #cursos2').addClass('hide')
        var curso = 0;
        switch (tipo_taller) {
            case 0:
                $('#cursos0').removeClass('hide')
                curso = $('#cursos0').val()
                break;
            case 1:
                $('#cursos1').removeClass('hide')
                curso = $('#cursos1').val()
                break;
            case 2:
                $('#cursos2').removeClass('hide')
                curso = $('#cursos2').val()
                break;
        }
        // console.log('curso ' + curso)
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodosTalleres/" + tipo_taller,
            data: {
                curso: curso
            },
            success: function(datos) {
                // alert(datos)
                var datos = $.parseJSON(datos);
                // alert(datos['emails'][0])
                direccionesPara = datos['emails']
                console.log('direcciones yahoo obtenidos ' + datos['yahoo'])
                if (datos['emails'].length == 1) emails = datos['emails'][0]
                if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos[
                    'emails'].length + ' més'
                if (datos['emails'].length == 0) emails = 'Cap email trobat '
                $(target).html(emails)
                $('#field-para').val(datos['emails'])
                $('#field-para_yahoo').val(datos['yahoo'])

            },
            error: function() {
                alert("Error en el proceso getEmailsTodos. Informar");
            }
        })
    }

    function todosUsuarios(target) {
        console.log('todos Usuarios')
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodos",
            data: {},
            success: function(datos) {
                // alert(datos)
                var datos = $.parseJSON(datos);
                // alert(datos['emails'][0])
                direccionesPara = datos['emails']
                console.log('direcciones yahoo TODOS ' + datos['yahoo'])

                // console.log('direccionesPara ' + direccionesPara)
                if (datos['emails'].length == 1) emails = datos['emails'][0]
                if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos[
                    'emails'].length + ' més'
                if (datos['emails'].length == 0) emails = 'Cap email trobat '
                $(target).html(emails)
                $('#field-para').val(datos['emails'])
                $('#field-para_yahoo').val(datos['yahoo'])
            },
            error: function() {
                alert("Error en el proceso getEmailsTodos. Informar");
            }
        })
    }



    // todos()
    // $('#direcciones1').html(emails)
    $("[name='x']").change(function() {
        // console.log($(this).val())
        var periodo = $(this).val()
        var taller = $('#talleres').val()
        // console.log(periodo)
        // console.log(taller)
        if (taller != 0) {
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTaller",
                data: {
                    taller: taller,
                    periodo: periodo
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    direccionesPara = datos['emails']
                    // alert(datos['emails'][0])
                    // console.log('direccionesPara ' + direccionesPara)
                    // console.log('direccionesPara length ' + datos['emails'].length)
                    // console.log("datos['emails'][0] " + datos['emails'][0])
                    if (datos['emails'].length == 1) emails = datos['emails'][0]
                    if (datos['emails'].length > 1) emails = datos['emails'][0] +
                        ",... i " + (datos['emails'].length - 1) + ' més'
                    if (datos['emails'].length == 0) emails = 'Cap email trobat '
                    $('#direcciones2').html(emails)
                    $('#field-para').val(datos['emails'])
                    $('#field-para_yahoo').val(datos['yahoo'])
                },
                error: function() {
                    alert("Error en el proceso getEmailsTodos. Informar");
                }
            })
        }

    })

    $('#talleres').change(function() {
        // alert('seleccionado taller '+$(this).val())
        // console.log($(this).val())
        if ($(this).val() == 0) {
            // document.getElementById("C_").checked
            $('#direcciones2').html("")
            return
        }

        var periodo = 0
        if (document.getElementById("C_").checked) {
            periodo = 7
        }
        if (document.getElementById("T1_").checked) {
            periodo = 4
        }
        if (document.getElementById("T2_").checked) {
            periodo = 2
        }
        if (document.getElementById("T3_").checked) {
            periodo = 1
        }


        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTaller",
            data: {
                taller: $(this).val(),
                periodo: periodo
            },
            success: function(datos) {
                // alert(datos)
                var datos = $.parseJSON(datos);
                direccionesPara = datos['emails']
                // alert(datos['emails'][0])
                // console.log('direccionesPara ' + direccionesPara)
                // console.log('direccionesPara length ' + datos['emails'].length)
                // console.log("datos['emails'][0] " + datos['emails'][0])
                if (datos['emails'].length == 1) emails = datos['emails'][0]
                if (datos['emails'].length > 1) emails = datos['emails'][0] + ",... i " + (
                    datos['emails'].length - 1) + ' més'
                if (datos['emails'].length == 0) emails = 'Cap email trobat '
                $('#direcciones2').html(emails)
                $('#field-para').val(datos['emails'])
            },
            error: function() {
                alert("Error en el proceso getEmailsTodos. Informar");
            }
        })
    })

    $('#otros').change(function() {

        console.log('desde otros 1 typeof direccionesPara ' + typeof direccionesPara)
        direccionesPara = $(this).val().split(' ').join('')
        // console.log('desde otros typeof direccionesPara ' + typeof direccionesPara)
        // console.log('direccionesPara ' + direccionesPara)
        // console.log('direccionesPara toString ' + direccionesPara.toString())
        $('#field-para').val(direccionesPara)
    })

    $('input[type=radio][name=grupo]').change(function() {
        console.log('click radio')
        $('#field-para').val("")
        $('#field-para_yahoo').val("")
        $('#save-and-go-back-button-2').removeClass('hide')
        $('.envioEmails').remove()
        // console.log('cambio')
        // alert('cambio')
        emails = ""
        nombres = ""
        apellidos = ""
        $('.direcciones').html('')
        $('#cursos0, #cursos1, #cursos2').addClass('hide')
        switch (this.value) {
            case 'option1':
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                $('#otros').addClass('hide')
                $('#talleres').val(0)
                todos()
                break;
            case 'option2':
                $('#talleres').val(0)
                $('#talleres').removeClass('hide')
                $('#periodos').removeClass('hide')
                $('#cursos').removeClass('hide')
                $('#otros').addClass('hide')
                break;
            case 'option3':
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                $('#otros').addClass('hide')
                todosTalleres(0, '#direcciones3')
                break;
            case 'option4':
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                $('#otros').addClass('hide')
                todosTalleres(1, '#direcciones4')
                break;
            case 'option5':
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                $('#otros').addClass('hide')
                todosTalleres(2, '#direcciones5')
                break;
            case 'option6':
                $('#field-para').val("")
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                // $('#otros').removeClass('hide')
                // $('#otros').focus()

                break;
            case 'option10':
                $('#talleres').addClass('hide')
                $('#periodos').addClass('hide')
                $('#cursos').addClass('hide')
                $('#otros').addClass('hide')
                todosUsuarios('#direcciones10')
                break;
        }
    });

    $('#field-para').change(function() {
        // console.log('cambiado mensaje')
        $('#para6').prop('checked', true);

    })

    $('#enviarEmail').click(function() {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/socios/enviarEmail",
            data: {
                titulo: $('#titulo').val(),
                mensaje: $('#mensaje').html(),
                direccionesPara: direccionesPara,
                adjunto_1: adjunto_1,
                adjunto_2: adjunto_2,
                adjunto_3: adjunto_3,
            },
            success: function(datos) {
                // alert(datos)
                var datos = $.parseJSON(datos);
                direccionesPara = datos['emails']
                // alert(datos['emails'][0])
                direccionesPara = datos['emails']
                // console.log('direccionesPara ' + direccionesPara)
                if (datos['emails'].length == 1) emails = datos['emails'][0]
                if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + (
                    datos['emails'].length - 1) + ' més'
                if (datos['emails'].length == 0) emails = 'Cap email trobat '
                $('#direcciones2').html(emails)
            },
            error: function() {
                alert("Error en el proceso getEmailsTodos. Informar");
            }
        })



    })


    // $('#save-and-go-back-button, #form-button-save').click(function() {

    //   if($('field-para').val()==""){
    //     alert("Se ha de indicar alguna dirección para")
    //     return false
    //     location.reload(true);
    //   }
    //   // alert('save-and-go-back-button ' + direccionesPara)
    // })

})
</script>