<style>
    [x-cloak] {
        display: none;
    }

    [type="checkbox"] {
        box-sizing: border-box;
        padding: 0;
    }

    .form-checkbox {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        display: inline-block;
        vertical-align: middle;
        background-origin: border-box;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        flex-shrink: 0;
        color: currentColor;
        background-color: #fff;
        border-color: #e2e8f0;
        border-width: 1px;
        border-radius: 0.25rem;
        height: 1.2em;
        width: 1.2em;
    }

    .form-checkbox:checked {
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M5.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L7 8.586 5.707 7.293z'/%3e%3c/svg%3e");
        border-color: transparent;
        background-color: currentColor;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

<?php

use App\Http\Controllers\InfoController;

    $semanal=InfoController::semanalC($IdComisionista);
    $semanaAnterior=InfoController::semanaAnteriorC($IdComisionista);
    $mes=InfoController::mesC($IdComisionista);
    $mesAnterior=InfoController::mesAnteriorC($IdComisionista);
    $mesAnteriorAnio =InfoController::mesAnteriorAnioC($IdComisionista); 
    $anual = InfoController::anualC($IdComisionista);
    $anualAnterior = InfoController::anualAnteriorC($IdComisionista);
    $preescriptorMes = InfoController::preescriptorMesC($IdComisionista);
    $pedidos6Meses  = InfoController::pedidos6MesesC($IdComisionista);
    $ventasComisionista  = InfoController::ventasComisionistaC($IdComisionista); 
    $totalVentasComisionista  = InfoController::totalVentasComisionistaC($IdComisionista);
    $resumenAnual = InfoController::resumenAnualC($IdComisionista);
    $resumenAnual2 = InfoController::resumenAnual2C($IdComisionista);
    $resumenAnual3 = InfoController::resumenAnual3C($IdComisionista);

?>

<div class="mt-2">
    <!-- Charts -->
    <div class="grid grid-cols-1 p-1 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-1 border-b">
        <!-- Bar chart card -->
        <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false}">
            <!-- Card header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Resumen Anual</h4>                
            </div>
            <!-- Chart -->
            <div class="relative p-4 h-72">
                <canvas id="barChart{{$IdComisionista}}"></canvas>
            </div>        
        </div>        
    </div>

    <!-- State cards -->
    <div class="grid grid-cols-1 gap-2 p-2 lg:grid-cols-2 xl:grid-cols-2 ">
        <!-- Value card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                Ventas Semanales > 
            </h6>
            <span class="text-xl font-semibold text-gray-900 dark:text-light">{{number_format(round($semanal[0]->total, 2), 2, ',', '.') }}€</span>
            <br>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                {{($semanal[0]->total - $semanaAnterior[0]->total) % 100}}%
            </span>
            <span class="text-gray-900 dark:text-light">Anterior: {{number_format(round($semanaAnterior[0]->total, 2), 2, ',', '.') }}€</span>
            </div>
            <div>
            <span>
                <svg
                class="w-12 h-12 text-gray-300 dark:text-primary-dark animate-pulse"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                </svg>
            </span>
            </div>
        </div>
        <!-- Value card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6
                class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light"
            >
                Ventas Mensuales >
            </h6>
            <span class="text-xl font-semibold text-gray-900 dark:text-light">{{number_format(round($mes[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                {{($mes[0]->total - $mesAnterior[0]->total) % 100}}%
            </span>
            <span class="text-gray-900 dark:text-light">Mes Anterior: {{number_format(round($mesAnterior[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                {{($mes[0]->total - $mesAnteriorAnio[0]->total) % 100}}%
            </span>
            <span class="text-gray-900 dark:text-light" >Año Anterior: {{number_format(round($mesAnteriorAnio[0]->total ,2), 2, ',', '.')}}€</span>
            </div>
            <div>
            <span>
                <svg
                class="w-12 h-12 text-gray-300 dark:text-primary-dark animate-pulse"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                </svg>
            </span>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6
                class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light"
            >
                Ventas Anuales >
            </h6>
            <span class="text-xl font-semibold text-gray-900 dark:text-light">{{number_format(round($anual[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                {{($anual[0]->total - $anualAnterior[0]->total) % 100}}%
            </span>
            <span class="text-gray-900 dark:text-light">Anterior: {{number_format(round($anualAnterior[0]->total ,2), 2, ',', '.')}}€</span>
            </div>
            <div>
            <span>
                <svg
                class="w-12 h-12 text-gray-300 dark:text-primary-dark animate-pulse"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                </svg>
            </span>
            </div>
        </div>        

    </div>

    <div class="flex flex-row grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-2">

        <div class="flex flex-col">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                    <?php
                        $fecha_actual = date("Y-m");
                    ?>

                    <div class="container mx-auto py-6 px-4">


                        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                            style="height: 100px;">
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">                                                            
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Comisionista
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 5 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 4 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 3 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 2 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 1 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            <?php echo date("m-Y",strtotime($fecha_actual."- 0 month"));?>
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Total
                                        </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($preescriptorMes as $tabla)
                                <tr>                                                                
                                    <td class="border-dashed border-t border-gray-200">
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->RazonSocial}}  </span>
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">
                                        @if($tabla->primero != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->primero,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->primero = 0}}</span>
                                        @endif
                                        
                                    </td>                                                        
                                    <td class="border-dashed border-t border-gray-200">
                                        @if($tabla->segundo != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->segundo,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->segundo = 0}}</span>
                                        @endif
                                        
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">
                                        
                                        @if($tabla->tercero != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->tercero,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->tercero = 0}}</span>
                                        @endif
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">

                                        @if($tabla->cuarto != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->cuarto,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->cuarto = 0}}</span>
                                        @endif
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">

                                        @if($tabla->quinto != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->quinto,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->quinto = 0}}</span>
                                        @endif
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">

                                        @if($tabla->sexto != null)
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla->sexto,2), 2, ',', '.')}}</span>
                                        @else
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla->sexto = 0}}</span>
                                        @endif
                                    </td>
                                    <td class="border-dashed border-t border-gray-200">
                                        <?php 
                                            $total = $tabla->primero + $tabla->segundo + $tabla->tercero + $tabla->cuarto +$tabla->quinto + $tabla->sexto;
                                        ?>
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format($total, 2, ',', '.')}}€</span>                                                                   
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        
        <div class="flex flex-col mb-5">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                    <div class="container mx-auto py-6 px-4">

                        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                            style="height: 100px;">
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Comisionista
                                        </th>                                                            
                                        @foreach(array_reverse($ventasComisionista) as $cabecera)
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            {{$cabecera->Periodo}}-{{$cabecera->EjercicioAlbaran}}
                                            {{--{{$cabecera->Periodo}}-{{$cabecera->EjercicioFactura}}--}}
                                        </th>
                                        @endforeach
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Total
                                        </th>                                                                                                  
                                    </tr>
                                </thead>
                                <tbody>
                            
                                <tr>
                                    <td class="border-dashed border-t border-gray-200">

                                    </td>
                                @foreach($ventasComisionista as $tabla3)                                                                                                            
                                    <td class="border-dashed border-t border-gray-200"> 
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla3->TotalMes,2), 2, ',', '.')}}€</span>
                                    </td>
                                @endforeach                                                        
                                    <td class="border-dashed border-t border-gray-200">
                                        <span class="text-gray-700 px-6 py-3 flex items-center">
                                            <?php
                                            echo number_format(round($totalVentasComisionista[0]->TotalMes,2), 2, ',', '.').'€';
                                            ?>
                                        </span>
                                    </td>                                               
                                </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                    <div class="container mx-auto py-6 px-4">

                        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                            style="height: 350px;">
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">                                                            
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Fecha
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Total
                                        </th>
                                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Cantidad
                                        </th>                                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pedidos6Meses as $tabla2)
                                <tr>                                                                
                                    <td class="border-dashed border-t border-gray-200">
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{date('d-m-Y', strtotime($tabla2->FechaAlbaran))}}</span>
                                        <!-- <span class="text-gray-700 px-6 py-3 flex items-center">{{--{{date('d-m-Y', strtotime($tabla2->FechaFactura))}}--}}</span> -->
                                    </td>
                                    <td class="border-dashed border-t border-gray-200"> 
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla2->Total,2), 2, ',', '.')}}€</span>
                                    </td>                                                        
                                    <td class="border-dashed border-t border-gray-200">
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla2->Cantidad}}</span>
                                    </td>                                               
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
     


    <?php
        //datos de la grafica
        
            $datos = array();
            foreach($resumenAnual[0] as $meses){
                if($meses == null){
                    $meses = 0;

                }
                array_push($datos,$meses);
            }
           

        
            $datos2 = array();
            foreach($resumenAnual2[0] as $meses){
                if($meses == null){
                    $meses = 0;

                }
                array_push($datos2,$meses);
            }
        

        
            $datos3 = array();
            foreach($resumenAnual3[0] as $meses){
                if($meses == null){
                    $meses = 0;

                }
                array_push($datos3,$meses);
            }
        
    ?>

</div>

<script>

    var cod = @json($IdComisionista);
 
    var datosGrafica = @json($datos);    

    var datosGrafica2 = @json($datos2);

    var datosGrafica3 = @json($datos3);

    
   

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<script src="{{asset('js/graficaCli.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@1.1.0/dist/spruce.umd.js"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js"></script>