<div class="container"><br />
<br />
<h3>Informe Cobros</h3>
<br />

<?php echo form_open('cobros/pdfInformeCobros', array('class'=>"form-horizontal", 'role'=>"form")); ?>
  <div class="form-group">
    <label class="control-label col-sm-2" for="taller">Desde:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('type'=>'date','class'=>'form-control','name'=>'desde','id'=>'desde','placeholder'=>'Desde', 'value'=>date('Y-m-d')));   ?>
        <span class="hidden aviso" style="color:red"> Sólo a partir del 12/12/2017</span>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="taller">Hasta:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('type'=>'date','class'=>'form-control','name'=>'hasta','id'=>'hasta','placeholder'=>'Hasta', 'value'=>date('Y-m-d')));   ?>
    </div>
    <div class="col-sm-2">
            <a href="#" class="btn btn-default " id="prepararInforme" name="prepararInforme">
                
                Preparar Informe<img class="ajax-loader hide"   src="<?php echo base_url('images/ajax-loader-2.gif') ?>"></a>
        
        </div>
    <div class="col-sm-2 ">
            <button type="submit" class="btn btn-success hide" id="pdfInformeCobros" name="pdfInformeCobros">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
          Bajar PDF Información cobros</button>
        </div>
    
  </div>
<?php echo form_close(); ?>
</div>

<br>

<div class="container" >
    <div class="col-sm-12">
        <div id="tablaResumen">
            
        </div>
    </div>
</div>
<style>
    .ajax-loader{
         width: 20px;
        height: 20px;
        margin-left: 10px;
    }
    
</style>
<script>
$(document).ready(function () {

//al cargar página lee report
//verResumen()

$('#desde, #hasta').change(function(){
    if($(this).val()<'2017-12-12'){
        $('.aviso').removeClass('hidden')
        $(this).val('2017-12-12')
        return
    }
   $('.aviso').addClass('hidden')
    $('#pdfInformeCobros').addClass('hide')
    $('#tablaResumen').html('')
})


function verResumen(){
    $('#tablaResumen').html("");
    var desde=$('#desde').val()
    var hasta=$('#hasta').val()
   
    var d=new Date(desde)
    var h=new Date(hasta)
    if(h<d){
        var temp=desde
        desde=hasta
        hasta=temp
       $('#desde').val(desde)
       $('#hasta').val(hasta)
    }
    $('.ajax-loader').removeClass('hide')
    
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/cobros/getInformeCobros", 
        data:{desde:desde,hasta:hasta},
        success:function(datos){
           // alert(datos)
           $('.ajax-loader').addClass('hide')
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaResumen').html(datos);
            $('#pdfInformeCobros').removeClass('hide')
             
        },
        error: function(){
                alert("Error en el proceso getInformeCobros. Informar");
         }
    })
}

$('#prepararInforme').click(function(e){
    e.preventDefault()
    
    
    verResumen()
}) 


})
</script>
