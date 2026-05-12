<?php

namespace App\Http\Livewire;

use App\Models\DropDownArticulo;
use Livewire\Component;

class SearchDropdawnarti2 extends Component
{

    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];
        $searchResults2=[];

        if (strlen($this->search) >= 1) {
            $searchResults2 = DropDownArticulo::selectRaw('CodigoArticulo, DescripcionArticulo, PrecioVenta, PendienteRecibir, StockReservado, UnidadSaldo,IdArticulo')            
            ->where(function ($quiery){
                $where = "CodigoArticulo like '%".$this->search."%' or DescripcionArticulo like '%".$this->search."%' ";
                $quiery->whereRaw($where);
            })
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))                                         
            ->get();
        }

        return view('livewire.search-dropdawnarti2', [
            'searchResults' => collect($searchResults2)
        ]);
    }
}
