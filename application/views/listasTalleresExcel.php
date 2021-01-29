<div class="container"><br />

<?php echo form_open('generarListas', array('class'=>"form-horizontal", 'role'=>"form")); ?>
    
<input class="hide" type="text" id="tipoTaller" name="tipoTaller" value="<?php echo $tipoTaller; ?>" >
<input class="hide" type="text" id="periodoInicial" name="periodoInicial" value="<?php echo $periodo; ?>" > 
<input class="hide" type="text" id="periodoInicialTexto" name="textoPeriodo" value="<?php echo $textoPeriodo; ?>" > 
<div class="container">
    <div class="row" >
        <div class="form-group" style="margin-bottom: 2px; " >
        <label class="control-label col-sm-1" for="curso" style="padding-left:35px;width:90px">Curs:</label>
        <div class="col-sm-2" style="width: 140px; " >
          
        <?php echo form_dropdown('curso', $optionsCursos,0,array('class'=>'form-control', 'id'=>'cursos')); ?>
        </div>
        <!-- Periodos radio selección -->
        <!--
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'C')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>       
            <input style="margin-left: 0px;" type="radio" id="C" name="periodoCheckbox[]" value="C" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Curso
        </div> 
        -->
        
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T1')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>      
            <input style="margin-left: 0px;" type="radio" id="T1" name="periodoCheckbox[]"  value="T1" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T2')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" id="T2" name="periodoCheckbox[]" value="T2" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2 
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 'T3')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" id="T3" name="periodoCheckbox[]" value="T3" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3 
        </div>
        <!-- Botón PDF -->
        <div class="col-sm-2" style="margin-right:0px;padding-right:0px">
            <button type="submit" class="btn btn-success listasExcel" id="listasExcel" name="listados">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Genarar Excel </button>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px" >
            <button type="submit" class="btn btn-success listasExcel" id="listasExcelSinTelefono" name="listados_sin">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Generar Excel Sin Teléfono</button>
        </div>
        </div>
        
        <div class="form-group">
        <label class="control-label col-sm-1"  style="padding-left:35px;width:90px"></label>
        <div class="col-sm-2" style="width:140px">
        </div>
        <?php if(true){ ?>
        <div class="col-sm-2 radio">
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
        
        <?php } ?>
        <div class="col-sm-2 radio">
            <?php if ($tipoTaller == 'voluntaris')
                $checked = "checked='checked'";
            else
                $checked = ""
                ?> 
            <input style="margin-left: 0px;" type="radio" name="tipoTaller[]" value="voluntaris" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Voluntaris
        </div>    
        <div class="col-sm-2 sin_radio">
        </div>
        <!--
        <div class="col-sm-2">
            <button type="submit" class="btn btn-success" id="excel_" name="excel">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Preparar Listados Excel</button>
        </div>
        -->
        </div>
        <div class="form-group">
        <label class="control-label col-sm-1"  style="padding-left:35px;width:90px">Inici:</label>
        <div class="col-sm-2" >
            <input class="fecha"  type="date" name="inicio" value="" > 
        </div>
        <label class="control-label col-sm-1"  style="padding-left:35px;width:90px">Fins:</label>
        <div class="col-sm-2" >
            <input class="fecha"  type="date" name="finaliza" value="" > 
        </div>
        
        </div>
        
         <div class="form-group" style="margin-bottom: 2px; ">
        <label class="control-label col-sm-1"  style="padding-left:5px;margin-right:5px;width:70px">Festius:</label>
        <div class="col-sm-2_ "   >
            <input class="fecha"  type="date" name="festivo0" value="" > 
            <input class="fecha"  type="date" name="festivo1" value="" > 
            <input class="fecha"  type="date" name="festivo2" value="" > 
            <input class="fecha"  type="date" name="festivo3" value="" > 
            <input class="fecha"  type="date" name="festivo4" value="" > 
            <input class="fecha"  type="date" name="festivo5" value="" > 
        </div>
        
        </div>
        
          <div class="form-group" style="margin-bottom: 2px; ">
        <label class="control-label col-sm-1"  style="padding-left:5px;margin-right:5px;width:70px"></label>
        <div class="col-sm-2_ "   >
            <input class="fecha"  type="date" name="festivo6" value="" > 
            <input class="fecha"  type="date" name="festivo7" value="" > 
            <input class="fecha"  type="date" name="festivo8" value="" > 
            <input class="fecha"  type="date" name="festivo9" value="" > 
            <input class="fecha"  type="date" name="festivo10" value="" > 
            <input class="fecha"  type="date" name="festivo11" value="" > 
        </div>
        
        </div>
        
    </div>
