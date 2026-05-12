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
    <a href="{{ route('clientesShow', [$IdCliente]) }}" class="p-1 text-green-600 hover:bg-green-600 hover:text-white rounded has-tooltip">
        <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Datos Cliente</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
    </a>
    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-yellow-600 hover:bg-yellow-600 hover:text-white rounded has-tooltip">
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