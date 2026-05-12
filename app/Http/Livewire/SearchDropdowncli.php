<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\Cliente;
use Livewire\Component;

class SearchDropdowncli extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];

        if (strlen($this->search) >= 1) {
            $searchResults = Cliente::select('Clientes.CodigoCliente','Clientes.RazonSocial')
            ->join('Comisionistas', function($join){
                $join->on('Clientes.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista')
                     ->on('Clientes.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            })
            ->where(function ($quiery){
                $quiery ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->Orwhere('Comisionistas.CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('Comisionistas.CodigoEmpresa', '=', session('codigoEmpresa'));
            })
            ->where(function ($quiery){
                $quiery  ->where('Clientes.CodigoCliente', 'like', '%'.$this->search.'%')
                         ->orWhere('Clientes.RazonSocial', 'like', '%'.$this->search.'%')
                         ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'));
            })
            ->where('Clientes.BajaEmpresaLc','=', 0)        
            ->get();
        }
        return view('livewire.search-dropdowncli', [
            'searchResults' => collect($searchResults)->take(7)
        ]);
    }
}

?>

