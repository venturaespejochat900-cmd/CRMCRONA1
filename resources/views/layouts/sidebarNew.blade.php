</head>
    <body>
        <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
            <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
            <!-- Loading screen -->
            <div x-ref="loading" class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker">
                Car gando CRONADIS.....
            </div>

        <!-- Sidebar -->
        <div x-show="abrirsidebar"
            class="flex overflow-x-hidden h-screen">
            <aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block">
                <div class="flex flex-col h-full">
                    <!-- Sidebar links -->
                    <nav class="flex flex-col h-screen px-4 tex-gray-900 border dark:bg-darker dark:border-primary-darker">                
                        <div class="flex flex-wrap mt-4">
                            <div class="w-1/4">                        
                                <button                      
                                class="mt-2 p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
                                >
                                    <a href="{{route('clientes')}}">  
                                        <span class="sr-only">Atras</span>
                                        <svg
                                            class="w-10 h-10"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19,11H7.14l3.63-4.36A1,1,0,1,0,9.23,5.36l-5,6a1.19,1.19,0,0,0-.09.15c0,.05,0,.08-.07.13A1,1,0,0,0,4,12H4a1,1,0,0,0,.07.36c0,.05,0,.08.07.13a1.19,1.19,0,0,0,.09.15l5,6A1,1,0,0,0,10,19a1,1,0,0,0,.64-.23,1,1,0,0,0,.13-1.41L7.14,13H19a1,1,0,0,0,0-2Z"
                                            />                                
                                        </svg>
                                    </a>
                                </button>
                            </div>                    
                            <div class="w-3/4">
                                <img src="{{asset('media/images/sur.png')}}" class="mx-auto w-20 h-20 rounded-full">
                            </div>
                            <div class="w-1/4"></div>
                            <div class="w-3/4">                                                                                    
                                <span class="font-semibold text-gray-600">
                                    <?php
                                        use App\Http\Controllers\ClienteController;
                                        $cliente = ClienteController::clientes($IdCliente);
                                        echo $cliente;
                                    ?>                                               
                                </span>                                       
                            </div>                     
                        </div>
                        <div class="mt-10 mb-4">
                            <ul class="ml-4">
                    
                                <li data-select="info" id="info" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 bg-gray-300 text-black font-bold dark:text-gray-100 flex flex-row border-gray-300 hover:text-black hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 15 3 C 8.373 3 3 8.373 3 16 c 0 6.627 5.373 12 12 12 s 12 -5.373 12 -12 C 27 8.373 21.627 3 15 3 z h 2 z z M 4 17 l 3.5 -4.5 l 3.5 7.5 L 16 5 l 3 10 L 21 9 L 23 9 L 19 21 L 16 10 L 11 24 L 7.5 16 L 6 18 z" clip-rule="evenodd"></path></svg>                
                                    </span>            
                                    <a>
                                        <span class="ml-2">Info</span>
                                    </a>
                                </li>
                                <li data-select="editar" id="editar" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>              
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Editar</span>
                                    </a>
                                </li>
                                <li data-select="oferta" id="oferta" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
                                    </span>
                                    <a>               
                                        <span class="ml-2">Ofertas</span>
                                    </a>
                                </li>
                                <li  data-select="pedido" id="pedido" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Pedidos</span>
                                    </a>
                                </li>
                                <li  data-select="articulo" id="articulo" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Articulos Pedidos</span>
                                    </a>
                                </li>
                                <li  data-select="cobro" id="cobro" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 4 2 C 3.448 2 3 2.448 3 3 L 3 24 C 3 26.209 4.791 28 7 28 L 22 28 L 23 28 L 24 28 C 26.209 28 28 26.209 28 24 L 28 10 C 28 9.448 27.552 9 27 9 L 25 9 L 25 24 C 25 24.553 24.552 25 24 25 C 23.448 25 23 24.553 23 24 L 23 3 C 23 2.448 22.552 2 22 2 L 4 2 z M 6 6 L 20 6 L 20 9 L 6 9 L 6 6 z M 6 13 L 12 13 L 12 15 L 6 15 L 6 13 z M 14 13 L 20 13 L 20 15 L 14 15 L 14 13 z M 6 17 L 12 17 L 12 19 L 6 19 L 6 17 z M 14 17 L 20 17 L 20 19 L 14 19 L 14 17 z M 6 21 L 12 21 L 12 23 L 6 23 L 6 21 z M 14 21 L 20 21 L 20 23 L 14 23 L 14 21 z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Cobros</span>
                                    </a>
                                </li>
                                <li data-select="tarifa" id="tarifa" href="javascript:void(0)" class="hidden h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,21h-8c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h8c0.552,0,1,0.448,1,1 C19,20.552,18.552,21,18,21z M20,17H10c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1C21,16.552,20.552,17,20,17 z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Tarifas</span>
                                    </a>
                                </li>   
                                <li data-select="incidencia" id="incidencia" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M22,4H8C5.791,4,4,5.791,4,8v14c0,2.209,1.791,4,4,4h14c2.209,0,4-1.791,4-4V8C26,5.791,24.209,4,22,4z M6.293,10.293l4-4	c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-4,4C7.512,11.902,7.256,12,7,12s-0.512-0.098-0.707-0.293	C5.902,11.316,5.902,10.684,6.293,10.293z M6.293,16.293l10-10c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-10,10	C7.512,17.902,7.256,18,7,18s-0.512-0.098-0.707-0.293C5.902,17.316,5.902,16.684,6.293,16.293z M22,24c-1.105,0-2-0.895-2-2	c0-1.105,0.895-2,2-2s2,0.895,2,2C24,23.105,23.105,24,22,24z M23.707,13.707l-10,10C13.512,23.902,13.256,24,13,24	s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l10-10c0.391-0.391,1.023-0.391,1.414,0S24.098,13.316,23.707,13.707z M23.707,7.707l-16,16C7.512,23.902,7.256,24,7,24s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l16-16	c0.391-0.391,1.023-0.391,1.414,0S24.098,7.316,23.707,7.707z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Incidencias</span>
                                    </a>
                                </li>
                                <li data-select="albaran" id="albaran" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" /><path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" /></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Albaranes</span>
                                    </a>
                                </li>
                                
                                <li data-select="pedDomi" id="pedDomi" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-home">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                            <path d="M5 12v8a1 1 0 0 0 1 1h3v-5h4v5h3a1 1 0 0 0 1 -1v-8" />
                                            <line x1="10" y1="12" x2="14" y2="12" />
                                        </svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Pedidos Domicilio</span>
                                    </a>
                                </li>
                                <li data-select="hacerpedido" id="hacerpedido" class="h-10 mb-2 px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">                            
                                    <a href="{{route('redirigirInicio2',['cod'=>$CodigoCliente])}}" target="_blank">
                                        <span class="ml-2">Pedido&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </a>
                                </li>
                                <li data-select="haceroferta" id="haceroferta" class="h-10 mb-2 px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">                            
                                    <a href="{{route('redirigirInicioOferta',['cod'=>$CodigoCliente])}}" target="_blank">
                                        <span class="ml-2">Ofertas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                    </nav>

                    <!-- Sidebar footer -->
                    <div class="flex-shrink-0 px-2 py-4 space-y-2">
                    <button
                        @click="openSettingsPanel"
                        type="button"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark"
                    >
                        <span aria-hidden="true">
                        <svg
                            class="w-4 h-4 mr-2"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                            />
                        </svg>
                        </span>
                        <span>Customizar</span>
                    </button>
                    </div>
                </div>
            </aside>
        </div>
