<div class="flex space-x-1 justify-around" > 
    @if($Estado == 0)
        <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-red-500 text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-red-700 focus:outline-none" 
        style="height: 30px !important;" value="{{$IdPedidoCli}}" onclick="eliminarPedido(this.value)" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal">
            <span>Eliminar</span>
        </button>
        
    @else
        <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-gray-300 text-white text-xs leading-4 font-medium uppercase tracking-wider focus:outline-none" 
        style="height: 30px !important;" value="{{$IdPedidoCli}}" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal" disabled>
            <span>Eliminar</span>
        </button>
    @endif
    </div>