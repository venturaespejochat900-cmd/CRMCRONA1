

 <?php
    use App\Http\Controllers\InfoController;

    $semanal=InfoController::semanalC($IdComisionista);
    $semanaAnterior=InfoController::semanaAnteriorC($IdComisionista);
    $mes=InfoController::mesC($IdComisionista);
    $mesAnterior=InfoController::mesAnteriorC($IdComisionista);
    $mesAnteriorAnio =InfoController::mesAnteriorAnioC($IdComisionista); 
    $anual = InfoController::anualC($IdComisionista);
    $anualAnterior = InfoController::anualAnteriorC($IdComisionista);
    //$tasaAnualMedia = InfoController::tasaMediaAnualc($IdComisionista);
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
    <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-1 border-b">
        <!-- Bar chart card -->
        <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false, isBar: false}">
            <!-- Card header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Resumen Anual</h4>

                <!-- <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-light">Barras</span>
                    <button class="relative focus:outline-none" x-cloak @click="isBar = !isBar; $parent.updateBarChart(isOn)" >
                        <div class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker"></div>
                        <div class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                        :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isBar, 'translate-x-6 bg-primary-light dark:bg-primary': isBar }"></div>
                    </button>
                    <span class="text-sm text-gray-500 dark:text-light">Lineas</span>
                </div> -->
               
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-light">Años Anteriores</span>
                    <button class="relative focus:outline-none" x-cloak @click="isOn = !isOn; $parent.updateBarChart(isOn)">
                        <div class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker"></div>
                        <div class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                        :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isOn, 'translate-x-6 bg-primary-light dark:bg-primary': isOn }"></div>
                    </button>
                </div>
            </div>
            <!-- Chart -->
            <div class="relative p-4 h-72">
                <canvas id="barChart"></canvas>
            </div>        
        </div>        
    </div>

    <!-- State cards -->
    <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4 md:grid-cols-2 sm:grid-cols-2">
        <!-- Value card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                Ventas Semanales > 
            </h6>
            <span class="text-xl font-semibold">{{number_format(round($semanal[0]->total, 2), 2, ',', '.') }}€</span>
            <br>
            <?php  
                if($semanaAnterior[0]->total > $semanal[0]->total || $semanaAnterior[0]->total == 0){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($semanaAnterior[0]->total == 0 ){
                    echo '100%';
                }else{
            ?>     
                {{round((($semanaAnterior[0]->total - $semanal[0]->total) / $semanaAnterior[0]->total) * 100,2)}}%                
            <?php
                }
            ?>    
            </span>
            <span>Anterior: {{number_format(round($semanaAnterior[0]->total, 2), 2, ',', '.') }}€</span>
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
            <span class="text-xl font-semibold">{{number_format(round($mes[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <?php  
                if($mesAnterior[0]->total > $mes[0]->total || $mesAnterior[0]->total == 0){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($mesAnterior[0]->total == 0 ){
                    echo '100%';
                }else{
            ?>     
                {{round((($mesAnterior[0]->total - $mes[0]->total) / $mesAnterior[0]->total) * 100,2)}}%                 
            <?php
                }
            ?>    
            </span>
            <span>Mes Anterior: {{number_format(round($mesAnterior[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <?php  
                if($mesAnteriorAnio[0]->total > $mes[0]->total || $mesAnteriorAnio[0]->total == 0 ){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($mesAnteriorAnio[0]->total == 0 ){
                    echo '100%';
                }else{
            ?>     
                {{round((($mesAnteriorAnio[0]->total - $mes[0]->total) / $mesAnteriorAnio[0]->total) * 100,2)}}%   
            <?php
                }
            ?>                 
            </span>
            <span>Año Anterior: {{number_format(round($mesAnteriorAnio[0]->total ,2), 2, ',', '.')}}€</span>
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
                <span class="text-xl font-semibold">{{number_format(round($anual[0]->total ,2), 2, ',', '.')}}€</span>
                <br>
                <?php  
                    if($anualAnterior[0]->total > $anual[0]->total || $anualAnterior[0]->total == 0){
                ?>
                <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
                <?php
                    }else{
                ?>
                <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                <?php
                    }
                    if($anualAnterior[0]->total == 0 ){
                        echo '100%';
                    }else{
                ?>     
                    {{round((($anualAnterior[0]->total - $anual[0]->total) / $anualAnterior[0]->total) * 100,2)}}%  
                <?php
                    }
                ?>
                </span>
                <span>Anterior: {{number_format(round($anualAnterior[0]->total ,2), 2, ',', '.')}}€</span>
               
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

        <div></div>

    </div>


    <div class="flex flex-row grid grid-cols-1 gap-8 p-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2">

        <div class="flex flex-col">        
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <h3 class="mb-2 pb-2">Ventas Clientes Mes</h3>
                <?php
                    $fecha_actual = date("Y-m");
                ?>
                
                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                    style="height: 150px;">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">                                                            
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Cliente
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
        <div class="flex flex-col">
            <div class="p-4 bg-white rounded-md dark:bg-darker"> 
                    <h3 class="mb-2 pb-2">Pedidos 6 Meses</h3>
                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                        style="height: 150px;">
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
                                        Cantidad Pedidos
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
    <div class="flex flex-row grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-2 mb-5">

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

<script>
 
    var datosGrafica = @json($datos);    

    var datosGrafica2 = @json($datos2);

    var datosGrafica3 = @json($datos3);

</script>


