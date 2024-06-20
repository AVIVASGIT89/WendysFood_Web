  <?php
  //Verificamos que el usuario en session puede ingresar a esta pagina
  if($_SESSION["sRolUsuario"] != "1"){
    echo '<script>
            window.location = "inicio";
          </script>';
  }

  ?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Registro de ventas</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <div class="form-inline items-abajo items-separados" method="POST">
            <div class="form-group mb-2">
              Desde: <input type="date" class="form-control" id="fechaDesde" value="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="form-group mb-2">
              Hasta: <input type="date" class="form-control" id="fechaHasta" value="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="form-group mb-2">
              Estado:
              <select name="" id="estadoVenta" class="form-control">
                <option value="0">Todos</option>
                <option value="2">Finalizado</option>
                <option value="1">Pendiente</option>
                <option value="3">Cancelado</option>
              </select>
            </div>
            <div class="form-group mb-2">
              Tipo:
              <select name="" id="tipoVenta" class="form-control">
                <option value="0">Todos</option>
                <option value="1">Mesa</option>
                <option value="2">Delivery</option>
              </select>
            </div>
            <button type="button" class="btn btn-primary mb-2" id="btnRegistroVentas">Buscar</button>
          </div>
        </div>
        <div class="card-body">
          
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-file-invoice-dollar"></i></span>

                <div class="info-box-content">
                  <div class="row">
                    <div class="col">Cant. registros:</div>
                    <div class="col"><b><span id="spRegistroVentas">0</span></b></div>
                  </div>
                  <div class="row">
                    <div class="col">Total venta:</div>
                    <div class="col"><b> S/. <span id="spTotalVentas">0</span></b></div>
                  </div>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
          
          <table class="table table-sm table-bordered table-striped table-hover dataTable" id="tablaRegistroVentas">
            <thead>
              <tr>
                <th>#</th>
                <th>Nro. orden</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Nro. productos</th>
                <th>Total S/.</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody id="tbodyRegistroVentas">
              <!-- Aqui se carga los datos -->
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


<!-- Modales -->
  <!-- Modal detalle venta- -->
  <div class="modal fade" id="modalDetalleVenta">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detalle</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-sm table-striped table-hover">
            <thead>
              <th>#</th>
              <th>Cantidad</th>
              <th>Producto</th>
              <th>Precio</th>
              <th>Subtotal</th>
            </thead>
            <tbody id="tbodyProductosVenta">

            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->