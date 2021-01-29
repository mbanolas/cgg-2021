<style>
    #tipoPago {
        margin-top: 10px;
    }
</style>
<div class="container">
    <h3>Sol·licitud Inscripcions a tallers</h3>
    <hr>
    <?php //var_dump($inscripcion['datosListaEspera']) 
    ?>
    <?php //var_dump($inscripcion['listaEsperaTaller']) 
    ?>
    <?php //var_dump($inscripcion['listaEsperaOrden']) 
    ?>

    <?php echo form_open('talleres/recibosInscripcionesNuevo', array('id' => 'form')); ?>

    <?php echo form_hidden('resultNuevo', $value = $inscripcion['resultNuevo']); ?>
    <?php echo form_hidden('cursoNombre', $value = $datosComunes['cursoNombre']); ?>
    <?php echo form_hidden('periodo', $value = $datosComunes['periodo']); ?>
    <?php echo form_hidden('socioNombre', $value = $datosComunes['socioNombre']); ?>
    <?php echo form_hidden('curso', $value = $datosComunes['curso']); ?>
    <?php echo form_hidden('socio', $value = $datosComunes['socio']); ?>
    <?php echo form_hidden('tarjetaRosa', $value = $datosComunes['tarjetaRosa']); ?>


    <?php echo form_hidden('listaEsperaTaller', $value = $inscripcion['listaEsperaTaller']); ?>
    <?php echo form_hidden('listaEsperaOrden', $value = $inscripcion['listaEsperaOrden']); ?>
    <?php echo form_hidden('listaEsperaPeriodo', $value = $inscripcion['listaEsperaPeriodo']); ?>
    <?php echo form_hidden('C', $value = $inscripcion['C']); ?>
    <?php echo form_hidden('T1', $value = $inscripcion['T1']); ?>
    <?php echo form_hidden('T2', $value = $inscripcion['T2']); ?>
    <?php echo form_hidden('T3', $value = $inscripcion['T3']); ?>
    <?php echo form_hidden('CNombres', $value = $inscripcion['CNombres']); ?>
    <?php echo form_hidden('trimestres', $value = $datosComunes['trimestres']); ?>
    <?php echo form_hidden('importes', $value = $inscripcion['importes']); ?>
    <?php echo form_hidden('id_talleres', $value = $inscripcion['id_talleres']); ?>
    <?php echo form_hidden('tipos_talleres', $value = $inscripcion['tipos_talleres']); ?>

    <?php echo form_hidden('cobroTarjeta', $value = ""); ?>
    <?php echo form_hidden('cobroMetalico', $value = ""); ?>


    <?php echo form_hidden('totalAPagar', $value = $inscripcion['totalAPagar']); ?>

    <h4>Curs: <?php echo $datosComunes['cursoNombre'] . ' - ' . $datosComunes['periodo']; ?></h4>

    <h4>Usuari/Usuària: <strong><?php echo $datosComunes['socioNombre']; ?></strong><?php if ($datosComunes['tarjetaRosa']) echo " - Tarjeta Rosa"; ?></h4>
    <?php if (count($inscripcion['CNombres']) > 1) { ?><h3>Talleres:</h3><?php } ?>
    <?php if (count($inscripcion['CNombres']) == 1) { ?><h3>Taller:</h3><?php } ?>
    <?php $salida = "<table>"; ?>
    <?php if (count($inscripcion['CNombres'])) {
        //var_dump($inscripcion);
        $nombres = array();
        $insc = array();
        foreach ($inscripcion['CNombres'] as $k => $v) {
            //log_message('INFO','========================='.$inscripcion['tipos_talleres'][$k]);
            if ($inscripcion['tipos_talleres'][$k] == 'Voluntari' && $datosComunes['trimestres'][$k] == 'T1') $datosComunes['trimestres'][$k] = 'C';
            $inscript = ' (' . $datosComunes['trimestres'][$k] . ')  ';
            if (!$datosComunes['trimestres'][$k]) $inscript = "";
            $insc[] = $inscript;
            //$nombres[]=$v. $insc."  Importe: ".$inscripcion['importes'][$k]." €"; 
        }
    ?>

    <?php } ?>
    <?php if (count($inscripcion['CNombres'])) {
        foreach ($inscripcion['CNombres'] as $k => $v) {
            $salida .= '<tr>';
            $tipoTaller = $inscripcion['tipos_talleres'][$k];
            $tipoTallerPago = $tipoTaller . 'Pago';
            $salida .= "<td width='400'>- $v<span > ($tipoTaller)</span></td>"
                . "<td width='100'>$insc[$k] </td>"
                . "<td width='90' style='text-align:right' class='$tipoTaller'>" . number_format($inscripcion['importes'][$k], 2) . "  € </td><td class='$tipoTallerPago'></td>"
                . "";
            $salida .= '</tr>';
        }
    } ?>

    <?php if (count($inscripcion['datosListaEspera'])) {
        $salida .= "<tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr>";
        $salida .= "<tr><td>En llista d'espera</td>";
        foreach ($inscripcion['datosListaEspera'] as $k => $v) {
            $salida .= '<tr>';
            $nombreTaller = $v['nombreTaller'];
            $orden = $v['orden'];
            $salida .= "<td width='400'>- $nombreTaller</td>"
                . "<td width='100'>" . " (" . $inscripcion['letraPeriodo'] . ") </td>"
                . "<td style='text-align:right'>ordre " . $orden . " </td>";
            $salida .= '</tr>';
        }
    } ?>

    <!--<h4> - <?php echo implode("<br> - ", $nombres); ?></h4>-->
    <?php $salida .= '</table>' ?>
    <?php echo $salida; ?>

    <h2>Total a Pagar: <?php echo $inscripcion['totalAPagar'] . ' €'; ?></h2>
    <h3 id="tipoPago">Metàl.lic</h3>
    <!-- <h4>(Metàl.lic: <span id="metalico"></span>; Targeta: <span id="tarjeta"></span>)</h4> -->
    <!--    
