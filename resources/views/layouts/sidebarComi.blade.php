</head>
  <body>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
      <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
        <!-- Loading screen -->
        <div
          x-ref="loading"
          class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
        >
          Cargando CRONADIS.....
        </div>

        <!-- Sidebar -->
        <div x-show="abrirsidebar"
            class="flex overflow-x-hidden h-screen">
            <aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker hidden md:block">
                <div class="flex flex-col h-full">
                    <!-- Sidebar links -->
                    <nav class="flex flex-col h-screen px-4 tex-gray-900 border dark:bg-darker dark:border-primary-darker">
                        <div class="flex flex-wrap mt-8">
                            <div class="w-1/4">                        
                                <button                      
                                class="mt-2 p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
                                >
                                <a href="{{route('comisionistas')}}">  
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
                                <span class="font-semibold text-gray-500">
                                    <?php
                                        use App\Http\Controllers\ComisionistaController;
                                        $comisionista = ComisionistaController::comisionista($IdComisionista);
                                        echo $comisionista;
                                    ?>                                               
                                </span>                                       
                            </div>                
                        </div>
                        <div class="mt-10 mb-4">
                            <ul class="ml-4">
                    
                                <li data-select="info" id="info" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 bg-gray-300 text-black font-bold  dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
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
                                <li data-select="cliente" id="cliente" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>                                                                        
                                        </svg>
                                    </span>
                                    <a>               
                                        <span class="ml-2">Clientes</span>
                                    </a>
                                </li>
                                <li  data-select="pedido" id="pedido" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Pedidos</span>
                                    </a>
                                </li>
                                <li  data-select="albaran" id="albaran" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Albaranes</span>
                                    </a>
                                </li>
                                <li  data-select="factura" id="factura" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 4 2 C 3.448 2 3 2.448 3 3 L 3 24 C 3 26.209 4.791 28 7 28 L 22 28 L 23 28 L 24 28 C 26.209 28 28 26.209 28 24 L 28 10 C 28 9.448 27.552 9 27 9 L 25 9 L 25 24 C 25 24.553 24.552 25 24 25 C 23.448 25 23 24.553 23 24 L 23 3 C 23 2.448 22.552 2 22 2 L 4 2 z M 6 6 L 20 6 L 20 9 L 6 9 L 6 6 z M 6 13 L 12 13 L 12 15 L 6 15 L 6 13 z M 14 13 L 20 13 L 20 15 L 14 15 L 14 13 z M 6 17 L 12 17 L 12 19 L 6 19 L 6 17 z M 14 17 L 20 17 L 20 19 L 14 19 L 14 17 z M 6 21 L 12 21 L 12 23 L 6 23 L 6 21 z M 14 21 L 20 21 L 20 23 L 14 23 L 14 21 z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <a>
                                        <span class="ml-2">Facturas</span>
                                    </a>
                                </li>
                                <li  data-select="articulo" id="articulo" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">                            
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />                                    
                                        </svg>
                                    </span>                            
                                    <a>
                                        <span class="ml-2">Articulos</span>
                                    </a>
                                </li> 
                                <li  data-select="cobro" id="cobro" href="javascript:void(0)" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6C4 9 4 7 4 13 4 17 4 17 4 18 4 18 17 18 26 18 26 14 26 12 26 6l-22 0ZM15.9531 15.8867 15.9531 17.2891 13.9531 17.2891 13.9531 15.8945C12.7578 15.6328 12.0273 14.8867 12.0039 13.8047L13.7109 13.8047C13.7539 14.3164 14.2695 14.6445 15.0117 14.6445 15.6797 14.6445 16.1367 14.3242 16.1367 13.8594 16.1367 13.4688 15.8281 13.2578 15.0234 13.0977L14.0977 12.9102C12.8086 12.6641 12.1445 11.9609 12.1445 10.8555 12.1445 9.7695 12.8359 8.9883 13.9531 8.6992L13.9531 7.2891 15.9531 7.2891 15.9531 8.707C17.043 8.9961 17.7461 9.7539 17.7695 10.7734L16.1133 10.7734C16.0742 10.2734 15.6016 9.9258 14.9727 9.9258 14.3398 9.9258 13.9258 10.2227 13.9258 10.6953 13.9258 11.0781 14.2383 11.3047 14.9805 11.4492L15.8906 11.625C17.2813 11.8945 17.9063 12.5234 17.9063 13.6445 17.9063 14.8164 17.1797 15.6094 15.9531 15.8867ZM15.9531 15.8867" />                                    
                                        </svg>
                                    </span>
                                    <a>
                                        <span class="ml-2"> Cobros</span>
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
    document.getElementById("cliente").onclick = function() {showTab(this)};
    document.getElementById("pedido").onclick = function() {showTab(this)};
    document.getElementById("albaran").onclick = function() {showTab(this)};
    document.getElementById("factura").onclick = function() {showTab(this)};
    document.getElementById("articulo").onclick = function() {showTab(this)};
    document.getElementById("cobro").onclick = function() {showTab(this)};

    
    
    function showTab(e) {
    let selectType = $(e).attr("data-select");
    if (selectType == 'info') {
        $("#editars,#clientes,#pedidos,#albaranes,#facturas,#articulos,#cobros").hide();
        $("#infos").show();
        $("#editar,#cliente,#pedido,#albaran,#factura,#articulo,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#editar,#cliente,#pedido,#albaran,#factura,#articulo,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#info").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#info").addClass('bg-gray-300 text-black font-bold');
        
    
    } else if (selectType == 'editar') {
    
        $("#infos,#clientes,#pedidos,#albaranes,#facturas,#articulos,#cobros").hide();
        $("#editars").show();
        $("#info,#cliente,#pedido,#albaran,#factura,#articulo,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#cliente,#pedido,#albaran,#factura,#articulo,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#editar").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#editar").addClass('bg-gray-300 text-black font-bold');
        
    
    } else if (selectType == 'cliente') {
    
        $("#infos,#editars,#pedidos,#albaranes,#facturas,#articulos,#cobros").hide();
        $("#clientes").show();
        $("#info,#editar,#pedido,#albaran,#factura,#articulo,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#pedido,#albaran,#factura,#articulo,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#cliente").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#cliente").addClass('bg-gray-300 text-black font-bold');
        

    } else if (selectType == 'pedido') {
        
        $("#infos,#clientes,#editars,#albaranes,#facturas,#articulos,#cobros").hide();
        $("#pedidos").show();
        $("#info,#editar,#cliente,#albaran,#factura,#articulo,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#cliente,#albaran,#factura,#articulo,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#pedido").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#pedido").addClass('bg-gray-300 text-black font-bold');
        

    } else if (selectType == 'albaran') {
        
        $("#infos,#clientes,#editars,#articulos,#facturas,#pedidos,#cobros").hide();
        $("#albaranes").show();
        $("#info,#editar,#cliente,#articulo,#factura,#pedido,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#cliente,#articulo,#factura,#pedido,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#albaran").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#albaran").addClass('bg-gray-300 text-black font-bold');
        

    }else if (selectType == 'factura') {
        
        $("#infos,#clientes,#editars,#articulos,#pedidos,#albaranes,#cobros").hide();
        $("#facturas").show();
        $("#info,#editar,#cliente,#articulo,#pedido,#albaran,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#cliente,#articulo,#pedido,#albaran,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#factura").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#factura").addClass('bg-gray-300 text-black font-bold');
        

    }else if (selectType == 'articulo') {
        
        $("#infos,#clientes,#pedidos,#albaranes,#facturas,#editars,#cobros").hide();
        $("#articulos").show();
        $("#info,#editar,#cliente,#albaran,#factura,#pedido,#cobro").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#cliente,#albaran,#factura,#pedido,#cobro").addClass('text-gray-600 dark:text-gray-100');     
        $("#articulo").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#articulo").addClass('bg-gray-300 text-black font-bold');
        
    }else if (selectType == 'cobro') {
        
        $("#infos,#clientes,#pedidos,#albaranes,#facturas,#editars,#articulos").hide();
        $("#cobros").show();
        $("#info,#editar,#cliente,#albaran,#factura,#pedido,#articulo").removeClass('bg-gray-300 text-black font-bold');     
        $("#info,#editar,#cliente,#albaran,#factura,#pedido,#articulo").addClass('text-gray-600 dark:text-gray-100');     
        $("#cobro").removeClass('text-gray-600 dark:text-gray-100'); 
        $("#cobro").addClass('bg-gray-300 text-black font-bold');
        
    }
    }

        </script>

        

        

        
    