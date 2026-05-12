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
                class="hidden p-1 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:block focus:outline-none focus:ring"
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
                      Cerrar Sesion
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
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>

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
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>

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
                              d="M 6 2 C 4.895 2 4 2.895 4 4 L 4 19 C 4 20.64497 5.3550302 22 7 22 L 20 22 L 20 20 L 7 20 C 6.4349698 20 6 19.56503 6 19 C 6 18.43497 6.4349698 18 7 18 L 20 18 L 20 17 L 20 16 L 20 2 L 16 2 L 16 12 L 13 10 L 10 12 L 10 2 L 6 2 z"/>
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
                        <!-- <a
                        href="{{route('stockIncidencias')}}"
                          role="menuitem"
                          class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                        >
                          Incidencias Stock
                        </a> -->
                                        
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
                            <!-- <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                            /> -->
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
                            <!-- <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                            /> -->
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
              
            </div>                      
          </header>

          <!-- Main content -->
          <div class="flex justify-center flex-1 h-full p-4">
            <main>
              