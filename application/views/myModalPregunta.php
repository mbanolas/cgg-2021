<!-- Modal Confirm Delete -->
<div class="modal fade" id="pregunta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Título </h4>
            </div>
            <div class="modal-predunta-body">
                <p>Información confirmacion.</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="numTaller" value="">
                <input type="hidden" id="box" value="">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="ponerEnReserva">Posar en llista espera</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" taller id="ampliarSocios">Sí</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="noAmpliarSocios">Cancel</button>
            </div>
        </div>
    </div>
</div>
<style>
    #ponerEnReserva{
        background-color:lightyellow;
    }
    #ponerEnReserva:hover{
        /*border-color:yellow;*/
        background-color:yellow;
    }
    #ampliarSocios{
        background-color:lightblue;
    }
    #ampliarSocios:hover{
        border-color:blue;
    }
    
</style>