  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Registrar Pago</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
        <div class="container">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Nuevo pago</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="fechaPago">Fecha</label>
                    <input type="date" class="form-control" name="fechaPago" value="<?php echo date("Y-m-d");?>" placeholder="Fecha" required>
                  </div>
                  <div class="form-group">
                    <label for="conceptoPago">Tipo pago</label>
                    <select name="tipoPago" id="tipoPago" class="form-control" required>
                      <option value="">- Seleccione -</option>

                      <?php
                        $listaTipoPago = $_SESSION["sListaTipoPago"];

                        foreach($listaTipoPago as $key => $tipoPago){
                      ?>

                        <option value="<?php echo $tipoPago["ID_TIPO_PAGO"];?>"><?php echo $tipoPago["NOMBRE_TIPO_PAGO"];?></option>

                      <?php
                        }
                      ?>
                      
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="conceptoPago">Concepto</label>
                    <input type="text" class="form-control" name="conceptoPago" placeholder="Concepto pago" required>
                  </div>
                  <div class="form-group">
                    <label for="montoPago">Monto</label>
                    <input type="number" step="0.01" class="form-control" name="montoPago" placeholder="Monto pago" required>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <div class="col-xs-12 col-sm-2">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-check-circle"></i> Registrar</button>
                  </div>
                </div>

                <?php
                  $registrarPago = new ControladorPago();
                  $registrarPago -> ctrRegistrarPago();
                ?>

              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->

        </div>
        <!-- /.row -->

      </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->