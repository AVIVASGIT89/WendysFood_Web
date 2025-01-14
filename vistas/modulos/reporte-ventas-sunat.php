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
                <th>Estado envio</th>
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


  <!----------------------- Modales ----------------------->

  <!-- Modal mostrar respuesta Sunat -->
  <div class="modal fade" id="modal-respuesta-sunat">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Respuesta SUNAT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><span id="spRespuestaSunat"></span></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  <!-- Modal Reenviar Comprobante a Sunat-->
  <div class="modal fade" id="modalGenerarComprobante">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reenviar venta: <span id="spSerieVentaSunat"></span>-<span id="spNroVentaSunat"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="facturaForm">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="tipoDocumento">Tipo de Documento</label>
                <select class="form-control" id="tipoDocumento" disabled>
                    <option value="">-Seleccione-</option>
                    <option value="03">Boleta</option>
                    <option value="01">Factura</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="identificacionCliente">Identificacion</label> <span id="spFuenteIdentificacion" style="font-size: 10px;"></span>
                <div class="input-group">
                  <input type="text" class="form-control" id="identificacionCliente" disabled>
                  <div class="input-group-append">
                      <button class="btn btn-primary" type="button" id="btnBuscarClienteIdentificacion" disabled>Buscar</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="clienteNombre">Nombre del Cliente</label>
              <input type="text" class="form-control" id="clienteNombre" disabled>
            </div>
            <div class="form-group">
              <label for="fechaVenta">Fecha de Factura</label>
              <input type="text" class="form-control" id="fechaVenta" value="" disabled>
            </div>
            <h5 class="mt-4">Productos</h5>
            <table class="table table-sm table-striped table-hover">
              <thead>
                <th>#</th>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Subtotal</th>
              </thead>
              <tbody id="tbodyProductosVentaComprobante">
                <!-- Aqui se agregaran los productos -->
              </tbody>

              <tfoot>
                <td colspan="4" align="right"><b>Total</b></td>
                <td align="right"><b><span id="spTotalVentaComprobante">0</span></b></td>
              </tfoot>
            </table>
            <input type="hidden" id="fIdVenta" value="">
            <input type="hidden" id="fVentaBase" value="">
            <input type="hidden" id="fVentaIGV" value="">
            <input type="hidden" id="fIdentificacionCliente" value="">
            <input type="hidden" id="fTipoIdentificacionCliente" value="">
            <input type="hidden" id="fIdCliente" value="">
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btnReenviarComprobante"><i class="fas fa-paper-plane"></i> Reenviar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

