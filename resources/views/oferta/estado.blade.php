@if($Estado != 2)    
    <div class="flex space-x-1 justify-around">    
        <button id="modificar{{$IdOfertaCli}}" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" 
        style="height: 30px !important;" value="{{$IdOfertaCli}}" onclick="convertirEnPedido(this.value)">
            <span>Aprobar</span>
        </button>
    </div>
@else

    <div class="flex space-x-1 justify-around">    
        <svg class="h-5 w-5 stroke-current text-green-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

@endif