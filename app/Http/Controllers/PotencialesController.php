<?php

namespace App\Http\Controllers;

use App\Models\CabeceraAlbaranClienteModel;
use App\Models\LineasAlbaranClienteModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Session;

class PotencialesController extends Controller
{

    public static function potenciales(){
        return view('potenciales.datosPotenciales');
    }

    public static function citasCalendario(){        

        $query= DB::table('LcComisionistaAgenda')        
        //->where('CodigoComisionista', '=', session('codigoComisionista'))
        ->where('CodigoComisionista', '=', session('codigoComisionista'))
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        //return $query;

        foreach($query as $citas){
            if($citas->CodigoCategoriaCliente_ == 'COMI'){
                $i = DB::table('Comisionistas')
                ->select('Comisionista')
                ->where('CodigoComisionista', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                $citas->NombreContactoLc= $i[0]->Comisionista;
            }else if($citas->CodigoCategoriaCliente_ == 'CLI' || $citas->CodigoCategoriaCliente_ == 'POT'){
                $k = DB::table('Clientes')
                ->select('RazonSocial')
                ->where('CodigoCliente', '=', $citas->CodigoCliente)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();
                
                $citas->NombreContactoLc= $k[0]->RazonSocial;
                //$citas->NombreContactoLc= $k;
            }
            
            $hil = ($citas->HoraInicialLc * 86400)+1633557600;
            $citas->HoraInicialLc = date('H:i', $hil);
            $hfl = ($citas->HoraFinalLc * 86400)+1633557600;
            $citas->HoraFinalLc = date('H:i', $hfl);
        }

        //return $_POST['fecha'];
        return $query;
    }

    public static function codigoClienteNuevo(){
        $query = DB::table('Clientes')->selectRaw('max(cast(CodigoCliente as bigint)) AS numeroMaximo')
        ->where('CodigoEmpresa','=',session('codigoEmpresa'))->first();
        return $query->numeroMaximo;
    } 


    public static function nuevo(){

        $direccion = $_POST['datos']['Direccion'].','.$_POST['datos']['Municipio'].', España';
        $geo = PrescriptorController::latitudyLongitud($direccion);
        $lat = $geo["results"][0]["geometry"]["location"]["lat"];
        $lng = $geo["results"][0]["geometry"]["location"]["lng"];

        $insertadoCorrectamente = "ERROR";
        $id = ClienteController::codigoClienteNuevo();
        $nuevoCliente = DB::table('Clientes')
            ->insert([
            'CodigoEmpresa'=>session('codigoEmpresa'),
            'CodigoCliente'=>430000000+$id,
            'IdCliente'=>$_POST['datos']['IdComisionista'],
            'CifDni'=> $_POST['datos']['CifDni'],
            'FechaAlta'=> new \DateTime('now'),
            'CodigoContable'=>430000000+$id,
            'CodigoCategoriaCliente_'=>'POT',
            'RazonSocial'=>$_POST['datos']['nombre'],
            'Nombre'=>$_POST['datos']['nombre'],
            'Domicilio'=>$_POST['datos']['Direccion'],
            
            'CodigoComisionista'=>session('codigoComisionista'),
            'Municipio'=>$_POST['datos']['Municipio'],
            'CodigoPostal'=>$_POST['datos']['codigoPostal'],
            'CodigoProvincia'=>$_POST['datos']['Provincia'],
            'Provincia'=>$_POST['datos']['nombreProvincia'],
            'Nacion'=>"España",
            'CodigoNacion'=>'108',
            'Telefono'=>$_POST['datos']['Telefono'],
            'Telefono2'=>$_POST['datos']['Telefono2'],
            'Email1'=>$_POST['datos']['EMail1'],
            
            'VLatitud'=>$lat,          
            'VLongitud'=>$lng,          
        ]);
        if($nuevoCliente) { 
            $domicilio = DB::table('Domicilios')            
            ->insert([
                'CodigoEmpresa'=>session('codigoEmpresa'),
                'CodigoCliente'=>4300000000+$id,
                'Nombre'=>$_POST['datos']['nombre'],
                'RazonSocial'=>$_POST['datos']['nombre'],
                'CodigoNacion'=>'108',
                'Nacion'=>"España",
                'CodigoProvincia'=>$_POST['datos']['Provincia'],
                'Provincia'=>$_POST['datos']['nombreProvincia'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                'Municipio'=>$_POST['datos']['Municipio'],
                'Domicilio'=>substr($_POST['datos']['Direccion'],0,39),
                'Domicilio2'=>substr($_POST['datos']['Direccion'],0,39),                
                'TipoDomicilio'=>"E",
                'NumeroDomicilio'=> 0,                                         
                
            ]);                      
            $cliente = DB::table('LsysContadores')
            //->select('sysContadorValor as numeroMaximo')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', 'COD_CLIENTE')        
            ->update(['sysContadorValor'=>$id+1]);
                                 
            $insertadoCorrectamente = "OK";            

        }        
        return $insertadoCorrectamente;
    }
    
    public static function seguimiento(){

        $conta = DB::table('lsysContadores')
        ->select('sysContadorValor')
        ->where('sysNombreContador', '=', 'ACCIONPOS_C')
        ->where('sysAplicacion', '=', 'PRV')
        ->get();
        
        $hora = strtotime($_POST['horaInicio']);
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;
        //return $hora3;
        $hora2 = strtotime($_POST['horaFin']);
        $hora4 = strtotime('00:00');
        $fin = ($hora2-$hora4)/86400;

        $nuevoCliente = DB::table('LcComisionistaAgenda')        
        ->insert([
            'CodigoEmpresa'=>session('codigoEmpresa'),
            //'CodigoComisionista'=>session('codigoComisionista'),
            'CodigoComisionista'=>session('codigoComisionista'),
            'AccionPosicionLc'=>$conta[0]->sysContadorValor,
            'FechaInicialLc'=>$_POST['fechaInicio'],
            'HoraInicialLc'=>$inicio,
            'FechaFinalLc'=>$_POST['fechaFin'],
            'HoraFinalLc'=>$fin,
            'CodigoCliente'=>$_POST['comisionistaOculto'],
            'CodigoCategoriaCliente_'=>$_POST['codigoCategoriaCliente'],
            'CodigoAccionComercialLc'=>$_POST['accionComercial'],
            'CodigoTipoPrioridadLc'=>$_POST['prioridad'],
            'Observaciones'=>$_POST['objetivo'],
            'IdDelegacion'=>100,
            'CodigoTemaComercialLc'=>$_POST['temaComercial'],
            'CodigoGrupoComercialLc'=>'COMER',
            'StatusTareaLc'=>$_POST['estado'],
            'TareaUnicaLc'=>0,          
            'FechaGrabacion'=>date("Y-m-d")
                        
        ]);

        if($_POST['estado'] != 0){
            $estadoNoPendiente = DB::table('LcComisionistaAcciones')        
                ->insert([
                'CodigoEmpresa'=>session('codigoEmpresa'),
                //'CodigoComisionista'=>session('codigoComisionista'),
                'CodigoComisionista'=>session('codigoComisionista'),
                'AccionPosicionLc'=>$conta[0]->sysContadorValor,
                'FechaInicialLc'=>$_POST['fechaInicio'],
                'HoraInicialLc'=>$inicio,
                'FechaFinalLc'=>$_POST['fechaFin'],
                'HoraFinalLc'=>$fin,
                'CodigoCliente'=>$_POST['comisionistaOculto'],
                'CodigoCategoriaCliente_'=>$_POST['codigoCategoriaCliente'],
                'CodigoAccionComercialLc'=>$_POST['accionComercial'],
                //'CodigoTipoPrioridadLc'=>$_POST['prioridad'],
                //'Observaciones'=>$_POST['objetivo'],
                'IdDelegacion'=>100,
                'CodigoTemaComercialLc'=>$_POST['temaComercial'],
                'CodigoGrupoComercialLc'=>'COMER',
                //'StatusTareaLc'=>$_POST['estado'],
                //'TareaUnicaLc'=>0,          
                //'FechaGrabacion'=>date("Y-m-d")
                            
            ]);
        }

        $contador = DB::table('lsysContadores')
        ->where('sysNombreContador', '=', 'ACCIONPOS_C')
        ->where('sysAplicacion', '=', 'PRV')
        ->update(['sysContadorValor'=>$conta[0]->sysContadorValor+1]);

    }
    
    
    public static function accionAgenda(){
        $hora = strtotime($_POST['hora']);
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;

        if($_POST['tipo'] != 'COMI'){

            $nuevoCliente = DB::table('LcComisionistaAgenda')
            ->selectRaw('LcComisionistaAgenda.*, Clientes.RazonSocial as NombreContactoLc')            
            ->join('Clientes', function($join){
                $join->on('LcComisionistaAgenda.CodigoCliente', '=', 'Clientes.CodigoCliente');           
            })
            ->where('LcComisionistaAgenda.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAgenda.FechaInicialLc', '=', $_POST['fecha'])
            ->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAgenda.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
            
            //return $_POST['tipo'];

        }else{

            $nuevoCliente = DB::table('LcComisionistaAgenda')
            ->selectRaw('LcComisionistaAgenda.*, Comisionistas.Comisionista as NombreContactoLc')
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAgenda.CodigoCliente', '=', 'Comisionistas.CodigoComisionista');
            })            
            ->where('LcComisionistaAgenda.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAgenda.FechaInicialLc', '=', $_POST['fecha'])
            ->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAgenda.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();


            //return 'comisionistas';
        }

        foreach($nuevoCliente as $citas){
            $hil = ($citas->HoraInicialLc * 86400)+1633557600;
            $citas->HoraInicialLc = date('H:i', $hil);
        }

        return $nuevoCliente;
    }

