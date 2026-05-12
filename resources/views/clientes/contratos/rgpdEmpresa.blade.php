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
$comi = $separar[3];
$comis = substr($comi,0,36);
//echo $comis;
$datosContrato = ClienteController::obtenerClientes($comis);
//echo $datosContrato;
setlocale(LC_ALL, 'es_ES');
$fecha = strftime(" %d de %B de %Y");
//echo $datosContrato; 
?>

<body class="  pace-done">
	<div class="pace  pace-inactive">
		<div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
			<div class="pace-progress-inner"></div>
		</div>
		<div class="pace-activity"></div>
	</div>
	<input type='hidden' id='id' name='id' value="{{$comi}}">
	<input type='hidden' id='tipo' name='tipo' value="{{$separar[2]}}">



	<div class="container-contact100">
		<div class="wrap-contact100">
			<form id="direccion_principal" class="contact100-form validate-form" method="post">
				<div class="m-t-50 documento_pdf">
					<p align="center" class="contact100-form-title">CONTRATO DE ENCARGADO DEL TRATAMIENTO</p>
					<div class="row eliminar">
						<div class="col-md-12">
							<p class="invisible-pdf"><strong>Deberá firmar EN EL RECUADRO DE LA PARTE INFERIOR para validar este CONTRATO RGPD.</strong></p>
							<p>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 m-t-10">
							

							<hr>
							<p class="m-t-20" align="center"><strong>APÉNDICE I</strong> </p>
							<p class="m-t-20"><strong>DECLARACIÓN DEL RESPONSABLE SOBRE LA OBLIGACIÓN DE DESIGNAR UN DELEGADO DE PROTECCIÓN DE DATOS (*)</strong> </p>
							<p class="m-t-10">El Proveedor manifiesta bajo su exclusiva responsabilidad que:</p>
							<p class="m-t-10"> <input type="checkbox" value="1" name="check1" id="check1" style="margin-left: 1px; margin-right: 10px;"> No tiene obligación de designar un delegado de protección de datos por no cumplirse los requisitos previstos en el RGPD, si bien la identidad y datos de contacto del responsable del cumplimiento de esta materia en el seno de la compañía del Proveedor son los siguientes:</p>
							<div class="row dato-empresa" style="padding: .5rem 1.25rem;">
								<div class="col-md-12 p-3">
									<div class="row">
										<div class="col-md-2 col-xs-12 label-input">Identidad: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="identidad1" id="identidad1">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
									<div class="row m-t-10">
										<div class="col-md-2 col-xs-12 label-input">Email: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="email1" id="email1">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
									<div class="row m-t-10">
										<div class="col-md-2 col-xs-12 label-input">Teléfono: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="telefono1" id="telefono1">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
								</div>
							</div>
							<p class="m-t-10">En el supuesto de que la identidad y datos de contacto anteriores se vean modificados durante la vigencia de la relación jurídica existente entre las partes por motivo de la prestación de servicios por parte del Proveedor, éste se compromete a comunicárselo de inmediato a la Sociedad y a facilitarle los datos de identidad y contacto debidamente actualizados.</p>

							<p class="m-t-10"><input type="checkbox" value="1" name="check2" id="check2" style="margin-left: 1px; margin-right: 10px;"> Los datos de contacto del delegado de protección de datos del Proveedor son los siguientes:</p>
							<div class="row dato-empresa " style="padding: .5rem 1.25rem;">
								<div class="col-md-12 p-3">
									<div class="row">
										<div class="col-md-2 col-xs-12 label-input">Identidad: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="identidad2" id="identidad2">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
									<div class="row m-t-10">
										<div class="col-md-2 col-xs-12 label-input">Email: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="email2" id="email2">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
									<div class="row m-t-10">
										<div class="col-md-2 col-xs-12 label-input">Teléfono: </div>
										<div class="col-md-10 col-xs-12 grupo-input">
											<input class="form-control required edita-input" name="telefono2" id="telefono2">
											<span class="texto-input" style="display: none;"></span>
										</div>
									</div>
								</div>
							</div>
							<p class="m-t-30 font-italic">(*) Designación obligatoria de un Delegado de Protección de Datos:</p>
							<p class="m-t-10 p-l-30 font-italic">• Cuando el tratamiento lo lleva a cabo una autoridad u organismo públicos.</p>
							<p class="m-t-10 p-l-30 font-italic">• Cuando las actividades principales del responsable o el encargado del tratamiento consisten en operaciones de tratamiento que requieren el seguimiento regular y sistemático de los interesados a
								gran escala.</p>
							<p class="m-t-10 p-l-30 font-italic">• Cuando las actividades principales del responsable o el encargado del tratamiento consisten en el
								tratamiento a gran escala de categorías especiales de datos o datos personales relacionados con condenas y delitos penales.</p>

							

							<div class="col-md-12 m-t-20" style="height:160px;">
								<div style="width:50%; float:right;">
									<p class="m-t-20" align="center">Ventura Espejo</p>
									<p class="m-t-20" align="center"><img width="100%" src="{{asset('media/images/sign-20200422.png')}}"></p>
									<p class="m-t-10" align="center">Nombre</p>
									<p align="center">Administrador Único</p>
								</div>
								<div style="width:50%; float:left;">
									<p class="m-t-20">{{$datosContrato->RazonSocial}} </p>
									@@FIRMA@@
									<p class="m-t-20"></p>
									<p>REPRESENTANTE LEGAL</p>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-md-8 col-xs-12 col-sm-12 m-b-5 m-t-10 m-l-50" style="position: relative;">
					<div class="firma" style="width: auto; height: 250px; border: 1px solid #aaa;">
						<canvas width="400.656" height="250" style="touch-action: none;"></canvas>
					</div>
					<div class="signature-pad--footer">
						<div class="description"><small>Firma en el cuadro superior (con el ratón o con el dedo en el caso de pantalla digital)</small></div>
						<div class="col-md-12 alert alert-danger aviso" style="display:none;"></div>
						<div class="col-md-12 alert alert-success aviso2" style="display:none;"></div>
						<div class="signature-pad--actions mt-3">
							<div>
								<button type="button" class="btn btn-light" data-action="clear">Borrar</button>
								<button type="button" class="btn btn-success" data-action="save-png">Firmar y Enviar</button>
							</div>
						</div>
					</div>

				</div>

			</form>

			<div class="contact100-more flex-col-c-m" style="background-image: url('../../media/images/bg.jpg');">
				<div class="row">
					<div class="col-md-12 p-b-10 p-t-10 m-b-20" style="background-color: white; opacity: 0.95; width: 100%;">
						<img src="{{asset('media/images/surembalaje.png')}}" class="img img-responsive" style="width: 90%;">
					</div>
				</div>
			</div>

			<!--===============================================================================================-->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
			<!--===============================================================================================-->
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
			<!--===============================================================================================-->
			<script src="{{asset('js/countdowntime.js')}}"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/localization/messages_es.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js"></script>
			<script src="{{asset('js/pgnoti.js')}}"></script>
			<!--===============================================================================================-->
			<script src="{{asset('js/signature_pad.js')}}"></script>
			<script src="{{asset('js/funciones.js')}}"></script>
			<script>
				//var token = "5pr056ebjbotfo00001443oncicjcnhe";

				$(".texto-input").hide();
				$(".edita-input").off();
				$(".edita-input").on("keyup", function() {
					$(this).closest("div").find('.texto-input').html($(this).val())
				});

				$('input[type=checkbox]').on("click", function() {
					console.log("pulsado", $(this));
					if ($(this).prop("checked") == true) {
						$(this).attr("checked", "checked");
					} else {
						$(this).removeAttr("checked");
					}
				})

				init_pad_rgpd();
			</script>


		</div>
	</div>
</body>

</html>