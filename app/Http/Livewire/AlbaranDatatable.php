<?php

namespace App\Http\Livewire;

use App\Models\CabeceraAlbaranClienteModel;
use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AlbaranDatatable extends LivewireDatatable
{
    public $model = CabeceraAlbaranClienteModel::class;
    public $post;

    public function builder(){
        return CabeceraAlbaranClienteModel::query()
        //->select('NumeroPedido','FechaPedido', 'CodigoCliente', 'RazonSocial', '%Descuento as Descuento', 'ImporteLiquido', 'SeriePedido', 'IdPedidoCli', 'Estado')
        ->join('Clientes', function($join) {                
            $join->on('Clientes.CodigoCliente', '=', 'CabeceraAlbaranCliente.CodigoCliente');
            $join->on('Clientes.CodigoEmpresa', '=', 'CabeceraAlbaranCliente.CodigoEmpresa');
        })
        ->where(function($query){
            $dato = explode('|', $this->post);
            if($dato[1] == "Cliente"){
                $query->where("CabeceraAlbaranCliente.CodigoCliente", $dato[0]);
            }else{
                $query->where("CabeceraAlbaranCliente.CodigoComisionista", $dato[0]);
            }
        })
        // ->where('CabeceraAlbaranCliente.CodigoComisionista', '=', $this->post)
        ->where('CabeceraAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'));
        //->orderby('CabeceraAlbaranCliente.FechaAlbaran', 'desc');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::callback(['IdAlbaranCli'], function ($IdAlbaranCli) {
                return view('clientes.pedidos.accionesAlbaran', ['IdAlbaranCli' => $IdAlbaranCli]);
            })
            ->unsortable()
            ->excludeFromExport(),

            Column::name('Clientes.RazonSocial')
            ->label('Cliente'),

            Column::raw("CONCAT(EjercicioAlbaran,'/',SerieAlbaran,'/',NumeroAlbaran) AS nFactura")
            ->label('Documento'),

            // NumberColumn::name('NumeroPedido')
            // ->label('Nº'),

            // Column::name('SeriePedido')
            // ->label('Serie'),

            DateColumn::name('FechaAlbaran')
            ->label('Fecha')
            ->defaultSort('desc'),            
            
            // NumberColumn::name('%Descuento')
            // ->label('Desc. %'),

            // Column::callback(['%Descuento'], function ($Descuento) {
            //     return view('clientes.ofertas.descuento', ['Descuento' => $Descuento]);
            // })->label('Desc. %'),

            // NumberColumn::name('ImporteLiquido')
            // ->label('Importe'),

            Column::callback(['ImporteLiquido'], function ($ImporteLiquido) {
                return view('clientes.ofertas.importeEfecto', ['ImporteEfecto' => $ImporteLiquido]);
            })->label('Importe')
            ->alignRight()
            ->unsortable(),

            // DateColumn::name('FechaEntrega')
            // ->label('F.Entrega'),

            // Column::name('Estado')
            // ->label('estado'),    
            
            // Column::callback(['Estado'], function ($Estado) {
            //     return view('clientes.ofertas.estado', ['Estado' => $Estado]);
            // })->label('estado'),
            
        ];
    }
}