<div class="container">
    <div class="col-sm-4">
        <input type='checkbox' value='' name="cobroTarjetaVoluntarios" id="cobroTarjetaVoluntarios">
        <label for="cobroTarjetaVoluntarios" style="font-weight: normal;margin-bottom:0px"> Cobrament amb targeta Tallers Voluntaris</label>
    </div>
  
</div>
-->
    <div class="container">
        <div class="col-sm-4" style="margin-bottom:15px">
            <input style="margin-bottom:0px" name="cobroTarjetaProfesionales" type='checkbox' value='' id="cobroTarjetaProfesionales">
            <label for="cobroTarjetaProfesionales" style="font-weight: normal;"> Cobrament amb targeta</label>
        </div>

    </div>

    <div class="form-group">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success" id="emitirRecibo">Confirma, emetre rebut i cobrar</button>
            <button class="btn btn-default cancel-button " type="button" id="cancel-button">
                <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Cancel·lar</button>
        </div>
    </div>
    <?php echo form_close(); ?>

</div>
<style>
    td {
        padding: 2px;
    }

    hr {
        padding: 0px;
    }
</style>
<script>
    $(document).ready(function() {

        calculoTipoCobros()

        function calculoTipoCobros() {
            var tarjeta = 0
            var metalico = 0


            if ($('#cobroTarjetaVoluntarios').prop("checked")) {
                $('.Voluntari').each(function() {
                    tarjeta += parseFloat($(this).html())
                    // $('.VoluntariPago').html('Targeta')
                })
            } else {
                $('.Voluntari').each(function() {
                    metalico += parseFloat($(this).html())
                    // $('.VoluntariPago').html('')
                })
            }
            if ($('#cobroTarjetaProfesionales').prop("checked")) {
                $('.Professional').each(function() {
                    tarjeta += parseFloat($(this).html())
                    // $('.ProfessionalPago').html('Targeta')
                })
            } else {
                $('.Professional').each(function() {
                    metalico += parseFloat($(this).html())
                    // $('.ProfessionalPago').html('')
                })
            }

            //    $('#metalico').html(metalico+ ' €')
            //    $('#tarjeta').html(tarjeta+ ' €')
            console.log('tarjeta ',tarjeta)
            console.log('metalico ',metalico)
            if (tarjeta == 0) $('#tipoPago').html("Metàl.lic")
            if (metalico == 0) $('#tipoPago').html("Targeta")
            if (parseFloat(tarjeta) + parseFloat(metalico) == 0) {
                $('#tipoPago').html("")
                $('#cobroTarjetaProfesionales').addClass('hide')    
                $('#form > div.container > div > label').addClass('hide')    
            }

            $('input[name="cobroTarjeta"]').val(tarjeta)
            $('input[name="cobroMetalico"]').val(metalico)

        }

        $('#cobroTarjetaVoluntarios, #cobroTarjetaProfesionales').click(function() {
            calculoTipoCobros()
        })



        $('#emitirRecibo').click(function(e) {

            var pagado = <?php echo json_encode($_SESSION['pagado']); ?>;

            if (pagado) {
                alert('RECIBO YA PAGADO. NO se puede emitir duplicado')
                e.preventDefault()
            } else {
                $('#cancel-button').html('Altra inscripció')
                $('#cancel-button').removeClass('btn-default')
                $('#cancel-button').addClass('btn-success')
            }
        })

        $('#cancel-button').click(function() {
            //cambios=false
            window.location.href = "<?php echo base_url() ?>" + "index.php/talleres/seleccionar";
        })
        // window.onload = recibosInscripcion_;

        function recibosInscripcion() {
            alert('recibosInscripcion')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/recibosInscripciones",
                data: $('form').serialize(),
                success: function(datos) {

                    var datos = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso recibosInscripciones. Informar");
                }
            })




        }

        // alert('hola')
        //control cambios
        var cambios = true;
        window.onbeforeunload = confirmExit

        function confirmExit() {
            if (cambios) {
                return 'No ha emès els rebuts per cobrar.'
            }
        }



        $("#form").submit(function(event) {
            cambios = false;
        });

    })
</script>