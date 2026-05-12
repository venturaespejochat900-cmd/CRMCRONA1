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

class OfertasDatatable extends LivewireDatatable
{
    public $model = CabeceraOfertaClienteModel::class;
    public $post;

    public function builder(){

        
            return CabeceraOfertaClienteModel::query()
            //->select('NumeroOferta','FechaOferta', 'CodigoCliente', 'RazonSocial', '%Descuento as Descuento', 'ImporteLiquido', 'SerieOferta', 'IdOfertaCli', 'Estado')
            ->where('CodigoCliente', '=', $this->post)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
            //->orderBy('FechaOferta', 'desc');
               

    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::callback(['IdOfertaCli'], function ($IdOfertaCli) {
                return view('clientes.ofertas.accionesOferta', ['IdOfertaCli' => $IdOfertaCli]);
            })->label('Acciones')
            ->unsortable()
            ->excludeFromExport(),

            NumberColumn::name('NumeroOferta')
            ->label('Nº'),

            Column::name('SerieOferta')
            ->label('Serie'),

            DateColumn::name('FechaOferta')
            ->label('Fecha')
            ->defaultSort('desc'),            
            
            // NumberColumn::name('%Descuento')
            // ->label('Desc. %'),

            Column::callback(['%Descuento'], function ($Descuento) {
                return view('clientes.ofertas.descuento', ['Descuento' => $Descuento]);
            })->label('Desc. %')
            ->alignRight()
            ->unsortable(),

            // NumberColumn::name('ImporteLiquido')
            // ->label('Importe'),

            Column::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
            })->label('Importe')
            ->unsortable(),

            // Column::name('Estado')
            // ->label('estado'),  
            
            Column::callback(['Estado'], function ($Estado) {
                return view('clientes.ofertas.estado', ['Estado' => $Estado]);
            })->label('estado')
            ->alignRight()
            ->unsortable()
            ->excludeFromExport(),
            
        ];
    }
}
