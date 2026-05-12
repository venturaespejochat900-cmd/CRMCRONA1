<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\prescriptor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchDropdownpre extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];

        // if (strlen($this->search) >= 1) {
        //     $searchResults = Prescriptor::select('CodigoComisionista','Comisionista')
        //     ->where(function ($quiery){
        //         $quiery ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
        //                 ->Orwhere('Comisionistas.CodigoComisionista', '=', session('codigoComisionista'))
        //                 ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
        //     })
        //     ->where(function ($quiery){
        //         $quiery  ->where('CodigoComisionista', 'like', '%'.$this->search.'%')
        //                  ->orWhere('Comisionista', 'like', '%'.$this->search.'%')
        //                  ->where('CodigoEmpresa', '=', session('codigoEmpresa'));
        //     })        
        //     ->get();
        // }
        // return view('livewire.search-dropdownpre', [
        //     'searchResults' => collect($searchResults)->take(7)
        // ]);

        if (strlen($this->search) >= 1) {

            //$searchResults = DB::table('AgendaPotenciales')            
            //->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            $searchResults = DB::table('Clientes')              
            ->where('CodigoCliente', 'like', '%'.$this->search.'%')
            ->orWhere('RazonSocial', 'like', '%'.$this->search.'%')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('RazonSocial', 'not like', "***%")
            ->where('Nombre', 'not like', "***%")
            //->where('CodigoComisionista', '=', session('codigoComisionista'))
            //->where('RazonSocial', '<>', '***%' )            
            ->orderBy('CodigoCliente', 'Asc')
            ->get();

        }
        return view('livewire.search-dropdownpoten', [
            'searchResults' => collect($searchResults)->take(12)
        ]);
    }
}

?>

