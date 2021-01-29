<div class="container">
<h3>Sol·licitud Baixes de tallers </h3><hr>
<!--
<?php echo 'curs: '.$inscripcion['curso'].'<br>'; ?>
<?php echo 'usuari: '.$inscripcion['socio'].'<br>'; echo '----------------------<br>'; ?>

<?php foreach($inscripcion['id_talleres'] as $k=>$v){
    echo 'id_taller: '.$v.'<br>';
    echo 'trimestres: '.$inscripcion['trimestres'][$k].'<br>';
    echo 'importes: '.$inscripcion['importes'][$k].'<br>';
    echo '----------------------<br>';
}
?>
-->

<?php echo form_open('talleres/recibosBajasNuevo',array('id'=>'form')); ?>
<?php echo form_hidden('resultNuevo', $value = $inscripcion['resultNuevo']) ; ?>
<?php echo form_hidden('cursoNombre', $value = $inscripcion['cursoNombre']) ; ?>
<?php echo form_hidden('socioNombre', $value = $inscripcion['socioNombre']) ; ?>
<?php echo form_hidden('curso', $value = $inscripcion['curso']) ; ?>
<?php echo form_hidden('socio', $value = $inscripcion['socio']) ; ?>

<?php echo form_hidden('C', $value = $inscripcion['C']) ; ?>
<?php echo form_hidden('T1', $value = $inscripcion['T1']) ; ?>
<?php echo form_hidden('T2', $value = $inscripcion['T2']) ; ?>
<?php echo form_hidden('T3', $value = $inscripcion['T3']) ; ?>
<?php echo form_hidden('CNombres', $value = $inscripcion['CNombres']) ; ?>
<?php echo form_hidden('trimestres', $value = $inscripcion['trimestres']) ; ?>
<?php echo form_hidden('importes', $value = $inscripcion['importes']) ; ?>
<?php echo form_hidden('id_talleres', $value = $inscripcion['id_talleres']) ; ?>


<!--
<?php echo form_hidden('CPrecios', $value = $inscripcion['CPrecios']) ; ?>
<?php echo form_hidden('T1Precios', $value = $inscripcion['T1Precios']) ; ?>
<?php echo form_hidden('T2Precios', $value = $inscripcion['T2Precios']) ; ?>
<?php echo form_hidden('T3Precios', $value = $inscripcion['T3Precios']) ; ?>
-->
<?php echo form_hidden('totalAPagar', $value = $inscripcion['totalAPagar']) ; ?>

<h4>Curs: <?php echo $inscripcion['cursoNombre']; ?></h4>
<h4>Usuari/Usuària: <strong><?php echo $inscripcion['socioNombre']; ?></strong></h4>
<?php if(count($inscripcion['CNombres'])>1) { ?><h3>Tallers:</h3><?php } ?>
<?php if(count($inscripcion['CNombres'])==1) { ?><h3>Taller:</h3><?php } ?>
<?php $salida=""; ?>
<?php if(count($inscripcion['CNombres'])) { 
     //var_dump($inscripcion);
    $salida.="<table>";
    $nombres=array();
    $insc=array();
    foreach($inscripcion['CNombres'] as $k=>$v){
            $textoImporte[$k]=$inscripcion['importes'][$k]."  €";
            $inscript=' ('.$inscripcion['trimestres'][$k].')  ';
           if (!$inscripcion['trimestres'][$k]) $inscript="";
           if($inscripcion['trimestres'][$k]==-1) {
               $inscript="Lista espera"; 
               $textoImporte[$k]="";
           }
           $insc[]=$inscript;
           //
    }
    ?>

<?php } ?>
<?php if(count($inscripcion['CNombres'])) { 
    foreach($inscripcion['CNombres'] as $k=>$v){
    $salida.='<tr>';
    $salida.="<td width='300'>- $v</td><td width='100'>$insc[$k] </td><td style='text-align:right'>".$textoImporte[$k]."</td>";
}
}?>
<!--<h4> - <?php echo implode("<br> - ",$nombres); ?></h4>-->
<?php $salida.='</tr></table>' ?>
<?php echo $salida; ?>

<h2 style="color:red">Total a Retornar: <?php echo ($inscripcion['totalAPagar']).' €'; ?></h2>
<br>

<div class="form-group"> 
    <div class="col-sm-10">
      <button type="submit" class="btn btn-danger" id="emitirRecibo">Emetre rebut i retornar</button>
    </div>
</div>
<?php echo form_close(); ?>
</div>

<script>
$(document).ready(function () {
    
    $('#emitirRecibo').click(function(e){
       
        
        
        var pagado=<?php echo json_encode($_SESSION['pagado']); ?>;
       
        if(pagado){
            alert('RECIBO YA PAGADO. NO se puede emitir duplicado')
            e.preventDefault()
        }
        else{  
            //recibosInscripcion()
        }
    })
    
    
   // window.onload = recibosInscripcion_;
    
    function recibosInscripcion(){
       
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/recibosInscripciones", 
        data: $('form').serialize(),
        success:function(datos){
           
            var datos=$.parseJSON(datos);
        },
        error: function(){
                alert("Error en el proceso recibosInscripciones. Informar");
         }
    })
        
        
        
   
    }
    
   // alert('hola')
 //control cambios
    var cambios=true;
    window.onbeforeunload=confirmExit
    function confirmExit() {
        if (cambios ) 
        {
            return 'No ha emitido los recibos para cobrar.'
        }
    }
    
    
    
    $( "#form" ).submit(function( event ) {
        cambios=false;
    });
    
    })
</script>

