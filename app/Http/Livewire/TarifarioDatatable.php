<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\TarifaPrecio;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TarifarioDatatable extends LivewireDatatable
{
    public $model = TarifaPrecio::class;
    public $post;

    public function builder(){


        return tarifaPrecio::query()
        ->join('Articulos', function($join) {            
            $join->on('Articulos.CodigoEmpresa', '=', 'TarifaPrecio.CodigoEmpresa');                        
            $join->on('Articulos.CodigoArticulo', '=', 'TarifaPrecio.CodigoArticulo');                        
        })
        ->where('TarifaPrecio.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('TarifaPrecio.Tarifa', '=', 99)
        ->where('TarifaPrecio.FechaFinal', '>', date('Y-m-d'))
        ->where('TarifaPrecio.FechaInicio', '<', date('Y-m-d'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            NumberColumn::name('Articulos.CodigoArticulo')
            ->label('Código Articulo')
            ->defaultSort('asc'),

            Column::name('Articulos.DescripcionArticulo')
            ->label('Descripción'),        

            NumberColumn::callback(['HastaUnidades1'], function ($Precio){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio]);
                })->alignRight()
            ->label('Unidades 1')
            ->unsortable(),

            NumberColumn::callback(['Precio1'], function ($Precio1){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio1]);
                })->alignRight()
            ->label('Precio 1')
            ->unsortable(),

            NumberColumn::callback(['HastaUnidades2'], function ($HastaUnidades2){
                return view('clientes.ofertas.descuento', ['Descuento' => $HastaUnidades2]);
                })->alignRight()
            ->label('Unidades 2')
            ->unsortable(),

            NumberColumn::callback(['Precio2'], function ($Precio2){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio2]);
                })->alignRight()
            ->label('Precio 2')
            ->unsortable(),

            NumberColumn::callback(['HastaUnidades3'], function ($HastaUnidades3){
                return view('clientes.ofertas.descuento', ['Descuento' => $HastaUnidades3]);
                })->alignRight()
            ->label('Unidades 3')
            ->unsortable(),

            NumberColumn::callback(['Precio3'], function ($Precio3){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio3]);
                })->alignRight()
            ->label('Precio 3')
            ->unsortable(),
            
            NumberColumn::callback(['HastaUnidades4'], function ($HastaUnidades4){
                return view('clientes.ofertas.descuento', ['Descuento' => $HastaUnidades4]);
                })->alignRight()
            ->label('Unidades 4')
            ->unsortable(),

            NumberColumn::callback(['Precio4'], function ($Precio4){
                return view('clientes.ofertas.descuento', ['Descuento' => $Precio4]);
                })->alignRight()
            ->label('Precio 4')
            ->unsortable(),


        ];
    }
}
