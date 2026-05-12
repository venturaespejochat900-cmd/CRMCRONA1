<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';
    //public $post;

    public function render()
    {
        $searchResults=[];

        if (strlen($this->search) >= 2) {
            $searchResults = Articulo::select('CodigoArticulo','DescripcionArticulo','PrecioVenta')
            ->where('CodigoArticulo', 'like', '%'.$this->search.'%')
            ->orWhere('DescripcionArticulo', 'like', '%'.$this->search.'%')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        }
        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(10)
            //'CodigoComisionista'=> $this->post
        ]);
    }
}

?>

