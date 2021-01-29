    <?php $total = $sexos['otros'] + $sexos['hombres'] + $sexos['mujeres'];
    $porcientoOtros = 0;
    $porcientoHombres = 0;
    $porcientoMujeres = 0;
    if ($total != 0) {
        $porcientoOtros = number_format($sexos['otros'] * 100 / $total, 1);
        $porcientoHombres = number_format($sexos['hombres'] * 100 / $total, 1);
        $porcientoMujeres = number_format($sexos['mujeres'] * 100 / $total, 1);
    }

    ?>

    <div class="container"><br />
        <h3>Estadístiques usuaris usuàries per sexes</h3>
        
        <br />

        <?php echo form_open('estadisticas/pdfSexos', array('class' => "form-horizontal", 'role' => "form")); ?>
        <input type="hidden" name="prueba" value="maba">
        <div class="form-group">

            <label class="control-label col-sm-2" for="curso">Curs:</label>
            <div class="col-sm-2">

                <?php echo form_dropdown('curso', $optionsCursos, 0, array('class' => 'form-control', 'id' => 'cursos')); ?>
            </div>
            <!--
        <div class="col-sm-2 radio" style="border:0px">
            <?php if ($periodo == 7)
                $checked = "checked='checked'";
            else
                $checked = ""
            ?>       
            <input class="hide" style="margin-left: 0px;" type="radio" name="periodo[]" value="C" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>  
    -->
            <div class="col-sm-2 radio">
                <?php if ($periodo == 4)
                    $checked = "checked='checked'";
                else
                    $checked = ""
                ?>
                <input style="margin-left: 0px;" type="radio" name="periodo[]" value="T1" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
            </div>
            <div class="col-sm-2 radio">
                <?php if ($periodo == 2)
                    $checked = "checked='checked'";
                else
                    $checked = ""
                ?>
                <input style="margin-left: 0px;" type="radio" name="periodo[]" value="T2" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2
            </div>
            <div class="col-sm-2 radio">
                <?php if ($periodo == 1)
                    $checked = "checked='checked'";
                else
                    $checked = ""
                ?>
                <input style="margin-left: 0px;" type="radio" name="periodo[]" value="T3" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3
            </div>
            <div id='datosGraficos'>

            </div>
            <div class="col-sm-2 ">
                <button type="submit" class="btn btn-success hide" id="pdfEstadisticas" name="pdfEstadisticas">
                    <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
                    PDF Estadísticas sexes</a>
            </div>
        </div>
    </div>

    <?php echo form_close(); ?>
    </div>
    <div class="container">
        <div id="resumenTodosUsuarios">
            <h3>Distribució usuaris/usuàries (TOTS) per sexe (<?php echo $hoy; ?>)</h3>
            <table class="table table-striped" style="width:60%">
                <tr>
                    <th class="col-sm-4" style="text-align: left;">Sexe Desconegut:</th>
                    <th class="col-sm-2" style="text-align: right;"><?php echo $sexos['otros']; ?></th>
                    <th class="col-sm-2" style="text-align: right;"> <?php echo $porcientoOtros; ?> %</th>
                    <th rowspan="3" class="col-sm-2" style="text-align: right;"><span id="chart_div"></span></th>
                </tr>
                <tr>
                    <th class="col-sm-4" style="text-align: left;">Sexe Homes:</th>
                    <th class="col-md-3" style="text-align: right;"><?php echo $sexos['hombres']; ?></th>
                    <th class="col-md-3" style="text-align: right;"> <?php echo $porcientoHombres; ?> %</th>
                </tr>
                <tr>
                    <th class="col-sm-4" style="text-align: left;">Sexe Dones:</th>
                    <th class="col-md-2" style="text-align: right;"><?php echo $sexos['mujeres']; ?></th>
                    <th class="col-md-3" style="text-align: right;"> <?php echo $porcientoMujeres; ?> %</th>
                </tr>
                </tr>
                <tr>
                    <th class="col-sm-4">Total:</th>
                    <th class="col-sm-2" style="text-align: right;"><?php echo $total; ?></th>
                    <th class="col-sm-2"> </th>
                </tr>
                <!-- <tr>
                <th class="col-md-7" >Gràfic:</th>
                <th class="col-md-5" colspan="2"><span id="chart_div"></span></th>
            </tr>        -->
            </table>
        </div>
        <div id="resumenTodosUsuariosInscritos">

        </div>
    </div>
    </div>
    <style>
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

        th.numDerecha {
            text-align: right;
        }
    </style>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">

        function graficos() {
            // Load the Visualization API and the corechart package.
            google.charts.load('current', {
                'packages': ['corechart']
            });

            // Set a callback to run when the Google Visualization API is loaded.

            google.charts.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.



            function drawChart() {
                var otros = <?php echo $sexos['otros'] ?>;
                var hombres = <?php echo $sexos['hombres'] ?>;
                var mujeres = <?php echo $sexos['mujeres'] ?>;
                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                
                data.addRows([
                    ['Desconeguts', otros],
                    ['Homes', hombres],
                    ['Dones', mujeres]
                ]);

                // Set chart options
                var options = {
                    'height': 100,
                    'width': 200
                };

                // Instantiate and draw our chart, passing in some options.
                // var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                // chart.draw(data, options);

                
                    var graficoId = 'chart_div' 
                    // print png grafico https://developers.google.com/chart/interactive/docs/printing
                    var my_div = document.getElementById(graficoId);
                    var my_chart = new google.visualization.PieChart(my_div);

                    var graficoSexo = graficoId + '.png'
                    google.visualization.events.addListener(my_chart, 'ready', function() {
                        my_div.innerHTML = '<a class="' + graficoId + '" href="' + my_chart.getImageURI() + '"  download="' + graficoSexo + '"><img src="' + my_chart.getImageURI() + '"></a>';
                    });
                    my_chart.draw(data, options);

                    $("#datosGraficos").append("<input type='hidden' name='"+graficoId+"' value='"+$('.'+graficoId).attr('href')+"'>");
    



                var taller = ""
                var data = new google.visualization.DataTable();
                var otros = parseInt($('#ins_otros' + taller).html())
                var hombres = parseInt($('#ins_hombres' + taller).html())
                var mujeres = parseInt($('#ins_mujeres' + taller).html())
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows([
                    ['Desconeguts', otros],
                    ['Homes', hombres],
                    ['Dones', mujeres]
                ]);
                var graficoId = 'chart_div2' 
                // print png grafico https://developers.google.com/chart/interactive/docs/printing
                var my_div = document.getElementById(graficoId);
                var my_chart = new google.visualization.PieChart(my_div);

                var graficoSexo = graficoId + '.png'
                    google.visualization.events.addListener(my_chart, 'ready', function() {
                        my_div.innerHTML = '<a class="' + graficoId + '" href="' + my_chart.getImageURI() + '"  download="' + graficoSexo + '"><img src="' + my_chart.getImageURI() + '"></a>';
                    });
                my_chart.draw(data, options);

                $("#datosGraficos").append("<input type='hidden' name='"+graficoId+"' value='"+$('.'+graficoId).attr('href')+"'>");




                $(".taller").each(function(index) {
                    // console.log(index + ": " + $(this).attr('taller'));
                    var taller = $(this).attr('taller')



                    // Create the data table.
                    var data = new google.visualization.DataTable();
                    var otros = parseInt($('#ins_otros' + taller).html())
                    var hombres = parseInt($('#ins_hombres' + taller).html())
                    var mujeres = parseInt($('#ins_mujeres' + taller).html())
                    data.addColumn('string', 'Topping');
                    data.addColumn('number', 'Slices');
                    data.addRows([
                        ['Desconeguts', otros],
                        ['Homes', hombres],
                        ['Dones', mujeres]
                    ]);
                    var graficoId = 'chart_div2' + taller
                    // print png grafico https://developers.google.com/chart/interactive/docs/printing
                    var my_div = document.getElementById(graficoId);
                    var my_chart = new google.visualization.PieChart(my_div);

                    var graficoSexo = graficoId + '.png'
                    google.visualization.events.addListener(my_chart, 'ready', function() {
                        my_div.innerHTML = '<a class="' + graficoId + '" href="' + my_chart.getImageURI() + '"  download="' + graficoSexo + '"><img src="' + my_chart.getImageURI() + '"></a>';
                    });
                    my_chart.draw(data, options);

                    // console.log(graficoId)

                    $("#datosGraficos").append("<input type='hidden' name='"+graficoId+"' value='"+$('.'+graficoId).attr('href')+"'>");






                    // console.log($('.'+graficoId).attr('href'))

                    

                });


            }
        }


        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
    </script>


    <script>
        $(document).ready(function() {
            //cursos()
            $('select#cursos option').first().attr('selected', 'selected')

            var curso = $('select#cursos').val()

            var periodo = $('input[name="periodo[]"]:checked').val()



            $('select#cursos').change(function() {
                curso = $('select#cursos').val()
                $('#tablaAsistentes').html('')
                ponerTablaUsuariosInscritosSexos(curso, periodo)
            })


            function ponerTablaUsuariosInscritosSexos(curso, periodo) {
                // alert('curso '+curso)
                // alert('periodo '+periodo)

                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url() ?>" + "index.php/estadisticas/getTablaUsuariosInscritosSexos",
                    data: {
                        periodo: periodo,
                        curso: curso
                    },
                    success: function(datos) {


                        var datos = $.parseJSON(datos);
                        //alert(datos)
                        $('#resumenTodosUsuariosInscritos').html(datos);
                        graficos()
                        $('#pdfEstadisticas').removeClass('hide')
                    },
                    error: function() {
                        alert("Error en el proceso getTablaReservasTaller PonerTablaReservas. Informar");
                    }

                })
            }

            ponerTablaUsuariosInscritosSexos(curso, periodo)

            $('input[name="periodo[]"]').click(function() {
                $('#pdfEstadisticas').addClass('hide')
                $('#tablaAsistentes').html("");

                periodo = $(this).val()
                //alert(periodo)
                //alert(periodo)
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url() ?>" + "index.php/talleres/setUltimoPeriodo",
                    data: {
                        periodo: periodo
                    },
                    success: function(datos) {
                        //alert(datos)
                        //var datosJSON=$.parseJSON(datos);
                        ponerTablaUsuariosInscritosSexos(curso, periodo)
                        return
                    },
                    error: function() {
                        alert("Error en el proceso de registrar ultimo periodo. Informar");
                    }
                })
            })



            function cursos() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url() ?>" + "index.php/estadisticas/getTablaUsuariosInscritosSexos",
                    data: {
                        curso: curso,
                        periodo: periodo
                    },
                    success: function(datos) {
                        //alert(datos)
                        var datos = $.parseJSON(datos);

                        $('select#talleres option').remove();
                        if (datos.length == 0)
                            $('#talleres').append('<option value="' + 0 + '">' + 'No hi ha cap taller registrat' + '</option>')
                        $('#talleres').append('<option value="' + 0 + '">' + '- Seleccionar un taller' + '</option>')
                        $.each(datos, function(index, value) {
                            var id = value['id']
                            var nombre = value['nombre']
                            var option = '<option value="' + id + '">' + nombre + '</option>'
                            $('#talleres').append(option)
                        })
                        sortSelectOptions('#talleres')
                        $('select#talleres option[value="0"').attr('selected', 'selected')
                    },
                    error: function() {
                        alert("Error en el proceso get Talleres. Informar");
                    }
                })
            }

            document.body.addEventListener("load", function() {
                console.log('loading')
            }, false)
            // $('#pdfEstadisticas').click(function() {
            //     alert('hola..')
            //     $('th').delegate('a#chart_div2123', 'click', function(e) {
            //         alert('hhola')
            //     })
            //     alert($('a#chart_div2123').attr('id'))
            //     alert('hola2')
            // })

        })
    </script>