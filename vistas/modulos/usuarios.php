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
            <h1>Usuarios</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo-usuario"><i class="fas fa-user-plus"></i> Nuevo usuario</button></h3>
        </div>
        <div class="card-body">
          <table class="table table-sm table-bordered table-striped table-hover dataTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Estado</th>
                <th>Opcion</th>
              </tr>
            </thead>
            <tbody>
            <?php

              $listaUsuarios = ControladorUsuario::ctrListarUsuarios(null, null);

              //var_dump($listaUsuarios);

              foreach($listaUsuarios as $key => $valor){

                echo '<tr>
                        <td>'.($key + 1).'</td>
                        <td>'.$valor["NOMBRE_USUARIO"].' '.$valor["APELLIDO_USUARIO"].'</td>
                        <td align="center">'.$valor["USUARIO"].'</td>
                        <td align="center">'.$valor["NOMBRE_ROL"].'</td>
                        <td align="center">'.$valor["FECHA_REGISTRO"].'</td>';
                      if($valor["ESTADO_REGISTRO"] == "1"){

                        echo '<td align="center"><span class="badge badge-success">Activo</span></td>
                              <td align="center">
                                <div class="btn-group">
                                  <button class="btn btn-warning btnEditar" idUsuario="'.$valor["ID_USUARIO"].'"><i class="fas fa-pen"></i></button>
                                  <button class="btn btn-danger btnDesactivar" idUsuario="'.$valor["ID_USUARIO"].'"><i class="fas fa-times"></i></button>
                                </div>
                              </td>';

                      }else{

                        echo '<td align="center"><span class="badge badge-warning">Inactivo</span></td>
                              <td align="center">
                                <div class="btn-group">
                                  <button class="btn btn-warning btnEditar" idUsuario="'.$valor["ID_USUARIO"].'"><i class="fas fa-pen"></i></button>
                                  <button class="btn btn-success btnActivar" idUsuario="'.$valor["ID_USUARIO"].'"><i class="fas fa-check"></i></button>
                                </div>
                              </td>';

                      }
          
                echo '</tr>';
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
  <!-- Modal nuevo usuario -->
  <div class="modal fade" id="modal-nuevo-usuario">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Nuevo usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="nombreUsuario" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario" placeholder="Ingrese nombre" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="apellidoUsuario" class="col-sm-2 col-form-label">Apellido</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="apellidoUsuario" id="apellidoUsuario" placeholder="Ingrese apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="nuevoUsuario" class="col-sm-2 col-form-label">Usuario</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nuevoUsuario" id="nuevoUsuario" onkeypress="return numletras(event);" placeholder="Ingrese usuario" required>
                  <span style="color: red;" id="spExisteUsuario"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="claveUsuario" class="col-sm-2 col-form-label">Clave</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" name="claveUsuario" id="claveUsuario" placeholder="Ingrese clave" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="rolUsuario" class="col-sm-2 col-form-label">Rol</label>
                <div class="col-sm-10">
                  <select name="rolUsuario" id="rolUsuario" class="form-control" required>
                    <option value="">- Seleccione -</option>

                    <?php

                      $listaRoles = ControladorUsuario::ctrListarRolesUsuario(null, null);

                      foreach($listaRoles as $key => $value){

                        echo '<option value="'.$value["ID_ROL"].'">'.$value["NOMBRE_ROL"].'</option>';
    
                      }

                    ?>

                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnRegistrar" class="btn btn-success"><i class="fas fa-check-circle"></i> Registrar</button>
          </div>
          <?php
            $objUsuario = new ControladorUsuario();
            $objUsuario -> ctrRegistrarUsuario();
          ?>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- Modal editar usuario -->
  <div class="modal fade" id="modal-editar-usuario">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form-horizontal" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Editar usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="form-group row">
                <label for="nombreUsuarioEditar" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nombreUsuarioEditar" id="nombreUsuarioEditar" placeholder="Ingrese nombre" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="apellidoUsuarioEditar" class="col-sm-2 col-form-label">Apellido</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="apellidoUsuarioEditar" id="apellidoUsuarioEditar" placeholder="Ingrese apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="usuarioEditar" class="col-sm-2 col-form-label">Usuario</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="usuarioEditar" id="usuarioEditar" placeholder="Ingrese usuario" disabled>
                  <span style="color: red;" id="spExisteUsuario"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="claveUsuarioEditar" class="col-sm-2 col-form-label">Clave</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" name="claveUsuarioEditar" id="claveUsuarioEditar" placeholder="Ingrese clave" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="rolUsuarioEditar" class="col-sm-2 col-form-label">Rol</label>
                <div class="col-sm-10">
                  <select name="rolUsuarioEditar" id="rolUsuarioEditar" class="form-control" required>
                    <option value="">- Seleccione -</option>

                    <?php

                      $listaRoles = ControladorUsuario::ctrListarRolesUsuario(null, null);

                      foreach($listaRoles as $key => $value){

                        echo '<option value="'.$value["ID_ROL"].'">'.$value["NOMBRE_ROL"].'</option>';
    
                      }

                    ?>

                  </select>
                  <input type="hidden" name="idUsuarioEditar" id="idUsuarioEditar" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnActualizar" class="btn btn-success"><i class="fas fa-check-circle"></i> Actualizar</button>
          </div>
          <?php
            $objUsuarioEdit = new ControladorUsuario();
            $objUsuarioEdit -> ctrEditarUsuario();
          ?>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->