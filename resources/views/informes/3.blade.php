@include('layouts.header')
@livewireStyles
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
@include('layouts.sidebar')
@include('layouts.navbar')
<style>
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

th:first-child, td:first-child
{
  position:sticky;
  left:0px;
  background-color:wheat;
  color:black;
  font-weight: bold;
  z-index: 1;  
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
            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Informes: Ventas Familia o Articulo Fecha </h4>
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
                        <div>
                            <label for="agrupacion" class="block text-sm font-medium text-gray-500 dark:text-light">Agrupación por:</label>
                            <select onchange="agrupacion2(this.value)" id="agrupacion" name="agrupacion" autocomplete="agrupacion" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                <option value="0"></option>
                                <?php
                                    if(session('tipo') == 5 ){
                                ?>
                                    <option value="1">Comisionista</option>
                                <?php
                                    }
                                ?>
                                <option value="2">Cliente</option>
                            </select>
                        </div>
                        <div>
                            <label for="fechaInicio" class="block text-sm font-medium text-gray-500 dark:text-light">Fecha Inicio:</label>
                            <input type="date" min="2005-01-01" max="<?php echo date('Y-m-d'); ?>" name="fechaInicio" id="fechaInicio" autocomplete="fechaInicio" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                        </div>
                        <div>
                            <label for="fechaFin" class="block text-sm font-medium text-gray-500 dark:text-light">Fecha Fin:</label>
                            <input type="date" min="2005-01-01" max="<?php echo date('Y-m-d'); ?>" name="fechaFin" id="fechaFin" autocomplete="fechaFin" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                        </div>
                        <div id="familias">
                            <?php
                            $familias = InformesController::familia();
                            ?>
                            <label for="familia" class="block text-sm font-medium text-gray-500 dark:text-light">Familia:</label>
                            <select id="familia" onchange="notArticulo()" name="familia" autocomplete="familia" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                <option value="0"></option>
                                @foreach($familias as $familia)
                                <option value="{{$familia->CodigoFamilia}}">{{$familia->CodigoFamilia}}-{{$familia->Descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="articulos">
                            <label for="articulo" class="block text-sm font-medium text-gray-500 dark:text-light">Articulo:</label>                            
                            <livewire:search-dropdownart :key="$randomKey"/>
                        </div>




                        <div style="display: none;" id="prescriptor">
                            <label for="prescriptores" class="block text-sm font-medium text-gray-500 dark:text-light">Comisionista:</label>                           
                            <livewire:search-dropdownpre :key="$randomKey"/>
                        </div>


                        <div style="display: none;" id="cliente">
                            <label for="clientes" class="block text-sm font-medium text-gray-500 dark:text-light">Cliente:</label>                            
                            <livewire:search-dropdowncli :key="$randomKey"/>
                        </div>

                        <div>
                            <label for="forma" class="block text-sm font-medium text-gray-500 dark:text-light">Tiempo:</label>
                            <select id="forma" name="forma" autocomplete="forma" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                <!-- <option value="0">Años</option> -->
                                <option value="1">Meses</option>
                                <option value="2">Semanas</option>
                                <option value="3">Días</option>
                            </select>
                        </div>

                        <input type="hidden" id="codigoOculto">
                        <input type="hidden" id="familiaOculto">
                        <input type="hidden" id="articuloOculto">

                    </div>
                </form>
            </div>            
        </div>
    </div>
    <div class="preloader text-center" style="display: none;"></div>
    <div style="display: none;" id="tarjeta" class="col-span-12 bg-white rounded-md dark:bg-darker">
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative" style="height: 405px;">

            <div id="informe">

            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/date.format.js')}}"></script>
<script src="{{asset('js/informes3.js')}}"></script>
<script>
    function selectCodigoPrescriptor(producto,comi){
        console.log(producto);        
        $('#codigoOculto').val(producto);
        $('#nombreOculto').val(comi);
        $('#prescriptorinput').val(producto+"-"+comi)       
        $(".prescriptorResultado-box").hide();        
    }
    
    function selectCodigoCliente(producto,cli){
        console.log(producto);      
        var pro  = '';
        if(producto.length == 1){
            pro = '00000' + producto;
        }else if(producto.length == 2){
            pro = '0000' + producto;
        }else if(producto.length == 3){
            pro = '000' + producto;
        }else if(producto.length == 4){
            pro = '00' + producto;
        }else if(producto.length == 5){
            pro = '0' + producto;
        }else{
            pro  = producto;
        }
        console.log(pro);
        $('#codigoOculto').val(pro);  
        //$('#codigoOculto').val(producto);
        $('#nombreOculto').val(cli);
        $('#clienteinput').val(producto+"-"+cli)       
        $(".clienteResultado-box").hide();        
    }

    function selectCodigoArticulo(producto,art){
        console.log(producto);        
        $('#articuloOculto').val(producto);
        $('#articulo').val(art);
        $('#articuloinput').val(producto+"-"+art)       
        $(".articuloResultado-box").hide();        
    }

    
</script>
<?php
    }
?>
@livewireScripts
@include('layouts.footer')
@include('layouts.panels')