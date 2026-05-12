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

    <div x-data="{ show: false }">
        <div class="flex justify-center">
        <button @click={show=true} type="button" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded has-tooltip">
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Lineas Oferta</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Lineas Oferta
                    <button class="items-center space-x-2 px-3 ml-5 border border-blue-400 rounded-md bg-white text-blue-500 text leading-4 font-medium uppercase tracking-wider hover:bg-blue-200 focus:outline-none" id="{{$IdOfertaCli}}" onclick="enviarEmailOferta(this.id)"><i class="space-x-2 py-3 fas fa-paper-plane"></i></button>
                    </div>
                        <div class="p-6 flex flex-col">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="flex py-2 inline-block justify-center min-w-full sm:px-6 lg:px-8">
                                    <div class="overflow-x-auto">
                                    
                                        <?php
                                            use App\Http\Controllers\ClienteController;

                                            $ofertas = ClienteController::lineasOferta($IdOfertaCli);
                                        ?>
                                        
                                        <table class="min-w-1000">
                                            <thead>
                                            <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                                <th class="px-4 py-3">Codigo Art.</th>
                                                <th class="px-4 py-3">Descipción</th>
                                                <th class="px-4 py-3">U.Pedidas</th>
                                                <th class="px-4 py-3">Precio</th>
                                                <th class="px-4 py-3">Descuento</th>
                                                <th class="px-4 py-3">Neto</th>
                                                <th class="px-4 py-3">Importe</th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                            @foreach($ofertas as $oferta) 
                                            <tr class="text-gray-700">
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        {{$oferta->CodigoArticulo}}
                                                    </div>
                                                </td>                                    
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        {{$oferta->DescripcionArticulo}}
                                                    </div>
                                                </td> 
                                                <td class="px-4 py-3 border">
                                                    <div class="text-right text-sm">
                                                        <?= round($oferta->UnidadesPedidas,0); ?>
                                                    </div>
                                                </td> 
                                                <td class="px-4 py-3 border">
                                                    <div class="text-right text-sm">
                                                        {{number_format(round($oferta->Precio,2), 2, ',', '.')}}€

                                                    </div>
                                                </td> 
                                                <td class="px-4 py-3 border">
                                                    <div class="text-right text-sm">
                                                        {{number_format(round($oferta->Descuento,2), 2, ',', '.')}}%
                                                    </div>
                                                </td> 
                                                <td class="px-4 py-3 border">
                                                    <div class="text-right text-sm">
                                                        {{number_format(round($oferta->ImporteNeto,2), 2, ',', '.')}}€
                                                    </div>
                                                </td> 
                                                <td class="px-4 py-3 border">
                                                    <div class="text-right text-sm">
                                                        {{number_format(round($oferta->ImporteLiquido,2), 2, ',', '.')}}€
                                                    </div>
                                                </td> 
                                            </tr>
                                            @endforeach  
                                            </tbody>
                                        </table>

                        
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
    