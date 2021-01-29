<div class="container">
<div class="row">
    <div class="   col-xs-12 col-md-6">
        <h1 ><img class="img-responsive"  id="logoNuevo" src="<?php echo base_url('images/logo_casal2.png') ?>"></img></h1>
    </div>
    <div class="   col-xs-12  col-md-6">
        <h1 id="title_top_lletra"><?php echo $_SESSION['tituloCasal'];; ?></h1>
        <h4>Versió 2.1</h4>
        <h4>Version php: <?php echo phpversion() ?></h4>      
        <!-- <h4>MD5: <?php echo MD5('Gestio2021') ?></h4>       -->
    </div>  
</div>
</div>
<hr><br />

<style type="text/css">
    #logoNuevo{
        height: 250px;
    }
    #title_top_lletra{
        font-size: 30px;
        text-align: left;
        padding-top:150px;
    }
</style>
