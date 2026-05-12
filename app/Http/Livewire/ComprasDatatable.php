<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprasDatatable extends LivewireDatatable
{
    public $model = factura::class;
    public $post;


    public function builder(){
                        
        return factura::query()
        ->where('CodigoComisionista', '=', $this->post)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
        //->orderBy('FechaFactura', 'desc');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
    

        return [

            Column::callback(['IdFacturaCli'], function ($IdFacturaCli) {
                return view('clientes.pedidos.accionesFactura', ['IdFacturaCli' => $IdFacturaCli]);
            })
            ->unsortable()
            ->excludeFromExport(),

            Column::name('ResumenCliente.RazonSocial')
            ->label('Nombre'),

            Column::raw("CONCAT(EjercicioFactura,'/',SerieFactura,'/',NumeroFactura) AS nFactura")
            ->label('Nº Factura'),

            DateColumn::name('ResumenCliente.FechaFactura')
            ->label('Fecha')
            ->defaultSort('desc'),
            
            NumberColumn::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                 return view('comisionistas.comprasClientes.numeroFactura', ['ImporteLiquido' => $ImporteLiquido]);
                 })
                 ->alignRight()
                 ->label('Base Imponible') 
                 ->unsortable(),
            
        ];
    }
}
