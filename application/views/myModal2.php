<!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close " data-dismiss="modal">&times;</button>
          <h4 class="modal-title2">Información</h4>
        </div>
        <div class="modal-body2">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Tancar</button>
        </div>
      </div>
      
    </div>
  </div>


<style>
    .modal-body2{
        padding: 20px;
        background-color: lightyellow;
    }  
    
</style>
<script>
 $(document).ready(function () {
    
 })

</script>


<script>
    function alerta(titulo,mensaje){
        
        $('.modal-title').html(titulo)
        $('.modal-body>p').html(mensaje)
        $("#myModal").modal()
    }
        // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
        
</script>  