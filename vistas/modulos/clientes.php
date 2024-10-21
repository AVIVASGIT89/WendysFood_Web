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
          <button class="btn btn-primary resetCliente"><i class="fas fa-user-edit"></i> Resetear</button>
        </div>
        <div class="card-body">
          <table class="table table-sm table-striped table-hover dataTable" id="tablaClientes">
            <thead>
              <tr>
                <th>#</th>
                <th>Codigo</th>
                <th>Identificacion</th>
                <th>Nombre cliente</th>
                <th>Puntos</th>
                <th>Fecha registro</th>
				        <th>Celular</th>
                <th>Msj enviado</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody id="tbodyRegistroClientes">

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


  <!-- Modal editar cliente -->
  <div class="modal fade" id="modal-editar-cliente">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Editar cliente</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="editarIdentificacion" class="col-sm-2 col-form-label">DNI/CE</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="editarIdentificacion" id="editarIdentificacion" placeholder="DNI/CE" onclick="this.select()" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="editarNombre" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="editarNombre" id="editarNombre" placeholder="Nombre cliente" onclick="this.select()" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="editarApellido" class="col-sm-2 col-form-label">Apellido</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="editarApellido" id="editarApellido" placeholder="Apellido cliente" onclick="this.select()" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="editarPuntos" class="col-sm-2 col-form-label">Puntos</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="editarPuntos" id="editarPuntos" placeholder="Puntos" onclick="this.select()" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="editarCelular" class="col-sm-2 col-form-label">Celular</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="editarCelular" id="editarCelular" placeholder="Celular" onclick="this.select()" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" id="btnEditarCliente" class="btn btn-success">Actualizar</button>
            <input type="hidden" id="idCliente">
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
      registroClientes();
    });
  </script>