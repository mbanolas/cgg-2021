<!--<div style='height:15px;'></div>-->  

<div class="container">
    <?php echo $output; ?>     
</div>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


  
<style>
    .derecha{
        text-align: right;
    }
    .crud-form{
        padding-top: 20px;
    }
   
    input#field-id_agrupacion{
        width: 80px;
    }
    input#field-inicio_1,input#field-inicio_2,input#field-final_1,input#field-final_2,
    input#field-precio_trimestre,input#field-precio_curso,input#field-precio_rosa_trimestre,input#field-precio_rosa_curso,
    input#field-num_maximo,input#field-num_reservas{
        width: 120px;
    }
    select#field-tipo_taller{
        width: 130px;
        height:50px;
        color:red;
    }
    tbody>tr>td:nth-child(3),
    tbody>tr>td:nth-child(4),
    tbody>tr>td:nth-child(5),
    tbody>tr>td:nth-child(6),
    tbody>tr>td:nth-child(7),
    tbody>tr>td:nth-child(8),
    tbody>tr>td:nth-child(9),
    tbody>tr>td:nth-child(10),
    tbody>tr>td:nth-child(11)
    
    {
        padding-top:7px;
    }
    .literal{
        font-weight: bold;
        padding-top:8px;
        
    }
    
</style>

<script type="text/javascript" src="<?php echo base_url('js/push.min.js') ?>"></script>

<script>
$(document).ready(function () {

  $('.delete-row').click(function(e){
      console.log('delete row')
    })
   
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
   

    $('.gc-export').addClass('hide')
    
  $(window).load(function(){
      var tabla=$('div.table-label div.floatL.l5').html().trim()
       
      // notificacion('Base Datos '+tabla+' modificada por <?php echo $_SESSION['nombre'] ?>',"<?php echo base_url() ?>" + "index.php/basedatos/casal_socios")
       
      $('span.derecha').parent().addClass('derecha') 
      $('td.derecha').parent().parent().parent().children().children().children('th:nth-child(4)').addClass('derecha') 
     // $('thead').children('tr').children('th:nth-child(3)').addClass('derecha') 
  }) 
  $('td span.derecha').addClass('derecha')  
  
  var fecha=$('#field-fecha_nacimiento').html()
  if (typeof(fecha) != "undefined"){
  var fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
  if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_nacimiento').html(fechaEuropea)
  }
  
  
 fecha=$('#field-fecha_alta').html()
 if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_alta').html(fechaEuropea)
 }
 
 fecha=$('#field-fecha_modificacion').html()
 if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_modificacion').html(fechaEuropea)
 }
 
 
 
  fecha=$('#field-fecha_baja').html()
  if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_baja').html(fechaEuropea)
  }
  
  var telefono=$('#field-telefono_1').html()
  if (typeof(fecha) != "undefined"){
  telefono=telefono.replace(/ /g,"")
  if(telefono.length==9)
      telefono=telefono.substr(0,3)+' '+telefono.substr(3,3)+' '+telefono.substr(6,3);
  $('#field-telefono_1').html(telefono)
  }
  
  telefono=$('#field-telefono_2').html()
  if (typeof(fecha) != "undefined"){
  telefono=telefono.replace(/ /g,"")
  if(telefono.length==9)
      telefono=telefono.substr(0,3)+' '+telefono.substr(3,3)+' '+telefono.substr(6,3);
  $('#field-telefono_2').html(telefono)
  }
  
  $("#field-inicio_1, #field-inicio_2,#field-final_1,#field-final_2").attr("placeholder", "hh:mm").blur();
  $("#field-precio_trimestre").attr("placeholder", "Importe en €").blur();
  $("#field-precio_curso").attr("placeholder", "Importe en €").blur();
  
  $('#field-fecha_modificacion').attr('disabled','disabled')
  
 
  
  
})
</script>