    public static function accionAgendaBlue(){
        $hora = strtotime($_POST['hora']);
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;

        if($_POST['tipo'] != 'COMI'){

            $nuevoCliente = DB::table('LcComisionistaAgenda')
            ->selectRaw('LcComisionistaAgenda.*, Clientes.RazonSocial as NombreContactoLc')            
            ->join('Clientes', function($join){
                $join->on('LcComisionistaAgenda.CodigoCliente', '=', 'Clientes.CodigoCliente');           
            })
            // ->join('LcComisionistaAcciones', function($join){
            //     $join->on('LcComisionistaAgenda.CodigoCliente', '=', 'LcComisionistaAcciones.CodigoCliente');                      
            // })
            ->where('LcComisionistaAgenda.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAgenda.FechaInicialLc', '=', $_POST['fecha'])
            //->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAgenda.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

            $accion= DB::table('LcComisionistaAcciones')
            ->selectRaw('LcComisionistaAcciones.*, Clientes.RazonSocial as NombreContactoLc')            
            ->join('Clientes', function($join){
                $join->on('LcComisionistaAcciones.CodigoCliente', '=', 'Clientes.CodigoCliente');           
            })                        
            ->where('LcComisionistaAcciones.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAcciones.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAcciones.FechaInicialLc', '=', $_POST['fecha'])
            //->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAcciones.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAcciones.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        }else{

            $nuevoCliente = DB::table('LcComisionistaAgenda')
            ->selectRaw('LcComisionistaAgenda.*, Comisionistas.Comisionista as NombreContactoLc')
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAgenda.CodigoCliente', '=', 'Comisionistas.CodigoComisionista');
            })           
            ->where('LcComisionistaAgenda.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAgenda.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAgenda.FechaInicialLc', '=', $_POST['fecha'])
            //->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAgenda.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAgenda.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

