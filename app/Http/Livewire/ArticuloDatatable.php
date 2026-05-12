<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\LineaFactura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ArticuloDatatable extends LivewireDatatable
{
    public $model = lineafactura::class;
    public $post;

    public function builder(){


        return lineafactura::query()
        ->select('LineasAlbaranCliente.EjercicioFactura', 'LineasAlbaranCliente.CodigoEmpresa', 'LineasAlbaranCliente.SerieFactura', 
        'LineasAlbaranCliente.NumeroFactura', 'LineasAlbaranCliente.CodigoArticulo', 'LineasAlbaranCliente.%Descuento', 'LineasAlbaranCliente.Precio', 
        'LineasAlbaranCliente.DescripcionArticulo', 'ResumenCliente.FechaFactura')
        ->join('ResumenCliente', function($join) {
            $join->select('ResumenCliente.FechaFactura', 'ResumenCliente.CodigoCliente', 'ResumenCliente.RazonSocial');
            $join->on('ResumenCliente.EjercicioFactura', '=', 'LineasAlbaranCliente.EjercicioFactura');
            $join->on('ResumenCliente.CodigoEmpresa', '=', 'LineasAlbaranCliente.CodigoEmpresa');
            $join->on('ResumenCliente.SerieFactura', '=', 'LineasAlbaranCliente.SerieFactura');
            $join->on('ResumenCliente.NumeroFactura', '=', 'LineasAlbaranCliente.NumeroFactura');
        })
        ->join('Articulos', function($join){
            $join->on('LineasAlbaranCliente.CodigoArticulo', '=', 'Articulos.CodigoArticulo');
            $join->on('LineasAlbaranCliente.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
        })
        ->where('LineasAlbaranCliente.EjercicioAlbaran', '>=', (date("Y")-2) )
        ->where(function($query){
            if ($this->post == "021916" || $this->post == "021917") {
                $query->where('ResumenCliente.CodigoCliente', '=', '021916')
                ->orWhere('ResumenCliente.CodigoCliente', '=', '021917');
            }else{
                $query->where('ResumenCliente.CodigoCliente', '=', $this->post);
            }
        })
        ->where('LineasAlbaranCliente.CodigoArticulo', '<>', 'PORTES' )
        ->where('LineasAlbaranCliente.DescripcionArticulo', '<>', 'PORTES' )
        ->where('LineasAlbaranCliente.NumeroFactura', '>', 0)
        ->where('LineasAlbaranCliente.Precio', '<>', 0)
        ->where('Articulos.ObsoletoLc', '=', 0)
        ->where('LineasAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'));
        // ->orderBy('ResumenCliente.FechaFactura', 'desc');

        // $subquery = DB::table('LineasAlbaranCliente')
        //     ->select(
        //         'LineasAlbaranCliente.CodigoArticulo',
        //         'LineasAlbaranCliente.FechaRegistro',
        //         'LineasAlbaranCliente.NumeroFactura',
        //         DB::raw('ROW_NUMBER() OVER (PARTITION BY LineasAlbaranCliente.CodigoArticulo ORDER BY LineasAlbaranCliente.FechaRegistro DESC) as rn')
        //     )
        //     ->join('ResumenCliente', function ($join) {
        //         $join->on('ResumenCliente.EjercicioFactura', '=', 'LineasAlbaranCliente.EjercicioFactura')
        //             ->on('ResumenCliente.CodigoEmpresa', '=', 'LineasAlbaranCliente.CodigoEmpresa')
        //             ->on('ResumenCliente.SerieFactura', '=', 'LineasAlbaranCliente.SerieFactura')
        //             ->on('ResumenCliente.NumeroFactura', '=', 'LineasAlbaranCliente.NumeroFactura');
        //     })
        //     ->join('Articulos', function ($join) {
        //         $join->on('LineasAlbaranCliente.CodigoArticulo', '=', 'Articulos.CodigoArticulo')
        //             ->on('LineasAlbaranCliente.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
        //     })
        //     ->where('ResumenCliente.CodigoCliente', '=', $this->post)
        //     ->where('LineasAlbaranCliente.CodigoArticulo', '<>', 'PORTES')
        //     ->where('LineasAlbaranCliente.DescripcionArticulo', '<>', 'PORTES')
        //     ->where('LineasAlbaranCliente.NumeroFactura', '>', 0)
        //     ->where('LineasAlbaranCliente.Precio', '<>', 0)
        //     ->where('Articulos.ObsoletoLc', '=', 0)
        //     ->where('LineasAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
        //     ->groupBy('LineasAlbaranCliente.CodigoArticulo', 'LineasAlbaranCliente.FechaRegistro', 'LineasAlbaranCliente.NumeroFactura');

        // return LineaFactura::query()
        //     ->select(
        //         'LineasAlbaranCliente.EjercicioFactura',
        //         'LineasAlbaranCliente.CodigoEmpresa',
        //         'LineasAlbaranCliente.SerieFactura',
        //         'LineasAlbaranCliente.NumeroFactura',
        //         'LineasAlbaranCliente.CodigoArticulo',
        //         'LineasAlbaranCliente.%Descuento',
        //         'LineasAlbaranCliente.Precio',
        //         'LineasAlbaranCliente.DescripcionArticulo',
        //         'LineasAlbaranCliente.FechaRegistro'
        //     )
        //     ->join('ResumenCliente', function ($join) {
        //         $join->on('ResumenCliente.EjercicioFactura', '=', 'LineasAlbaranCliente.EjercicioFactura')
        //             ->on('ResumenCliente.CodigoEmpresa', '=', 'LineasAlbaranCliente.CodigoEmpresa')
        //             ->on('ResumenCliente.SerieFactura', '=', 'LineasAlbaranCliente.SerieFactura')
        //             ->on('ResumenCliente.NumeroFactura', '=', 'LineasAlbaranCliente.NumeroFactura');
        //     })
        //     ->join('Articulos', function ($join) {
        //         $join->on('LineasAlbaranCliente.CodigoArticulo', '=', 'Articulos.CodigoArticulo')
        //             ->on('LineasAlbaranCliente.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
        //     })
        //     ->joinSub($subquery, 'subquery', function ($join) {
        //         $join->on('LineasAlbaranCliente.CodigoArticulo', '=', 'subquery.CodigoArticulo')
        //             ->on('LineasAlbaranCliente.FechaRegistro', '=', 'subquery.FechaRegistro')
        //             ->on('LineasAlbaranCliente.NumeroFactura', '=', 'subquery.NumeroFactura');
        //     })
        //     ->where('ResumenCliente.CodigoCliente', '=', '004492')
        //     ->where('LineasAlbaranCliente.CodigoArticulo', '<>', 'PORTES')
        //     ->where('LineasAlbaranCliente.DescripcionArticulo', '<>', 'PORTES')
        //     ->where('LineasAlbaranCliente.NumeroFactura', '>', 0)
        //     ->where('LineasAlbaranCliente.Precio', '<>', 0)
        //     ->where('Articulos.ObsoletoLc', '=', 0)
        //     ->where('LineasAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
        //     ->where('subquery.rn', '=', 1)
        //     ->orderBy('LineasAlbaranCliente.FechaRegistro', 'desc');


    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::checkbox('LineasAlbaranCliente.LineasPosicion')
            ->excludeFromExport(),

            // NumberColumn::name('ResumenCliente.CodigoCliente')
            // ->label('CodigoCliente'),

            // Column::name('ResumenCliente.RazonSocial')
            // ->label('Cliente'),

            DateColumn::name('ResumenCliente.FechaFactura')
            ->label('Fecha')
            ->defaultSort('desc'),

            Column::raw("CONCAT(LineasAlbaranCliente.EjercicioFactura,'/',LineasAlbaranCliente.SerieFactura,'/',LineasAlbaranCliente.NumeroFactura) AS nFactura")
            ->label('Documento'),

            Column::name('LineasAlbaranCliente.CodigoArticulo')
            ->label('Articulo'),       

            Column::name('LineasAlbaranCliente.DescripcionArticulo')
            ->label('Descripcion'),

            NumberColumn::callback(['LineasAlbaranCliente.Precio'], function ($Precio){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio]);
                })->alignRight()
                ->label('Precio')
                ->unsortable(),
            
            NumberColumn::callback(['LineasAlbaranCliente.%Descuento'], function ($descuento){
                return view('clientes.ofertas.descuento', ['Descuento' => $descuento]);
                })->alignRight()
                ->label('Descuentos')
                ->unsortable(),
            
            NumberColumn::callback(['LineasAlbaranCliente.Unidades'], function ($Unidades) {
                return view('clientes.ofertas.unidades', ['Unidades' => $Unidades]);
                })->alignRight()
                ->label('Unidades')
                ->unsortable(),
        ];
    }
}
