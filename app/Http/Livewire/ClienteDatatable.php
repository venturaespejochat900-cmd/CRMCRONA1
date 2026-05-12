<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClienteDatatable extends LivewireDatatable
{
    public $model = cliente::class;
    public $post;
    
    public function builder(){
        
        return cliente::query()
        ->where('Clientes.CodigoComisionista', '=', $this->post )
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [                       

            NumberColumn::name('Clientes.CodigoCliente')
            ->label('Codigo Cliente')
            ->alignCenter()
            ->defaultSort('asc'),                             

            Column::name('Clientes.CifDni')
            ->label('NIF'),
  
            Column::name('Clientes.RazonSocial')
            ->label('Nombre'),

            Column::name('Clientes.Municipio')
            ->label('Población'),

        ];
    }
}
