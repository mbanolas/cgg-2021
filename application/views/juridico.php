<!--<div style='height:15px;'></div>-->  

<div class="container">
    <?php echo $output; ?>     
</div>

<style>
    .derecha{
        text-align: right;
    }
    .crud-form{
        padding-top: 20px;
    }
</style>

<script type="text/javascript" src="<?php echo base_url('js/push.min.js') ?>"></script>

<script>
$(document).ready(function () {
   
   function notificacion(body,url){
        Push.create('Modificacion Base Datos',{
            body:body,
            icon: "<?php echo base_url() ?>"+"images/parcsardaru.jpg",
            timeout:1
            
        });
    }
   
   $('#save-and-go-back-button, #form-button-save').click(function(e){
   
       var tabla=$('div.table-label div.floatL.l5').html().trim()
       
     //  notificacion('Base Datos '+tabla+' modificada por <?php echo $_SESSION['nombre'] ?>')
       
       console.log('tabla '+tabla)
       
   })
    
    
  $(window).load(function(){
      var tabla=$('div.table-label div.floatL.l5').html().trim()
       
      // notificacion('Base Datos '+tabla+' modificada por <?php echo $_SESSION['nombre'] ?>',"<?php echo base_url() ?>" + "index.php/basedatos/casal_socios")
       
      $('span.derecha').parent().addClass('derecha') 
      $('td.derecha').parent().parent().parent().children().children().children('th:nth-child(4)').addClass('derecha') 
     // $('thead').children('tr').children('th:nth-child(3)').addClass('derecha') 
  }) 
  $('td span.derecha').addClass('derecha')  
  
  var fecha=$('#field-fecha').html()
  if (typeof(fecha) != "undefined"){
  var fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
  if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_nacimiento').html(fechaEuropea)
  }
  
  
 
  
  
})
</script>