<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCorreo;
use App\Mail\EnviarFicha;
use App\Mail\EnviarOferta;
use App\Models\CabeceraAlbaranClienteModel;
use App\Models\Cliente;
use App\Models\ClienteConta;
use App\Models\LineasAlbaranClienteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MongoDB\Driver\Session;
use PhpParser\Node\Expr\Cast\Array_;

class OfertaController extends Controller
{
    public static function codigoGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }


    static function insercionOferta()
    {
        $correcto = "ERROR";

        $array = $_POST['datos']['lineas'];
        $cliente = ClienteController::obtenerDatosClientePedido($_POST['datos']['codigoCliente']);
        if($cliente[0]['CodigoCategoriaCliente_'] == 'POT'){
            $clienteConta = ClienteController::obtenerDatosClientePedidoConta('43000000000');
        }else{
            $clienteConta = ClienteController::obtenerDatosClientePedidoConta($_POST['datos']['codigoCliente']);
        }
        $comisionista = ComisionistaController::obtenerDatosComisionistaPedido(session('codigoComisionista'));

        //INSERCIÓN OFERTA
        //comprobación para ver si existe la cabecera, si ya existe no se vuelve a insertar
        $existeCabeceraPedido = self::comprobarExisteCabeceraOferta($_POST['datos']['idPedido'], $_POST['datos']['seriePedido']);
        //return $existeCabeceraPedido;
        $orden = 5;        

        if ($existeCabeceraPedido == false) {            
            
            $quiery = DB::table('Comisionistas')
                ->select('CodigoJefeVenta_')
                ->where('CodigoComisionista', '=', session('codigoComisionista'))
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();

            $cabecera = DB::table('CabeceraOfertaCliente')
            ->selectRaw('*, [%ProntoPago] as ProntoPago')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoCliente', '=', $_POST['datos']['codigoCliente'])
            ->orderby('FechaOferta', 'Desc')
            ->take(1)
            ->get();

            $codigoCondiciones = 0;
            if ($cabecera->count() != null) {
                $cabecera[0]->CodigoCondiciones = $codigoCondiciones;
            }
                                    
            if($clienteConta[0]['CodigoCondiciones'] != $codigoCondiciones || isset($cliente) ){
                $CodigoCondiciones = $clienteConta[0]['CodigoCondiciones'];
                $DiasPrimerPlazo = $clienteConta[0]['DiasPrimerPlazo'];
                $DiasFijos1 = $clienteConta[0]['DiasFijos1'];
                $DiasFijos2 = $clienteConta[0]['DiasFijos2'];
                $DiasFijos3 = $clienteConta[0]['DiasFijos3'];
                $DiasRetroceso = $clienteConta  [0]['DiasRetroceso'];
                $FormadePago = $cliente[0]['FormadePago'];
                $MesesComerciales = $clienteConta[0]['MesesComerciales'];
                $ControlarFestivos = $clienteConta[0]['ControlarFestivos'];
                $RemesaHabitual = $clienteConta[0]['RemesaHabitual'];
                $CodigoTipoEfecto = $clienteConta[0]['CodigoTipoEfecto'];
                $CodigoBanco = $cliente[0]['CodigoBanco'];
                $CodigoAgencia = $cliente[0]['CodigoAgencia'];
                $DC = $cliente[0]['DC'];
                $CCC = $cliente[0]['CCC'];
                $CopiasOferta = $cliente[0]['CopiasOferta'];
                $CopiasPedido = $cliente[0]['CopiasPedido'];
                $CopiasAlbaran = $cliente[0]['CopiasAlbaran'];
                $CopiasFactura = $cliente[0]['CopiasFactura'];
                $IndicadorIva = $cliente[0]['IndicadorIva'];
                $TipoPortesEnvios = $cliente[0]['TipoPortes'];
                $CodigoTransaccion = $clienteConta[0]['CodigoTransaccion'];
                $AgruparAlbaranes = $cliente[0]['AgruparAlbaranes'];
                $MantenerCambio_ = $cliente[0]['MantenerCambio_'];
                //$FactorCambio = $cliente[0]['FactorCambio'];
                $ReferenciaMandato = $cliente[0]['ReferenciaMandato'];
                //$ProntoPago = $cliente[0]['%ProntoPago'];
                $NumeroPlazos = $clienteConta[0]['NumeroPlazos'];
                $DiasEntrePlazos = $clienteConta[0]['DiasEntrePlazos'];
                $InicioNoPago = $clienteConta[0]['InicioNoPago'];
                $FinNoPago = $clienteConta[0]['FinNoPago'];

            }else{
                
                $CodigoCondiciones = $cabecera[0]->CodigoCondiciones;
                $DiasPrimerPlazo = $cabecera[0]->DiasPrimerPlazo;
                $DiasFijos1 = $cabecera[0]->DiasFijos1;
                $DiasFijos2 = $cabecera[0]->DiasFijos2;
                $DiasFijos3 = $cabecera[0]->DiasFijos3;
                $DiasRetroceso = $cabecera[0]->DiasRetroceso;
                $FormadePago = $cabecera[0]->FormadePago;
                $MesesComerciales = $cabecera[0]->MesesComerciales;
                $ControlarFestivos = $cabecera[0]->ControlarFestivos;
                $RemesaHabitual = $cabecera[0]->RemesaHabitual;
                $CodigoTipoEfecto = $cabecera[0]->CodigoTipoEfecto;
                $CodigoBanco = $cabecera[0]->CodigoBanco;
                $CodigoAgencia = $cabecera[0]->CodigoAgencia;
                $DC = $cabecera[0]->DC;
                $CCC = $cabecera[0]->CCC;
                $CopiasOferta = $cabecera[0]->CopiasOferta;
                $CopiasPedido = $cabecera[0]->CopiasPedido;
                $CopiasAlbaran = $cabecera[0]->CopiasAlbaran;
                $CopiasFactura = $cabecera[0]->CopiasFactura;
                $IndicadorIva = $cabecera[0]->IndicadorIva;
                $TipoPortesEnvios = $cabecera[0]->TipoPortesEnvios;
                $CodigoTransaccion = $cabecera[0]->CodigoTransaccion;
                $AgruparAlbaranes = $cabecera[0]->AgruparAlbaranes;
                $MantenerCambio_ = $cabecera[0]->MantenerCambio_;
                //$FactorCambio = $cabecera[0]->FactorCambio;
                $ReferenciaMandato = $cabecera[0]->ReferenciaMandato;
                //$ProntoPago = $cabecera[0]->ProntoPago;
                $NumeroPlazos = $cabecera[0]->NumeroPlazos;
                $DiasEntrePlazos = $cabecera[0]->DiasEntrePlazos;
                $InicioNoPago = $cabecera[0]->InicioNoPago;
                $FinNoPago = $cabecera[0]->FinNoPago;

            }



            $pedido = DB::table('CabeceraOfertaCliente');
            $pedido->insert([
                "CodigoEmpresa" => session('codigoEmpresa'),
                "CodigoCliente" => $_POST['datos']['codigoCliente'],
                "EjercicioOferta" => date("Y"),
                "SerieOferta" => $_POST['datos']['seriePedido'],
                "NumeroOferta" => $_POST['datos']['idPedido'],
                "FechaOferta" => date("Y-m-d") . " " . date("H:i:s"),                
                "CIFDNI" => $cliente[0]['CifDni'],
                "CIFEuropeo" => "ES" . $cliente[0]['CifDni'],
                "RazonSocial" => substr($cliente[0]['RazonSocial'],0,35),
                "Nombre"=>substr($cliente[0]['RazonSocial'],0,35),
                "Domicilio" => $cliente[0]['Domicilio'],
                "CodigoPostal" => $cliente[0]['CodigoPostal'],
                "CodigoMunicipio" => $cliente[0]['CodigoMunicipio'],
                "Municipio" => $cliente[0]['Municipio'],
                "CodigoProvincia" => $cliente[0]['CodigoProvincia'],
                "Provincia" => $cliente[0]['Provincia'],
                "CodigoNacion" => $cliente[0]['CodigoNacion'],
                "Nacion" => $cliente[0]['Nacion'],
                "CodigoCondiciones" => $CodigoCondiciones,
                "NumeroPlazos" => "$NumeroPlazos",
                "CodigoContable" => $cliente[0]['CodigoContable'],
                "IBAN" => $cliente[0]['IBAN'],                                
                "%Descuento" => $cliente[0]['%Descuento'],
                "CodigoComisionista" => session('codigoComisionista'),
                "ComercialAsignadoLc"=>session('codigoComisionista'),
                "StatusAprobado" => 0,
                "CodigoJefeVenta_" => $quiery[0]->CodigoJefeVenta_,
                "DiasPrimerPlazo" => $DiasPrimerPlazo,
                "DiasEntrePlazos" => $DiasEntrePlazos,
                "DiasFijos1" => $DiasFijos1,
                "DiasFijos2" => $DiasFijos2,
                "DiasFijos3" => $DiasFijos3,
                "DiasRetroceso" => $DiasRetroceso,
                "FormadePago" => $FormadePago,
                "MesesComerciales" => $MesesComerciales,
                "ControlarFestivos" => $ControlarFestivos,
                "InicioNoPago" => $InicioNoPago,
                "FinNoPago" => $FinNoPago,
                "RemesaHabitual" => $RemesaHabitual,
                "CodigoTipoEfecto" => $CodigoTipoEfecto,
                "CodigoBanco" => $CodigoBanco,
                "CodigoAgencia" => $CodigoAgencia,
                "DC" => $DC,
                "CCC" => $CCC,
                "CopiasOferta" => $CopiasOferta,
                "CopiasPedido" => $CopiasPedido,
                "CopiasAlbaran" => $CopiasAlbaran,
                "CopiasFactura" => $CopiasFactura,
                "AgruparAlbaranes" => -1,


                //"%ProntoPago"=> $ProntoPago,
                "IndicadorIva" => $IndicadorIva,
                "TipoPortesEnvios" => $TipoPortesEnvios,
                "CodigoTransaccion" => $CodigoTransaccion,
                "AgruparAlbaranes" => $AgruparAlbaranes,
                "MantenerCambio_" => $MantenerCambio_,
                //"FactorCambio" => $FactorCambio,
                "ReferenciaMandato" => $ReferenciaMandato,
                "AlbaranValorado"=>0,

                "StatusAprobado" => 0,                                                
            ]);
           

            if ($pedido) $correcto = "OK";
            else $correcto = "Error cabecera oferta";
        }  
        
        $numeroOrdenLinea = self::obtenerUltimoNumeroOrdenOferta($_POST['datos']['idPedido'], $_POST['datos']['seriePedido']);
        if (!empty($numeroOrdenLinea)) {
            //para establecer el siguiente número que le tocaría
            $orden = $numeroOrdenLinea + 5;
        }
        $comisionista = ComisionistaController::obtenerDatosComisionistaPedido(session('codigoComisionista'));

        foreach ($array as $linea) {
                $recargo = 0;
                $ivaArticulo = 0;
                $codigoIva = 0;


            $existeArticuloEnPedido = self::comprobarExisteArticuloEnOferta($_POST['datos']['idPedido'], $_POST['datos']['seriePedido'], $linea['codigo'],$linea['orden']);
            if ($existeArticuloEnPedido == false) {
                self::actualizarCantidadLineasCabeceraOferta($_POST['datos']['seriePedido'], $_POST['datos']['idPedido']);
                $articulo = ArticuloController::buscarArticulo($linea['codigo'], $_POST['datos']['codigoCliente']);                                

                //obtenemos iva de cada articulo
                if ($articulo[0]['GI'] == 1) {
                    $ivaArticulo = 21;
                    $codigoIva = 1;
                }
                if ($articulo[0]['GI'] == 2) {
                    $ivaArticulo = 10;
                    $codigoIva = 2;
                }
                if ($articulo[0]['GI'] == 3) {
                    $ivaArticulo = 4;
                    $codigoIva = 3;
                }
                $guidArticulo = self::codigoGuid();


                if($articulo[0]['Recargo'] == NULL){
                    $articulo[0]['Recargo'] = 0;
                }

                $datos = DB::table('Articulos')
                ->select('PrecioCompra', '%Margen as Margen')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('CodigoArticulo', '=', $linea['codigo'])
                ->get();

                $importeBruto = $linea['precio'] * $linea['cantidad'];
                $recargo = $importeBruto * $articulo[0]['Recargo'] / 100;
                $importeDescuento = $importeBruto *  $linea['descuento'] / 100;
                $importeNeto = $importeBruto - $importeDescuento;
                //$descuentoArticulo =$importeNeto * session('descuento') / 100;
                $descuentoCliente = $importeNeto * $cliente[0]['%Descuento'] / 100;
                $baseImponible = $importeNeto - $descuentoCliente;
                $cuotaiva = ($baseImponible * $ivaArticulo / 100);
                $totalIva = $cuotaiva + $recargo;
                $importeLiquido = $importeNeto + $totalIva; 

                //$margen = $datos[0]->Margen;
                if(count($datos)>0){
                    $precioCompra = $datos[0]->PrecioCompra;
                }else{
                    $precioCompra = 0;
                }
                
                $margenDeBeneficio = ($linea['precio'] - $importeDescuento - $precioCompra) * $linea['cantidad'];

                if($importeNeto == 0){
                    if($importeNeto == 0 && $linea['precio'] == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        $porMargenBeneficio = 100;
                    }
                }else{    
                    //$precio2 = $linea['precio'] - $importeDescuento;
                    $porMargenBeneficio = ($margenDeBeneficio  * 100) / $importeNeto;
                }                    
                

                $lineaPedido = DB::table('LineasOfertaCliente');
                $lineaPedido->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "CodigoDelCliente" => $_POST['datos']['codigoCliente'],
                    "EjercicioOferta" => date("Y"),
                    'CodigoAlmacen' => '01',
                    "SerieOferta" => $_POST['datos']['seriePedido'],
                    "NumeroOferta" => $_POST['datos']['idPedido'],
                    'LineasPosicion' => $guidArticulo,
                    "CodigoArticulo" => $linea['codigo'],
                    "DescripcionArticulo" => $linea['descripcion'],
                    //"CodigoAlmacenAnterior"=>'01',
                    "CodigoFamilia" => $articulo[0]['CodigoFamilia'],
                    "CodigoSubFamilia" => $articulo[0]['CodigoSubfamilia'],
                    "TipoArticulo" => $articulo[0]['TipoArticulo'],
                    //"FechaEntrega"=>session('fechaEntrega'). " 00:00:00.000",
                    //"ReservarStock_" => -1,
                    "Estado" => 0,
                    "GrupoIva" => $codigoIva,
                    "CodigoIva" => $ivaArticulo,
                    "%Iva" => $ivaArticulo,
                    //"UnidadesPendientesFabricar" => $linea['cantidad'],
                    "UnidadesPedidas" => $linea['cantidad'],
                    //"UnidadesPendientes" => $linea['cantidad'],
                    //"UnidadesServidas" => 0,
                    "Unidades2_" => $linea['cantidad'],
                    "Precio" => $linea['precio'],
                    "CodigoComisionista" => session('codigoComisionista'),
                    "%Recargo"=> $articulo[0]['Recargo'],
                    "CuotaRecargo"=> $recargo,
                    "%Descuento" => $linea['descuento'],
                    "Orden" => $orden,
                    "CodigoJefeVenta_" => $comisionista[0]['CodigoJefeVenta_'],
                    "%Comision" => $comisionista[0]['%Comision'],
                    "ImporteBruto" => $importeBruto,
                    //"ImporteBrutoPendiente" => $importeBruto,
                    "ImporteDescuento" => $importeDescuento,
                    "ImporteNeto" => $importeNeto,
                    //"ImporteNetoPendiente" => $importeNeto,
                    "ImporteDescuentoCliente" => $descuentoCliente,
                    "BaseImponible" => $baseImponible,
                    "BaseIva" => $baseImponible,
                    "CuotaIva" => $cuotaiva,
                    "TotalIva" => $totalIva,
                    "ImporteLiquido" => $importeLiquido,

                    //"UnidadesPendAnterior"=>$linea['cantidad'],
                    "PrecioCoste"=>$precioCompra ,
                    "ImporteCoste"=>$precioCompra * $linea['cantidad'] ,
                    "ImporteParcial"=> $importeBruto,
                    // "ImporteParcialPendiente"=>$importeBruto,
                    // "BaseImponiblePendiente"=>$baseImponible,
                    "PorMargenBeneficio"=>$porMargenBeneficio,
                    "MargenBeneficio"=>$margenDeBeneficio,
                    //"%Margen"=>$margen,

                    "CodigoTransaccion"=>1,
                    "CodigoDefinicion_"=>$articulo[0]['CodigoDefinicion_'],

                ]);
                
                $partida = "";
                

                if ($lineaPedido) {
                    $correcto = "OK";
                } else {
                    $correcto = "Error linea pedido";
                }                
                $orden = $orden + 5;   
            }


        }

            $cabeceraUpdate = DB::table('LineasOfertaCliente')
            ->selectRaw('Sum(ImporteBruto) as importeBruto, Sum(ImporteDescuento) as importeDescuento, Sum(ImporteNeto) as importeNeto, 
            Sum(ImporteDescuentoCliente) as importeDescuentoCliente, Sum(ImporteProntoPago) as importeProntoPago, Sum(BaseImponible) as baseImponible, 
            Sum(CuotaIva) as cuotaIva, Sum(CuotaRecargo) as cuotaRecargo, Sum(TotalIva) as totalIva, Sum(ImporteLiquido) as importeLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['datos']['seriePedido'])
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $_POST['datos']['idPedido'])
            ->get();

            $query2 = DB::table('CabeceraOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['datos']['seriePedido'])
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $_POST['datos']['idPedido'])
            ->update([

                "ImporteBruto" => $cabeceraUpdate[0]->importeBruto,
                "ImporteDescuentoLineas"=> $cabeceraUpdate[0]->importeDescuento,
                "ImporteNetoLineas"=> $cabeceraUpdate[0]->importeNeto,
                "BaseImponible"=> $cabeceraUpdate[0]->baseImponible,
                "TotalCuotaIva"=>$cabeceraUpdate[0]->cuotaIva,
                "TotalCuotaRecargo"=>$cabeceraUpdate[0]->cuotaRecargo,
                "TotalIva"=>$cabeceraUpdate[0]->totalIva,
                "ImporteLiquido"=> $cabeceraUpdate[0]->importeLiquido,                
            ]);
                    
        // Realizamos una ultima insercion para añadir las observaciones, si existen
        return $correcto;
    }


    public static function eliminarOferta()
    {
        $array = $_POST['datos']['lineas'];

        $query = DB::table('CabeceraOfertaCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('SerieOferta', '=', $_POST['seriePedido'])
            ->where('NumeroOferta', '=', $_POST['numeroPedido'])->delete();
        // if($query == 1 ){
        $query2 = DB::table('LineasOfertaCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('SerieOferta', '=', $_POST['seriePedido'])
            ->where('NumeroOferta', '=', $_POST['numeroPedido'])->delete();
        //   if($query2 == 1 ) {
        //SI SE HAN PODIDO ELIMINAR AMBOS REGISTROS ELIMINAMOS REGISTRO DE MOVIMIENTOS PENDIENTES
        

        $numero = $_POST['numeroPedido'];
        $serie = $_POST['seriePedido'];
        $nombreContador = 'OFERTA_CLI';

        self::contadorMenosOferta($serie, $numero, $nombreContador);

        $eliminado = "OK";
        
        return $eliminado;
    }
    
    /**
     * método para comprobar que ya exista pedido y se le coja el nuevo orden
     */
    public static function ultimoOrdenOferta()
    {
        $query = DB::table('LineasOfertaCliente')
            ->select('Orden')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['serie'])
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $_POST['pedido'])
            ->max('Orden');
        return $query + 5;
    }

    public static function obtenerUltimoNumeroOrdenOferta($numero, $serie)
    {
        $query = DB::table('LineasOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $serie)
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $numero)
            ->max('Orden');
        return $query;
    }

    public static function comprobarExisteCabeceraOferta($numero, $serie)
    {
        $existe = false;
        $query = DB::table('CabeceraOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $serie)
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $numero)->get();
        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function comprobarExisteArticuloEnOferta($numero, $serie, $codigoArticulo, $orden)
    {
        $existe = false;
        $query = DB::table('LineasOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $serie)
            ->where('EjercicioOferta', '=', date('Y'))
            ->where('NumeroOferta', '=', $numero)
            //->where('Orden', '=', $orden)
            ->where('CodigoArticulo', '=', $codigoArticulo)->get();

        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function obtenerGuidLineaPedido($seriePedido, $numeroPedido, $codigoArticulo)
    {
        $query = DB::table('LineasPedidoCliente')->select('LineasPosicion')
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))
            ->where('SeriePedido', $seriePedido)
            ->where('NumeroPedido', $numeroPedido)
            ->where('CodigoArticulo', $codigoArticulo)->get();
        return $query;
    }

    public static function actualizarEstadoLineaPedido($seriePedido, $numeroPedido, $codigoArticulo)
    {
        DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('SeriePedido', '=', $seriePedido)
            ->where('NumeroPedido', '=', $numeroPedido)
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('CodigoAlmacen', '=', '01')
            ->update(['Estado' => 2]);
    }
    public static function actualizarEstadoCabeceraPedido($seriePedido, $numeroPedido)
    {
        DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $seriePedido)
            ->where('NumeroPedido', '=', $numeroPedido)
            ->where('EjercicioPedido', '=', date('Y'))
            ->update(['Estado' => 2]);
    }
    public static function actualizarCantidadLineasCabeceraOferta($seriePedido, $numeroPedido)
    {
        $cantidadActual = self::obtenerCantidadLineasCabeceraOferta($seriePedido, $numeroPedido);
        DB::table('CabeceraOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $seriePedido)
            ->where('NumeroOferta', '=', $numeroPedido)
            ->where('EjercicioOferta', '=', date('Y'))
            ->update(['NumeroLineas' => $cantidadActual[0]->NumeroLineas + 1]);
    }
    
    public static function obtenerCantidadLineasCabeceraOferta($seriePedido, $numeroPedido)
    {
        $query = DB::table('cabeceraOfertacliente')->select('NumeroLineas', 'ImporteLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $seriePedido)
            ->where('NumeroOferta', '=', $numeroPedido)
            ->where('EjercicioOferta', '=', date('Y'))->get();
        return $query;
    }
        

    /**
     * MÉTODOS PARA ACTUALIZAR UNIDADES PRODUCTO CUANDO HAYA ALGÚN CAMBIO
     */
    public static function cambioUnidadesProducto()
    {
        $cantidadAnterior = self::obtenerCantidadProductoLineaPedido($_POST['seriePedido'], $_POST['numeroDocumento'], $_POST['codigoProducto']);
        self::actualizarUnidadesLineaPedidoCliente(
            $_POST['seriePedido'],
            $_POST['numeroDocumento'],
            $_POST['codigoProducto'],
            $_POST['nuevaCantidad'],
            $_POST['precio'],
            $_POST['dtoCliente'],
            $_POST['dtoArticulo'],
            $_POST['iva']
        );
        self::actualizarUnidadesMovimientosPendientes($_POST['seriePedido'], $_POST['numeroDocumento'], $_POST['codigoProducto'], $_POST['nuevaCantidad']);
        self::actualizarUnidadesAcumuladoPendientes("",$_POST['codigoProducto'],$_POST['nuevaCantidad'],$cantidadAnterior[0]->UnidadesPedidas);

    }

    public static function actualizarUnidadesLineaPedidoCliente(
        $serie,
        $numeroDocumento,
        $codigoProducto,
        $nuevaCantidad,
        $importeBruto,
        $descuentoCliente,
        $descuentoArticulo,
        $iva
    ) {
        $importeTotal = $importeBruto * $nuevaCantidad;
        $importeDescuento = $importeTotal  * $descuentoArticulo / 100;
        $importeNeto = $importeTotal - $importeDescuento;
        $importeDescuentoCliente = $importeNeto * $descuentoCliente / 100;
        $baseImponible = $importeNeto - $importeDescuentoCliente;
        $cuotaIva = $baseImponible * $iva;
        $importeLiquido = $baseImponible + $cuotaIva;
        $query = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $serie)
            ->where('NumeroPedido', '=', $numeroDocumento)
            ->where('CodigoArticulo', '=', $codigoProducto)
            ->update([
                'UnidadesPedidas' => $nuevaCantidad, 
                'Unidades2_' => $nuevaCantidad,
                "Precio" => $importeBruto,
                "ImporteBruto" => $importeTotal, 
                "ImporteNeto" => $importeNeto, 
                "ImporteDescuento" => $importeDescuento, 
                "ImporteDescuentoCliente" => $importeDescuentoCliente,
                "BaseImponible" => $baseImponible, 
                "%Descuento" => $descuentoArticulo, 
                "BaseIva" => $baseImponible, 
                "ImporteLiquido" => $importeLiquido,
                "CuotaIva" => $cuotaIva, 
                "TotalIva" => $cuotaIva, 
                "ImporteBrutoPendiente" => $importeTotal,

                // "UnidadesPendAnterior"=>$nuevaCantidad,
                // // "ImporteCoste"=>$importeCoste ,
                // "ImporteParcial"=> $importeTotal,
                // "ImporteParcialPendiente"=>$importeTotal,
                // "BaseImponiblePendiente"=>$baseImponible,
                // "MargenBeneficio"=>$margenDeBeneficio,
            ]);

        self::actualizarCabeceraPedido($serie, $numeroDocumento, $iva, $descuentoCliente);
    }

    public static function actualizarCabeceraPedido($serie, $numeroDocumento, $iva, $descuentoCliente)
    {
        $datos = self::obtenerDatosPedidoParaActualizarCabecera($serie, $numeroDocumento);
        //var_dump($datos[0]);
        $importeBruto = $datos[0]->sumaImporte;
        $importeDtoLineas = $datos[0]->descuento;
        $importeNetoLineas = $datos[0]->importeNeto;
        $descuentoCliente = $importeNetoLineas * session('descuento')/ 100;
        $baseImponible = $datos[0]->baseImponible ;
        $cuotaIva = $datos[0]->sumaIva;
        $cuotaRecargo = $datos[0]->recargo;  
        $TotalCuotaIva = $datos[0]->totalIva; 
        $importeProntoPago = $datos[0]->importeProntoPago; 
        //$cuotaIva = $baseImponible * 0.21;
        $importeLiquido = ($baseImponible + $cuotaIva) + $cuotaRecargo ;
        $magerBeneficio = $datos[0]->beneficio;
        $precioCoste = $datos[0]->precioCoste;
        if($precioCoste == 0){
            $porMargenBeneficio = 0;
        }else{            
            $porMargenBeneficio = ($magerBeneficio * 100)/ $importeNetoLineas;
        }

        //actualizamos importe liquido cabecera Y DEMÁS DATOS CABECERA
        $serie = DB::table('CabeceraOfertaCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioOferta', '=', date("Y"))
            ->where('SerieOferta', '=', $serie)
            ->where('NumeroOferta', '=', $numeroDocumento)
            ->update([

                "ImporteBruto" => $importeBruto, 
                //"ImporteBrutoPendiente" => $importeBruto,
                "ImporteNetoLineas" => $importeNetoLineas,
                //"ImporteNetoLineasPendiente" => $importeNetoLineas,
                //"ImporteDescuento" => $importeDtoLineas, 
                "ImporteDescuentoLineas" => $importeDtoLineas, 
                //"%Descuento" => $descuentoCliente,                 
                "ImporteParcial" => $importeNetoLineas - $importeDtoLineas ,
                //"ImporteParcialPendiente" => $importeNetoLineas - $importeDtoLineas,
                "importeProntoPago" => $importeProntoPago,                
                "BaseImponible" => $baseImponible,
                //"BaseImponiblePendiente" => $importeNetoLineas - $importeDtoLineas,
                "ImporteLiquido" => $importeLiquido, 
                "ImporteFactura" => $importeLiquido,
                "TotalIva" => $TotalCuotaIva, 
                "TotalCuotaIva" => $cuotaIva,
                "TotalCuotaRecargo" => $cuotaRecargo,
                "MargenBeneficio" =>$magerBeneficio,
                "PorMargenBeneficio" => $porMargenBeneficio,

            ]);
    }

    public static function obtenerDatosPedidoParaActualizarCabecera($serie, $numeroDocumento)
    {
        $query = DB::table('LineasOfertaCliente')->selectRaw('sum(ImporteBruto) as sumaImporte, sum(ImporteDescuento) as descuento, sum(CuotaRecargo) as recargo, sum(CuotaIva) as sumaIva, 
        sum(ImporteNeto) as importeNeto, sum(ImporteProntoPago) as importeProntoPago, sum(BaseImponible) as baseImponible, sum(BaseIva) as baseIva, Sum(TotalIva) as totalIva, Sum(MargenBeneficio) as beneficio, Sum(PrecioCoste) as precioCoste ')
            ->where('NumeroOferta', $numeroDocumento)
            ->where('SerieOferta', $serie)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioOferta', date('Y'))->get();

        return $query;
    }

    public static function actualizarUnidadesMovimientosPendientes($serie, $numeroDocumento, $codigoProducto, $nuevaCantidad)
    {
        $cantidad = self::obtenerUnidadesTotalesMovimientosPendientes($serie, $numeroDocumento, $codigoProducto);

        $query = DB::table('MovimientoPendientes')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Ejercicio', '=', date("Y"))
            ->where('Serie', '=', $serie)
            ->where('Documento', '=', $numeroDocumento)
            ->where('CodigoArticulo', '=', $codigoProducto)
            ->where('Periodo', '=', date('m'))
            ->where('CodigoAlmacen', '01')
            ->update(['Unidades' => $nuevaCantidad, 'Unidades2_' => $nuevaCantidad, 'Importe' => $cantidad[0]->Precio * $nuevaCantidad]);
    }

    public static function actualizarUnidadesAcumuladoPendientes($partida, $codigoProducto, $nuevaCantidad, $cantidadAnterior)
    {
        //obtenemos cantidad total que tenemos ya calculada en nuestros campos
        $cantidad = self::obtenerUnidadesTotalesAcumuladoPendientes($codigoProducto, $partida);
        $cantidadPendienteServir = $cantidad[0]->PendienteServir;
        $cantidadStockReservadoPedidos = $cantidad[0]->StockReservadoPedidos_;
        //a través de un cálculo por el cual a la cantidad que ya tenemos le restamos lo que habíamos añadido y le sumamos la nueva cantidad
        $cantidadFinalPendienteServir = $cantidadPendienteServir - $cantidadAnterior + $nuevaCantidad;
        $cantidadFinalStockReservado = $cantidadStockReservadoPedidos - $cantidadAnterior + $nuevaCantidad;

        $query = DB::table('AcumuladoPendientes')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoProducto)
            ->where("Partida", $partida)
            ->where('CodigoAlmacen','01')
            ->update([
                'PendienteServir' => $cantidadFinalPendienteServir, 'PendienteServirTipo_' => $cantidadFinalPendienteServir,
                'StockReservadoPedidos_' => $cantidadFinalStockReservado, 'StockReservadoPedidosTipo_' => $cantidadFinalStockReservado
            ]);
        return $query;
    }

    public static function obtenerCantidadProductoLineaPedido($seriePedido, $numeroPedido, $codigoArticulo)
    {
        $query = DB::table('LineasPedidoCliente')->select('UnidadesPedidas')
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))
            ->where('SeriePedido', $seriePedido)
            ->where('NumeroPedido', $numeroPedido)
            ->where('CodigoArticulo', $codigoArticulo)->get();
        return $query;
    }

    public static function obtenerUnidadesTotalesMovimientosPendientes($serie, $numeroDocumento, $codigoProducto)
    {
        $query = DB::table('MovimientoPendientes')->select('Unidades', 'Precio')
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('Ejercicio', date('Y'))
            ->where('Serie', $serie)
            ->where('Documento', $numeroDocumento)
            ->where('Periodo', '=', date('m'))
            ->where('CodigoAlmacen', '01')
            ->where('CodigoArticulo', $codigoProducto)->get();
        return $query;
    }

    public static function obtenerUnidadesTotalesAcumuladoPendientes($codigoProducto, $partida)
    {
        $query = DB::table('AcumuladoPendientes')->select('PendienteServir', 'StockReservadoPedidos_')
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('CodigoAlmacen','01')
            ->where('Partida', $partida)
            ->where('CodigoArticulo', $codigoProducto)->get();
        return $query;
    }

    public static function eliminarMovimientoPendiente($guid)
    {
        $query = DB::table('MovimientoPendientes')->where('MovOrigen', $guid)->delete();
    }

    public static function actualizarCantidadAcumuladoStock($codigoArticulo, $partida, $cantidad, $periodo)
    {
        $query = DB::table('AcumuladoStock')->select('UnidadEntrada', 'UnidadSalida', 'UnidadSaldo', 'UnidadConsumo', 'UnidadSalidaTipo_', 'UnidadSaldoTipo_', 'UnidadConsumoTipo_')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '01')
            ->where('Periodo', $periodo)->get();

        $unidadEntrada = $query[0]->UnidadEntrada;
        $unidadSalida = $query[0]->UnidadSalida;
        $unidadSaldoAcumulado = $query[0]->UnidadSaldo;
        $unidadSaldo = $unidadSaldoAcumulado - $cantidad; //es el resultado de la resta de la unidad entrada menos la unidad salida
        DB::table('AcumuladoStock')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '01')
            ->where('Periodo', $periodo)
            ->update([
                'UnidadSalida' => $unidadSalida + $cantidad, 'UnidadSaldo' => $unidadSaldo, 'UnidadConsumo' => $unidadSalida + $cantidad,
                'UnidadSalidaTipo_' => $unidadSalida + $cantidad, 'UnidadSaldoTipo_' => $unidadSaldo, 'UnidadSalida' => $unidadSalida + $cantidad, 'UnidadConsumoTipo_' => $unidadSalida + $cantidad,
            ]);
    }

    public static function actualizarDescuentoEnvio()
    {
        $descuento = DB::table('LineasPedidoCliente')->select('ImporteLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroDocumento'])
            ->where('CodigoArticulo', '=', 'PORTES')->get();


        $nuevoImporteLiquido = $descuento[0]->ImporteLiquido * $_POST['nuevaCantidad'] / 100;
        $query = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroDocumento'])
            ->where('CodigoArticulo', '=', 'PORTES')
            ->update(['%Descuento' => $_POST['nuevaCantidad'], 'ImporteDescuento' => 12, 'ImporteLiquido' => $nuevoImporteLiquido]);
    }

    public static function actualizarDescuentoProductoOferta()
    {

        $recargoPorcentaje = $_POST['recargo'] / 100; //porcentaje recargo a producto
        $importeTotal = ($_POST['precio'] * $_POST['nuevaCantidad']) * ($_POST['iva'] ); 
        $importeBruto = $_POST['precio2'] * $_POST['nuevaCantidad']; // 10


        $importeDescuento = $importeBruto  * ($_POST['dtoArticulo'] / 100); // 0.5
        $importeNeto = $importeBruto - $importeDescuento;
        $importeDescuentoCliente = $importeNeto * (0);
        $prontoPago = $importeNeto * $_POST['protopago'];
        $baseImponible = ($importeNeto - $importeDescuentoCliente) - $prontoPago;
        $cuotaRecargo = $baseImponible * $recargoPorcentaje;
        $cuotaIva = $baseImponible * $_POST['iva'];
        $importeLiquido = $baseImponible + $cuotaIva + $cuotaRecargo;


        $linea = DB::table('LineasOfertaCliente')
        ->select('UnidadesPedidas', 'Partida', 'PrecioCoste')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('EjercicioOferta', '=', date("Y"))
        ->where('SerieOferta', '=', $_POST['seriePedido'])
        ->where('NumeroOferta', '=', $_POST['numeroDocumento'])
        ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
        ->where('Orden', '=', $_POST['orden'])
        ->get();        

        $precioCoste = $linea[0]->PrecioCoste;
        $cantidadAnterior = $linea[0]->UnidadesPedidas;    
        $partida = $linea[0]->Partida;    
        $margenBeneficio= ($importeNeto - $precioCoste);
        if($importeNeto == 0){
            $porMargenBeneficio = 0;
        }else{            
            $porMargenBeneficio = ($margenBeneficio * 100) / $importeNeto;
        }

        $query = DB::table('LineasOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioOferta', '=', date("Y"))
            ->where('SerieOferta', '=', $_POST['seriePedido'])
            ->where('NumeroOferta', '=', $_POST['numeroDocumento'])
            ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
            ->where('Orden', '=', $_POST['orden'])
            ->update([
                "%Recargo"=> $_POST['recargo'],
                "CuotaRecargo"=> $cuotaRecargo,
                'UnidadesPedidas' => $_POST['nuevaCantidad'],
                'Unidades2_' => $_POST['nuevaCantidad'],
                "Precio" => $_POST['precio2'],
                "ImporteBruto" => $importeBruto,
                "ImporteNeto" => $importeNeto,
                "ImporteDescuento" => $importeDescuento,
                "ImporteDescuentoCliente" => $importeDescuentoCliente,
                "BaseImponible" => $baseImponible,
                "%Descuento" => $_POST['dtoArticulo'],
                "BaseIva" => $baseImponible,
                "ImporteLiquido" => $importeLiquido,
                "CuotaIva" => $cuotaIva,
                "TotalIva" => $cuotaIva + $cuotaRecargo,
                //"ImporteBrutoPendiente" => $importeBruto,
                "ImporteProntoPago" => $prontoPago,

                //"UnidadesPendAnterior"=>$_POST['nuevaCantidad'],                
                "ImporteCoste"=>$precioCoste * $_POST['nuevaCantidad'] ,
                "ImporteParcial"=> $importeBruto,
                //"ImporteParcialPendiente"=>$importeBruto,
                //"BaseImponiblePendiente"=>$baseImponible,                
                "MargenBeneficio"=>$margenBeneficio,
                "PorMargenBeneficio"=>$porMargenBeneficio,
            ]);

        self::actualizarCabeceraPedido($_POST['seriePedido'], $_POST['numeroDocumento'], $cuotaIva, $importeDescuentoCliente);
        //self::actualizarUnidadesAcumuladoPendientes($partida, $_POST['codigoProducto'], $_POST['nuevaCantidad'], $cantidadAnterior);

        // $updateMovimiento = DB::table('MovimientoPendientes')
        // ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        // ->where('Ejercicio', '=', date("Y"))
        // ->where('Serie', '=', $_POST['seriePedido'])
        // ->where('Documento', '=', $_POST['numeroDocumento'])
        // ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
        // ->where('Periodo', '=', date('m'))
        // ->where('CodigoAlmacen', '01')
        // ->update(['Unidades' => $_POST['nuevaCantidad'], 'Unidades2_' => $_POST['nuevaCantidad'], 'Importe' => $_POST['precio2'] * $_POST['nuevaCantidad']]);
        return $porMargenBeneficio;
    }

    public static function eliminarLineaOferta()
    {
        //para eliminar pedido obtenemos información de la linea como las unidades, el importe total así como el guid de posicion de la linea
        $query = DB::table('LineasOfertaCliente')
        ->select('Unidades2_ as unidades', 'LineasPosicion', 'ImporteLiquido', 'ImporteBruto', 'BaseImponible', 'TotalIva', 'ImporteNeto')
            ->where('CodigoArticulo', $_POST['codigoArticulo'])
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('EjercicioOferta', date('Y'))
            ->where('Orden', '=', $_POST['orden'])
            ->where('SerieOferta', $_POST['seriePedido'])
            ->where('NumeroOferta', $_POST['numeroPedido'])->get();
        //
        //return $query;

        $lineasPosicion = $query[0]->LineasPosicion;
        $cantidadLinea = $query[0]->unidades;
        $importeLiquidoLinea = $query[0]->ImporteLiquido;
        $importeBrutoLinea = $query[0]->ImporteBruto;
        $baseImponiblelinea = $query[0]->BaseImponible;
        $totalIvaLinea = $query[0]->TotalIva;
        $importeNetoLinea = $query[0]->ImporteNeto;
        //$partida = $query[0]->Partida;

        //borramos la línea que tiene asociado el guid
        $query = DB::table('LineasOfertaCliente')->where('LineasPosicion', $lineasPosicion)->delete();

        //obtenemos información relativa de la la cabecera para actualizarla cada vez que se elimine una línea
        $query = DB::table('CabeceraOfertaCliente')
        ->select('NumeroLineas', 'ImporteBruto', '%Descuento as descuento', 'ImporteNetoLineas', 'ImporteParcial', 'BaseImponible', 'ImporteLiquido', 'TotalIva')
        ->where('CodigoEmpresa', \session('codigoEmpresa'))
        ->where('EjercicioOferta', date('Y'))
        ->where('SerieOferta', $_POST['seriePedido'])
        ->where('NumeroOferta', $_POST['numeroPedido'])
        ->get();

        //$importeDescuentoLineas = $_POST['datos']['total'] * $_POST['datos']['descuentoLineas'];
        $importeBruto = $query[0]->ImporteBruto - $importeBrutoLinea;
        $importeNeto = $query[0]->ImporteNetoLineas   - $importeNetoLinea;
        $importeDescuento = $importeNeto * session('descuento') / 100;
        $baseImponible = $query[0]->BaseImponible - $baseImponiblelinea;
        //$desgloseIva = $query[0]->ImporteLiquido / 1.21;
        $totalIva = $query[0]->TotalIva - $totalIvaLinea;
        $importeLiquido = $query[0]->ImporteLiquido - $importeLiquidoLinea;
        $orden = $query[0]->NumeroLineas - 1; 

        $query = DB::table('CabeceraOfertaCliente')->where('CodigoEmpresa', \session('codigoEmpresa'))
            ->where('EjercicioOferta', date('Y'))
            ->where('SerieOferta', $_POST['seriePedido'])
            ->where('NumeroOferta', $_POST['numeroPedido'])
            ->update([
                "ImporteBruto" => $importeBruto,
                //"ImporteBrutoPendiente" => $importeBruto, //realmente es con cálculo de importe pendiente
                "ImporteNetoLineas" => $importeNeto,
                //"ImporteNetoLineasPendiente" => $importeNeto, //realmente es con cálculo de importe pendiente
                "ImporteDescuento" => $importeDescuento,
                "ImporteParcial" => $importeNeto - $importeDescuento,
                //"ImporteParcialPendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                "BaseImponible" => $baseImponible,
                //"BaseImponiblePendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                "TotalCuotaIva" => $totalIva,
                "TotalIva" => $totalIva,
                "ImporteLiquido" => $importeLiquido,
                //"ImporteFactura" => $importeLiquido,
                "NumeroLineas"=> $orden,
            ]);

        
        $eliminado = "OK";
                
        return $eliminado;
    }

    public static function comprobarEstadoPedido()
    {
        $query = DB::table('CabeceraOfertaCliente')->select('Estado')
            ->where('SerieOferta', $_POST['seriePedido'])
            ->where('NumeroOferta', $_POST['numeroPedido'])
            ->where('EjercicioOferta', date('Y'))
            ->where('CodigoEmpresa', \session('codigoEmpresa'))->get();
        $estado = $query[0]->Estado;
        return $estado;
    }

    public static function serieOferta()
    {

        $query = DB::table('LsysContadores')
        ->where('sysNombreContador', '=', 'OFERTA_CLI')
        ->where('sysGrupo', '=', session('codigoEmpresa'))
        ->where('sysEjercicio', '=', 0)
        ->where('sysContadorValor', '<>', 0)
        ->where('sysNumeroSerie', '=', '')
        ->get();

        return $query;
    }

    public static function contador()
    {

        $query = DB::table('lsysContadores')
            ->select('sysContadorValor')
            ->where('sysNombreContador', '=', 'OFERTA_CLI')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=',0)
            ->where('sysNumeroSerie', '=', $_POST['serie'])
            ->get();

        $query2 = DB::table('lsysContadores')
            ->where('sysNombreContador', '=', 'OFERTA_CLI')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNumeroSerie', '=', $_POST['serie'])
            ->update(['sysContadorValor' => $query[0]->sysContadorValor + 1]);

        return $query[0]->sysContadorValor + 1;
    }

    public static function contadorMenosOferta($serie, $numero, $nombreContador)
    {
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNombreContador', '=', $nombreContador)
            ->where('sysNumeroSerie', '=', $serie)
            ->update(['sysContadorValor' => $numero]);
        return $query;
    }



    public static function tabla()
    {
        $query = DB::table('CabeceraOfertaCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaOferta',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioOferta',
            'SerieOferta',
            'NumeroOferta',
            'RazonSocial'
        )
            ->where('EjercicioOferta', $_POST['ejercicio'])
            ->where('SerieOferta', $_POST['serie'])
            ->where('NumeroOferta', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        $query2 = DB::table('LineasOfertaCliente')->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesPedidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas'
        )
            ->where('EjercicioOferta', $_POST['ejercicio'])
            ->where('SerieOferta', $_POST['serie'])
            ->where('NumeroOferta', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        $datos = [
            "cabecera" => $query,
            "lineas" => $query2
        ];

        return $datos;
    }


    public static function cabeceraOferta($ejercicio, $serie, $numero)
    {

        $query = DB::table('CabeceraOfertaCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaOferta',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioOferta',
            'SerieOferta',
            'NumeroOferta',
            'RazonSocial'
        )
            ->where('EjercicioOferta', $ejercicio)
            ->where('SerieOferta', $serie)
            ->where('NumeroOferta', $numero)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        return $query[0];
    }

    public static function lineasOferta($ejercicio, $serie, $numero)
    {
        $query = DB::table('LineasOfertaCliente')->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesPedidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas',
            'ImporteNeto'
        )
            ->where('EjercicioOferta', $ejercicio)
            ->where('SerieOferta', $serie)
            ->where('NumeroOferta', $numero)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        return $query;
    }

    public static function correoOferta()
    {

        $query = DB::table('CabeceraOfertaCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaOferta',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioOferta',
            'SerieOferta',
            'NumeroOferta',
            'RazonSocial'
        )
            ->where('EjercicioOferta', $_POST['ejercicio'])
            ->where('SerieOferta', $_POST['serie'])
            ->where('NumeroOferta', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        $query2 = DB::table('LineasOfertaCliente')->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesPedidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas'
        )
            ->where('EjercicioOferta', $_POST['ejercicio'])
            ->where('SerieOferta', $_POST['serie'])
            ->where('NumeroOferta', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();


        $html = '<div id="imprimir">' .
            '<div id="cabeceraImprimir">' .
            '<div class="invoice">' .
            '<div style="min-width: 600px">' .

            '<div class="container-fluid">' .
            '<div class="row">' .
            '<div class="col-6">' .
            //'<img src="{{asset('media/images/ventura-espejoshop.png')}}" style="width:70%;"/>'.                                
            '</div>' .
            '<div class="col-6 company-details">' .
            '<h2 class="name">' .
            '<a target="_blank">' .
            'Sur Embalaje' .
            '</a>' .
            '</h2>' .
            '<div>POLIGONO INDUSTRIAL LA VEGA, 24 - PAR C 1 </div>
            <div>14800, Priego DE Cordoba (Cordoba)·</div>
            <div>957 700 003</div>' .
            '</div>' .
            '</div>' .
            '</div>' .

            '<div class="form-group col-md-12">' .
            '<hr style="width:100%;margin-bottom: 1%; color:blue; border:4px;">' .
            '</div>' .

            '<div class="content-wrapper">' .
            '<section class="content">' .
            '<div class="box" id="tpvNoTactil">' .
            '<div class="box-body">' .

            '<div class="col-md-9 ">' .

            ' <div class="row">' .
            '<div class="row contacts">' .
            '<div class="col invoice-to" id="datosCliente">' .
            '<div class="text-gray-light">Cliente:</div>' .
            '<h3 class="to" id="razonSocialCliente2">' . $query[0]->RazonSocial . '</h3>' .
            '<div class="address" id="direccionCliente2">' . $query[0]->Domicilio . ' ,' . $query[0]->Municipio . ' (' . $query[0]->CodigoPostal . ')</div>' .
            '</div>' .
            '</div>' .
            '</div>' .

            '<div class="form-group col-md-12">' .
            '<hr style="width:100%;margin-bottom: 1%; border:4px;">' .
            '</div>' .

            '<div id="tablaImprimir">';

        $html = $html . '<table class="table table-bordered" border="0" cellspacing="0" cellpadding="0">' .
            '<thead>' .
            '<th class="bg-primary text-light">' .
            '<div id="cabeceraDatoss">' .
            $_POST['ejercicio'] . '/' . $_POST['serie'] . '/' . $_POST['numero'] .
            '</div>' .
            '</th>' .
            '<th>Artículo</th>' .
            '<th>Partida</th>' .
            '<th>Precio U:</th>' .
            '<th>Dto.</th>' .

            '<th>Cantidad </th>' .
            '<th>Subtotal</th>' .
            '</thead>' .
            '<tbody class="listadoArticuloPedidos">';

        foreach ($query2 as $lineas) {
            $html = $html . "<tr>" .
                "<td>" . $lineas->CodigoArticulo . "</td>" .
                "<td>" . $lineas->DescripcionArticulo . "</td>" .
                "<td>" . $lineas->Partida . "</td>" .
                "<td class='text-right'>" . intval($lineas->Precio) . "</td>" .
                "<td class='text-right'>" . intval($lineas->descuento) . "%</td>" .
                "<td class='text-right'>" . intval($lineas->UnidadesPedidas) . "</td>" .
                "<td class='text-right'>" . intval($lineas->Precio) * intval($lineas->UnidadesPedidas) . "</td>" .
                "</tr>";
        }

        $html = $html . '</tbody>' .
            '</table>' .
            "<div class='col-8'></div>" .
            "<div class='col-4 mr-1'>" .
            "<div class='row' style='border: 2px solid #3c8dbc; font-size: 20px;'>" .
            "<div class='col-6'>" .
            "IVA" .
            "</div>" .
            "<div class='col-6 text-right'>" .
            round($query[0]->ImporteLiquido, 2) .
            "</div>" .
            "<div class='col-6'>" .
            "Base Imponible" .
            "</div>" .
            "<div class='col-6 text-right'>" .
            round($query[0]->TotalIva, 2) .
            "</div>" .
            "<div class='col-6'>" .
            "Total" .
            "</div>" .
            "<div class='col-6 text-right'>" .
            round($query[0]->ImporteLiquido, 2) . "€" .
            "</div>" .
            "</div>" .
            "</div>";

        $html = $html . '</div>' .
            '<div class="row" id="totalImprimir">' .

            '</div>' .

            '</div>' .
            '</div>' .
            '</div>' .
            '</section>' .
            '</div>' .
            '</div>' .
            '</div> ' .
            '</div> ' .
            '</div>';



        $datos = [
            'serie' => $_POST['serie'],
            'ejercicio' => $_POST['ejercicio'],
            'numero' => $_POST['numero'],
        ];

        $correos = explode(",", $_POST['correos']);
        //return $correos;


        foreach ($correos as $correo) {
            $correo = trim($correo);
            Mail::to($correo)->send(new EnviarOferta($datos));
        }

        //EnviarCorreo::enviarEmail($datos);

    }


	public static function correoFicha(){                
        $contenido = ['descripcion' => $_POST['descripcion'], 'imagen' => $_POST['imagen'], 'marca' => $_POST['marca'], 'precio' => $_POST['precio'], 'garantia' => $_POST['garantia'], 'descripcionlinea' => $_POST['descripcionlinea'] ];
        $correos = explode(",", $_POST['correos']);
        //return $correos;

        foreach ($correos as $correo) {
            $correo = trim($correo);            
            Mail::to($correo)->send(new EnviarFicha($contenido));
        }
    }


    public static function lineasPedidoArticulo()
    {

        $query = DB::table('LineasAlbaranCliente')
            ->select('CodigoEmpresa','EjercicioAlbaran','SerieAlbaran','NumeroAlbaran','CodigoArticulo', 'DescripcionArticulo', 
            'Precio', '%Descuento AS Descuento', 'Unidades', '%Recargo as Recargo', 'CodigoArticulo AS CodigoCliente')            
            ->where('LineasPosicion', '=', $_POST['lineaPosicion'])
            ->get();

        $query2 = DB::table('CabeceraAlbaranCliente')
        ->select('CodigoCliente')
        ->where('CodigoEmpresa', '=', $query[0]->CodigoEmpresa)
        ->where('EjercicioAlbaran', '=', $query[0]->EjercicioAlbaran)
        ->where('SerieAlbaran', '=', $query[0]->SerieAlbaran)
        ->where('NumeroAlbaran', '=', $query[0]->NumeroAlbaran)
        ->get();

        $query[0]->CodigoCliente = $query2[0]->CodigoCliente;

        return $query;
        
    }

    public static function observacionOferta(){

        $quiery = DB::table('CabeceraOfertaCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('SerieOferta', '=', '')
        ->where('EjercicioOferta', '=', date('Y'))
        ->update(['ObservacionesOferta' => $_POST['comentario'],
                //'ObservacionesPedido' => $_POST['comentario'],
                //'ObservacionesAlbaran' => $_POST['comentario'],
                //'ObservacionesFactura' => $_POST['comentario']
            ]);

        return 'ok';
    }    


    public static function descripcion0Oferta(){

        $update = DB::table('LineasOfertaCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', date('Y'))
        ->where('Orden', '=', $_POST['orden'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->update([
            'DescripcionArticulo' =>$_POST['descripcion']
        ]);


        if($update){
            return 'ok articulo 0';
        }
    }



    public static function precioCosteProductoOferta(){

        $update = DB::table('LineasOfertaCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', date('Y'))
        ->where('Orden', '=', $_POST['orden'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->update([
            'PrecioCoste' =>$_POST['coste']
        ]);


        if($update){
            return 'ok articulo 0 coste';
        }
    }

    public static function datosOferta() {
        $q = DB::table('CabeceraOfertaCliente')->where('IdOfertaCli', '=', $_POST['idOferta'])->get();
        return $q;
    }


    public static function pasarOfertaPedido(){

        $cabeceraOferta = DB::table('CabeceraOfertaCliente')
        ->select('*','%Descuento AS Descuento')
        ->where('IdOfertaCli', '=', $_POST['idOferta'])
        ->get();

        $query = DB::table('lsysContadores')
            ->select('sysContadorValor')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=',date('Y'))
            ->where('sysNumeroSerie', '=', $cabeceraOferta[0]->SerieOferta)
            ->get();

        $query2 = DB::table('lsysContadores')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', date('Y'))
            ->where('sysNumeroSerie', '=', $cabeceraOferta[0]->SerieOferta)
            ->update(['sysContadorValor' => $query[0]->sysContadorValor + 1]);


        $numeroPedido = $query[0]->sysContadorValor + 1;
        //$guidPedido = self::codigoGuid();        
       

        if(count($cabeceraOferta) > 0){

            $cliente = Cliente::where('CodigoCliente', '=', $cabeceraOferta[0]->CodigoCliente)
            ->where('CodigoEmpresa', '=',  $cabeceraOferta[0]->CodigoEmpresa)
            ->where('CodigoCategoriaCliente_', '=', 'CLI')       
            ->get();

            if(!isset($cliente[0])){
                $noSePuede = 'La oferta no se puede aprobar porque es procedente de un potencial';
                return $noSePuede;
            }  

            $clienteConta = ClienteConta::where('CodigoClienteProveedor', '=', $cliente[0]['CodigoContable'])
            ->where('ClienteOproveedor', '=', 'C')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CifDni', '=', $cliente[0]['CifDni'])
            ->get();                      

            $comisionista = DB::table('Comisionistas')
            ->select('CodigoComisionista', 'CodigoJefeVenta_', '%Comision as Comision')
            ->where('CodigoComisionista', '=', $cliente[0]['CodigoComisionista'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();


            if(count($cliente) > -1){

                $cabeceraPedido = DB::table('CabeceraPedidoCliente')
                ->insert([
                    "CodigoEmpresa" => $cabeceraOferta[0]->CodigoEmpresa,
                    "CodigoCliente" => $cabeceraOferta[0]->CodigoCliente,
                    "EjercicioPedido" => date("Y"),
                    "SeriePedido" => $cabeceraOferta[0]->SerieOferta,
                    "NumeroPedido" => $numeroPedido,
                    "FechaPedido" => date("Y-m-d") . " " . date("H:i:s"),                
                    "CIFDNI" => $cliente[0]['CifDni'],
                    "CIFEuropeo" => "ES" . $cliente[0]['CifDni'],
                    "RazonSocial" => substr($cliente[0]['RazonSocial'],0,35),
                    "Nombre"=>substr($cliente[0]['Nombre'],0,35),
                    "Domicilio" => $cliente[0]['Domicilio'],
                    "CodigoPostal" => $cliente[0]['CodigoPostal'],
                    "CodigoMunicipio" => $cliente[0]['CodigoMunicipio'],
                    "Municipio" => $cliente[0]['Municipio'],
                    "CodigoProvincia" => $cliente[0]['CodigoProvincia'],
                    "Provincia" => $cliente[0]['Provincia'],
                    "CodigoNacion" => $cliente[0]['CodigoNacion'],
                    "Nacion" => $cliente[0]['Nacion'],
                    "CodigoCondiciones" => $clienteConta[0]['CodigoCondiciones'],
                    "NumeroPlazos" => $clienteConta[0]['NumeroPlazos'],
                    "CodigoContable" => $cliente[0]['CodigoContable'],
                    "IBAN" => $cliente[0]['IBAN'],
                    "ReservarStock_" => -1,                
                    //"%Descuento" => $cliente[0]['%Descuento'],
                    "CodigoComisionista" => session('codigoComisionista'),
                    "ComercialAsignadoLc"=>session('codigoComisionista'),
                    "StatusAprobado" => 0,
                    "CodigoJefeVenta_" => $comisionista[0]->CodigoJefeVenta_,
                    "DiasPrimerPlazo" => $clienteConta[0]['DiasPrimerPlazo'],
                    "DiasEntrePlazos" => $clienteConta[0]['DiasEntrePlazos'],
                    "DiasFijos1" => $clienteConta[0]['DiasFijos1'],
                    "DiasFijos2" => $clienteConta[0]['DiasFijos2'],
                    "DiasFijos3" => $clienteConta[0]['DiasFijos3'],
                    "DiasRetroceso" => $clienteConta[0]['DiasRetroceso'],
                    "FormadePago" => $cliente[0]['FormadePago'],
                    "MesesComerciales" => $clienteConta[0]['MesesComerciales'],
                    "ControlarFestivos" => $clienteConta[0]['ControlarFestivos'],
                    "RemesaHabitual" => $clienteConta[0]['RemesaHabitual'],
                    "CodigoTipoEfecto" => $clienteConta[0]['CodigoTipoEfecto'],
                    "CodigoBanco" => $cliente[0]['CodigoBanco'],
                    "CodigoAgencia" => $cliente[0]['CodigoAgencia'],
                    "DC" => $cliente[0]['DC'],
                    "CCC" => $cliente[0]['CCC'],
                    "CopiasAlbaran" => $cliente[0]['CopiasAlbaran'],
                    "CopiasFactura" => $cliente[0]['CopiasFactura'],
                    "AgruparAlbaranes" => $cliente[0]['AgruparAlbaranes'],
                    "NumeroLineas"=> $cabeceraOferta[0]->NumeroLineas,

                    //"%ProntoPago"=> $ProntoPago,
                    "IndicadorIva" => $cliente[0]['IndicadorIva'],
                    "TipoPortesEnvios" => $cliente[0]['TipoPortes'],
                    "CodigoTransaccion" => $clienteConta[0]['CodigoTransaccion'],
                    "AgruparAlbaranes" => $cliente[0]['AgruparAlbaranes'],
                    "MantenerCambio_" => $cliente[0]['MantenerCambio_'],
                    //"FactorCambio" => $FactorCambio,
                    "ReferenciaMandato" => $cliente[0]['ReferenciaMandato'],
                    "AlbaranValorado"=>$cliente[0]['AlbaranValorado'],
                    
                    "StatusAprobado" => 0,

                    "ImporteBruto" => $cabeceraOferta[0]->ImporteBruto,
                    "ImporteDescuentoLineas"=> $cabeceraOferta[0]->ImporteDescuentoLineas,
                    "ImporteNetoLineas"=> $cabeceraOferta[0]->ImporteNetoLineas,
                    "BaseImponible"=>  $cabeceraOferta[0]->BaseImponible,
                    "TotalCuotaIva"=> $cabeceraOferta[0]->TotalCuotaIva,
                    "TotalCuotaRecargo"=> $cabeceraOferta[0]->TotalCuotaRecargo,
                    "TotalIva"=> $cabeceraOferta[0]->TotalIva,
                    "ImporteLiquido"=>  $cabeceraOferta[0]->ImporteLiquido,
                    "ImporteBrutoPendiente"=> $cabeceraOferta[0]->ImporteBruto,
                    "ImporteNetoLineasPendiente"=> $cabeceraOferta[0]->ImporteNetoLineas,
                    "ImporteParcialPendiente"=> $cabeceraOferta[0]->ImporteBruto,
                    "BaseImponiblePendiente"=> $cabeceraOferta[0]->BaseImponible,
                    
                    "MargenBeneficio" => $cabeceraOferta[0]->MargenBeneficio,
                    "PorMargenBeneficio"=> $cabeceraOferta[0]->PorMargenBeneficio,
                    
                ]);

                if($cabeceraPedido){

                    $lineasOfertas = DB::table('LineasOfertaCliente')
                    ->select('*', '%Descuento AS Descuento', '%Iva as Iva', '%Recargo as Recargo')
                    ->where('SerieOferta', '=', $cabeceraOferta[0]->SerieOferta)
                    ->where('NumeroOferta', '=', $cabeceraOferta[0]->NumeroOferta)
                    ->where('EjercicioOferta', '=', $cabeceraOferta[0]->EjercicioOferta)
                    ->where('CodigoEmpresa', '=', $cabeceraOferta[0]->CodigoEmpresa)
                    ->get();

                    if(count($lineasOfertas) > -1){
                        foreach($lineasOfertas as $lineasOferta){
                            $guidLinea = self::codigoGuid();
                            $lineasPedido =  DB::table('LineasPedidoCliente')
                            ->insert([
                                "CodigoEmpresa" => $lineasOferta->CodigoEmpresa,                            
                                "EjercicioPedido" => date("Y"),
                                'CodigoAlmacen' => '01',
                                "SeriePedido" => $lineasOferta->SerieOferta,
                                "NumeroPedido" => $numeroPedido,
                                'LineasPosicion' => $guidLinea,
                                "CodigoArticulo" => $lineasOferta->CodigoArticulo,
                                "DescripcionArticulo" => $lineasOferta->DescripcionArticulo,
                                "CodigoAlmacenAnterior"=>'01',
                                "CodigoFamilia" => $lineasOferta->CodigoFamilia,
                                "CodigoSubFamilia" => $lineasOferta->CodigoSubfamilia,
                                "TipoArticulo" => $lineasOferta->TipoArticulo,
                                //"FechaEntrega"=>session('fechaEntrega'). " 00:00:00.000",
                                "ReservarStock_" => -1,
                                "Estado" => 0,
                                "GrupoIva" => $lineasOferta->GrupoIva,
                                "CodigoIva" => $lineasOferta->CodigoIva,
                                "%Iva" => $lineasOferta->Iva,
                                "UnidadesPendientesFabricar" => $lineasOferta->UnidadesPedidas,
                                "UnidadesPedidas" => $lineasOferta->UnidadesPedidas,
                                "UnidadesPendientes" => $lineasOferta->UnidadesPedidas,
                                "UnidadesServidas" => 0,
                                "Unidades2_" => $lineasOferta->Unidades2_,
                                "Precio" => $lineasOferta->Precio,
                                "CodigoComisionista" => session('codigoComisionista'),
                                "%Recargo"=> $lineasOferta->Recargo,
                                "CuotaRecargo"=> $lineasOferta->CuotaRecargo,
                                "%Descuento" => $lineasOferta->Descuento,
                                "Orden" => $lineasOferta->Orden,
                                "CodigoJefeVenta_" => $comisionista[0]->CodigoJefeVenta_,
                                "%Comision" => $comisionista[0]->Comision,
                                "ImporteBruto" => $lineasOferta->ImporteBruto,
                                "ImporteBrutoPendiente" => $lineasOferta->ImporteBruto,
                                "ImporteDescuento" => $lineasOferta->ImporteDescuento,
                                "ImporteNeto" => $lineasOferta->ImporteNeto,
                                "ImporteNetoPendiente" => $lineasOferta->ImporteNeto,
                                "ImporteDescuentoCliente" => $lineasOferta->ImporteDescuentoCliente,
                                "BaseImponible" => $lineasOferta->BaseImponible,
                                "BaseIva" => $lineasOferta->BaseImponible,
                                "CuotaIva" => $lineasOferta->CuotaIva,
                                "TotalIva" => $lineasOferta->TotalIva,
                                "ImporteLiquido" => $lineasOferta->ImporteLiquido,

                                "UnidadesPendAnterior"=>$lineasOferta->UnidadesPedidas,
                                "PrecioCoste"=>$lineasOferta->PrecioCoste ,
                                "ImporteCoste"=>$lineasOferta->ImporteCoste ,
                                "ImporteParcial"=> $lineasOferta->ImporteParcial,
                                "ImporteParcialPendiente"=>$lineasOferta->ImporteParcial,
                                "BaseImponiblePendiente"=>$lineasOferta->BaseImponible,
                                "PorMargenBeneficio"=>$lineasOferta->PorMargenBeneficio,
                                "MargenBeneficio"=>$lineasOferta->MargenBeneficio,                                

                                "CodigoTransaccion"=>$lineasOferta->CodigoTransaccion,
                                "CodigoDefinicion_"=>$lineasOferta->CodigoDefinicion_,
                            ]);

                            //comprobamos MovimientoPendientes                         

                            $movimientoPendientes = DB::table('MovimientoPendientes')
                            ->insert([
                                "CodigoEmpresa" => $lineasOferta->CodigoEmpresa,
                                "Ejercicio" => $lineasOferta->EjercicioOferta,
                                "Periodo" => date('m'),
                                "Serie" => $lineasOferta->SerieOferta,
                                "Documento" => $numeroPedido,
                                "CodigoArticulo" => $lineasOferta->CodigoArticulo,
                                'CodigoAlmacen' => '01',
                                "Unidades" => $lineasOferta->UnidadesPedidas,
                                "Unidades2_" => $lineasOferta->Unidades2_,
                                "Precio" => $lineasOferta->Precio,
                                "Importe" => $lineasOferta->ImporteBruto,
                                "Comentario" => "PedidoVenta: " . date('Y') . "/" . $lineasOferta->SerieOferta . "/" . $numeroPedido ."/".$lineasOferta->Orden,
                                "CodigoCliente" => $cabeceraOferta[0]->CodigoCliente,
                                "StatusAcumulado" => -1,
                                "OrigenMovimiento" => "C",
                                "MovOrigen" => $guidLinea,
                                "EmpresaOrigen" => $lineasOferta->CodigoEmpresa,
                                "EjercicioDocumento" => $lineasOferta->EjercicioOferta,
                                "ReservarStock_" => -1
                            ]);   
                            
                            $existeEnAcumulado = PedidoController::comprobarExisteArticuloEnAcumuladoPendientes($lineasOferta->CodigoArticulo, $lineasOferta->Partida);

                            if($existeEnAcumulado == false){

                                $acumuladoPendientes = DB::table("AcumuladoPendientes")
                                ->insert([
                                "CodigoEmpresa" => session('codigoEmpresa'),
                                "CodigoArticulo" => $lineasOferta->CodigoArticulo,
                                'CodigoAlmacen' => '01',
                                "Partida" => $lineasOferta->Partida,
                                "PendienteServir" => $lineasOferta->UnidadesPedidas,
                                "PendienteServirTipo_" => $lineasOferta->UnidadesPedidas,
                                "StockReservadoPedidos_" => $lineasOferta->UnidadesPedidas,
                                "StockReservadoPedidosTipo_" => $lineasOferta->UnidadesPedidas,
                                ]);
                            }else{                            
                                $cantidadAactualizar = PedidoController::obtenerCantidadArticuloEnAcumuladoPendientes($lineasOferta->CodigoArticulo, $lineasOferta->Partida);
                                PedidoController::actualizarCantidadAcumuladoPendientes($lineasOferta->CodigoArticulo, $lineasOferta->Partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $lineasOferta->UnidadesPedidas);
                            }

                            $updateStadoOferta = DB::table('CabeceraOfertaCliente')
                            ->where('IdOfertaCli', '=', $_POST['idOferta'])
                            ->update(['Estado'=>2, 'StatusAprobado'=>-1]);

                            $datos = ['serie'=>$cabeceraOferta[0]->SerieOferta, 'ejercicio'=>date("Y"), 'numero'=>$numeroPedido];
                            return $datos;
                        }    
                    }
                }
            }
        }

    }


    public static function descripcion0(){

        $update = DB::table('LineasOfertaCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', date('Y'))
        ->where('Orden', '=', $_POST['orden'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->update([
            'DescripcionArticulo' =>$_POST['descripcion']
        ]);


        if($update){
            return 'ok articulo 0';
        }
    }



    public static function precioCosteProducto(){

        $update = DB::table('LineasOfertaCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', date('Y'))
        ->where('Orden', '=', $_POST['orden'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->update([
            'PrecioCoste' =>$_POST['coste']
        ]);


        if($update){
            return 'ok articulo 0 coste';
        }
    }

    public static function observacionArticuloOferta(){
        $q = DB::table('LineasOfertaCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('EjercicioOferta', '=', date('Y'))
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->where('Orden', '=', $_POST['orden'])
        ->update(['DescripcionLinea' => $_POST['observacion'] ]);
    }


	public static function direccionPedido(){
    	$update = DB::table('CabeceraPedidoCliente')        
        	->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        	->where('NumeroPedido', '=', $_POST['numero'])
        	->where('SeriePedido', '=', $_POST['serie'])
        	->where('EjercicioPedido', '=', $_POST['ejercicio'])
        	->update([
        	    'DomicilioEnvio' =>$_POST['direccion']
     	   ]);
    }


    public static function recuperarOferta(){

        $datosOferta = DB::table('CabeceraOfertaCliente')
        ->select('CodigoEmpresa','EjercicioOferta','SerieOferta','NumeroOferta', 'CodigoCliente', 'Estado','VEstadoCRM', 'IndicadorIva', 'ObservacionesOferta') 
        ->where('IdOfertaCli', '=', $_POST['idOferta'])   
        ->get();

        $lineasOferta = DB::table('LineasOfertaCliente')
        ->select('*','%Descuento as Descuento', '%Recargo as Recargo')
        ->where('CodigoEmpresa', '=', $datosOferta[0]->CodigoEmpresa)
        ->where('EjercicioOferta', '=', $datosOferta[0]->EjercicioOferta)
        ->where('SerieOferta', '=', $datosOferta[0]->SerieOferta)
        ->where('NumeroOferta', '=', $datosOferta[0]->NumeroOferta)
        ->get();

        //$lineasOferta[0]->CodigodelCliente = $datosOferta[0]->CodigoCliente;
        $respuesta = ["codigocliente"=>$datosOferta[0]->CodigoCliente, "observaciones" => $datosOferta[0]->ObservacionesOferta, "lineasOferta"=>$lineasOferta, "estadoPedido"=>$datosOferta[0]->Estado, "VEstadoCRM"=>$datosOferta[0]->VEstadoCRM, "IndicadorIva"=>$datosOferta[0]->IndicadorIva];

        return  $respuesta;

    }





    public static function ofertamod(){        

        $importeDescuentoLineas = 0;
        $importeBruto = 0;
        $importeNeto = 0;
        $importeDescuento = 0;
        $baseImponible = 0;
        $totalIva = 0;
        $totalRecargo = 0;
        $orden = 5;

        $codigoCliente = '';
        $codigoComisionista = '';
        $codigoJefeVenta = '';
        $partida = '';
        $fechaCaducidad = '';

        if($_POST['indicadorIva'] == "I" ){
        
            $IndicadorIva = "I";
        }else if($_POST['indicadorIva'] == "R" ){
            $IndicadorIva = "R";
        }
        else{
            $IndicadorIva = "E";
        }

        $cabecera = DB::table('CabeceraOfertaCliente')
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', $_POST['ejercicio'])
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        $cliente = DB::table('Clientes')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('CodigoCliente', '=', $cabecera[0]->CodigoCliente)
        ->get();
        // $cabecera[0]->CodigoBarrasPedido
            
            foreach ($_POST['lineasPosicion'] as $lineas) {

                $guidArticulo = self::codigoGuid();

                list($codigo, $puesto) = explode("¬", $lineas['guid']);
                //var_dump($codigo);

                if($codigo == "TEXTOLIBRE") $codigo="TEXTOLIBRE";
                if($codigo == "PROCAREROLL") $codigo="PROCARE ROLL";
                if($codigo == "P005-AZU4B") $codigo="P 005-AZU4B";
                if($codigo == "P005-AZU7B") $codigo="P 005-AZU7B";
                if($codigo == "P005-FUC7B") $codigo="P 005-FUC7B";
                if($codigo == "PAETCMINIITNEGRO") $codigo="PAETCMINIIT NEGRO";
                if($codigo == "PAETCMINIITROJO") $codigo="PAETCMINIIT ROJO";
                if($codigo == "PAETCMINIITVERDE") $codigo="PAETCMINIIT VERDE";

                if($lineas['origen'] == 1){
                    

                    $consultarLineas  = DB::table('LineasOfertaCliente')
                    ->where('SerieOferta', '=', $_POST['serie'])
                    ->where('EjercicioOferta', '=', $_POST['ejercicio'])
                    ->where('NumeroOferta', '=', $_POST['numero'])
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                    ->where('CodigoArticulo', '=', $codigo)
                    ->where('Orden','=', $puesto)
                    ->get();

                    if (count($consultarLineas) > 0) {
                        $eliminarlinea = DB::table('LineasOfertaCliente')->where('lineasPosicion', $consultarLineas[0]->LineasPosicion)->delete();
                    }
                    

                }
                

                // dd($codigo);
                $lineo = DB::table('Articulos')                   
                ->where('CodigoArticulo', '=', $codigo)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))                    
                ->get();

                $tienePartida = DB::table('Articulos')
                ->select('TratamientoPartidas','PrecioCompra','%Descuento as Descuento','%Margen as Margen')
                ->where('CodigoEmpresa',\session('codigoEmpresa'))
                ->where('CodigoArticulo',$codigo)->get();

                if(count($tienePartida) > 0){
                    if($tienePartida[0]->TratamientoPartidas == -1){
                        $partidas = DB::table('AcumuladoStock')->select('Partida','FechaCaducidad')
                        ->where('Periodo',99)
                        ->where('CodigoAlmacen','000')
                        ->where('CodigoEmpresa',\session('codigoEmpresa'))
                        ->where('Ejercicio',date('Y'))
                        ->where('UnidadSaldo','>',0)
                        ->where('FechaCaducidad','<>',null)
                        ->where('CodigoArticulo',$_POST['codigoArticulo'])
                        ->where('UnidadSaldo', '>=', $lineas['unidades'])
                        ->orderByDesc('FechaCaducidad')->get();   
                        
                        if(count($partidas) > 0){
                            $partida = $partidas[0]->Partida;
                            $fechaCaducidad = $partidas[0]->FechaCaducidad;
                        }
                        
                    }
                }



                $ivaArticulo = 0;
                $codigoIva = 0;
                //obtenemos iva de cada articulo
                if($lineo[0]->GrupoIva == 1){
                    $ivaArticulo = 21;
                    $codigoIva = 1;
                }
                if($lineo[0]->GrupoIva == 2){
                    $ivaArticulo = 10;
                    $codigoIva = 2;
                }
                if($lineo[0]->GrupoIva == 3){
                    $ivaArticulo = 4;
                    $codigoIva = 3;
                }
                if($lineo[0]->GrupoIva == 4){
                    $ivaArticulo = 0;
                    $codigoIva = 4;
                }

                if($lineas['recargo'] == NULL) $lineas['recargo'] = 0;

                if(!is_numeric($lineas['descuento']) || $lineas['descuento'] == null || $lineas['descuento'] == ''){
                    $lineas['descuento'] = 0;
                };
                            

                $unidades = $lineas['unidades'];
                $descuento = $lineas['descuento'];
                $precio = $lineas['precio'];
                $recargo = $lineas['recargo'];   
                
                if(isset($lineas['observacion'])){
                    if($lineas['observacion'] != 'undefined') $descripcionArticulo = $lineas['observacion'];
                    else $descripcionArticulo = $lineo[0]->DescripcionArticulo;
                }else{
                    if($codigo != "TEXTOLIBRE"){
                        $descripcionArticulo = $lineo[0]->DescripcionArticulo;
                    }else{
                        $descripcionArticulo = $consultarLineas[0]->DescripcionArticulo;
                    }
                } 

                $comision = PedidoController::obtenerComisionLinea($codigo, $cabecera[0]->CodigoComisionista, $lineo[0]->CodigoFamilia, $cabecera[0]->CodigoCliente);
                
                $importeBruto = $precio * floatval($unidades);
                //$baseRecargo = $importeBruto * $recargo / 100;
                if ($descuento == 100) {
                    $importeDescuento = floatval($importeBruto);
                }else{
                    $importeDescuento = $importeBruto *  ($descuento / 100);
                }
                $importeNeto = $importeBruto - round($importeDescuento,2, PHP_ROUND_HALF_DOWN);                  
                //$descuentoCliente = $importeNeto * $descuento / 100;
                $baseImponible = $importeNeto;

                $baseRecargo = $baseImponible * ($recargo / 100);

                if($IndicadorIva == "I" ){
                    $cuotaiva = ($baseImponible * $ivaArticulo / 100);
                    $totalIva = $cuotaiva;
                }else if($IndicadorIva == "R"){

                    $cuotaiva = ($baseImponible * $ivaArticulo / 100);
                    $totalIva = $cuotaiva ;
                }else{
                    $cuotaiva = 0;
                    $totalIva = $cuotaiva ;
                }

                //$importeLiquido = $importeNeto + $totalIva;                     
                $precioCompra = $tienePartida[0]->PrecioCompra;
                $margenDeBeneficio = ($precio - $importeDescuento - $precioCompra) * $unidades;
                if(round($importeNeto,4, PHP_ROUND_HALF_EVEN) == 0 ){
                    if(round($importeNeto,4, PHP_ROUND_HALF_EVEN) == 0 && $precio == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        $porMargenBeneficio = 100;
                    }
                }else{                            
                    $porMargenBeneficio = (round($margenDeBeneficio,4, PHP_ROUND_HALF_EVEN)  * 100) / round($importeNeto,4, PHP_ROUND_HALF_EVEN);
                }


                $lineaPedido = DB::table('LineasOfertaCliente')
                ->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "CodigoDelCliente" => $cabecera[0]->CodigoCliente,
                    "EjercicioOferta" => $_POST['ejercicio'],
                    'CodigoAlmacen' => '000',
                    "SerieOferta" => $_POST['serie'],
                    "NumeroOferta" => $_POST['numero'],
                    'LineasPosicion'=> $guidArticulo,
                    "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                    "DescripcionArticulo" => $descripcionArticulo,
                    //"CodigoAlmacenAnterior"=>'000',
                    "CodigoFamilia"=>$lineo[0]->CodigoFamilia,
                    "CodigoSubFamilia"=>$lineo[0]->CodigoSubfamilia,
                    "TipoArticulo"=>$lineo[0]->TipoArticulo,                   
                    //"ReservarStock_"=>-1,
                    "Estado"=>0,
                    "GrupoIva" => $codigoIva,
                    "CodigoIva" => $ivaArticulo,
                    "%Iva"=>$ivaArticulo,
                    //"UnidadesPendientesFabricar" => $unidades,
                    "UnidadesPedidas" => $unidades,
                    //"UnidadesPendientes" => $unidades,
                    //"UnidadesServidas" => 0,
                    "Unidades2_" => $unidades,
                    "Precio" => $precio,
                    "CodigoComisionista"=>$cabecera[0]->CodigoComisionista,
                    "%Descuento" =>$descuento,
                    "Orden" => $orden,
                    "CodigoJefeVenta_"=>$codigoJefeVenta,
                    "%Comision"=>$comision,
                    "ImporteBruto" => round($importeBruto,4, PHP_ROUND_HALF_EVEN),
                    //"ImporteBrutoPendiente" => $importeBruto,
                    "ImporteDescuento" => round($importeDescuento,4, PHP_ROUND_HALF_DOWN),
                    "ImporteNeto" => round($importeNeto,4, PHP_ROUND_HALF_EVEN),
                    //"ImporteNetoPendiente" => $importeNeto,
                    //"ImporteDescuentoCliente" => $desCliente,
                    "BaseImponible" =>round($baseImponible,4, PHP_ROUND_HALF_EVEN),
                    "BaseIva" =>round($baseImponible,4, PHP_ROUND_HALF_EVEN),
                    "CuotaIva" =>round($cuotaiva,4, PHP_ROUND_HALF_EVEN),
                    "%Recargo" =>$lineas['recargo'],
                    "CuotaRecargo" =>round($baseRecargo,4, PHP_ROUND_HALF_EVEN),
                    "TotalIva" =>round($totalIva,4, PHP_ROUND_HALF_EVEN),
                    "ImporteLiquido"=>round($baseImponible + $baseRecargo + $totalIva,4, PHP_ROUND_HALF_EVEN),

                    "Partida" => "$partida",
                    "FechaCaduca" => "$fechaCaducidad",

                    //"UnidadesPendAnterior"=>$unidades,
                    "PrecioCoste"=>round($precioCompra,4, PHP_ROUND_HALF_EVEN) ,
                    "ImporteCoste"=>round($precioCompra * $unidades,4, PHP_ROUND_HALF_EVEN) ,
                    "ImporteParcial"=> round($importeBruto,4, PHP_ROUND_HALF_EVEN),
                   // "ImporteParcialPendiente"=>$importeNeto,
                   // "BaseImponiblePendiente"=>$baseImponible,
                    "PorMargenBeneficio"=>round(floatval($porMargenBeneficio),4,PHP_ROUND_HALF_EVEN),
                    "MargenBeneficio"=>$margenDeBeneficio,                    

                    "CodigoTransaccion"=>1,
                    "CodigoDefinicion_"=>$lineo[0]->CodigoDefinicion_,
                  //  "CodigoBarrasOferta"=>$cabecera[0]->CodigoBarrasOferta,
                   // "CodigoClientePSP"=>$cabecera[0]->CodigoCliente,
                    //"RazonSocialPSP"=>substr($cabecera[0]->RazonSocial,0,35),
                ]);
                
                if ($lineo[0]->Lote_ == -1 && $lineo[0]->FormulaLote == 1) {
                    $loteCompleto = DB::table("DesgloseLote_")
                    ->selectRaw("(select top 1 DescripcionArticulo from Articulos where CodigoArticulo=ArticuloComponente and CodigoEmpresa='1') AS desArticulo, *")
                    ->where("CodigoEmpresa", session('codigoEmpresa'))
                    ->where("FormulaLote", $lineo[0]->FormulaLote)
                    ->where("CodigoArticulo", $lineo[0]->CodigoArticulo)->get();

                    foreach ($loteCompleto as $articuloLote) {
                        $guidArticulo = self::codigoGuid();
                        $orden = $orden + 5;

                        $lineaPedido = DB::table('LineasOfertaCliente')
                            ->insert([
                                "CodigoEmpresa" => session('codigoEmpresa'),
                                "CodigoDelCliente" => $cabecera[0]->CodigoCliente,
                                "EjercicioOferta" => $_POST['ejercicio'],
                                'CodigoAlmacen' => '000',
                                "SerieOferta" => $_POST['serie'],
                                "NumeroOferta" => $_POST['numero'],
                                'LineasPosicion'=> $guidArticulo,
                                "CodigoArticulo" => $articuloLote->ArticuloComponente,
                                "DescripcionArticulo" => $articuloLote->desArticulo,
                                "CodigoFamilia"=>$lineo[0]->CodigoFamilia,
                                "CodigoSubFamilia"=>$lineo[0]->CodigoSubfamilia,
                                "TipoArticulo"=>$lineo[0]->TipoArticulo,                   
                                "Estado"=>0,
                                "GrupoIva" => $codigoIva,
                                "CodigoIva" => $ivaArticulo,
                                "UnidadesPedidas" => ($articuloLote->Unidades2_ * $unidades),
                                "Unidades2_" => ($articuloLote->Unidades2_ * $unidades),
                                "CodigoComisionista"=>$cabecera[0]->CodigoComisionista,
                                "Orden" => $orden,
                                "CodigoJefeVenta_"=>$codigoJefeVenta,
                                "%Comision"=>$comision,

                                "Partida" => "$partida",
                                "FechaCaduca" => "$fechaCaducidad",

                                "CodigoTransaccion"=>1,
                                "CodigoDefinicion_"=>$lineo[0]->CodigoDefinicion_,

                                "VCodLote" => $lineo[0]->CodigoArticulo,
                            ]);
                    }
                }
                
                $orden = $orden + 5;

            }
            $nLineas = $orden/5-1;

            

            $cabeceraUpdate = DB::table('LineasOfertaCliente')
            ->selectRaw('Sum(ImporteBruto) as importeBruto, Sum(ImporteDescuento) as importeDescuento, Sum(ImporteNeto) as importeNeto, 
            Sum(ImporteDescuentoCliente) as importeDescuentoCliente, Sum(ImporteProntoPago) as importeProntoPago, Sum(BaseImponible) as baseImponible, 
            Sum(CuotaIva) as cuotaIva, Sum(CuotaRecargo) as cuotaRecargo, Sum(TotalIva) as totalIva, Sum(ImporteLiquido) as importeLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['serie'])
            ->where('EjercicioOferta', '=', $_POST['ejercicio'])
            ->where('NumeroOferta', '=', $_POST['numero'])
            ->get();

            
            $con = conexion::OpenConnectionSQLServer();
            $sql = "select
                (((CONVERT(DECIMAL(7,2),(SUBSTRING((convert(char(8), getdate(), 108)),1,2))) +
                CONVERT(DECIMAL(7,2),(SUBSTRING((convert(char(8), getdate(), 108)),4,2)))*0.0166667 +
                CONVERT(DECIMAL(7,2),(SUBSTRING((convert(char(8), getdate(), 108)),7,2)))*0.000277778)*1)/24) as hora";
            $resultado = sqlsrv_query($con, $sql);
            if (sqlsrv_rows_affected($resultado)) {
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                $hora = $fila["hora"];
            }
            // "HoraPedidoCRM"=>$hora,
            


            $query2 = DB::table('CabeceraOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['serie'])
            ->where('EjercicioOferta', '=', $_POST['ejercicio'])
            ->where('NumeroOferta', '=', $_POST['numero'])
            ->update([

                "ImporteBruto" => $cabeceraUpdate[0]->importeBruto,
                "ImporteDescuentoLineas"=> $cabeceraUpdate[0]->importeDescuento,
                "ImporteNetoLineas"=> $cabeceraUpdate[0]->importeNeto,
                "BaseImponible"=> $cabeceraUpdate[0]->baseImponible,
                "TotalCuotaIva"=>$cabeceraUpdate[0]->cuotaIva,
                "TotalCuotaRecargo"=>$cabeceraUpdate[0]->cuotaRecargo,
                "TotalIva"=>$cabeceraUpdate[0]->totalIva,
                "ImporteLiquido"=> $cabeceraUpdate[0]->importeLiquido, 
                "HoraPedidoCRM"=>$hora,
                // "ObservacionesOferta"=> $_POST["observaciones"],
            ]);
                        
            //$datos = ['importeDescuentoLineas'=>$importeDescuentoLineas, 'importeBruto'=>$importeBruto, 'importeNeto'=>$importeNeto, 'importeDescuento'=>$importeDescuento, 'importeDescuento'=>$baseImponible, 'desgloseIva'=>$desgloseIva, 'totalIva'=>$totalIva];
            $datos = ['correcto'=>'1', 'numero'=>$_POST['numero'], 'serie'=>$_POST['serie'], 'ejercicio'=>$_POST['ejercicio'], 'correo'=>$cliente[0]->EMail1];
            return $datos;
            //return 1;

    }


    public static function eliminarmod(){
        
        if($_POST['codigoArticulo']=="TEXTOLIBRE") $_POST['codigoArticulo']="TEXTO LIBRE";
        if($_POST['codigoArticulo'] == "PROCAREROLL") $_POST['codigoArticulo']="PROCARE ROLL";
        if($_POST['codigoArticulo'] == "P005-AZU4B") $_POST['codigoArticulo']="P 005-AZU4B";
        if($_POST['codigoArticulo'] == "P005-AZU7B") $_POST['codigoArticulo']="P 005-AZU7B";
        if($_POST['codigoArticulo'] == "P005-FUC7B") $_POST['codigoArticulo']="P 005-FUC7B";
        if($_POST['codigoArticulo'] == "PAETCMINIITNEGRO") $_POST['codigoArticulo']="PAETCMINIIT NEGRO";
        if($_POST['codigoArticulo'] == "PAETCMINIITROJO") $_POST['codigoArticulo']="PAETCMINIIT ROJO";
        if($_POST['codigoArticulo'] == "PAETCMINIITVERDE") $_POST['codigoArticulo']="PAETCMINIIT VERDE";

        $linea = DB::table('LineasOfertaCliente')
        ->select('LineasPosicion', 'Partida', 'UnidadesPedidas')
        ->where('SerieOferta', '=', $_POST['serie'])
        ->where('EjercicioOferta', '=', $_POST['ejercicio'])
        ->where('NumeroOferta', '=', $_POST['numero'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('CodigoArticulo', '=', $_POST['codigoArticulo'])
        ->where('Orden', '=', $_POST['orden'])
        ->get();

        if(count($linea) > 0){
            $articulo = DB::table("Articulos")->where("CodigoEmpresa", session('codigoEmpresa'))
            ->where("CodigoArticulo", $_POST['codigoArticulo'])->get();
            
            DB::table('LineasOfertaCliente')->where('lineasPosicion', $linea[0]->LineasPosicion)->delete();
            
            if($articulo[0]->Lote_ == -1 && $articulo[0]->FormulaLote == 1){
            
            DB::table('LineasOfertaCliente')
                ->where('VCodLote', $_POST['codigoArticulo'])
                ->where('EjercicioOferta', $_POST['ejercicio'])
                ->where('SerieOferta', $_POST['serie'])
                ->where('NumeroOferta', $_POST['numero'])
                ->delete();


            
               // DB::table('LineasOfertaCliente')->where('VCodLote', $_POST['codigoArticulo'])->delete();
            }
            $cabeceraUpdate = DB::table('LineasOfertaCliente')
            ->selectRaw('Sum(ImporteBruto) as importeBruto, Sum(ImporteDescuento) as importeDescuento, Sum(ImporteNeto) as importeNeto, 
            Sum(ImporteDescuentoCliente) as importeDescuentoCliente, Sum(ImporteProntoPago) as importeProntoPago, Sum(BaseImponible) as baseImponible, 
            Sum(CuotaIva) as cuotaIva, Sum(CuotaRecargo) as cuotaRecargo, Sum(TotalIva) as totalIva, Sum(ImporteLiquido) as importeLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['serie'])
            ->where('EjercicioOferta', '=', $_POST['ejercicio'])
            ->where('NumeroOferta', '=', $_POST['numero'])
            ->get();



            $query2 = DB::table('CabeceraOfertaCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieOferta', '=', $_POST['serie'])
            ->where('EjercicioOferta', '=', $_POST['ejercicio'])
            ->where('NumeroOferta', '=', $_POST['numero'])
            ->update([
                "ImporteBruto" => $cabeceraUpdate[0]->importeBruto,
                "ImporteDescuentoLineas"=> $cabeceraUpdate[0]->importeDescuento,
                "ImporteNetoLineas"=> $cabeceraUpdate[0]->importeNeto,
                "BaseImponible"=> $cabeceraUpdate[0]->baseImponible,
                "TotalCuotaIva"=>$cabeceraUpdate[0]->cuotaIva,
                "TotalCuotaRecargo"=>$cabeceraUpdate[0]->cuotaRecargo,
                "TotalIva"=>$cabeceraUpdate[0]->totalIva,
                "ImporteLiquido"=> $cabeceraUpdate[0]->importeLiquido, 
            ]);
            return 1;
        }else{
            return 0;
        }
        // return $linea;
    
        //return 1;

    }

}
