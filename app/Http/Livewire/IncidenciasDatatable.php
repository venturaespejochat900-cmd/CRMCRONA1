<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use Livewire\Component;
use App\Models\Factura;
use App\Models\Incidencia;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class IncidenciasDatatable extends LivewireDatatable
{
    public $model = Incidencia::class;
    public $post;
    //public $deletedAt;

    public function builder(){
        
        return Incidencia::query()
        ->select('CAC.RazonSocial', 'CAC.CodigoCliente', 'CAC.CodigoComisionista', 'ABMS_CabeceraIncidencias.VCodigoEstadoIncidencia', 'ABMS_CabeceraIncidencias.VCodigoIncidencia',
        'ABMS_CabeceraIncidencias.VTipoIncidencia', 'LI.Descripcion', 'ABMS_CabeceraIncidencias.Fecha', 'ABMS_CabeceraIncidencias.VNumeroIncidencia')
        ->join('CabeceraAlbaranCliente as CAC', function($join) {
            $join->on('CAC.EjercicioAlbaran', '=', 'ABMS_CabeceraIncidencias.EjercicioAlbaran');
            $join->on('CAC.SerieAlbaran', '=', 'ABMS_CabeceraIncidencias.SerieAlbaran');
            $join->on('CAC.NumeroAlbaran', '=', 'ABMS_CabeceraIncidencias.NumeroAlbaran');
            $join->on('CAC.CodigoEmpresa', '=', 'ABMS_CabeceraIncidencias.CodigoEmpresa');
            $join->where('CAC.CodigoEmpresa', '=', session('codigoEmpresa'));
            $join->where('CAC.CodigoCliente', '=', $this->post);
        })
        ->join('ABMS_LineasIncidencias as LI', function($join2) {
            $join2->on('LI.EjercicioIncidencia', '=', 'ABMS_CabeceraIncidencias.EjercicioIncidencia');
            $join2->on('LI.VNumeroIncidencia', '=', 'ABMS_CabeceraIncidencias.VNumeroIncidencia');            
            $join2->on('LI.CodigoEmpresa', '=', 'ABMS_CabeceraIncidencias.CodigoEmpresa');
            //$join2->where('CAC.CodigoEmpresa', '=', session('codigoEmpresa'));           
        })
        ->where('ABMS_CabeceraIncidencias.VTipoIncidencia', '=', 'V');

    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [        

            NumberColumn::name('ABMS_CabeceraIncidencias.VNumeroIncidencia')
            ->label('Numero Incidencia')
            ->defaultSort('asc'),

            Column::raw("CONCAT(CAC.CodigoCliente,'-',CAC.RazonSocial) AS cliente")
            ->label('Cliente'),

            // Column::name('ABMS_CabeceraIncidencias.VCodigoEstadoIncidencia')
            // ->label('Estado'),

            Column::callback(['ABMS_CabeceraIncidencias.VCodigoEstadoIncidencia'], function ($vCodigoEstadoIncidencia) {
                return view('clientes.incidencias.vCodigoEstadoIncidencia', ['vCodigoEstadoIncidencia' => $vCodigoEstadoIncidencia]);
            })->label('Estado')
            ->unsortable(),

            // Column::name('ABMS_CabeceraIncidencias.VCodigoIncidencia')
            // ->label('Tipo'),

            Column::callback(['ABMS_CabeceraIncidencias.VCodigoIncidencia'], function ($vCodigoIncidencia) {
                return view('clientes.incidencias.vCodigoIncidencia', ['vCodigoIncidencia' => $vCodigoIncidencia]);
            })->label('Motivo')
            ->unsortable(),

            Column::name('LI.Descripcion')
            ->label('Descripción'),

            NumberColumn::name('CAC.CodigoComisionista')
            ->label('Prescriptor'),

            DateColumn::name('ABMS_CabeceraIncidencias.Fecha')
            ->label('Creado')
            
        ];
    }
}
