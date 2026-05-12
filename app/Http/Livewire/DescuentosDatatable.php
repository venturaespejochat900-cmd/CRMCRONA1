<?php

namespace App\Http\Livewire;

use App\Models\Descuento;
use Livewire\Component;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class DescuentosDatatable extends LivewireDatatable
{
    public $model = descuento::class;
    public $post;

    public function builder(){
        
        // $cliente = DB::table('Clientes')
        // ->select('VCentroVendedor')
        // ->where('CodigoComisionista', '=', $this->post)
        // ->get();    

        return descuento::query()
        ->select('descuentoPrecioFamilia.CodigoFamilia', 'descuentoPrecioFamilia.Descuento', 'descuentoPrecioFamilia.CodigoArticulo', 'descuentoPrecioFamilia.Precio',
        'descuentoPrecioFamilia.FechaInicio','descuentoPrecioFamilia.FechaFinal', 'Articulos.DescripcionArticulo', 'VFamilias.Descripcion')
        ->leftJoin('Articulos',function ($join){
            $join->on('descuentoPrecioFamilia.CodigoArticulo', '=', 'Articulos.CodigoArticulo');
            
        })
        ->leftJoin('VFamilias',function ($join){
            $join->on('descuentoPrecioFamilia.CodigoFamilia', '=', 'VFamilias.CodigoFamilia');
            
        })        
        ->where('descuentoPrecioFamilia.CodigoCliente', '=', $this->post)
        ->where('descuentoPrecioFamilia.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('descuentoPrecioFamilia.StatusActivo', '=', -1 );        

    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            
            Column::name('descuentoPrecioFamilia.CodigoArticulo')
            ->label('Articulo')            
            ->hide(),

            Column::name('Articulos.DescripcionArticulo')
            ->label('Descripcion Articulo')            
            ->hide(),

            Column::callback(['descuentoPrecioFamilia.CodigoArticulo'], function ($CodigoArticulo) {
                return view('clientes.tarifas.articuloTarifa', ['CodigoArticulo' => $CodigoArticulo]);
                })
                ->alignCenter()
                ->label('Articulo')
                ->unsortable(),
            
            NumberColumn::callback(['descuentoPrecioFamilia.Precio'], function ($Precio) {
                return view('clientes.tarifas.precioTarifa', ['Precio' => $Precio]);
                })
                ->alignRight()
                ->label('Precio')
                ->unsortable(),

            Column::name('descuentoPrecioFamilia.CodigoFamilia')
            ->label('Familia')
            ->hide(),   
            
            Column::name('VFamilias.Descripcion')
            ->label('Descripcion Familia')
            ->hide(),  

            Column::callback(['descuentoPrecioFamilia.CodigoFamilia'], function ($CodigoFamilia) {
                return view('clientes.tarifas.familiaTarifa', ['CodigoFamilia' => $CodigoFamilia]);
                })
                ->alignCenter()
                ->label('Familia')
                ->unsortable(),

            NumberColumn::callback(['descuentoPrecioFamilia.Descuento'], function ($Descuento) {
                return view('clientes.tarifas.descuentoTarifa', ['Descuento' => $Descuento]);
                })
                ->alignRight()
                ->label('Descuento') 
                ->unsortable(),
            
            
            DateColumn::name('descuentoPrecioFamilia.FechaInicio')
            ->label('Fecha Inicio'),

            DateColumn::name('descuentoPrecioFamilia.FechaFinal')
            ->label('Fecha Fin')
            ->defaultSort('desc'),
        ];
    }
}