<script>
    document.getElementById("info").onclick = function() {showTab(this)};
    document.getElementById("editar").onclick = function() {showTab(this)};
    document.getElementById("oferta").onclick = function() {showTab(this)};
    document.getElementById("pedido").onclick = function() {showTab(this)};
    document.getElementById("articulo").onclick = function() {showTab(this)};
    document.getElementById("cobro").onclick = function() {showTab(this)};
    document.getElementById("tarifa").onclick = function() {showTab(this)};
    document.getElementById("incidencia").onclick = function() {showTab(this)};
    document.getElementById("albaran").onclick = function() {showTab(this)};
    document.getElementById("pedDomi").onclick = function() {showTab(this)};

    function showTab(e) {
        let selectType = $(e).attr("data-select");
        if (selectType == 'info') {
            $("#editars,#ofertas,#pedidos,#cobros,#tarifas,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#infos").show(); 
            $("#editar,#oferta,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#editar,#oferta,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#info").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#info").addClass('bg-gray-300 text-black font-bold');
            $("#meditar,#moferta,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#meditar,#moferta,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#minfo").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#minfo").addClass('bg-gray-300 text-black font-bold');
        
        } else if (selectType == 'editar') {
        
            $("#infos,#ofertas,#pedidos,#cobros,#tarifas,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#editars").show();
            $("#info,#oferta,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#editar").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#editar").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#meditar").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#meditar").addClass('bg-gray-300 text-black font-bold');
            
        
        } else if (selectType == 'oferta') {
        
            $("#infos,#editars,#pedidos,#cobros,#tarifas,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#ofertas").show();
            $("#info,#editar,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#editar,#pedido,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#oferta").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#oferta").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#meditar,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#meditar,#mpedido,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#moferta").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#moferta").addClass('bg-gray-300 text-black font-bold');
            

        } else if (selectType == 'pedido') {
            
            $("#infos,#ofertas,#editars,#cobros,#tarifas,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#pedidos").show();
            $("#info,#oferta,#editar,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#editar,#cobro,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#pedido").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#pedido").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#meditar,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#meditar,#mcobro,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#mpedido").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#mpedido").addClass('bg-gray-300 text-black font-bold');
            

        } else if (selectType == 'cobro') {
            
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#cobros").show();
            $("#info,#oferta,#pedido,#editar,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#editar,#tarifa,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#cobro").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#cobro").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#mcobro").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#mcobro").addClass('bg-gray-300 text-black font-bold');
            
        }  else if (selectType == 'tarifa') {
            
            $("#infos,#ofertas,#pedidos,#editars,#cobros,#incidencias,#articulos,#albaranes,#pedDomis").hide();
            $("#tarifas").show();
            $("#info,#oferta,#pedido,#editar,#cobro,#incidencia,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#editar,#cobro,#incidencia,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#tarifa").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#tarifa").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#meditar,#mcobro,#mincidencia,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#meditar,#mcobro,#mincidencia,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#mtarifa").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#mtarifa").addClass('bg-gray-300 text-black font-bold');
            

        }  else if (selectType == 'incidencia') {
            
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#cobros,#articulos,#albaranes,#pedDomis").hide();
            $("#incidencias").show();
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#articulo,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#articulo,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#incidencia").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#incidencia").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#marticulo,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#marticulo,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#mincidencia").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#mincidencia").addClass('bg-gray-300 text-black font-bold');
            

        } else if (selectType == 'articulo') {
            
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#cobros,#incidencias,#albaranes,#pedDomis").hide();
            $("#articulos").show();
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#albaran,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#albaran,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#articulo").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#articulo").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#malbaran,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#malbaran,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#marticulo").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#marticulo").addClass('bg-gray-300 text-black font-bold');
        }else if (selectType == 'albaran') {
        
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#cobros,#incidencias,#articulos,#pedDomis").hide();
            $("#albaranes").show();
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#articulo,#pedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#articulo,#pedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#albaran").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#albaran").addClass('bg-gray-300 text-black font-bold');
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#marticulo,#mpedDomi").removeClass('bg-gray-300 text-black font-bold');     
            $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#marticulo,#mpedDomi").addClass('text-gray-600 dark:text-gray-100');     
            $("#malbaran").removeClass('text-gray-600 dark:text-gray-100'); 
            $("#malbaran").addClass('bg-gray-300 text-black font-bold');
        }else if (selectType == 'pedDomi') {
        
        $("#infos,#ofertas,#pedidos,#editars,#tarifas,#cobros,#incidencias,#articulos,#albaranes").hide();
        $("#pedDomis").show();
        $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#articulo,#albaran").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#oferta,#pedido,#editar,#tarifa,#cobro,#incidencia,#articulo,#albaran").addClass('text-gray-600 dark:text-gray-100');     
        $("#pedDomi").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#pedDomi").addClass('bg-gray-300 text-black font-bold');
        $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#marticulo,#malbaran").removeClass('bg-gray-300 text-black font-bold');     
        $("#minfo,#moferta,#mpedido,#meditar,#mtarifa,#mcobro,#mincidencia,#marticulo,#malbaran").addClass('text-gray-600 dark:text-gray-100');     
        $("#mpedDomi").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#mpedDomi").addClass('bg-gray-300 text-black font-bold');
    }
    }
</script>