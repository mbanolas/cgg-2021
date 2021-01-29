<br /> <br />

<?php echo validation_errors(); ?>

<?php echo form_open('verifyLogin'); ?>
<div class="col-xs-6 col-md-4"></div>
<div class="row">
    <div class="form-group col-xs-6 col-md-4">
        <label for="usuario">Usuari/Usuària: </label>
        <input type="text" name="username" class="form-control" id="usuario" placeholder="Introduir nom usuari/usuària" value="<?php echo set_value('username'); ?>">
        <label style="margin-top:10px" for="password">Contrasenya: </label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Introduir ontrasenya" value="<?php echo set_value('password'); ?>">
        <span id="error" style="color:red"><?php echo $error ?></span>
        <br />
        <input type="submit" class="btn btn-primary" id="enter" value="Entra">
        <h6 style="margin-top: 50px">Utilitza, preferiblement, el navegador Chrome</h6>
        <h6>amb pantalla completa (Windows F11 - Mac Ctr + Maj + f)</h6>
    </div>
</div>


<?php echo \form_close(); ?>


<style>
    /* .col-centered {
        margin: 0 auto;
        float: none;
    } */
</style>