            $accion= DB::table('LcComisionistaAcciones')
            ->selectRaw('LcComisionistaAcciones.*, Comisionistas.Comisionista as NombreContactoLc')
            ->join('Comisionistas', function($join){
                $join->on('LcComisionistaAcciones.CodigoCliente', '=', 'Comisionistas.CodigoComisionista');
            })                        
            ->where('LcComisionistaAcciones.CodigoCategoriaCliente_', '=', $_POST['tipo'])
            ->where('LcComisionistaAcciones.CodigoCliente', '=', $_POST['codigo'])
            ->where('LcComisionistaAcciones.FechaInicialLc', '=', $_POST['fecha'])
            //->where('LcComisionistaAgenda.HoraInicialLc', '=', $inicio)
            ->where('LcComisionistaAcciones.CodigoComisionista', '=', session('codigoComisionista'))
            ->where('LcComisionistaAcciones.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();



        }

        foreach($nuevoCliente as $citas){
            $hil = ($citas->HoraInicialLc * 86400)+1633557600;
            $citas->HoraInicialLc = date('H:i', $hil);
        }

        $envio = ['agenda'=>$nuevoCliente, 'accion'=>$accion];

        return $envio;
    }

    public static function guardarUpdatearAccion(){
        $hora = strtotime($_POST['horaInicio']);
        $hora3 = strtotime('00:00');        
        $inicio = ($hora-$hora3)/86400;
        $hora2 = strtotime(date('H:i'));
        $final = ($hora2 - $hora3)/86400;

        if($_POST['estadoBandera'] == 'red'){

            $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                ->update([
                    'StatusTareaLc'=>1,
            ]);

            $estadoNoPendiente = DB::table('LcComisionistaAcciones')        
                    ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    //'CodigoComisionista'=>session('codigoComisionista'),
                    'CodigoComisionista'=>session('codigoComisionista'),
                    'AccionPosicionLc'=>$_POST['accionPosicionLcPost'],
                    'FechaInicialLc'=>$_POST['fechaInicio'],
                    'HoraInicialLc'=>$inicio,
                    'FechaFinalLc'=>date('Y-m-d'),
                    'HoraFinalLc'=>$final,
                    'CodigoCliente'=>$_POST['comisionistaOculto'],
                    'CodigoCategoriaCliente_'=>$_POST['codigoClienteCategoriaPost'],
                    'CodigoAccionComercialLc'=>$_POST['accionComercial'],
                    //'CodigoTipoPrioridadLc'=>$_POST['prioridad'],
                    //'Observaciones'=>$_POST['objetivo'],
                    'Observaciones'=>$_POST['resultado'],
                    'IdDelegacion'=>$_POST['idDelegacionPost'],
                    'CodigoTemaComercialLc'=>$_POST['temaComercial'],
                    'CodigoGrupoComercialLc'=>$_POST['CodigoGrupoComercialPost'],
                    //'StatusTareaLc'=>$_POST['estado'],
                    //'TareaUnicaLc'=>0,          
                    //'FechaGrabacion'=>date("Y-m-d")
                                
                ]);

            if($_POST['estado'] == 0){
                $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))                    
                ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                ->update([
                    'StatusTareaLc'=>1,
                ]);
                return 'blue';
            }    

            if($_POST['estado'] == 1){
                $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                ->update([
                    'StatusTareaLc'=>1,
                ]);
                return 'blue';
            }
            
            if($_POST['estado'] == 3){
                $agenda = DB::table('LcComisionistaAgenda')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                ->update([
                    'StatusTareaLc'=>3,
                ]);
                return 'green';
            }
            return 'insert';
        }

        if($_POST['estadoBandera'] == 'blue'){

            $estadoNoPendiente = DB::table('LcComisionistaAcciones')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                    ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                    ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                    ->update([
                        'CodigoEmpresa'=>session('codigoEmpresa'),
                        //'CodigoComisionista'=>session('codigoComisionista'),
                        'CodigoComisionista'=>session('codigoComisionista'),
                        'AccionPosicionLc'=>$_POST['accionPosicionLcPost'],
                        'FechaInicialLc'=>$_POST['fechaInicio'],
                        'HoraInicialLc'=>$inicio,
                        'FechaFinalLc'=>date('Y-m-d'),
                        'HoraFinalLc'=>$final,
                        'CodigoCliente'=>$_POST['comisionistaOculto'],
                        'CodigoCategoriaCliente_'=>$_POST['codigoClienteCategoriaPost'],
                        'CodigoAccionComercialLc'=>$_POST['accionComercial'],
                        //'CodigoTipoPrioridadLc'=>$_POST['prioridad'],
                        'Observaciones'=>$_POST['resultado'],
                        'IdDelegacion'=>$_POST['idDelegacionPost'],
                        'CodigoTemaComercialLc'=>$_POST['temaComercial'],
                        'CodigoGrupoComercialLc'=>$_POST['CodigoGrupoComercialPost'],
                        //'StatusTareaLc'=>$_POST['estado'],
                        //'TareaUnicaLc'=>0,          
                        //'FechaGrabacion'=>date("Y-m-d")
                                
                ]);
            
                if($_POST['estado'] == 0){
                    $agenda = DB::table('LcComisionistaAgenda')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                    ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                    ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                    ->update([
                        'StatusTareaLc'=>1,
                    ]);
                    return 'blue';
                }    
    
                if($_POST['estado'] == 1){
                    $agenda = DB::table('LcComisionistaAgenda')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                    ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                    ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                    ->update([
                        'StatusTareaLc'=>1,
                    ]);

                    return 'blue';
                }
                
                if($_POST['estado'] == 3){
                    $agenda = DB::table('LcComisionistaAgenda')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))    
                    ->where('AccionPosicionLc', '=', $_POST['accionPosicionLcPost'])
                    ->where('CodigoCliente', '=', $_POST['comisionistaOculto'])
                    ->update([
                        'StatusTareaLc'=>3,
                    ]);

                    return 'green';
                }
            return 'update';
        }
    }


    public static function actualizar(){

        $actualizarAutorizacion = DB::table('Clientes')->where('IdCliente', '=', $_POST['datos']['IdCliente'])
            ->update([                
                'ObservacionesCliente'=>$_POST['datos']['ObservacionesCliente'],                                
                'Domicilio'=>$_POST['datos']['direccion'],
                'Municipio'=>$_POST['datos']['poblacion'],
                'CodigoProvincia'=>$_POST['datos']['provincia'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                'Telefono'=>$_POST['datos']['telefono'],
                'Telefono2'=>$_POST['datos']['telefono2'],
                'Email1'=>$_POST['datos']['eMail1'],
                'Provincia'=>$_POST['datos']['nombreProvincia'],                                                                              
            ]);       

        return 'ok';    

    }
}
?>