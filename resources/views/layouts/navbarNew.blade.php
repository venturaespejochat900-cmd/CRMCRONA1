<div class="flex flex-col flex-1 min-h-screen overflow-x-hidden overflow-y-auto">
          <!-- Navbar -->
          <header class="relative flex-shrink-0 bg-white dark:bg-darker" style="z-index:8;">
            <div class="flex items-center justify-between p-2 border-b dark:border-primary-darker">              

            <button
                @click="isMobileSubMenuOpen = !isMobileSubMenuOpen"
                class="p-1 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:hidden focus:outline-none focus:ring"
              >
                <span class="sr-only">Menu</span>                
                <span aria-hidden="true">
                  <svg
                    class="w-8 h-8"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </span>
              </button>

              <button  
                @click="abrirsidebar = !abrirsidebar"
                class="hidden p-1 mr-3 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:block focus:outline-none focus:ring"
              >
                <span class="sr-only">Menu</span>                
                <span aria-hidden="true">
                  <svg
                    class="w-8 h-8"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </span>
              </button>

              <!-- Brand -->
              <a
                href="{{route('inicio')}}"
                class="p-3 inline-block text-2xl font-bold tracking-wider uppercase text-primary-dark dark:text-light"
              >
                CRM CRONADIS
              </a>
              
              <!-- Mobile sub menu button -->            
                <div class="relative ml-auto" x-data="{ open: false }">
                  <button
                    @click="open = !open"
                    type="button"
                    aria-haspopup="true"
                    :aria-expanded="open ? 'true' : 'false'"
                    class="block transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100 md:hidden"
                  >
                    <span class="sr-only">User menu</span>
                    <img class="w-10 h-10 rounded-full" src="{{asset('media/images/sur.png')}}" alt="Ahmed Kamel" />
                  </button>

                  <!-- User dropdown menu -->
                  <div
                    x-show="open"
                    x-transition:enter="transition-all transform ease-out"
                    x-transition:enter-start="translate-y-1/2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition-all transform ease-in"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-1/2 opacity-0"
                    @click.away="open = false"
                    class="absolute right-0 w-48 py-1 origin-top-right bg-white rounded-md shadow-lg top-12 ring-1 ring-black ring-opacity-5 dark:bg-dark"
                    role="menu"
                    aria-orientation="vertical"
                    aria-label="User menu"
                  >                                                                          
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Sesión {{session('codigoComisionista')}}
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Comisionista {{session('comisionista')}}
                    </a>
                    <a
                      href="./"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Cerrar Sesión
                    </a>
                  </div>
                </div>

              <!-- Desktop Right buttons -->
              <nav aria-label="Secondary" class="hidden space-x-2 md:flex md:items-center">
                <!-- Toggle dark theme button -->
                <button aria-hidden="true" class="relative focus:outline-none" x-cloak @click="toggleTheme">
                  <div
                    class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-lighter"
                  ></div>
                  <div
                    class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-150 transform scale-110 rounded-full shadow-sm"
                    :class="{ 'translate-x-0 -translate-y-px  bg-white text-primary-dark': !isDark, 'translate-x-6 text-primary-100 bg-primary-darker': isDark }"
                  >
                    <svg
                      x-show="!isDark"
                      class="w-4 h-4"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                      />
                    </svg>
                    <svg
                      x-show="isDark"
                      class="w-4 h-4"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                      />
                    </svg>
                  </div>
                </button>                

                <!-- Settings button -->
                <button
                  @click="openSettingsPanel"
                  class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
                >
                  <span class="sr-only">Ajustes</span>
                  <svg
                    class="w-7 h-7"
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
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                </button>

                <!-- User avatar button -->
                <div class="relative" x-data="{ open: false }">
                  <button
                    @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })"
                    type="button"
                    aria-haspopup="true"
                    :aria-expanded="open ? 'true' : 'false'"
                    class="transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100"
                  >
                    <span class="sr-only">User menu</span>
                    <img class="w-10 h-10 rounded-full" src="{{asset('media/images/sur.png')}}" alt="Imagen" />
                  </button>

                  <!-- User dropdown menu -->
                  <div
                    x-show="open"
                    x-ref="userMenu"
                    x-transition:enter="transition-all transform ease-out"
                    x-transition:enter-start="translate-y-1/2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition-all transform ease-in"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-1/2 opacity-0"
                    @click.away="open = false"
                    @keydown.escape="open = false"
                    class="absolute right-0 w-48 py-1 bg-white rounded-md shadow-lg top-12 ring-1 ring-black ring-opacity-5 dark:bg-dark focus:outline-none"
                    tabindex="-1"
                    role="menu"
                    aria-orientation="vertical"
                    aria-label="User menu"
                    style="z-index: 9;"
                  >                    
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Sesión {{session('codigoComisionista')}}
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Comisionista {{session('comisionista')}}
                    </a>
                    <a
                      href="./"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Cerrar Sesión
                    </a>
                  </div>
                </div>
              </nav>
              <!-- Mobile sub menu -->
              <?php 
                use App\Http\Controllers\ComisionistaController;                
              ?>
                <nav
                  x-transition:enter="transition duration-200 ease-in-out transform sm:duration-500"
                  x-transition:enter-start="-translate-y-full opacity-0"
                  x-transition:enter-end="translate-y-0 opacity-100"
                  x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
                  x-transition:leave-start="translate-y-0 opacity-100"
                  x-transition:leave-end="-translate-y-full opacity-0"
                  x-show="isMobileSubMenuOpen"
                  @click.away="isMobileSubMenuOpen = false"
                  class="absolute items-center p-4 bg-white rounded-md shadow-lg dark:bg-darker top-16 inset-x-4 md:hidden"  
                >                
                    
                  <div class="flex justify-between">
                                    
                        <button                      
                        class="mt-2 p-2 mr-5 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
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
                                                                      
                        <span class="font-semibold text-gray-500 mt-8 mr-auto">
                            <?php
                                
                               $comisionista = ComisionistaController::comisionista($IdComisionista);
                               echo $comisionista;
                            ?>                                               
                        </span>   
                        
                        <img src="{{asset('media/images/sur.png')}}" class="mx-auto w-20 h-20 rounded-full">
                                  
                  </div>
                  <div class="mt-10 mb-4">
                      <ul class="ml-4">
              
                          <li onclick="$('#info').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span>
                                  <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 15 3 C 8.373 3 3 8.373 3 16 c 0 6.627 5.373 12 12 12 s 12 -5.373 12 -12 C 27 8.373 21.627 3 15 3 z h 2 z z M 4 17 l 3.5 -4.5 l 3.5 7.5 L 16 5 l 3 10 L 21 9 L 23 9 L 19 21 L 16 10 L 11 24 L 7.5 16 L 6 18 z" clip-rule="evenodd"></path></svg>                
                              </span>            
                              <a>
                                  <span class="ml-2">Info</span>
                              </a>
                          </li>
                          <li onclick="$('#editar').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span>              
                                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                              </span>
                              <a>
                                  <span class="ml-2">Editar</span>
                              </a>
                          </li>
                          <li onclick="$('#cliente').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span aria-hidden="true">
                                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>                                                                        
                                  </svg>
                              </span>
                              <a>               
                                  <span class="ml-2">Clientes</span>
                              </a>
                          </li>
                          <li  onclick="$('#pedido').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span>
                                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
                              </span>
                              <a>
                                  <span class="ml-2">Pedidos</span>
                              </a>
                          </li>
                          <li  onclick="$('#albaran').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span>
                                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                              </span>
                              <a>
                                  <span class="ml-2">Albaranes</span>
                              </a>
                          </li>
                          <li  onclick="$('#factura').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                              <span>
                                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 4 2 C 3.448 2 3 2.448 3 3 L 3 24 C 3 26.209 4.791 28 7 28 L 22 28 L 23 28 L 24 28 C 26.209 28 28 26.209 28 24 L 28 10 C 28 9.448 27.552 9 27 9 L 25 9 L 25 24 C 25 24.553 24.552 25 24 25 C 23.448 25 23 24.553 23 24 L 23 3 C 23 2.448 22.552 2 22 2 L 4 2 z M 6 6 L 20 6 L 20 9 L 6 9 L 6 6 z M 6 13 L 12 13 L 12 15 L 6 15 L 6 13 z M 14 13 L 20 13 L 20 15 L 14 15 L 14 13 z M 6 17 L 12 17 L 12 19 L 6 19 L 6 17 z M 14 17 L 20 17 L 20 19 L 14 19 L 14 17 z M 6 21 L 12 21 L 12 23 L 6 23 L 6 21 z M 14 21 L 20 21 L 20 23 L 14 23 L 14 21 z" clip-rule="evenodd"></path></svg>
                              </span>
                              <a>
                                  <span class="ml-2">Facturas</span>
                              </a>
                          </li>
                          <li  onclick="$('#articulo').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">                            
                              <span aria-hidden="true">
                                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />                                    
                                  </svg>
                              </span>                            
                              <a>
                                  <span class="ml-2">Articulos</span>
                              </a>
                          </li> 
                          <li onclick="$('#cobro').trigger('click')" class="h-10 mb-2 px-4 py-2 text-gray-600 dark:text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
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
              

            </div>            
          </header>

          <!-- Main content -->
          <div class="h-full p-4">
            <main>
              