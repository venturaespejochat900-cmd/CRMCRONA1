<?php

namespace App\Http\Livewire;

use App\Models\CabeceraPedidoClienteModel;
use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PedidosDatatable extends LivewireDatatable
{
    public $model = CabeceraPedidoClienteModel::class;
    public $post;

    public function builder(){
        return CabeceraPedidoClienteModel::query()
        //->select('NumeroPedido','FechaPedido', 'CodigoCliente', 'RazonSocial', '%Descuento as Descuento', 'ImporteLiquido', 'SeriePedido', 'IdPedidoCli', 'Estado')
        ->where('CodigoCliente', '=', $this->post)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
        //->orderby('FechaPedido', 'Desc');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::callback(['IdPedidoCli'], function ($IdPedidoCli) {
                return view('clientes.pedidos.accionesPedido', ['IdPedidoCli' => $IdPedidoCli]);
            })
            ->unsortable()
            ->excludeFromExport(),

            NumberColumn::name('NumeroPedido')
            ->alignCenter()
            ->label('Nº'),

            Column::name('SeriePedido')
            ->label('Serie'),

            DateColumn::name('FechaPedido')
            ->label('Fecha')
            ->alignCenter()
            ->defaultSort('desc'),            
            
            // NumberColumn::name('%Descuento')
            // ->label('Desc. %'),

            Column::callback(['%Descuento'], function ($Descuento) {
                return view('clientes.ofertas.descuento', ['Descuento' => $Descuento]);
            })->label('Desc. %')
            ->alignCenter()
            ->unsortable(),

            // NumberColumn::name('ImporteLiquido')
            // ->label('Importe'),

            Column::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
            })->label('Importe')
            ->alignCenter()
            ->unsortable(),

            DateColumn::name('FechaEntrega')
            ->label('F.Entrega'),

            // Column::name('Estado')
            // ->label('estado'),    
            
            Column::callback(['Estado','IdPedidoCli'], function ($Estado, $IdPedidoCli) {
                return view('clientes.pedidos.estado', ['Estado' => $Estado, 'IdPedidoCli' => $IdPedidoCli]);
            })->label('estado')
            ->unsortable()
            ->excludeFromExport(),
            
            Column::raw("(CASE
                    WHEN Estado = 0 THEN 'Pendiente'
                    WHEN Estado = 1 THEN 'Bloqueado'
                    WHEN Estado = 2 THEN 'Servido'
                    ELSE 'Modificable'
                    END) AS Estado2")
                ->label('des. Est'),

            Column::name('CabeceraPedidoCliente.ObservacionesPedido')
            ->label('Observaciones'),
        ];
    }
}
