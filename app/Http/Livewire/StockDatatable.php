<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Stock;
use App\Models\StockVista;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use PHPUnit\Framework\Constraint\Callback;

class StockDatatable extends LivewireDatatable
{
    public $model = stockVista::class;

    public function builder(){
        

        // return stock::query()
        // ->selectRaw('SUM(AcumuladoStock.UnidadSaldo) As total, AcumuladoStock.CodigoArticulo, Articulo.StockMinimo, Articulo.DescripcionArticulo')
        // ->join('Articulos as Articulo', function ($join){        
        //     $join->on('AcumuladoStock.CodigoArticulo', '=', 'Articulo.CodigoArticulo');
        //     //$join->on('AcumuladoStock.CodigoEmpresa', '=', 'Articulo.CodigoEmpresa');            
        // })
        // ->where('AcumuladoStock.CodigoEmpresa', '=', 1)
        // ->where('AcumuladoStock.Periodo', '=', 99)
        // ->where('AcumuladoStock.CodigoAlmacen', '=', 1)
        // ->where('AcumuladoStock.Ejercicio', '=', date('Y'))
        // //->sum('AcumuladoStock.UnidadSaldo')
        // ->groupBy( 'AcumuladoStock.CodigoArticulo', 'AcumuladoStock.UnidadSaldo', 'Articulo.StockMinimo', 'Articulo.DescripcionArticulo');

        return StockVista::query();

    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [      
            
            Column::callback(['IdArticulo'], function ($IdArticulo) {
                return view('comisionistas.stock.acciones', ['IdArticulo' => $IdArticulo]);
            })->unsortable()
            ->excludeFromExport(),

            NumberColumn::name('CodigoArticulo')
            ->label('Codigo Articulo')
            ->alignCenter()
            ->defaultSort('asc'),

            Column::name('DescripcionArticulo')
            ->label('Descripcion'),    
            
            // NumberColumn::name('StockMinimo')
            // ->label('minimo')
            // ->alignCenter(),

            NumberColumn::callback(['total', 'StockMinimo'], function ($total, $StockMinimo) {
                return view('comisionistas.stock.unidades', ['Unidades' => $total, 'StockMinimo' => $StockMinimo]);
                })->label('Unidades')
                ->alignCenter()
                ->unsortable(),
            
        ];
    }
}
