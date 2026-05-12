<?php
//use Illuminate\Support\Facades\Config;include 'variablesGlobales.php';
?>
    <!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token AÑADIMOS TOKEN PARA PODER ENVIAR FORMULARIO DE LOGUEO POR POST Y EVITAR ERROR-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="shortcut icon" type="image/png" href="<?php echo url('/images/favicon.ico'); ?>"> -->
    <title>CRM CRONADIS</title>
    <link rel="icon" href="{{asset('media/images/cronadis2.png')}}" type="image/gif">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>   
    <script type="text/javascript" src=" {{asset('js/paper.js')}}"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <style type="text/css">
        .ocultar {
            display: none;
        }

        .mostrar {
            display: block;
        }
    </style>
</head>
<body>

<div id="back">
  <canvas id="canvas" class="canvas-back"></canvas>
  <div class="backLeft">
    <img src="{{asset('media/images/surembalaje.png')}}" style=" position: relative; top: 50%; left: 35%; width: 200px;"  class="img-responsive ">
  </div>
</div>

<div id="slideBox">
  <div class="topLayer">
    
    <div class="right">
      <div class="content">

            <!-- Mensajes de Verificación -->
            <div id="error" class="alert alert-danger ocultar" role="alert">
                Las Contraseñas no coinciden, vuelve a intentar !
            </div>
            <div id="ok" class="alert alert-success ocultar" role="alert">
                Las Contraseñas coinciden ! (Procesando formulario ... )
            </div>
            <!-- Fin Mensajes de Verificación -->            

        <h2>Cambiar Contraseña</h2>
        <form action="{{url('change/password')}}" id="login" method="POST">
            <!-- AÑADIMOS TOKEN csrf para poder mandar formulario por post -->
                @csrf
            
          <div class="form-element form-stack col-6">
            <label for="username-login" class="form-label">Nueva Contraseña</label>
            <input type="hidden" id="prescriptor" name="prescriptor" value="{{$prescriptor}}">
            <input type="password" id="password" name="password" required>
          </div>
          <div class="form-element form-stack col-6">
            <label for="password-login" class="form-label">Repite Nueva Contraseña</label>
            <input type="password" id="password2" name="password2" required>
          </div>
          <div class="form-element form-submit">
            <button id="boton-acceso"  name="btn" class="btn login_btn mt-4">Cambiar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</div>



<script src="{{asset('js/login.js')}}"></script>
<script src="{{asset('js/jquery.md5.js')}}"></script>

</body>
</html>