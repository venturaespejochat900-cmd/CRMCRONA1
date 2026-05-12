<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Accion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AccionesrDatatable extends LivewireDatatable
{
    public $model = accion::class;
    //public $post;
    
    public function builder(){
        
        $comercial = session('codigoComisionista');


        if(session('tipo') != 3){
        
            return accion::query()
            ->join('Comisionistas', function ($join){
                $join->on('Comisionistas.CodigoComisionista', '=', 'recuentoAcciones.Comercial');
                $join->on('Comisionistas.CodigoEmpresa', '=', 'recuentoAcciones.CodigoEmpresa');
            })
            ->where('Comercial', '=', session('codigoComisionista'))
            ->where('recuentoAcciones.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->groupBy('Comercial', 'Comisionistas.Comisionista', 'recuentoAcciones.Periodo');
            //->orderBy('Periodo', 'DESC');
        } else {

            return accion::query()
            ->join('Comisionistas', function ($join){
                $join->on('Comisionistas.CodigoComisionista', '=', 'recuentoAcciones.Comercial');
                $join->on('Comisionistas.CodigoEmpresa', '=', 'recuentoAcciones.CodigoEmpresa');
            })
            ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            //->where('Comercial', '=', session('codigoComisionista'))
            ->where('recuentoAcciones.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->groupBy('Comercial', 'Comisionistas.Comisionista', 'recuentoAcciones.Periodo');
            //->orderBy('Periodo', 'DESC');
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

            NumberColumn::name('Comercial')
            ->label('Codigo Comisionista')
            ->alignCenter(),                             

            Column::name('Comisionistas.Comisionista')
            ->label('Nombre'),
  
            Column::name('recuentoAcciones.Periodo')
            ->label('Periodo')
            ->alignRight()
            ->defaultSort('desc'),

            NumberColumn::raw('count(recuentoAcciones.Comercial) AS TotalAcciones')
            ->alignRight()
            ->label('Total Acciones'),

            NumberColumn::raw('sum( CASE
                                        WHEN recuentoAcciones.StatusTareaLc = 0 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Pendientes')
            ->alignRight()
            ->label('Pendientes'),        
            
            NumberColumn::raw('sum( CASE
                                        WHEN recuentoAcciones.StatusTareaLc = 1 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Abiertas')
            ->alignRight()
            ->label('Abiertas'),

            NumberColumn::raw('sum( CASE
                                        WHEN recuentoAcciones.StatusTareaLc = 3 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Cerradas')
            ->alignRight()
            ->label('Cerradas'),

        ];
    }
}
