<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Clientes2Datatable extends LivewireDatatable
{
    public $model =cliente::class;

    public function builder(){

        return Cliente::query()            
        ->join('Comisionistas as Co', function($join) {
            $join->on('Co.CodigoComisionista', '=', 'Clientes.CodigoComisionista');    
            $join->on('Co.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                        
        })
        ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('Co.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->Where('Clientes.CodigoCategoriaCliente', '=', 'CLI' )
        //->Where('Clientes.CodigoComisionista', '=', session('codigoComisionista'))
        ->Where('Co.CodigoJefeVenta_', '=', session('codigoComisionista'))
        ;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::callback(['CodigoCliente','IdCliente'], function ($codigoCliente, $IdCliente) {
                return view('clientes.acciones', ['CodigoCliente' => $IdCliente],['IdCliente' => $codigoCliente]);
            })->unsortable(),

            Column::name('codigoCliente')
            ->hide()
            ->defaultSort('asc'),
            
            Column::name('Clientes.CifDni')
            ->label('Nif/Cif'),

            Column::name('Clientes.RazonSocial')
            ->label('Nombre'),

            //DateColumn::name("Clientes.VFechaPrescripcion")
            //->label('Fecha'),
            DateColumn::name('Clientes.VFechaSepa')
            ->label('Fecha SEPA'),

            Column::callback(['Clientes.VPdfSepa'], function($vFechaContrato){
                return view('clientes.contratos.contratoPrescriptores', ['PdfContrato' => $vFechaContrato]);
            })                        
            ->label('PDF SEPA')
            ->unsortable(),
            //->truncate(8),
            
            DateColumn::name('Clientes.VFechaRGPD')
            ->label('Fecha RGPD'),
            
            Column::callback(['Clientes.VPdfRgpd'], function($vFechaRgpd){
                return view('clientes.contratos.RgpdPrescriptores', ['PdfRgpd' => $vFechaRgpd]);
            })                        
            ->label('PDF RGPD')
            ->unsortable(),

            NumberColumn::name('Clientes.CodigoComisionista')
            ->label('Codigo Comisionista'),

            Column::name('Co.Comisionista')
            ->label('Comisionista'),
            
            DateColumn::name('Clientes.FechaAlta')
            ->label('Creado')
        ];
    }
}
