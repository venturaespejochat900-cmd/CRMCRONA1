<?php

namespace App\Http\Livewire;

use App\Models\CabeceraOfertaClienteModel;
use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OfertascomiDatatable extends LivewireDatatable
{
    public $model = CabeceraOfertaClienteModel::class;
    public $post;

    public function builder(){

            if(session('tipo') == 5){

                return CabeceraOfertaClienteModel::query()
                // ->selectRaw("(CASE
                //     WHEN Estado = 0 THEN 'Pendiente'
                //     WHEN Estado = 1 THEN 'Bloqueado'
                //     WHEN Estado = 2 THEN 'Servido'
                //     ELSE 'Modificable'
                // END) AS Tipo")
                ->join('Comisionistas', function($join) {                
                    $join->on('Comisionistas.CodigoComisionista', '=', 'CabeceraOfertaCliente.CodigoComisionista');
                    $join->on('Comisionistas.CodigoEmpresa', '=', 'CabeceraOfertaCliente.CodigoEmpresa');
                })
                ->where('CabeceraOfertaCliente.CodigoEmpresa', '=', session('codigoEmpresa'));
                //->where('Comisionistas.CodigoJefeVenta_', '=', $this->post);

            }else if(session('tipo') == 3){

                return CabeceraOfertaClienteModel::query()
                // ->selectRaw("(CASE
                //     WHEN Estado = 0 THEN 'Pendiente'
                //     WHEN Estado = 1 THEN 'Bloqueado'
                //     WHEN Estado = 2 THEN 'Servido'
                //     ELSE 'Modificable'
                // END) AS Tipo")
                ->join('Comisionistas', function($join) {                
                    $join->on('Comisionistas.CodigoComisionista', '=', 'CabeceraOfertaCliente.CodigoComisionista');
                    $join->on('Comisionistas.CodigoEmpresa', '=', 'CabeceraOfertaCliente.CodigoEmpresa');
                })
                ->where('CabeceraOfertaCliente.CodigoEmpresa', '=', session('codigoEmpresa'))            
                ->where('Comisionistas.CodigoJefeVenta_', '=', $this->post);


            }else{
            
            return CabeceraOfertaClienteModel::query()
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

                Column::callback(['IdOfertaCli'], function ($IdPedidoCli) {
                    return view('clientes.ofertas.accionesOferta', ['IdOfertaCli' => $IdPedidoCli]);
                })
                ->unsortable()
                ->excludeFromExport(),

                Column::name('RazonSocial')
                ->label('Cliente')
                ->searchable(),

                NumberColumn::name('NumeroOferta')
                ->label('Nº')
                ->searchable(),

                Column::name('SerieOferta')
                ->label('Serie'),

                DateColumn::name('FechaOferta')
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

                // DateColumn::name('FechaEntrega')
                // ->label('F.Entrega'),

                // Column::name('Estado')
                // ->label('estado')
                // ->hide()
                // ->defaultSort('asc'),    
                
                Column::callback(['Estado','IdOfertaCli'], function ($Estado, $IdPedidoCli) {
                    return view('oferta.estado', ['Estado' => $Estado, 'IdOfertaCli' => $IdPedidoCli]);
                })->label('estado')
                ->unsortable()
                ->excludeFromExport(),

                Column::callback(['Estado','IdOfertaCli', 'VEstadoCRM'], function ($Estado, $IdOfertaCli, $VEstadoCRM) {
                    return view('clientes.ofertas.estado2', ['Estado' => $Estado, 'IdOfertaCli' => $IdOfertaCli, 'VEstadoCRM'=>$VEstadoCRM]);
                })->label('')
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
                
            ];

        }else{

            return [

                Column::callback(['IdOfertaCli'], function ($IdPedidoCli) {
                    return view('clientes.ofertas.accionesOferta', ['IdOfertaCli' => $IdPedidoCli]);
                })
                ->unsortable()
                ->excludeFromExport(),

                Column::name('RazonSocial')
                ->label('Cliente')
                ->searchable(),

                NumberColumn::name('NumeroOferta')
                ->label('Nº')
                ->searchable(),

                Column::name('SerieOferta')
                ->label('Serie'),

                DateColumn::name('FechaOferta')
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

                // DateColumn::name('FechaEntrega')
                // ->label('F.Entrega'),

                // Column::name('Estado')
                // ->label('estado'),    
                
                Column::callback(['Estado','IdOfertaCli'], function ($Estado, $IdPedidoCli) {
                    return view('oferta.estado', ['Estado' => $Estado, 'IdOfertaCli' => $IdPedidoCli]);
                })->label('estado')
                ->unsortable()
                ->excludeFromExport(),

                Column::callback(['Estado','IdOfertaCli', 'VEstadoCRM'], function ($Estado, $IdOfertaCli, $VEstadoCRM) {
                    return view('clientes.ofertas.estado2', ['Estado' => $Estado, 'IdOfertaCli' => $IdOfertaCli, 'VEstadoCRM'=>$VEstadoCRM]);
                })->label('')
                ->unsortable()
                ->excludeFromExport(),

                Column::raw("(CASE
                    WHEN Estado = 0 THEN 'Pendiente'
                    WHEN Estado = 1 THEN 'Bloqueado'
                    WHEN Estado = 2 THEN 'Servido'
                    ELSE 'Modificable'
                    END) AS Estado2")
                ->label('des. Est'),
                
            ];
        }
    }
}
