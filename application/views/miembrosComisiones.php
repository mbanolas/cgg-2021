
<h3> Membres Comissions </h3>
<?php $optionsSocios = array() ?>
<div class="col-sm-4"> 

    <?php
    $nombreComisiones = array('Seleccionar comissió');
    foreach ($comisiones as $k => $v) {
        $nombreComisiones[] = $v['nombre_comision'];
    }
    echo form_dropdown('comisiones', $nombreComisiones, null, array('class' => 'form-control ', 'id' => 'comisiones'));
    ?>
</div>
<br>
<br>

<div class="col-sm-12 hide" id="datosComision"> 
    <h4 id="comision">  </h4>
    <p style="font-size: 18px">Llista membres &nbsp;&nbsp;&nbsp;&nbsp; <a href="#" class="anadirMiembro">Afegir membre comissió</a></p>
    
    <div class="form-group hide" id="usuarios">
        <label class="control-label col-sm-2" for="socio">Usuari/Usuària:</label>
        <div class="col-sm-2"> 
            <?php echo form_input(array('class' => 'clearable form-control searchable-input', 'name' => 'buscarSocio', 'id' => 'buscarSocio', 'placeholder' => 'Cercar usuari/usuària')); ?>
        </div>
        <div class="col-sm-1"> 
            <button class="btn  btn-default"  id="buscarSocios"> Cercar</button>
            <button class="btn  btn-default  buscando hide"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cercant...</button>
        </div>
        <div class="col-sm-3"> 
            <?php echo form_dropdown('socio', $optionsSocios, null, array('class' => 'form-control hide socios', 'id' => 'socios')); ?>
        </div>
        <div class="col-sm-3"> 
            <button class="btn  btn-default socios hide" id="anadirMiembro" > Afegir membre</button>
        </div>
        
        <div class="col-sm-3"> 
        </div>
        
    </div>

    <div class="col-sm-12" id="miembros"></div>
</div>    
<style>
    td{
        font-size: 18px;

    }
    tr>td:nth-child(1){
        min-width: 350px;
    }

    tr>td:nth-child(2){
        min-width: 150px;
    }
    
    .clearable{
        background: #fff url(http://i.stack.imgur.com/mJotv.gif) no-repeat right -10px center;
        border: 1px solid #999;
        padding: 3px 18px 3px 4px;     /* Use the same right padding (18) in jQ! */
        border-radius: 3px;
        /*transition: background 0.4s;*/
    }
    .clearable.x  { background-position: right 5px center; } /* (jQ) Show icon */
    .clearable.onX{ cursor: pointer; }              /* (jQ) hover cursor style */
    .clearable::-ms-clear {display: none; width:0; height:0;} /* Remove IE default X */   

     .glyphicon-refresh-animate {
        -animation: spin .7s infinite linear;
        -webkit-animation: spin2 .7s infinite linear;
    }

    @-webkit-keyframes spin2 {
        from { -webkit-transform: rotate(0deg);}
        to { -webkit-transform: rotate(360deg);}
    }

    @keyframes spin {
        from { transform: scale(1) rotate(0deg);}
        to { transform: scale(1) rotate(360deg);}
    }

    
</style>

