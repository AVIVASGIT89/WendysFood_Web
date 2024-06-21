  <script>
      function efectoParpadeo(){

          $("#item-delivery").addClass("parpadeo");

      }
  </script>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Registrar Orden</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        
        <div class="card-body">

          <div class="card card-success card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <ul class="nav nav-tabs" id="tipoAtencion" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tabMesas" data-toggle="pill" href="#tablaMesas" role="tab" aria-controls="tablaMesas" aria-selected="true">Mesa</a>
                </li>
                <li class="nav-item" id="item-delivery">
                  <a class="nav-link" id="tabDelivery" data-toggle="pill" href="#tableDeliverys" role="tab" aria-controls="tableDeliverys" aria-selected="false">Delivery</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="tipoAtencionContent">
                <div class="tab-pane fade show active" id="tablaMesas" role="tabpanel" aria-labelledby="tabMesas">
                  <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Nro. Mesa</th>
                        <th>Estado</th>
                        <th>Total Orden S/.</th>
                        <th>Opcion</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                      $listadoMesas = ControladorVenta::ctrListarMesas();

                      //var_dump($listadoMesas);

                      foreach($listadoMesas as $key => $mesa){

                        echo '<tr>
                                <td align="center">'.$mesa["NRO_MESA"].'</td>';
                                
                                if($mesa["ESTADO_MESA"] == "1"){
                                  echo '<td align="center"><span class="badge badge-success">Disponible</span></td>';
                                }else{
                                  echo '<td align="center"><span class="badge badge-warning">Ocupado</span></td>';
                                }

                          echo '<td align="center">'.number_format($mesa["TOTAL_CONSUMO"], 2).'</td>
                                <td align="center">
                                  <div class="btn-group">';

                                  if($mesa["ESTADO_MESA"] == "1"){
                                    echo '<button class="btn btn-primary nuevaOrden" idMesa="'.$mesa["ID_MESA"].'" nroMesa="'.$mesa["NRO_MESA"].'" tipoVenta="1" title="Registrar orden"><i class="fas fa-utensils"></i></button>';
                                  }else{
                                    echo '<button class="btn btn-success registrarPago" idMesa="'.$mesa["ID_MESA"].'" idVenta="'.$mesa["ID_VENTA_OCUPACION"].'" nroMesa="'.$mesa["NRO_MESA"].'" title="Cobrar"><i class="fas fa-money-bill-alt"></i></button>';
                                    echo '<button class="btn btn-warning modificarOrden" idMesa="'.$mesa["ID_MESA"].'" idVenta="'.$mesa["ID_VENTA_OCUPACION"].'" nroMesa="'.$mesa["NRO_MESA"].'" title="Modificar orden"><i class="fas fa-pen"></i></button>';
                                  }

                                  '</div>
                                </td>
                              </tr>';

                      }
                    
                    ?>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="tableDeliverys" role="tabpanel" aria-labelledby="tabDelivery">
                  
                  <button type="button" class="btn btn-primary nuevoDelivery" tipoVenta="2"><i class="fas fa-plus-circle"></i> Nuevo delivery</button>
                  
                  <br>
                  <br>

                  <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Total S/.</th>
                        <th>Cliente</th>
                        <th>Opcion</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    $listadoDelivery = ControladorVenta::ctrListarDeliveryHoy();
                    $contadorPendiente = 0;

                    //var_dump($listadoDelivery);

                    foreach($listadoDelivery as $key => $delivery){

                      echo '<tr>
                              <td>'.($key + 1).'</td>';
                              
                              if($delivery["ESTADO_VENTA"] == "1"){
                                $contadorPendiente++;
                                echo '<td align="center"><span class="badge badge-warning">Pendiente</span></td>';
                              }
                              else
                              if($delivery["ESTADO_VENTA"] == "2"){
                                echo '<td align="center"><span class="badge badge-success">Finalizado</span></td>';
                              }
                              else
                              if($delivery["ESTADO_VENTA"] == "3"){
                                echo '<td align="center"><span class="badge badge-danger">Cancelado</span></td>';
                              }

                        echo '<td align="center">'.$delivery["FECHA_VENTA"].'</td>
                              <td align="center">'.number_format($delivery["TOTAL_VENTA"], 2).'</td>
                              <td align="center">'.$delivery["CLIENTE_DELIVERY"].'</td>
                              <td align="center">
                                <div class="btn-group">';

                                if($delivery["ESTADO_VENTA"] == "1"){
                                  echo '<button class="btn btn-success registrarPago" idVenta="'.$delivery["ID_VENTA"].'" nromesa="Delivery" title="Cobrar"><i class="fas fa-money-bill-alt"></i></button>';
                                  echo '<button class="btn btn-warning modificarOrden" idVenta="'.$delivery["ID_VENTA"].'" nromesa="Delivery" title="Modificar orden"><i class="fas fa-pen"></i></button>';
                                }else{
                                  echo '<button class="btn btn-info productosOrden" idVenta="'.$delivery["ID_VENTA"].'" title="ver productos"><i class="fas fa-eye"></i></button>';
                                }
                                '</div>
                              </td>
                            </tr>';

                    }

                    if($contadorPendiente > 0){

                      echo '<script>
                              efectoParpadeo();
                            </script>';

                    }

                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-------------------------------- Modales -------------------------------->
  <!-- Modal largo para Registrar Nueva Orden -->
  <div class="modal fade" id="modalRegistrarOrden">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Registrar orden en mesa: <span id="spNroMesa"></span> <input type="hidden" id="idMesaVenta" value=""></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="d-flex align-items-center justify-content-center">
            <form class="form-inline" id="frmBuscarProducto">
              <div class="form-group mx-sm-2 mb-2">
                <label for="codigoProducto" class="sr-only">Codigo</label>
                <input type="text" class="form-control" id="codigoProducto" placeholder="Codigo producto">
              </div>
              <button type="submit" class="btn btn-primary mb-2">Buscar</button>
            </form>
          </div>
          
          <br>

          <div class="d-flex align-items-center justify-content-center">
            <table class="tabla-registro">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Sub total</th>
                  <th>Opcion</th>
                </tr>
              </thead>
              <tbody id="tbodyListaProductos">
              <!-- 
                Aqui se carga la lista de productos a traves de Ajax
              -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" align="right"><h3>Total Orden: S/.</h3></td>
                  <td><h3><span id="spTotalVenta">0.00</span></h3></td>
                  <td>
                    <input type="hidden" id="tipoVenta" value=""/>
                    <input type="hidden" id="ventaBase" value=""/>
	                  <input type="hidden" id="ventaIgv" value=""/>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="d-flex justify-content-end">
            <label><input type="checkbox" id="noImprimir"/> No Imprimir</label>
          </div>

          <div id="divClienteDelivery">
            <div class="col-4" style="margin: 0 auto;">
              <input type="text" class="form-control" id="clienteDelivery" placeholder="Nombre cliente" .col-4>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-warning" id="btnLimpiar"><i class="fas fa-broom"></i> Limpiar</button>
          <button type="button" class="btn btn-success" id="btnRegistrarVenta"><i class="fas fa-check-circle"></i> Registrar orden</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- Modal largo para Registrar Pago -->
  <div class="modal fade" id="modalRegistrarPago">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Registrar pago en mesa: <span id="spNroMesaPago"></span> <input type="hidden" id="idMesaPago" value=""></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="d-flex align-items-center justify-content-center">
            <input type="radio" class="m-1" name="formaPago" id="formaPago" value="1">Efectivo &nbsp;
            <input type="radio" class="m-1" name="formaPago" id="formaPago" value="2">Yape &nbsp;
            <input type="radio" class="m-1" name="formaPago" id="formaPago" value="3">Plin
          </div>
          <br>
          <div class="d-flex align-items-center justify-content-center">
            <table class="tabla-registro">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Sub total</th>
                  <th>Opcion</th>
                </tr>
              </thead>
              <tbody id="tbodyListaProductosPago">
              <!-- 
                Aqui se carga la lista de productos a traves de Ajax
              -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" align="right"><h3>Total Orden: S/.</h3></td>
                  <td>
                    <h3><span id="spTotalVentaPago">0.00</span></h3>
                    <input type="hidden" id="idVentaPago" value="">
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <br>
          <p align="center">Fecha orden: <span id="spFechaOrdenPago"></span></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btnImprimirOrden"><i class="fas fa-print"></i> Imprimir Orden</button>
          <button type="button" class="btn btn-success" id="btnRegistrarPago"><i class="fas fa-money-bill-alt"></i> Registrar Pago</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- Modal largo para Modificar orden -->
  <div class="modal fade" id="modalModificarOrden">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Modificar orden en mesa: <span id="spNroMesaModificar"></span> <input type="hidden" id="idMesaModificar" value=""></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="d-flex align-items-center justify-content-center">
            <form class="form-inline" id="frmBuscarProductoModificar">
              <div class="form-group mx-sm-2 mb-2">
                <label for="codigoProductoModificar" class="sr-only">Codigo</label>
                <input type="text" class="form-control" id="codigoProductoModificar" placeholder="Codigo producto">
              </div>
              <button type="submit" class="btn btn-primary mb-2">Agregar</button>
            </form>
          </div>
          <br>
          <div class="d-flex align-items-center justify-content-center">
            <table class="tabla-registro">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Sub total</th>
                  <th>Opcion</th>
                </tr>
              </thead>
              <tbody id="tbodyListaProductosModificar">
              <!-- 
                Aqui se carga la lista de productos a traves de Ajax
              -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" align="right"><h3>Total Orden: S/.</h3></td>
                  <td>
                    <h3><span id="spTotalVentaModificar">0.00</span></h3>
                    <input type="hidden" id="idVentaModificar" value="">
                    <input type="hidden" id="ventaBaseModificar" value=""/>
	                  <input type="hidden" id="ventaIgvModificar" value=""/>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <br>
          <p align="center">Fecha orden: <span id="spFechaOrdenModificar"></span></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" id="btnCancelarOrden"><i class="fas fa-times-circle"></i> Anular Orden</button>
          <button type="button" class="btn btn-primary" id="btnModificarOrden"><i class="fas fa-pen"></i> Modificar Orden</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


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