<?php

namespace App\Http\Livewire;

use App\Models\DropDownArticulo;
use App\Models\Cliente;
use Livewire\Component;

class SearchDropdownarti extends Component
{
    public $search = '';
    public $post;

    public function render()
    {
        $searchResults=[];
        $searchResults2=[];

        if (strlen($this->search) >= 1) {
            // $searchResults = DropDownArticulo::select('Articulos.CodigoArticulo','Articulos.DescripcionArticulo','Articulos.PrecioVenta','AcumuladoPendientes.PendienteRecibir', 'AcumuladoPendientes.StockReservado', 'AcumuladoStock.UnidadSaldo')
            // ->join('AcumuladoStock', function($join){
            //     $join->on('Articulos.CodigoArticulo', '=', 'AcumuladoStock.CodigoArticulo');
            //     $join->on('AcumuladoStock.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
            //     $join->where('AcumuladoStock.Periodo', '=', 99);
            //     $join->where('AcumuladoStock.Ejercicio', '=',  date("Y"));
            //     $join->where('AcumuladoStock.CodigoAlmacen', '=', '01');
            //     $join->where('AcumuladoStock.CodigoEmpresa', '=', session('codigoEmpresa'));
                
            // })
            // ->join('AcumuladoPendientes', function($join){
            //     $join->on('Articulos.CodigoArticulo', '=', 'AcumuladoPendientes.CodigoArticulo');
            //     $join->on('AcumuladoPendientes.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
            //     $join->where('AcumuladoPendientes.CodigoAlmacen', '=', '01');
            //     //$join->where('AcumuladoPendientes.Ejercicio', '=',  date("Y"));
            //     $join->where('AcumuladoPendientes.CodigoEmpresa', '=', session('codigoEmpresa'));
               
            // })
            // // ->where(function ($quiery){
            // //     $quiery ->where('Articulos.CodigoArticulo', 'like', '%'.$this->search.'%')
            // //     ->orWhere('Articulos.DescripcionArticulo', 'like', '%'.$this->search.'%');
            // // })  
            // //$searchResults = Articulo::select('Articulos.CodigoArticulo','Articulos.DescripcionArticulo','Articulos.PrecioVenta')
            // ->where(function ($quiery){

            //     $buscar = explode(" ", $this->search);
            //     $where = "Articulos.CodigoArticulo like '%".$buscar[0]."%' or Articulos.DescripcionArticulo like '%".$buscar[0]."%' ";
            //     for($i = 1; $i < count($buscar); $i++) {
            //         if(!empty($buscar[$i])) {
            //             $where .= "and Articulos.DescripcionArticulo LIKE '%".$buscar[$i]."%' ";
            //         }
            //     }        
            //     $quiery->whereRaw($where);
    
            //     // $quiery ->where('CodigoArticulo','LIKE',$buscar[0]."%")
            //     // ->orWhere('DescripcionArticulo','LIKE','%'.$buscar[0].'%')
            //     // ->orwhereRaw($where);                                
            // })  
            
            
            // ->where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
            // ->where('Articulos.ObsoletoLc', '=', 0)            
            // ->groupBy('Articulos.CodigoArticulo','Articulos.DescripcionArticulo','Articulos.PrecioVenta', 'AcumuladoPendientes.PendienteRecibir', 'AcumuladoPendientes.StockReservado', 'AcumuladoStock.UnidadSaldo' )
            // //->groupBy('Articulos.CodigoArticulo','Articulos.DescripcionArticulo','Articulos.PrecioVenta')
            // ->get();


            $searchResults2 = DropDownArticulo::selectRaw('CodigoArticulo, DescripcionArticulo, PrecioVenta, PendienteRecibir, StockReservado, UnidadSaldo, IdArticulo')            
            ->where(function ($quiery){

                $where = "CodigoArticulo like '%".$this->search."%' or DescripcionArticulo like '%".$this->search."%' ";
                $quiery->whereRaw($where);
            })
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))                                         
            ->get();


            
            

        }
        return view('livewire.search-dropdownarti', [
            'searchResults' => collect($searchResults2)
        ]);
    }
}

?>

