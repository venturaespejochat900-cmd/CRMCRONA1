<?php

namespace App\Http\Controllers;

use App\Models\prescriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DateTime;
use App\Models\Cliente;
use App\Models\Comisionista;
use DateTime as GlobalDateTime;
use Exception;
use Faker\Provider\DateTime as ProviderDateTime;

class PrescriptorController extends Controller
{

    /**
     * MÉTODO PARA MODIFICAR VARIABLE SESSION DATOS CLIENTE 
     * 
     */
    public function modificarVariableSesion(){
        Session(['modalTablaPrescriptores'=>$_POST['codigoPrescriptor']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prescriptores.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\preescriptor  $preescriptor
     * @return \Illuminate\Http\Response
     */
    public function show(prescriptor $preescriptor)
    {
        //return view('prescriptores.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\preescriptor  $preescriptor
     * @return \Illuminate\Http\Response
     */
    public function edit(prescriptor $preescriptor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\preescriptor  $preescriptor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, prescriptor $preescriptor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\preescriptor  $preescriptor
     * @return \Illuminate\Http\Response
     */
    public function destroy(prescriptor $prescriptor)
    {
        $query = DB::prescriptor()
        ->where('CodigoComisionista', '=', $prescriptor)
        ->delete();
    }

    public static function naciones(){ 
        $naciones = DB::table('naciones')->orderBy('Nacion', 'ASC')->get();
        return $naciones;
    }

    public static function provincias(){ 
        $provincias = DB::table('provincias')->get();
        return $provincias;
    }

    public static function tipoCliente(){
        $tipoCliente = DB::table('LcTiposCliente')->get();
        return $tipoCliente;
    }

    public static function conDieta(){
        $conocimientoDieta = DB::table('ABMS_MotivosDieta')->get();
        return $conocimientoDieta;
    }

    // public static function fases(){
    //     $fase = DB::table('ABMS_Fases')->get();
    //     return $fase;
    // }

    public static function envios(){
        $envios = DB::table('ABMS_TiposEnvio')->get();
        return $envios;
    }

    public function modificarPassword(request $request){
        //return $request;
        $datos = explode("_", $request);
        $fecha = substr($datos[0],-8,8); 
        $prescriptor = substr($datos[1],0,36); 
        //return $prescriptor;
        return view('comisionistas.pass.pass')->with('prescriptor', $prescriptor)->with('fecha', $fecha);
    }

    public function updatePassword(request $request)
    {
        
        $prescriptor= Prescriptor::where('IdComisionista', '=', $request->prescriptor)->first();
        $prescriptor->AccesoPass=$request->password2;
        $prescriptor->save();


        //return $prescriptor;
        if($prescriptor){
            return view('comisionistas.pass.passChange')->with('pass', $request->password2);
        }
        return view('comisionistas.pass.pass')->with('prescriptor', $request->prescriptor);
        
    }

    public static function actualizarContador(){
        $codigo=DB::table('lsysContadores')
        ->select('sysContadorValor')
        ->where('sysNombreContador', '=', 'CODCOMISIONISTA')
        ->get();

        $codigoUpdate = $codigo[0]->sysContadorValor;

        DB::table('lsysContadores')        
        ->where('sysNombreContador', '=', 'CODCOMISIONISTA')
        ->Update(['sysContadorValor' =>$codigoUpdate +1]);

        return $codigoUpdate;
    }    

    // public static function obtenerCodigoComisionita(){ 
    //     $codigo=DB::table('lsysContadores')
    //     ->select('sysContadorValor')
    //     ->where('sysNombreContador', '=', 'CODCOMISIONISTA')
    //     ->where('sysGrupo', '=', session('codigoEmpresa'))
    //     ->get();        

    //     return $codigo;
    // }

    public static function obtenerCodigoAccesoUsuario(){ 
        $acceso=DB::select('SELECT MAX(CodigoComisionista)as cod FROM Comisionistas');

        return $acceso;
    }

    public static function obtenerGuid(){ 
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function codigoClienteNuevo(){
        $query = DB::table('Clientes')->selectRaw('max(cast(CodigoCliente as bigint)) AS numeroMaximo')
        ->where('CodigoEmpresa','=',session('codigoEmpresa'))->first();
        return $query->numeroMaximo;
    }    

    public static function insertarNuevoPrescriptores(){
        
        $insertadoCorrectamente = "ERROR";

        if($_POST['datos']['FechaBajaLc'] == ''){
            $_POST['datos']['FechaBajaLc'] = Null;
        }

        $cuentaContableComisionista = 465000000 + $_POST['datos']['CodigoComisionista'];

        //comprobar si existe el dni ingresado para crear incidencia de cambio comercial
        $comprobarPrescriptor = DB::table('Comisionistas')
        ->where('CifDni', '=', $_POST['datos']['CifDni'])
        ->get();

        if(count($comprobarPrescriptor)>0){
            
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
                'CodigoComisionista'=>$_POST['datos']['CodigoComisionista'],
                'CodigoJefeVenta_'=>session('codigoComisionista')                 
            ]);
            // prescriptor intenta cambiar comercial
            if($nuevaCabecera == 1 && $comprobarPrescriptor[0]->CodigoJefeVenta_ != session('codigoComisionista') && $_POST['datos']['VcodigoTipoPrescriptor'] != 2){
                $lineaIncidencia= DB::table('ABMS_LineasIncidencias')->insert([
                    "CodigoEmpresa"=>session('codigoEmpresa'),
                    "EjercicioIncidencia"=> date("Y"),
                    "VNumeroIncidencia"=>$query[0]->sysContadorValor + 1,
                    "Descripcion"=>'El Prescriptor intenta cambiar del comercial '.$comprobarPrescriptor[0]->CodigoJefeVenta_ .' al comercial '. session('codigoComisionista'),
                    "VTipoIncidencia"=>"P",                    
                    "Orden"=> 0
                ]);

                if ($lineaIncidencia == 1){
                    $correcto = "Este prescriptor ya pertenece a otro comercial, pregunta en administracion si es posible el trapaso del prescriptor";

                    $actualizarC = DB::table('lsysContadores')
                    ->where('sysGrupo', '=' ,session('codigoEmpresa'))
                    ->where("sysNombreContador", '=' ,"INCIDENCIASP")
                    ->where('sysEjercicio', '=', date('Y'))
                    ->update(['sysContadorValor'=>$query[0]->sysContadorValor + 1]);
                }
            // prescriptor existente
            }else if($nuevaCabecera == 1 && $comprobarPrescriptor[0]->CodigoJefeVenta_ == session('codigoComisionista') && $_POST['datos']['VcodigoTipoPrescriptor'] != 2){
                $lineaIncidencia= DB::table('ABMS_LineasIncidencias')->insert([
                    "CodigoEmpresa"=>session('codigoEmpresa'),
                    "EjercicioIncidencia"=> date("Y"),
                    "VNumeroIncidencia"=>$query[0]->sysContadorValor + 1,
                    "Descripcion"=>'El Prescriptor ya esta registrado con el comercial '.$comprobarPrescriptor[0]->CodigoJefeVenta_,
                    "VTipoIncidencia"=>"P",                    
                    "Orden"=> 0
                ]);

                if ($lineaIncidencia == 1){
                    $correcto = 'El Prescriptor ya esta registrado con el comercial'.$comprobarPrescriptor[0]->CodigoJefeVenta_.' mire bien en la tabla';

                    $actualizarC = DB::table('lsysContadores')
                    ->where('sysGrupo', '=' ,session('codigoEmpresa'))
                    ->where("sysNombreContador", '=' ,"INCIDENCIASP")
                    ->where('sysEjercicio', '=', date('Y'))
                    ->update(['sysContadorValor'=>$query[0]->sysContadorValor + 1]);
                }
            //centro vendedor  intenta cambiar comercial
            }

            return $correcto;

        }else{

                $direccion = $_POST['datos']['Direccion'].','.$_POST['datos']['Municipio'].','.$_POST['datos']['nombrePais'];
                $geo = self::latitudyLongitud($direccion);
                $lat = $geo["results"][0]["geometry"]["location"]["lat"];
                $lng = $geo["results"][0]["geometry"]["location"]["lng"];

                $fechaAlta = new \dateTime ('now');
                $nuevoPrescriptor = Prescriptor::insert([
                    'IdComisionista'=>$_POST['datos']['IdComisionista'],
                    'AccesoUsuario'=>$_POST['datos']['AccesoUsuario'],
                    'CifDni'=> $_POST['datos']['CifDni'],
                    'CodigoComisionista'=>$_POST['datos']['CodigoComisionista'],  
                    'CodigoEmpresa'=>session('codigoEmpresa'),
                    '%Comision'=>$_POST['datos']['Comision'],                                  
                                                     
                    'Comisionista'=>$_POST['datos']['Comisionista'],            
                    'Domicilio'=>$_POST['datos']['Direccion'],
                    'EMail1'=>$_POST['datos']['EMail1'],                    
                    'CuentaContable'=>$cuentaContableComisionista,
                    'Municipio'=>$_POST['datos']['Municipio'],
                    'CodigoNacion'=>$_POST['datos']['Nacion'],
                    'Observaciones'=>$_POST['datos']['Observaciones'],
                    'CodigoProvincia'=>$_POST['datos']['Provincia'],
                    'Telefono'=>$_POST['datos']['Telefono'],
                    'Telefono2'=>$_POST['datos']['Telefono2'],
                    'VLatitud'=>$lat,
                    'VLongitud'=>$lng,
                    
                    'CodigoJefeVenta_'=>$_POST['datos']['VComisionista'],                    
                    'CodigoPostal'=>$_POST['datos']['codigoPostal'],
                    
                    'Provincia'=>$_POST['datos']['nombreProvincia'],
                    'Nacion'=>$_POST['datos']['nombrePais'],
                    'FechaBajaLc'=>$_POST['datos']['FechaBajaLc'],
                    'AccesoPass'=>1234,                                       
                ]);
            

            $insertadoCorrectamente = "OK";
            return $insertadoCorrectamente;


        }
    }

    public static function latitudyLongitud($direccion){
        
        $google_maps_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($direccion) ."&key=AIzaSyC6R6EBZzAGgThM28ap0hOAqTQg5Wr4Onk";
		$google_maps_json = file_get_contents($google_maps_url);
		$google_maps_array = json_decode($google_maps_json, true);		
        
        return $google_maps_array;
    }


    public static function actualizarComisionista(){

        
        if($_POST['datos']['VLatitud'] == .0000000000 && $_POST['datos']['VLongitud'] == .0000000000){

            $direccion = $_POST['datos']['Direccion'].','.$_POST['datos']['Municipio'].','.$_POST['datos']['nombrePais'];
            $geo = self::latitudyLongitud($direccion);
            $lat = $geo["results"][0]["geometry"]["location"]["lat"];
            $lng = $geo["results"][0]["geometry"]["location"]["lng"];


            $insertadoCorrectamente = "ERROR";        
            $nuevoPrescriptor = Prescriptor::where('IdComisionista', '=', $_POST['datos']['IdComisionista'],)
            ->update([
                'CifDni'=> $_POST['datos']['CifDni'],
                '%Comision'=>$_POST['datos']['Comision'],
                'VLatitud'=>$lat,                                  
                'VLongitud'=>$lng,                                  
                //'%Comision2_'=>$_POST['datos']['Comision2'],                                  
                //'VDescuentoCli'=>$_POST['datos']['Comision'],                                  
                //'Comisionista'=>$_POST['datos']['VDescuentoCli'],            
                'Domicilio'=>$_POST['datos']['Direccion'],
                'EMail1'=>$_POST['datos']['EMail1'],
                //'IBAN'=>$_POST['datos']['IBAN'],
                'Municipio'=>$_POST['datos']['Municipio'],
                'CodigoNacion'=>$_POST['datos']['Nacion'],
                'Observaciones'=>$_POST['datos']['Observaciones'],
                'CodigoProvincia'=>$_POST['datos']['Provincia'],
                'Telefono'=>$_POST['datos']['Telefono'],
                'Telefono2'=>$_POST['datos']['Telefono2'],            
                //'VcodigoTipoPrescriptor'=>$_POST['datos']['VcodigoTipoPrescriptor'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],        
                'Provincia'=>$_POST['datos']['nombreProvincia'],
                'Nacion'=>$_POST['datos']['nombrePais'],
                'FechaBajaLc'=>$_POST['datos']['FechaBajaLc']
            ]);

            $insertadoCorrectamente = "OK";
            return $insertadoCorrectamente;
            
        }else{
        
            $insertadoCorrectamente = "ERROR";        
            $nuevoPrescriptor = Prescriptor::where('IdComisionista', '=', $_POST['datos']['IdComisionista'],)
            ->update([
                'CifDni'=> $_POST['datos']['CifDni'],
                '%Comision'=>$_POST['datos']['Comision'],                                  
                //'%Comision2_'=>$_POST['datos']['Comision2'],                                  
                //'VDescuentoCli'=>$_POST['datos']['Comision'],                                  
                //'Comisionista'=>$_POST['datos']['VDescuentoCli'],            
                'Domicilio'=>$_POST['datos']['Direccion'],
                'EMail1'=>$_POST['datos']['EMail1'],
                //'IBAN'=>$_POST['datos']['IBAN'],
                'Municipio'=>$_POST['datos']['Municipio'],
                'CodigoNacion'=>$_POST['datos']['Nacion'],
                'Observaciones'=>$_POST['datos']['Observaciones'],
                'CodigoProvincia'=>$_POST['datos']['Provincia'],
                'Telefono'=>$_POST['datos']['Telefono'],
                'Telefono2'=>$_POST['datos']['Telefono2'],            
                //'VcodigoTipoPrescriptor'=>$_POST['datos']['VcodigoTipoPrescriptor'],
                'CodigoPostal'=>$_POST['datos']['codigoPostal'],        
                'Provincia'=>$_POST['datos']['nombreProvincia'],
                'Nacion'=>$_POST['datos']['nombrePais'],
                'FechaBajaLc'=>$_POST['datos']['FechaBajaLc']
            ]);

            $insertadoCorrectamente = "OK";
            return $insertadoCorrectamente;
        }
    }

    // public static function busquedaClienteComisionista(){
    //     $query = DB::table('Comisionistas')
    //     ->select('VCentroVendedor')
    //     ->where('CodigoComisionista', '=', $_POST['comisionista'])
    //     ->get();
    //     return $query[0]->VCentroVendedor;
    // }

    public static function busquedaClienteComisionista($comisionista){
        $query = DB::table('Comisionistas')
        ->select('VCliente')
        ->where('CodigoComisionista', '=', $comisionista)
        ->get();
        return $query[0]->VCliente;
    }

    // public static function obtenerPrescriptor($comisionista){
    //     $query = Prescriptor::select('CifDni', 'CodigoComisionista', 'CodigoEmpresa', '%Comision AS Comision', '%Comision2_ AS Comision2', 'Comisionista', 'Domicilio', 'EMail1', 'CodigoPostal',
    //     'FechaBajaLc', 'IBAN', 'IdComisionista', 'Municipio', 'CodigoNacion', 'Observaciones', 'CodigoProvincia', 'Telefono', 'Telefono2', 'CodigoJefeVenta_', 'VCodigoTipoPrescriptor', 'VFecha', 'CodigoPostal', 'VDescuentoCli')
    //     ->where('IdComisionista', '=', $comisionista)
    //     ->get();
    //     return $query[0];
    // }


    public static function obtenerComisionista($comisionista){
        $query = Prescriptor::select('CifDni', 'CodigoComisionista', 'CodigoEmpresa', '%Comision AS Comision', 'Comisionista', 'Domicilio', 'EMail1', 'CodigoPostal',
        'FechaBajaLc', 'IdComisionista', 'Municipio', 'CodigoNacion', 'Observaciones', 'CodigoProvincia', 'Telefono', 'Telefono2', 'CodigoJefeVenta_', 'CodigoPostal'
        ,'VLatitud','VLongitud')
        ->where('IdComisionista', '=', $comisionista)
        ->get();
        return $query[0];
    }

    public static function firmaCompleta(){
        
        $pdf = $_POST['pdf'];
        $firma = $_POST['firma'];
        $guid = self::obtenerGuid();        
        $fecha2 = date("Y-m-d");

        //return $fecha2;

        $bin = urldecode($pdf);
        $bin2 = utf8_decode($firma);
        $bin3 = urldecode($fecha2);
        $imagen = '<img src="'.$bin2.'">';

        $html = $bin3.$bin.$imagen;

        $mpdf = new \Mpdf\Mpdf();        
        $mpdf->WriteHTML($html);
        //$mpdf->Output($_POST['id'].'contrato.pdf');

        $mpdf->Output("./media/pdf/contrato/".$_POST['tipo'].$_POST['id']."contrato.pdf");

        $contrato = Prescriptor::where('IdComisionista', '=', $_POST['id'],)
        ->update([
            'VFechaContrato'=> $fecha2,
            'VPdfContrato'=> asset('/media/pdf/contrato/'.$_POST['tipo'].$_POST['id'].'contrato.pdf'),
            'ImagenExt'=>$guid,
        ]);
        
        $firmaImagen = DB::insert("INSERT INTO lsysBinary (sysIdBinario,sysBinario,sysTipoBinario) VALUES ('".$guid."', CONVERT(VARBINARY(MAX),'".$firma."'),'1' )");

        return asset('media/pdf/contrato/'.$_POST['tipo'].$_POST['id'].'contrato.pdf');

    }

    public static function firmargpd(){
        
        $pdf = $_POST['pdf'];
        $firma = $_POST['firma'];
        $guid = self::obtenerGuid();        
        $fecha2 = date("Y-m-d");

        //return $fecha2;

        $bin = urldecode($pdf);
        $bin2 = utf8_decode($firma);
        $bin3 = urldecode($fecha2);
        $imagen = '<img src="'.$bin2.'">';

        $html = $bin3.$bin.$imagen;

        $mpdf = new \Mpdf\Mpdf();        
        $mpdf->WriteHTML($html);
        //$mpdf->Output($_POST['id'].'rgpd.pdf');

        $mpdf->Output("./media/pdf/rgpd/".$_POST['tipo'].$_POST['id']."rgpd.pdf");

        $contrato = Cliente::where('IdCliente', '=', $_POST['id'],)
        ->update([
            'VFechaRgpd'=> $fecha2,
            'VPdfRgpd'=> asset('/media/pdf/rgpd/'.$_POST['tipo'].$_POST['id'].'rgpd.pdf'),
            'ImagenExt'=>$guid,
        ]);
        
        //$firmaImagen = DB::insert("INSERT INTO lsysBinary (sysIdBinario,sysBinario,sysTipoBinario) VALUES ('".$guid."', CONVERT(VARBINARY(MAX),'".$firma."'),'1' )");

        return asset('media/pdf/rgpd/'.$_POST['tipo'].$_POST['id'].'rgpd.pdf');

    }

    public static function firmarsepa(){
        
        $pdf = $_POST['pdf'];
        $firma = $_POST['firma'];
        $guid = self::obtenerGuid();        
        $fecha2 = date("Y-m-d");

        //return $fecha2;

        $bin = urldecode($pdf);
        $bin2 = utf8_decode($firma);
        $bin3 = urldecode($fecha2);
        $imagen = '<img src="'.$bin2.'">';

        $html = $bin3.$bin.$imagen;

        $mpdf = new \Mpdf\Mpdf();        
        $mpdf->WriteHTML($html);
        //$mpdf->Output($_POST['id'].'rgpd.pdf');

        $mpdf->Output("./media/pdf/sepa/".$_POST['tipo'].$_POST['id']."sepa.pdf");

        $contrato = Cliente::where('IdCliente', '=', $_POST['id'],)
        ->update([
            'VFechaSepa'=> $fecha2,
            'VPdfSepa'=> asset('/media/pdf/sepa/'.$_POST['tipo'].$_POST['id'].'sepa.pdf'),
            'ImagenExt1'=>$guid,
        ]);
        
        //$firmaImagen = DB::insert("INSERT INTO lsysBinary (sysIdBinario,sysBinario,sysTipoBinario) VALUES ('".$guid."', CONVERT(VARBINARY(MAX),'".$firma."'),'1' )");

        return asset('media/pdf/sepa/'.$_POST['tipo'].$_POST['id'].'sepa.pdf');

    }

    public static function comprobarSiElContratoEstaFirmado($id){
        $contrato = prescriptor::select('VFechaContrato')
        ->where('IdComisionista', '=', $id)
        ->get();

        if(count($contrato)>0 && $contrato[0]->VFechaContrato != null){
            return 'null';
        }else{
            return 'ok';
        }

    }

    public static function comprobarSiLaRgpdEstaFirmado($id){
        $contrato = Cliente::select('VFechaRGPD')
        ->where('IdCliente', '=', $id)
        ->get();

        if(count($contrato)>0 && $contrato[0]->VFechaRGPD != null){
            return 'null';
        }else{
            return 'ok';
        }
    }

    public static function comprobarSiSepaEstaFirmado($id){
        $contrato = Cliente::select('VFechaSepa')
        ->where('IdCliente', '=', $id)
        ->get();

        if(count($contrato)>0 && $contrato[0]->VFechaSepa != null){
            return 'null';
        }else{
            return 'ok';
        }
    }
    
    public static function firmaContratoNutricionista(request $request ){
        
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);
        
        $comprobar = self::comprobarSiElContratoEstaFirmado($prescriptor);
        
        if($comprobar == 'ok'){
            return view('contratos.contratoNutricionista');
        }else{
            return view('contratos.yaFirmado');
        }
        
    }

    public static function firmaContratoCentroVendedor(request $request ){
        
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);
        
        $comprobar = self::comprobarSiElContratoEstaFirmado($prescriptor);
        
        if($comprobar == 'ok'){
            return view('contratos.contratoCentroVendedor');
        }else{
            return view('contratos.yaFirmado');
        }
        
    }
    
    public static function firmaContratoMedico(request $request ){
        
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);
        
        $comprobar = self::comprobarSiElContratoEstaFirmado($prescriptor);
        
        if($comprobar == 'ok'){
            return view('contratos.contratoMedico');
        }else{
            return view('contratos.yaFirmado');
        }
        
    }

    public static function datosContratoEmpresa(request $request){
        return view('contratos.datosEmpresa');
    }

    public static function datosRgpdEmpresa(request $request){
        return view('contratos.datosEmpresaRgpd');
    }

    public static function datosContratoCentroVendedor(request $request){
        return view('contratos.datosCentroVendedor');
    }

    public static function firmaContratoEmpresa(request $request ){
        
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);
        
        $comprobar = self::comprobarSiElContratoEstaFirmado($prescriptor);
        
        if($comprobar == 'ok'){
            return view('contratos.contratoEmpresa');
        }else{
            return view('contratos.yaFirmado');
        }
        
    }


