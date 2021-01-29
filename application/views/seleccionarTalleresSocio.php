<div class="container"><br />
<br />
<h3>Llistat tallers inscrits</h3>
<?php $optionsSocios=array() ?>
<br />

<?php echo form_open('talleres/pdfTalleresSocio', array('class'=>"form-horizontal", 'role'=>"form")); ?>
  <div class="form-group">
    <label class="control-label col-sm-2" for="curso">Curs:</label>
    <div class="col-sm-2">
    <?php echo form_dropdown('curso', $optionsCursos,0,array('class'=>'form-control', 'id'=>'cursos')); ?>
    <input type="hidden" name="curso" value="" id="curso">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="socio">Usuari/Usuària:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('class'=>'clearable form-control searchable-input','name'=>'buscarSocio','id'=>'buscarSocio','placeholder'=>'Cercar soci/sòcia'));   ?>
    </div>
    <div class="col-sm-1"> 
        <button class="btn  btn-default"  id="buscarSocios"> Cercar</button>
        <button class="btn  btn-default  buscando hide"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Buscant...</button>

    </div>
    
    
    
    <div class="col-sm-3"> 
     
    <?php echo form_dropdown('socio', $optionsSocios,null,array('class'=>'form-control hide', 'id'=>'socios')); ?>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-12">
      <button type="submit" class="btn btn-default" id="inscripcionesTalleres">Mostra Inscripcions a tallers</button>
    <!--
      <button type="submit" class="btn btn-success hide" id="pdfTalleresSocio" name="pdfTalleresSocio">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Baixar PDF </button>
      -->
    </div>
      
  </div>



<?php echo form_close(); ?>
</div>

<div class="container hide" id="informacion">
    
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

.numDerecha{
    text-align: right;
}
</style>




<script>
  
    
$(document).ready(function () {
    
    
    

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

            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){

            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){

            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc') 
            $('select#socios').addClass('hide');
           // filtroSocios(" ")
        });

// $('.clearable').trigger("input");
// Uncomment the line above if you pre-fill values from LS or server
    
  
    
    
 
//$('select#cursos option').last().attr('selected','selected')

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

$('input.searchable-input').click(function(){
    $('select#socios').addClass('hide');
    
}) 





$('#buscarSocios').click(function(e){
    e.preventDefault()
    
    $('#informacion').addClass('hide')
    $('#informacion').html('')
    $('#pdfTalleresSocio').addClass('hide')
    
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

 function filtroSocios(filtro){
        $('.buscando').removeClass('hide')
        $('#buscarSocios').addClass('hide')
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
                $('.buscando').addClass('hide')
                $('#buscarSocios').removeClass('hide')
                $('select#socios').removeClass('hide');
            }
            else{
            var datos=$.parseJSON(datos);
            
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#socios').append(option)
             })
             sortSelectOptions('#socios')
             $('select#socios').removeClass('hide');
             $('.buscando').addClass('hide')
             $('#buscarSocios').removeClass('hide')
         }
        },
        error: function(){
                alert("Error en el proceso getSociosFiltro. Informar");
         }
    })
    }
   
})

$('#socios, #cursos').change(function(){
    $('#informacion').addClass('hide')
    $('#informacion').html('')
    $('#pdfTalleresSocio').addClass('hide')
})


$('#inscripcionesTalleres').click(function(e){
    e.preventDefault()
    //alert('hola')
    var curso=$('#cursos').val()
    var socio=$('#socios').val()
    if(!socio){
        $('.modal-title').html('Informaciónn')
           $('#myModal').css('color','')
           $('.modal-body p').html('Buscar y seleccionar un socio')
           $('#myModal').modal('show')
        return false
    }
    
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaTalleresSocio", 
        data:{curso:curso,socio:socio},
        success:function(datos){
           //alert(datos)
           var datos=$.parseJSON(datos);
           $('#informacion').html(datos) 
           $('#informacion').removeClass('hide')
           $('#pdfTalleresSocio').removeClass('hide')
           
            
        },
        error: function(){
                alert("Error en el proceso getTablaTalleresSocio. Informar");
         }
    })
    
    
    
    
    $('#informacion').removeClass('hide')
    
})
 

</script>

    
    