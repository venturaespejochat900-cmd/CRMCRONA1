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
                        <p class="mb-0">La verificación en dos pasos ya está activa en su cuenta.</p>
                        <a href="{{ url('/inicios') }}" class="btn btn-secondary mt-3">Volver al inicio</a>
                    @elseif ($qrUrl)
                        <p class="text-muted">Escanee el código QR con su aplicación de autenticación y confirme con el código de 6 dígitos.</p>
                        <div class="text-center my-3">
                            <img src="{{ $qrUrl }}" alt="Código QR" width="200" height="200" class="border rounded">
                        </div>
                        @if ($otpauthUrl)
                            <p class="small text-muted">Si no puede usar el QR, añada una cuenta manualmente con esta clave (formato otpauth):</p>
                            <pre class="small bg-white border p-2 text-break">{{ $otpauthUrl }}</pre>
                        @endif

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
</body>
</html>
