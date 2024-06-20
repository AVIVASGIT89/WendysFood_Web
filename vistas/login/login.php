<!doctype html>
<html lang="es">
  <head>
  	<title>Wendys Food | Acceso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="icon" href="vistas/dist/img/logomenu.png">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="vistas/login/css/style.css">

  </head>
  <body class="img js-fullheight" style="background-image: url(vistas/login/images/bg.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      			<h3 class="mb-4 text-center">Acceso Wendys Food</h3>
		      			<form class="signin-form" method="post">
							<div class="form-group">
								<input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
							</div>
							<div class="form-group">
								<input id="password-field" type="password" name="clave" class="form-control" placeholder="Clave" required>
								<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary submit px-3">Ingresar</button>
							</div>
							<?php
								$login = new ControladorUsuario();
								$login -> ctrValidarUsuario();
							?>
						</form>
		      		</div>
				</div>
			</div>
		</div>
	</section>

  	<script src="vistas/login/js/jquery.min.js"></script>
  	<script src="vistas/login/js/popper.js"></script>
  	<script src="vistas/login/js/bootstrap.min.js"></script>
  	<script src="vistas/login/js/main.js"></script>

  </body>
</html>

