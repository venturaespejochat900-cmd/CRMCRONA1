<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Stock;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class StockiDatatable extends LivewireDatatable
{
    public $model = stock::class;

    public function builder(){
        // $subQuery = prescriptor::query()
        // ->select('Comisionista')
        // ->where('CodigoComisionista','=', 2);

        // return prescriptor::query()
        // ->addselect(['Comercial'=>$subQuery])
        // ->where('VComisionista', '=', 2);

        return stock::query()
        ->select('UnidadSaldo', 'AcumuladoStock.CodigoArticulo', 'Articulo.StockMinimo')
        ->join('Articulos as Articulo', function ($join){
            $join->select('Articulo.DescripcionArticulo');
            $join->on('AcumuladoStock.CodigoArticulo', '=', 'Articulo.CodigoArticulo');
        })
        ->whereRaw('AcumuladoStock.UnidadSaldo <= (SELECT Articulos.StockMinimo from Articulos where Articulos.CodigoArticulo = AcumuladoStock.CodigoArticulo and CodigoEmpresa = '.session('codigoEmpresa').')')        
        ->where('AcumuladoStock.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('AcumuladoStock.Periodo', '=', 99)
        //->where('AcumuladoStock.CodigoAlmacen', '=', '')
        ->where('AcumuladoStock.Ejercicio', '=', date('Y'));

    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [                      

            NumberColumn::name('AcumuladoStock.CodigoArticulo')
            ->label('Codigo Articulo')
            ->alignCenter()
            ->defaultSort('asc'),

            Column::name('Articulo.DescripcionArticulo')
            ->label('Descripcion'),                        

            NumberColumn::callback(['AcumuladoStock.UnidadSaldo', 'Articulo.StockMinimo'], function ($Unidades, $StockMinimo) {
                return view('comisionistas.stock.unidades', ['Unidades' => $Unidades, 'StockMinimo'=>$StockMinimo]);
                })->label('Unidades')
                ->alignCenter()
                ->unsortable(),
            
        ];
    }
}
