
<div class="container">
<?php echo form_open(''); ?>
    <div class="col-xs-12 col-md-4"></div>
    <div class="row">
    <div class="form-group col-xs-12 col-md-4">
        <label for="usuario">Usuari/Usuària: </label>
        <input type="text" name="username" class="form-control" id="usuario" placeholder="Introduir nom usuari/usuària" value="<?php echo set_value('username'); ?>">
        <label style="margin-top:10px" for="password">Contrasenya: </label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Introduir ontrasenya" value="<?php echo set_value('password'); ?>" >
        <span id="error" style="color:red"><?php echo $error ?></span>
        <br />
        <input type="submit" class="btn btn-primary" id="enter" value="Entra">  
        
    </div>
    </div>
   
    
    <div class="row">
        <div class="col-xs-12 col-md-4">
        <h6 style="margin-top: 50px">Utilitza, preferiblement, el navegador Chrome</h6>
        <h6 >amb pantalla completa (Windows F11 - Mac Ctr + Maj + f)</h6>
        </div>
    </div>
    
<?php echo \form_close(); ?>
</div>


<script>
$(document).ready(function(){
    
    $('#enter').click(function(e){
        e.preventDefault();
        var usuario=$('#usuario').val();
        var password=$('#password').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"/index.php/parc/verificarEntrada", 
            data: {usuario:usuario,password:password },
            success: function(datos){
                if(datos=='false'){
                    $('.modal-title').html('Informaciónn')
                    $('#myModal').css('color','')
                    $('.modal-body p').html('El nombre usuario o la contraseña NO son correctos.<br />Por favor, vuelva a introducirlos o contacte con el administrador')
                    $('#myModal').modal('show')
                } else{  
                //window.location.href = "http://localhost:8888/parc/index.php/inicio";
                window.location.href = "<?php echo base_url() ?>"+"index.php/inicio";
                //var d=$.parseJSON(datos);
            }
                 },
            error: function(){
                $('.modal-title').html('Error')
                    $('.modal-body p').html('Process error.\nPlease inform to the Administrator.'+"<?php echo base_url() ?>"+"/parc/verificarEntrada")
                    $('#myModal').css('color','red')
                    $('#myModal').modal('show')
            }
        });
    })
     
})
</script>    
