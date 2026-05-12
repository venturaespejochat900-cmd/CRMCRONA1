<div class="relative mt-3 md:mt-0" x-data="{ isOpen: true }" @click.away="isOpen = false">
    <div class="absolute top-0" >
        <svg class="fill-current w-4 text-gray-500 mt-2" viewBox="0 0 20 20"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 01-1.42 1.4l-5.38-5.38a8 8 0 111.41-1.41zM10 16a6 6 0 100-12 6 6 0 000 12z"/></svg>
    </div>
    <input
        wire:model.debounce.500ms="search"
        type="text"
        class="ml-1 mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " placeholder="Articulos"
        id="articuloinput"
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
        OnKeyup = "notFamilia()"
    >
    

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-3"></div>

    @if (strlen($search) >= 2)
        <div            
            class="z-50 absolute ml-1 mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 articuloResultado-box "
            style=" width:350px !important ;"
            x-show.transition.opacity="isOpen"
        >
            @if ($searchResults->count() > 0)
                <ul>
                    @foreach ($searchResults as $result)
                        <li class="border-b border-gray-700" id='articulo{{$result->DescripcionArticulo}}'>
                            <a href="#" id="{{$result->CodigoArticulo}}" onclick="selectCodigoArticulo(this.id,'<?=$result->DescripcionArticulo?>')">
                                <h2 class="text-md font-semibold text-gray-900">{{$result->CodigoArticulo}}</h2>
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