<script>
     $(document).ready(function () {

        $('.anadirMiembro').click(function(){
            $('#usuarios').removeClass('hide')
        })
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

        
        // CLEARABLE INPUT
        function tog(v) {
            return v ? 'addClass' : 'removeClass';
        }
        $(document).on('input', '.clearable', function () {
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function (e) {
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function (ev) {
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border', '1px solid #ccc')
            $('.socios').addClass('hide');
            // filtroSocios(" ")
        });

        $('input.searchable-input_').keyup(function () {
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

        $('input.searchable-input').click(function () {
            $('.socios').addClass('hide');
        })

        $('#buscarSocios').click(function (e) {
            $('#inscripcionesTalleres').attr('disabled','disabled')
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

        $('.buscando').click(function (e) {
            e.preventDefault()
        })

        function sortSelectOptions(selectElement) {
            var options = $(selectElement + " option");
            options.sort(function (a, b) {
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

      $('#buscarSocio').click(function(){
            $('#inscripcionesTalleres').attr('disabled','disabled')
        })

        function filtroSocios(filtro) {
            $('.buscando').removeClass('hide')
            $('#buscarSocios').addClass('hide')
            
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/getSociosFiltro",
                data: {filtro: filtro},
                success: function (datos) {
                    $('select#socios option').remove();
                    //alert(datos)
                    if (datos == 'false') {
                        //alert('hola')
                        $('#socios').append('<option value="0">No existen socios</option>')
                        $('#inscripcionesTalleres').attr('disabled','disabled')
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                        $('.socios').removeClass('hide');
                    } else {
                        var datos = $.parseJSON(datos);
                        $('#inscripcionesTalleres').removeAttr('disabled')
                        $.each(datos, function (index, value) {
                            var option = '<option value="' + index + '">' + value + '</option>'
                            $('#socios').append(option)
                        })
                        sortSelectOptions('#socios')
                        $('.socios').removeClass('hide');
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                    }
                },
                error: function () {
                    alert("Error en el proceso getSociosFiltro. Informar");
                }
            })
        }

    })

    
    
    
    
    $(document).ready(function () {
        var idComision = 0

        function ponerComision(idComision) {
            $('#usuarios').addClass('hide')
            $('input#buscarSocio').val('')
            $('.socios').addClass('hide')
             $('input#buscarSocio').removeClass('onX')
            $('input#buscarSocio').removeClass('x')
            $('input.searchable-input').css('border', '1px solid #ccc')
            
            $('#datosComision').addClass('hide')
            //idComision=$('#comisiones').val()
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/comisiones/getDatosComision",
                data: {idComision: idComision},
                success: function (datos) {
                    var datosJSON = $.parseJSON(datos);
                    if (datosJSON.length == 0) {
                        $('#miembros').html('No hi ha membres encara')
                        $('#datosComision').removeClass('hide')
                    } else {
                        $('#comision').html(datosJSON[0]['nombre_comision'])
                        $('#datosComision').removeClass('hide')
                        var tabla = "<table class=table table-striped'>"
                        tabla += "<thead><tr><th>Nom</th><th>Telèfon</th><th>e-mail</th></tr></thead><tbody>"
                        $.each(datosJSON, function (index, value) {
                            var telefono = value['telefono_1']
                            telefono = telefono.substr(0, 3) + ' ' + telefono.substr(3, 3) + ' ' + telefono.substr(6)
                            tabla += "<tr scope='row'><td>" + value['nombre'] + ' ' + value['apellidos'] + "</td>"
                            tabla += "<td>" + telefono + "</td>"
                            tabla += "<td>" + value['email'] + "</td>"
                            //var href="<?php echo base_url() ?>"+"index.php/comisiones/eliminarMiembro/"+value['id_comision']+"/"+value['id_socio']
                            tabla += "<td>" + "<a href='#' class='eliminar' id_socio='" + value['id_socio'] + "'>Eliminar</a>" + "</td>"
                            tabla + "</tr>"
                        })
                        tabla += "</tbody></table>"
                        $('#miembros').html(tabla)
                    }
                },
                error: function () {
                    alert("Error en el proceso de registrar ultimo periodo. Informar");
                }
            })
        }

        $('#comisiones').change(function () {
            idComision = $('#comisiones').val()
            ponerComision(idComision)
        })

        $('body').delegate('.eliminar', 'click', function () {
            var idSocio = $(this).attr('id_socio')
            $.ajax({
                url: "<?php echo base_url() ?>" + "index.php/comisiones/eliminarMiembro/" + idComision + "/" + idSocio,
                success: function (datos) {
                    //alert(datos)
                    ponerComision(idComision)
                },
                error: function () {
                    alert("Error en el proceso de registrar ultimo periodo. Informar");
                }
            })
        })
        
        $('#anadirMiembro').click(function(){
        //alert($('select#socios').val())
        var idSocio=$('select#socios').val()
        $.ajax({
                url: "<?php echo base_url() ?>" + "index.php/comisiones/ponerMiembro/" + idComision + "/" + idSocio,
                success: function (datos) {
                    //alert(datos)
                    ponerComision(idComision)
                    
                },
                error: function () {
                    alert("Error en el proceso de registrar ultimo periodo. Informar");
                }
            })
        })


    })
</script>    