
<br><br>
<?php //var_dump($infoUltimoRecibo);
if(isset($infoUltimoRecibo['lineas'][0])) {
?>
    <div class="container">
    <h3>Dades últim rebut</h3><hr>
    <?php echo form_open('talleres/anularRecibo/'.$infoUltimoRecibo['lineas'][0]['id_recibo'],array('id'=>'form')); ?>
    <?php $tarjeta_rosa=$infoUltimoRecibo['lineas'][0]['tarjeta_rosa'];
        $tarjetaRosa="";
        if ($tarjeta_rosa=='Sí') $tarjetaRosa=" - Tarjeta Rosa";
    ?>   
    <h4>Curs: <?php echo $infoUltimoRecibo['lineas'][0]['nombreCurso']; ?></h4>
    <h4>Usuari/Usuària: <strong><?php echo $infoUltimoRecibo['lineas'][0]['nombreSocio'].' '.$infoUltimoRecibo['lineas'][0]['apellidosSocio'].' ('.$infoUltimoRecibo['lineas'][0]['id_socio'].')</strong> '.$tarjetaRosa; ?></h4>
    <h3>Talleres:</h3>
<?php }
else if(isset($infoUltimoRecibo['lineasEspera'][0])) {
?>
    <div class="container">
    <h3>Dades últim rebut</h3><hr>
    <?php echo form_open('talleres/anularRecibo/'.$infoUltimoRecibo['lineasEspera'][0]['id_recibo'],array('id'=>'form')); ?>
    <?php $tarjeta_rosa=$infoUltimoRecibo['lineasEspera'][0]['tarjeta_rosa'];
        $tarjetaRosa="";
        if ($tarjeta_rosa=='Sí') $tarjetaRosa=" - Tarjeta Rosa";
    ?>   
    <h4>Curs: <?php echo $infoUltimoRecibo['lineasEspera'][0]['nombreCurso']; ?></h4>
    <h4>Usuari/Usuària: <strong><?php echo $infoUltimoRecibo['lineasEspera'][0]['nombreSocio'].' '.$infoUltimoRecibo['lineasEspera'][0]['apellidosSocio'].' ('.$infoUltimoRecibo['lineasEspera'][0]['id_socio'].')</strong> '.$tarjetaRosa; ?></h4>
    <h3>Talleres:</h3>
<?php } ?>
<?php
 $salida=""; 
 $totalRecibo=0;
 $totalMetalico=0;
 $totalTarjeta=0;
if(isset($infoUltimoRecibo['lineas'][0])) {
?>
<?php $salida.="<table>"; ?>
<?php if(count($infoUltimoRecibo['lineas'])) { 
     //var_dump($inscripcion);
    $nombres=array();
    
    foreach($infoUltimoRecibo['lineas'] as $k=>$v){
        $totalTarjeta+=$v['tarjeta'];
        $totalRecibo+=$v['importe'];
        $salida.='<tr>';
        $tipoTaller=$v['tipo_taller'];
        $nombreTaller=$v['nombreTaller'];
        $tipoTallerPago=$tipoTaller.'Pago';
        log_message('INFO','----------------------- '.$v['periodos']);
        $periodos='C';
        switch($v['periodos']){
            case 4:
                $periodos='T1';
                break;
            case 2:
                $periodos='T2';
                break;
           case 1:
                $periodos='T3';
                break;
            case 3:
                $periodos='T2,T3';
                break;
             case 6:
                $periodos='T1,T2';
                break;
           case 7:
                $periodos='C';
                break;     
        }
        $salida.="<td width='400'>- $nombreTaller<span > ($tipoTaller)</span></td>"
            . "<td width='100'>($periodos) </td>"
            . "<td width='90' style='text-align:right' class='$tipoTaller'>".number_format($v['importe'],2)."  € </td><td class=''></td>"
            . "";
    $salida.='</tr>';
    }
    $salida.='</table>';
    $totalMetalico=$totalRecibo-$totalTarjeta;
    }
} ?>
    
    
<?php $salida.="<table>"; ?>
<?php if(count($infoUltimoRecibo['lineasEspera'])) { 
     //var_dump($inscripcion);
    $nombres=array();
    
    foreach($infoUltimoRecibo['lineasEspera'] as $k=>$v){
        
        $salida.='<tr>';
        $tipoTaller=$v['tipo_taller'];
        $nombreTaller=$v['nombreTaller'];
        $tipoTallerPago=$tipoTaller.'Pago';
        $periodo='C';
        switch($v['periodos']){
            case 4:
                $periodos='T1';
                break;
            case 2:
                $periodos='T2';
                break;
           case 1:
                $periodos='T3';
                break;
           case 7:
                $periodos='C';
                break;     
        }
        $salida.="<td width='400'>- $nombreTaller<span > ($tipoTaller)</span></td>"
            . "<td width='100'>($periodos) </td>"
            . "<td style='text-align:right' class='$tipoTaller'>"."Llista d'espera "." </td><td class=''></td>"
            . "";
    $salida.='</tr>';
    }
    $salida.='</table>';
    
    }
 ?>    
  
    
<?php  
    echo $salida."<h2>Total a Pagar: $totalRecibo € </h2>"."<h4>(Metàl.lic: <span id='metalico'> $totalMetalico € </span>; Targeta: <span id='tarjeta'> $totalTarjeta € </span>)</h4>";
?>    
    
    
    
    
    
    

<br>
<div class="form-group"> 
    <div class="col-sm-10">
      <button type="submit" class="btn btn-danger" id="emitirRecibo">Destruir rebuts impresos (casal i usuari/usuària), si s'han lliurat i confirmar anul·lació</button>
      <button class="btn btn-default cancel-button " type="button" id="cancel-button">
           <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Cancel·lar</button>
    </div>
</div>
<?php echo form_close(); ?>


</div>

<style>
    td{
        padding:2px;
        font-size: 18px;
    }
    hr{
        padding:0px;
    }
</style>
<script>
$(document).ready(function () {    
    
     var cambios=true;
    window.onbeforeunload=confirmExit
    function confirmExit() {
        if (cambios ) 
        {
            return "No ha realitzat l'anul·lació del rebut"
        }
    }
    
    
    
    $('#cancel-button').click(function(){
        //cambios=false
        window.location.href = "<?php echo base_url() ?>"+"index.php/talleres/seleccionar";
    })
    
    
    
    
    $( "#form" ).submit(function( event ) {
        cambios=false;
    });
    
     })
</script>