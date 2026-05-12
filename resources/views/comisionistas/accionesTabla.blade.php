<style>
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
<div class="flex space-x-1 justify-around">

    <div x-data="{ show: false }">
        <div class="flex justify-center">
        <button @click={show=true} type="button" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded has-tooltip">
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Editar Ficha</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Editar Ficha</div>
                        <div class="p-6 flex-grow">
                            @include('comisionistas.edit', ['codigoComisionista'=>$CodigoComisionista, 'IdComisionista'=>$IdComisionista])
                        </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>

    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-yellow-600  hover:bg-yellow-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Clientes</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Clientes</div>
                        <div class="p-6 flex-grow">
                            @php
                                $randomKey = time();
                            @endphp 
                            <livewire:cliente-datatable :post="$CodigoComisionista" :key="$randomKey"
                            searchable="Clientes.CifDni, Clientes.RazonSocial, Clientes.Municipio"
                            exportable
                            modal 
                            />
                        </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>
 

    <div x-data="{ show: false }">
        <div class="flex justify-center">
        <button @click={show=true} type="button" class="p-1 text-gray-600 hover:bg-gray-600 hover:text-white rounded has-tooltip">
        <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Pedidos</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
    </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Pedidos</div>
                        <div class="p-6 flex-grow">

                            <livewire:compras-datatable :post="$CodigoComisionista" :key="$randomKey"
                            searchable="ResumenCliente.RazonSocial, ResumenCliente.FechaFactura"                            
                            exportable
                            modal
                            />
                        </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>


    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Articulos</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Articulos</div>
                    <div class="p-6 flex-grow">



                    <livewire:linea-datatable :post="$CodigoComisionista" :key="$randomKey"                                                       
                            searchable="ResumenCliente.CodigoCliente, ResumenCliente.RazonSocial, LineasAlbaranCliente.CodigoArticulo"
                            exportable
                            modal
                            />
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>
    
    

    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded has-tooltip">     
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Cambiar Contraseña</span>   
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 600px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Cambiar Contraseña</div>
                    <div class="p-6 flex-grow">
                        <!-- card Link cambio contraseña -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                            <div>
                                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                                    Cambio de Contraseña >
                                </h6>
                                <br>
                                <a href ="{{url('/change/password/')}}/{{date('dmY')}}_{{$IdComisionista}}" target="_blank">
                                    <p id="changePassword{{$CodigoComisionista}}" class="text-sm font-semibold dark:text-light" style="white-space: normal">{{url("/change/password/")}}/{{date('dmY')}}_{{$IdComisionista}}</p>                                    
                                </a>                                                                           
                            </div>
                            <div>
                                <button class="btn" style="outline:none;" data-clipboard-target="#changePassword{{$CodigoComisionista}}">                                    
                                    <span>
                                        <svg
                                        class="w-12 h-12 text-gray-400 dark:text-primary-dark"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"                                            
                                        /> 
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"                                            
                                            d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"
                                        />                                        
                                        </svg>
                                    </span>
                                                                               
                                </button>    
                            </div>
                        </div> 
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>

    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Info</span>
                
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 15 3 C 8.373 3 3 8.373 3 16 c 0 6.627 5.373 12 12 12 s 12 -5.373 12 -12 C 27 8.373 21.627 3 15 3 z h 2 z z M 4 17 l 3.5 -4.5 l 3.5 7.5 L 16 5 l 3 10 L 21 9 L 23 9 L 19 21 L 16 10 L 11 24 L 7.5 16 L 6 18 z" clip-rule="evenodd"></path></svg>                
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1200px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Info</div>
                    <div class="p-6 flex-grow">
                        @include('comisionistas.info.datos',['CodigoComisionista'=>$IdComisionista, 'IdComisionista'=>$CodigoComisionista])                        
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>    

</div>

<script>
      var clipboard = new ClipboardJS('.btn');

      clipboard.on('success', function (e) {
        console.log(e);
      });

      clipboard.on('error', function (e) {
        console.log(e);
      });
</script>