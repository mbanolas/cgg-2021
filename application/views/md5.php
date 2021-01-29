<div class='container'>
<h3>!Bienvenido!</h3>
<h3 id="nombre"><?php echo $this->session->nombre ?></h3>

<h3><?php echo $this->session->categoria ?></h3>
<h3><?php echo '2108 = '.md5('2108') ?></h3>
<h3><?php echo 'casal1234 = '.md5('casal1234') ?></h3>
<h3><?php echo '1234 = '.md5('1234') ?></h3>
<h3><?php echo 'rosell = '.md5('rosell') ?></h3>
<h3><?php echo 'encis1 = '.md5('encis1') ?></h3>

<h3><?php echo 'Dinamitzador Isidret = '.md5('dinamIsidret1') ?></h3>
<h3><?php echo 'Dinamitzador Casanelles = '.md5('dinamCasanelles1') ?></h3>
<h3><?php echo 'Dinamitzador Maragall = '.md5('dinamMaragall1') ?></h3>
<h3><?php echo 'Dinamitzador Clot = '.md5('dinamClot1') ?></h3>
<h3><?php echo 'Dinamitzador 4 Cantons = '.md5('dinam4Cantons1') ?></h3>
<h3><?php echo 'Dinamitzador Sant Marti = '.md5('dinamSantMarti1') ?></h3>
<h3><?php echo 'Dinamitzador Taulat = '.md5('dinamTaulat1') ?></h3>
<h3><?php echo 'Dinamitzador Verneda = '.md5('dinamVernedaAlta1') ?></h3>
<h3><?php echo 'Dinamitzador Parc Sandaru = '.md5('dinamParcSandaru1') ?></h3>

<h3><?php echo 'Secretaria Isidret = '.md5('secreIsidret1') ?></h3>
<h3><?php echo 'Secretaria Casanelles = '.md5('secreCasanelles1') ?></h3>
<h3><?php echo 'Secretaria Maragall = '.md5('secreMaragall1') ?></h3>
<h3><?php echo 'Secretaria Clot = '.md5('secreClot1') ?></h3>
<h3><?php echo 'Secretaria 4 Cantons = '.md5('secre4Cantons1') ?></h3>
<h3><?php echo 'Secretaria Sant Marti = '.md5('secreSantMarti1') ?></h3>
<h3><?php echo 'Secretaria Taulat = '.md5('secreTaulat1') ?></h3>
<h3><?php echo 'Secretaria Verneda = '.md5('secreVernedaAlta1') ?></h3>
<h3><?php echo 'Secretaria Parc Sandaru = '.md5('secreParcSandaru1') ?></h3>

<h3><?php echo 'Pat = '.md5('GestioEncis') ?></h3>
<h3><?php echo 'Elena = '.md5('GestioEncis') ?></h3>

<h3><?php echo 'Pat = '.md5('coordinacio') ?></h3>
<h3><?php echo 'Encis = '.md5('gestio') ?></h3>

<hr>
<div style="color: red">
<h3>Aviso:</h3><h3>Esta aplicación está en desarrollo. Algunas partes no están completadas o no han sido verificadas. Por favor tenerlo en consideración.</h3>
<h3>Gracias.</h3>
</div>
</div>

<script>
$(document).ready(function(){
    
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

var horas=today.getHours()
var minutos=today.getMinutes()

var segundos=today.getSeconds()

if(dd<10) {
    dd='0'+dd
} 

if(mm<10) {
    mm='0'+mm
} 

if(horas<10) {
    horas='0'+horas
} 
if(minutos<10) {
    minutos='0'+minutos
}

today = yyyy+'-'+mm+'-'+dd+' '+horas+':'+minutos

var fecha=today


var nombre=$('#nombre').html()


  $.ajax({
      type: 'POST',
      url: "<?php echo base_url() ?>"+"index.php/inicio/fechaMovimientoWeb", 
      data: {fecha:fecha,nombre:nombre},
      success: function(datos){
       //  alert(datos)
      //var d=$.parseJSON(datos)
      //alert(datos)
        },
       error: function(){
                alert("Error en el proceso fechaMovimientoWeb. Informar");
       }
  })
  
})
</script>