</div>


<h3>Curso <span id="textoCurso"><?php echo ucwords($tipoTaller) ?></span> Talleres <span id="textoTipoTaller"><?php echo ucwords($tipoTaller) ?></span> - Periodo <span id="textoPeriodo"><?php echo $textoPeriodo; ?></span></h3>


<div class="container" >
    <div class="col-sm-12">
        <div id="tablaResumen">
            
        </div>
    </div>
    <div class="col-sm-2" style="margin-top:20px">
            <button type="submit" class="btn btn-success listasExcel" id="listasExcel" name="listados">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Generar Excel</button>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px;margin-top:20px" >
            <button type="submit" class="btn btn-success listasExcel" id="listasExcelSinTelefono" name="listados_sin">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Generar Excel Sin Teléfono</button>
        </div>
</div>

<?php echo form_close(); ?>

</div>
<style>
     .radio{
        width:140px;
        height: 34px;
       
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 5px;
    }   
    .fecha{
        width:160px;
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
    
    .profesional{
        color:blue;
    }
    
</style>
<script>
$(document).ready(function () {

var tipoTaller=$('#tipoTaller').val()

var periodo=$('input#periodoInicial').val()
//alert(periodo)


$('.listasExcel').click(function(e){
    var textoCurso=$('option[value="'+$('#cursos').val()+'"').html()
    var inicio=$('input[name=inicio]').val()
    var finaliza=$('input[name=finaliza]').val()
    if(textoCurso.lastIndexOf(inicio.substr(0,4))==-1){
        $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("La data d'inici no correspond al curs")
            $("#myModal").modal() 
            return false
    }
    if(textoCurso.lastIndexOf(finaliza.substr(0,4))==-1){
        $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("La data de finalització no correspond al curs")
            $("#myModal").modal() 
            return false
    }
    for (var i = 0; i < 12; i++) { 
        var fechaFestivo=$('input[name="festivo'+i+'"]').val()
        //alert(fechaFestivo)
        if(fechaFestivo=="" || fechaFestivo.substr(0,4)=="0000" ) continue;
        //if(textoCurso.lastIndexOf(fechaFestivo.substr(0,4))==-1 ){
        if(fechaFestivo<inicio || fechaFestivo>finaliza){     
        $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("La data "+fechaFestivo.substr(8,2)+"/"+fechaFestivo.substr(5,2)+"/"+fechaFestivo.substr(0,4)+" no correspond al periode")
            $("#myModal").modal() 
            return false
        }
    }
    
    
    
    
    if(finaliza<=inicio){
        $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("La data de finalització no pot ser major que la d'inici")
            $("#myModal").modal() 
            return false
    } 
   
        if(inicio=="" || finaliza==""){
            $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("Manca indicar data d'inici i / o finalització del període")
            $("#myModal").modal()  
            return false
    }

    if(!$('.taller').is(':checked')) {
            $('#myModal').css('color','black')
            $('.modal-title').html('Informació')
            $('.modal-body>p').html("No s'ha marcat cap taller per llistar")
            $("#myModal").modal() 
            return false
        }
       
    
})

$('input[name="todos"]').on('click', function () {
    alert("We have Access!");
});

$('#tomaba').on('click', function () {
    alert("We have Accessnnnnn!");
});

$(document).on('click', '#marcarTodos', function(){
    //alert('holaaaaa')
    if($('#marcarTodos').is(':checked')) { 
        //alert('checked')
        $('.taller').prop("checked", true);
    }
    else{
        //alert('NO checked')
         $('.taller').prop("checked", false);
    }
})

$('.taller_ :checkbox').change(function() {
alert("We have Access!");
        // do stuff here. It will fire on any checkbox change

}); 


$('input[name="tipoTaller[]"]').click(function () {
    tipoTaller = $(this).val()
    $('input#tipoTaller').val(tipoTaller)
    $('span#textoTipoTaller').html(tipoTaller)
    obtenerFechas()
    obtenerTalleres()
})

//al entrar, se selecciona por omisión, el último curso 
//$('select#cursos option').last().attr('selected','selected')

$('input[name="periodoCheckbox[]"]').click(function () {
            periodo = $(this).val()
            //alert(periodo)
            $('input#periodoInicial').val(periodo)
            obtenerTextosCursoPeriodo()
            obtenerFechas()
            obtenerTalleres()
        })






//al cargar página lee report


function obtenerTalleres(){
    
    $('#tablaResumen').html("");
    var curso=$('select#cursos').val()
    //alert('periodo '+periodo)
    //alert('curso '+curso)
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaTalleresListas", 
        data:{
              periodo:periodo,  
              curso:curso,
              tipoTaller:tipoTaller},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos[0]['nombre'])
            
            $('#textoCurso').html(datos['textoCurso'])
            $('#textoPeriodo').html(datos['textoPeriodo'])
            $('#tablaResumen').append(datos)
            
        },
        error: function(){
                alert("Error en el proceso obtener Talleres. Informar");
         }
    })
}


