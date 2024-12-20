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
            <h1>Registro ventas SUNAT</h1>
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
              Tipo:
              <select name="" id="tipoComprobante" class="form-control">
                <option value="0">Todos</option>
                <option value="01">Factura</option>
                <option value="03">Boleta</option>
              </select>
            </div>
            <div class="form-group mb-2">
              Estado:
              <select name="" id="estadoEnvio" class="form-control">
                <option value="0">Todos</option>
                <option value="1">Enviado</option>
                <option value="2">No enviado</option>
              </select>
            </div>
            <button type="button" class="btn btn-primary mb-2" id="btnReporteVentasSunat">Buscar</button>
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
          
          <table class="table table-sm table-bordered table-striped table-hover dataTable" id="tablaReposteVentasSunat">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha venta</th>
                <th>Tipo</th>
                <th>Serie</th>
                <th>Nro.</th>
                <th>Total S/.</th>
                <th>Estavo envio</th>
                <th>Descargar</th>
              </tr>
            </thead>
            <tbody id="tbodyReporteVentasSunat">
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