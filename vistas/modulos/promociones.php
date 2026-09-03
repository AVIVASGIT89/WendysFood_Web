<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Promociones</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nueva-promocion"><i class="fas fa-plus-circle"></i> Nueva promocion</button>
        </div>
        <div class="card-body">
          <table class="table table-sm table-striped table-hover dataTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Promocion</th>
                <th>Descripcion</th>
                <th>URL Imagen</th>
                <th>Vista</th>
                <th>Estado</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody>
            <?php

              $item = null;
              $valor = null;
            
              $listaPromociones = ControladorPromociones::ctrMostrarPromociones($item, $valor);

              //var_dump($listaPromociones);

              if($listaPromociones != null){

                foreach($listaPromociones as $key => $promocion){

                  echo '<tr>
                          <td>'.($key + 1).'</td>
                          <td>'.$promocion["NOMBRE_PROMOCION"].'</td>
                          <td>'.$promocion["DESCRIPCION_PROMOCION"].'</td>
                          <td>'.$promocion["URL_IMAGEN"].'</td>';

                    echo '<td align="center">
                            <div class="btn-group">
                              <button class="btn btn-primary verImagenPromocion" urlImagen="'.$promocion["URL_IMAGEN"].'" title="Editar"><i class="fas fa-image"></i></button>
                            </div>
                          </td>';

                          if($promocion["ESTADO_REGISTRO"] == "1"){
                            echo '<td align="center"><span class="badge badge-success">Activo</span></td>';
                          }else{
                            echo '<td align="center"><span class="badge badge-warning">Desactivo</span></td>';
                          }

                    echo '<td align="center">
                            <div class="btn-group">
                              <button class="btn btn-warning" idPromocion="'.$promocion["ID_PROMOCION"].'" title="Editar"><i class="fas fa-pen"></i></button>';

                      if($promocion["ESTADO_REGISTRO"] == "1"){
                        echo '<button class="btn btn-danger desactivarPromocion" idPromocion="'.$promocion["ID_PROMOCION"].'" title="Desactivar"><i class="fas fa-times-circle"></i></button>';
                      }else{
                        echo '<button class="btn btn-success activarPromocion" idPromocion="'.$promocion["ID_PROMOCION"].'" title="Activar"><i class="fas fa-check-circle"></i></button>';
                      }  

                      echo '</div>
                          </td>
                        </tr>';
  
                }

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


  <!------------------ Modales ------------------>
  <!-- Modal nueva promocion -->
  <div class="modal fade" id="modal-nueva-promocion">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Nueva promocion</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="nuevaPromocion" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nuevaPromocion" id="nuevaPromocion" placeholder="Nombre promocion" required>
                  <span style="color: red;" id="spExisteCodigo"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="detallePromocion" class="col-sm-2 col-form-label">Detalle</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="detallePromocion" id="detallePromocion" placeholder="Detalle promocion" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="urlImagen" class="col-sm-2 col-form-label">URL</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="urlImagen" id="urlImagen" placeholder="URL imagen" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnRegistrarPromocion" class="btn btn-success"><i class="fas fa-check-circle"></i> Registrar</button>
          </div>
          <?php
            $objPromocion = new ControladorPromociones();
            $objPromocion -> ctrRegistrarPromocion();
          ?>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- Modal imagen promocion -->
  <div class="modal fade" id="modal-imagen-promocion">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Imagen promocion</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">

            <img id="imagenModal"
              src=""
              class="img-fluid"
              alt="Imagen">
            
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->