    public static function firmaRgpdE (request $request){
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);

        //return $datos;

        $comprobar = self::comprobarSiLaRgpdEstaFirmado($prescriptor);

        if($comprobar == 'ok'){
            return view('clientes.contratos.rgpdEmpresa');
        }else{
            return view('clientes.contratos.yaFirmado');
        }
    }


    public static function rgpdProfesional (request $request){

        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);

        $comprobar = self::comprobarSiLaRgpdEstaFirmado($prescriptor);

        if($comprobar == 'ok'){
            return view('clientes.contratos.rgpdProfesional');
        }else{
            return view('clientes.contratos.yaFirmado');
        }

    }

    public static function firmaSepa (request $request){
        $datos = explode("/", $request); 
        $prescriptor = substr($datos[3],0,36);

        $comprobar = self::comprobarSiSepaEstaFirmado($prescriptor);

        if($comprobar == 'ok'){
            return view('clientes.contratos.sepa');
        }else{
            return view('clientes.contratos.yaFirmado');
        }
    }

    public static function direccionesPrescriptores(){
        $query = Prescriptor::select('VLatitud', 'VLongitud', 'CodigoComisionista')
        ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('VLatitud', '<>', 0)
        ->where('VLongitud', '<>', 0)
        //->groupBy('CodigoComisionista', 'VLatitud', 'VLongitud')
        ->get();

        return $query;
    }

    public static function usuarioCrm($id){
        $query = Prescriptor::select('AccesoUsuario')
        ->where('IdComisionista', '=', $id)                
        ->get();

        return $query;
    }

}
