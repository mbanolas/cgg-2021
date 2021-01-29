
<div class="container"><br />
<h3>Llistat usuaris/usuàries en llista espera per taller</h3>
<br />

<?php echo form_open('talleres/pdfReservas', array('class'=>"form-horizontal", 'role'=>"form")); ?>
  <div class="form-group">
      
    <label class="control-label col-sm-2" for="curso">Curs:</label>
    <div class="col-sm-2">
       
    <?php echo form_dropdown('curso', $optionsCursos,0,array('class'=>'form-control', 'id'=>'cursos')); ?>
    </div>
    <!--
        <div class="col-sm-2 radio" style="border:0px">
            <?php if ($periodo == 7)
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>       
            <input class="hide" style="margin-left: 0px;" type="radio" name="periodo[]" value="C" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>  
    -->
        <div class="col-sm-2 radio">
            <?php if ($periodo == 4)
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>      
            <input style="margin-left: 0px;" type="radio" name="periodo[]"  value="T1" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 1
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 2)
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
            <input style="margin-left: 0px;" type="radio" name="periodo[]" value="T2" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 2 
        </div>    
        <div class="col-sm-2 radio">
            <?php if ($periodo == 1)
                $checked = "checked='checked'";
            else
                $checked = ""
                ?>  
             <input style="margin-left: 0px;" type="radio" name="periodo[]" value="T3" <?php echo $checked ?>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trimestre 3 
        </div>
  </div>
<div class="col-sm-12">
  <div class="form-group">
    <label class="control-label col-sm-1" for="taller">Taller:</label>
    <div class="col-sm-2"> 
        <?php echo form_input(array('class'=>'clearable form-control searchable-input','name'=>'buscarTaller','id'=>'buscarTaller','placeholder'=>'Cercar taller'));   ?>
    </div>
    <div class="col-sm-3"> 
      <?php echo form_dropdown('taller', $optionsTalleres,null,array('class'=>'form-control', 'id'=>'talleres')); ?>
    </div>
   
    
 
    <div class="col-sm-2 hide orden">
        <?php echo form_radio('orden', "ordenOrden",true,array('class'=>'', 'id'=>'ordenOrden')); ?> Ordenar per nombre ordre
    </div>
     <div class="col-sm-2 hide orden">
        <?php echo form_radio('orden', "ordenNombre",false,array('class'=>'', 'id'=>'ordenNombre')); ?> Ordenar per Nom
    </div>
    <div class="col-sm-2 hide orden">
        <?php echo form_radio('orden', "ordenNumSocio",false,array('class'=>'', 'id'=>'ordenNumSocio')); ?> Ordenar per Núm usuari/usuària 
    </div>
     </div>
</div>

<div class="col-sm-6"></div>
    <div class="col-sm-2">
            <button type="submit" class="btn btn-success hide" id="pdfReservasSinTelefono" name="pdfReservasSinTelefono">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
           PDF SENSE telèfon</button>
        </div>
    <div class="col-sm-2">
            <button type="submit" class="btn btn-success hide" id="pdfReservasSin" name="pdfReservasSin">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
           PDF AMB telèfon</button>
    </div>
    
   
  
<?php echo form_close(); ?>
</div>
<div class="container" >
    <div class="col-sm-8">
        <div id="tablaAsistentes">
            
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
 .clearable{
  background: #fff url(http://i.stack.imgur.com/mJotv.gif) no-repeat right -10px center;
  border: 1px solid #999;
  padding: 3px 18px 3px 4px;     /* Use the same right padding (18) in jQ! */
  border-radius: 3px;
  /*transition: background 0.4s;*/
}
.clearable.x  { background-position: right 5px center; } /* (jQ) Show icon */
.clearable.onX{ cursor: pointer; }              /* (jQ) hover cursor style */
.clearable::-ms-clear {display: none; width:0; height:0;} /* Remove IE default X */   
 
th.numDerecha{
    text-align: right;
}

</style>

<script>
    
$(document).ready(function () {



var orden=0;
$('#ordenOrden').change(function(){
    var taller=$('select#talleres').val()
    orden=0
    ponerTableReservasTaller(taller,orden)
})

$('#ordenNombre').change(function(){
    var taller=$('select#talleres').val()
    orden=1
    ponerTableReservasTaller(taller,orden)
})

$('#ordenNumSocio').change(function(){
    var taller=$('select#talleres').val()
    orden=2
    ponerTableReservasTaller(taller,orden)
})



$('select#cursos option').first().attr('selected','selected')

var curso=$('select#cursos').val()

var periodo=$('input[name="periodo[]"]:checked').val()



$('#buscarTaller').keyup(function(){
   // alert('buscarTaller')
   //filtroTalleres($(this).val())
   console.log('valor de buscarTaller '+$(this).val())
    $('#tablaAsistentes').html('')
   setTimeout(
  function() 
  {
   taller=$('#talleres').val()
    console.log('taller '+taller)
    ponerTableReservasTaller(taller,orden)
  }, 1000);
    
    
    //ponerTableReservasTaller(taller,orden)
})



 $('input[name="periodo[]"]').click(function () {
            $('#pdfReservasSin').addClass('hide')
            $('#pdfReservasCon').addClass('hide')
            $('#pdfReservasSinTelefono').addClass('hide')
            $('#tablaAsistentes').html("");
            $('.orden').addClass('hide')
            $('#talleres').val(0)
            
            periodo = $(this).val()
            //alert(periodo)
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
                    alert("Error en el proceso de registrar ultimo periodo. Informar");
                }
            })
        })

