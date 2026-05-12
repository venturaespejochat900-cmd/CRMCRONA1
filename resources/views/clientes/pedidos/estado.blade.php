<!-- <div class="flex space-x-1 justify-around" > 
@if($Estado == 0)
        <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" 
        style="height: 30px !important;" value="{{$IdPedidoCli}}" onclick="modalPedido(this.value)" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal">
        <span>Modificar</span>
        </button>

@elseif($Estado == 1)
        <svg class="h-5 w-5 stroke-current text-red-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>  
        </svg>
@elseif($Estado == 2)
        <svg class="h-5 w-5 stroke-current text-green-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
@endif
</div> -->

<div class="flex space-x-1 justify-around">
        @if($Estado == 0)
                <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;" value="{{$IdPedidoCli}}" onclick="modalPedido(this.value)" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal">
                        <span>Modificar</span>
                </button>
        @else
                <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-gray-300 text-white text-xs leading-4 font-medium uppercase tracking-wider focus:outline-none" style="height: 30px !important;" value="{{$IdPedidoCli}}" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal" disabled>
                        <span>Modificar</span>
                </button>
        @endif
</div>
