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
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Lineas Albaran</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Lineas Albaran</div>
                        <div class="p-6 flex flex-col">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="overflow-x-auto">
                                        {{$IdFacturaCli}}
                                        <?php
                                            use App\Http\Controllers\ClienteController;

                                            $pedidos = ClienteController::lineaFactura($IdFacturaCli);
                                        ?>
                                        
                                        <table class="w-full">
                                            <thead>
                                            <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                                <th class="px-4 py-3">Codigo Artículo</th>
                                                <th class="px-4 py-3">Descipcion</th>
                                                <th class="px-4 py-3">U.Pedidas</th>
                                                <th class="px-4 py-3">Precio</th>
                                                <th class="px-4 py-3">Descuento</th>
                                                <th class="px-4 py-3">Importe</th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                            @foreach($pedidos as $pedido)                                    
                                                <tr class="text-gray-700">
                                                    <td class="px-4 py-3 border">
                                                        <div class="flex items-center text-sm">
                                                            {{$pedido->CodigoArticulo}}
                                                        </div>
                                                    </td>                                    
                                                    <td class="px-4 py-3 border">
                                                        <div class="flex items-center text-sm">
                                                            {{$pedido->DescripcionArticulo}}
                                                        </div>
                                                    </td> 
                                                    <td class="px-4 py-3 border">
                                                        <div class="text-right text-sm">
                                                            <?= round($pedido->Unidades,0); ?>
                                                        </div>
                                                    </td> 
                                                    <td class="px-4 py-3 border">
                                                        <div class="text-right text-sm">
                                                            {{number_format(round($pedido->Precio,2), 2, ',', '.')}}€
                                                        </div>
                                                    </td> 
                                                    <td class="px-4 py-3 border">
                                                        <div class="text-right text-sm">
                                                            {{number_format(round($pedido->Descuento,2), 2, ',', '.')}}%
                                                        </div>
                                                    </td> 
                                                    <td class="px-4 py-3 border">
                                                        <div class="text-right text-sm">
                                                            {{number_format(round($pedido->ImporteLiquido,2), 2, ',', '.')}}€
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
    