$('select#cursos').change(function(){
    curso=$('select#cursos').val()
    cursos()
    $('#tablaAsistentes').html('')
})
//cargar todos los talleres de curso
cursos()

function cursos(){
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTalleres", 
        data:{curso:curso,periodo:periodo},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
           
            $('select#talleres option').remove();
            if(datos.length==0)
                $('#talleres').append('<option value="'+0+'">'+'No hi ha cap taller registrat'+'</option>')
            $('#talleres').append('<option value="'+0+'">'+'- Seleccionar un taller'+'</option>')
        $.each(datos, function(index, value){
                 var id=value['id']
                 var nombre=value['nombre']
                 var option='<option value="'+id+'">'+nombre+'</option>'
                 $('#talleres').append(option)
             })
             sortSelectOptions('#talleres')
            $('select#talleres option[value="0"').attr('selected','selected')
        },
        error: function(){
                alert("Error en el proceso get Talleres. Informar");
         }
    })
    }
 
 
function ponerTableReservasTaller(taller,orden){
//alert(periodo)
    if(taller==0) {
        $('#tablaAsistentes').html("");
        return false;
        }
    // alert(' orden '+orden)
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaReservasTaller", 
        data:{taller:taller,
             periodo:periodo,
              orden:orden},
        success:function(datos){
            
           
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaAsistentes').html(datos);
        },
        error: function(){
                alert("Error en el proceso getTablaReservasTaller. Informar");
         }
   
}) 
}
 
// var orden=0;
// $('#ordenNumSocio').change(function(){
//     var taller=$('select#talleres').val()
//     orden=1
//     ponerTableReservasTaller(taller,orden)
// })
// $('#ordenNombre').change(function(){
//     var taller=$('select#talleres').val()
//     orden=0
//     ponerTableReservasTaller(taller,orden)
// })
 
$(document).on('change','select#talleres',function(){
               console.log("PROBANDO"+$(this).val());
          }); 
 
$('select#talleres').change(function(){
    $('#tablaAsistentes').html('')
    $('#buscarTaller').val('')
    if($(this).val()==0){
        $('#pdfReservasSin').addClass('hide')
        $('#pdfReservasCon').addClass('hide')
        $('#pdfReservasSinTelefono').addClass('hide')
        $('#tablaAsistentes').html("");
        $('.orden').addClass('hide')
    return false
}


//inicialización
$('#pdfReservasSin').removeClass('hide')
$('#pdfReservasCon').removeClass('hide')
$('#pdfReservasSinTelefono').removeClass('hide')
$('#tablaAsistentes').html("");
$('.orden').removeClass('hide')

var taller=$('select#talleres').val()

ponerTableReservasTaller(taller,orden)
}) 
    
$('#verAsistentes').click(function(e){
    e.preventDefault()
    var taller=$('select#talleres').val()
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTablaReservasTaller", 
        data:{taller:taller},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos)
            $('#tablaAsistentes').html(datos);
             
        },
        error: function(){
                alert("Error en el proceso getTablaReservasTaller. Informar");
         }
    })

})    
    

$('input.searchable-input').keyup(function(){
    if($(this).val()){
    $(this).css('border-color','#444')
    $(this).css('border-style','dashed')
    var filtro=$(this).val()
    filtroTalleres(filtro)
    }
    else{
    $(this).css('border','1px solid #ccc')  
    filtroTalleres(" ")
    }
   
})  

 function filtroTalleres(filtro){
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTalleresFiltroCurso/"+curso, 
        data:{filtro:filtro},
        success:function(datos){
           // alert(datos)
            var datos=$.parseJSON(datos);
             
             $('select#talleres option').remove();
            
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#talleres').append(option)
             })
             sortSelectOptions('#talleres')
             
        },
        error: function(){
                alert("Error en el proceso getTalleresFiltroCurso. Informar");
         }
    })
    }

        // CLEARABLE INPUT
        function tog(v){return v?'addClass':'removeClass';} 
        $(document).on('input', '.clearable', function(){
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc')  
            filtroTalleres(" ")
            setTimeout(
                function() 
                {
                 taller=$('#talleres').val()
                  console.log('taller '+taller)
                  ponerTableReservasTaller(taller,orden)
                }, 1000);
        });


function sortSelectOptions(selectElement) {
	var options = $(selectElement+" option");

	options.sort(function(a,b) {
		if (a.text.toUpperCase() > b.text.toUpperCase()) return 1;
		else if (a.text.toUpperCase() < b.text.toUpperCase()) return -1;
		else return 0;
	});

	$(selectElement).empty().append( options );
        $(selectElement+" option").first().attr('selected','selected')
}
})

</script>
