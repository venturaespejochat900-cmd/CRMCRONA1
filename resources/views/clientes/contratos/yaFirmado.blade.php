

<html lang="es">

<head>
	<title>CRM CRONADIS</title>
    <link rel="icon" href="{{asset('media/images/cronadis2.png')}}" type="image/gif">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<!--===============================================================================================-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<!--===============================================================================================-->
	<link rel="stylesheet" href="{{asset('css/util.css')}}">
	<link rel="stylesheet" href="{{asset('css/main.css')}}">
	<link rel="stylesheet" href="{{asset('css/pgnoti.css')}}">
	<!--===============================================================================================-->
	<style>
		.contact100-form {
			width: 70%;
		}

		.contact100-more {
			width: 30%;
		}

		@media (max-width: 992px) {
			.contact100-form {
				width: 80%;
			}

			.contact100-more {
				width: 20%;
			}
		}

		@media (max-width: 768px) {
			.contact100-form {
				width: 100%;
			}

			.contact100-more {
				width: 100%;
			}
		}

		p {
			line-height: 1.4;
		}
	</style>
</head>
<?php

use App\Http\Controllers\PrescriptorController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Date;

$url = $_SERVER["REQUEST_URI"];
//echo $url;
$separar = explode("/", $url);
//echo $separar[5];
$datosContrato = ClienteController::obtenerClientes($separar[5]);
//echo $datosContrato;
setlocale(LC_ALL, 'es_ES');
$fecha = strftime(" %d de %B de %Y");
//echo $fecha; 
?>

<body class="  pace-done">
	<div class="pace  pace-inactive">
		<div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
			<div class="pace-progress-inner"></div>
		</div>
		<div class="pace-activity"></div>
	</div>
	<input type='hidden' id='id' name='id' value="{{$separar[5]}}">
	<input type='hidden' id='tipo' name='tipo' value="{{$separar[4]}}">

	<div class="container-contact100">
		<div class="wrap-contact100">
			<div class="m-t-50 documento_pdf contact100-form ">
				<p align="center" class="contact100-form-title">UPPSS </p>
				<div class="row eliminar">
					<div class="col-md-12">
						<p><strong>El contrato, RGPD O SEPA,  ya ha sido firmado, si no es así pongase en contacto con nosotros.</strong></p>						
					</div>
				</div>

			</div>

			<div class="contact100-more flex-col-c-m" style="background-image: url('../../media/images/bg.jpg');">
				<div class="row">
					<div class="col-md-12 p-b-10 p-t-10 m-b-20" style="background-color: white; opacity: 0.95; width: 100%;">
						<img src="{{asset('media/images/essentialdiet.png')}}" class="img img-responsive">
					</div>
				</div>
			</div>

			<!--===============================================================================================-->
			<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
			<!--===============================================================================================-->
			<script src="vendor/animsition/js/animsition.min.js"></script>
			<!--===============================================================================================-->
			<script src="vendor/bootstrap/js/popper.js"></script>
			<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
			<!--===============================================================================================-->
			<script src="vendor/select2/select2.min.js"></script>
			<!--===============================================================================================-->
			<script src="vendor/countdowntime/countdowntime.js"></script>
			<script src="js/jquery.validate.min.js"></script>
			<script src="js/localization/messages_es.min.js"></script>
			<script src="js/pace.min.js"></script>
			<script src="js/pgnoti.js"></script>
			<!--===============================================================================================-->
			<script src="js/funciones.js"></script>
			<script>
				$(document).ready(function() {
					$("#tipo_prescriptor").on("change", function() {
						console.log('tipo_prescriptor cambiado', $(this).val());
						if ($(this).val() == 'empresa') {
							$(".contrato-empresa").show();
						} else {
							$(".contrato-empresa").hide();
						}
					});

					$("#tipo_rgpd").on("change", function() {
						console.log('tipo_rgpd cambiado', $(this).val());
						if ($(this).val() == 'empresa') {
							$(".rgpd-empresa").show();
						} else {
							$(".rgpd-empresa").hide();
						}
					});
					$(".rgpd-empresa").hide();

					$('#datos_contrato').validate();

					$('#datos_rgpd').validate();

				});
			</script>

		</div>
</body>

</html>