

<?php echo form_open('talleres/seleccionar', array('class'=>"form-horizontal", 'role'=>"form")); ?>
<div class="container">
    
    <div class="row">
        <div class="form-group">
            <h3>Resum tallers</h3>
        <div class="col-sm-12">
            <h3><?php echo $tipo ?></h3>
            <h4>NO es pot emetre un duplicat</h4>
            <h4>Per tornar-lo a imprimir, baixar-lo de la base dades de rebuts</h4>
        </div>
        <div class="col-sm-2">
        <button type="submit" class="btn btn-success" id="volver">Tornar</button>
        </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>