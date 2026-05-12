@include('layouts.header')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
<script src="{{asset('js/map3.js')}}"></script>
@include('layouts.sidebar')
@include('layouts.navbar')
<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 650px;
       width: 1300px;
       overflow: hidden;
       float: left;
       border: thin solid #333;
    }

    @media only screen and (min-width: 600px) {
        #map {
            height: 500px;
            width: 500px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 768px) {
        #map {
            height: 700px;
            width: 700px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 1025px) {
        #map {
            height: 650px;
            width: 900px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 1440px) {
        #map {
            height: 700px;
            width: 1000px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    
    .preloader {
    width: 70px;
    height: 70px;
    border: 10px solid #eee;
    border-top: 10px solid #666;
    border-radius: 50%;
    animation-name: girar;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    margin-left: 350%;    
    }
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.tooltip{
  visibility: hidden;
  position: absolute;
}
.has-tooltip:hover .tooltip {
  visibility: visible;
  z-index: 100;
}
/* smartphones, touchscreens */
@media (hover: none) and (pointer: coarse) {
    .has-tooltip:hover .tooltip{
    visibility: hidden;    
    }
}

    
</style>
<?php 
    use App\Http\Controllers\InformesController;
?>
<?php
    if(session('codigoComisionista') == 0){  
        header("Location: http://cronadis.abmscloud.com/");
        exit();
    }else{
?>
<div class="grid grid-cols-1 p-4 space-y-12 lg:gap-12 lg:space-y-0 lg:grid-cols-4 border-b">
    <!-- Bar chart card -->
    <div class="col-span-12 bg-white rounded-md dark:bg-darker">
        <!-- Card header -->
        <div class="flex items-center justify-between p-4 border-b dark:border-primary">
            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Informes Mapa de calor</h4>
            <div class="flex items-center space-x-2">
                <!-- <span class="text-sm text-gray-500 dark:text-light">Solicitar</span> -->
                <button onclick="enviarInforme()" class="bg-primary-darker hover:bg-primary-100 text-white hover:text-black font-bold py-2 px-4 rounded-full">
                    Enviar
                </button>
            </div>
        </div>
        <!-- Chart -->
        <div class="relative p-5">
            <div class="mt-5 md:mt-0 md:col-span-1">
                <form action="#" method="POST">
                    
                            <div class="grid grid-cols-6 gap-6">                            
                                <div >
                                    <label for="agrupacion" class="block text-sm font-medium text-gray-500 dark:text-light">Agrupación por:</label>
                                    <select id="agrupacion" name="agrupacion" autocomplete="agrupacion" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                        <option value="0"></option>
                                        <option value="1">Comisionista</option>
                                        <option value="2">Cliente</option>                                    
                                    </select>
                                </div>
                                <div>
                                    <label for="fechaInicio" class="block text-sm font-medium text-gray-500 dark:text-light">Fecha Inicio:</label>
                                    <input type="date" min="2005-01-01" max="<?php echo date('Y-m-d');?>" name="fechaInicio" id="fechaInicio" autocomplete="fechaInicio" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                </div> 
                                <div>
                                    <label for="fechaFin" class="block text-sm font-medium text-gray-500 dark:text-light">Fecha Fin:</label>
                                    <input type="date" min="2005-01-01" max="<?php echo date('Y-m-d');?>" name="fechaFin" id="fechaFin" autocomplete="fechaFin" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                </div> 
                                                                                                
                            </div>
                            
                    
                </form>
            </div>
        </div>
    </div>
    <div class="preloader text-center" style="display: none;" ></div>
    <div style="display: none;" id="tarjeta" class="col-span-12 bg-white rounded-md dark:bg-darker">
        
            <div id="map" class="justify-center"></div>
        
    </div>
</div>


<script src="{{asset('js/date.format.js')}}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7hbeiRJZhuZ2l8ybIxVh_hUYB-yLUQnw&callback=initMap&libraries=visualization&v=weekly&channel=2"
    async type="text/javascript"
></script>



<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<?php
    }
?>

@include('layouts.footer')
@include('layouts.panels')

