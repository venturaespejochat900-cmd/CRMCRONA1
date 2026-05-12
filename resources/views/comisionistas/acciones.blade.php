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
    <a href="{{ route('comisionistasShow', [$CodigoComisionista]) }}" class="p-1 text-green-600 hover:bg-green-600 hover:text-white rounded has-tooltip">
        <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Datos Comisionista</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
    </a>
    <?php
        use App\Http\Controllers\PrescriptorController;
        $usuario = PrescriptorController::usuarioCrm($IdComisionista);

        if($usuario[0]->AccesoUsuario != null){
    ?>
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
    <?php 
        }
    ?>
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
