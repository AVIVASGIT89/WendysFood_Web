<?php

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
            <h1>Reporte pagos</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <form rol="form" method="POST">
            <div class="form-inline">
              
              <div class="col-xs-12 col-sm-2 mb-1">
                <div class="input-group align-items-center">
                  Desde: <input type="date" name="fechaDesde" class="form-control" value="<?php echo $fechaDesde?>">
                </div>
              </div>

              <div class="col-xs-12 col-sm-2 mb-1">
                <div class="input-group align-items-center">
                  Hasta: <input type="date" name="fechaHasta" class="form-control" value="<?php echo $fechaHasta?>">
                </div>
              </div>

              <div class="col-xs-12 col-sm-1">
                <button type="submit" class="btn btn-block btn-primary">Buscar</button>
              </div>

            </div>
          </form>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-file-invoice-dollar"></i></span>

                <div class="info-box-content">
                  <div class="row">
                    <div class="col">Cant. registros:</div>
                    <div class="col"><b><span id="spRegistroPagos">0</span></b></div>
                  </div>
                  <div class="row">
                    <div class="col">Total pagos:</div>
                    <div class="col"><b> S/. <span id="spTotalPagos">0</span></b></div>
                  </div>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>

          <table class="table table-sm table-bordered table-striped table-hover dataTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha pago</th>
                <th>Concepto</th>
                <th>Monto S/.</th>
                <th>Fecha registro</th>
                <th>Usuario</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody>
            <?php
            
              $reportePagos = ControladorPago::ctrReportePagos();

              //var_dump($reportePagos);

              if($reportePagos != null){

                $n = 0;
                $totalMontoPagos = 0;

                foreach($reportePagos as $key => $pagos){

                  $n++;

                  $montoPago = $pagos["MONTO_PAGO"];
                  $totalMontoPagos = $totalMontoPagos + $montoPago;

                  echo '<tr>
                          <td>'.($key + 1).'</td>
                          <td align="center">'.$pagos["FECHA_PAGO"].'</td>
                          <td>'.$pagos["CONCEPTO_PAGO"].'</td>
                          <td align="center">'.number_format($pagos["MONTO_PAGO"], 2).'</td>
                          <td align="center">'.$pagos["FECHA_REGISTRO"].'</td>
                          <td align="center">'.$pagos["USUARIO_REGISTRO"].'</td>
                          <td align="center">
                            <div class="btn-group">
                              <button class="btn btn-sm btn-danger anularPago" idPago="'.$pagos["ID_PAGO"].'" title="Anular pago"><i class="fas fa-times-circle"></i></button>
                            </div>
                          </td>
                        </tr>';
  
                }

                $r = '<script> 
                        $("#spTotalPagos").text("'.number_format($totalMontoPagos, 2).'");
                        $("#spRegistroPagos").text("'.$n.'");
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