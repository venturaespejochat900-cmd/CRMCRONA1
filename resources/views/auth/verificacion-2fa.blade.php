<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificación en dos pasos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Segundo paso</h1>
                    <p class="text-muted small">Introduzca el código de 6 dígitos de su aplicación de autenticación.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="post" action="{{ route('login.verificar2fa.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" name="code" id="code" class="form-control form-control-lg text-center" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Continuar</button>
                    </form>

                    <form method="post" action="{{ route('login.verificar2fa.cancelar') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm text-secondary w-100">Cancelar e iniciar sesión de nuevo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
