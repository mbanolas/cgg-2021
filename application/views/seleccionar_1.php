<br />
<h3>Inscripciones a talleres</h3>
<br />

<?php echo form_open('talleres/inscripciones', array('class'=>"form-horizontal", 'role'=>"form")); ?>
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
        <?php echo form_input(array('class'=>'clearable form-control searchable-input','name'=>'buscarSocio','id'=>'buscarSocio','placeholder'=>'Buscar socio'));   ?>
    </div>
    <div class="col-sm-3"> 
      <?php echo form_dropdown('socio', $optionsSocios,null,array('class'=>'form-control', 'id'=>'socios')); ?>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="inscripcionesTalleres">Mostrar talleres del curso</button>
    </div>
  </div>
<?php echo form_close(); ?>



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
            filtroSocios(" ")
        });

// $('.clearable').trigger("input");
// Uncomment the line above if you pre-fill values from LS or server
    
  
    
    
 
$('select#cursos option').last().attr('selected','selected')

$('#curso').val($('select#cursos').val())
   

$('input.searchable-input').keyup(function(){
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
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getSociosFiltro", 
        data:{filtro:filtro},
        success:function(datos){
            $('select#socios option').remove();
            //alert(datos)
            if(datos=='false'){
                //alert('hola')
                $('#socios').append('<option value="0">No existen socios</option>')
            }
            else{
            var datos=$.parseJSON(datos);
            
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#socios').append(option)
             })
             sortSelectOptions('#socios')
         }
        },
        error: function(){
                alert("Error en el proceso getSociosFiltro. Informar");
         }
    })
    }
   
})

 

</script>

    
    