function obtenerTextosCursoPeriodo(){
    
    $('#tablaResumen').html("");
    var curso=$('select#cursos').val()
    //alert('periodo '+periodo)
    //alert('curso '+curso)
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTextosCursoPeriodo", 
        data:{
              periodo:periodo,  
              curso:curso,
              tipoTaller:tipoTaller},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos['inicio'])
            
            $('#textoCurso').html(datos['textoCurso'])
            $('#textoPeriodo').html(datos['textoPeriodo'])
            $('input#periodoInicialTexto').val(datos['textoPeriodo']);
            $('input#periodoInicial').val(periodo);
            
        },
        error: function(){
                alert("Error en el proceso obtener Fechas. Informar");
         }
    })
}

function obtenerFechas(){
    borrarFechas()
    $('#tablaResumen').html("");
    var curso=$('select#cursos').val()
    //alert('periodo '+periodo)
    //alert('curso '+curso)
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getFechasPeriodo", 
        data:{
              periodo:periodo,  
              curso:curso,
              tipoTaller:tipoTaller},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos['inicio'])
            $('input[name="inicio"').val(datos['inicio'])
            $('input[name="finaliza"').val(datos['finaliza'])
            for (var i = 0; i < 12; i++) { 
                $('input[name="festivo'+i+'"]').val(datos['festivo'+i])
            }
           
            
        },
        error: function(){
                alert("Error en el proceso obtener Fechas. Informar");
         }
    })
}

function borrarFechas(){
    $('input[name="inicio"').val("")
            $('input[name="finaliza"').val("")
            for (var i = 0; i < 12; i++) { 
                $('input[name="festivo'+i+'"]').val("")
            }
}

$('input[name="inicio"], input[name="finaliza"], input[name="festivo0"], input[name="festivo1"], input[name="festivo2"], input[name="festivo3"], input[name="festivo4"], input[name="festivo5"], input[name="festivo6"],input[name="festivo7"], input[name="festivo8"], input[name="festivo9"], input[name="festivo10"], input[name="festivo11"]').change(function(){
        var nombre=$(this).attr('name')
        var valor=$(this).val()
        //console.log(valor)
        var curso=$('select#cursos').val()
        //alert('input[name="inicio"] '+nombre+' '+valor)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/talleres/grabarFechas", 
            data: {curso:curso,periodo:periodo,nombre:nombre,valor:valor},
            success:function(datos){
            },
            error:function(){
                alert('Error en la grabación fechas. Informar al Administrador')
            },
        })
    })

$('select#cursos').change(function(){
    obtenerTextosCursoPeriodo()
    obtenerFechas()
    obtenerTalleres()
}) 

$('input#'+periodo).click()
    
})
</script>
