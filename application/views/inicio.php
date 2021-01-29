<div class='container'>
    <h3>Benvingut!</h3>
    <h3>Benvinguda!</h3>
    <h3 id="nombre"><?php echo $this->session->nombre ?></h3>
    <?php //echo md5('gentgran').' '.md5('adminGloria');
    ?>

    <input type="hidden" id="cumplen90" value="<?php echo $cumplen90; ?>">
    <input type="hidden" id="cumplen90proximos30dias" value="<?php echo $cumplen90proximos30dias; ?>">
    <input type="hidden" id="cumpleanos" value="<?php echo $cumpleanos; ?>">


    <h3><?php echo $this->session->tipoUsuario ?></h3>
    <br>
    <!-- <h4>Programa en fase de proves. En cas de trobar algun error, informar indicant acció i missatge d'error si n'hi ha. Gràcies.</h4> -->


    <hr>
    <!--
<div style="color: red">
<h3>Aviso:</h3><h3>Esta aplicación está en desarrollo. Algunas partes no están completadas o no han sido verificadas. Por favor tenerlo en consideración.</h3>
<h3>Gracias.</h3>
</div>
-->
</div>

<script>
    $(document).ready(function() {

        // al iniciar se calcula ancho pantalla y envía email a usuers category !=10
        function informarAnchoPantalla() {
            var ancho = $(window).width()
            var alto = $(window).height()
            //ajax para enviar información con ancho pantalla
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/" + "inicio/sizePantallaEmail",
                data: {
                    ancho: ancho,
                    alto: alto
                },
                success: function(datos) {
                    // alert(datos);
                    var datos = $.parseJSON(datos);
                    // alert(datos);
                },
                error: function() {
                    alert('Error proceso inicio. Informar ');
                }
            });
        }

        informarAnchoPantalla()

        var cumplen90 = $('#cumplen90').val();
        var cumpleanos = $('#cumpleanos').val();
        /*
          $('.modal-title').html('Información Cumplen 90 años este año <br>(sólo disponemos del año de nacimiento')
                  $('.modal-body>p').html(cumplen90)
                  $("#myModal").modal()
            */
        var cumplen90proximos30dias = $('#cumplen90proximos30dias').val();

        $('.modal-title').html('Información. En los próximos 30 días o durante este año<br>(para los que sólo disponemos del año de nacimiento)<br>cunplirán su 90 aniversario, los siguientes socios:')
        $('.modal-body>p').html(cumplen90 + '<br>' + cumplen90proximos30dias)
        //$("#myModal").modal()



        if (cumpleanos == "") {
            $('.modal-title2').html('Información. Hoy NO cumple años ningún socio: <br>')
            $('.modal-body2>p').html(cumpleanos)
            //$("#myModal2").modal()
        } else {
            $('.modal-title2').html('Información. Hoy cumplen años los siguientes socios: <br>¡Felicidades!')
            $('.modal-body2>p').html(cumpleanos)
            //$("#myModal2").modal()
        }


        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();

        var horas = today.getHours()
        var minutos = today.getMinutes()

        var segundos = today.getSeconds()

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        if (horas < 10) {
            horas = '0' + horas
        }
        if (minutos < 10) {
            minutos = '0' + minutos
        }

        today = yyyy + '-' + mm + '-' + dd + ' ' + horas + ':' + minutos

        var fecha = today


        var nombre = $('#nombre').html()






        /*
          $.ajax({
              type: 'POST',
              url: "<?php echo base_url() ?>"+"index.php/inicio/fechaMovimientoWeb", 
              data: {fecha:fecha,nombre:nombre},
              success: function(datos){
               //  alert(datos)
              //var d=$.parseJSON(datos)
              //alert(datos)
                },
               error: function(){
                        alert("Error en el proceso fechaMovimientoWeb. Informar");
               }
          })
         */
    })
</script>
<style>
    .modal-content {
        width: 800px;
        left: -52px;

    }

    h4 {
        color: blue;
    }
</style>