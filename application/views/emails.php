<!--<div style='height:15px;'></div>-->

<style>
  #cursos,#cursos0,#cursos1,#cursos2 {
    margin-left: 50px;
  }
  #talleres {
    margin-left: 8px;
  }

  .direcciones {
    margin-left: 50px;
    color: blue;
  }

  #crudForm > div > div.form-group.mensaje_form_group > div{
    width:70% !important;
  }
  #field-para{
    width:100% !important;
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



<style>
#selCurso{
  margin-left:5px;
}

</style>


<script>
  $(document).ready(function() {


    // $('<div class="floatL t5"><select class="form-control floatL" id="selCurso"><?php //echo $cursos ?></select></div>').insertAfter($('#gcrud-search-form > div.header-tools > div.floatL.t5'))
    $('#gcrud-search-form > div.header-tools > div:nth-child(1) > a').html('<i class="fa fa-plus"></i> &nbsp; Nou correu electrònic')

    var actual=$('#save-and-go-back-button').html()
    $('#save-and-go-back-button').html(actual+' <img class="loading hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   ')
    // var actual2=$('#form-button-save').html()
    // $('#form-button-save').html(actual2+' <img class="loading hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" alt="Smiley face" height="20" width="20">   ')
    $('#form-button-save').addClass('hide')

    $('#save-and-go-back-button').click(function(){
      $('#save-and-go-back-button img').removeClass('hide')
    })
    // $('#field-para').addClass('hide')
    // $('#field-para').attr('class','hidden')

    var emails = ""
    var nombres = ""
    var apellidos = ""
    var direccionesPara = ""

    $('#cursos').change(function() {
            console.log($(this).val())
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
                        $("#talleres").append('<option value="' + value['taller'] + '">' + value['nombre'] + '</option>');
                    })

                    // alert(datosJSON[0]['nombre'])

                },
                error: function() {
                    alert("Error en el proceso de getTalleresOptions. Informar");
                }
            })
        })



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
          $('#field-para').val(datos['emails'])
        },
        error: function() {
          alert("Error en el proceso getEmailsTodos. Informar");
        }
      })
    }

    $('#cursos0').change(function(){
      todosTalleres(0, '#direcciones3')
    })
    $('#cursos1').change(function(){
      todosTalleres(1, '#direcciones4')
    })
    $('#cursos2').change(function(){
      todosTalleres(2, '#direcciones5')
    })

    function todosTalleres(tipo_taller = 0, target) {
      $('#cursos0, #cursos1, #cursos2').addClass('hide')
      var curso=0;
      switch(tipo_taller){
        case 0:
          $('#cursos0').removeClass('hide')
          curso=$('#cursos0').val()
          break;
        case 1:
          $('#cursos1').removeClass('hide')
          curso=$('#cursos1').val()
          break;
        case 2:
          $('#cursos2').removeClass('hide')
          curso=$('#cursos2').val()
          break;
      }
      console.log('curso '+curso)
      $.ajax({
        type: 'POST',
        url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodosTalleres/" + tipo_taller,
        data: {curso:curso},
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
          $('#field-para').val(datos['emails'])
        },
        error: function() {
          alert("Error en el proceso getEmailsTodos. Informar");
        }
      })
    }

    function todosUsuarios(target) {
      $.ajax({
        type: 'POST',
        url: "<?php echo base_url() ?>" + "index.php/socios/getEmailsTodos",
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
          $('#field-para').val(datos['emails'])
        },
        error: function() {
          alert("Error en el proceso getEmailsTodos. Informar");
        }
      })
    }


    
    // todos()
    // $('#direcciones1').html(emails)


    $('#talleres').change(function() {
      // alert('seleccionado taller '+$(this).val())
      console.log($(this).val())
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
          // console.log('direccionesPara ' + direccionesPara)
          // console.log('direccionesPara length ' + datos['emails'].length)
          // console.log("datos['emails'][0] " + datos['emails'][0])
          if (datos['emails'].length == 1) emails = datos['emails'][0]
          if (datos['emails'].length > 1) emails = datos['emails'][0] + ",... i " + (datos['emails'].length - 1) + ' més'
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

      console.log('desde otros 1 typeof direccionesPara '+typeof direccionesPara)
      direccionesPara = $(this).val().split(' ').join('')
      console.log('desde otros typeof direccionesPara '+typeof direccionesPara)
      console.log('direccionesPara ' + direccionesPara)
      console.log('direccionesPara toString ' + direccionesPara.toString())
      $('#field-para').val(direccionesPara)

    })

    $('input[type=radio][name=grupo]').change(function() {
      // alert('cambio')
      emails = ""
      nombres = ""
      apellidos = ""
      $('.direcciones').html('')
      $('#cursos0, #cursos1, #cursos2').addClass('hide')
      switch (this.value) {
        case 'option1':
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          $('#otros').addClass('hide')
          $('#talleres').val(0)
          todos()
          break;
        case 'option2':
          $('#talleres').val(0)
          $('#talleres').removeClass('hide')
          $('#cursos').removeClass('hide')
          $('#otros').addClass('hide')
          break;
        case 'option3':
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          $('#otros').addClass('hide')
          todosTalleres(0, '#direcciones3')
          break;
        case 'option4':
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          $('#otros').addClass('hide')
          todosTalleres(1, '#direcciones4')
          break;
        case 'option5':
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          $('#otros').addClass('hide')
          todosTalleres(2, '#direcciones5')
          break;
        case 'option6':
          $('#field-para').val("")
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          // $('#otros').removeClass('hide')
          // $('#otros').focus()

          break;
          case 'option10':
          $('#talleres').addClass('hide')
          $('#cursos').addClass('hide')
          $('#otros').addClass('hide')
          todosUsuarios('#direcciones10')
          break;
      }
    });

    $('#field-para').change(function(){
      console.log('cambiado mensaje')
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
          console.log('direccionesPara ' + direccionesPara)
          if (datos['emails'].length == 1) emails = datos['emails'][0]
          if (datos['emails'].length > 1) emails = datos['emails'][0] + ',... i ' + (datos['emails'].length - 1) + ' més'
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