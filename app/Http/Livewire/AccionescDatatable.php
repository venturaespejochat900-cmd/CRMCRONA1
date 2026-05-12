<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\AccionesComericales;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AccionescDatatable extends LivewireDatatable
{
    public $model = AccionesComericales::class;
    public $post;
    
    public function builder(){
        
        $comercial = session('codigoComisionista');

        if(session('tipo') < 3){

            return AccionesComericales::query()
            ->join('LcComisionistaAcciones', function($join) {
                $join->on('LcComisionistaAcciones.AccionPosicionLc', '=', 'LcComisionistaAgenda.AccionPosicionLc');
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'LcComisionistaAgenda.CodigoComisionista');                            
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'LcComisionistaAgenda.CodigoEmpresa');                            
            })
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            })
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $this->post)
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('LcComisionistaAgenda.CodigoComisionista',  '=', session('codigoComisionista')); 

        } elseif(session('tipo') == 3){

            return AccionesComericales::query()
            ->join('LcComisionistaAcciones', function($join) {
                $join->on('LcComisionistaAcciones.AccionPosicionLc', '=', 'LcComisionistaAgenda.AccionPosicionLc');
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'LcComisionistaAgenda.CodigoComisionista');                            
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'LcComisionistaAgenda.CodigoEmpresa');                            
            })
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            })
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $this->post)
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('LcComisionistaAgenda.CodigoComisionista',  '=', session('codigoComisionista'))
            ->orWhere('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));
             

        }else{
            return AccionesComericales::query()
            ->join('LcComisionistaAcciones', function($join) {
                $join->on('LcComisionistaAcciones.AccionPosicionLc', '=', 'LcComisionistaAgenda.AccionPosicionLc');
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'LcComisionistaAgenda.CodigoComisionista');                            
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'LcComisionistaAgenda.CodigoEmpresa');                            
            })
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAcciones.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');
                $join->on('LcComisionistaAcciones.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            })
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $this->post)
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'));
            //->where('LcComisionistaAgenda.CodigoComisionista',  '=', session('codigoComisionista'));
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

            NumberColumn::name('LcComisionistaAgenda.AccionPosicionLc')
            ->label('Nº Acción')
            ->defaultSort('asc'),

            NumberColumn::name('Comisionistas.Comisionista')
            ->label('Comisionista'),
            
            DateColumn::name('LcComisionistaAgenda.fechaGrabacion')
            ->label('Fecha'),    

            Column::name('LcComisionistaAgenda.CodigoAccionComercialLc')
            ->label('AccionComercial'),

            Column::name('LcComisionistaAgenda.Observaciones')
            //->truncate(20)
            ->label('Objetivo'),

            Column::name('LcComisionistaAcciones.Observaciones')
            //->truncate(20)
            ->label('Resultado'),

        ];
    }
}
