<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteConta;
use App\Models\Comisionista;
use App\Models\Domicilio;
use App\Models\prescriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{

    public function index(){
        $conocimientoDieta = DB::table('ABMS_MotivosDieta')->get();
        $fase = DB::table('ABMS_Fases')->get();
        $tipoCliente = DB::table('LcTiposCliente')->get();
        $envios = DB::table('ABMS_TiposEnvio')->get();
        $naciones = DB::table('naciones')->get();
        $provincias = DB::table('provincias')->get();
        //Session(['tipoPedido'=>$_GET['tipoPedido']]);

        return view('clientes.fichaClientes')->with('conDieta',$conocimientoDieta)
            ->with('fases',$fase)->with('tipoCliente',$tipoCliente)
            ->with('envios',$envios)->with('naciones',$naciones)->with('provincias',$provincias);
    }

    public static function obtenerCodigoContable($id){
        $query = Cliente::select('CodigoContable')
        ->where('CodigoCliente','=',$id)->first();
        return $query;
    }

    public static function razonSocial($id){
        $query = Cliente::select('RazonSocial')
        ->where('IdCliente', '=', $id)
        ->get();

        return $query;

    }

    public static function clientes($codigo){

        $query = Cliente::select('RazonSocial')
        ->where('CodigoCliente','=',$codigo)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();
        return $query[0]->RazonSocial;
    }

    public static function comisionista($id){
        if(session('codigoEmpresa') != 0){
            $query = Comisionista::where('CodigoComisionista', '=', $id)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))        
            ->get();

            return view('dashboard3')->with('IdComisionista', $id)->with('CodigoComisionista', $query[0]->IdComisionista);
        }else{
            return view('dashboard3')->with('IdComisionista', $id)->with('CodigoComisionista', 0);
        }
    }

    public static function ObtenerIdCliente(){
        $quiery = Cliente::select('IdCliente')
        ->where('CodigoCliente', '=', $_POST['codigo'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return $quiery[0]['IdCliente'];
    }

    public static function obtenerCliente(){
        $correcto = "ERROR";
        $query = Cliente::where('CodigoCliente','=',$_POST['datos']['idCliente'])->first();
        if($query){
            session(['codigoCliente'=>$_POST['datos']['idCliente']]);
            session(['nombreCliente'=>$_POST['datos']['nombre']]);
            session(['telefonoCliente'=>$_POST['datos']['telefono']]);
            session(['direccionEnvioCliente'=>$_POST['datos']['direccion']]);
            session(['iva'=>$_POST['datos']['iva']]);
            session(['autorizacionRGPD'=>$query->VAutorizacionRGPD]);
            session(['descuento'=>$_POST['datos']['descuento']]);
            $correcto = "OK";
        }
        return $correcto;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarClientes(){
        $query = Cliente::where('Nombre','LIKE',"%".$_POST['clienteAbuscar']."%")
            ->orWhere('Telefono','LIKE',"%".$_POST['clienteAbuscar']."%")
            ->orWhere('CifDni','LIKE',"%".$_POST['clienteAbuscar']."%")->get();
            $html = "";
        if(count($query)!= 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                $html .="
                        <tr>
                        <td><a>".$value['CodigoCliente']."</a></td>
                        <td>".$value['Nombre']."</td>
                        <td>".$value['Telefono']."</td>
                        <td><button class='btn ' id='btnCliente' onclick='seleccionarCliente(".$value.")'><i class='far fa-hand-pointer'></i>
                        </button></td>
                        </tr>
                ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen clientes con esos datos</div>";
        }
        return $html;
    }

    public function buscarConsultaClientes(){
        $query = Cliente::where('Nombre','LIKE',"%".$_POST['clienteAbuscar']."%")
            ->orWhere('Telefono','LIKE',"%".$_POST['clienteAbuscar']."%")
            ->orWhere('CifDni','LIKE',"%".$_POST['clienteAbuscar']."%")->get();
        $html = "";
        if(count($query)!= 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                $html .="
                        <tr>
                        <td><a>".$value['CodigoCliente']."</a></td>
                        <td>".$value['Nombre']."</td>
                        <td>".$value['Telefono']."</td>
                        <td><button class='btn ' id='btnCliente' onclick='mostrarDatosCliente(".$value.")'><i class='far fa-hand-pointer'></i>
                        </button></td>
                        </tr>
                 ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen clientes con esos datos</div>";
        }
        return $html;
    }

    public  function historicoCliente(){
        $cabecera=DB::table('CabeceraPedidoCliente')
            ->select('NumeroPedido','FechaPedido', 'ImporteLiquido', 'SeriePedido')
            ->where('CodigoCliente', '=', $_POST['idCliente'])
            //->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        $html = "";
        if(count($cabecera)!= 0){
            foreach ($cabecera as $datos){
                $date = date_create($datos->FechaPedido);
                $html .= "<div class='row'>
                        <div class='col col-md-1 col-sm-1'><i class='far fa-eye'></i></div>
                        <div class='col col-md-3 col-sm-3'>".$datos->NumeroPedido."<br>".date_format($date,'d/m/Y')."</div>
                        <div class='col col-md-3 col-sm-3'>".$datos->SeriePedido."</div>
                        <div class='col col-md-3 col-sm-3'>".$datos->ImporteLiquido."</div>
                    </div>";
            }
        }else{
            $html = "<p>No existen compras anteriores para el cliente <span class='badge bg-danger mr-3 ml-2'>".$_POST['idCliente']."<span</p>";
        }
        return $html;
    }

    public static function nuevo(){

        $comprobarClienteEnBBDD = DB::table('clientes')->where('CifDni', '=', $_POST['datos']['nif'])->get();

        if(count($comprobarClienteEnBBDD) < 0){

            $direccion = $_POST['datos']['direccion'].','.$_POST['datos']['poblacion'].', España';
            $geo = PrescriptorController::latitudyLongitud($direccion);
            $lat = $geo["results"][0]["geometry"]["location"]["lat"];
            $lng = $geo["results"][0]["geometry"]["location"]["lng"];

            $insertadoCorrectamente = "ERROR";
            $id = self::codigoClienteNuevo() + 1;
            $nuevoCliente = Cliente::insert([
                'CodigoEmpresa'=>session('codigoEmpresa'),
                'CodigoCliente'=>430000000+$id,
                'CifDni'=> $_POST['datos']['nif'],
                'FechaAlta'=> new \DateTime('now'),
                'CodigoContable'=>430000000+$id,
                'CodigoCategoriaCliente_'=>'CLI',
                'RazonSocial'=>$_POST['datos']['nombre'],
                'Nombre'=>$_POST['datos']['nombre'],
                'Domicilio'=>$_POST['datos']['direccion'],
                //'TipoCliente'=>$_POST['datos']['tipoCliente'],
                'IBAN'=>$_POST['datos']['iban'],
                //'%Descuento'=>$_POST['datos']['descuento'],
                'CodigoComisionista'=>$_POST['datos']['comisionista'],
                'Municipio'=>$_POST['datos']['poblacion'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                'CodigoProvincia'=>$_POST['datos']['provincia'],
                'Provincia'=>$_POST['datos']['nombreProvincia'],
                'Nacion'=>"España",
                'CodigoNacion'=>'108',
                'Telefono'=>$_POST['datos']['telefono'],
                'Telefono2'=>$_POST['datos']['telefono2'],
                'Email1'=>$_POST['datos']['eMail1'],
                'CodigoBanco'=>$_POST['datos']['CodigoBanco'],            
                'CodigoAgencia'=>$_POST['datos']['CodigoAgencia'],            
                'DC'=>$_POST['datos']['DC'],            
                'CCC'=>$_POST['datos']['CCC'],
                'VLatitud'=>$lat,          
                'VLongitud'=>$lng,          
            ]);
            if($nuevoCliente) {  
                $domicilio = DB::table('Domicilios')            
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'CodigoCliente'=>4300000000+$id,
                    'Nombre'=>$_POST['datos']['nombreComercial'],
                    'RazonSocial'=>$_POST['datos']['nombre'],
                    'CodigoNacion'=>'108',
                    'Nacion'=>'España',
                    'CodigoProvincia'=>$_POST['datos']['provincia'],
                    'Provincia'=>$_POST['datos']['nombreProvincia'],
                    'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                    'Municipio'=>$_POST['datos']['poblacion'],
                    'Domicilio'=>substr($_POST['datos']['direccion'],0,39),
                    'Domicilio2'=>substr($_POST['datos']['direccion'],0,39),                
                    'TipoDomicilio'=>"E",
                    'NumeroDomicilio'=> 0,                                         
                    
                ]);
                
                $cartera = DB::table('ClientesConta')            
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'ClienteOproveedor'=>'C',
                    'CodigoClienteProveedor'=>430000000+$id,
                    'SiglaNacion'=>'ES',
                    'CodigoTransaccion'=>0,
                    'CodigoRetencion'=>0,
                    'CodigoTipoEfecto'=>0,
                    'CodigoIva'=>21,
                    'CodigoCuenta'=>430000000+$id,
                    'CodigoCuentaEfecto'=>430000000+$id,
                    'CodigoCuentaImpagado'=>430000000+$id,
                    'CifDni'=>$_POST['datos']['nif'],
                    'ClienteProveedor'=>$_POST['datos']['nombre'],
                    'NumeroPlazos'=>$_POST['datos']['NumeroPlazos'],
                    'DiasPrimerPlazo'=>$_POST['datos']['DiasPrimerPlazo'],
                    'DiasEntrePlazos'=>$_POST['datos']['DiasEntrePlazos'],
                    'DiasRetroceso'=>$_POST['datos']['DiasRetroceso'],
                    'CodigoTipoEfecto'=>$_POST['datos']['CodigoTipoEfecto'],
                    'DiasFijos1'=>$_POST['datos']['DiasFijos1'],
                    'DiasFijos2'=>$_POST['datos']['DiasFijos2'],
                ]);  
                if($cartera){        
                    $insertadoCorrectamente = "ok";

                    $cliente = DB::table('LsysContadores')
                    //->select('sysContadorValor as numeroMaximo')
                    ->where('sysGrupo', '=', session('codigoEmpresa'))
                    ->where('sysNombreContador', '=', 'COD_CLIENTE') 
                    ->update(['sysContadorValor'=>$id]);

                }
            }
        }else{
            $insertadoCorrectamente = 'Cliente duplicado';
        }    
        return $insertadoCorrectamente;
    }

    public static function codigoClienteNuevo(){

        $cliente = DB::table('LsysContadores')
        //->select('sysContadorValor as numeroMaximo')
        ->where('sysGrupo', '=', session('codigoEmpresa'))
        ->where('sysNombreContador', '=', 'COD_CLIENTE')        
        ->get();

        // $query = DB::table('Clientes')->selectRaw('max(CodigoCliente) AS numeroMaximo')
        // ->where('CodigoEmpresa','=',session('codigoEmpresa'))->where('CodigoCliente', '<', '43000999999')->first();
        // return $query->numeroMaximo+1;

        return $cliente[0]->sysContadorValor;
    }

    public static function obtenerClientesEmpresa (){
        $query = Cliente::select('CodigoCliente as codigo', 'Nombre as nombre', 'Municipio as municipio', 'Telefono as telefono', 'CodigoComisionista');
        if (session('codigoComisionista')){
            $query ->where('CodigoComisionista','=',session('codigoComisionista'));
        }
        $query->where('CodigoEmpresa','=',session('codigoEmpresa'));
        $results = $query->paginate();
        return view('clientes.verClientes')->with('clientes',$results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */     

    public static function datosPotencial($request){
        $datos = Cliente::where('CodigoCliente', '=', $request)
        ->where('CodigoEmpresa', '=', 1)
        ->get();
        return $datos;
    }

    public static function datosAutorizaciónCliente($request){
        $datos = Cliente::
        //select('IdCliente', 'FechaAlta', 'ObservacionesCliente', 'VFase', 'VProductosDia', 'VGamaAmarilla', 'VGamaRoja', 'VGamaVerde', 'VSuplementacion', 'VFechaPrescripcion')
        where('CodigoCliente', '=', $request)
        ->where('CodigoEmpresa', '=', 1)
        ->get();
        return $datos;
    }

    public static function actualizar(){ 
        //return $_POST['datos'];
        
        if (!isset($_POST['datos']['ObservacionesCliente']) || $_POST['datos']['ObservacionesCliente'] == '') {
            $observacion = '';
        }else{
            $observacion = $_POST['datos']['ObservacionesCliente'];
        }
        
        if($_POST['datos']['VLatitud'] == .0000000000 && $_POST['datos']['VLongitud'] == .0000000000 || $_POST['datos']['VLatitud'] == '' && $_POST['datos']['VLongitud'] == '' ){
            $direccion = $_POST['datos']['direccion'].','.$_POST['datos']['poblacion'].', Spain';
            $geo = PrescriptorController::latitudyLongitud($direccion);

            //return $geo;
            
            $lat = $geo["results"][0]["geometry"]["location"]["lat"];
            $lng = $geo["results"][0]["geometry"]["location"]["lng"];

            $actualizarAutorizacion = Cliente::where('IdCliente', '=', $_POST['datos']['IdCliente'])
                ->update([                
                    'ObservacionesCliente'=>$observacion,
                    'CodigoSector_' => $_POST['datos']['sector'],
                    'CodigoCondiciones' => $_POST['datos']['fPago'],
                    //'Domicilio'=>$_POST['datos']['direccion'],
                    //'Municipio'=>$_POST['datos']['poblacion'],
                    //'CodigoProvincia'=>$_POST['datos']['provincia'],
                    //'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                    //'IBAN'=>$_POST['datos']['iban'],
                    'Telefono'=>$_POST['datos']['telefono'],
                    'Telefono2'=>$_POST['datos']['telefono2'],
                    'Email1'=>$_POST['datos']['eMail1'],
                    //'Provincia'=>$_POST['datos']['nombreProvincia'],
                    //'CodigoBanco'=>$_POST['datos']['CodigoBanco'],
                    //'CodigoAgencia'=>$_POST['datos']['CodigoAgencia'],
                    //'DC'=>$_POST['datos']['DC'],
                    //'CCC'=>$_POST['datos']['CCC'],
                    'VLatitud'=>$lat,
                    'VLongitud'=>$lng,
                ]);

            if($actualizarAutorizacion){
                return 'ok';
            }else{
                return 'fallo al actualizar';
            } 

        }else{

            $actualizarAutorizacion = Cliente::where('IdCliente', '=', $_POST['datos']['IdCliente'])
            ->update([
                'ObservacionesCliente'=>$observacion,
                //'Domicilio'=>$_POST['datos']['direccion'],
                //'Municipio'=>$_POST['datos']['poblacion'],
                //'CodigoProvincia'=>$_POST['datos']['provincia'],
                //'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                //'IBAN'=>$_POST['datos']['iban'],
                'CodigoSector_' => $_POST['datos']['sector'],
                'CodigoCondiciones' => $_POST['datos']['fPago'],
                'Telefono'=>$_POST['datos']['telefono'],
                'Telefono2'=>$_POST['datos']['telefono2'],
                'Email1'=>$_POST['datos']['eMail1'],
                //'Provincia'=>$_POST['datos']['nombreProvincia'],
                //'CodigoBanco'=>$_POST['datos']['CodigoBanco'],
                //'CodigoAgencia'=>$_POST['datos']['CodigoAgencia'],
                //'DC'=>$_POST['datos']['DC'],
                //'CCC'=>$_POST['datos']['CCC'],
            ]);

            if($actualizarAutorizacion){
                return 'ok';
            }else{
                return 'fallo al actualizar';
            }
        }

        $clientesConta = DB::table('ClientesConta')
        ->where('CodigoClienteProveedor', '=', $_POST['datos']['CodigoCliente'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('ClienteOproveedor', '=', 'C')
        ->update([
            'NumeroPlazos'=>$_POST['datos']['NumeroPlazos'],
            'DiasPrimerPlazo'=>$_POST['datos']['DiasPrimerPlazo'],
            'DiasEntrePlazos'=>$_POST['datos']['DiasEntrePlazos'],
            'DiasRetroceso'=>$_POST['datos']['DiasRetroceso'],
            'CodigoTipoEfecto'=>$_POST['datos']['CodigoTipoEfecto'],
            'DiasFijos1'=>$_POST['datos']['DiasFijos1'],
            'DiasFijos2'=>$_POST['datos']['DiasFijos2'],
        ]);

        return 'ok';    

    }

    public static function prescriptores(){
        $prescriptores = prescriptor::select('CodigoComisionista', 'Comisionista')
        ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
        ->get();
        return $prescriptores;
    }


    public static function create(){
        $insertadoCorrectamente = "ERROR";

        $comprobarCliente= DB::table('Clientes')
        ->where('CifDni', '=', $_POST['datos']['domumento'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        if(count($comprobarCliente)>0){

            $query = DB::table('lsysContadores')->select('sysContadorValor')
            ->where('sysGrupo', '=' ,session('codigoEmpresa'))
            ->where("sysNombreContador", '=' ,"INCIDENCIASP")
            ->where('sysEjercicio', '=', date('Y'))
            ->get();

            $correcto = "ERROR";

            $nuevaCabecera = DB::table('ABMS_CabeceraIncidencias')->insert([
                "CodigoEmpresa"=>session('codigoEmpresa'),
                "EjercicioIncidencia"=> date("Y"),
                "Fecha"=>new \dateTime ('now'),
                "VNumeroIncidencia"=>$query[0]->sysContadorValor + 1,
                "VCodigoIncidencia"=>11,
                "VCodigoEstadoIncidencia"=>0,
                "VTipoIncidencia"=>"P",
                'CodigoComisionista'=>$_POST['datos']['prescriptor'],
                'CodigoJefeVenta_'=>session('codigoComisionista')                
            ]);

            if($nuevaCabecera == 1){
                $lineaIncidencia= DB::table('ABMS_LineasIncidencias')->insert([
                    "CodigoEmpresa"=>session('codigoEmpresa'),
                    "EjercicioIncidencia"=> date("Y"),
                    "VNumeroIncidencia"=>$query[0]->sysContadorValor + 1,
                    "Descripcion"=>'El Cliente ya existe su prescriptor es el '.$comprobarCliente[0]->CodigoComisionista,
                    "VTipoIncidencia"=>"P",                    
                    "Orden"=> 0
                ]);

                if ($lineaIncidencia == 1){
                    $correcto = 'El Cliente ya existe su prescriptor es el '.$comprobarCliente[0]->CodigoComisionista. 'Pregunta en administración';

                    $actualizarC = DB::table('lsysContadores')
                    ->where('sysGrupo', '=' ,session('codigoEmpresa'))
                    ->where("sysNombreContador", '=' ,"INCIDENCIASP")
                    ->where('sysEjercicio', '=', date('Y'))
                    ->update(['sysContadorValor'=>$query[0]->sysContadorValor + 1]);
                }
            }
        

            return $correcto;

        }else{

            $descuento = DB::table('comisionistas')
            ->select('VDescuentoCli')
            ->where('CodigoComisionista', '=', $_POST['datos']['prescriptor'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
            
            $des= floatval($descuento[0]->VDescuentoCli);

            $cadena = '';

            if($_POST['datos']['recomendacion']>=0){
                foreach($_POST['datos']['recomendacion'] as $recomendado){
                    $cadena = $cadena.$recomendado.'/ ';
                }
            }

            //return $_POST;

            $id = self::codigoClienteNuevo() + 1;
            $createAutorizacion = Cliente::insert([
                'CodigoEmpresa'=>session('codigoEmpresa'),
                'CodigoCliente'=>$id,
                'CifDni'=> $_POST['datos']['domumento'],
                'FechaAlta'=> new \DateTime('now'),
                'CodigoContable'=> 430000000+$id,
                'CodigoCategoriaCliente_'=>'CLI',
                'CodigoComisionista'=>$_POST['datos']['prescriptor'],
                'RazonSocial'=>$_POST['datos']['nombre'],
                'Nombre'=>$_POST['datos']['nombre'],

                'Domicilio'=>$_POST['datos']['direccion'],
                'IBAN'=>$_POST['datos']['iban'],
                '%Descuento'=>$des,
                'Municipio'=>$_POST['datos']['poblacion'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                'CodigoProvincia'=>$_POST['datos']['provincia'],
                'Provincia'=>$_POST['datos']['nombreProvincia'],
                'Nacion'=>$_POST['datos']['nombrePais'],
                'CodigoNacion'=>$_POST['datos']['pais'],
                'Telefono'=>$_POST['datos']['telefono'],
                'Telefono2'=>$_POST['datos']['telefono2'],
                'Email1'=>$_POST['datos']['eMail1'],

                'TipoCliente'=>$_POST['datos']['tipoCliente'],
                'CodigoTipoClienteLc'=>$_POST['datos']['tipoCliente'],
                'VAlergico'=>$_POST['datos']['valergico'],
                'VFechaPrescripcion'=>$_POST['datos']['vfechaPrescripcion'],
                'VFase'=>$_POST['datos']['vfase'],
                'VProductosDia'=>$_POST['datos']['vproductosDia'],
                'VGamaAmarilla'=>$_POST['datos']['vGamaAmarilla'],
                'VGamaRoja'=>$_POST['datos']['vGamaRoja'],
                'VGamaVerde'=>$_POST['datos']['vGamaVerde'],
                'VSuplementacion'=>$_POST['datos']['vSuplementacion'],
                'VRecoSuple'=>$cadena,
            ]);

            return 'ok';
        }
    }


    public static function fechaPrescripcion($request){
        $fechaPrescripcion = Cliente::select('VFechaPrescripcion')
        ->where('CodigoCliente', '=', $request)
        ->get();

        return $fechaPrescripcion;
    }

    public static function clienteShow($id){
        $query = Cliente::where('IdCliente','=',$id)->get();
        return $query[0];
    }

    public static function clienteConta ($id){
        $conta = DB::table('ClientesConta')
        ->where('CodigoClienteProveedor', '=', "'".$id."'")
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('ClienteOProveedor', '=', 'C')
        ->get();

        return $conta;
    }

    static function condicionesPlazos(){
        $condiciones = DB::table('condicionesPlazos')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();
        return $condiciones;
    }



    public static function obtenerDatosClientePedido($cliente){
        $query = Cliente::where('CodigoCliente','=',$cliente)    
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();
        return $query;
    }

    public static function obtenerDatosClientePedidoConta($cliente){
        $query = ClienteConta::where('CodigoClienteProveedor','=',$cliente)    
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();
        return $query;
    }
    

    public static function tarifaProducto(){

            $comprobarTarifa = DB::table('Tarifas')
            ->select('*', '[%ProntoPago] as ProntoPago')
            ->where('CodigoCliente', '=', $_POST['cliente'])
            ->get();
            $existe = $comprobarTarifa->count();
            
            //return $query[0]->VCentroVendedor;
            if($existe != 0){
                $numeroTarifa = DB::table('Tarifas')
                ->select('Tarifa')
                ->where('CodigoCliente', '=', $_POST['cliente'])
                ->get();

                $tarifa=$numeroTarifa[0]->Tarifa;

                $insertarTarifaProducto = DB::table('TarifaPrecio')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'CodigoArticulo'=>$_POST['codigo'],
                    'Tarifa'=>$tarifa,
                    'FechaInicio'=>$_POST['fechaInicnio'],
                    'FechaFinal'=>$_POST['fechaFin'],
                    'EnEuros_'=>-1,
                    'StatusActivo'=>-1,
                    'HastaUnidades1'=>9999,
                    'Precio1'=>$_POST['precio']
                ]);

                $llego = 'ok';
                return $llego;
            }else{ 
                
                $numeroUltimaTarifa = DB::select('SELECT MAX(Tarifa) AS numero from Tarifas');
                $max = $numeroUltimaTarifa[0]->numero +1;

                $insertarTarifa = DB::table('Tarifas')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'Tarifa'=>$max,
                    'DescripcionTarifa'=>'Cliente '.$_POST['cliente'],
                    'IndicadorTarifa'=>0,
                    'TraspasarASageRetail'=>0,
                    'PublicarGCRM'=>-1,
                    'CodigoCliente'=>$_POST['cliente']
                ]);
                

                $insertarTarifaProducto = DB::table('TarifaPrecio')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'CodigoArticulo'=>$_POST['codigo'],
                    'Tarifa'=>$max,
                    'FechaInicio'=>$_POST['fechaInicnio'],
                    'FechaFinal'=>$_POST['fechaFin'],
                    'EnEuros_'=>-1,
                    'StatusActivo'=>-1,
                    'HastaUnidades1'=>9999,
                    'Precio1'=>$_POST['precio']
                ]);

                $llego = 'ok';
                return $llego;
                
            }
        
    }
    
    public static  function tarifaFamilia(){

        
            $comprobarTarifa = DB::table('Tarifas')
            ->where('CodigoCliente', '=',$_POST['cliente'])
            ->get();
            $existe = $comprobarTarifa->count();
            
            //return $query[0]->VCentroVendedor;
            if($existe != 0){
                $numeroTarifa = DB::table('Tarifas')
                ->select('Tarifa')
                ->where('CodigoCliente', '=',$_POST['cliente'])
                ->get();

                $tarifa=$numeroTarifa[0]->Tarifa;

                $insertarTarifaProducto = DB::table('TarifaDescuento')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'CodigoFamilia'=>$_POST['familia'],
                    'Tarifa'=>$tarifa,
                    'FechaInicio'=>$_POST['fechaInicnio'],
                    'FechaFinal'=>$_POST['fechaFin'],                    
                    'StatusActivo'=>-1,
                    'HastaUnidades1'=>9999,
                    '%Descuento1'=>$_POST['descuento']
                ]);

                $llego = 'ok';
                return $llego;
            }else{ 
                
                $numeroUltimaTarifa = DB::select('SELECT MAX(Tarifa) AS numero from Tarifas');
                $max = $numeroUltimaTarifa[0]->numero +1;

                $insertarTarifa = DB::table('Tarifas')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'Tarifa'=>$max,
                    'DescripcionTarifa'=>'Cliente '.$_POST['cliente'],
                    'IndicadorTarifa'=>0,
                    'TraspasarASageRetail'=>0,
                    'PublicarGCRM'=>-1,
                    'CodigoCliente'=>$_POST['cliente']
                ]);
                

                $insertarTarifaProducto = DB::table('TarifaDescuento')
                ->insert([
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    'CodigoFamilia'=>$_POST['familia'],
                    'Tarifa'=>$max,
                    'FechaInicio'=>$_POST['fechaInicnio'],
                    'FechaFinal'=>$_POST['fechaFin'],                    
                    'StatusActivo'=>-1,
                    'HastaUnidades1'=>9999,
                    '%Descuento1'=>$_POST['descuento']
                ]);

                $llego = 'ok';
                return $llego;
                
            }
    }

    public static function accionComercial(){
        $query = DB::table('LcAccionesComerciales')
        ->get();

        return $query;
    }

    public static function prioridad(){
        $query = DB::table('LcTiposPrioridadTareas')
        ->select('CodigoTipoPrioridadLc')
        ->get();

        return $query;
    }

    public static function temaComercial(){
        $query = DB::table('LcAccionTemasComerciales')
        ->select('LcAccionTemasComerciales.CodigoEmpresa', 'LcAccionTemasComerciales.CodigoAccionComercialLc', 
        'LcAccionTemasComerciales.CodigoTemaComercialLc', 'LcTemasComerciales.TemaComercialLc', 
        'LcTemasComerciales.StatusBajaLc')
        ->join('LcTemasComerciales', function ($join) {
            $join->on('LcTemasComerciales.CodigoEmpresa', '=', 'LcAccionTemasComerciales.CodigoEmpresa');
            $join->on('LcTemasComerciales.CodigoTemaComercialLc', '=', 'LcAccionTemasComerciales.CodigoTemaComercialLc');            
        })
        ->where('CodigoAccionComercialLc', '=', $_POST['accion'])
        ->get();

        return $query;
    }

    public static function direccionesh(){
        $query = Cliente::select('Clientes.VLatitud', 'Clientes.VLongitud', 'Clientes.CodigoCliente')
        ->join('Comisionistas', function ($join){
            $join->on('Clientes.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');
            $join->on('Clientes.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            
            //$join->where('Comisionistas.CodigoJefeVenta_', '=', 9001);
        })
        ->where('CodigoCategoríaCliente_', '=', 'CLI')
        ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where(
            function ($query){
                $query ->where('Clientes.CodigoComisionista', '=', session('codigoComisionista'))
                ->orWhere('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));
        })        
        ->where('Clientes.VLongitud', '<>', null)
        ->where('Clientes.VLatitud', '<>', null)
        ->groupBy('Clientes.CodigoCliente', 'Clientes.VLatitud', 'Clientes.VLongitud')
        ->get();
        return $query;
    }

    public static function direccionesPotenciales(){
        $query = Cliente::select('Clientes.VLatitud', 'Clientes.VLongitud', 'Clientes.CodigoCliente')
        ->join('Comisionistas', function ($join){
            $join->on('Clientes.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');
            $join->on('Clientes.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');
            //$join->where('Comisionistas.CodigoJefeVenta_', '=', 9001);
        })
        ->where(
            function ($query){
                $query ->where('Clientes.CodigoComisionista', '=', session('codigoComisionista'))
                ->orWhere('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));
        })
        ->where('CodigoCategoríaCliente_', '=', 'POT')
        ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('Clientes.VLongitud', '<>', null)
        ->where('Clientes.VLatitud', '<>', null)
        ->groupBy('Clientes.CodigoCliente', 'Clientes.VLatitud', 'Clientes.VLongitud')
        ->get();
        return $query;
    }


    public static function lineasOferta($id){
        $query = DB::table('LineasOferta')
        ->where('IdOfertaCli', '=', $id)
        ->orderBy('Orden', 'Asc')
        ->get();

        return $query;
    }

    public static function lineasPedido($id){
        $query = DB::table('LineasPedido')       
        ->where('IdPedidoCli', '=', $id)
        ->orderBy('Orden', 'Asc')
        ->get();

        return $query;
    }

    public static function lineasAlbaran($id){
        $query = DB::table('LineasAlbaran')
        ->where('IdAlbaranCli', '=', $id)
        ->orderBy('Orden', 'Asc')
        ->get();

        return $query;
    }

    public static function lineaFactura($id){
        $query = DB::table('LineasFactura')
        ->where('IdFacturaCli', '=', $id)
        ->orderBy('Orden', 'Asc')
        ->get();

        
        return $query;
      
        
    }


    public static function cabeceraFactura($id){
        $query = DB::table('ResumenCliente')
        ->where('MovPosicion', '=', $id)
        ->get();


        if(count($query) > 0){
            return $query;
        }
        $error = 'error';
        return $error;
    }

    public static function lineasFactura($ejercicio, $serie, $numero){
        $query = DB::table('LineasAlbaranCliente')
        ->selectRaw('CodigoArticulo, DescripcionArticulo, Unidades, Precio, [%Descuento] AS Descuento, ImporteLiquido')
        ->where('EjercicioFactura', '=', $ejercicio)
        ->where('SerieFactura', '=', $serie)
        ->where('NumeroFactura', '=', $numero)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return $query;
    }

    public static function obtenerClientes($cliente){
        $query = Cliente::where('IdCliente', '=', $cliente)
        ->get();
        return $query[0];
    }


    public static function cliente($id){
        if(session('codigoEmpresa') != 0){        
            $query = Cliente::where('CodigoCliente', '=', $id)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))        
            ->get();        
            return view('dashboard2')->with('IdCliente', $id)->with('CodigoCliente', $query[0]->IdCliente);
        }else{
            return view('dashboard2')->with('IdCliente', $id)->with('CodigoCliente', 0);
        }
    }

    public static function busquedaClientes(){

        $query = DB::table('Clientes')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))        
        ->where(function ($quiery){

            $buscar = explode(" ", $_POST['cliente']);
            $where = "CodigoCliente like '%".$buscar[0]."%' or Nombre like '%".$buscar[0]."%' or RazonSocial like '%".$buscar[0]."%'";
            for($i = 1; $i < count($buscar); $i++) {
                if(!empty($buscar[$i])) {
                    $where .= "and Nombre LIKE '%".$buscar[$i]."%' or RazonSocial like '%".$buscar[$i]."%'";
                }
            }        
            $quiery->whereRaw($where);                               
        })
        ->take(10)
        ->get();

        return $query;
    }    


    public static function comprobarExisteDniCifEnBBDD(){

        $comprobarCliente= DB::table('Clientes')
        ->where('CifDni', '=', $_POST['cifdni'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return count($comprobarCliente);
    }
    
    public static function convertirEnCliente(){

        $insertadoCorrectamente = 0;
        $potencial = Cliente::where('IdCliente', '=', $_POST['id'])->get();


        $nuevoCliente = Cliente::where('IdCliente', '=', $_POST['id'])->update([
                'CodigoCategoriaCliente_'=>'CLI'
        ]);
        if($nuevoCliente) {  
            $cartera = DB::table('ClientesConta')            
            ->insert([
                'CodigoEmpresa'=>$potencial[0]['CodigoEmpresa'],
                'ClienteOproveedor'=>'C',
                'CodigoClienteProveedor'=>$potencial[0]['CodigoCliente'],
                'SiglaNacion'=>'ES',
                'CodigoTransaccion'=>1,
                'CodigoRetencion'=>0,
                'CodigoTipoEfecto'=>0,
                'CodigoIva'=>21,
                'CodigoCuenta'=>430000000+$potencial[0]['CodigoCliente'],
                'CodigoCuentaEfecto'=>431100000+$potencial[0]['CodigoCliente'],
                'CodigoCuentaImpagado'=>431500000+$potencial[0]['CodigoCliente'],
                'CifDni'=>$potencial[0]['CifDni'],
                'ClienteProveedor'=>$potencial[0]['Nombre'],
                'NumeroPlazos'=>1,
                'DiasPrimerPlazo'=>1,
                'DiasEntrePlazos'=>1,
                'DiasRetroceso'=>1,
                'CodigoTipoEfecto'=>1,
                'DiasFijos1'=>1,
                'DiasFijos2'=>1,
            ]);  
            if($cartera){        
                $insertadoCorrectamente = 'ok';
            }
        }
        return $insertadoCorrectamente;
    }

    public static function sectorCliente(){
        $query = DB::table('Sector_')
        ->get();

        return $query;
    }


    public static function formasPago(){
        $query = DB::table('CondicionesPlazos')
        ->get();

        return $query;
    }

    public static function obtenerDomicilio($IdCliente)
    {
        $query = DB::table('Domicilios')
        ->where("TipoDomicilio", "E")
        ->where("CodigoCliente", $IdCliente)
        ->where("CodigoEmpresa", session("codigoEmpresa"))
        ->orderBy("NumeroDomicilio")
        ->get();

        return $query;
    }

}
