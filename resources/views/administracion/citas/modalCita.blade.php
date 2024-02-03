<!-- Modal -->
<div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="modalCitaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" id="frm-modal-cita">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCitaLabel">ALTA CITA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label for="horas">DÍA DE LA CITA</label>
                    <input type="text" readonly class="form-control-plaintext" id="fe_inicio" name="data[fe_inicio]" >
                  </div>
                
                  <div class="form-group">
                    <label for="horas">HORA SELECCIONADA</label>
                    <input type="text" readonly class="form-control-plaintext" id="horas" name="data[horas]" >
                  </div>

              
               
                  <div class="form-group">
                    <label for="selectPaciente">SELECCIONA PACIENTE</label>
                    <select name="data[paciente]" id="selectPaciente" class="form-control col-12">
                        
                     </select>
                    <small id="emailHelp" class="form-text text-muted"> <a href="">Cita primera vez</a> </small>
                  </div>
                  
                  <div class="form-group">
                    <label for="horas">MOTIVO DE CONSULTA</label>
                    <textarea data="[motivo]" id="motivo" cols="30" rows="10" class="form-control"></textarea>
                  </div>

                <input type="hidden" id="idconsultasignado" name="idconsultasignado">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
              </div>
        </form>
      </div>
    </div>
  </div>