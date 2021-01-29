<div class="container inscripciones">
<h3>Inscripcions actuals</h3>

<br>

<?php echo form_open('talleres/registrarBajasNuevo', array('id'=>'form','class'=>"form-horizontal", 'role'=>"form")); ?>
<input type="hidden" name="curso" value="<?php echo $idCurso; ?>" >
<input type="hidden" name="socio" value="<?php echo $idSocio; ?>" >
<h4>Curs: <strong><?php echo $curso;?></strong></h4>
<h4>Usuari/Usuària: <strong><?php echo $socio;?></strong>
    <!--
-- Tarjeta Rosa: 
    <?php $tarjetaRosa=""; if($tarjeta_rosa=='Sí') $tarjetaRosa="checked='checked'"; ?>
    
<input type="checkbox" <?php echo  $tarjetaRosa; ?> name="tarjetaRosa"  disabled="disabled">
    -->
<input type="checkbox" <?php echo  $tarjetaRosa; ?> name="tarjetaRosa" id="tarjetaRosa" class="hide" >

    </h4>
<h3>Tallers</h3>

<?php if(!$talleres){ ?>
    <h4 id='noTalleres'>No està inscrit en tallers, ni està en llistes d'espera</h4><br>
<?php }else { echo $talleres; } ?>   

<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-danger" id="donarBaixa">Donar de baixa</button>
      <button class="btn btn-default cancel-button " type="button" id="cancel-button">
           <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Cancel·lar</button>
       
    </div>
</div>

<?php echo form_close(); ?>
</div>

<style>
 
    .marcado{
        color:white;
        background-color: #d9534f;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
    }
    .noMarcado{
        color:black;
        background-color: white;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 5px
    }
    .colorEspera{
        background:yellow;
    }
    .noInscriptos{
        border: 0px;
        font-size: 14px;
    }
    
</style>


<script>
$(document).ready(function () {
    
    //quita la C y checkbox en bajas
    // $('#form > table > tbody > tr > td:odd > span:nth-child(1)').addClass('hide')

    if($('#noTalleres').length != 0) {
        $('#donarBaixa').addClass('hide')
        $('#cancel-button').html('<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Tornar')
    }

    $('#cancel-button').click(function(){
        cambios=false
        window.location.href = "<?php echo base_url() ?>"+"index.php/talleres/seleccionarBajas";
    })
    
    if($('#tarjetaRosa').is(':checked')){
        //alert('hola paso si')
        $('.inscripciones').css('background-color','lightpink')
        
    }
    else{
        //alert('hola paso no')
        $('.inscripciones').css('background-color','white')
    }
    
    $('#tarjetaRosa').change(function(){
        //alert('hola paso si')
        if($('#tarjetaRosa').is(':checked')){
        //alert('hola paso si')
        $('.inscripciones').css('background-color','lightpink')
        
    }
    else{
        //alert('hola paso no')
        $('.inscripciones').css('background-color','white')
    }
    })
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
        if(cambios==false){
            event.preventDefault();
           $('.modal-title').html('Informació')
           $('#myModal').css('color','')
           $('.modal-body p').html('No ha marcat nigún període per donar de baixa o no està inscrit a cap taller')
           $('#myModal').modal('show')
            return;
        }
        cambios=false;
    });
    
    $('.C').click(function() {
        cambios=true;
        //alert($(this).parent().parent().children('span:nth-child(1)').children('input.C').prop("tagName"))
        $(this).parent().parent().children('span:nth-child(2)').children('input.T1').prop('checked', false);
        $(this).parent().parent().children('span:nth-child(3)').children('input.T2').prop('checked', false);
        $(this).parent().parent().children('span:nth-child(4)').children('input.T3').prop('checked', false);

	});
        
    $('.T1, .T2, .T3').click(function() {
        cambios=true;
	if ($(this).is(':checked')) {
                if($(this).parent().parent().children('span:nth-child(2)').children('input.T1').is(':checked')
                        && $(this).parent().parent().children('span:nth-child(3)').children('input.T2').is(':checked')
                        && $(this).parent().parent().children('span:nth-child(4)').children('input.T3').is(':checked')){
                           //$(this).parent().parent().children('span:nth-child(1)').children('input.C').prop('checked', true);
                           //$(this).parent().parent().children('span:nth-child(2)').children('input.T1').prop('checked', false)
                           //$(this).parent().parent().children('span:nth-child(3)').children('input.T2').prop('checked', false)
                           //$(this).parent().parent().children('span:nth-child(4)').children('input.T3').prop('checked', false)
                            //$(this).parent().children('.T1, .T2, .T3').prop('checked', false)
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
        
})
</script>
