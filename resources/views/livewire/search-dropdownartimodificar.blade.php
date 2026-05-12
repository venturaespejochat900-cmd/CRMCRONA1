<div class="relative mt-3 md:mt-0" x-data="{ isOpen: true }" @click.away="isOpen = false">
    <div class="absolute top-0" >
        <svg class="fill-current w-4 text-gray-500 mt-2 ml-1" viewBox="0 0 20 20"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 01-1.42 1.4l-5.38-5.38a8 8 0 111.41-1.41zM10 16a6 6 0 100-12 6 6 0 000 12z"/></svg>
    </div>
    <input
        wire:model.debounce.500ms="search"
        type="text"
        class="ml-1 mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " placeholder="Articulos"
        id="articuloinputmodificar"
        x-ref="search"        
        @keydown.window="
            if (event.keyCode === 191) {
                event.preventDefault();
                $refs.search.focus();
            }            
        "
        @focus="isOpen = true"
        @keydown="isOpen = true"
        @keydown.escape.window="isOpen = false"
        @keydown.shift.tab="isOpen = false"        
    >
    

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-3"></div>

    @if (strlen($search) >= 2)
        <div            
            class="z-50 absolute overflow-auto ml-1 mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 articuloResultadoModificable-box "
            style=" width:450px !important;  height:400px !important;"
            x-show.transition.opacity="isOpen">
            @if ($searchResults->count() > 0)
                <ul>
                    @foreach ($searchResults as $result)
                        <li class="relative border-b border-gray-700 p-1" id='marticulo{{$result->DescripcionArticulo}}'>
                            <a href="#" id="{{$result->CodigoArticulo}}" name='{{ $result->DescripcionArticulo }}' onclick="selectCodigoArticuloModificable(this.id,this.name, '<?=$result->PrecioVenta?>')">                                
                                    <h2 class="text-md font-semibold text-gray-900">{{$result->CodigoArticulo}}                                              
                                        @if($result->PendienteServir > 0)
                                            <span class=" ml-2 p-1 right rounded-full bg-green-500">{{round($result->PendienteServir, 0)}}</span>
                                        @else
                                            <span class=" ml-2 p-1 right rounded-full bg-red-500">{{round($result->PendienteServir, 0)}}</span>  
                                            {{-- <span class=" ml-2 p-1 right rounded-full bg-red-500">{{round($result->StockReservado, 0)}}</span>   --}}
                                        @endif

                                        @if($result->UnidadSaldo > 0)
                                            <span class=" ml-2 p-1 right rounded-full bg-green-500">{{round($result->UnidadSaldo, 0)}}</span>
                                        @else
                                            <span class=" ml-2 p-1 right rounded-full bg-red-500">{{round($result->UnidadSaldo, 0)}}</span>  
                                        @endif

                                        @if($result->PendienteRecibir > 0)
                                            <span class=" ml-2 p-1 right rounded-full bg-green-500">{{round($result->PendienteRecibir, 0)}}</span>
                                        @else
                                            <span class=" ml-2 p-1 right rounded-full bg-red-500">{{round($result->PendienteRecibir, 0)}}</span>
                                        @endif
                                    </h2>                                    
                                <p class="text-gray-900">
                                    {{$result->DescripcionArticulo}}
                                </p>
                            </a>
                        </li>
                    @endforeach

                </ul>
            @else
                <div class="px-3 py-3">No hay resultados de: "{{ $search }}"</div>
            @endif
        </div>
    @endif
</div>

