<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Activar verificación en dos pasos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Verificación en dos pasos</h1>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @if ($alreadyEnabled)
                        <p class="mb-3">La verificación en dos pasos está activa en su cuenta.</p>
                        <p class="small text-muted mb-3">Para desactivarla, introduzca un código de 6 dígitos actual de su aplicación de autenticación. Si ha perdido el acceso a la app, un administrador puede vaciar los campos <code>CRM_TwoFactorSecret</code> y <code>CRM_TwoFactorConfirmedAt</code> en Sage para su usuario.</p>

                        <form method="post" action="{{ route('seguridad.2fa.desactivar') }}" class="mb-4" onsubmit="return confirm('¿Desactivar la verificación en dos pasos? El inicio de sesión volverá a ser solo con usuario y contraseña.');">
                            @csrf
                            <div class="mb-3">
                                <label for="disable_code" class="form-label">Código de la app</label>
                                <input type="text" name="code" id="disable_code" class="form-control text-center" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required>
                            </div>
                            <button type="submit" class="btn btn-outline-danger">Desactivar verificación en dos pasos</button>
                        </form>

                        <a href="{{ url('/inicios') }}" class="btn btn-secondary">Volver al inicio</a>
                    @elseif ($otpauthUrl)
                        <p class="text-muted">Escanee el código QR con su aplicación de autenticación y confirme con el código de 6 dígitos.</p>
                        <div class="text-center my-3">
                            <div id="crm-twofa-qrcode" class="d-inline-block p-2 border rounded bg-white"></div>
                        </div>
                        <p class="small text-muted">Si no ve el QR, añada la cuenta manualmente en la app con esta clave (formato otpauth):</p>
                        <pre class="small bg-white border p-2 text-break">{{ $otpauthUrl }}</pre>

                        <form method="post" action="{{ route('seguridad.2fa.confirmar') }}" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de confirmación</label>
                                <input type="text" name="code" id="code" class="form-control form-control-lg text-center" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Activar</button>
                            <a href="{{ route('seguridad.2fa') }}" class="btn btn-link">Empezar de nuevo</a>
                        </form>
                    @else
                        <p>Active la verificación en dos pasos para que, tras introducir usuario y contraseña, se solicite un código de su aplicación de autenticación.</p>
                        <p class="small text-muted">Los campos deben existir en la tabla Sage <strong>Comisionistas</strong> (creados desde Sage). Nombres por defecto: <code>CRM_TwoFactorSecret</code>, <code>CRM_TwoFactorConfirmedAt</code> (configurables en <code>.env</code>).</p>
                        <form method="post" action="{{ route('seguridad.2fa.activar') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Generar código QR</button>
                        </form>
                        <a href="{{ url('/inicios') }}" class="btn btn-link mt-2">Volver al inicio</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if (!empty($otpauthUrl))
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var wrap = document.getElementById('crm-twofa-qrcode');
    var otpauth = @json($otpauthUrl);
    if (wrap && typeof QRCode !== 'undefined' && otpauth) {
        new QRCode(wrap, {
            text: otpauth,
            width: 200,
            height: 200,
            correctLevel: QRCode.CorrectLevel.M
        });
    }
});
</script>
@endif
</body>
</html>
