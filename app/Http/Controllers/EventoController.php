<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Evento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('evento.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $conta = DB::table('lsysContadores')
        ->select('sysContadorValor')
        ->where('sysNombreContador', '=', 'ACCIONPOS_C')
        ->where('sysAplicacion', '=', 'PRV')
        ->get();
        
        $hora = strtotime(request()->input('startH'));
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;
        //return $hora3;
        $hora2 = strtotime(request()->input('endH'));
        $hora4 = strtotime('00:00');
        $fin = ($hora2-$hora4)/86400;

        $nuevoCliente = DB::table('LcComisionistaAgenda')        
        ->insert([
            'CodigoEmpresa'=>session('codigoEmpresa'),            
            'CodigoComisionista'=>session('codigoComisionista'),
            'AccionPosicionLc'=>$conta[0]->sysContadorValor,
            'FechaInicialLc'=>request()->input('start'),
            'HoraInicialLc'=>$inicio,
            'FechaFinalLc'=>request()->input('end'),
            'HoraFinalLc'=>$fin,
            'CodigoCliente'=>request()->input('comisionistaOculto'),
            'CodigoCategoriaCliente_'=>request()->input('codigoCategoriaCliente'),
            'CodigoAccionComercialLc'=>request()->input('accionComercial'),
            'CodigoTipoPrioridadLc'=>request()->input('prioridad'),
            'Observaciones'=>request()->input('objetivo'),
            'IdDelegacion'=>100,
            'CodigoTemaComercialLc'=>request()->input('temaComercial'),
            'CodigoGrupoComercialLc'=>'COMER',
            'StatusTareaLc'=>request()->input('estado'),
            'BgColor'=>request()->input('color'),
            'TxColor'=>request()->input('textColor'),
            'TareaUnicaLc'=>0,          
            'FechaGrabacion'=>date("Y-m-d")
                        
        ]);

        // if($_POST['estado'] != 0){
        //     $estadoNoPendiente = DB::table('LcComisionistaAcciones')        
        //         ->insert([
        //         'CodigoEmpresa'=>session('codigoEmpresa'),
        //         //'CodigoComisionista'=>session('codigoComisionista'),
        //         'CodigoComisionista'=>session('codigoComisionista'),
        //         'AccionPosicionLc'=>$conta[0]->sysContadorValor,
        //         'FechaInicialLc'=>$_POST['fechaInicio'],
        //         'HoraInicialLc'=>$inicio,
        //         'FechaFinalLc'=>$_POST['fechaFin'],
        //         'HoraFinalLc'=>$fin,
        //         'CodigoCliente'=>$_POST['comisionistaOculto'],
        //         'CodigoCategoriaCliente_'=>$_POST['codigoCategoriaCliente'],
        //         'CodigoAccionComercialLc'=>$_POST['accionComercial'],
        //         //'CodigoTipoPrioridadLc'=>$_POST['prioridad'],
        //         //'Observaciones'=>$_POST['objetivo'],
        //         'IdDelegacion'=>100,
        //         'CodigoTemaComercialLc'=>$_POST['temaComercial'],
        //         'CodigoGrupoComercialLc'=>'COMER',
        //         //'StatusTareaLc'=>$_POST['estado'],
        //         //'TareaUnicaLc'=>0,          
        //         //'FechaGrabacion'=>date("Y-m-d")
                            
        //     ]);
        // }

        $contador = DB::table('lsysContadores')
        ->where('sysNombreContador', '=', 'ACCIONPOS_C')
        ->where('sysAplicacion', '=', 'PRV')
        ->update(['sysContadorValor'=>$conta[0]->sysContadorValor+1]);
   
        // request()->validate(Evento::$rules);

        // DB::table('eventos')->insert([
        //     'id_user' => Auth::user()->id,
        //     'title' => request()->input('title'),
        //     'descripcion' => request()->input('descripcion'),
        //     'start' => request()->input('start').' '.request()->input('startH'),
        //     'end' => request()->input('end').' '.request()->input('endH'),
        //     'price'=> request()->input('importe'),
        //     'material'=>request()->input('materiales'),
        //     'backgroundColor'=>request()->input('color'),
        //     'textColor'=>request()->input('textColor')
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $evento)
    {
        $evento= DB::table('LcComisionistaAgenda')
        ->select('AccionPosicionLc AS publicId','CodigoCategoriaCliente_','CodigoCliente','HoraInicialLc','HoraFinalLc','FechaInicialLc AS start','FechaFinalLc AS end','CodigoCliente AS title', 'BgColor as backgroundColor','BgColor as borderColor', 'TxColor as textColor', 'FechaGrabacion')                
        // ->where('CodigoComisionista', '=', 1)
        ->where('CodigoComisionista', '=', session('codigoComisionista'))
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();        

        foreach($evento as $citas){
            if($citas->CodigoCategoriaCliente_ == 'COMI'){
                $i = DB::table('Comisionistas')
                ->select('Comisionista')
                ->where('CodigoComisionista', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                $citas->NombreContactoLc= $i[0]->Comisionista;
                $citas->title = $i[0]->Comisionista . ' - ' . $citas->title  .' '.$citas->CodigoCategoriaCliente_;
            }else if($citas->CodigoCategoriaCliente_ == 'CLI' || $citas->CodigoCategoriaCliente_ == 'POT'){
                $k = DB::table('Clientes')
                ->select('RazonSocial')
                ->where('CodigoCliente', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                
                $citas->NombreContactoLc= $k[0]->RazonSocial;
                $citas->title = $k[0]->RazonSocial . ' - ' . $citas->title  .' '.$citas->CodigoCategoriaCliente_;
                //$citas->NombreContactoLc= $k;
            }
            
            $hil = ($citas->HoraInicialLc * 86400)+1633557600;
            $citas->HoraInicialLc = date('H:i', $hil);
            $fechai = strtotime($citas->start); 
            //para controlar cambio horario
            if($citas->FechaGrabacion >= '2022-10-31 00:00:00.000' && $citas->FechaGrabacion <= '2023-03-28 00:00:00.000' && date('Y-m-d', $fechai) >='2022-10-31'){
            $citas->start = date('Y-m-d', $fechai).'T'.date('H:i', $hil).'+01:00' ;
           }else{
            $citas->start = date('Y-m-d', $fechai).'T'.date('H:i', $hil).'+02:00' ;
           }

            $hfl = ($citas->HoraFinalLc * 86400)+1633557600;
            $citas->HoraFinalLc = date('H:i', $hfl);
            $fechaf = strtotime($citas->end); 
            //para controlar cambio horario
            if($citas->FechaGrabacion >= '2022-10-31 00:00:00.000' && $citas->FechaGrabacion <= '2023-03-28 00:00:00.000' && date('Y-m-d', $fechai) >='2022-10-31'){
                $citas->end = date('Y-m-d', $fechaf).'T'.date('H:i', $hfl).'+01:00' ;

            }else{
                $citas->end = date('Y-m-d', $fechaf).'T'.date('H:i', $hfl).'+02:00' ;

            }
            //var_dump($inicio);
        }
        
        return response()->json($evento);

        //$evento = Evento::all();
        // $evento = DB::table('eventos')->where('id_user','=', Auth::user()->id)->get();
        // return response()->json($evento);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evento = DB::table('LcComisionistaAgenda')
        ->select('*', 'CodigoCliente AS title')
        ->where('AccionPosicionLc', '=', $id)
        ->get();

        foreach($evento as $citas){
            $hil = ($citas->HoraInicialLc * 86400)+1633557600;
            $citas->HoraInicialLc = date('H:i', $hil);
            $fechai = strtotime($citas->FechaInicialLc); 
            $citas->FechaInicialLc = date('Y-m-d', $fechai);
            $hfl = ($citas->HoraFinalLc * 86400)+1633557600;
            $citas->HoraFinalLc = date('H:i', $hfl);
            $fechaf = strtotime($citas->FechaFinalLc); 
            $citas->FechaFinalLc = date('Y-m-d', $fechaf);

            if($citas->CodigoCategoriaCliente_ == 'COMI'){
                $i = DB::table('Comisionistas')
                ->select('Comisionista')
                ->where('CodigoComisionista', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                $citas->NombreContactoLc= $i[0]->Comisionista;
                $citas->title =  $citas->title  .'-'.$i[0]->Comisionista . ' ' .$citas->CodigoCategoriaCliente_;
            }else if($citas->CodigoCategoriaCliente_ == 'CLI' || $citas->CodigoCategoriaCliente_ == 'POT'){
                $k = DB::table('Clientes')
                ->select('RazonSocial')
                ->where('CodigoCliente', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                
                $citas->NombreContactoLc= $k[0]->RazonSocial;
                $citas->title =  $citas->title  .'-'.$k[0]->RazonSocial . ' ' .$citas->CodigoCategoriaCliente_;
                //$citas->NombreContactoLc= $k;
            }
        }
        // $evento = Evento::find($id);
        // $evento->startF=Carbon::createFromFormat('Y-m-d H:i:s', $evento->start)->format('Y-m-d');
        // $evento->endF=Carbon::createFromFormat('Y-m-d H:i:s', $evento->end)->format('Y-m-d');
        
        // $evento->startH=Carbon::createFromFormat('Y-m-d H:i:s', $evento->start)->format('H:i:s');
        // $evento->endH=Carbon::createFromFormat('Y-m-d H:i:s', $evento->end)->format('H:i:s');
        return response()->json($evento);
        //return $evento;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {    

        $hora = strtotime(request()->input('startH'));
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;
        //return $hora3;
        $hora2 = strtotime(request()->input('endH'));
        $hora4 = strtotime('00:00');
        $fin = ($hora2-$hora4)/86400;       

            $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))
                ->where('CodigoCliente', '=', request()->input('comisionistaOculto'))
                ->update([
                    'StatusTareaLc'=>1,                    
                    'FechaInicialLc'=>request()->input('start'),
                    'HoraInicialLc'=>$inicio,
                    'FechaFinalLc'=>request()->input('end'),
                    'HoraFinalLc'=>$fin,
                    'CodigoTipoPrioridadLc'=>request()->input('prioridad'),
                    'Observaciones'=>request()->input('objetivo'),
                    'StatusTareaLc'=>request()->input('estado'),
                    'BgColor'=>request()->input('color'),
                    'TxColor'=>request()->input('textColor'),
            ]);

            $comisionistaAcciones = DB::table('LcComisionistaAcciones')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
            ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))
            ->get();

            if(count($comisionistaAcciones)>0){
                
                $estadoNoPendiente = DB::table('LcComisionistaAcciones') 
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                    ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))       
                    ->update([                    
                    'FechaInicialLc'=>request()->input('start'),
                    'HoraInicialLc'=>$inicio,
                    'FechaFinalLc'=>request()->input('end'),
                    'HoraFinalLc'=>$fin,                         
                    'Observaciones'=>request()->input('resultado'),           
                ]);

            }else{

                $estadoNoPendiente = DB::table('LcComisionistaAcciones')        
                    ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),               
                    'CodigoComisionista'=>session('codigoComisionista'),
                    'AccionPosicionLc'=>request()->input('accionPosicionId'),
                    'FechaInicialLc'=>request()->input('start'),
                    'HoraInicialLc'=>$inicio,
                    'FechaFinalLc'=>request()->input('end'),
                    'HoraFinalLc'=>$fin,
                    'CodigoCliente'=>request()->input('comisionistaOculto'),
                    'CodigoCategoriaCliente_'=>request()->input('codigoCategoriaCliente'),
                    // 'CodigoAccionComercialLc'=>request()->input('accionComercial'),                
                    'Observaciones'=>request()->input('resultado'),
                    'IdDelegacion'=>100,
                    
                                
                ]);
            }

        //return response()->json($evento);


    }

    public function updateDate(Request $request, Evento $evento){


        $hora = strtotime(request()->input('startH'));
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;
        //return $hora3;
        $hora2 = strtotime(request()->input('endH'));
        $hora4 = strtotime('00:00');
        $fin = ($hora2-$hora4)/86400; 


            $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))
                ->where('CodigoCliente', '=', request()->input('comisionistaOculto'))
                ->update([                                        
                    'FechaInicialLc'=>request()->input('start'),
                    'HoraInicialLc'=>$inicio,
                    'FechaFinalLc'=>request()->input('end'),
                    'HoraFinalLc'=>$fin,                    
            ]);

            $estadoNoPendiente = DB::table('LcComisionistaAcciones')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))                                                                         
                ->update([                
                'FechaInicialLc'=>request()->input('start'),
                'HoraInicialLc'=>$inicio,
                'FechaFinalLc'=>request()->input('end'),
                'HoraFinalLc'=>$fin,            
            ]);

        

        return response()->json($evento);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $evento = Evento::find($id)->delete();
        $evento = DB::table('LcComisionistaAcciones')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
        ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))
        ->delete();

        $evento = DB::table('LcComisionistaAgenda')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
        ->where('AccionPosicionLc', '=', request()->input('accionPosicionId'))
        ->where('CodigoCliente', '=', request()->input('comisionistaOculto'))
        ->delete();

        
        return response()->json($evento);
    }
}
