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

class LarticuloDatatable extends LivewireDatatable
{
    public $model = stockVista::class;
    public $model2 = stockVista2::class;

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
        // return 
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
                return view('pedido.aniadirLinea', ['IdArticulo' => $IdArticulo]);
            })->unsortable(),

            NumberColumn::name('CodigoArticulo')
            ->label('Codigo Articulo')
            ->alignCenter()
            ->defaultSort('asc'),

            Column::name('DescripcionArticulo')
            ->label('Descripcion'),    
            
            // NumberColumn::name('StockMinimo')
            // ->label('minimo')
            // ->alignCenter(),

            Column::callback(['PrecioVenta'], function ($ImporteLiquido) {
                return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
            })->label('Precio Venta')                
            ->unsortable()
            ->alignRight(),

            // NumberColumn::callback(['total', 'StockMinimo'], function ($total, $StockMinimo) {
            //     return view('comisionistas.stock.unidades', ['Unidades' => $total, 'StockMinimo' => $StockMinimo]);
            // })->label('Unidades')
            // ->alignCenter()
            // ->unsortable(),
            
            NumberColumn::callback(['total', 'StockMinimo', 'PendienteServir', 'PendienteRecibir'], function ($total, $StockMinimo, $PendienteServir, $PendienteRecibir) {
                return view('comisionistas.stock.unidades2', ['Unidades' => $total, 'StockMinimo' => $StockMinimo, 'PendienteServir' => $PendienteServir, 'PendienteRecibir' => $PendienteRecibir]);
            })->label('Unidades')
            ->alignCenter()
            ->unsortable(),
            
        ];
    }
}
