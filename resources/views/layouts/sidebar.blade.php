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

        <div x-show="abrirsidebar"              
             class="flex overflow-x-hidden h-screen" >          
          <!-- Sidebar -->
          <aside class="flex-shrink-0 w-64 bg-white border-r dark:border-primary-darker dark:bg-darker hidden md:block">
            <div class="flex flex-col h-full">
              <!-- Sidebar links -->
              <nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">
                <!-- Dashboards links -->
                <div x-data="{ isActive: true, open: false}">
                  <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('dashboard')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"                  
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >                               
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Dashboard </span>                  
                  </a>                
                </div>

                <!-- Components links -->
                <?php
                if(session('tipo') == 3 || session('tipo') > 3 ){
                ?>
                <div x-data="{ isActive: false, open: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('comisionistas')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                        <!-- <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        /> -->
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Comisionistas </span>
                    
                  </a>
                </div>  
                <?php 
                }
                ?>

                <div x-data="{ isActive: false, open: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('clientes')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                        <!-- <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        /> -->
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Clientes </span>
                    
                  </a>
                </div>                 
                <div x-data="{ isActive: false, open: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('potenciales')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                        <!-- <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        /> -->
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Potenciales </span>
                    
                  </a>
                </div>
                @livewireStyles

                <div x-data="{ isActive: false, open: false, show: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a @click={show=true}                                      
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 30 30"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15,3C8.373,3,3,8.373,3,15c0,6.627,5.373,12,12,12s12-5.373,12-12C27,8.373,21.627,3,15,3z M21,16h-5v5 c0,0.553-0.448,1-1,1s-1-0.447-1-1v-5H9c-0.552,0-1-0.447-1-1s0.448-1,1-1h5V9c0-0.553,0.448-1,1-1s1,0.447,1,1v5h5 c0.552,0,1,0.447,1,1S21.552,16,21,16z"
                        />                       
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Pedido/Oferta Rápid@ </span>
                  </a>
                  
                  <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 h-full fixed">
                      <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                          <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                              <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>                    
                              <div class="px-6 py-3 text-xl border-b font-bold text-dark">
                                  PEDIDO RAPIDO 
                              </div>
                              <div class="p-6 flex-grow">
                                  <div class="grid grid-cols-1 gap-8 p-4">
                                      
                                      <div id="cliente" class="mb-5">
                                          <label for="clientes" class="block text-sm font-medium text-gray-500">Cliente:</label>
                                          <livewire:search-dropdowncli :key="$randomKey"/>
                                      </div>

                                  </div>                  
                              </div>
                              <div class="px-6 py-3 border-t mt-5">                            
                                  <div class="flex justify-end">
                                      <button onclick="irPedido('p')" type="button" class="text-gray-100 rounded px-4 py-2 mr-5" style="background-color: #00b7db;">Ir a Pedido</Button>
                                      <button onclick="irPedido('o')" type="button" class="text-gray-100 rounded px-4 py-2 mr-5" style="background-color: #00db92;">Ir a Oferta</Button>
                                      <button onclick="$('#clienteinput').val('')"@click={show=false} type="button" class="bg-red-700 text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50"></div>
                  </div>
                  
                </div>

                @livewireScripts
                <div x-data="{ isActive: false, open: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('pedidosClientes')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5 L18.5,9H13z"
                        />
                        <!-- <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        /> -->
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Pedidos </span>

                    </a>
                </div>

                <div x-data="{ isActive: false, open: false }">
                  <!-- active classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="{{route('ofertasClientes')}}"                  
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5 L18.5,9H13z"
                        />
                        <!-- <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        /> -->
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Ofertas </span>

                    </a>
                </div>
                
                <!-- Layouts links -->
                <div x-data="{ isActive: false, open: false}">
                  <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="#"
                    @click="$event.preventDefault(); open = !open"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >   
                                        
                        <path 
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"                        
                          d="M 6 2 C 4.895 2 4 2.895 4 4 L 4 19 C 4 20.64497 5.3550302 22 7 22 L 20 22 L 20 20 L 7 20 C 6.4349698 20 6 19.56503 6 19 C 6 18.43497 6.4349698 18 7 18 L 20 18 L 20 17 L 20 16 L 20 2 L 16 2 L 16 12 L 13 10 L 10 12 L 10 2 L 6 2 z"
                        />                        
                        
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Agenda </span>
                    <span aria-hidden="true" class="ml-auto">
                      <!-- active class 'rotate-180' -->
                      <svg
                        class="w-4 h-4 transition-transform transform"
                        :class="{ 'rotate-180': open }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </span>
                  </a>
                  <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Layouts">
                    <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                    <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->                
                    <a
                    href="{{route('calendario')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Calendario Comercial
                    </a> 
                    <a
                    href="{{route('recuentoAcciones')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"                    
                    >
                      Acciones de Comerciales
                    </a>
                    <a
                    href="{{route('recuentoEmpresas')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"                    
                    >
                      Acciones de Clientes
                    </a>                   
                  </div>
                </div>

                <!-- Layouts links -->
                <div x-data="{ isActive: false, open: false}">
                  <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="#"
                    @click="$event.preventDefault(); open = !open"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                        />
                        
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Articulos </span>
                    <span aria-hidden="true" class="ml-auto">
                      <!-- active class 'rotate-180' -->
                      <svg
                        class="w-4 h-4 transition-transform transform"
                        :class="{ 'rotate-180': open }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </span>
                  </a>
                  <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Layouts">
                    <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                    <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                    <a
                      href="{{route('stock')}}"
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                    >
                      Stock
                    </a>
                    <a
                    href="{{route('articulosTarifa')}}"
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                    >
                      Artículos Tarifa
                    </a>
                                    
                  </div>
                </div>

                <!-- Layouts links -->
                <div x-data="{ isActive: false, open: false}">
                  <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="#"
                    @click="$event.preventDefault(); open = !open"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >                        
                        <path 
                          stroke="none" 
                          d="M0 0h24v24H0z"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                        />  
                        <path 
                          d="M14 3v4a1 1 0 0 0 1 1h4" 
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                        />  
                        <path 
                          d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" 
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                        />  
                        <line
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2" 
                          x1="3" y1="12" x2="21" y2="12" 
                        />  
                        <line
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2" 
                          x1="6" y1="16" x2="6" y2="18" 
                        />  
                        <line 
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          x1="10" y1="16" x2="10" y2="22" 
                        />  
                        <line
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2" 
                          x1="14" y1="16" x2="14" y2="18" 
                        />  
                        <line 
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          x1="18" y1="16" x2="18" y2="20" 
                        />
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Informes </span>
                    <span aria-hidden="true" class="ml-auto">
                      <!-- active class 'rotate-180' -->
                      <svg
                        class="w-4 h-4 transition-transform transform"
                        :class="{ 'rotate-180': open }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </span>
                  </a>
                  <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Layouts">
                    <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                    <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                    <a
                      href="{{route('informes1')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Ventas Anuales/Meses
                    </a>
                    <a
                    href="{{route('informes2')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Ventas por fecha
                    </a> 
                    <a
                    href="{{route('informes3')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"                    
                    >
                      Ventas familia, articulo, fecha
                    </a>
                  <?php
                  if(session('tipo') == 5 ){
                  ?> 
                    <a
                    href="{{route('informes4')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-700 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Ventas por Comisionista 
                    </a>
                  <?php 
                  }
                  ?>                   
                  </div>
                </div>

                <!-- Layouts links -->
                <div x-data="{ isActive: false, open: false}">
                  <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
                  <a
                    href="#"
                    @click="$event.preventDefault(); open = !open"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                    role="button"
                    aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  >
                    <span aria-hidden="true">
                      <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >                        
                        <path d="M 18.5 2 C 16.567 2 15 3.567 15 5.5 C 15 8.125 18.0625 9.427 18.0625 12.5 C 18.0625 12.741 18.259 12.953125 18.5 12.953125 C 18.741 12.953125 18.972656 12.775156 18.972656 12.535156 C 18.971656 9.4611563 22 8 22 5.5 C 22 3.567 20.432 2 18.5 2 z M 5 3 C 3.895 3 3 3.895 3 5 L 3 19 C 3 20.105 3.895 21 5 21 L 19 21 C 20.105 21 21 20.105 21 19 L 21 12 C 21 12 20.875 14.375 19 15 L 19 17.585938 L 13.414062 12 L 15.144531 10.269531 L 13.917969 8.6679688 L 5 17.585938 L 5 5 L 13 5 C 13.125 3.75 13.625 3 13.625 3 L 5 3 z M 18.533203 4.2988281 C 19.177203 4.2988281 19.701172 4.8198438 19.701172 5.4648438 C 19.701172 6.1098438 19.177203 6.6328125 18.533203 6.6328125 C 17.889203 6.6328125 17.367188 6.1098438 17.367188 5.4648438 C 17.367188 4.8208437 17.888203 4.2988281 18.533203 4.2988281 z M 8.5019531 6 C 7.1199531 6 6 7.119 6 8.5 C 6 9.881 7.1199531 11 8.5019531 11 C 10.600953 11 11.062422 9.0365 10.857422 8.0625 L 8.5019531 8.0605469 L 8.5019531 9.015625 L 9.8613281 9.015625 C 9.6833281 9.594625 9.2019531 10.007812 8.5019531 10.007812 C 7.6689531 10.007812 6.9921875 9.333 6.9921875 8.5 C 6.9921875 7.667 7.6689531 6.9921875 8.5019531 6.9921875 C 8.8759531 6.9921875 9.2164688 7.1294688 9.4804688 7.3554688 L 10.185547 6.6523438 C 9.7405469 6.2473437 9.1499531 6 8.5019531 6 z M 12 13.414062 L 17.585938 19 L 6.4140625 19 L 12 13.414062 z"/>
                      </svg>
                    </span>
                    <span class="ml-2 text-sm"> Mapas de Calor </span>
                    <span aria-hidden="true" class="ml-auto">
                      <!-- active class 'rotate-180' -->
                      <svg
                        class="w-4 h-4 transition-transform transform"
                        :class="{ 'rotate-180': open }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </span>
                  </a>
                  <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Layouts">
                    <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                    <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                    <a
                      href="{{route('heatmap')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Clientes
                    </a>
                    <a
                    href="{{route('heatmapotenciales')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                    >
                      Potenciales
                    </a> 
                    <a
                    href="{{route('heatmaprescriptores')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"                    
                    >
                      Comisionistas
                    </a>
                    <a
                    href="{{route('heatmapinforme')}}" 
                      role="menuitem"
                      class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"                    
                    >
                      Informe Ventas
                    </a>                   
                  </div>
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
          

        

        

        
    