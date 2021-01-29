<!-- Modal -->
  <div class="modal fade" id="myModalEmails" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close " data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Información</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Tancar</button>
        </div>
      </div>
      
    </div>
  </div>

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