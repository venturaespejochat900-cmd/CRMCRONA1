<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FamiliaDropdown extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];
        //$ordenar = "CAST(CodigoFamilia AS INTEGER) ASC";
        if (strlen($this->search) > 0) {
            $searchResults = DB::table('VFamilias')->select('CodigoFamilia','Descripcion')
            ->where('CodigoFamilia', 'like', '%'.$this->search.'%')
            ->orWhere('Descripcion', 'like', '%'.$this->search.'%')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->orderBy('CodigoFamilia', 'ASC')
            ->get();
        }
        return view('livewire.familia-dropdown', [
            'searchResults' => collect($searchResults)->take(7),
            'CodigoComisionista'=> $this->post
        ]);
    }
}

?>

