<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\prescriptor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchDropdownpoten extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];

        if (strlen($this->search) >= 1) {

            $searchResults = DB::table('AgendaPotenciales')            
            //->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            
            ->where('CodigoCliente', 'like', '%'.$this->search.'%')
            ->orWhere('RazonSocial', 'like', '%'.$this->search.'%')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->orderBy('CodigoCliente', 'Asc')
            ->get();

        }
        return view('livewire.search-dropdownpoten', [
            'searchResults' => collect($searchResults)->take(12)
        ]);
    }
}

?>

