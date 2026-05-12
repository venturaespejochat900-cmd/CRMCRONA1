@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')
<?php
    if(session('codigoComisionista') == 0){  
        header("Location: https://cronadis.abmscloud.com/");
        exit();
    }else{
?>
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



    <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
    </div>


<div class="mt-2">
    <!-- Charts -->
    <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-4 border-b">
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
    <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4 ">
        <!-- Value card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                    Ventas Semanales > 
                </h6>
                <span class="text-xl font-semibold">{{number_format(round($semanal[0]->total, 2), 2, ',', '.') }}€</span>
                <br>
                <?php  
                    if($semanaAnterior[0]->total > $semanal[0]->total){
                ?>
                <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
                <?php
                    }else{
                ?>
                <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                <?php
                    }
                    if ($semanaAnterior[0]->total==0 && $semanal[0]->total==0){
                        echo "0%";
                    }else{
                        if($semanaAnterior[0]->total == 0){
                            echo round( $semanal[0]->total * 100,2) . "%";
                        }else{
                            echo round((($semanaAnterior[0]->total - $semanal[0]->total) / $semanaAnterior[0]->total) * 100,2) ."%";
                        }
                        //
                    }
                    ?>     
                       {{-- {{round((($semanaAnterior[0]->total - $semanal[0]->total) / $semanaAnterior[0]->total) * 100,2)}}% --}}
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
                if($mesAnterior[0]->total > $mes[0]->total){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md">-
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($mesAnterior[0]->total  == 0 && $mes[0]->total == 0) {
                    echo "0%";
                } else{
                    if($mesAnteriorAnio[0]->total == 0){
                        echo round( $mes[0]->total * 100,2) . "%";
                    }else{
                    	if($mesAnterior[0]->total  == 0){
                        	echo round(($mesAnterior[0]->total - $mes[0]->total),2) . "%";
                        }else{
                        	echo round(($mesAnterior[0]->total - $mes[0]->total) / $mesAnterior[0]->total * 100,2) . "%";
                        }
                        
                    }
                   
                }
            ?>  
                {{-- {{round(($mesAnterior[0]->total - $mes[0]->total) / $mesAnterior[0]->total * 100,2)}}% --}}
            </span>
            <span>Mes Anterior: {{number_format(round($mesAnterior[0]->total ,2), 2, ',', '.')}}€</span>
            <br>
            <?php  
                if($mesAnteriorAnio[0]->total > $mes[0]->total){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($mesAnteriorAnio[0]->total  == 0 && $mes[0]->total == 0) {
                    
                    echo "0%";
                } else{
                    if($mesAnteriorAnio[0]->total == 0){
                        echo round( $mes[0]->total * 100,2) . "%";
                    }else{
                        echo round(($mesAnteriorAnio[0]->total - $mes[0]->total) / $mesAnteriorAnio[0]->total * 100,2) . "%";
                    }
                    //
                }
               ?>  
                   {{-- {{round(($mesAnteriorAnio[0]->total - $mes[0]->total) / $mesAnteriorAnio[0]->total * 100,2)}}% --}}
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
                    if($anualAnterior[0]->total > $anual[0]->total){
                ?>
                <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
                <?php
                    }else{
                ?>
                <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
                <?php
                    }
                    if($anualAnterior[0]->total  == 0 && $anual[0]->total == 0) {
                        echo "0%";
                    } else{
                        if($anualAnterior[0]->total == 0){
                            echo round($anual[0]->total * 100,2) . "%";
                        }else{
                            echo round(($anualAnterior[0]->total - $anual[0]->total) / $anualAnterior[0]->total * 100,2) . "%";
                        }
                        //
                    }
                    ?>  
                        {{-- {{round(($anualAnterior[0]->total - $anual[0]->total) / $anualAnterior[0]->total * 100,2)}}% --}}
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

        <!-- Users card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                Clientes Activos >
            </h6>
            <span class="text-xl font-semibold">{{$clientesActivos[0]->activos}}</span>
            <br>
            <?php  
                if($clientesActivosMesAnterior[0]->activos > $clientesActivos[0]->activos){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($clientesActivosMesAnterior[0]->activos  == 0 && $clientesActivos[0]->activos == 0) {
                        echo "0%";
                    } else{
                        if($clientesActivosMesAnterior[0]->activos == 0){
                            echo round($clientesActivos[0]->activos * 100,2) . "%";
                        }else{
                            echo round(($clientesActivosMesAnterior[0]->activos - $clientesActivos[0]->activos) / $clientesActivosMesAnterior[0]->activos * 100,2) . "%";
                        }
                        //echo round(($clientesActivosMesAnterior[0]->activos - $clientesActivos[0]->activos) / $clientesActivosMesAnterior[0]->activos * 100,2) . "%";
                    }
                ?>  
                    {{-- {{round(($clientesActivosMesAnterior[0]->activos - $clientesActivos[0]->activos) / $clientesActivosMesAnterior[0]->activos * 100,2)}}% --}}
            </span>
            <span>Mes Anterior: {{$clientesActivosMesAnterior[0]->activos}}</span>
            <br>
            <?php  
                if($clientesActivosAnioAnterior[0]->activos > $clientesActivos[0]->activos){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($clientesActivosAnioAnterior[0]->activos  == 0 && $clientesActivos[0]->activos == 0) {
                        echo "0%";
                    } else{
                        if($clientesActivosAnioAnterior[0]->activos == 0){
                            echo round($clientesActivos[0]->activos * 100,2) . "%";
                        }else{
                            echo round(($clientesActivosAnioAnterior[0]->activos - $clientesActivos[0]->activos) / $clientesActivosAnioAnterior[0]->activos * 100,2) . "%";
                        }
                        //
                    }
                ?>  
                    {{-- {{round(($clientesActivosAnioAnterior[0]->activos - $clientesActivos[0]->activos) / $clientesActivosAnioAnterior[0]->activos * 100,2)}}% --}}
            </span>
            <span>Año Anterior: {{$clientesActivosAnioAnterior[0]->activos}}</span>
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
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                />
                </svg>
            </span>
            </div>
        </div>

        <!-- Users card -->
        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
            <div>
            <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                Clientes Nuevos >
            </h6>
            <span class="text-xl font-semibold">{{$nuevosClientes[0]->usuarios}}</span>
            <br>
            <?php  
                if($nuevosClientesMesAnterior[0]->usuarios > $nuevosClientes[0]->usuarios){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                // if($nuevosClientesMesAnterior[0]->usuarios == null) $nuevosClientesMesAnterior = 0;
                //$nuevosClientesMesAnterior = $nuevosClientes[0]->usuarios
                if($nuevosClientesMesAnterior[0]->usuarios == 0 && $nuevosClientes[0]->usuarios == 0) {
                            
                    echo "0%";
                } else{
                    if($nuevosClientesMesAnterior[0]->usuarios == 0){
                        echo round($nuevosClientes[0]->usuarios * 100,2) . "%";
                    }else{
                        echo round((($nuevosClientesMesAnterior[0]->usuarios - $nuevosClientes[0]->usuarios) / $nuevosClientesMesAnterior[0]->usuarios) * 100,2) . "%";
                    }
                    
                }
               
               
               
            ?>  
               
                {{-- anterior {{round(($nuevosClientesMesAnterior[0]->usuarios - $nuevosClientes[0]->usuarios) / $nuevosClientesMesAnterior[0]->usuarios * 100,2)}}% --}}
                
            </span>
            <span>Mes Anterior: {{$nuevosClientesMesAnterior[0]->usuarios}}</span>
            <br>
            <?php  
                if($nuevosClietesAnioAnterior[0]->usuarios > $nuevosClientes[0]->usuarios){
            ?>
            <span class="inline-block px-2 py-px text-xs text-red-500 bg-red-100 rounded-md"> -
            <?php
                }else{
            ?>
            <span class="inline-block px-2 py-px text-xs text-green-500 bg-green-100 rounded-md">
            <?php
                }
                if($nuevosClietesAnioAnterior[0]->usuarios == 0 && $nuevosClientes[0]->usuarios == 0) {
                            
                    echo "0%";
                } else{
                    
                    if($nuevosClietesAnioAnterior[0]->usuarios == 0){
                        echo round($nuevosClientes[0]->usuarios * 100,2) . "%";
                    }else{
                        echo round((($nuevosClietesAnioAnterior[0]->usuarios - $nuevosClientes[0]->usuarios) / $nuevosClietesAnioAnterior[0]->usuarios) * 100,2) . "%";
                    }
                    
                }
            ?>  
                {{-- anterior {{round(($nuevosClietesAnioAnterior[0]->usuarios - $nuevosClientes[0]->usuarios) / $nuevosClietesAnioAnterior[0]->usuarios * 100,0)}}% --}}
            </span>
            <span>Año Anterior: {{$nuevosClietesAnioAnterior[0]->usuarios}} </span>
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
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                />
                </svg>
            </span>
            </div>
        </div>

    </div>

    <div class="flex flex-row grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-2">

        <div class="flex flex-col">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div>
                    <!-- <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                        Preescriptor y Mes >
                    </h6> -->
                        <div x-data="accordion(1)">
                            <div @click="handleClick()" class="flex flex-row justify-between items-center font-semibold p-3 cursor-pointer">
                                <span class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">Cliente y Mes</span>
                                <svg
                                :class="handleRotate()"
                                class="fill-current h-6 w-6 transform transition-transform duration-500 dark:text-primary-light float-right"
                                viewBox="0 0 20 20"
                                >
                                <path d="M13.962,8.885l-3.736,3.739c-0.086,0.086-0.201,0.13-0.314,0.13S9.686,12.71,9.6,12.624l-3.562-3.56C5.863,8.892,5.863,8.611,6.036,8.438c0.175-0.173,0.454-0.173,0.626,0l3.25,3.247l3.426-3.424c0.173-0.172,0.451-0.172,0.624,0C14.137,8.434,14.137,8.712,13.962,8.885 M18.406,10c0,4.644-3.763,8.406-8.406,8.406S1.594,14.644,1.594,10S5.356,1.594,10,1.594S18.406,5.356,18.406,10 M17.521,10c0-4.148-3.373-7.521-7.521-7.521c-4.148,0-7.521,3.374-7.521,7.521c0,4.147,3.374,7.521,7.521,7.521C14.148,17.521,17.521,14.147,17.521,10"></path>
                                </svg>
                            </div>
                            <div x-ref="tab" :style="handleToggle()" class="border-l-2 dark:border-primary-light overflow-hidden max-h-0 duration-500 transition-all">

                                <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                                        <?php
                                            $fecha_actual = date("Y-m");
                                        ?>

                                        <div class="container mx-auto py-6 px-4">


                                            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                                                style="height: 405px;">
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

                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div>
                    <div x-data="accordion(2)">
                        <div @click="handleClick()" class="flex flex-row justify-between items-center font-semibold p-3 cursor-pointer">
                            <span class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">Pedidos Diarios Últimos 6 Meses</span>
                            <svg
                            :class="handleRotate()"
                            class="fill-current h-6 w-6 transform transition-transform duration-500 dark:text-primary-light float-right"
                            viewBox="0 0 20 20"
                            >
                            <path d="M13.962,8.885l-3.736,3.739c-0.086,0.086-0.201,0.13-0.314,0.13S9.686,12.71,9.6,12.624l-3.562-3.56C5.863,8.892,5.863,8.611,6.036,8.438c0.175-0.173,0.454-0.173,0.626,0l3.25,3.247l3.426-3.424c0.173-0.172,0.451-0.172,0.624,0C14.137,8.434,14.137,8.712,13.962,8.885 M18.406,10c0,4.644-3.763,8.406-8.406,8.406S1.594,14.644,1.594,10S5.356,1.594,10,1.594S18.406,5.356,18.406,10 M17.521,10c0-4.148-3.373-7.521-7.521-7.521c-4.148,0-7.521,3.374-7.521,7.521c0,4.147,3.374,7.521,7.521,7.521C14.148,17.521,17.521,14.147,17.521,10"></path>
                            </svg>
                        </div>
                        <div
                            x-ref="tab"
                            :style="handleToggle()"
                            class="border-l-2 dark:border-primary-light overflow-hidden max-h-0 duration-500 transition-all"
                        >
                            
                            <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                                <div class="container mx-auto py-6 px-4">

                                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                                        style="height: 405px;">
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
                                                    <span class="text-gray-700 px-6 py-3 flex items-center">{{date('d-m-Y', strtotime($tabla2->FechaFactura))}}</span>
                                                    {{-- <span class="text-gray-700 px-6 py-3 flex items-center">{{date('d-m-Y', strtotime($tabla2->FechaFactura))}}</span>  --}}
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
            </div>
        </div>
        <div class="flex flex-col mb-5">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div>
                    <div x-data="accordion(3)">
                        <div @click="handleClick()" class="flex flex-row justify-between items-center font-semibold p-3 cursor-pointer">
                            <span class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">Clientes Activos Últimos 6 Meses</span>
                            <svg
                            :class="handleRotate()"
                            class="fill-current h-6 w-6 transform transition-transform duration-500 dark:text-primary-light float-right"
                            viewBox="0 0 20 20"
                            >
                            <path d="M13.962,8.885l-3.736,3.739c-0.086,0.086-0.201,0.13-0.314,0.13S9.686,12.71,9.6,12.624l-3.562-3.56C5.863,8.892,5.863,8.611,6.036,8.438c0.175-0.173,0.454-0.173,0.626,0l3.25,3.247l3.426-3.424c0.173-0.172,0.451-0.172,0.624,0C14.137,8.434,14.137,8.712,13.962,8.885 M18.406,10c0,4.644-3.763,8.406-8.406,8.406S1.594,14.644,1.594,10S5.356,1.594,10,1.594S18.406,5.356,18.406,10 M17.521,10c0-4.148-3.373-7.521-7.521-7.521c-4.148,0-7.521,3.374-7.521,7.521c0,4.147,3.374,7.521,7.521,7.521C14.148,17.521,17.521,14.147,17.521,10"></path>
                            </svg>
                        </div>
                        
                        <div
                            x-ref="tab"
                            :style="handleToggle()"
                            class="border-l-2 dark:border-primary-light overflow-hidden max-h-0 duration-500 transition-all"
                        >
                            <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">
                                
                                <div class="container mx-auto py-6 px-4">
                                    Total:
                                    <span class="inline-block px-2 py-px ml-2 text-xs text-gray-500 bg-gray-100 rounded-md mb-3">
                                                        {{$tablaClientesCount[0]->total}}
                                    </span>
                                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                                        style="height: 405px;">                                        
                                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                            <thead>
                                                <tr class="text-left">                                                            
                                                    <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                                        Codigo Cliente
                                                    </th>
                                                    <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                                        Nombre
                                                    </th>                                                                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tablaClientes as $tabla3)
                                            <tr>                                                                
                                                <td class="border-dashed border-t border-gray-200">
                                                    <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla3->CodigoCliente}}</span>
                                                    {{-- <span class="text-gray-700 px-6 py-3 flex items-center">{{date('d-m-Y', strtotime($tabla2->FechaFactura))}}</span> --}}
                                                </td>
                                                <td class="border-dashed border-t border-gray-200"> 
                                                    <span class="text-gray-700 px-6 py-3 flex items-center">{{$tabla3->RazonSocial}}</span>
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
            </div>
        </div>
        <div class="flex flex-col mb-5">
            <div class="p-4 bg-white rounded-md dark:bg-darker">
                <div>
                    <div x-data="accordion(4)">
                        <div @click="handleClick()" class="flex flex-row justify-between items-center font-semibold p-3 cursor-pointer">
                            <span class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">Últimos 6 Meses Facturado Comercial</span>
                            <svg
                            :class="handleRotate()"
                            class="fill-current h-6 w-6 transform transition-transform duration-500 dark:text-primary-light float-right"
                            viewBox="0 0 20 20"
                            >
                            <path d="M13.962,8.885l-3.736,3.739c-0.086,0.086-0.201,0.13-0.314,0.13S9.686,12.71,9.6,12.624l-3.562-3.56C5.863,8.892,5.863,8.611,6.036,8.438c0.175-0.173,0.454-0.173,0.626,0l3.25,3.247l3.426-3.424c0.173-0.172,0.451-0.172,0.624,0C14.137,8.434,14.137,8.712,13.962,8.885 M18.406,10c0,4.644-3.763,8.406-8.406,8.406S1.594,14.644,1.594,10S5.356,1.594,10,1.594S18.406,5.356,18.406,10 M17.521,10c0-4.148-3.373-7.521-7.521-7.521c-4.148,0-7.521,3.374-7.521,7.521c0,4.147,3.374,7.521,7.521,7.521C14.148,17.521,17.521,14.147,17.521,10"></path>
                            </svg>
                        </div>
                        <div
                            x-ref="tab"
                            :style="handleToggle()"
                            class="border-l-2 dark:border-primary-light overflow-hidden max-h-0 duration-500 transition-all"
                        >
                        <div class="px-3 my-2 flex sm:flex-row flex-col justify-between">

                            <div class="container mx-auto py-6 px-4">

                                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
                                    style="height: 100px;">
                                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                        <thead>
                                            <tr class="text-left">
                                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                                    Comercial
                                                </th>                                                            
                                                @foreach(array_reverse($ventasComisionista) as $cabecera)
                                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                                    {{$cabecera->Periodo}}-{{$cabecera->EjercicioAlbaran}}
                                                    {{-- {{$cabecera->Periodo}}-{{$cabecera->EjercicioFactura}} --}}
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
                                                <span class="text-gray-700 px-6 py-3 flex items-center"> {{session('codigoComisionista')}} </span>
                                            </td>
                                        @foreach(array_reverse($ventasComisionista) as $tabla3)                                                                                                            
                                            <td class="border-dashed border-t border-gray-200"> 
                                                <span class="text-gray-700 px-6 py-3 flex items-center">{{number_format(round($tabla3->TotalMes,2), 2, ',', '.')}}€</span> 
                                            </td>
                                        @endforeach                                                         
                                            <td class="border-dashed border-t border-gray-200">
                                                <span class="text-gray-700 px-6 py-3 flex items-center">
                                                    <?php
                                                        $suma = 0;
                                                        foreach($totalVentasComisionista as $total){
                                                            $suma += $total->TotalMes;
                                                        }
                                                       echo number_format(round($suma,2), 2, ',', '.').'€';
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
            </div>
        </div>
    </div>
    <div class="flex flex-row grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-2 mb-5">

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
 
    var datosGrafica = @json($datos);    

    var datosGrafica2 = @json($datos2);

    var datosGrafica3 = @json($datos3);

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@1.1.0/dist/spruce.umd.js"></script>


<script src="{{asset('js/grafica.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js"></script>
<script>
Spruce.store('accordion', {
    tab: 0,
});

const accordion = (idx) => ({
    handleClick() {
    this.$store.accordion.tab = this.$store.accordion.tab === idx ? 0 : idx;
    },
    handleRotate() {
    return this.$store.accordion.tab === idx ? 'rotate-180' : '';
    },
    handleToggle() {
    return this.$store.accordion.tab === idx ? `max-height: ${this.$refs.tab.scrollHeight}px` : '';
    }
});


</script>
<?php 
    }
?>
@include('layouts.footer')
@include('layouts.panels')