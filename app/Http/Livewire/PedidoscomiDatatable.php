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

class PedidoscomiDatatable extends LivewireDatatable
{
    public $model = CabeceraPedidoClienteModel::class;
    public $post;

    public function builder(){

            if(session('tipo') == 5){

                return CabeceraPedidoClienteModel::query()
                // ->selectRaw("(CASE
                //     WHEN Estado = 0 THEN 'Pendiente'
                //     WHEN Estado = 1 THEN 'Bloqueado'
                //     WHEN Estado = 2 THEN 'Servido'
                //     ELSE 'Modificable'
                // END) AS Tipo")
                ->join('Comisionistas', function($join) {                
                    $join->on('Comisionistas.CodigoComisionista', '=', 'CabeceraPedidoCliente.CodigoComisionista');
                    $join->on('Comisionistas.CodigoEmpresa', '=', 'CabeceraPedidoCliente.CodigoEmpresa');
                })
                ->where('CabeceraPedidoCliente.CodigoEmpresa', '=', session('codigoEmpresa'));
                //->where('Comisionistas.CodigoJefeVenta_', '=', $this->post);

            }else if(session('tipo') == 3){

                return CabeceraPedidoClienteModel::query()
                // ->selectRaw("(CASE
                //     WHEN Estado = 0 THEN 'Pendiente'
                //     WHEN Estado = 1 THEN 'Bloqueado'
                //     WHEN Estado = 2 THEN 'Servido'
                //     ELSE 'Modificable'
                // END) AS Tipo")
                ->join('Comisionistas', function($join) {                
                    $join->on('Comisionistas.CodigoComisionista', '=', 'CabeceraPedidoCliente.CodigoComisionista');
                    $join->on('Comisionistas.CodigoEmpresa', '=', 'CabeceraPedidoCliente.CodigoEmpresa');
                })
                ->where('CabeceraPedidoCliente.CodigoEmpresa', '=', session('codigoEmpresa'))            
                //->where('Comisionistas.CodigoJefeVenta_', '=', $this->post)
                ->where(function($query){
                	$query->where('Comisionistas.CodigoJefeVenta_', '=', $this->post)
                    ->orwhere('Comisionistas.CodigoComisionista', '=', $this->post);
                });
                //->where('Comisionistas.CodigoComisionista', '=', $this->post);


            }else{
            
            return CabeceraPedidoClienteModel::query()
            //->select('NumeroPedido','FechaPedido', 'CodigoCliente', 'RazonSocial', '%Descuento as Descuento', 'ImporteLiquido', 'SeriePedido', 'IdPedidoCli', 'Estado')
            ->where('CodigoComisionista', '=', $this->post)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
            // ->orderby('Estado', 'asc');
            }
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {        
        if(session('tipo')==5){
            return [
                Column::callback(['IdPedidoCli'], function ($IdPedidoCli) {
                    return view('clientes.pedidos.accionesPedido', ['IdPedidoCli' => $IdPedidoCli]);
                })
                ->unsortable()
                ->excludeFromExport(),

                Column::name('RazonSocial')
                ->label('Cliente')
                ->searchable(),

                NumberColumn::name('NumeroPedido')
                ->label('Nº')
                ->searchable(),

                Column::name('SeriePedido')
                ->label('Serie'),

                DateColumn::name('FechaPedido')
                ->label('Fecha')
                ->defaultSort('desc')
                ->searchable(),            
                
                // NumberColumn::name('%Descuento')
                // ->label('Desc. %'),

                Column::callback(['%Descuento'], function ($Descuento) {
                    return view('clientes.ofertas.descuento', ['Descuento' => $Descuento]);
                })->label('Desc. %')
                ->unsortable()
                ->alignRight(),

                // NumberColumn::name('ImporteLiquido')
                // ->label('Importe'),

                Column::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                    return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
                })->label('Importe')                
                ->unsortable()
                ->alignRight(),

                DateColumn::name('FechaEntrega')
                ->label('F.Entrega'),

                // Column::name('Estado')
                // ->label('estado')
                // ->hide()
                // ->defaultSort('asc'),    
                
                Column::callback(['Estado','IdPedidoCli'], function ($Estado, $IdPedidoCli) {
                    return view('clientes.pedidos.estado', ['Estado' => $Estado, 'IdPedidoCli' => $IdPedidoCli]);
                })->label('estado')
                ->unsortable()
                ->excludeFromExport(),
                
                Column::callback(['EjercicioPedido','SeriePedido','NumeroPedido','IdPedidoCli','Estado'], function ($EjercicioPedido, $SeriePedido, $NumeroPedido, $IdPedidoCli, $Estado) {
                    return view('clientes.pedidos.eliminar', 
                    ['EjercicioPedido' => $EjercicioPedido, 'SeriePedido' => $SeriePedido, 'NumeroPedido' => $NumeroPedido, 'IdPedidoCli' => $IdPedidoCli, 'Estado' => $Estado]);
                })->label('Eliminar')
                ->unsortable()
                ->excludeFromExport(),                
                
                Column::callback(['EjercicioPedido','SeriePedido','NumeroPedido','IdPedidoCli'], function ($EjercicioPedido, $SeriePedido, $NumeroPedido, $IdPedidoCli) {
                    return view('clientes.pedidos.duplicarPedido', 
                    ['EjercicioPedido' => $EjercicioPedido, 'SeriePedido' => $SeriePedido, 'NumeroPedido' => $NumeroPedido, 'IdPedidoCli' => $IdPedidoCli]);
                })->label('Duplicar')
                ->unsortable()
                ->excludeFromExport(),                

                Column::raw("(CASE
                    WHEN Estado = 0 THEN 'Pendiente'
                    WHEN Estado = 1 THEN 'Bloqueado'
                    WHEN Estado = 2 THEN 'Servido'
                    ELSE 'Modificable'
                    END) AS Estado2")
                ->label('des. Est'),
                
                Column::name('Comisionistas.Comisionista')
                ->label('Comercial')
                ->searchable(),

                Column::name('CabeceraPedidoCliente.ObservacionesPedido')
                ->label('Observaciones'),
            ];

        }else{

            return [

                Column::callback(['IdPedidoCli'], function ($IdPedidoCli) {
                    return view('clientes.pedidos.accionesPedido', ['IdPedidoCli' => $IdPedidoCli]);
                })
                ->unsortable()
                ->excludeFromExport(),

                Column::name('RazonSocial')
                ->label('Cliente')
                ->searchable(),

                NumberColumn::name('NumeroPedido')
                ->label('Nº')
                ->searchable(),

                Column::name('SeriePedido')
                ->label('Serie'),

                DateColumn::name('FechaPedido')
                ->label('Fecha')                
                ->searchable()
                ->defaultSort('desc'),            
                
                // NumberColumn::name('%Descuento')
                // ->label('Desc. %'),

                Column::callback(['%Descuento'], function ($Descuento) {
                    return view('clientes.ofertas.descuento', ['Descuento' => $Descuento]);
                })->label('Desc. %')
                ->unsortable()
                ->alignRight(),

                // NumberColumn::name('ImporteLiquido')
                // ->label('Importe'),

                Column::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                    return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
                })->label('Importe')
                ->alignRight()
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

                Column::callback(['EjercicioPedido','SeriePedido','NumeroPedido','IdPedidoCli','Estado'], function ($EjercicioPedido, $SeriePedido, $NumeroPedido, $IdPedidoCli, $Estado) {
                    return view('clientes.pedidos.eliminar', 
                    ['EjercicioPedido' => $EjercicioPedido, 'SeriePedido' => $SeriePedido, 'NumeroPedido' => $NumeroPedido, 'IdPedidoCli' => $IdPedidoCli, 'Estado' => $Estado]);
                })->label('Eliminar')
                ->unsortable()
                ->excludeFromExport(),                
                
                Column::callback(['EjercicioPedido','SeriePedido','NumeroPedido','IdPedidoCli'], function ($EjercicioPedido, $SeriePedido, $NumeroPedido, $IdPedidoCli) {
                    return view('clientes.pedidos.duplicarPedido', 
                    ['EjercicioPedido' => $EjercicioPedido, 'SeriePedido' => $SeriePedido, 'NumeroPedido' => $NumeroPedido, 'IdPedidoCli' => $IdPedidoCli]);
                })->label('Duplicar')
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
}
