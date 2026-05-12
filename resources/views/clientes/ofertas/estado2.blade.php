@if($Estado == 2)
<div class="flex space-x-1 justify-around">    
    <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-gray-300 text-white text-xs leading-4 font-medium uppercase tracking-wider focus:outline-none" 
        style="height: 30px !important;" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal" disabled>
        <span>Modificar</span>
    </button>
</div>
@else
    <div class="flex space-x-1 justify-around">    
        <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" 
        style="height: 30px !important;" value="{{$IdOfertaCli}}" onclick="modalPedido(this.value)" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal">
            <span>Modificar</span>
        </button>
    </div>

@endif