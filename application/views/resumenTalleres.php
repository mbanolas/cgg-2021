<div class="container"><br />

<?php echo form_open('reporte/informes', array('class'=>"form-horizontal", 'role'=>"form")); ?>
<input class="hide" type="text" id="tipoTaller" name="tipoTaller" value="<?php echo $tipoTaller; ?>" >
<input class="hide" type="text" id="periodoInicial" name="periodoInicial" value="<?php echo $periodo; ?>" > 
<div class="container">
    <div class="row" >
        <div class="form-group" style="margin-bottom: 2px">
        <label class="control-label col-sm-1" for="curso" style="padding-left:35px;width:86px">Curs:</label>
        <div class="col-sm-2">
            <?php echo form_dropdown('curso', $optionsCursos,count($optionsCursos),array('class'=>'form-control', 'id'=>'cursos')); ?>
        </div>
        <!--
        <div class="col-sm-2 radio" style="border:0px">
            <?php if ($periodo == "C")
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>       
            <input  class="hide" style="margin-left: 0px;" type="radio" name="periodo[]" id="C" value="C" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>   
        
        -->
        <div class="col-sm-2 radio">
            <?php if ($periodo == "T1")
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>      
            <input style="margin-left: 0px;" type="radio" name="periodo[]"  id="T1" value="T1" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == "T2")
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" name="periodo[]" id="T2" value="T2" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2 
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == "T3")
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
             <input style="margin-left: 0px;" type="radio" name="periodo[]" id="T3" value="T3" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3 
        </div>
        
       <!--
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'C')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>       
            <input style="margin-left: 0px;" type="radio" name="periodoCheckbox[]" value="C" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Curs
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T1')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>      
            <input style="margin-left: 0px;" type="radio" name="periodoCheckbox[]"  value="T1" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T2')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" name="periodoCheckbox[]" value="T2" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2 
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T3')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" name="periodoCheckbox[]" value="T3" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3 
        </div>
        -->
        <!--
        </div>
        <div class="form-group">
        <label class="control-label col-sm-1" for="curso" style="padding-left:35px;width:90px"></label>
        <div class="col-sm-2">
        </div>
        -->
        </div>
    <div class="row" > 
        <div class="col-sm-3 " style="width:292px">
        </div>
        <div class="col-sm-2 radio form-control">
            <?php if ($tipoTaller == 'tots')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>       
            <input style="margin-left: 0px;" type="radio" checked="checked" name="tipoTaller[]" value="tots" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tots
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($tipoTaller == 'professionals')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?> 
            <input style="margin-left: 0px;" type="radio" name="tipoTaller[]"  value="professionals" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professionals
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($tipoTaller == 'voluntaris')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?> 
            <input style="margin-left: 0px;" type="radio" name="tipoTaller[]" value="voluntaris" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Voluntaris
        </div>    
        <div class="col-sm-1 "></div>
        <div class="col-sm-2 hidden" style="padding-left:40px;width:140px;">
            <button type="submit" class="btn btn-success" id="pdf" name="pdf">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Exporta PDF</button>
        </div>
        <div class="col-sm-2" style="padding-left:40px;width:140px;">
            <button type="submit" class="btn btn-success " id="excel" name="excel">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Exporta Excel</button>
        </div>
       
     
        </div>
    </div>
</div>
<?php echo form_close(); ?>

</div>

<h3>Resum tallers: <span id="textoTipoTaller"><?php echo ucwords($tipoTaller) ?></span>  - <span id="textoPeriodo"><?php echo $textoPeriodo; ?></span></h3>


<div class="container" >
    <div class="col-sm-12">
        <div id="tablaResumen">
            
        </div>
    </div>
</div>
<style>
     .radio{
        width:140px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 5px;
    }   
    .sin_radio{
        width:140px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        
        margin-right: 5px;
    } 
    
    
    
    
</style>


<script>
$(document).ready(function () {

var tipoTaller=$('#tipoTaller').val()

var periodo=$('input#periodoInicial').val()
//alert(periodo)

$('input[name="tipoTaller[]"]').click(function () {
    tipoTaller = $(this).val()
    $('input#tipoTaller').val(tipoTaller)
    $('span#textoTipoTaller').html(tipoTaller)
            verResumen()
})

$('input[name="periodo[]"]').click(function () {
            periodo = $(this).val()
            //alert(periodo)
            $('input#periodoInicial').val(periodo)
            verResumen()
            
            //alert(periodo)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/talleres/setUltimoPeriodo",
                data: {periodo: periodo},
                success: function (datos) {
                    //alert(datos)
                    //var datosJSON=$.parseJSON(datos);
                    return
                },
                error: function () {
                    alert("Error en el procés de registrar últim període. informar");
                }
            })
        })



//al entrar, se selecciona por omisión, el último curso 
$('select#cursos option').first().attr('selected','selected')

//al cargar página lee report
verResumen()

function verResumen(){
    $('#tablaResumen').html("");
    var curso=$('select#cursos').val()
    //alert('periodo '+periodo)
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaResumenCurso", 
        data:{
              periodo:periodo,  
              curso:curso,
              tipoTaller:tipoTaller},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaResumen').html(datos['table']);
            $('#textoPeriodo').html(datos['textoPeriodo']); 
        },
        error: function(){
                alert("Error en el proceso getTablaResumenCurso. Informar");
         }
    })
}

$('select#cursos').change(function(){
    verResumen()
}) 


})
</script>
