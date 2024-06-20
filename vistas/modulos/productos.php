<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Productos</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo-producto"><i class="fas fa-plus-circle"></i> Nuevo producto</button>
        </div>
        <div class="card-body">
          <table class="table table-sm table-striped table-hover dataTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Codigo producto</th>
                <th>Nombre producto</th>
                <th>Atencion</th>
                <th>Precio S/.</th>
                <th>Tipo precio</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody>
            <?php

              $item = null;
              $valor = null;
            
              $listaProductos = ControladorProducto::ctrMostrarProducto($item, $valor);

              //var_dump($reportePagos);

              if($listaProductos != null){

                foreach($listaProductos as $key => $producto){

                  echo '<tr>
                          <td>'.($key + 1).'</td>
                          <td align="center">'.$producto["CODIGO_PRODUCTO"].'</td>
                          <td>'.$producto["NOMBRE_PRODUCTO"].'</td>
                          <td align="center">'.$producto["NOM_TIPO_COCINA"].'</td>
                          <td align="center">'.number_format($producto["PRECIO_UNITARIO"], 2).'</td>
                          <td align="center">'.$producto["NOM_TIPO_PRECIO"].'</td>
                          <td align="center">
                            <div class="btn-group">
                              <button class="btn btn-warning editarProducto" idProducto="'.$producto["ID_PRODUCTO"].'" title="Editar"><i class="fas fa-pen"></i></button>
                            </div>
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
  <!-- Modal nuevo producto -->
  <div class="modal fade" id="modal-nuevo-producto">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Nuevo producto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="nuevoCodigo" class="col-sm-2 col-form-label">Codigo</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nuevoCodigo" id="nuevoCodigo" onkeypress="return numletras(event);" placeholder="Codigo producto" required>
                  <span style="color: red;" id="spExisteCodigo"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="nombreProducto" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nombreProducto" id="nombreProducto" placeholder="Nombre producto" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="precioUnitario" class="col-sm-2 col-form-label">Precio</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="precioUnitario" id="precioUnitario" onkeypress="return filterFloat(event,this);" placeholder="Precio unitario" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="tipoCocina" class="col-sm-2 col-form-label">Atencion</label>
                <div class="col-sm-10">
                  <select name="tipoCocina" id="tipoCocina" class="form-control" required>
                    <option value="">- Seleccione -</option>
                    <option value="1">Cocina</option>
                    <option value="2">Carrito</option>
                    <option value="3">Ambos</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="tipoPrecio" class="col-sm-2 col-form-label">Tipo precio</label>
                <div class="col-sm-10">
                  <select name="tipoPrecio" id="tipoPrecio" class="form-control" required>
                    <option value="">- Seleccione -</option>
                    <option value="1">Automatico</option>
                    <option value="2">Manual</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnRegistrarProducto" class="btn btn-success"><i class="fas fa-check-circle"></i> Registrar</button>
          </div>
          <?php
            $objProducto = new ControladorProducto();
            $objProducto -> ctrRegistrarProducto();
          ?>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- Modal editar producto -->
  <div class="modal fade" id="modal-editar-producto">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Editar producto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="editarCodigo" class="col-sm-2 col-form-label">Codigo</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="editarCodigo" id="editarCodigo" onkeypress="return numletras(event);" placeholder="Codigo producto" required>
                  <span style="color: red;" id="spExisteCodigo"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="editarProducto" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="editarProducto" id="editarProducto" placeholder="Nombre producto" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="editarPrecioUnitario" class="col-sm-2 col-form-label">Precio</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="editarPrecioUnitario" id="editarPrecioUnitario" placeholder="Precio unitario" onclick="this.select()" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="editarTipoCocina" class="col-sm-2 col-form-label">Atencion</label>
                <div class="col-sm-10">
                  <select name="editarTipoCocina" id="editarTipoCocina" class="form-control" required>
                    <option value="">- Seleccione -</option>
                    <option value="1">Cocina</option>
                    <option value="2">Carrito</option>
                    <option value="3">Ambos</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="editarTipoPrecio" class="col-sm-2 col-form-label">Tipo precio</label>
                <div class="col-sm-10">
                  <select name="editarTipoPrecio" id="editarTipoPrecio" class="form-control" required>
                    <option value="">- Seleccione -</option>
                    <option value="1">Automatico</option>
                    <option value="2">Manual</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" id="btnEditarProducto" class="btn btn-success">Actualizar</button>
            <input type="hidden" id="idProducto">
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->