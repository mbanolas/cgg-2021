<style>
    #talleres {
        margin-left: 50px;
    }

    .direcciones {
        margin-left: 50px;
        color: blue;
    }

    /* #direcciones2 {
        margin-left: 50px;
    } */
</style>
<script src="http://localhost:8888/casal_gent_gran/assets/grocery_crud/texteditor/ckeditor/ckeditor.js"></script>
<script src="http://localhost:8888/casal_gent_gran/assets/grocery_crud/texteditor/ckeditor/adapters/jquery.js"></script>
<script src="http://localhost:8888/casal_gent_gran/assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js"></script>

<?php echo form_open_multipart('socios/copiar_email/'.$id);?>
<div class="container">
    <br />
    <h4>Email</h4>
    <h4>Para:</h4>
    <div id="opcionesPara">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para1" value="option1" checked>
            <label class="form-check-label" for="para1">
                Tots els usuaris / usuàries
            </label>
            <label id="direcciones1" class="direcciones"></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para2" value="option2">
            <label class="form-check-label" for="para2">
                Usuaris / usuàries d'un taller
            </label>
            <select id="talleres" class="hide">
                <option selected>Seleccionar un taller</option>
                <?php foreach ($talleres as $k => $v) { ?>
                    <option value="<?php echo $v->id ?>"><?php echo $v->nombre ?></option>
                <?php } ?>
            </select>
            <label id="direcciones2" class="direcciones"></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para3" value="option3">
            <label class="form-check-label" for="para3">
                Usuaris / usuàries de tots els tallers
            </label>
            <label id="direcciones3" class="direcciones"></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para4" value="option4">
            <label class="form-check-label" for="para4">
                Usuaris / usuàries de tots els tallers VOLUNTARIS
            </label>
            <label id="direcciones4" class="direcciones"></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para5" value="option5">
            <label class="form-check-label" for="para5">
                Usuaris / usuàries de tots els tallers PROFESSIONALS
            </label>
            <label id="direcciones5" class="direcciones"></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="para" id="para6" value="option6">
            <label class="form-check-label" for="para6">
                Altres
            </label>
            <label id="direcciones6" class="direcciones"></label>
        </div>
        <div class="">
            <input type="text" class="form-control hide" id="otros" placeholder="Indicar una o diverses adreces email separades per comes">
        </div>
    </div>
    <br />

    <div class="form-group mb-2">
        <h4>Títol:</h4>
    </div>
    <div class="form-group mx-sm-12 mb-12">
        <input type="text" class="form-control " id="titulo" placeholder="Indicar títol email" value="<?php echo $titulo ?>">
    </div>


    <div class="form-group mb-2">
        <h4>Missatge:</h4>
    </div>
    <div class="form-group mx-sm-8 mb-8">
        <textarea id="mensaje" name="mensaje" class="texteditor" style="visibility: hidden; display: none;"><?php echo $mensaje ?></textarea>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <h4>Adjunt 1</h4>
            <span>Pujar un arxiu</span>
			<input type="file" name="adjunto_1" class="" rel="http://localhost:8888/casal_gent_gran/index.php/basesDatos/emails/upload_file/adjunto_1" id="adjunto_1">
        </div>
        <div class="col-sm-4">
            <h4>Adjunt 2</h4>
            <span>Pujar un arxiu</span>
			<input type="file" name="adjunto_2" class="" rel="http://localhost:8888/casal_gent_gran/index.php/basesDatos/emails/upload_file/adjunto_2" id="adjunto_2">
       
        </div>
        <div class="col-sm-4">
            <h4>Adjunt 3</h4>
            <span>Pujar un arxiu</span>
			<input type="file" name="adjunto_3" class="" rel="http://localhost:8888/casal_gent_gran/index.php/basesDatos/emails/upload_file/adjunto_3" id="adjunto_3">
       
        </div>

    </div>
    <br>


    <div class="form-group mx-sm-8 mb-8">
        <button type="submit" class="btn btn-primary mb-2" id="enviarEmail">Enviar email</button>
    </div>


