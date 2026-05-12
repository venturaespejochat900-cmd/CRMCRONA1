<?php
use App\Http\Controllers\ClienteController;
?>
<div>
    <select wire:model="direccion" name="filtroDomiPed" id="filtroDomiPed" class="block w-full py-3 pl-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none text-gray-900">
        <?php
            $domicilios = App\Http\Controllers\ClienteController::obtenerDomicilio($post)
        ?>
        @foreach ($domicilios as $domicilio)
            <option value="{{ $domicilio->NumeroDomicilio }}">{{ $domicilio->Domicilio }}</option>
        @endforeach
    </select>

    <table class="min-w-full bg-white border border-gray-200 mt-4">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-4 py-2 border-b">Acciones</th>
                <th class="px-4 py-2 border-b">Nº</th>
                <th class="px-4 py-2 border-b">Serie</th>
                <th class="px-4 py-2 border-b">Fecha</th>
                <th class="px-4 py-2 border-b">Desc. %</th>
                <th class="px-4 py-2 border-b">Importe</th>
                <th class="px-4 py-2 border-b">F.Entrega</th>
                <th class="px-4 py-2 border-b">Des. Est</th>
                <th class="px-4 py-2 border-b">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
                <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} text-black">
                    <td class="border px-4 py-2 text-center">
                        @include('clientes.pedidos.accionesPedido', ['IdPedidoCli'=>$pedido->IdPedidoCli])
                        {{-- view('clientes.pedidos.accionesPedido', ['IdPedidoCli' => $IdPedidoCli]); --}}
                        {{-- <a href="{{ route('pedido.acciones', ['IdPedidoCli' => $pedido->IdPedidoCli]) }}" class="text-blue-500">Acciones</a> --}}
                    </td>
                    <td class="border px-4 py-2 text-center">{{ $pedido->NumeroPedido }}</td>
                    <td class="border px-4 py-2">{{ $pedido->SeriePedido }}</td>
                    <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($pedido->FechaPedido)->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2 text-center">{{ round($pedido->{'%Descuento'},2) }}</td>
                    <td class="border px-4 py-2 text-center">{{ round($pedido->ImporteLiquido,2) }}</td>
                    <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($pedido->FechaEntrega)->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2">{{ $pedido->Estado == 0 ? 'Pendiente' : ($pedido->Estado == 1 ? 'Bloqueado' : ($pedido->Estado == 2 ? 'Servido' : 'Modificable')) }}</td>
                    <td class="border px-4 py-2">{{ $pedido->ObservacionesPedido }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>
</div>