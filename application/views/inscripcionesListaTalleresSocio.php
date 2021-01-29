<h3>Inscripciones</h3>
<h4>Curs: <strong><?php echo $curso;?></strong></h4>
<?php $telefono="";
    $telefono_1=$socio->telefono_1;
    $telefono_2=$socio->telefono_2;
    if($telefono_1 || $telefono_2) $telefono=' - Teléfono: ';
    if($telefono_1) $telefono.=$telefono_1;
    if($telefono_2) $telefono.=' - '.$telefono_2;
    ?>
<h4>Usuari/Usuària: <strong><?php echo $socio->id.' - '.$socio->apellidos.', '.$socio->nombre;?></strong> 
 <?php //echo $telefono; ?>
</h4>
<h3>Lista Talleres Inscritos</h3>
<br>

<?php echo form_open('talleres/registrarInscripciones', array('id'=>'form','class'=>"form-horizontal", 'role'=>"form")); ?>
<input type="hidden" name="curso" value="<?php echo $idCurso; ?>" >
<input type="hidden" name="socio" value="<?php echo $idSocio; ?>" >
<?php echo $talleres; ?>
<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="" onclick="controlCambios()">Registrar inscripciones</button>
    </div>
</div>
<?php echo form_close(); ?>








<style>
 
    .marcado{
        color:blue;
        background-color: lightblue;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
    }
    .noMarcado{
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
        
    }
    
    .completo{
        font-weight: normal;
        color:red;
        
    }
    
    .noCompleto{
        font-weight: normal;
        color:black;
    }
    
</style>


<script>
$(document).ready(function () {
    
    //control cambios
    var cambios=false;
    
    window.onbeforeunload=confirmExit
    function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han guardado.'
        }
    }
  
    $( "#form" ).submit(function( event ) {
        cambios=false;
    });
    
   $('.completo').attr('disabled','disabled')
   $('.noCompleto').removeAttr('disabled')
    
    $('.C').click(function() {
        //alert($(this).parent().parent().children('span:nth-child(1)').children('input.C').prop("tagName"))
        $(this).parent().parent().children('span:nth-child(2)').children('input.T1').prop('checked', false);
        $(this).parent().parent().children('span:nth-child(3)').children('input.T2').prop('checked', false);
        $(this).parent().parent().children('span:nth-child(4)').children('input.T3').prop('checked', false);
        cambios=true;
	});
        
      
      
        
    $('.T1, .T2, .T3').click(function() {
        cambios=true;
	if ($(this).is(':checked')) {
                if($(this).parent().parent().children('span:nth-child(2)').children('input.T1').is(':checked')
                        && $(this).parent().parent().children('span:nth-child(3)').children('input.T2').is(':checked')
                        && $(this).parent().parent().children('span:nth-child(4)').children('input.T3').is(':checked')){
                           // $(this).parent().parent().children('span:nth-child(1)').children('input.C').prop('checked', true);
                           // $(this).parent().parent().children('span:nth-child(2)').children('input.T1').prop('checked', false)
                           // $(this).parent().parent().children('span:nth-child(3)').children('input.T2').prop('checked', false)
                           // $(this).parent().parent().children('span:nth-child(4)').children('input.T3').prop('checked', false)
                }
                else{
                            $(this).parent().parent().children('span:nth-child(1)').children('input.C').prop('checked', false);
                        }
		} 
                else 
                {
                //$(this).parent().children('.C').prop('checked', true);
            //alert("Checkbox Master must be checked, but it's not!");
		}
	});
        
        $('.C,.T1,.T2,.T3').click(function(){
            if($(this).prop('checked'))
                $(this).parent().parent().prev().children().css('color','blue')
            else
                $(this).parent().parent().prev().children().css('color','black')
        })
        
        $('.nombreTaller').click(function(){
           var numTaller=($(this).parent().next().children().children('input').val())
           //alert('Num Taller '+numTaller)
           $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTaller/"+numTaller, 
        //data:{curso:curso},
        success:function(datos){
            
            var datos=$.parseJSON(datos);
            var informacion='<strong>'+datos['nombreTaller']+'</strong><p>Profesor: '+datos['nombreProfesor']+'</p>'
            informacion+='Espacio: '+datos['espacio']
            informacion+='<p>'+datos['dia1']+':  '+datos['inicio1'].substr(0,5)+' - '+datos['final1'].substr(0,5)+'</p>'
            if(!datos['dia1'])
               informacion+='<p>'+datos['dia2']+':  '+datos['inicio2'].substr(0,5)+' - '+datos['final2'].substr(0,5)+'</p>'
            $('.modal-title').html('Información Taller')
            $('.modal-body>p').html(informacion)
            $("#myModal").modal()  
            
            
            
        },
        error: function(){
                alert("Error en el proceso getTaller. Informar");
         }
    })
           
           
        })
        
})
</script>
