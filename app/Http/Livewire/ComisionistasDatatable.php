<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\prescriptor;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Illuminate\Support\Facades\DB;

class ComisionistasDatatable extends LivewireDatatable
{
    public $model = prescriptor::class;

    public function builder(){
        // $subQuery = prescriptor::query()
        // ->select('Comisionista')
        // ->where('CodigoComisionista','=', 2);

        // return prescriptor::query()
        // ->addselect(['Comercial'=>$subQuery])
        // ->where('VComisionista', '=', 2);

    	if(session('tipo') == 5){
        	return prescriptor::query()
        	// ->join('Comisionistas as Comercial', function ($join){
	        //     $join->select('Comercial.Comisionista');
    	    //     $join->on('Comercial.CodigoComisionista', '=', 'Comisionistas.CodigoJefeVenta_');
        	// })
	        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
    	    ->where('CodigoComisionista', '!=', '102')
        	->where('CodigoComisionista', '!=', '100')
	        ->where('CodigoComisionista', '!=', '85')
            ->where(function($query){
    			$query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
			    ->orWhereIn('Comisionistas.CodigoJefeVenta_', function($sub) {
                	$sub->select('CodigoComisionista')
            		->from('Comisionistas')
            		//->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            		->where('CodigoEmpresa', '=', session('codigoEmpresa'));
    			});
			});
        }else{
        	return prescriptor::query()
        	// ->join('Comisionistas as Comercial', function ($join){
	        //     $join->select('Comercial.Comisionista');
    	    //     $join->on('Comercial.CodigoComisionista', '=', 'Comisionistas.CodigoJefeVenta_');
        	// })
	        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
    	    ->where('CodigoComisionista', '!=', '102')
        	->where('CodigoComisionista', '!=', '100')
	        ->where('CodigoComisionista', '!=', '85')
    	    ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));
        }
        

    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            // Column::callback(['CodigoComisionista','IdComisionista'], function ($codigoComisionista, $IdComisionista) {
            //     return view('comisionistas.accionesTabla', ['CodigoComisionista' => $codigoComisionista],['IdComisionista' => $IdComisionista]);
            // })->label('Acciones'),     
            
            Column::callback(['CodigoComisionista','IdComisionista'], function ($codigoComisionista, $IdComisionista) {
                return view('comisionistas.acciones', ['CodigoComisionista' => $codigoComisionista],['IdComisionista' => $IdComisionista]);
            })->label('Acciones')
            ->unsortable()
            ->excludeFromExport(),   

            NumberColumn::name('Comisionistas.CodigoComisionista')
            ->label('Codigo Comisionista')
            ->alignCenter()
            ->defaultSort('asc'),            

            Column::name('Comisionistas.CifDni')
            ->label('NIF'),
  
            Column::name('Comisionistas.Comisionista')
            ->label('Comisionista'),

            Column::name('Comisionistas.Municipio')
            ->label('Población'),
  
            Column::name('Comisionistas.EMail1')
            ->label('Email'),
  
            Column::name('Comisionistas.Telefono')
            ->label('Teléfono'),            
            
            Column::name('Comisionistas.CodigoJefeVenta_')
            ->label('J.Venta'),
        ];
    }
}
