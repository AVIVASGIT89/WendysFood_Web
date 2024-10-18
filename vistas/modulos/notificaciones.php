<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Notificaciones</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nueva-notificacion"><i class="fas fa-comment-medical"></i> Nueva notificacion</button>
        </div>
        <div class="card-body">
          <table class="table table-striped table-hover dataTable" id="tablaNotificaciones">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha notificacion</th>
                <th>Tipo</th>
                <th>Nro. mensajes</th>
                <th>Usuario envio</th>
              </tr>
            </thead>
            <tbody id="tbodyRegistroNotificaciones">

            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!------------------ Modales ------------------>
  <!-- Modal nueva notificacion -->
  <div class="modal fade" id="modal-nueva-notificacion">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Nueva notificacion</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="fechaDesde" class="col-sm-2 col-form-label">Desde</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" name="fechaDesde" id="fechaDesde" value="<?php echo date("Y-m-d")?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="fechaHasta" class="col-sm-2 col-form-label">Hasta</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" name="fechaHasta" id="fechaHasta" value="<?php echo date("Y-m-d")?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="tipoNotificacion" class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-10">
                  <select name="tipoNotificacion" id="tipoNotificacion" class="form-control" required>
                    <option value="">- Seleccione -</option>
                    <option value="1">Menor 100 puntos</option>
                    <option value="2">Mayor 100 puntos</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary procesarClientes" id="btnProcesar">Procesar</button>
                </div>
              </div>
              <br>

              <div id="dvDatosNotificacion" style="display: none;">
                <div>
                  Clientes encontrados: &nbsp;<b><span id="spNroClientes">-</span></b> &nbsp; <span id="spTipo">-</span>
                </div>
                <br>
                <div class="row">
                  <div class="col text-center">
                      <button type="button" class="btn btn-success enviarNotificacion"><i class="fas fa-paper-plane"></i> Enviar notificacion</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <script>
    $(document).ready(function(){
      registroNotificaciones();
    });
  </script>