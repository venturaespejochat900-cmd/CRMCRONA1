<?php
    if(session('codigoComisionista') == 0){  
        header("Location: https://cronadis.abmscloud.com/");
        exit();
    }else{
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <title>CRM CRONADIS</title>
    <link rel="icon" href="{{asset('media/images/cronadis2.png')}}" type="image/gif">
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap"
      rel="stylesheet"
    />  
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.4/dist/tailwind.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/tailwind.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.5.x/dist/component.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
    <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
    <!-- JQUERY -->
    <script src="{{asset('js/jquery-3.6.0.js')}}"></script>


@include('layouts.sidebar')
@include('layouts.navbar')



<div class="container mx-auto flex flex-wrap">
        <div class="w-full p-4">
            <a href="dashboard">                                
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #c851c4;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> DASHBOARD </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fas fa-home"></i>
                        </span> 
                    </div>                    
                </div>​                                         
            </a>            
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="{{ route('seguridad.2fa') }}">
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md" style="background-color: #2d3748;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold text-white"> SEGURIDAD </h3>
                        <p class="text-gray-300 text-sm">Verificación en dos pasos</p>
                        <p class="small-box-footer text-gray-200">Configurar<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;" class="text-white">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <?php
            if(session('tipo') == 3 || session('tipo')>3){
        ?>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="comisionistas">                            
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #00b7db;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> COMISIONISTAS </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fas fa-user-tie"></i>
                        </span> 
                    </div>                    
                </div>​                                          
            </a>
        </div>
        <?php
            }
        ?>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="clientes">                                
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #00db92;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> CLIENTES </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fa fa-users"></i>
                        </span> 
                    </div>                    
                </div>​                                        
            </a>
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="potenciales">                
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: teal;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> POTENCIALES </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fa fa-users"></i>
                        </span> 
                    </div>                    
                </div>​                                         
            </a>
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="calendario">                                 
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #9c5ed9;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> CALENDARIO </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="far fa-calendar"></i>
                        </span> 
                    </div>                    
                </div>​                                        
            </a>
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="Stock">                                
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #d95e5e;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> ARTICULOS </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fa fa-shopping-cart"></i>
                        </span> 
                    </div>                    
                </div>​                                          
            </a>
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="informes1">                                
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #db6300;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> INFORMES </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fa fa-file"></i>
                        </span> 
                    </div>                    
                </div>​                                           
            </a>
        </div>
        <div class="w-1/2 md:w-2/2 p-4">
            <a href="heatmap">                                 
                <div class="flex items-center justify-between p-8 rounded-xl shadow-md"  style="background-color: #c4844f;">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold"> MAPAS DE CALOR </h3>
                        <p> </p>                                                               
                        <p class="small-box-footer">Acceder<i class="fa fa-arrow-circle-right"></i></p>
                    </div>
                    <div>
                        <span style="font-size: 48px;">
                            <i class="fas fa-map-marked-alt"></i>
                        </span> 
                    </div>                    
                </div>​                                         
            </a>
        </div>
    </div>
</div>

@include('layouts.footer')
@include('layouts.panels')


<?php
    }
?>