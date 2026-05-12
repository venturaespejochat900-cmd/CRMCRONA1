<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\LineaFactura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LineaDatatable extends LivewireDatatable
{
    public $model = lineafactura::class;
    public $post;

    public function builder(){


        return lineafactura::query()
        ->select('LineasAlbaranCliente.EjercicioFactura', 'LineasAlbaranCliente.CodigoEmpresa', 'LineasAlbaranCliente.SerieFactura', 
        'LineasAlbaranCliente.NumeroFactura', 'LineasAlbaranCliente.CodigoComisionista', 'LineasAlbaranCliente.CodigoArticulo', 
        'LineasAlbaranCliente.DescripcionArticulo', 'ResumenCliente.FechaFactura')
        ->join('ResumenCliente', function($join) {
            $join->select('ResumenCliente.FechaFactura', 'ResumenCliente.CodigoCliente', 'ResumenCliente.RazonSocial');
            $join->on('ResumenCliente.EjercicioFactura', '=', 'LineasAlbaranCliente.EjercicioFactura');
            $join->on('ResumenCliente.CodigoEmpresa', '=', 'LineasAlbaranCliente.CodigoEmpresa');
            $join->on('ResumenCliente.SerieFactura', '=', 'LineasAlbaranCliente.SerieFactura');
            $join->on('ResumenCliente.NumeroFactura', '=', 'LineasAlbaranCliente.NumeroFactura');
        })
        ->where('LineasAlbaranCliente.CodigoComisionista', '=', $this->post )
        ->where('LineasAlbaranCliente.CodigoArticulo', '<>', 'PORTES' )
        ->where('LineasAlbaranCliente.NumeroFactura', '>', 0)
        ->where('LineasAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'));
        //->orderBy('ResumenCliente.FechaFactura', 'desc');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            // NumberColumn::name('ResumenCliente.CodigoCliente')
            // ->label('CodigoCliente'),

            Column::name('ResumenCliente.RazonSocial')
            ->label('Cliente'),

            DateColumn::name('ResumenCliente.FechaFactura')
            ->label('Fecha')
            ->defaultSort('desc'),

            Column::raw("CONCAT(LineasAlbaranCliente.EjercicioFactura,'/',LineasAlbaranCliente.SerieFactura,'/',LineasAlbaranCliente.NumeroFactura) AS nFactura")
            ->label('Documento'),

            Column::name('LineasAlbaranCliente.CodigoArticulo')
            ->label('Articulo'),       

            Column::name('LineasAlbaranCliente.DescripcionArticulo')
            ->label('Descripcion'),   
            
            NumberColumn::callback(['LineasAlbaranCliente.Unidades'], function ($Unidades) {
                return view('clientes.ofertas.unidades', ['Unidades' => $Unidades]);
                })->alignRight()
                ->label('Unidades')
                ->unsortable(),
        ];
    }
}
