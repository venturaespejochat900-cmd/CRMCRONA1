@if($Estado == 0)
    <div class="flex space-x-1 justify-around"> 
        
        <svg class="h-5 w-5 stroke-current text-black-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12,2C6.477,2,2,6.477,2,12c0,5.523,4.477,10,10,10s10-4.477,10-10C22,6.477,17.523,2,12,2z M15.293,16.707L11,12.414V6h2 v5.586l3.707,3.707L15.293,16.707z"/></svg>

        </svg>
    </div>
@elseif($Estado == 1)
    <div class="flex space-x-1 justify-around">
    
        <svg class="h-5 w-5 stroke-current text-red-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>  
        </svg>
    </div>
@elseif($Estado == 2)

    <div class="flex space-x-1 justify-around">    
        <svg class="h-5 w-5 stroke-current text-green-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
@elseif($Estado == 3)
    <div class="flex space-x-1 justify-around">    
        <button id="modificar" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" 
        style="height: 30px !important;" value="{{$IdPedidoCli}}" onclick="modalPedido(this.value)" data-modal-toggle="extralarge-modal" data-target="#extralarge-modal">
            <span>Modificar</span>
        </button>
    </div>
@endif