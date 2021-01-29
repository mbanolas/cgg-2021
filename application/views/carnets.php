<div class="container"><br />
<br />
<h3>Imprimir carnets</h3>
<input type="hidden" id="reload" value="<?php echo $reload ?>">
<?php ?>
<br />
<?php $optionsSocios[0]="- Seleccione un socio"; ?>
<?php echo form_open('socios/pdfCarnets', array('class'=>"form-horizontal", 'role'=>"form")); ?>
  
  <div class="form-group">
      <div class="row">
    <label class="control-label col-sm-2" for="taller">Usuari/Usuària:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('class'=>'clearable form-control searchable-input buscarSocio','name'=>'buscarSocio','id'=>'1','placeholder'=>'Cercar soci/sòcia'));   ?>
    </div>
    <div class="col-sm-1"> 
        <button class="btn  btn-default"  id="buscarSocios"> Cercar</button>
        <button class="btn  btn-default  buscando hide"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Buscando...</button>

    </div>
    <div class="col-sm-3"> 
      <?php echo form_dropdown('socio', $optionsSocios,null,array('class'=>'form-control socios hide', 'id'=>'socios')); ?>
    </div>
        <button  class="btn btn-success hide" id="agregarALista" name="">Afexir a la llista</button>
   </div>
     
      <div class="container" ><br><h4>Llista per preparar carnets</h4>
        <div class="col-sm-5">
        <div id="listaCarnets">
        <img class="img-responsive hide" id="loader" src="<?php echo base_url('images/ajax-loader-2.gif') ?>">

        </div>
    
    </div>
</div>
    
  </div>



<div class="col-sm-2">
            <button type="submit" class="btn btn-success " id="imprimirCarnets" name="pdfCarnets">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Pdf carnets llista i Nous usuaris/usuàries</button>
        </div>
<!--
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="verAsistentes">Ver asistentes</button>
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
  /*transition: background 0.4s; */
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

#nuevoSocio{
    color:red;
}
</style>


<script>
$(document).ready(function () {
    
  $('#imprimirCarnets__').click(function(e){
      e.preventDefault()
       var socios=[]
        $('input.socios').each(function(i,e)  {
            
            socios[i]=$(this).val()
        })
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/socios/pdfCarnets",
            data: $('form').serialize(),
            success: function(datos){
                alert('datos '+datos)
                var resultado=$.parseJSON(datos)
                //alert('resultado '+resultado['totalFactura'])
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Carnets emitidos correctamente..')
            $("#myModalVolver").modal()  
            
            //alert('Factura proveedor registrada correctamente.')
            //document.location.reload(true);
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas facturas. Informar");
            }
        })
        
      
  })  
  
  
    
  if($('#reload').val()==1){
           $('.modal-title').html('Informació')
           $('#myModal').css('color','')
           $('.modal-body p').html('No se ha incluido ningún socio para imprimir carnets')
           $('#myModal').modal('show')
            
        } 
        
   if($('#reload').val()==2){
            $('.modal-title').html('Informaciónn')
           $('#myModal').css('color','')
           $('.modal-body p').html('No se ha incluido ningún socio para imprimir carnets')
           $('#myModal').modal('show')
            
        }      
  
  $( "body" ).delegate( ".eliminar", "click", function(e) {
    e.preventDefault()
    $(this).parent().remove()
   });
  
  
   //incorpora nuevos socios sin carnets
   $('#loader').removeClass('hide')
   $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/socios/getSociosNuevosAjax", 
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos)
            $.each(datos, function(index, value){
                    //alert(value)
                    $('#listaCarnets').append('<div class="row"><input type="hidden" name="carnets[]" value="'+value['id']+'"><a href="#" class="eliminar">Eliminar </a> '+value['id']+' - '+value['apellidos']+', '+value['nombre']+' <span id="nuevoSocio">SOCIO NUEVO O EDITADO</span></div>')
            })
            $('#loader').addClass('hide')
        },
        error: function(){
                alert("Error en el proceso getSociosNuevosAjax. Informar");
         }
    })
    
     
  $('#agregarALista').click(function(e){
      e.preventDefault()
      var socio=$('#socios').val()
      if(socio==0){
           $('.modal-title').html('Informaciónn')
           $('#myModal').css('color','')
           $('.modal-body p').html('Seleccione un socio')
           $('#myModal').modal('show')
           return false
      }
      
       $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/socios/getSocioAjax", 
        data:{socio:socio},
        success:function(datos){
           // alert(datos)
            var datos=$.parseJSON(datos);
            // alert(datos['id'])
             $('#listaCarnets').append('<div class="row"><input class="socios" type="hidden" name="carnets[]" value="'+datos['id']+'"><a href="#" class="eliminar">Eliminar </a> '+datos['id']+' - '+datos['apellidos']+', '+datos['nombre']+'</div>')
            // $('#listaCarnets').append('<input type="hidden" name="carnets[]" value="'+datos['id']+'"></div>')
             
        },
        error: function(){
                alert("Error en el proceso getSocioAjax. Informar");
         }
    })
      
      
      
      
      
  })  

  sortSelectOptions('.socios')
  $('select.socios option[value="0"').attr('selected','selected')

//inicialización
var socio=$('select#socios').val()
//filtroSocios("")


$('input.searchable-input_').keyup(function(){
    //alert('hola')
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
    $('#agregarALista').addClass('hide');
    
}) 





$('#buscarSocios').click(function(e){
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

 function filtroSocios(filtro){
        $('.buscando').removeClass('hide')
        $('#buscarSocios').addClass('hide')
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
                $('.buscando').addClass('hide')
                $('#buscarSocios').removeClass('hide')
                $('select#socios').removeClass('hide');
                $('#agregarALista').removeClass('hide');
            }
            else{
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos)
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#socios').append(option)
             })
             sortSelectOptions('#socios')
             $('select#socios').removeClass('hide');
             $('#agregarALista').removeClass('hide');
             $('.buscando').addClass('hide')
             $('#buscarSocios').removeClass('hide')
            }
        },
        error: function(){
                alert("Error en el proceso getSociosFiltro. Informar");
         }
    })
    }

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
})

</script>
