<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\prescriptor;
use Livewire\Component;

class SearchDropdowncomi extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];

        if (strlen($this->search) >= 1) {
            $searchResults = Prescriptor::select('CodigoComisionista','Comisionista')
            ->where(function ($quiery){
                $quiery ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
            })
            ->where(function ($quiery){
                $quiery  ->where('CodigoComisionista', 'like', '%'.$this->search.'%')
                         ->orWhere('Comisionista', 'like', '%'.$this->search.'%')
                         ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
            })        
            ->get();            
        }
        return view('livewire.search-dropdowncomi', [
            'searchResults' => collect($searchResults)->take(7)
        ]);
    }
}

?>

