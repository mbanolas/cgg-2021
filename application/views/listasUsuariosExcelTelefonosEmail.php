<?php ini_set('MAX_EXECUTION_TIME', '-1'); ?>

<style>
    body>div.container>div.container>form>div>div {
        margin-top: 26px;
    }

    .no-visible {
        visibility: hidden;
    }

    .radio {
        width: 140px;
        height: 34px;

        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 5px;
    }

    .fecha {
        width: 160px;
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

    .sin_radio {
        width: 140px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;

        margin-right: 5px;
    }

    .profesional {
        color: blue;
    }
</style>

<div class="container"><br />

    <?php //echo form_open('generarListasUsuarios/listaExcel', array('class' => "form-horizontal", 'role' => "form")); 
    ?>
    <?php echo form_open('generarListasUsuarios/emitirListaUsuarios', array('class' => "form-horizontal", 'role' => "form"));
    ?>

    <input class="hide" type="text" id="texto_titulo" name="texto_titulo" value="">
    <input class="hide" type="text" id="tipoTaller" name="tipoTaller" value="<?php echo $tipoTaller; ?>">
    <input class="hide" type="text" id="periodoInicial" name="periodoInicial" value="<?php echo $periodo; ?>">
    <input class="hide" type="text" id="periodoInicialTexto" name="textoPeriodo" value="<?php echo $textoPeriodo; ?>">
    <div class="container">
        <h3>Preparar llistat usuaris/usuàries inscrits a tallers amb telèfon i/o e-mail i indicació inscripcions</h3>

        <div class="row">
            <div class="form-group col-sm-1">
                <label class="control-label col-sm-1" for="curso">Curs:</label>
            </div>
            <div class="col-sm-2">
                <?php echo form_dropdown('curso', $optionsCursos, 0, array('class' => 'form-control', 'id' => 'cursos')); ?>
            </div>
            <div class="col-sm-2 radio">
                <input style="margin-left: 0px;" type="radio" id="C" checked="checked" name="periodoCheckbox[]" value="C"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Todo el curso
            </div>
            <div class="col-sm-2 radio">
                <input style="margin-left: 0px;" type="radio" id="T1" name="periodoCheckbox[]" value="T1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
            </div>
            <div class="col-sm-2 radio">

                <input style="margin-left: 0px;" type="radio" id="T2" name="periodoCheckbox[]" value="T2"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2
            </div>
            <div class="col-sm-2 radio">

                <input style="margin-left: 0px;" type="radio" id="T3" name="periodoCheckbox[]" value="T3"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3
            </div>
        </div>
        <div class="row" style="margin-top:0px">
            <div class="form-group col-sm-1 ">
                <label class="control-label col-sm-1" for="curso">Tallers:</label>
            </div>
            <div class="col-sm-2 no-visible">
            </div>
            <div class="col-sm-2 radio">
                <input style="margin-left: 0px;" type="radio" checked="checked" name="tipoTaller[]" value="tots"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tots
            </div>
            <div class="col-sm-2 radio">
                <input style="margin-left: 0px;" type="radio" name="tipoTaller[]" value="professionals"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professionals
            </div>
            <div class="col-sm-2 radio">
                <input style="margin-left: 0px;" type="radio" name="tipoTaller[]" value="voluntaris"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Voluntaris
            </div>
        </div>
        <h3 id="titulo">Curso <span id="textoCurso"><?php echo ucwords($tipoTaller) ?></span> Tallers <span id="textoTipoTaller"><?php echo ucwords($tipoTaller) ?></span> - Periodo <span id="textoPeriodo"><?php echo $textoPeriodo; ?></span> </h3>
        <div>
            <button type="submit" class="btn btn-success listasExcel" id="listasExcel" name="listados">
                <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
                Gererar Excel &nbsp;&nbsp;<img class="ajax-loader1 hide" src="<?php echo base_url('images/ajax-loader-2.gif') ?>"></button>
            <span id="numRegistros"> </span>
        </div>

    </div>





</div>
</div>






<?php echo form_close();
?>

</div>





<script>
    $(document).ready(function() {
        $('input[name="tipoTaller[]"]').change(function() {
            $('#numRegistros').html("")
            $('#bajarExcel').addClass('hide')
            $('#bajarExcel1').addClass('hide')
            $('#bajarExcel2').addClass('hide')
        })
        $('input[name="periodoCheckbox[]"]').change(function() {
            $('#numRegistros').html("")
            $('#bajarExcel').addClass('hide')
            $('#bajarExcel1').addClass('hide')
            $('#bajarExcel2').addClass('hide')
        })
        $('#cursos').change(function() {
            $('#numRegistros').html("")
            $('#bajarExcel').addClass('hide')
            $('#bajarExcel1').addClass('hide')
            $('#bajarExcel2').addClass('hide')
        })

        var tipoTaller = $('#tipoTaller').val()

        var periodo = $('input#periodoInicial').val()
        //alert(periodo)
        function parseUrl(url) {
            var a = document.createElement('a');
            a.href = url;
            return a;
        }


        $('#bajarExcel').click(function() {
            $('#boton_bajada').val(0)
        })
        $('#bajarExcel1').click(function() {
            $('#boton_bajada').val(1)
        })
        $('#bajarExcel2').click(function() {
            $('#boton_bajada').val(2)
        })

        $('#listasExcel').click(function(e) {
            // e.preventDefault()
            var titulo = $('#titulo').text()
            $('#texto_titulo').val(titulo)
            // alert(titulo)
            var textoCurso = $('option[value="' + $('#cursos').val() + '"').html()

            var periodoInicial = $('#periodoInicial').val()
            var curso = $('#cursos').val()
            var tipoTaller = $('#tipoTaller').val()
            var titulo = "hola" //parseUrl($('#titulo').html())
            var texto_titulo = encodeURIComponent($('#titulo').text())
            $('#texto_titulo').val(texto_titulo)
            console.log(periodoInicial)
            console.log(curso)
            console.log(tipoTaller)
            console.log(titulo)
            // window.location.href = "<?php echo base_url() ?>index.php/generarListasUsuarios/listaUsuarios/" + periodoInicial + "/" + curso + "/" + tipoTaller + "/" + titulo + "/" + texto_titulo
            $('.ajax-loader1').removeClass('hide')
            setTimeout(
                function() {
                    $('.ajax-loader1').addClass('hide')
                }, 3000);

            return;





            var periodoInicial = $('#periodoInicial').val()
            var curso = $('#cursos').val()
            var tipoTaller = $('#tipoTaller').val()
            var titulo = $('#titulo').html()
            $('#numRegistros').html("")
            $('#bajarExcel').addClass('hide')
            $('#bajarExcel1').addClass('hide')
            $('#bajarExcel2').addClass('hide')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/generarListasUsuarios/listaUsuarios",
                data: {
                    periodoInicial: periodoInicial,
                    curso: curso,
                    tipoTaller: tipoTaller,
                    texto_titulo: titulo
                },
                success: function(datos) {
                    $('.ajax-loader1').addClass('hide')
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    //alert(datos['inicio'])
                    var numRegistros = datos
                    $('#numRegistros').html(datos + ' registres trobats')
                    if (datos == 0) {
                        $('#textoBoton').html("Baixar excel ")
                        $('#bajarExcel').removeClass('hide')
                        return
                    }
                    if (datos > 300) {
                        $('#textoBoton').html("Baixar excel registres 1 a 300")
                        $('#bajarExcel').removeClass('hide')
                    } else {
                        $('#textoBoton').html("Baixar excel registres 1 a " + numRegistros)
                        $('#bajarExcel').removeClass('hide')
                        return
                    }
                    if (datos > 600) {
                        $('#textoBoton1').html("Baixar excel registres 301 a 600")
                        $('#bajarExcel1').removeClass('hide')
                    } else {
                        $('#textoBoton1').html("Baixar excel registres 301 a " + numRegistros)
                        $('#bajarExcel1').removeClass('hide')
                        return
                    }
                    if (datos > 600) {
                        $('#textoBoton2').html("Baixar excel registres 601 a " + numRegistros)
                        $('#bajarExcel2').removeClass('hide')
                        return
                    }
                },
                error: function() {
                    alert("Error en el proceso enerarListasUsuarios/listaUsuarios. Informar");
                }
            })

        })







        $('input[name="tipoTaller[]"]').click(function() {
            tipoTaller = $(this).val()
            $('input#tipoTaller').val(tipoTaller)
            $('span#textoTipoTaller').html(tipoTaller)
        })

        //al entrar, se selecciona por omisión, el último curso 
        //$('select#cursos option').last().attr('selected','selected')

        $('input[name="periodoCheckbox[]"]').click(function() {
            periodo = $(this).val()
            //alert(periodo)
            $('input#periodoInicial').val(periodo)
            obtenerTextosCursoPeriodo()
        })






        //al cargar página lee report


        // function obtenerTalleres() {

        //     $('#tablaResumen').html("");
        //     var curso = $('select#cursos').val()
        //     //alert('periodo '+periodo)
        //     //alert('curso '+curso)
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?php echo base_url() ?>" + "index.php/talleres/getTablaTalleresListas",
        //         data: {
        //             periodo: periodo,
        //             curso: curso,
        //             tipoTaller: tipoTaller
        //         },
        //         success: function(datos) {
        //             //alert(datos)
        //             var datos = $.parseJSON(datos);
        //             //alert(datos[0]['nombre'])

        //             $('#textoCurso').html(datos['textoCurso'])
        //             $('#textoPeriodo').html(datos['textoPeriodo'])
        //             $('#tablaResumen').append(datos)

        //         },
        //         error: function() {
        //             alert("Error en el proceso obtener Talleres. Informar");
        //         }
        //     })
        // }


        // obtenerTextosCursoPeriodo()

        function obtenerTextosCursoPeriodo() {

            $('#tablaResumen').html("");
            var curso = $('select#cursos').val()
            //alert('periodo '+periodo)
            //alert('curso '+curso)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/getTextosCursoPeriodo",
                data: {
                    periodo: periodo,
                    curso: curso,
                    tipoTaller: tipoTaller
                },
                success: function(datos) {
                    //alert(datos)
                    var datos = $.parseJSON(datos);
                    //alert(datos['inicio'])

                    $('#textoCurso').html(datos['textoCurso'])
                    $('#textoPeriodo').html(datos['textoPeriodo'])
                    $('input#periodoInicialTexto').val(datos['textoPeriodo']);
                    $('input#periodoInicial').val(periodo);

                },
                error: function() {
                    alert("Error en el proceso obtener Fechas. Informar");
                }
            })
        }

        // function obtenerFechas() {
        //     borrarFechas()
        //     $('#tablaResumen').html("");
        //     var curso = $('select#cursos').val()
        //     //alert('periodo '+periodo)
        //     //alert('curso '+curso)
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?php echo base_url() ?>" + "index.php/talleres/getFechasPeriodo",
        //         data: {
        //             periodo: periodo,
        //             curso: curso,
        //             tipoTaller: tipoTaller
        //         },
        //         success: function(datos) {
        //             //alert(datos)
        //             var datos = $.parseJSON(datos);
        //             //alert(datos['inicio'])
        //             $('input[name="inicio"').val(datos['inicio'])
        //             $('input[name="finaliza"').val(datos['finaliza'])
        //             for (var i = 0; i < 12; i++) {
        //                 $('input[name="festivo' + i + '"]').val(datos['festivo' + i])
        //             }


        //         },
        //         error: function() {
        //             alert("Error en el proceso obtener Fechas. Informar");
        //         }
        //     })
        // }

        // function borrarFechas() {
        //     $('input[name="inicio"').val("")
        //     $('input[name="finaliza"').val("")
        //     for (var i = 0; i < 12; i++) {
        //         $('input[name="festivo' + i + '"]').val("")
        //     }
        // }

        // $('input[name="inicio"], input[name="finaliza"], input[name="festivo0"], input[name="festivo1"], input[name="festivo2"], input[name="festivo3"], input[name="festivo4"], input[name="festivo5"], input[name="festivo6"],input[name="festivo7"], input[name="festivo8"], input[name="festivo9"], input[name="festivo10"], input[name="festivo11"]').change(function() {
        //     var nombre = $(this).attr('name')
        //     var valor = $(this).val()
        //     //console.log(valor)
        //     var curso = $('select#cursos').val()
        //     //alert('input[name="inicio"] '+nombre+' '+valor)
        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url() ?>" + "index.php/talleres/grabarFechas",
        //         data: {
        //             curso: curso,
        //             periodo: periodo,
        //             nombre: nombre,
        //             valor: valor
        //         },
        //         success: function(datos) {},
        //         error: function() {
        //             alert('Error en la grabación fechas. Informar al Administrador')
        //         },
        //     })
        // })

        $('select#cursos').change(function() {
            obtenerTextosCursoPeriodo()
        })

        $('input#C').click()

        $('#bajarExcel_').click(function(e) {
            e.preventDefault()
            console.log('#bajarExcel')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/generarListasUsuarios/listaExcel",
                data: {

                },
                success: function(datos) {
                    console.log(datos)
                    var datos = $.parseJSON(datos);
                    //alert(datos['inicio'])



                },
                error: function() {
                    alert("Error en el proceso obtener Fechas. Informar");
                }
            })

        })

    })
</script>