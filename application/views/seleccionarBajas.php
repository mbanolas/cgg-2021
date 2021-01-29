<div class="container"><br />
<br />
<h3 id="bajas">Baixes en tallers</h3>
<?php $optionsSocios=array() ?>
<input type="hidden" id="reload" value="<?php echo $reload ?>">
<input type="hidden" id="dni" value="<?php echo $dni ?>">
<input type="hidden" id="numSocio" value="<?php echo $numSocio ?>">
<input type="hidden" id="nombreSocio" value="<?php echo $nombreSocio ?>">

<br />

<?php echo form_open('talleres/bajas', array('class'=>"form-horizontal", 'role'=>"form")); ?>
  <div class="form-group">
    <label class="control-label col-sm-2" for="curso">Curs:</label>
    <div class="col-sm-2">
    <?php echo form_dropdown('curso', $optionsCursos,count($optionsCursos),array('class'=>'form-control', 'id'=>'cursos')); ?>
    <input type="hidden" name="curso" value="" id="curso">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="socio">Usuari/Usuària:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('class'=>'clearable form-control searchable-input','name'=>'buscarSocio','id'=>'buscarSocio','placeholder'=>'Cercar usuari/usuària/sòcia'));   ?>
    </div>
    <div class="col-sm-1"> 
        <button class="btn  btn-default"  id="buscarSocios"> Cercar</button>
        <button class="btn  btn-default  buscando hide"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Buscant...</button>
    </div>
    <div class="col-sm-3"> 
      <?php echo form_dropdown('socio', $optionsSocios, null, array('class' => 'form-control hide', 'id' => 'socios')); ?>

    </div>

    

    <div class="col-sm-1">
            <button type="submit" class="btn btn-default" id="inscripcionesTalleres">Mostra tallers del curs</button>
        </div>
  </div>

  <div class="form-group">
        <div class="col-sm-5"> 
        </div>  
        <div class="col-sm-6"> 
            <label  id="numSeleccionados"></label>
        </div>
    </div>
  <!--
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="inscripcionesTalleres">Mostra tallers inscrits</button>
    </div>
  </div>
-->
<?php echo form_close(); ?>
</div>


<style>
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
 
    #bajas{
        color: red;
    }    
    
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
    
#numSeleccionados{
       color:blue;
   }
    
    
    
</style>




<script>
  
    
$(document).ready(function () {

    $('#inscripcionesTalleres').addClass('hide');
    
        if($('#reload').val()==1){
            $('.modal-title').html('Informació')
           $('#myModal').css('color','')
           $('.modal-body p').html('Cercar i seleccionar un usuari/usuària')
           $('#myModal').modal('show')
            
        }

        if ($('#reload').val() == 3) {           
            $('.modal-title').html('Informació')
            $('#myModal').css('color', 'red')
            dni=$("#dni").val()
            numSocio=$("#numSocio").val()
            nombreSocio=$("#nombreSocio").val()
            $('.modal-body p').html("El DNI ("+dni+") de <strong>"+nombreSocio+"</strong> amb el número "+numSocio+" no és vàlid.<br>Heu d'anar a la base de dades d'usuaris/ usuàries i modificar-ho abans de fer la inscripció al taller.")
            $('#myModal').modal('show')
        }
    
    

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
        function tog(v){return v?'addClass':'removeClass';} 
        $(document).on('input', '.clearable', function(){
            $('#numSeleccionados').html('')
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $('#numSeleccionados').html('')
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            $('#numSeleccionados').html('')
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc') 
            $('inscripcionesTalleres').addClass('hide'); 
            //filtroSocios(" ")
        });

// $('.clearable').trigger("input");
// Uncomment the line above if you pre-fill values from LS or server
    
  
    
    
 
$('select#cursos option').first().attr('selected','selected')

$('#curso').val($('select#cursos').val())
   
$('input.searchable-input_').keyup(function(){
    if($(this).val()){
    $(this).css('border-color','#444')
    $(this).css('border-style','dashed')
    var filtro=$(this).val()
    filtroSocios(filtro)
    }
    else{
    $(this).css('border','1px solid #ccc')  
    filtroSocios(" ")
    }
   
})  



$('input.searchable-input').click(function () {
            $('select#socios').addClass('hide');
            $('inscripcionesTalleres').addClass('hide');
        })

$('#buscarSocios').click(function(e){
    $('#inscripcionesTalleres').attr('disabled','disabled')
    e.preventDefault()
    if($('input.searchable-input').val()){
    $('input.searchable-input').css('border-color','#444')
    $('input.searchable-input').css('border-style','dashed')
    var filtro=$('input.searchable-input').val()
    filtroSocios(filtro)
    }
    else{
    $('input.searchable-input').css('border','1px solid #ccc')  
    filtroSocios(" ")
    }
   
})  

$('.buscando').click(function(e){
    e.preventDefault()
    
    
})

function sortSelectOptions(selectElement) {
	var options = $(selectElement+" option");

	options.sort(function(a,b) {
		if (a.text.toUpperCase() > b.text.toUpperCase()) return 1;
		else if (a.text.toUpperCase() < b.text.toUpperCase()) return -1;
		else return 0;
	});

	$(selectElement).empty().append( options );
        $(selectElement+" option").first().attr('selected','selected')
}

$('#inscripcionesTalleres').attr('disabled','disabled')

$('#buscarSocio').click(function(){
    $('#inscripcionesTalleres').attr('disabled','disabled')
    $('#inscripcionesTalleres').addClass('hide');
})


 function filtroSocios(filtro){
     $('.buscando').removeClass('hide')
        $('#buscarSocios').addClass('hide')
        $('#inscripcionesTalleres').addClass('hide');
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getSociosFiltroTodos", 
        data:{filtro:filtro},
        success:function(datos){
            $('select#socios option').remove();
            //alert(datos)
            if(datos=='false'){
                //alert('hola')
                $('#socios').append('<option value="0">No existen socios</option>')
                $('#inscripcionesTalleres').attr('disabled','disabled')
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                        $('select#socios').removeClass('hide');
            }
            else
            {
                        
                        var datos = $.parseJSON(datos);
                        $('#inscripcionesTalleres').removeAttr('disabled')
                        $.each(datos, function (index, value) {
                            var option = '<option value="' + index + '">' + value + '</option>'
                            $('#socios').append(option)
                        })
                        $('#numSeleccionados').html('')
                        $('select#socios').css('height','')
                        if(Object.keys(datos).length>1){
                            $('#numSeleccionados').html(Object.keys(datos).length+" en la llista. Trieu l'apropiat")
                            $('select#socios').attr('size',Math.min(Object.keys(datos).length,25))
                        }
                        sortSelectOptions('#socios')
                        $('select#socios').removeClass('hide');
                        $('.buscando').addClass('hide')
                        $('#buscarSocios').removeClass('hide')
                        $('#inscripcionesTalleres').removeClass('hide');
                    }
        },
        error: function(){
                alert("Error en el proceso getSociosFiltroTodos. Informar");
         }
    })
    }
   
   $('input.searchable-input').click(function(){
    $('select#socios').addClass('hide');
    
}) 
   
 $('#cursos').change(function(){
    $('#curso').val($(this).val())
})
  
   
})

 

</script>

    
    