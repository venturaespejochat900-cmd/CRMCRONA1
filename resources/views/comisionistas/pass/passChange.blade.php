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
    <!-- <link rel="icon" href="{{asset('media/images/surembalaje.png')}}" type="image/gif"> -->
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

                  

        <h2>Enhorabuena <br>Contraseña Cambiada <br>con Éxito:</h2>
        <h3>{{$pass}}</h3>

        <div class="form-element form-submit">
            <a href="{{url('/')}}" id="boton-acceso"  name="btn" class="btn btn-secondary login_btn mt-4" >Inicio</a>
        </div>

      </div>
    </div>
  </div>
  
</div>



<script src="{{asset('js/login.js')}}"></script>
<script src="{{asset('js/jquery.md5.js')}}"></script>

</body>
</html>