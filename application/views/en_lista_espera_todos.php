<div class="container"><br />
    <h3>Llistat usuaris/usuàries en llista espera</h3>
    <br />

    <?php echo form_open('talleres/pdfReservasTodos', array('class' => "form-horizontal", 'role' => "form")); ?>
    <div class="form-group">

        <label class="control-label col-sm-1 text-left" for="curso">Curs:</label>
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
        <div class="col-sm-2">
    <button type="submit" class="btn btn-success hide" id="pdfReservasSinTelefono" name="pdfReservasSinTelefono">
        <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
        PDF SENSE telèfon</button>
</div>
<div class="col-sm-2">
    <button type="submit" class="btn btn-success hide" id="pdfReservasSin" name="pdfReservasSin">
        <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
        PDF AMB telèfon</button>
</div>



    </div>
    <div class="col-sm-12">
        
    <div class="col-sm-4 hide orden">
            <!-- <?php echo form_radio('orden', "ordenOrden", true, array('class' => '', 'id' => 'ordenOrden')); ?> Ordenar per nombre ordre -->
        </div>


        <div class="col-sm-3 hide orden">
            <?php echo form_radio('orden', "ordenOrden", true, array('class' => '', 'id' => 'ordenOrden')); ?> Ordenar per nombre ordre
        </div>
        <div class="col-sm-2 hide orden">
            <?php echo form_radio('orden', "ordenNombre", false, array('class' => '', 'id' => 'ordenNombre')); ?> Ordenar per Nom
        </div>
        <div class="col-sm-3 hide orden">
            <?php echo form_radio('orden', "ordenNumSocio", false, array('class' => '', 'id' => 'ordenNumSocio')); ?> Ordenar per Núm usuari/usuària
        </div>
    </div>
</div>

<div class="col-sm-6"></div>




<?php echo form_close(); ?>
</div>
<div class="container">
    <div class="col-sm-8">
        <div id="tablaAsistentes">

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

    /* Remove IE default X */

    th.numDerecha {
        text-align: right;
    }
    .orden{
        padding-left:0px;
        padding-right:0px;
    }
    tr,td{
        padding:8px !important;
    }
    .linea >td{
        padding:2px !important;
        background-color: white;
    }
</style>

<script>
$(document).ready(function() {

    // por omisión se selecciona el ultimo curso introducido
    $('select#cursos option').first().attr('selected', 'selected')

    // inicialización pagina con datos seleccionados por omisión
    var curso = $('select#cursos').val()

    var periodo = $('input[name="periodo[]"]:checked').val()

    var orden = 0;

    // alert('curso '+curso)
    // alert('periodo '+periodo)
    // alert('orden '+orden)

    ponerTableReservas(curso, orden)



    $('#ordenOrden').change(function() {
        curso = $('select#cursos').val()
        orden = 0
        ponerTableReservas(curso, orden)
    })

    $('#ordenNombre').change(function() {
        curso = $('select#cursos').val()
        orden = 1
        ponerTableReservas(curso, orden)
    })

    $('#ordenNumSocio').change(function() {
        curso = $('select#cursos').val()
        orden = 2
        ponerTableReservas(curso, orden)
    })



    $('input[name="periodo[]"]').click(function() {
        $('#pdfReservasSin').addClass('hide')
        $('#pdfReservasCon').addClass('hide')
        $('#pdfReservasSinTelefono').addClass('hide')
        $('#tablaAsistentes').html("");
        $('.orden').addClass('hide')

        periodo = $(this).val()
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
                ponerTableReservas(curso, orden)
                return
            },
            error: function() {
                alert("Error en el proceso de registrar ultimo periodo. Informar");
            }
        })
    })

    $('select#cursos').change(function() {
        curso = $('select#cursos').val()
        $('#tablaAsistentes').html('')
        ponerTableReservas(curso, orden)
    })



    function ponerTableReservas(curso, orden) {
        // alert('curso '+curso)
        // alert('periodo '+periodo)
        // alert('orden '+orden)

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() ?>" + "index.php/talleres/getTablaReservasCurso",
            data: {
                periodo: periodo,
                orden: orden,
                curso: curso
            },
            success: function(datos) {


                var datos = $.parseJSON(datos);
                //alert(datos)
                $('#tablaAsistentes').html(datos);
                $('.orden').removeClass('hide')
                $('#pdfReservasSinTelefono').removeClass('hide')
                $('#pdfReservasSin').removeClass('hide')
            },
            error: function() {
                alert("Error en el proceso getTablaReservasTaller PonerTablaReservas. Informar");
            }

        })
    }

    



   


    

    

    


    
})
</script>