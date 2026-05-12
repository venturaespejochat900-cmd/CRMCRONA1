<?php

namespace App\Http\Livewire;

use App\Models\CarteraEfectosModel;
use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CobroDatatable extends LivewireDatatable
{
    public $model = CarteraEfectosModel::class;
    public $post;

    public function builder(){

        
            return CarteraEfectosModel::query()
            //->select('Ejercicio','SerieFactura', 'Factura', 'NumeroEfecto', 'NumeroOrdenEfecto', 'FechaEmision', 'FechaVencimiento', 'ImporteEfecto', 'StatusBorrado', 'MovPosicion', 'CodigoClienteProveedor')
            //->where('CodigoClienteProveedor', '=', $this->post)            
            ->where('Prevision', '=', 'C')
            ->where('CarteraEfectos.CodigoEmpresa', '=', session('codigoEmpresa'))   
            ->join('ResumenCliente', function($join){
                $join->on('CarteraEfectos.CodigoEmpresa', '=', 'ResumenCliente.CodigoEmpresa');
                $join->on('CarteraEfectos.CodigoClienteProveedor', '=', 'ResumenCliente.CodigoCliente');
            })
            ->where(function ($quiery){
                $quiery  ->where('CarteraEfectos.CodigoComisionista', '=', $this->post)
                         ->orWhere('ResumenCliente.CodigoJefeVenta_', '=', $this->post);
            })
            //->where('FechaVencimiento','<', getdate())         
            // ->join('ResumenCliente', function($join) {
            //     $join->on('ResumenCliente.MovPosicion', '=', 'CarteraEfectos.MovPosicion');  
            //     $join->on('ResumenCliente.CodigoEmpresa', '=', 'CarteraEfectos.CodigoEmpresa');                                             
            // }) 
            ->groupby('CarteraEfectos.Factura','CarteraEfectos.NumeroEfecto','CarteraEfectos.NumeroOrdenEfecto','CarteraEfectos.SerieFactura','CarteraEfectos.Ejercicio',  
                        'CarteraEfectos.FechaEmision','CarteraEfectos.FechaVencimiento', 'CarteraEfectos.ImporteEfecto', 'CarteraEfectos.StatusBorrado', 'CarteraEfectos.MovPosicion');
            //->orderBy('CarteraEfectos.FechaVencimiento', 'Desc');
               

    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::callback(['MovPosicion'], function ($MovPosicion) {
                return view('clientes.cobros.accionesCobro', ['MovPosicion' => $MovPosicion]);
            })->label('Acciones')
            ->unsortable()
            ->excludeFromExport(),

            // Column::name('CodigoClienteProveedor')
            // ->label('cod'),

            NumberColumn::name('Ejercicio')
            ->label('Ejercicio'),

            Column::name('SerieFactura')
            ->label('Serie'),

            Column::name('Factura')
            ->label('Factura'),

            NumberColumn::name('NumeroEfecto')
            ->label('Numero Efecto'),

            NumberColumn::name('NumeroOrdenEfecto')
            ->label('Orden'),

            DateColumn::name('FechaEmision')
            ->label('Fecha E.'), 
            
            DateColumn::name('FechaVencimiento')
            ->label('Fecha V.')
            ->defaultSort('desc'),  
            
            Column::callback(['ImporteEfecto'], function ($ImporteEfecto) {
                return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteEfecto]);
            })->label('Importe')
            ->alignRight()
            ->unsortable(),

            // NumberColumn::name('ImporteEfecto')
            // ->label('Importe'), 
            
            Column::callback(['StatusBorrado'], function ($StatusBorrado) {
                return view('clientes.cobros.statusBorrado', ['StatusBorrado' => $StatusBorrado]);
            })->label('cobrado')
            ->unsortable()
            ->excludeFromExport(),

            // Column::name('StatusBorrado')
            // ->label('cobrado'),            
            
        ];
    }
}
