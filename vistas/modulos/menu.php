<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="vistas/dist/img/logomenu.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Wendys Food</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="vistas/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION["sNombreUsuario"] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="inicio" class="nav-link">
              <i class="nav-icon fas fa-utensils"></i>
              <p>
                Ordenes
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="productos" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Productos
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="pagos" class="nav-link">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Pago
              </p>
            </a>
          </li>

          <?php
          if($_SESSION["sRolUsuario"] == "1"){
            echo '<li class="nav-item">
                    <a href="usuarios" class="nav-link">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                        Usuarios
                      </p>
                    </a>
                  </li>';
          }
          ?>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-signal"></i>
              <p>
                Reportes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php
              if($_SESSION["sRolUsuario"] == "1"){
                echo '<li class="nav-item">
                        <a href="resumen-ventas" class="nav-link">
                          <i class="far fa-circle nav-icon text-info"></i>
                          <p>Resumen ventas</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="registro-ventas" class="nav-link">
                          <i class="far fa-circle nav-icon text-info"></i>
                          <p>Registro ventas</p>
                        </a>
                      </li>';
              }
              ?>
              <li class="nav-item">
                <a href="reporte-pagos" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Reporte pagos</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-comments"></i>
              <p>
              Notificaciones
              <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="clientes" class="nav-link">
                  <i class="fas fa-users nav-icon text-warning"></i>
                  <p>Clientes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="notificaciones" class="nav-link">
                  <i class="fas fa-paper-plane nav-icon text-info"></i>
                  <p>Enviar notificacion</p>
                </a>
              </li>
            </ul>
          </li>  

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
