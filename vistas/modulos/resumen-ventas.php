  <?php
  //Verificamos que el usuario en session puede ingresar a esta pagina
  if($_SESSION["sRolUsuario"] != "1"){
    echo '<script>
            window.location = "inicio";
          </script>';
  }
  
  //Si "fechaDesde" desde un valor
  if(isset($_POST["fechaDesde"])){

    $fechaDesde = $_POST["fechaDesde"];
    $fechaHasta = $_POST["fechaHasta"];

  }else{

    $fechaDesde = date("Y-m-d");
    $fechaHasta = date("Y-m-d");

  }

  ?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Resumen ventas</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <form class="form-inline items-abajo items-separados" method="POST">
            <div class="form-group mb-2">
              Desde: <input type="date" class="form-control" name="fechaDesde" value="<?php echo $fechaDesde; ?>">
            </div>
            <div class="form-group mb-2">
              Hasta: <input type="date" class="form-control" name="fechaHasta" value="<?php echo $fechaHasta; ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-2 toBottom">Buscar</button>
          </form>
        </div>
        <div class="card-body">
          
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><span id="spOrdenes">0</span></h3>
                  <p>Ordenes</p>
                </div>
                <div class="icon">
                  <i class="fas fa-utensils"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><sup style="font-size: 20px">S/. </sup><span id="spTotalFinalizados">0</span></h3>
                  <p>Finalizados</p>
                </div>
                <div class="icon">
                  <i class="fas fa-check-circle"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><sup style="font-size: 20px">S/. </sup><span id="spTotalPendientes">0</span></h3>
                  <p>Pendientes</p>
                </div>
                <div class="icon">
                  <i class="fas fa-exclamation-circle"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><sup style="font-size: 20px">S/. </sup><span id="spTotalCancelados">0</span></h3>
                  <p>Anulados</p>
                </div>
                <div class="icon">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
              </div>

            </div>
            <!-- ./col -->
          </div>
          
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Finalizados</th>
                <th>Pendientes</th>
                <th>Cancelados</th>
              </tr>
            </thead>
            <tbody>
            <?php
            
              $reporteResumenVenta = ControladorVenta::ctrReporteResumenVenta();

              //var_dump($reporteResumenVenta);

              if($reporteResumenVenta != null){

                $n = 0;
                $totalVentaFinalizados = 0;
                $totalVentaPendientes = 0;
                $totalVentaCancelados = 0;
                $totalNroVentas = 0;

                foreach($reporteResumenVenta as $key => $venta){

                  $ventaFinalizados = $venta["TOTAL_VENTA_FINALIZADO"];
                  $ventaPendientes = $venta["TOTAL_VENTA_PENDIENTE"];
                  $ventaCancelados = $venta["TOTAL_VENTA_CANCELADO"];
                  $nroVentas = $venta["NRO_VENTAS"];

                  $totalVentaFinalizados += $ventaFinalizados;
                  $totalVentaPendientes += $ventaPendientes;
                  $totalVentaCancelados += $ventaCancelados;
                  $totalNroVentas += $nroVentas;

                  $n++;

                  echo '<tr>
                          <td>'.($key + 1).'</td>
                          <td>'.$venta["USUARIO_VENTA"].'</td>
                          <td align="right">'.number_format($ventaFinalizados, 2).'</td>
                          <td align="right">'.number_format($ventaPendientes, 2).'</td>
                          <td align="right">'.number_format($ventaCancelados, 2).'</td>
                        </tr>';
  
                }

                $r = '<script> 
                        $("#spTotalFinalizados").text("'.number_format($totalVentaFinalizados, 2).'");
                        $("#spTotalPendientes").text("'.number_format($totalVentaPendientes, 2).'");
                        $("#spTotalCancelados").text("'.number_format($totalVentaCancelados, 2).'");
                        $("#spOrdenes").text("'.$totalNroVentas.'");
                      </script>';

                echo $r;

              }
            
            ?>
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