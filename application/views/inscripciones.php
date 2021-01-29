<div class="container inscripciones" >
<h3 style="margin-left:10px;">Inscripcions</h3>
<h4 style="margin-left:10px;">Curs: <strong><?php echo $curso.' - '.$periodo;?></strong></h4>
<?php $telefono="";
    $telefono_1=$socio->telefono_1;
    $telefono_2=$socio->telefono_2;
    if($telefono_1 || $telefono_2) $telefono=' - Teléfono: ';
    if($telefono_1) $telefono.=$telefono_1;
    if($telefono_2) $telefono.=' - '.$telefono_2;
    ?>

<?php echo form_open('talleres/registrarInscripcionesNuevo', array('id'=>'form','class'=>"form-horizontal", 'role'=>"form")); ?>
<h4 style="margin-left:10px;">Usuari/Usuària: <strong><?php echo $socio->id.' - '.$socio->apellidos.', '.$socio->nombre;?></strong> 
 <?php //echo $telefono; ?>
    -- Targeta Rosa: 
    <?php $tarjetaRosa=""; if($socio->tarjeta_rosa=='Sí') $tarjetaRosa="checked='checked'"; ?>
    <input type="checkbox" <?php echo  $tarjetaRosa; ?> name="tarjetaRosa" id="tarjetaRosa">
</h4>
<h3 style="margin-left:10px;">Tallers  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary" id="" >Registrar inscripcions</button>
    
</h3>

<input type="hidden" name="curso" value="<?php echo $idCurso; ?>" >
<input type="hidden" name="socio" value="<?php echo $idSocio; ?>" >
<input type="hidden" name="periodo" value="<?php echo $periodo; ?>" >
<input type="hidden" name="numPeriodo" value="<?php echo $numPeriodo; ?>" >
<input type="hidden" name="letraPeriodo" value="<?php echo $letraPeriodo; ?>" >

<?php echo $talleres; ?>

<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary" >Registrar inscripcions</button>
    </div>
</div>
<?php echo form_close(); ?>
</div>

<style>
    
    
    .marcado{
        /* color:blue; */
        background-color: lightblue;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 6px;
        padding-top: 6px;
         
         
    }
    
    .marcadoListaEspera{
        /* color:blue; */
        background-color: yellow;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 6px;
        padding-top: 6px;
    }
    
    .noMarcado{
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
        
    }
    
    .completo{
        font-weight: bold;
        color:red;
        
    }
    
    .noCompleto{
        font-weight: normal;
        color:black;
    }
    
    .listaEspera{
        background-color: yellow;
    }
    
    .modal-predunta-body{
        margin-left: 30px;
    }
    .Professional{
        padding-left: 5px;
        padding-right: 5px;
        border: 2px solid grey;
        margin-top: 3px;
        margin-bottom: 1px;
    }
    
     .Voluntari{
        padding-left: 5px;
        padding-right: 5px;
       
        margin-top: 5px;
        margin-bottom: 0px;
    }
    .verReservas{
        margin-left:2px;
        padding-left: 2px;
        padding-right: 5px;
        padding-bottom: 2px;
        padding-top: 2px;
    }
    
@media screen and (min-width: 1200px) {
.container {
    width: 1370px;
   
}   
}

    
</style>

