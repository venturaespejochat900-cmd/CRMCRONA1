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


use App\Http\Controllers\ClienteController;

use Illuminate\Support\Facades\Date;

$url = $_SERVER["REQUEST_URI"];
//echo $url;
$separar = explode("/", $url);
//echo $separar[5];
$datosContrato = ClienteController::obtenerClientes($separar[3]);
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
	<input type='hidden' id='id' name='id' value="{{$separar[3]}}">
	<input type='hidden' id='tipo' name='tipo' value="{{$separar[2]}}">

	<div class="container-contact100">
		<div class="wrap-contact100">
			<form id="direccion_principal" class="contact100-form validate-form" method="post">

				<div class="m-t-50 documento_pdf">
					<p align="center" class="contact100-form-title">Orden de domiciliación de adeudo directo SEPA B2B</p>
					<div class="row">
						<div class="col-md-12">
							<p class="invisible-pdf"><strong>Deberás dar al consentimiento y firmar EN EL RECUADRO DE LA PARTE INFERIOR para validar este ORDEN DE DOMICILIACION.</strong></p>
							<p>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 m-t-10">
							<p class="m-t-20" align="center">DATOS DEL ACREEDOR </p>
						</div>
						<div class="col-md-12 m-t-10 border">
							<p class="m-t-20"><strong>Referencia de la orden de domiciliación:</strong></p>
							<p>Nombre Empresa</p>
							<p class="m-t-10"><strong>Indentificador del acreedor:</strong></p>
							<p>NIF</p>
							<p class="m-t-10"><strong>Nombre del acreedor:</strong></p>
							<p>Razon Social</p>
							<p class="m-t-10"><strong>Dirección:</strong></p>
							<p>c/ddd n/nnn</p>
							<p class="m-t-10"><strong>Código postal - Población - Provincia:</strong></p>
							<p>28921 - Prueba - Prueba</p>
							<p class="m-t-10"><strong>Pais:</strong></p>
							<p>España</p>
						</div>						
						<div class="col-md-12 m-t-10">
							<p class="m-t-20">Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al acreedor a enviar instrucciones a la entidad del deudor para adeudar su cuenta y
								(B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Esta orden de domiciliación está prevista para operaciones exclusivamente entre empresas y/o autónomos. El deudor no tiene derecho a que su entidad le reembolse una vez que se haya realizado el cargo en cuenta, pero puede solicitar a su entidad que no efectúe el adeudo en la cuenta hasta la fecha debida. Podrá obtener información detallada del procedimiento en su entidad financiera.
							</p>
						</div>
						<div class="col-md-12 m-t-10">
							<p class="m-t-20" align="center">DATOS DEL DEUDOR </p>
						</div>
						<div class="col-md-12 m-t-10 border">
							<p class="m-t-10"><strong>Nombre del deudor: </strong></p>
							<p>{{$datosContrato->RazonSocial}}</p>
							<p class="m-t-10"><strong>Dirección del deudor: </strong></p>
							<p>{{$datosContrato->Domicilio}}</p>
							<p class="m-t-10"><strong>Código postal - Población - Provincia: </strong></p>
							<p>{{$datosContrato->CodigoPostal}} - {{$datosContrato->Municipio}}</p>
							<p class="m-t-10"><strong>Pais del deudor: </strong></p>
							<p>España</p>
							<!-- <p class="m-t-10"><strong>Swift BIC:</strong></p>
							<p>CAHMESMMXXX</p> -->
							<p class="m-t-10"><strong>Número de cuenta - IBAN:</strong></p>
							<p>{{$datosContrato->IBAN}}</p>
							<p class="m-t-10"><strong>Tipo de pago:</strong></p>
							<p>Pago recurrente</p>
						</div>

						<div class="col-md-12 m-t-10">
							<p class="m-t-50">En Prueba, a {{$fecha}}</p>

							<div class="col-md-12 m-t-20" style="height:160px;">
								<div style="width:50%; float:left;">
									@@FIRMA@@
								</div>
							</div>

						</div>
					</div>

				</div>




				<div class="col-md-8 col-xs-12 col-sm-12 m-b-5 m-t-10" style="position: relative;">
					<div class="firma" style="width: auto; height: 250px; border: 1px solid #aaa;">
						<canvas width="395.062" height="250" style="touch-action: none;"></canvas>
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
				init_pad();
				/*
					function firmar()
					{
				console.log('firmado');
				return;
					if ($("input[name=dirpre]:checked").length == 0) { return; }

					obj = $("input[name=dirpre]:checked");

					var id1 = $("input[name=dirpre]:checked").closest("li").attr("data-id");

					//Es bid??

					if (obj.val()=="bid") { msgobj ($("div.container-contact100-form-btn"),'No se puede borrar la dirección principal.','danger', '', 'top', '', 5000); return; }

					confirm('Confirma para borrar la dirección.',"{style:danger,text:Este operación no se puede deshacer.}",'{style:danger,text:BORRAR DIRECCIÓN}','',
							function ()
							{

								load_script ('../direcciones',"acc=borra_direccion&token=5pr056ebjbotfo00001443oncicjcnhe&id="+id1);

							}
							,'');

					}
				*/
			</script>



		</div>
	</div>
</body>

</html>