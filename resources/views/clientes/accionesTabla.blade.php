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

    <!-- <a href="{{--{{ route('prescriptor.edit', [$CodigoCliente]) }}--}}" target="_blank" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
    </a> -->
    <?php

        use App\Http\Controllers\ClienteController;

        $razonSocial = ClienteController::razonSocial($CodigoCliente);
    ?>

    <div x-data="{ show: false }">
        <div class="flex justify-center">
        <button @click={show=true} type="button" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded has-tooltip">
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Editar Cliente</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Editar Cliente</div>
                        <div x-if="show"  class="p-6 flex-grow">
                            
                                @include('clientes.edit', ['codigoCliente'=>$CodigoCliente, 'idCliente'=>$IdCliente])
                            
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
        <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Ofertas</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
    </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Ofertas: {{$razonSocial[0]->RazonSocial}} </div>
                        <div class="p-6 flex-grow">
                            @php
                                $randomKey = time();
                            @endphp 
                            
                            <livewire:ofertas-datatable :post='$IdCliente' :key='$randomKey'                                
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
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Pedidos</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Pedidos: {{$razonSocial[0]->RazonSocial}}</div>
                    <div class="p-6 flex-grow">

                            <livewire:pedidos-datatable :post='$IdCliente' :key='$randomKey'                              
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
            <button @click={show=true} type="button" class="p-1 text-yellow-600 hover:bg-yellow-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Cobros</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 4 2 C 3.448 2 3 2.448 3 3 L 3 24 C 3 26.209 4.791 28 7 28 L 22 28 L 23 28 L 24 28 C 26.209 28 28 26.209 28 24 L 28 10 C 28 9.448 27.552 9 27 9 L 25 9 L 25 24 C 25 24.553 24.552 25 24 25 C 23.448 25 23 24.553 23 24 L 23 3 C 23 2.448 22.552 2 22 2 L 4 2 z M 6 6 L 20 6 L 20 9 L 6 9 L 6 6 z M 6 13 L 12 13 L 12 15 L 6 15 L 6 13 z M 14 13 L 20 13 L 20 15 L 14 15 L 14 13 z M 6 17 L 12 17 L 12 19 L 6 19 L 6 17 z M 14 17 L 20 17 L 20 19 L 14 19 L 14 17 z M 6 21 L 12 21 L 12 23 L 6 23 L 6 21 z M 14 21 L 20 21 L 20 23 L 14 23 L 14 21 z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1200px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Cobros: {{$razonSocial[0]->RazonSocial}}{{$IdCliente}}</div>
                    <div class="p-6 flex-grow">

                            <livewire:cobros-datatable :post='$IdCliente'  :key='$randomKey'                             
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
            <button @click={show=true} type="button" class="p-1 text-green-600 hover:bg-green-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Tarifas</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,21h-8c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h8c0.552,0,1,0.448,1,1 C19,20.552,18.552,21,18,21z M20,17H10c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1C21,16.552,20.552,17,20,17 z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div  x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" id="modalTarifas" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Tarifas Especiales</div>
                    <div class="p-6 flex-grow">

                    @include('clientes.tarifas.tarifas',['CodigoCliente'=>$CodigoCliente, 'IdCliente'=>$IdCliente])
                                
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false}  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>
    
    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-pink-600 hover:bg-pink-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Incidencias</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M22,4H8C5.791,4,4,5.791,4,8v14c0,2.209,1.791,4,4,4h14c2.209,0,4-1.791,4-4V8C26,5.791,24.209,4,22,4z M6.293,10.293l4-4	c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-4,4C7.512,11.902,7.256,12,7,12s-0.512-0.098-0.707-0.293	C5.902,11.316,5.902,10.684,6.293,10.293z M6.293,16.293l10-10c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-10,10	C7.512,17.902,7.256,18,7,18s-0.512-0.098-0.707-0.293C5.902,17.316,5.902,16.684,6.293,16.293z M22,24c-1.105,0-2-0.895-2-2	c0-1.105,0.895-2,2-2s2,0.895,2,2C24,23.105,23.105,24,22,24z M23.707,13.707l-10,10C13.512,23.902,13.256,24,13,24	s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l10-10c0.391-0.391,1.023-0.391,1.414,0S24.098,13.316,23.707,13.707z M23.707,7.707l-16,16C7.512,23.902,7.256,24,7,24s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l16-16	c0.391-0.391,1.023-0.391,1.414,0S24.098,7.316,23.707,7.707z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Incidencias</div>
                    <div class="p-6 flex-grow">
                        <livewire:incidencias-datatable :post="$IdCliente" :key="$randomKey"                                                                               
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

    <a id="codigoCliente{{$CodigoCliente}}" class="p-1 text-black-600 rounded disabled:opacity-50" disabled>        
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 13 1 C 11.394 1 10.1383 2.1848 9.3027 3.6133 C 8.6657 4.7023 8.247 5.9596 8.082 7.1191 L 5.668 7.668 L 3 24 L 18.332 27 L 27 25.0781 L 24.332 7 L 22.332 6.5 L 20.332 4.332 L 18.2402 4.8086 C 18.1288 4.4221 17.99 4.0388 17.8027 3.6816 C 17.3347 2.7889 16.5623 2 15.5 2 C 15.3404 2 15.1914 2.0296 15.041 2.0547 C 14.9222 1.8901 14.7923 1.7333 14.6387 1.5977 C 14.2178 1.226 13.6504 1 13 1 z M 13 2 C 13.4356 2 13.725 2.1236 13.9766 2.3457 C 13.9988 2.3653 14.0156 2.3985 14.0371 2.4199 C 13.7209 2.6051 13.4295 2.8309 13.1816 3.0977 C 12.5953 3.7289 12.1729 4.5277 11.8555 5.3125 C 11.7132 5.6643 11.5985 6.0094 11.4961 6.3418 L 9.1523 6.875 C 9.3337 5.9539 9.6736 4.961 10.166 4.1191 C 10.9032 2.8588 11.897 2 13 2 z M 15.5352 3.0078 C 16.0811 3.026 16.5482 3.445 16.916 4.1465 C 17.0576 4.4166 17.1679 4.7216 17.2598 5.0313 L 15.959 5.3262 C 15.9082 4.5335 15.7948 3.7255 15.5352 3.0078 z M 14.5547 3.2617 C 14.5666 3.2921 14.5824 3.3143 14.5938 3.3457 C 14.8183 3.9651 14.9256 4.7578 14.9707 5.5508 L 12.6465 6.0801 C 12.6941 5.9478 12.7294 5.8206 12.7832 5.6875 C 13.0724 4.9723 13.4554 4.2711 13.9141 3.7773 C 14.116 3.56 14.3295 3.3914 14.5547 3.2617 z z" clip-rule="evenodd"></path></svg>
    </a>

    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-teal-600 hover:bg-teal-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Firma documentos</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 800px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Links de Firmas de documentos</div>
                        <div class="p-6 flex-grow">
                              <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-1 ">
                                <!-- card Link firma -->
                                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                                    <div>
                                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                                            RGPD >
                                        </h6>
                                        <br>
                                        <a href ="{{url('/firma/rgpd/')}}/{{$CodigoCliente}}#{{date('dmY') }}" target="_blank">
                                            <p id="centroVendedor{{$CodigoCliente}}" class="text-sm font-semibold dark:text-light" style="white-space: normal">{{url("/firma/rgpd/")}}/{{$CodigoCliente}}#{{date('dmY') }}</p>                                    
                                        </a>
                                    </div>
                                    <div>
                                        <button class="btn" style="outline:none;" data-clipboard-target="#centroVendedor{{$CodigoCliente}}">
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
                                <!-- card Link firma -->
                                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                                    <div>
                                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                                            SEPA >
                                        </h6>
                                        <br>
                                        <a href ="{{url('/firma/sepa/')}}/{{$CodigoCliente}}#{{date('dmY') }}" target="_blank">
                                            <p id="medico{{$CodigoCliente}}" class="text-sm font-semibold dark:text-light" style="white-space: normal">{{url("/firma/sepa/")}}/{{$CodigoCliente}}#{{date('dmY') }}</p>                                    
                                        </a>                                                                           
                                    </div>
                                    <div>
                                        <button class="btn" style="outline:none;" data-clipboard-target="#medico{{$CodigoCliente}}">                                    
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
                        @include('clientes.info.datos',['CodigoCliente'=>$CodigoCliente, 'IdCliente'=>$IdCliente])
                        
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
        //console.log(e);
      });

      clipboard.on('error', function (e) {
        //console.log(e);
      });


</script>