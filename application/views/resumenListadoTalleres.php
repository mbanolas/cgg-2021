<div class="container"><br />
<br />
<h3>Resum tallers</h3>
<br />

<?php echo form_open('reporte/pdfLista', array('class'=>"form-horizontal", 'role'=>"form")); ?>
<div class="container">
    <div class="row">
        <div class="form-group">
        <label class="control-label col-sm-2" for="curso">Curs:</label>
        <div class="col-sm-2">
        <?php echo form_dropdown('curso', $optionsCursos,count($optionsCursos),array('class'=>'form-control', 'id'=>'cursos')); ?>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-success" id="pdf">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Baixar PDF</button>
        </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>


<div class="container" >
    <div class="col-sm-10">
        <div id="tablaResumen">
            
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

$('select#cursos option').first().attr('selected','selected')

//verResumen()

function verResumen(){
    $('#tablaResumen').html("");
var curso=$('select#cursos').val()

    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaResumenCurso", 
        data:{curso:curso},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaResumen').html(datos);
             
        },
        error: function(){
                alert("Error en el proceso getTablaResumenCurso. Informar");
         }
    })
}

$('select#cursos').change(function(){
    //verResumen()
}) 


})
</script>
