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
          <table class="table table-striped table-hover dataTable" id="tablaClientes">
            <thead>
              <tr>
                <th>#</th>
                <th>Codigo</th>
                <th>Identificacion</th>
                <th>Nombre cliente</th>
                <th>Puntos</th>
                <th>Fecha registro</th>
                <th>Msj enviado</th>
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

  <script>
    $(document).ready(function(){
      registroClientes();
    });
  </script>