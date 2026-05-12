<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Recuento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AccioneseDatatable extends LivewireDatatable
{
    public $model = recuento::class;
    //public $post;
    
    public function builder(){
        
        $comercial = session('codigoComisionista');


        if(session('tipo') != 3){
        
            return recuento::query()        
            ->where('Comercial', '=', session('codigoComisionista'))
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))     
            ->groupBy('recuentoEmpresas.CodigoCliente', 'recuentoEmpresas.Contacto', 'recuentoEmpresas.Identificacion', 'recuentoEmpresas.Comercial','recuentoEmpresas.tipo_contacto');
            // ->orderBy('recuentoEmpresas.CodigoCliente', 'ASC');
            
        }else{

            return recuento::query()        
            ->join('Comisionistas', function($join) {                
                $join->on('recuentoEmpresas.Comercial', '=', 'Comisionistas.CodigoComisionista');                            
                $join->on('recuentoEmpresas.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
            })
            ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            ->where('recuentoEmpresas.CodigoEmpresa', '=', session('codigoEmpresa'))     
            ->groupBy('recuentoEmpresas.CodigoCliente', 'recuentoEmpresas.Contacto', 'recuentoEmpresas.Identificacion', 'recuentoEmpresas.Comercial','recuentoEmpresas.tipo_contacto');
            //->orderBy('recuentoEmpresas.CodigoCliente', 'ASC');
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
            
            Column::callback(['CodigoCliente'], function ($codigoCliente) {
                return view('potenciales.accionesRecuento', ['CodigoCliente' => $codigoCliente]);
            })
            ->unsortable(),

            NumberColumn::name('recuentoEmpresas.CodigoCliente')
            ->label('Id')
            ->alignCenter()
            ->defaultSort('asc'),                             

            Column::name('recuentoEmpresas.Contacto')
            ->label('Nombre'),
  
            Column::name('recuentoEmpresas.Identificacion')
            ->label('Nif/Dni'),

            Column::name('recuentoEmpresas.Comercial')
            ->label('Comercial'),

            Column::name('recuentoEmpresas.tipo_contacto')
            ->label('Tipo'),

            NumberColumn::raw('count(AccionPosicionLc) AS TotalAcciones')
            ->alignRight()
            ->label('Total Acciones'),

            NumberColumn::raw('sum( CASE
                                        WHEN recuentoEmpresas.StatusTareaLc = 0 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Pendientes')
                                ->alignRight()
            ->label('Pendientes'),        
            
            NumberColumn::raw('sum( CASE
                                        WHEN recuentoEmpresas.StatusTareaLc = 1 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Abiertas')
                                ->alignRight()
            ->label('Abiertas'),

            NumberColumn::raw('sum( CASE
                                        WHEN recuentoEmpresas.StatusTareaLc = 3 THEN 1 
                                        ELSE 0
                                    END) 
                                AS Cerradas')
                                ->alignRight()
            ->label('Cerradas'),

            DateColumn::raw('MAX(recuentoEmpresas.FechaFinalLc) AS UltimaFecha')
            ->label('Última Fecha'),

            Column::raw('(SELECT TOP 1 r1.CodigoTemaComercialLc FROM recuentoEmpresas  as r1 WHERE recuentoEmpresas.CodigoCliente = r1.CodigoCliente ORDER BY r1.FechaFinalLc DESC) AS Tema')
            ->label('Último Motivo'),

        ];
    }
}
