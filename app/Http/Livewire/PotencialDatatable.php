<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PotencialDatatable extends LivewireDatatable
{
    public $model = cliente::class;
    //public $post;
    
    public function builder(){

        

        if(session('tipo') == 5){
        
            return cliente::query()
            ->join('Comisionistas', function($join){
                $join->on('Comisionistas.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');
                $join->on('Comisionistas.CodigoComisionista', '=', 'Clientes.CodigoComisionista');
            })
            ->where('CodigoCategoriaCliente_', '=', 'POT')
            ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'));

        }else if(session('tipo') == 3){
        
            return cliente::query()
            ->join('Comisionistas', function($join){
                $join->on('Comisionistas.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');
                $join->on('Comisionistas.CodigoComisionista', '=', 'Clientes.CodigoComisionista');
            })
            ->where('CodigoCategoriaCliente_', '=', 'POT')
            ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))         
            ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));

        }else if(session('tipo')==1 || session('tipo')==2){

            return cliente::query()            
            ->where('CodigoCategoriaCliente_', '=', 'POT')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoComisionista', '=', session('codigoComisionista'));
            
        }
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [ 
            
            Column::callback(['Clientes.CodigoCliente','Clientes.IdCliente'], function ($codigoCliente, $idCliente) {
               return view('potenciales.accionesTabla', ['CodigoCliente' => $codigoCliente],['IdCliente' => $idCliente]);
            })->label('Acciones')
            ->unsortable()
            ->excludeFromExport(),     

            NumberColumn::name('Clientes.CodigoCliente')
            ->label('Codigo Cliente')
            ->alignCenter()
            ->defaultSort('asc'),                             

            Column::name('Clientes.CifDni')
            ->label('NIF'),
  
            Column::name('Clientes.RazonSocial')
            ->label('Nombre'),

            Column::name('Clientes.Domicilio')
            ->label('Domicilio'),

            Column::name('Clientes.CodigoPostal')
            ->label('CodigoPostal'),                         

            Column::name('Clientes.Municipio')
            ->label('Población'),

            Column::name('Clientes.Provincia')
            ->label('Provincia'),

            Column::name('Clientes.Nacion')
            ->label('Nacion'),

            Column::name('Clientes.Telefono')
            ->label('Telefono1'),

            Column::name('Clientes.Telefono2')
            ->label('Telefono2'),

            Column::name('Clientes.EMail1')
            ->label('Email'),

        ];
    }
}