<script>
$(document).ready(function () {
    
    //variables globales 
    //variable control cambios
    var cambios=false;
    
    //variable identificacion click checbox
    var idClick=""
    //curso 
    var curso=$('input[name="curso"').val()
    // socio
    var socio=$('input[name="socio"').val()
    //periodo
    var numPeriodo=$('input[name="numPeriodo"').val()
    //alert(numPeriodo);
    
    //letraPeriodo
    var letraPeriodo=$('input[name="letraPeriodo"').val()
    
    var tarjetaRosa=$('#tarjetaRosa').is(':checked')
    
    //alert($('#idT224').attr('disabled')+'idT224')
    //alert($('#idT27').attr('disabled')+'idT27')
   
   $( "#form" ).submit(function( event ) {
        var encontradoCambio=false;
        if($('#tarjetaRosa').is(':checked')!=tarjetaRosa){
            encontradoCambio=true;
        }
        $('.C, .T1, .T2, .T3').each(function(){
            if($(this).is(':checked') && (typeof $(this).attr('disabled')==="undefined")){
                encontradoCambio=true
                return false
            }
        })
        if(encontradoCambio==false){
            event.preventDefault();
           $('.modal-title').html('Informació')
           $('#myModal').css('color','')
           $('.modal-body p').html("No s'ha introduït cap nou període / taller, ni canviat tipus de targeta rosa")
           $('#myModal').modal('show')
            return;
        }
        cambios=false;
    });
   
   $('input[type="checkbox"].C').each(function(){
       if(!$(this).attr('disabled')){
           //console.log($(this).val())
           $(this).parent().css('color','blue')
           
       }
   })
   $('input[type="checkbox"].'+letraPeriodo).each(function(){
       if(!$(this).attr('disabled')){
           //console.log($(this).val())
           $(this).parent().css('color','blue')
          
       }
   })
   $('input[type="checkbox"]').each(function(){
       if(!$(this).attr('disabled')){
           //console.log($(this).val())
           $(this).parent().css('color','blue')
           $(this).parent().css('padding-top','5px')
           $(this).parent().css('border','1px solid blue')
       }
       $('#tarjetaRosa').parent().css('border','')
       $('#tarjetaRosa').parent().css('color','black')
   })
   
    /*
    console.log('curso '+curso)
    console.log('socio '+socio)
    console.log('numPeriodo '+numPeriodo)
    */
    //console.log('letraPeriodo '+letraPeriodo)
    
    //poner color fondo según tipo pago (tarjeta rosa, si/no)
    if($('#tarjetaRosa').is(':checked')){
        $('.modal-title').html('Control Targeta Rosa')
        $('#myModal').css('color','')
        $('.modal-body p').html("S'ha seleccionat usuari/usuària amb targeta rosa.<br><b>Verificar que la té.</b>")
        $('#myModal').modal('show')
           
        $('.inscripciones').css('background-color','#F8E0F7')
    }
    else{
        $('.inscripciones').css('background-color','#E0E0F8')
    }

    //al cambiar tipo pago (tarjeta rosa si/no), cambiar color fondo apropiadamente
    $('#tarjetaRosa').change(function(){
        
        cambios=true;
        if($('#tarjetaRosa').is(':checked')){
            $('.modal-title').html('Control Targeta Rosa')
            $('#myModal').css('color','')
            $('.modal-body p').html("S'ha seleccionat usuari/usuària amb targeta rosa.<br><b>Verificar que la té.</b>")
            $('#myModal').modal('show')
            $('.inscripciones').css('background-color','#F8E0F7')
        }
        else{
           $('.inscripciones').css('background-color','#E0E0F8')
        }    
    })
    
    
    //advertencia al cambiar de página
    window.onbeforeunload=confirmExit
    function confirmExit() {
        if (cambios ) 
        {   
            return "Ha introduït dades que no s'han desat."
        }
    }
  
    $('.T2').click(function(e) {
        console.log($(this).val())
        var taller=$(this).val()
        if($('#idT2'+taller).is(':checked')){
            console.log('hkhkkh')
            if(!$('#idT3'+taller).prop('disabled'))
                $('#idT3'+taller).prop('checked', true);
        }
        else{
            $('#idT3'+taller).prop('checked', false);
        } 
    })
    
    //acciones al hacer click en un checkbox
    $('.T1,.T2,.T3').click(function(e) {
        
        idClick=$(this).attr('id')
        //alert('clicked '+idClick)
        //alert('idClick '+idClick)
        var taller=$(this).val()
        //alert(taller)

        //Acciones cuando se ha desmarcado?
        if( !$('#id'+taller).is(':checked') 
            && !$('#idT1'+taller).is(':checked') 
            && !$('#idT2'+taller).is(':checked') 
            && !$('#idT3'+taller).is(':checked')) {
            //console.log("Se ha eliminado la marca "+idClick)
            //var taller=$(this).val()
            if( $('#idT1'+taller).is(':checked') 
               || $('#idT2'+taller).is(':checked')
               || $('#idT3'+taller).is(':checked'))
                    $('#parametros'+taller).css('color','black')
                //
            //comprobar si se ha incrementado anteriormente
            //para dejarlo como estaba
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/talleres/checkIncrementarNumMaximo/"+taller, 
            data:{taller:taller},
            success:function(datos){
                //alert(datos)
                var datosJSON=$.parseJSON(datos);
                var infoInscritos=$('#idInscritos'+taller).html()
                infoInscritos=infoInscritos.trim()
                var patt1 = /[0-9]*/g;
                var result = infoInscritos.match(patt1);
                var numActual=result[1]
                var numMaximo=parseInt(result[3])-datos
                infoInscritos=" ("+numActual+"/"+numMaximo+")"
                var nE=parseInt($('#parametros'+taller).attr('ne'))
                var maxE=parseInt($('#parametros'+taller).attr('maxe'))
                $('#idInscritos'+taller).html(infoInscritos)  
                $('#parametros'+taller).attr('maxa',numMaximo)
                if(numActual==numMaximo){
                    //ponemos la clase completo donde lo tenía.
                    $('#id'+taller).parent().parent().prev().children().children().addClass('completo')
                    $('#id'+taller).parent().children().next().addClass('completo')
                    $('#id'+taller).parent().next().children().next().addClass('completo')
                    $('#id'+taller).parent().next().next().children().next().addClass('completo')
                    $('#reservasInfo'+taller).removeClass('hide')
                    $('#reservasInfo'+taller).css('color','black')
                }
                $('#reservasInfo'+taller).css('color','black')
                $('#reservasInfo'+taller).css('background-color','yellow')
                $('#reservasInfo'+taller).css('font-weight','normal')
                
                if(nE==maxE){
                    $('#reservasInfo'+taller).css('color','red')
                }
                $('#parametros'+taller).css('color','black')
                //console.log('enListaEspera '+enListaEspera)
                if(enListaEspera==1) lineaTaller.css('background-color','yellow')
                cambios=true;
                return
                },
                error: function(){
                        alert("Error en el proceso de incrementar num asistentes en taller. Informar");
                 }
            })
            return
        }
        
        //get parámetros
        $('#numTaller').val(taller)
        $('#box').val($(this).attr('name'))
        
        var nA=parseInt($('#parametros'+taller).attr('na'))
        var maxA=parseInt($('#parametros'+taller).attr('maxa'))
        var nE=parseInt($('#parametros'+taller).attr('ne'))
        var maxE=parseInt($('#parametros'+taller).attr('maxe'))
        var enListaEspera=$('#parametros'+taller).attr('enlistaespera')
        var lineaTaller=$('#parametros'+taller).parent()
        
        $('#parametros'+taller).css('color','black')
        
        //console.log('nA '+nA,'maxA '+maxA, 'nE '+nE, 'maxE '+maxE, 'enListaEspera '+enListaEspera )
        //restaura situación listaEspera si hay discrepancia por haberlo cambiado.
        if(lineaTaller.css('background-color')=='rgb(255, 255, 0)' && enListaEspera==0){
            e.preventDefault()
            //console.log("lineaTaller.css('background-color') "+lineaTaller.css('background-color')+" enListaEspera "+enListaEspera)
            lineaTaller.css('background-color','white')
            lineaTaller.css('color','black')
            $('#listaEspera'+taller).remove()
            return
        }
        
        
        //alert(nA+' / '+maxA +'\n'+ nE+' / '+maxE + '\n'+ enListaEspera)
        var body=""
        
        //inscripción 'normal'
        if((nA<maxA && nE==0)) {
             //console.log("nA<maxA && nE==0 -> taller AUN NO lleno, nadie en lista espera")
            $('#parametros'+taller).css('color','blue')
            return
        }
        
        //taller completo y socio en lista espera del taller. Se puede ampliar e inscribir
        if((nA==maxA &&  enListaEspera==1)){
            //console.log("//taller completo  posibilidad de incrementar maxA e incribirlo")
            body="Aquest taller ESTA COMPLET.<br>Com està en llista d'espera, es pot ampliar el nombre de asistenes i incribirlo.<br>Voleu incribirlo?."
            $('#ponerEnReserva').addClass('hide')
            $('#ampliarSocios').removeClass('hide')
            $('#noAmpliarSocios').html('NO')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        }
        
        //taller completo, nadie en lista espera
        if(nA==maxA && nE==0 && enListaEspera==0){
            //console.log('//taller completo, nadie en lista espera')
            body="Aquest taller ESTA COMPLET.<br>Ningú en llista d'espera<br>Voleu ampliar el nombre màxim d'inscrits en un soci més per poder inscriure o posar-lo en llista d'espera?."
            $('#ponerEnReserva').removeClass('hide')
            $('#ampliarSocios').removeClass('hide')
            $('#ampliarSocios').html('Incrementar nombre màxim i inscriure')
            $('#noAmpliarSocios').html('Cancel')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        }
        
        //taller completo, otros socios en lista espera, no completa. Solo se puede poner en lista espera 
        if(nA==maxA && nE<maxE && enListaEspera==0){
            //console.log("//taller completo, otros socios en lista espera, no completa. Solo se puede poner en lista espera ")
            body="Aquest taller ESTA COMPLET.<br>Voleu apuntar-ho en llista d'espera?."
            $('#ponerEnReserva').removeClass('hide')
            $('#ampliarSocios').addClass('hide')
            $('#noAmpliarSocios').html('NO')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        }
        
        //taller completo y lists de espera completa. Socio NO esta en lista de espera
        if(nA==maxA && nE==maxE && enListaEspera==0){
           //console.log("//taller completo  y lista de espera completa")
            body="Aquest taller ESTA COMPLET.<br>També està completa la llista d'espera."
            $('#ponerEnReserva').addClass('hide')
            $('#ampliarSocios').addClass('hide')
            $('#noAmpliarSocios').html('Volver')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        }
        
        //se pueden inscribir, se puede poner en lista espera, socio en lista espera
        if((nA<maxA && nE<maxE && enListaEspera==1)){
            //console.log("//se pueden inscribir, se puede poner en lista espera, socio en lista espera")
             $('#parametros'+taller).css('color','blue')
            return
        }
        
        if((nA<maxA && enListaEspera==0 && maxA-nA >nE )){
             //console.log("nA<maxA && enListaEspera==0 && maxA-nA >nE  -> taller AUN NO lleno, no en linea de espera y número de asistentes posibles menor que la lista de espera")
            $('#parametros'+taller).css('color','blue')
            return
        
        }
        
        if((nA<maxA && enListaEspera==0 && maxA-nA <=nE && nE<maxE)){
             //console.log("no se pueden escribir porque no esta en lista de espera y los que faltan es menor o igual a los que están en lista de espera")
            body="No es pot escriure perquè no està en llista d'espera i els que falten és menor o igual als que estan en llista d'espera<br>Voleu posar-ho en llista d'espera, després es pot inscriure?.";
            $('#ponerEnReserva').removeClass('hide')
            $('#ampliarSocios').addClass('hide')
            $('#noAmpliarSocios').html('NO')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        }
        
        if((nA<maxA && enListaEspera==0 && maxA-nA <=nE && nE==maxE)){
            // no se puede inscribir ni poner en lista de espera
            //no se puede hacer nada
            body="No se puede inscribir. Las vacantes que faltan son para la lista de espera"
            $('#noAmpliarSocios').html('OK')
            $('#ponerEnReserva').addClass('hide')
            $('#ampliarSocios').addClass('hide')
            
            $('.modal-title').html('Inscripció a taller - Informació')
            $('.modal-predunta-body').html(body)
            $("#pregunta").modal({backdrop: 'static',   keyboard: false})
            return
        
        }
        
      //  $(this).parent().parent().children('span:nth-child(2)').children('input.T1').prop('checked', false);
      //  $(this).parent().parent().children('span:nth-child(3)').children('input.T2').prop('checked', false);
      //  $(this).parent().parent().children('span:nth-child(4)').children('input.T3').prop('checked', false);
        cambios=true;
        
        
	});
    
    //No ampliar número máximo incripciones
    $('#noAmpliarSocios').click(function(){
        //console.log('idClick '+idClick)
        $('#'+idClick).prop("checked", false);
        return
    }) 
    
    //ampliar núm máximo inscripción
    $('#ampliarSocios').click(function(){
        var taller=$('#'+idClick).val()
        var maxA=parseInt($('#parametros'+taller).attr('maxa'))+1
        var nA=$('#parametros'+taller).attr('na')
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/incrementarNumMaximo/"+taller, 
        data:{taller:taller},
        success:function(datos){
            //console.log(datos)
            var datos=$.parseJSON(datos);
            $('#idInscritos'+taller).html(' ('+nA+'/'+maxA+')')
            $('#idInscritos'+taller).removeClass('completo')
            $('#reservasInfo'+taller).addClass('hide')
            $('#parametros'+taller).attr('maxa',maxA)
            $('#parametros'+taller).css('color','blue')
            cambios=true;
        },
        error: function(){
                alert("Error en el proceso de incrementar num máximo asistentes en taller. Informar");
         }
    })
        return 
        var box=$('#box').val()
       // alert('taller '+taller)
        var nA=$('#parametros'+taller).attr('na')
        var maxA=$('#parametros'+taller).attr('maxa')
        var nE=$('#parametros'+taller).attr('ne')
        var maxE=$('#parametros'+taller).attr('maxe')
        var enListaEspera=$('#parametros'+taller).attr('enlistaespera')
        var numActual=nA
        var numMaximo=parseInt(maxA)+1
        var infoInscritos=" ("+numActual+"/"+numMaximo+")"
        var infoReservas=" ("+nE+"/"+maxE+")"
        
        var lineaTaller=$('#idInscritos'+taller).parent().parent()
        lineaTaller.css('background-color','white')
        $('#listaEspera'+taller).remove()
        
       $('#idInscritos'+taller).html(infoInscritos)
       $('#reservasInfo'+taller).html(infoReservas)
       $('#id'+taller).parent().parent().prev().children().children().removeClass('completo')  //del texto
       $('#id'+taller).parent().parent().children().children('input').removeClass('completo')  //de C
       $('#id'+taller).parent().parent().children().next().children('input').removeClass('completo') //de T1
       $('#id'+taller).parent().parent().children().next().next().children('input').removeClass('completo') //de T2
       $('#id'+taller).parent().parent().children().next().next().next().children('input').removeClass('completo') //de T3
       
       //cambiar el num_maximo en la info taller
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/incrementarNumMaximo/"+taller, 
        data:{taller:taller},
        success:function(datos){
            //console.log(datos)
            var datos=$.parseJSON(datos);
            switch(box){
                case 'C[]':
                    $('#id'+taller).prop("checked", true);
                    break;
                case 'T1[]':
                    $('#id'+taller).parent().next().children().next().prop("checked", true);
                    break;
                case 'T2[]':
                    $('#id'+taller).parent().next().next().children().next().prop("checked", true);
                    break;  
                case 'T3[]':
                    $('#id'+taller).parent().next().next().next().children().next().prop("checked", true);
                    break;    
            }
            $('#parametros'+taller).css('color','blue')
            cambios=true;
            
        },
        error: function(){
                alert("Error en el proceso de incrementar num máximo asistentes en taller. Informar");
         }
    })
       
    }) 

    //poner en lista espera
    $('#ponerEnReserva').click(function(e){
        var taller=$('#'+idClick).val()
        var periodoActual=$("[name='numPeriodo']").val();
        //alert($('#idT275').prop('checked'))
       
        //console.log('ponerEnReserva idClick '+idClick)
        //console.log('ponerEnReserva taller '+taller)
        /*
        $('#idT1'+taller).prop('checked', false);
        $('#idT2'+taller).prop('checked', false);
        $('#idT3'+taller).prop('checked', false);
        */
       //alert ('paso ponerEnReserva');
        if(letraPeriodo=='C') idClick='id'+taller
        else idClick='id'+letraPeriodo+taller
        
        if(periodoActual==2){
            $('#idT3'+taller).prop('checked', true);
            $('#idT3'+taller).attr('disabled','disabled');
        }
        if(periodoActual==3){
            $('#idT2'+taller).prop('checked', true);
            $('#idT2'+taller).attr('disabled','disabled');
        }
        //$('#idT375').prop('checked', true);
        
        
        $('#parametros'+taller).parent().css('background-color','yellow')
        $('#parametros'+taller).css('color','blue')
        $('#'+idClick).attr('name','listaEspera'+letraPeriodo+'[]')
    })
    
    //muestra características taller
    $('.nombreTaller').click(function(){
           var numTaller=$(this).attr('taller')
           $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/talleres/getTaller/"+numTaller, 
        //data:{curso:curso},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            var informacion='Curs: '+datos['nombreCurso']+'<br>';
            informacion+='<strong style="color:blue">'+datos['nombreTaller']+'</strong>'+' ('+numTaller+')'+'<p><strong style="color:blue">'+datos['tipoTaller']+'</strong></p><p>Profesor: '+datos['nombreProfesor']+'</p>'
            var espacio=datos['espacio']
            if (!espacio) espacio='No definit'
            informacion+='<p >Espacio: '+espacio+'</p>'
            
            
            informacion+='<table>';
            var dias="Dia"
            if(datos['dia2']) dias="Dias" 
            informacion+='<tr><th>'+dias+'</th>'
            informacion+='<th style="padding-left:30px">Horari</th></tr>';
            informacion+='<tr><td>'+datos['dia1']+'</td>';
            informacion+='<td style="padding-left:30px">'+datos['inicio1'].substr(0,5)+' - '+datos['final1'].substr(0,5)+'</td></tr>'
            if(datos['dia2']){
            informacion+='<tr><td>'+datos['dia2']+'</td>';
            informacion+='<td style="padding-left:30px">'+datos['inicio2'].substr(0,5)+' - '+datos['final2'].substr(0,5)+'</td></tr>'
            }
            // if(datos['fecha_inicio']!='0000-00-00'){
            // informacion+='<tr><td>Data inici</td>';
            // informacion+='<td style="padding-left:30px">'+datos['fecha_inicio']+'</td></tr>'
            // }
            // if(datos['fecha_final']!='0000-00-00'){
            // informacion+='<tr><td>Data final</td>';
            // informacion+='<td style="padding-left:30px">'+datos['fecha_final']+'</td></tr>'
            // }
            informacion+='</table>';
           
            informacion+='<br><table>';
            informacion+='<tr><th>Precios</th>'
            if(datos['tipoTaller']=='Voluntari')
                informacion+='<th style="padding-left:20px">Curso</th>';
            informacion+='<th style="padding-left:20px">Trimestre</th></tr>';
            informacion+='<tr><td>Normal</td>';
            if(datos['tipoTaller']=='Voluntari')
                informacion+='<td style="text-align:right;">'+datos['precioCurso']+' €</td>';
            informacion+='<td style="text-align:right"> '+datos['precioTrimestre']+' €</td></tr>';
            informacion+='<tr><td>Rosa</td>';
            if(datos['tipoTaller']=='Voluntari')
                informacion+='<td style="text-align:right">'+datos['precioRosaCurso']+' €</td>'
            informacion+='<td style="text-align:right">'+datos['precioRosaTrimestre']+' €</td></tr>';
            informacion+='</table>';
            
            
            $('.modal-title').html('Informació Taller ')
            $('.modal-body>p').html(informacion)
            $("#myModal").modal()
     
        },
        error: function(){
                alert("Error en el proceso caracteristicas taller. Informar");
         }
    })
           })
           
})
</script>




