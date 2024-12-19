<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>

  <title>Wendys Food System</title>

  <link rel="icon" href="vistas/dist/img/logomenu.png">

  <!--*********** Plugins CSS -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vistas/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/dist/css/adminlte.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vistas/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vistas/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vistas/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="vistas/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Estilos personalizados (modifica algunos plugins) -->
  <link rel="stylesheet" href="vistas/css/estilos-personalizados.css">

  
  <!--*********** Plugins JavaScript -->
  <!-- jQuery -->
  <script src="vistas/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="vistas/dist/js/adminlte.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="vistas/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="vistas/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="vistas/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="vistas/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="vistas/plugins/jszip/jszip.min.js"></script>
  <script src="vistas/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="vistas/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="vistas/plugins/sweetalert2/sweetalert2.min.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    if(isset($_SESSION["sSesionUsuario"]) && $_SESSION["sSesionUsuario"] == "ok"){

        include "modulos/cabezote.php";
        include "modulos/menu.php";

        if(isset($_GET["ruta"])){

            if($_GET["ruta"] == "inicio" ||
               $_GET["ruta"] == "productos" ||
               $_GET["ruta"] == "pagos" ||
               $_GET["ruta"] == "reporte-pagos" ||
               $_GET["ruta"] == "resumen-ventas" ||
               $_GET["ruta"] == "registro-ventas" ||
               $_GET["ruta"] == "usuarios" ||
               $_GET["ruta"] == "notificaciones" ||
               $_GET["ruta"] == "clientes" ||
               $_GET["ruta"] == "salir"){

                include "modulos/".$_GET["ruta"].".php";

            }else{

                include "modulos/404.php";

            }

        }

        include "modulos/footer.php";
    
    }else{

        echo '<script>
                window.location = "index.php";
              </script>';

    }
    
    ?>
</div>
<!-- ./wrapper -->


<!------------------- Muestra animacion de Cargando... ------------------->
<div id="loading-screen" style="display:none">
    <img src="vistas/images/spinning-circles.svg">
</div>


<script src="../ambientesejecucion/wendysfood.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/plantilla.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/producto.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/usuario.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/venta.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/pago.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/reportes.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/notificaciones.js?v=<?php echo(rand()); ?>"></script>
<script src="vistas/js/comprobantesunat.js?v=<?php echo(rand()); ?>"></script>

</body>
</html>
