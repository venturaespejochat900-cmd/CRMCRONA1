<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\Cliente;
use Livewire\Component;

class SearchDropdownart extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];

        if (strlen($this->search) >= 1) {
            $searchResults = Articulo::select('Articulos.CodigoArticulo','Articulos.DescripcionArticulo')
            ->join('AcumuladoStock', function($join){
                $join->on('Articulos.CodigoArticulo', '=', 'AcumuladoStock.CodigoArticulo');
                $join->where('AcumuladoStock.Periodo', '=', 99);
                $join->where('AcumuladoStock.Ejercicio', '=',  date("Y"));
                $join->where('AcumuladoStock.CodigoEmpresa', '=', session('codigoEmpresa'));
            })
            ->where('Articulos.CodigoArticulo', 'like', '%'.$this->search.'%')
            ->orWhere('Articulos.DescripcionArticulo', 'like', '%'.$this->search.'%')
            ->where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->groupBy('Articulos.CodigoArticulo','Articulos.DescripcionArticulo')
            ->get();
        }
        return view('livewire.search-dropdownart', [
            'searchResults' => collect($searchResults)->take(7)
        ]);
    }
}

?>

