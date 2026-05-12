<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use App\Models\Comisionista;
use Livewire\Component;
use App\Models\Factura;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientesDatatable extends LivewireDatatable
{
    public $model = Cliente::class;
    public $post;    

    public function builder(){

        if(session('tipo') == 5){

            if(session('codigoComisionista') == 36 && session('codigoComisionista') == 100){
                return Cliente::query()            
                ->join('Comisionistas as Co', function($join) {
                    $join->on('Co.CodigoComisionista', '=', 'Clientes.CodigoComisionista');  
                    $join->on('Co.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');
                    $join->where('Co.CodigoEmpresa', '=', session('codigoEmpresa'));                             
                })            
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa')) 
                ->Where('Clientes.CodigoCategoriaCliente_', '=', 'CLI' )           
                ->where('Clientes.BajaEmpresaLc','=', 0);
            }else{
                return Cliente::query()            
                ->join('Comisionistas as Co', function($join) {
                    $join->on('Co.CodigoComisionista', '=', 'Clientes.CodigoComisionista');  
                    $join->on('Co.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');
                    $join->where('Co.CodigoEmpresa', '=', session('codigoEmpresa'));                             
                })            
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa')) 
                ->Where('Clientes.CodigoCategoriaCliente_', '=', 'CLI' )           
                //->where(
                //    function ($query){
    
                //        $jefeVentas = Comisionista::select('CodigoComisionista')
                 //       ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                //        ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                //        ->where('IndicadorJefeVenta_', '=', -1)
                //        ->get();
    
                //            $where = "Clientes.CodigoComisionista = ".session('codigoComisionista')."or Co.CodigoJefeVenta_ = ".session('codigoComisionista')." ";
        
                //            foreach($jefeVentas as $comi){
                //                $where .= "or Co.CodigoJefeVenta_ = ".$comi->CodigoComisionista." ";
                //            }
                                            
                //        $query->whereRaw($where);
    
                //})
                ->where('Clientes.BajaEmpresaLc','=', 0);
            }


        } else if(session('tipo') == 3){

            return Cliente::query()
            ->join('Comisionistas as Co', function($join) {
                $join->on('Co.CodigoComisionista', '=', 'Clientes.CodigoComisionista');  
                $join->on('Co.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');
                $join->where('Co.CodigoEmpresa', '=', session('codigoEmpresa'));                             
            })            
            ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa')) 
            ->Where('Clientes.CodigoCategoriaCliente_', '=', 'CLI' )           
            ->where(
                function ($query){
                	$query->whereRaw("Clientes.CodigoComisionista='".session('codigoComisionista')."' 
                    	or Clientes.CodigoComisionista in 
                        	(select CodigoComisionista From Comisionistas where CodigoEmpresa=Clientes.CodigoEmpresa and Comisionistas.CodigoJefeVenta_='".session('codigoComisionista')."')
                        or Clientes.CodigoComisionista in 
                        	(select CodigoComisionista From Comisionistas where CodigoEmpresa=Clientes.CodigoEmpresa and Comisionistas.CodigoJefeVenta_ IN 
                            	(select CodigoComisionista From Comisionistas where CodigoEmpresa=Clientes.CodigoEmpresa and Comisionistas.CodigoJefeVenta_='".session('codigoComisionista')."'))");
                    //$query ->where('Clientes.CodigoComisionista', '=', session('codigoComisionista'))
                           //->orWhere('Co.CodigoJefeVenta_', '=', session('codigoComisionista'));
            })
            ->where('Clientes.BajaEmpresaLc','=', 0);

        } else if(session('tipo') == 1 || session('tipo')==2){

            return Cliente::query()
            ->join('Comisionistas as Co', function($join) {
                $join->on('Co.CodigoComisionista', '=', 'Clientes.CodigoComisionista');              
            })
            ->where('Co.CodigoComisionista', '=', session('codigoComisionista'))
            ->Where('Clientes.CodigoCategoriaCliente_', '=', 'CLI' )
            ->where('Co.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Clientes.BajaEmpresaLc','=', 0);
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

            // Column::callback(['CodigoCliente','IdCliente'], function ($codigoCliente, $IdCliente) {
            //     return view('clientes.accionesTabla', ['CodigoCliente' => $IdCliente],['IdCliente' => $codigoCliente]);
            // })->label('Acciones'),

            Column::callback(['CodigoCliente','IdCliente'], function ($codigoCliente, $IdCliente) {
                return view('clientes.acciones', ['CodigoCliente' => $IdCliente],['IdCliente' => $codigoCliente]);
            })->unsortable()
            ->excludeFromExport(),
            
            Column::name('Clientes.CodigoCliente')
            ->label('Codigo Cliente')
            ->defaultSort('asc'),

            Column::name('Clientes.Nombre')
            ->label('Nombre Comercial'),

            Column::name('Clientes.RazonSocial')
            ->label('Razón Social'),

            Column::name('Clientes.CifDni')
            ->label('Nif/Cif'),

            //DateColumn::name("Clientes.VFechaPrescripcion")
            //->label('Fecha'),
            // DateColumn::name('Clientes.VFechaSepa')
            // ->label('Fecha SEPA'),

            Column::callback(['Clientes.VPdfSepa'], function($vFechaContrato){
                return view('clientes.contratos.contratoPrescriptores', ['PdfContrato' => $vFechaContrato]);
            })                        
            ->label('PDF SEPA')
            ->unsortable()
            ->excludeFromExport(),
            //->truncate(8),
            
            // DateColumn::name('Clientes.VFechaRGPD')
            // ->label('Fecha RGPD'),
            
            Column::callback(['Clientes.VPdfRgpd'], function($vFechaRgpd){
                return view('clientes.contratos.RgpdPrescriptores', ['PdfRgpd' => $vFechaRgpd]);
            })                        
            ->label('PDF RGPD')
            ->unsortable()
            ->excludeFromExport(),

            Column::name('Clientes.Municipio')
            ->label('Poblacion'),

            Column::name('Clientes.Telefono')
            ->label('Telefono'),

            NumberColumn::name('Clientes.CodigoComisionista')
            ->label('Codigo Comisionista'),

            Column::name('Co.Comisionista')
            ->label('Comisionista'),
            
            //DateColumn::name('Clientes.FechaAlta')
            //->label('Creado')
            
        ];
    }
}