</div>
</form>
<!-- <button type="submit" class="btn btn-primary mb-2">Confirm identity</button> -->


<script>
    $(document).ready(function() {

        var emails = ""
        var nombres = ""
        var apellidos = ""
        var direccionesPara = ""

        $('#direcciones1').html(emails)



        function todos() {
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodos",
                data: {},
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    direccionesPara = datos['emails']
                    console.log('direccionesPara ' + direccionesPara)
                    if (datos['emails'].length == 1) emails = datos['emails'][0]
                    if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos['emails'].length + ' més'
                    if (datos['emails'].length = 0) emails = 'cap email trobat '
                    $('#direcciones1').html(emails)
                    

                },
                error: function() {
                    alert("Error en el proceso getEmailsTodos. Informar");
                }
            })
        }

        function todosTalleres(tipo_taller = 0, target) {
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodosTalleres/" + tipo_taller,
                data: {},
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['emails'][0])
                    direccionesPara = datos['emails']
                    console.log('direccionesPara ' + direccionesPara)
                    if (datos['emails'].length == 1) emails = datos['emails'][0]
                    if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos['emails'].length + ' més'
                    if (datos['emails'].length == 0) emails = 'Cap email trobat '
                    $(target).html(emails)
                },
                error: function() {
                    alert("Error en el proceso getEmailsTodos. Informar");
                }
            })
        }


        todos()

        $('#talleres').change(function() {
            // alert('seleccionado taller '+$(this).val())
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTaller",
                data: {
                    taller: $(this).val()
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    direccionesPara = datos['emails']
                    // alert(datos['emails'][0])
                    direccionesPara = datos['emails']
                    console.log('direccionesPara ' + direccionesPara)
                    if (datos['emails'].length == 1) emails = datos['emails'][0]
                    if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos['emails'].length - 1 + ' més'
                    if (datos['emails'].length == 0) emails = 'Cap email trobat '
                    $('#direcciones2').html(emails)
                },
                error: function() {
                    alert("Error en el proceso getEmailsTodos. Informar");
                }
            })
        })

        $('#otros').change(function() {
            direccionesPara = $(this).val().split(' ').join('');
            console.log('direccionesPara ' + direccionesPara)

        })

        $('input[type=radio][name=para]').change(function() {
            // alert('cambio')
            emails = ""
            nombres = ""
            apellidos = ""
            $('.direcciones').html('')
            switch (this.id) {
                case 'para1':
                    $('#talleres').addClass('hide')
                    $('#otros').addClass('hide')
                    todos()
                    $('#para1').val(direccionesPara)
                    break;
                case 'para2':
                    $('#talleres').removeClass('hide')
                    $('#otros').addClass('hide')
                    break;
                case 'para3':
                    $('#talleres').addClass('hide')
                    $('#otros').addClass('hide')
                    todosTalleres(0, '#direcciones3')
                    break;
                case 'para4':
                    $('#talleres').addClass('hide')
                    $('#otros').addClass('hide')
                    todosTalleres(1, '#direcciones4')
                    break;
                case 'para5':
                    $('#talleres').addClass('hide')
                    $('#otros').addClass('hide')
                    todosTalleres(2, '#direcciones5')
                    break;
                case 'para6':
                    $('#talleres').addClass('hide')
                    $('#otros').removeClass('hide')
                    break;
            }
        });

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
                    console.log('direccionesPara ' + direccionesPara)
                    if (datos['emails'].length == 1) emails = datos['emails'][0]
                    if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + datos['emails'].length - 1 + ' més'
                    if (datos['emails'].length == 0) emails = 'Cap email trobat '
                    $('#direcciones2').html(emails)
                },
                error: function() {
                    alert("Error en el proceso getEmailsTodos. Informar");
                }
            })



        })

    })
</script>