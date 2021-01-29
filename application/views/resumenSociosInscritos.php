<div class="container"><br />
<br />
<h3>Resum usuaris/usuàries inscrits en tallers</h3>
<br />

<?php echo form_open('reporte/pdfSociosInscritos', array('class'=>"form-horizontal", 'role'=>"form")); ?>
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
          Baixar PDF <img  id="loader" class="hide " src="<?php echo base_url() ?>/images/ajax-loader-2.gif" alt="Procesando" height="20" width="20"></button>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
        <label class="control-label col-sm-2 hide loader" for="curso">Processant</label>
        <div class="col-sm-2">
        <img  class="hide loader" src="<?php echo base_url() ?>/images/ajax-loader-2.gif" alt="Procesando" height="42" width="42">
        </div>
        
        </div>
    </div>

    
</div>
<?php echo form_close(); ?>
</div>



<div class="container" >
    <div class="col-sm-6">
        <div id="tablaResumen">
            
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

$('#pdf').click(function(e){
    
    $('#loader').removeClass('hide')
    var curso=$('select#cursos').val()
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/reporte/pdfSociosInscritos", 
        data:{curso:curso},
        success:function(datos){
            $('#loader').addClass('hide')
            //alert(datos)
            //var datos=$.parseJSON(datos)
           
             
        },
        error: function(){
                alert("Error en el proceso getTablaSociosInscritosCurso. Informar");
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
    $('.loader').removeClass('hide')
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaSociosInscritosCurso", 
        data:{curso:curso},
        success:function(datos){
            $('.loader').addClass('hide')
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaResumen').html(datos);
             
        },
        error: function(){
                alert("Error en el proceso getTablaSociosInscritosCurso. Informar");
         }
    })
}

$('select#cursos').change(function(){
    verResumen()
}) 


})
</script>
