<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCorreo;
use App\Mail\EnviarFicha;
use App\Models\Articulo;
use App\Models\CabeceraAlbaranClienteModel;
use App\Models\Cliente;
use App\Models\Comisionista;
use App\Models\LineasAlbaranClienteModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MongoDB\Driver\Session;
use PhpParser\Node\Expr\Cast\Array_;

class PedidoController extends Controller
{
    public static function codigoGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function codigoPedidoNuevo($serie)
    {
        /* $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', 'Pedidos_Cli')
            ->where('sysNumeroSerie','=',$serie)
            ->where('sysEjercicio','=',date('Y'))
            ->max('sysContadorValor');
        return $query;*/

        $codigoEmpresa = \session('codigoEmpresa');
        // Establecemos la conexión
        $con = Conexion::OpenConnectionSQLServer();
        $json = array();
        $ejercicioAlbaran = date('Y');
        $contador = 0;
        /**
         * PARA QUE NO EXISTAN SALTOS DE TICKET
         */
        //COMPROBAMOS SI EXISTE VISTA PARA CADA UNA DE LAS SERIES
        $sql = "SELECT sysContadorValor FROM lsysContadores
                WHERE sysGrupo = $codigoEmpresa
                AND sysEjercicio = (SELECT TOP 1 Ejercicio FROM Periodos WHERE Periodos.CodigoEmpresa = lsysContadores.sysGrupo AND Periodos.fechainicio <= GETDATE() ORDER BY FechaInicio DESC)
                AND sysNombreContador = 'PEDIDOS_CLI'
                AND sysNumeroSerie = '$serie'
                AND sysAplicacion = 'GES'";
        $resultado = sqlsrv_query($con, $sql);
        if (sqlsrv_rows_affected($resultado)) {
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
            $contador = $fila["sysContadorValor"];
        }
        $sql = "drop view Contador_View_$serie";
        sqlsrv_query($con, $sql);
        $sql = "select * from sys.views where name = 'contador_view_$serie'";
        $resultado = sqlsrv_query($con, $sql);
        if (!sqlsrv_rows_affected($resultado)) {
            //SI NO EXISTE LA VISTA TENEMOS QUE CREARLA
            $sql = "create view Contador_View_$serie as
            (select (($contador - 1) + ROW_NUMBER() OVER (ORDER BY NumeroPedido asc)) as NumeroRegistro,
            NumeroPedido-(($contador - 1) + ROW_NUMBER() OVER (ORDER BY NumeroPedido asc)) as Resultado,
            NumeroPedido from CabeceraPedidoCliente
            where codigoempresa=$codigoEmpresa and EjercicioPedido=$ejercicioAlbaran and SeriePedido='$serie' AND NumeroPedido >=$contador)";
            sqlsrv_query($con, $sql);
        }
        //SI YA EXISTE O LA HEMOS CREADO EMPEZAMOS A TRABAJAR CON ELLA
        //VAMOS A OBTENER EL PRIMER REGISTRO DE LA VISTA CUYA DIFERENCIA SEA MAYOR QUE 0
        $sql = "select TOP 1 NumeroRegistro AS contadorRegistro from Contador_View_$serie where Resultado > 0";
        $resultado = sqlsrv_query($con, $sql);
        if (sqlsrv_rows_affected($resultado)) {
            //si obtenemos resultado quiere decir que hay contadores que se han saltado y únicamente vamos a darle a la cabecera y las líneas ese nº de contador
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
            $nuevoValorContador = $fila['contadorRegistro'];

        } else {
            $sql = "select MAX(NumeroPedido) as nuevoContador from CabeceraPedidoCliente where codigoempresa=$codigoEmpresa and EjercicioPedido=$ejercicioAlbaran and SeriePedido='$serie'";
            $resultado = sqlsrv_query($con, $sql);
            if (sqlsrv_rows_affected($resultado)) {
                //si obtenemos resultado quiere decir que hay contadores que se han saltado y únicamente vamos a darle a la cabecera y las líneas ese nº de contador
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                $nuevoValorContador = $fila['nuevoContador'] + 1;
            }
            //EN CASO DE QUE NO HAYA RESULTADO, QUIERE DECIR QUE NO HAY NINGÚN Nº SUELTO Y USAREMOS EL CONTADOR QUE MARQUE LA TABLA LSYSCONTADORES
            $tabla = "lsysContadores";
            $sql = "SELECT sysContadorValor FROM $tabla
                WHERE sysGrupo = $codigoEmpresa
                AND sysEjercicio = (SELECT TOP 1 Ejercicio FROM Periodos WHERE Periodos.CodigoEmpresa = lsysContadores.sysGrupo AND Periodos.fechainicio <= GETDATE() ORDER BY FechaInicio DESC)
                AND sysNombreContador = 'PEDIDOS_CLI'
                AND sysNumeroSerie = '$serie'
                AND sysAplicacion = 'GES'";
            $resultado = sqlsrv_query($con, $sql);
            if (sqlsrv_rows_affected($resultado)) {
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

                $tabla = "lsysContadores";
                $sql = "UPDATE $tabla
                    SET sysContadorValor = $nuevoValorContador
                    WHERE sysGrupo = $codigoEmpresa
                    AND sysEjercicio = (SELECT TOP 1 Ejercicio FROM Periodos WHERE Periodos.CodigoEmpresa = lsysContadores.sysGrupo AND Periodos.fechainicio <= GETDATE() ORDER BY FechaInicio DESC)
                    AND sysNombreContador = 'PEDIDOS_CLI'
                    AND sysNumeroSerie = '$serie'
                    AND sysAplicacion = 'GES'";
                $resultado = sqlsrv_query($con, $sql);
                sqlsrv_free_stmt($resultado);

            } else { // Si no existe el contador, se crea

                $tabla = "lsysContadores";
                $sql = "INSERT INTO $tabla (sysGrupo,sysEjercicio,sysNombreContador,sysNumeroSerie,sysAplicacion,sysContadorValor)
                    VALUES(1,(SELECT TOP 1 Ejercicio FROM Periodos,$tabla WHERE Periodos.CodigoEmpresa = lsysContadores.sysGrupo AND Periodos.fechainicio <= GETDATE() ORDER BY FechaInicio DESC),
                    'PEDIDOS_CLI','$serie','GES',1)";
                $insert = sqlsrv_query($con, $sql);
                if ($insert != 'FALSE') {
                    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                    $nuevoValorContador = 1;
                }
            }

        }
        //FIN MODIFICACIÓN SALTOS DE TICKET
        sqlsrv_close($con);
        return $nuevoValorContador;

    }
    public static function codigoAlbaranNuevo($serie)
    {
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', 'Albaran_Cli')
            ->where('sysNumeroSerie', '=', $serie)
            ->where('sysEjercicio', '=', date('Y'))
            ->max('sysContadorValor');
        return $query;
    }
    public static function codigoFacturaNuevo($serie)
    {
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', 'Factura_Cli')
            ->where('sysNumeroSerie', '=', $serie)
            ->where('sysEjercicio', '=', date('Y'))
            ->max('sysContadorValor');
        return $query;
    }

    public static function obtenerLineasAlbaranSeleccionado()
    {
        $query = LineasAlbaranClienteModel::selectRaw('[%Descuento] as Descuento, [%Iva] as Iva, *')
            ->where('EjercicioAlbaran', $_POST['ejercicioAlbaran'])
            ->where('SerieAlbaran', $_POST['serieAlbaran'])
            ->where('NumeroAlbaran', $_POST['numeroAlbaran'])->get();

        return $query;
    }



    public static function obtenerPedidoDia()
    {
        $dia = date('Y-m-d');
        $query = DB::table('LineasPedidoCliente')->select(
            'li.CodigoArticulo AS codigo',
            'li.DescripcionArticulo AS descripcion',
            'li.FechaPedido AS fecha',
            'li.UnidadesPedidas AS unidades',
            'li.precio',
            'li.%Descuento AS descuento',
            'CabeceraPedidoCliente.CifDni as dniCliente',
            'CabeceraPedidoCliente.RazonSocial as nombreCliente',
            'CabeceraPedidoCliente.ImporteLiquido'
        )->from('LineasPedidoCliente AS li')
            ->join('CabeceraPedidoCliente', function ($join) {
                $join->on('CabeceraPedidoCliente.CodigoEmpresa', '=', 'li.CodigoEmpresa');
                $join->on('CabeceraPedidoCliente.EjercicioPedido', '=', 'li.EjercicioPedido');
                $join->on('CabeceraPedidoCliente.SeriePedido', '=', 'li.SeriePedido');
                $join->on('CabeceraPedidoCliente.NumeroPedido', '=', 'li.NumeroPedido');
            })->where('li.CodigoArticulo', '!=', '00')
            ->where('li.CodigoArticulo', '!=', 'INFORMACION')
            ->whereBetween('Li.FechaPedido', [$dia . ' 00:00:00.000', $dia . ' 23:59:59.000'])
            ->where('li.CodigoAlmacen', '=', '0')
            ->where('li.CodigoEmpresa', '=', session('codigoEmpresa'))->get();



        return $query;
    }


    public static function obtenerTiposPago ()
    {
        $query = DB::table('CondicionesPlazos')->where('codigoCondiciones', '!=', 2)
            ->where('codigoCondiciones', '!=', 3)
            ->where('codigoCondiciones', '!=', 6)
            ->where('codigoCondiciones', '!=', 12)
            ->where('codigoEmpresa', '=', session('codigoEmpresa'))->get();
        return $query;
    }

    static function insercionPedido()
    {

        $correcto = "ERROR";
        
        $array = $_POST['datos']['lineas'];
        $cliente = ClienteController::obtenerDatosClientePedido($_POST['datos']['codigoCliente']);
        $clienteConta = ClienteController::obtenerDatosClientePedidoConta($_POST['datos']['codigoCliente']);
        $comisionista = ComisionistaController::obtenerDatosComisionistaPedido(session('codigoComisionista'));

        //INSERCIÓN PEDIDO
        //comprobación para ver si existe la cabecera, si ya existe no se vuelve a insertar
        $existeCabeceraPedido = self::comprobarExisteCabeceraPedido($_POST['datos']['idPedido'], $_POST['datos']['seriePedido'], $_POST['datos']['codigoCliente']);
        $_POST['datos']['idPedido'] = $existeCabeceraPedido[1];
        //return $existeCabeceraPedido;
        $orden = 5;
        // $importeDescuentoLineas = $_POST['datos']['total'] * $_POST['datos']['descuentoLineas'];
        // $importeBruto = $_POST['datos']['importeBruto'];
        // $importeNeto = $_POST['datos']['total'] / 1.21  - $importeDescuentoLineas;
        // $importeDescuento = $importeNeto * session('descuento') / 100;
        // $baseImponible = $importeNeto - $importeDescuento;
        // $desgloseIva = $_POST['datos']['total'] / 1.21;
        // $totalIva = $_POST['datos']['total'] - $desgloseIva;

        // $importeDescuentoLineas = $_POST['datos']['total'] * $_POST['datos']['descuentoLineas'];
        // $importeBruto = $_POST['datos']['importeBruto'] + $importeDescuentoLineas;
        // $importeNeto = $_POST['datos']['importeBruto'];
        // $importeDescuento = $importeNeto * session('descuento') / 100;
        // $baseImponible = $importeNeto - $importeDescuento;
        // $desgloseIva = $baseImponible * 0.21;
        // $totalIva = $_POST['datos']['total'] - $desgloseIva;


        if ($existeCabeceraPedido[0] == false) {

            // $importeBruto1 = $array[0]['precio'] * $array[0]['cantidad'];
            // $importeDescuento1 = $importeBruto1 *  session('descuento')/ 100;
            // $importeNeto1 = $importeBruto1 - $importeDescuento1;            
            // $descuentoCliente1 = $importeNeto1 * $cliente[0]['%Descuento'] / 100;
            // $baseImponible1 = $importeNeto1 - $descuentoCliente1;
            // $totalIva1 = $baseImponible1 * 0.21;
            // $importeLiquido1 = $baseImponible1 + $totalIva1;
                        
            $quiery = DB::table('Comisionistas')
                ->select('CodigoJefeVenta_')
                ->where('CodigoComisionista', '=', session('codigoComisionista'))
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->get();

            $cabecera = DB::table('CabeceraPedidoCliente')
            ->selectRaw('*, [%ProntoPago] as ProntoPago')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoCliente', '=', $_POST['datos']['codigoCliente'])
            ->orderby('FechaPedido', 'Desc')
            ->take(1)
            ->get();

            $codigoCondiciones = 0;
            if ($cabecera->count() != null) {
                $cabecera[0]->CodigoCondiciones = $codigoCondiciones;
            }
            
            

            //var_dump($cliente[0]['%ProntoPago']);
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



            $pedido = DB::table('CabeceraPedidoCliente');
            $pedido->insert([
                "CodigoEmpresa" => session('codigoEmpresa'),
                "CodigoCliente" => $_POST['datos']['codigoCliente'],
                "EjercicioPedido" => date("Y"),
                "SeriePedido" => $_POST['datos']['seriePedido'],
                "NumeroPedido" => $_POST['datos']['idPedido'],
                "FechaPedido" => date("Y-m-d") . " " . date("H:i:s"),                
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
                "ReservarStock_" => -1,                
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
                "InicioNoPago" => $InicioNoPago,
                "FinNoPago" => $FinNoPago,
                "MesesComerciales" => $MesesComerciales,
                "ControlarFestivos" => $ControlarFestivos,
                "RemesaHabitual" => $RemesaHabitual,
                "CodigoTipoEfecto" => $CodigoTipoEfecto,
                "CodigoBanco" => $CodigoBanco,
                "CodigoAgencia" => $CodigoAgencia,
                "DC" => $DC,
                "CCC" => $CCC,
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
                "AlbaranValorado"=>-1,

                
                "StatusAprobado" => 0,
                
                "CodigoJefeVenta_" => $quiery[0]->CodigoJefeVenta_,                
            ]);

            if ($pedido) $correcto = "cabecera";
            else $correcto = "Error cabecera pedido";
        }  
        // else {
        //     $query = DB::table('CabeceraPedidoCliente')
        //         ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        //         ->where('SeriePedido', '=', $_POST['datos']['seriePedido'])
        //         ->where('EjercicioPedido', '=', date('Y'))
        //         ->where('NumeroPedido', '=', $_POST['datos']['idPedido'])
        //         ->update([
        //             "ImporteBruto" => 0,
        //             "ImporteBrutoPendiente" => 0, //realmente es con cálculo de importe pendiente
        //             "ImporteNetoLineas" => 0,
        //             "ImporteNetoLineasPendiente" => 0, //realmente es con cálculo de importe pendiente
        //             "ImporteDescuento" => 0,
        //             "ImporteParcial" => 0,
        //             "ImporteParcialPendiente" => 0, //realmente es con cálculo de importe pendiente
        //             "BaseImponible" => 0,
        //             "BaseImponiblePendiente" =>0, //realmente es con cálculo de importe pendiente
        //             "TotalCuotaIva" => 0,
        //             "TotalIva" => 0,
        //             "ImporteLiquido" => 0,
        //             "ImporteFactura" => 0,
        //         ]);
        // }
        $numeroOrdenLinea = self::obtenerUltimoNumeroOrden($_POST['datos']['idPedido'], $_POST['datos']['seriePedido']);
        if (!empty($numeroOrdenLinea)) {
            //para establecer el siguiente número que le tocaría
            $orden = $numeroOrdenLinea + 5;
        }
        $comisionista = ComisionistaController::obtenerDatosComisionistaPedido(session('codigoComisionista'));

        foreach ($array as $linea) {
                $recargo = 0;
                $ivaArticulo = 0;
                $codigoIva = 0;


            $existeArticuloEnPedido = self::comprobarExisteArticuloEnPedido($_POST['datos']['idPedido'], $_POST['datos']['seriePedido'], $linea['codigo'],$linea['orden']);
            if ($existeArticuloEnPedido == false) {
                self::actualizarCantidadLineasCabeceraPedido($_POST['datos']['seriePedido'], $_POST['datos']['idPedido']);
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
                ->where('CodigoArticulo', '=', trim($linea['codigo']))
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
                if($importeNeto == 0 ){
                    if($importeNeto == 0 && $linea['precio'] == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        $porMargenBeneficio = 100;
                    }
                }else{    
                    //$precio2 = $linea['precio'] - $importeDescuento;
                    $porMargenBeneficio = ($margenDeBeneficio  * 100) / $importeNeto;
                }                    
                

                $lineaPedido = DB::table('LineasPedidoCliente');
                $lineaPedido->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "CodigoDelCliente" => $_POST['datos']['codigoCliente'],
                    "EjercicioPedido" => date("Y"),
                    'CodigoAlmacen' => '0',
                    "SeriePedido" => $_POST['datos']['seriePedido'],
                    "NumeroPedido" => $_POST['datos']['idPedido'],
                    'LineasPosicion' => $guidArticulo,
                    "CodigoArticulo" => $linea['codigo'],
                    "DescripcionArticulo" => $linea['descripcion'],
                    "CodigoAlmacenAnterior"=>'0',
                    "CodigoFamilia" => $articulo[0]['CodigoFamilia'],
                    "CodigoSubFamilia" => $articulo[0]['CodigoSubfamilia'],
                    "TipoArticulo" => $articulo[0]['TipoArticulo'],
                    //"FechaEntrega"=>session('fechaEntrega'). " 00:00:00.000",
                    "ReservarStock_" => -1,
                    "Estado" => 0,
                    "GrupoIva" => $codigoIva,
                    "CodigoIva" => $ivaArticulo,
                    "%Iva" => $ivaArticulo,
                    "UnidadesPendientesFabricar" => $linea['cantidad'],
                    "UnidadesPedidas" => $linea['cantidad'],
                    "UnidadesPendientes" => $linea['cantidad'],
                    "UnidadesServidas" => 0,
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
                    "ImporteBrutoPendiente" => $importeBruto,
                    "ImporteDescuento" => $importeDescuento,
                    "ImporteNeto" => $importeNeto,
                    "ImporteNetoPendiente" => $importeNeto,
                    "ImporteDescuentoCliente" => $descuentoCliente,
                    "BaseImponible" => $baseImponible,
                    "BaseIva" => $baseImponible,
                    "CuotaIva" => $cuotaiva,
                    "TotalIva" => $totalIva,
                    "ImporteLiquido" => $importeLiquido,

                    "UnidadesPendAnterior"=>$linea['cantidad'],
                    "PrecioCoste"=>$precioCompra ,
                    "ImporteCoste"=>$precioCompra * $linea['cantidad'] ,
                    "ImporteParcial"=> $importeBruto,
                    "ImporteParcialPendiente"=>$importeBruto,
                    "BaseImponiblePendiente"=>$baseImponible,
                    "PorMargenBeneficio"=>$porMargenBeneficio,
                    "MargenBeneficio"=>$margenDeBeneficio,
                    //"%Margen"=>$margen,

                    "CodigoTransaccion"=>1,
                    "CodigoDefinicion_"=>$articulo[0]['CodigoDefinicion_'],

                ]);
                // if ($linea['fechaCaducidad'] != "0") {
                //     $partida = $linea['partida'];
                //     $lineaPedido->insert([
                //         "Partida" => $linea['partida'],
                //         "FechaCaduca" => $linea['fechaCaducidad']
                //     ]);
                // } else {
                    $partida = "";
                // }



                
                if ($lineaPedido) {
                    $correcto = "linea";
                } else {
                    $correcto = "Error linea pedido";
                }
                if ($linea['codigo'] != "PORTES") {
                    //insercción en tabla MOVIMIENTOPENDIENTES
                    //comprobación para ver si ya existe el pedido en movimientopendientes y solo actualizar datos cantidad y pago
                    $existePedidoEnMovimientoPendientes = self::comprobarExisteArticuloEnMovimientoPendiente($_POST['datos']['idPedido'], $_POST['datos']['seriePedido'], $linea['codigo']);
                    //SI $existePedidoEnMovimientoPendientes es falso se realiza insercción, si es verdadera se realiza update cantidades y precios
                    //if ($existePedidoEnMovimientoPendientes == false) {
                        //insercción
                        $lineaPedido = DB::table('MovimientoPendientes');
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "Ejercicio" => date('Y'),
                            "Periodo" => date('m'),
                            "Serie" => $_POST['datos']['seriePedido'],
                            "Documento" => $_POST['datos']['idPedido'],
                            "CodigoArticulo" => $linea['codigo'],
                            'CodigoAlmacen' => '0',
                            "Unidades" => $linea['cantidad'],
                            "Unidades2_" => $linea['cantidad'],
                            "Precio" => $linea['precio'],
                            "Importe" => $linea['precio'] * $linea['cantidad'],
                            "Comentario" => "PedidoVenta: " . date('Y') . "/" . $_POST['datos']['seriePedido'] . "/" . $_POST['datos']['idPedido']."/".$orden,
                            "CodigoCliente" => $_POST['datos']['codigoCliente'],
                            "StatusAcumulado" => -1,
                            "OrigenMovimiento" => "C",
                            "MovOrigen" => $guidArticulo,
                            "EmpresaOrigen" => session('codigoEmpresa'),
                            "EjercicioDocumento" => date('Y'),
                            "ReservarStock_" => -1
                        ]);
                    //}
                    //insercción en tabla ACUMULADOPENDIENTES
                    //comprobación para ver si existe ya ese articulo en ese almacén y con el número de partida para actualizar o bien realizar insercción
                    $existeArticuloEnAcumuladoPendientes = self::comprobarExisteArticuloEnAcumuladoPendientes($linea['codigo'], $partida);
                    if ($existeArticuloEnAcumuladoPendientes == false) {
                        //si no hay ninguna línea en la que haya el artículo con la partida, el almacen y la empresa se realiza una insercción
                        $lineaPedido = DB::table("AcumuladoPendientes");
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "CodigoArticulo" => $linea['codigo'],
                            'CodigoAlmacen' => '0',
                            "Partida" => $partida,
                            "PendienteServir" => $linea['cantidad'],
                            "PendienteServirTipo_" => $linea['cantidad'],
                            "StockReservadoPedidos_" => $linea['cantidad'],
                            "StockReservadoPedidosTipo_" => $linea['cantidad']
                        ]);
                    } else {
                        //si existe articulo se hace un update
                        //obtenemos cantidad para actualizarla
                        $cantidadAactualizar = self::obtenerCantidadArticuloEnAcumuladoPendientes($linea['codigo'], $partida);
                        self::actualizarCantidadAcumuladoPendientes($linea['codigo'], $partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $linea['cantidad']);
                    }                    
                }
                $orden = $orden + 5;   
            }


        }

            $cabeceraUpdate = DB::table('LineasPedidoCliente')
            ->selectRaw('Sum(ImporteBruto) as importeBruto, Sum(ImporteDescuento) as importeDescuento, Sum(ImporteNeto) as importeNeto, 
            Sum(ImporteDescuentoCliente) as importeDescuentoCliente, Sum(ImporteProntoPago) as importeProntoPago, Sum(BaseImponible) as baseImponible, 
            Sum(CuotaIva) as cuotaIva, Sum(CuotaRecargo) as cuotaRecargo, Sum(TotalIva) as totalIva, Sum(ImporteBrutoPendiente) as importeBrutoPendiente,
            sum(ImporteDescuentoPendiente) as importeDescuentoPendiente, Sum(ImporteNetoPendiente) as importeNetoPendiente,  Sum(BaseImponiblePendiente) as baseImponiblePendiente,
            Sum(ImporteLiquido) as importeLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $_POST['datos']['seriePedido'])
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $_POST['datos']['idPedido'])
            ->get();

            $query2 = DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $_POST['datos']['seriePedido'])
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $_POST['datos']['idPedido'])
            ->update([

                "ImporteBruto" => $cabeceraUpdate[0]->importeBruto,
                "ImporteDescuentoLineas"=> $cabeceraUpdate[0]->importeDescuento,
                "ImporteNetoLineas"=> $cabeceraUpdate[0]->importeNeto,
                "BaseImponible"=> $cabeceraUpdate[0]->baseImponible,
                "TotalCuotaIva"=>$cabeceraUpdate[0]->cuotaIva,
                "TotalCuotaRecargo"=>$cabeceraUpdate[0]->cuotaRecargo,
                "TotalIva"=>$cabeceraUpdate[0]->totalIva,
                "ImporteLiquido"=> $cabeceraUpdate[0]->importeLiquido,
                "ImporteBrutoPendiente"=>$cabeceraUpdate[0]->importeBrutoPendiente,
                "ImporteNetoLineasPendiente"=>$cabeceraUpdate[0]->importeNetoPendiente,
                "ImporteParcialPendiente"=>$cabeceraUpdate[0]->baseImponiblePendiente,
                "BaseImponiblePendiente"=>$cabeceraUpdate[0]->baseImponiblePendiente
            ]);

            
            if($cabeceraUpdate) $correcto = 'OK';
       
        return [$correcto, $_POST['datos']['idPedido']];
    }

    public static function actualizarContadorPedido()
    {
        $serie = $_POST['seriePedido'];
        $codigoEmpresa = \session('codigoEmpresa');
        // Establecemos la conexión
        $con = Conexion::OpenConnectionSQLServer();
        $json = array();
        $ejercicioAlbaran = date('Y');
        /**
         * PARA QUE NO EXISTAN SALTOS DE TICKET
         */
        //COMPROBAMOS SI EXISTE VISTA PARA CADA UNA DE LAS SERIES
        $sql = "SELECT sysContadorValor FROM lsysContadores
                WHERE sysGrupo = $codigoEmpresa
                AND sysEjercicio = 0
                AND sysNombreContador = 'PEDIDOS_CLI'
                AND sysNumeroSerie = '$serie'
                AND sysAplicacion = 'GES'";
        $resultado = sqlsrv_query($con, $sql);
        if (sqlsrv_rows_affected($resultado)) {
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
            $contador = $fila["sysContadorValor"];
        }
        $sql = "drop view Contador_View_$serie";
        sqlsrv_query($con, $sql);
        $sql = "select * from sys.views where name = 'contador_view_$serie'";
        $resultado = sqlsrv_query($con, $sql);
        if (!sqlsrv_rows_affected($resultado)) {
            //SI NO EXISTE LA VISTA TENEMOS QUE CREARLA
            $sql = "create view Contador_View_$serie as
            (select (($contador - 1) + ROW_NUMBER() OVER (ORDER BY NumeroPedido asc)) as NumeroRegistro,
            NumeroPedido-(($contador - 1) + ROW_NUMBER() OVER (ORDER BY NumeroPedido asc)) as Resultado,
            NumeroPedido from CabeceraPedidoCliente
            where codigoempresa=$codigoEmpresa and EjercicioPedido=$ejercicioAlbaran and SeriePedido='$serie' AND NumeroPedido >=$contador)";
            sqlsrv_query($con, $sql);
        }
        //SI YA EXISTE O LA HEMOS CREADO EMPEZAMOS A TRABAJAR CON ELLA
        //VAMOS A OBTENER EL PRIMER REGISTRO DE LA VISTA CUYA DIFERENCIA SEA MAYOR QUE 0
        $sql = "select TOP 1 NumeroRegistro AS contadorRegistro from Contador_View_$serie where Resultado > 0";
        $resultado = sqlsrv_query($con, $sql);
        if (sqlsrv_rows_affected($resultado)) {
            //si obtenemos resultado quiere decir que hay contadores que se han saltado y únicamente vamos a darle a la cabecera y las líneas ese nº de contador
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
            $json["sysContadorValor"] = $fila['contadorRegistro'];
        } else {
            $sql = "select MAX(NumeroPedido) as nuevoContador from CabeceraPedidoCliente where codigoempresa=$codigoEmpresa and EjercicioPedido=$ejercicioAlbaran and SeriePedido='$serie'";
            $resultado = sqlsrv_query($con, $sql);
            if (sqlsrv_rows_affected($resultado)) {
                //si obtenemos resultado quiere decir que hay contadores que se han saltado y únicamente vamos a darle a la cabecera y las líneas ese nº de contador
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                $nuevoValorContador = $fila['nuevoContador'] + 1;
            }
            //EN CASO DE QUE NO HAYA RESULTADO, QUIERE DECIR QUE NO HAY NINGÚN Nº SUELTO Y USAREMOS EL CONTADOR QUE MARQUE LA TABLA LSYSCONTADORES
            $tabla = "lsysContadores";
            $sql = "SELECT sysContadorValor FROM $tabla
                WHERE sysGrupo = $codigoEmpresa
                AND sysEjercicio = 0
                AND sysNombreContador = 'PEDIDOS_CLI'
                AND sysNumeroSerie = '$serie'
                AND sysAplicacion = 'GES'";
            $resultado = sqlsrv_query($con, $sql);
            if (sqlsrv_rows_affected($resultado)) {
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

                $tabla = "lsysContadores";
                $sql = "UPDATE $tabla
                    SET sysContadorValor = $nuevoValorContador
                    WHERE sysGrupo = $codigoEmpresa
                    AND sysEjercicio = 0
                    AND sysNombreContador = 'PEDIDOS_CLI'
                    AND sysNumeroSerie = '$serie'
                    AND sysAplicacion = 'GES'";
                $resultado = sqlsrv_query($con, $sql);
                sqlsrv_free_stmt($resultado);

                $json["sysContadorValor"] = $nuevoValorContador;
            } else { // Si no existe el contador, se crea

                $tabla = "lsysContadores";
                $sql = "INSERT INTO $tabla (sysGrupo,sysEjercicio,sysNombreContador,sysNumeroSerie,sysAplicacion,sysContadorValor)
                    VALUES(1,0,
                    'PEDIDOS_CLI','$serie','GES',1)";
                $insert = sqlsrv_query($con, $sql);
                if ($insert != 'FALSE') {
                    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                    $json["sysContadorValor"] = 1;
                } else {
                    $json["sysContadorValor"] = "No furula";
                    // if( ($errors = sqlsrv_errors() ) != null) {
                    //     foreach( $errors as $error ) {
                    //         echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                    //         echo "code: ".$error[ 'code']."<br />";
                    //         echo "message: ".$error[ 'message']."<br />";
                    //     }
                    // }
                }
            }
        }
        //FIN MODIFICACIÓN SALTOS DE TICKET
        sqlsrv_close($con);

        return "OK";
    }


    public static function actualizarContador($serie, $numero, $nombreContador)
    {
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNombreContador', '=', $nombreContador)
            ->where('sysNumeroSerie', '=', $serie)
            ->update(['sysContadorValor' => $numero + 1]);
        return $query;
    }

    static function obtenerContadorBorrador($serie)
    {
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', 'Pedidos_Cli')
            ->where('sysNumeroSerie', '=', $serie)
            ->where('sysEjercicio', '=', 0)
            ->max('sysContadorValor');
        return $query;
    }

    public static function obtenerIdPago($descripcion)
    {
        $query = DB::table('CondicionesPlazos')->select('CodigoCondiciones')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Condiciones', '=', $descripcion)->get();
        return $query;
    }

    public static function buscarPedido()
    {
        $query = DB::table('CabeceraPedidoCliente')->select(
            'cab.CodigoEmpresa as codigoEmpresa',
            'cab.ImporteLiquido as importeLiquido',
            'cab.CodigoCliente as codigoCliente',
            'cab.RazonSocial',
            'cab.Domicilio',
            'cab.CifDni',
            'cli.EMail1',
            'lin.CodigoArticulo',
            'lin.DescripcionArticulo',
            'lin.UnidadesPedidas',
            'lin.Precio',
            'lin.%Descuento AS Descuento',
            'cli.Telefono',
            'lin.%Iva as Iva',
            'cli.VAutorizacionRGPD',
            'cab.ObservacionesPedido',
            'cab.SeriePedido',
            'cli.%Descuento as descuentoCliente',
            'lin.*'
        )
            ->from('Clientes as cli')
            ->join('CabeceraPedidoCliente as cab', function ($join) {
                $join->on('cab.CodigoCliente', '=', 'cli.CodigoCliente');
            })
            ->join('LineasPedidoCliente as lin', function ($join) {
                $join->on('lin.CodigoEmpresa', '=', 'cab.CodigoEmpresa');
                $join->on('lin.EjercicioPedido', '=', 'cab.EjercicioPedido');
                $join->on('lin.SeriePedido', '=', 'cab.SeriePedido');
                $join->on('lin.NumeroPedido', '=', 'cab.NumeroPedido');
            })
            ->where('cab.SeriePedido', '=', $_POST['seriePedido'])
            ->where('cab.EjercicioPedido', '=', $_POST['ejercicioPedido'])
            ->where('cab.NumeroPedido', '=', $_POST['numeroPedido'])->get();
        if ($query->count() != 0) {
            session(['codigoCliente' => $query[0]->codigoCliente]);
            session(['nombreCliente' => $query[0]->RazonSocial]);
            session(['telefonoCliente' => $query[0]->Telefono]);
            session(['descuento' => $query[0]->descuentoCliente]);
        }
        return $query;
    }

    public static function obtenerUltimoPedidoPendientePuntoVenta()
    {

        $cabeceraPedido = self::obtenerCabeceraUltimoPedidoPendientePuntoVenta($_POST['puntoVenta']);
        $lineasPedido = self::obtenerLineaUltimoPedidoPendientePuntoVenta($cabeceraPedido[0]->SeriePedido, $cabeceraPedido[0]->NumeroPedido, $_POST['puntoVenta'], $cabeceraPedido[0]->codigoCliente);


        $descuentoCliente = $cabeceraPedido[0]->descuentoCliente;
        $html = "";
        for ($i = 0; $i < count($lineasPedido); $i++) {
            if ($lineasPedido[$i]->CodigoArticulo == "PORTES") {
                $subtotalDescuento =  round($lineasPedido[$i]->ImporteLiquido, 2)  - ($lineasPedido[$i]->ImporteLiquido * $lineasPedido[$i]->descuento / 100);

                $html .= "<tr class='linea-terminada' id='linea-envio'>" .
                    "<td>ENVIO</td>" .
                    "<td>Agencia</td>" .
                    "<td></td>" .
                    "<td>" . round($lineasPedido[$i]->Precio, 2) . "</td>" .
                    "<td><input type='number' class='descuento' name='descuentoProducto' id='descuentoProducto' value='" . round($lineasPedido[$i]->descuento, 2) . "' min='0' max='100' onchange='actualizarPrecioDescuento()'></td>" .
                    "<td>" . round($lineasPedido[$i]->Precio, 2) . "</td>" .
                    "<td id='celdaUdTotalDevolEnvio' style='display: none'><input type='text' readonly name='cantidadEnvio' class='cantidadEnvio' value='1'</td>" .
                    "<td><input type='number' class='cantidadDescuento' name='cantidadDescuento' id='cantidadDescuento' value='1'></td>" .
                    "<td id='inputSubTotalEnvio'><input type='hidden' id='envio' class='envio' name='envio' value='1'>" . $subtotalDescuento . "</td>" .
                    "<input type='hidden' id='subTotal' class='subTotal subEnvio'  value='" . $subtotalDescuento . "'/>" .
                    "<input type='hidden' class='codigoArticulo' value='PORTES' />" .
                    "<input type='hidden' class='precio' value='" . round($lineasPedido[$i]->Precio, 2) . "' />" .
                    "<input type='hidden' class='cantidad' value='1' />" .
                    "<input type='hidden' class='descripcion' value='Portes envio' />" .
                    "<input type='hidden' class='precioUnidad' value='" . round($lineasPedido[$i]->Precio, 2) . "' />" .
                    "<input type='hidden' class='descuento_articulo' id='inputDescuentoEnvio' value='" . round($lineasPedido[$i]->descuento, 2) . "' />" .
                    "</tr>";
            } else {
                $html .= "<tr class='linea-terminada " . $lineasPedido[$i]->CodigoArticulo . "'>" .
                    "<td><button type='button' class='btn mr-2 text-danger eliminarProducto' onclick='eliminarProductoTabla(this.id)' id='" . $lineasPedido[$i]->CodigoArticulo . "'><i class='fas fa-trash-alt'></i></button>" .
                    $lineasPedido[$i]->CodigoArticulo . "</td>" .
                    "<td>" . $lineasPedido[$i]->DescripcionArticulo . "</td>" .
                    "<td>" . $lineasPedido[$i]->Partida . "</td>" .
                    "<td>" . round($lineasPedido[$i]->Precio, 2) . "</td>" .
                    "<td><input type='number' class='" . $lineasPedido[$i]->CodigoArticulo . "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" . $lineasPedido[$i]->CodigoArticulo . "' value='" . round($lineasPedido[$i]->descuento, 2) . "' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)'></td>" .
                    "<td>" . round(round($lineasPedido[$i]->ImporteLiquido * 100) / 100 / $lineasPedido[$i]->UnidadesPedidas, 2) . "</td>" .
                    "<td><input type='number' class='" . $lineasPedido[$i]->CodigoArticulo . "cantidadProducto' name='cantidadProducto' id='cantidadProducto-" . $lineasPedido[$i]->CodigoArticulo . "' value='" . round($lineasPedido[$i]->UnidadesPedidas) . "' onchange='actualizarDatos(this.id)'></td>" .
                    "<td>" . round($lineasPedido[$i]->ImporteLiquido * 100) / 100 . "</td>" .
                    "<input type='hidden' id='" . $lineasPedido[$i]->CodigoArticulo . "1' class='subTotal' value='" . round($lineasPedido[$i]->ImporteLiquido, 2) . "'/>" .
                    "<input type='hidden' class='codigoArticulo' value='" . $lineasPedido[$i]->CodigoArticulo . "' />" .
                    "<input type='hidden' class='precio' value='" . round($lineasPedido[$i]->ImporteLiquido, 2) / round($lineasPedido[$i]->UnidadesPedidas, 2) . "' />" .
                    "<input type='hidden' id='" . $lineasPedido[$i]->CodigoArticulo . "cantidad'' class='cantidad' value='" . round($lineasPedido[$i]->UnidadesPedidas) . "' />" .
                    "<input type='hidden' class='descripcion' value='" . $lineasPedido[$i]->DescripcionArticulo . "' />" .
                    "<input type='hidden' class='precioUnidad' value='" . round($lineasPedido[$i]->Precio, 2) . "' />" .
                    "<input type='hidden' class='descuento_cliente' id='descuento_cliente_pedido' value='" . round($descuentoCliente, 2) . "' />" .
                    "<input type='hidden' class='descuento_articulo' id='" . $lineasPedido[$i]->CodigoArticulo . "2' value='" . round($lineasPedido[$i]->descuento, 2) . "' />" .
                    "</tr>";
            }
        }

        return $html;
    }

    public static function obtenerCabeceraUltimoPedidoPendientePuntoVenta($puntoVenta)
    {
        $query = DB::table('CabeceraPedidoCliente')->select(
            'cab.CodigoEmpresa as codigoEmpresa',
            'cab.CodigoCliente as codigoCliente',
            'cab.RazonSocial',
            'cli.Telefono',
            'lin.%Iva as iva',
            'cli.VAutorizacionRGPD',
            'cab.ObservacionesPedido',
            'cab.SeriePedido',
            'cab.NumeroPedido',
            'cli.%Descuento as descuentoCliente',
            'cab.CodigoComisionista',
            'cab.ImporteLiquido'
        )->from('Clientes as cli')
            ->join('CabeceraPedidoCliente as cab', function ($join) {
                $join->on('cab.CodigoCliente', '=', 'cli.CodigoCliente');
            })
            ->join('LineasPedidoCliente as lin', function ($join) {
                $join->on('lin.CodigoEmpresa', '=', 'cab.CodigoEmpresa');
                $join->on('lin.EjercicioPedido', '=', 'cab.EjercicioPedido');
                $join->on('lin.SeriePedido', '=', 'cab.SeriePedido');
                $join->on('lin.SeriePedido', '=', 'cab.SeriePedido');
                $join->on('lin.NumeroPedido', '=', 'cab.NumeroPedido');
            })
            ->where('lin.Estado', '=', 0)
            ->where('lin.CodigoAlmacen', '=', '0')
            ->orderByDesc('cab.FechaPedido')->limit(1)->get();
        if ($query->count() != 0) {
            session(['codigoCliente' => $query[0]->codigoCliente]);
            session(['nombreCliente' => $query[0]->RazonSocial]);
            session(['telefonoCliente' => $query[0]->Telefono]);
            session(['descuento' => $query[0]->descuentoCliente]);
            Session(['codigoComisionista' => $query[0]->CodigoComisionista]);
        }

        return $query;
    }
    public static function obtenerCabeceraPedidoPendientePuntoVenta()
    {
        $query = DB::table('CabeceraPedidoCliente')->select(
            'cab.CodigoEmpresa as codigoEmpresa',
            'cab.CodigoCliente as codigoCliente',
            'cab.RazonSocial',
            'cli.Telefono',
            'lin.%Iva as iva',
            'cli.VAutorizacionRGPD',
            'cab.ObservacionesPedido',
            'cab.SeriePedido',
            'cab.NumeroPedido',
            'cli.%Descuento as descuentoCliente',
            'cab.CodigoComisionista',
            'cab.ImporteLiquido'
        )->from('Clientes as cli')
            ->join('CabeceraPedidoCliente as cab', function ($join) {
                $join->on('cab.CodigoCliente', '=', 'cli.CodigoCliente');
            })
            ->join('LineasPedidoCliente as lin', function ($join) {
                $join->on('lin.CodigoEmpresa', '=', 'cab.CodigoEmpresa');
                $join->on('lin.EjercicioPedido', '=', 'cab.EjercicioPedido');
                $join->on('lin.SeriePedido', '=', 'cab.SeriePedido');
                $join->on('lin.NumeroPedido', '=', 'cab.NumeroPedido');
            })
            ->where('lin.Estado', '=', 0)
            ->where('lin.CodigoAlmacen', '=', '0')
            ->orderByDesc('cab.FechaPedido')->limit(1)->get();
        if ($query->count() != 0) {
            session(['codigoCliente' => $query[0]->codigoCliente]);
            session(['nombreCliente' => $query[0]->RazonSocial]);
            session(['telefonoCliente' => $query[0]->Telefono]);
            session(['descuento' => $query[0]->descuentoCliente]);
            Session(['codigoComisionista' => $query[0]->CodigoComisionista]);
        }

        return $query;
    }
    public static function obtenerLineaUltimoPedidoPendientePuntoVenta($seriePedido, $numeroPedido, $almacen, $cliente)
    {
        $query = DB::table('LineasPedidoCliente')->select(
            'ImporteBruto',
            '%Descuento as descuento',
            'ImporteNeto',
            'ImporteDescuento',
            'ImporteDescuentoCliente',
            'BaseImponible',
            'BaseIva',
            '%Iva',
            'CuotaIva',
            'TotalIva',
            'ImporteLiquido',
            'CodigoArticulo',
            'DescripcionArticulo',
            'UnidadesPedidas',
            'Precio',
            'ImporteLiquido',
            'Partida'
        )->where('seriePedido', $seriePedido)
            ->where('NumeroPedido', $numeroPedido)
            ->where('CodigoAlmacen', $almacen)
            ->where('CodigodelCliente', $cliente)
            ->get();
        return $query;
    }

    public static function eliminarPedido()
    {
        $array = $_POST['datos']['lineas'];

        $query = DB::table('CabeceraPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroPedido'])->delete();
        // if($query == 1 ){
        $query2 = DB::table('LineasPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroPedido'])->delete();
        //   if($query2 == 1 ) {
        //SI SE HAN PODIDO ELIMINAR AMBOS REGISTROS ELIMINAMOS REGISTRO DE MOVIMIENTOS PENDIENTES
        $query3 = DB::table('MovimientoPendientes')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Ejercicio', '=', date('Y'))
            ->where('Serie', '=', $_POST['seriePedido'])
            ->where('Documento', '=', $_POST['numeroPedido'])
            ->where('CodigoAlmacen', '0')->delete();
        //     if($query3 == 1 ){
        foreach ($array as $linea) {
            if ($linea['fechaCaducidad'] == 0) {
                $partida = "";
            } else {
                $partida = $linea['partida'];
            }
            $query4 = self::actualizarUnidadesAcumuladoPendientes($partida, $linea['codigo'], 0, $linea['cantidad']);
            //if($query4 == 1 ) $eliminado = "OK";
        }

        $numero = $_POST['numeroPedido'];
        $serie = $_POST['seriePedido'];
        $nombreContador = 'pedidos_cli';

        // self::contadorMenos($serie, $numero, $nombreContador);

        $eliminado = "OK";
        /*}else{
                    $eliminado = "No ha sido posible eliminarlos movimientos";
                }
            }
            else{
                $eliminado = "No ha sido posible eliminar las líneas del pedido";
            }
        }else{
            $eliminado = "No ha sido posible eliminar la cabecera del pedido";
        }*/
        return $eliminado;
    }

    /**
     * método para comprobar si existe ya una serie y si no existe crearla
     */
    public static function comprobarSiExisteContador($descripcion, $serie)
    {
        $query = DB::table('lsysContadores')->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysNombreContador', '=', $descripcion)
            ->where('sysNumeroSerie', '=', $serie)->where('sysEjercicio', '=', date('Y'))->get();
        if ($query->count() == 0) {
            $contador = DB::table('lsysContadores');
            $contador->insert([
                'sysAplicacion' => 'GES',
                'sysGrupo' => session('codigoEmpresa'),
                'sysEjercicio' => date('Y'),
                'sysNombreContador' => $descripcion,
                'sysNumeroSerie' => $serie,
                'sysContadorValor' => 1,
            ]);
        }
    }

    /**
     * método para comprobar que ya exista pedido y se le coja el nuevo orden
     */
    public static function ultimoOrdenPedido()
    {
        $query = DB::table('LineasPedidoCliente')
            ->select('Orden')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $_POST['serie'])
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $_POST['pedido'])
            ->max('Orden');
        return $query + 5;
    }

    public static function obtenerUltimoNumeroOrden($numero, $serie)
    {
        $query = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $serie)
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $numero)
            ->max('Orden');
        return $query;
    }

    public static function comprobarExisteCabeceraPedido($numero, $serie, $codigoCliente)
    {
        $existe = false;
        $query = DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $serie)
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $numero)->get();
        if ($query->count() != 0) {
            $query2 = DB::table('CabeceraPedidoCliente')
            ->where('CodigoCliente', '=', $codigoCliente)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $serie)
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $numero)->get();

            if ($query2->count() != 0) {
                $existe =  true;
            }else{
                $contador = self::obtenerContadorNuevo($serie);
                return [$existe, $contador];
            }
        }
        return [$existe, $numero];
    }

    public static function comprobarExisteArticuloEnPedido($numero, $serie, $codigoArticulo, $orden)
    {
        $existe = false;
        $query = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $serie)
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('NumeroPedido', '=', $numero)
            ->where('Orden', '=', $orden)
            ->where('CodigoArticulo', '=', $codigoArticulo)->get();

        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function comprobarExisteArticuloEnMovimientoPendiente($numero, $serie, $codigoArticulo)
    {
        $existe = false;
        $query = DB::table('MovimientoPendientes')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Serie', '=', $serie)
            ->where('Ejercicio', '=', date('Y'))
            ->where('Documento', '=', $numero)
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Periodo', '=', date('m'))
            ->where('CodigoAlmacen', '=','0')
            ->get();
        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function comprobarExisteArticuloEnAcumuladoPendientes($codigoArticulo, $partida)
    {
        $existe = false;
        $query = DB::table('AcumuladoPendientes')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '0')
            ->get();

        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function comprobarExisteArticuloEnAcumuladoStock($codigoArticulo, $partida, $periodo)
    {
        $existe = false;
        $query = DB::table('AcumuladoStock')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '0')
            ->where('Periodo', $periodo)
            ->where('Ejercicio', date('Y'))
            ->get();

        if ($query->count() != 0) {
            $existe =  true;
        }
        return $existe;
    }

    public static function obtenerCantidadArticuloEnAcumuladoPendientes($codigoArticulo, $partida)
    {

        $query = DB::table('AcumuladoPendientes')->select('PendienteServir', 'StockReservadoPedidos_')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '0')
            ->get();

        return $query;
    }

    public static function actualizarCantidadAcumuladoPendientes($codigoArticulo, $partida, $pendienteServir, $stockReservado, $cantidad)
    {

        $query = DB::table('AcumuladoPendientes')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '0')
            ->update([
                'PendienteServir' => $pendienteServir + $cantidad,
                "StockReservadoPedidos_" => $stockReservado + $cantidad,
                "PendienteServirTipo_" => $pendienteServir + $cantidad,
                "StockReservadoPedidosTipo_" => $stockReservado + $cantidad
            ]);
        return $query;
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
            ->where('CodigoAlmacen', '=', '0')
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
    public static function actualizarCantidadLineasCabeceraPedido($seriePedido, $numeroPedido)
    {
        $cantidadActual = self::obtenerCantidadLineasCabeceraPedido($seriePedido, $numeroPedido);
        DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $seriePedido)
            ->where('NumeroPedido', '=', $numeroPedido)
            ->where('EjercicioPedido', '=', date('Y'))
            ->update(['NumeroLineas' => $cantidadActual[0]->NumeroLineas + 1]);
    }

    public static function actualizarCantidadLineasCabeceraAlbaran($serieAlbaran, $numeroAlbaran)
    {
        $cantidadActual = self::obtenerCantidadLineasCabeceraAlbaran($serieAlbaran, $numeroAlbaran);
        if (empty($cantidadActual)) $lineas = 0;
        else $lineas = $cantidadActual[0]->NumeroLineas;
        DB::table('CabeceraAlbaranCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieAlbaran', '=', $serieAlbaran)
            ->where('NumeroAlbaran', '=', $numeroAlbaran)
            ->where('EjercicioPedido', '=', date('Y'))
            ->update(['NumeroLineas' => $lineas + 1]);
    }
    public static function obtenerCantidadLineasCabeceraPedido($seriePedido, $numeroPedido)
    {
        $query = DB::table('cabecerapedidocliente')->select('NumeroLineas', 'ImporteLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $seriePedido)
            ->where('NumeroPedido', '=', $numeroPedido)
            ->where('EjercicioPedido', '=', date('Y'))->get();
        return $query;
    }
    public static function obtenerCantidadLineasCabeceraAlbaran($serieAlbaran, $numeroAlbaran)
    {
        $query = DB::table('CabeceraAlbaranCliente')->select('NumeroLineas', 'ImporteLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SerieAlbaran', '=', $serieAlbaran)
            ->where('NumeroAlbaran', '=', $numeroAlbaran)
            ->where('EjercicioAlbaran', '=', date('Y'))->get();
        return $query;
    }

    /**
     * MÉTODO CON EL QUE ACTUALIZAREMOS EL STATUS PARA QUE SE REALICE LA IMPORTACIÓN
     */
    /*  public static function actualizarStatusTraspasoCabeceraYLinea($serie,$numeroDocumento,$codigoDocumento){
        $query = DB::table('TmpIME_CabeceraDocumento')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioDocumento', '=', date("Y"))
            ->where('SerieDocumento','=',$serie)
            ->where('CodigoDocumentoLc','=',$codigoDocumento)
            ->where('NumeroDocumento','=',$numeroDocumento)
            ->update(['StatusTraspasadoIME'=>"0"]);
        $query = DB::table('TmpIME_LineasDocumento')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioDocumento', '=', date("Y"))
            ->where('SerieDocumento','=',$serie)
            ->where('CodigoDocumentoLc','=',$codigoDocumento)
            ->where('NumeroDocumento','=',$numeroDocumento)
            ->update(['StatusTraspasadoIME'=>"0"]);
    }

    public static function actualizarStatusTraspasoCabeceraYLineaAlbaranFactura($serie,$numeroFactura,$numeroAlbaran){
        $query = DB::table('TmpIME_AlbaranesFacturas')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioFactura', '=', date("Y"))
            ->where('EjercicioAlbaran', '=', date("Y"))
            ->where('SerieFactura','=',$serie)
            ->where('SerieAlbaran','=',$serie)
            ->where('NumeroFactura','=',$numeroFactura)
            ->where('NumeroAlbaran','=',$numeroAlbaran)
            ->update(['StatusTraspasadoIME'=>"0"]);

    }*/

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
                'UnidadesPendientes'=> $nuevaCantidad,
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
        $margenBeneficio = $datos[0]->beneficio;
        $precioCoste = $datos[0]->precioCoste;
    
        if($importeNetoLineas == 0){
            $porMargenBeneficio = 0;
        }else{            
            $porMargenBeneficio = ($margenBeneficio * 100)/ $importeNetoLineas;
        }

        //actualizamos importe liquido cabecera Y DEMÁS DATOS CABECERA
        $serie = DB::table('CabeceraPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $serie)
            ->where('NumeroPedido', '=', $numeroDocumento)
            ->update([

                "ImporteBruto" => $importeBruto, 
                "ImporteBrutoPendiente" => $importeBruto,
                "ImporteNetoLineas" => $importeNetoLineas,
                "ImporteNetoLineasPendiente" => $importeNetoLineas,
                //"ImporteDescuento" => $importeDtoLineas, 
                "ImporteDescuentoLineas" => $importeDtoLineas, 
                //"%Descuento" => $descuentoCliente,                 
                "ImporteParcial" => $importeNetoLineas - $importeDtoLineas ,
                "ImporteParcialPendiente" => $importeNetoLineas - $importeDtoLineas,
                "importeProntoPago" => $importeProntoPago,                
                "BaseImponible" => $baseImponible,
                "BaseImponiblePendiente" => $importeNetoLineas - $importeDtoLineas,
                "ImporteLiquido" => $importeLiquido, 
                "ImporteFactura" => $importeLiquido,
                "TotalIva" => $TotalCuotaIva, 
                "TotalCuotaIva" => $cuotaIva,
                "TotalCuotaRecargo" => $cuotaRecargo,
                "MargenBeneficio" => $margenBeneficio,
                "PorMargenBeneficio"=> $porMargenBeneficio,                                
            ]);
    }

    public static function obtenerDatosPedidoParaActualizarCabecera($serie, $numeroDocumento)
    {
        $query = DB::table('LineasPedidoCliente')->selectRaw('sum(ImporteBruto) as sumaImporte, sum(ImporteDescuento) as descuento, sum(CuotaRecargo) as recargo, sum(CuotaIva) as sumaIva, 
        sum(ImporteNeto) as importeNeto, sum(ImporteProntoPago) as importeProntoPago, sum(BaseImponible) as baseImponible, sum(BaseIva) as baseIva, Sum(TotalIva) as totalIva, Sum(MargenBeneficio) as beneficio, Sum(PrecioCoste) as precioCoste ')
            ->where('NumeroPedido', $numeroDocumento)
            ->where('SeriePedido', $serie)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))->get();

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
            ->where('CodigoAlmacen', '0')
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
            ->where('CodigoAlmacen','0')
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
            ->where('CodigoAlmacen', '0')
            ->where('CodigoArticulo', $codigoProducto)->get();
        return $query;
    }

    public static function obtenerUnidadesTotalesAcumuladoPendientes($codigoProducto, $partida)
    {
        
        $query = DB::table('AcumuladoPendientes')->select('PendienteServir', 'StockReservadoPedidos_')
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('CodigoAlmacen','0')
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
            ->where('CodigoAlmacen', '=', '0')
            ->where('Periodo', $periodo)->get();

        $unidadEntrada = $query[0]->UnidadEntrada;
        $unidadSalida = $query[0]->UnidadSalida;
        $unidadSaldoAcumulado = $query[0]->UnidadSaldo;
        $unidadSaldo = $unidadSaldoAcumulado - $cantidad; //es el resultado de la resta de la unidad entrada menos la unidad salida
        DB::table('AcumuladoStock')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoArticulo', '=', $codigoArticulo)
            ->where('Partida', '=', $partida)
            ->where('CodigoAlmacen', '=', '0')
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

    public static function actualizarDescuentoProducto()
    {
        if(!isset($_POST['iva'])){
            $iva = 0.21;
        }else{
            $iva = $_POST['iva'];
        }

        $recargoPorcentaje = $_POST['recargo'] / 100; //porcentaje recargo a producto
        $importeTotal = ($_POST['precio'] * $_POST['nuevaCantidad']) * ($_POST['iva'] ); 
        $importeBruto = $_POST['precio2'] * $_POST['nuevaCantidad']; // 10


        $importeDescuento = $importeBruto  * ($_POST['dtoArticulo'] / 100); // 0.5
        $importeNeto = $importeBruto - $importeDescuento;
        $importeDescuentoCliente = $importeNeto * (0);
        $prontoPago = $importeNeto * $_POST['protopago'];
        $baseImponible = ($importeNeto - $importeDescuentoCliente) - $prontoPago;
        $cuotaRecargo = $baseImponible * $recargoPorcentaje;
        $cuotaIva = $baseImponible * $iva;
        $importeLiquido = $baseImponible + $cuotaIva + $cuotaRecargo;


        $linea = DB::table('LineasPedidoCliente')
        ->select('UnidadesPedidas', 'Partida', 'PrecioCoste')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroDocumento'])
            ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
            ->where('Orden', '=', $_POST['orden'])
            ->get();

        $precioCoste = $linea[0]->PrecioCoste;
        $cantidadAnterior = $linea[0]->UnidadesPedidas;    
        $partida = $linea[0]->Partida;    
        $margenBeneficio= ($_POST['precio2'] - $precioCoste) * $_POST['nuevaCantidad'] ;
        if($importeNeto == 0){
            $porMargenBeneficio = 0;
        }else{            
            $porMargenBeneficio = ($margenBeneficio * 100)/ $importeNeto;
        }

        $query = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date("Y"))
            ->where('SeriePedido', '=', $_POST['seriePedido'])
            ->where('NumeroPedido', '=', $_POST['numeroDocumento'])
            ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
            ->where('Orden', '=', $_POST['orden'])
            ->update([
                "%Recargo"=> $_POST['recargo'],
                "CuotaRecargo"=> $cuotaRecargo,
                'UnidadesPedidas' => $_POST['nuevaCantidad'],
                'UnidadesPendientes' => $_POST['nuevaCantidad'],
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
                "ImporteBrutoPendiente" => $importeBruto,
                "ImporteProntoPago" => $prontoPago,

                "UnidadesPendAnterior"=>$_POST['nuevaCantidad'],                
                "ImporteCoste"=>$precioCoste * $_POST['nuevaCantidad'] ,
                "ImporteParcial"=> $importeBruto,
                "ImporteParcialPendiente"=>$importeBruto,
                "BaseImponiblePendiente"=>$baseImponible,                
                "MargenBeneficio"=>$margenBeneficio,
                "PorMargenBeneficio"=>$porMargenBeneficio,
                
            ]);

        self::actualizarCabeceraPedido($_POST['seriePedido'], $_POST['numeroDocumento'], $cuotaIva, $importeDescuentoCliente);
        self::actualizarUnidadesAcumuladoPendientes($partida, $_POST['codigoProducto'], $_POST['nuevaCantidad'], $cantidadAnterior);

        $updateMovimiento = DB::table('MovimientoPendientes')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('Ejercicio', '=', date("Y"))
        ->where('Serie', '=', $_POST['seriePedido'])
        ->where('Documento', '=', $_POST['numeroDocumento'])
        ->where('CodigoArticulo', '=', $_POST['codigoProducto'])
        ->where('Periodo', '=', date('m'))
        ->where('CodigoAlmacen', '0')
        ->update(['Unidades' => $_POST['nuevaCantidad'], 'Unidades2_' => $_POST['nuevaCantidad'], 'Importe' => $_POST['precio2'] * $_POST['nuevaCantidad']]);

        return $porMargenBeneficio;
    }

    public static function eliminarLineaPedido()
    {
        //para eliminar pedido obtenemos información de la linea como las unidades, el importe total así como el guid de posicion de la linea
        $query = DB::table('LineasPedidoCliente')->select('Unidades2_ as unidades', 'LineasPosicion', 'ImporteLiquido', 'ImporteBruto', 'BaseImponible', 'TotalIva', 'ImporteNeto', 'Partida')->where('CodigoArticulo', $_POST['codigoArticulo'])
            ->where('CodigoEmpresa', session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))
            ->where('Orden', '=', $_POST['orden'])
            ->where('SeriePedido', $_POST['seriePedido'])
            ->where('NumeroPedido', $_POST['numeroPedido'])->get();
        //
        //return $query;

        $lineasPosicion = $query[0]->LineasPosicion;
        $cantidadLinea = $query[0]->unidades;
        $importeLiquidoLinea = $query[0]->ImporteLiquido;
        $importeBrutoLinea = $query[0]->ImporteBruto;
        $baseImponiblelinea = $query[0]->BaseImponible;
        $totalIvaLinea = $query[0]->TotalIva;
        $importeNetoLinea = $query[0]->ImporteNeto;
        $partida = $query[0]->Partida;

        //borramos la línea que tiene asociado el guid
        $query = DB::table('LineasPedidoCliente')->where('LineasPosicion', $lineasPosicion)->delete();

        //obtenemos información relativa de la la cabecera para actualizarla cada vez que se elimine una línea
        $query = DB::table('CabeceraPedidoCliente')->select('NumeroLineas', 'ImporteBruto', '%Descuento as descuento', 'ImporteNetoLineas', 'ImporteParcial', 'BaseImponible', 'ImporteLiquido', 'TotalIva')
            ->where('CodigoEmpresa', \session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))
            ->where('SeriePedido', $_POST['seriePedido'])
            ->where('NumeroPedido', $_POST['numeroPedido'])->get();

        //$importeDescuentoLineas = $_POST['datos']['total'] * $_POST['datos']['descuentoLineas'];
        $importeBruto = $query[0]->ImporteBruto - $importeBrutoLinea;
        $importeNeto = $query[0]->ImporteNetoLineas   - $importeNetoLinea;
        $importeDescuento = $importeNeto * session('descuento') / 100;
        $baseImponible = $query[0]->BaseImponible - $baseImponiblelinea;
        //$desgloseIva = $query[0]->ImporteLiquido / 1.21;
        $totalIva = $query[0]->TotalIva - $totalIvaLinea;
        $importeLiquido = $query[0]->ImporteLiquido - $importeLiquidoLinea;
        $orden = $query[0]->NumeroLineas - 1; 

        $query = DB::table('CabeceraPedidoCliente')->where('CodigoEmpresa', \session('codigoEmpresa'))
            ->where('EjercicioPedido', date('Y'))
            ->where('SeriePedido', $_POST['seriePedido'])
            ->where('NumeroPedido', $_POST['numeroPedido'])
            ->update([
                "ImporteBruto" => $importeBruto,
                "ImporteBrutoPendiente" => $importeBruto, //realmente es con cálculo de importe pendiente
                "ImporteNetoLineas" => $importeNeto,
                "ImporteNetoLineasPendiente" => $importeNeto, //realmente es con cálculo de importe pendiente
                "ImporteDescuento" => $importeDescuento,
                "ImporteParcial" => $importeNeto - $importeDescuento,
                "ImporteParcialPendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                "BaseImponible" => $baseImponible,
                "BaseImponiblePendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                "TotalCuotaIva" => $totalIva,
                "TotalIva" => $totalIva,
                "ImporteLiquido" => $importeLiquido,
                "ImporteFactura" => $importeLiquido,
                "NumeroLineas"=> $orden,
            ]);

        if ($query == 1) {
            $query2 = self::actualizarUnidadesAcumuladoPendientes($partida, $_POST['codigoArticulo'], 0, $cantidadLinea);
            if ($query2 == 1) {
                $query3 = DB::table('movimientopendientes')->where('MovOrigen', $lineasPosicion)->delete();
                if ($query3 == 1) {
                    $eliminado = "OK";
                } else {
                    $eliminado = "Error al borrar movimientos pendientes";
                }
            } else {
                $eliminado = "Error al realizar movimientos stock";
            }
        } else {
        }
        return $eliminado;
    }

    public static function comprobarEstadoPedido()
    {
        $query = DB::table('CabeceraPedidoCliente')->select('Estado')
            ->where('SeriePedido', $_POST['seriePedido'])
            ->where('NumeroPedido', $_POST['numeroPedido'])
            ->where('EjercicioPedido', date('Y'))
            ->where('CodigoEmpresa', \session('codigoEmpresa'))->get();
        $estado = $query[0]->Estado;
        return $estado;
    }


    //MÉTODO COLA PEDIDOS
    public static function obtenerPedidosColaEnvio()
    {
    }

    public static function seriePedido()
    {

        $query = DB::table('LsysContadores')
        ->where('sysNombreContador', '=', 'pedidos_cli')
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
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=',0)
            ->where('sysNumeroSerie', '=', $_POST['serie'])
            ->get();

        $query2 = DB::table('lsysContadores')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNumeroSerie', '=', $_POST['serie'])
            ->update(['sysContadorValor' => $query[0]->sysContadorValor + 1]);

        return $query[0]->sysContadorValor + 1;
    }

    public static function contadorMenos($serie, $numero, $nombreContador)
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
        $query = DB::table('CabeceraPedidoCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaPedido',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioPedido',
            'SeriePedido',
            'NumeroPedido',
            'RazonSocial'
        )
            ->where('EjercicioPedido', $_POST['ejercicio'])
            ->where('SeriePedido', $_POST['serie'])
            ->where('NumeroPedido', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        $query2 = DB::table('LineasPedidoCliente')->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesServidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas'
        )
            ->where('EjercicioPedido', $_POST['ejercicio'])
            ->where('SeriePedido', $_POST['serie'])
            ->where('NumeroPedido', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        $datos = [
            "cabecera" => $query,
            "lineas" => $query2
        ];

        return $datos;
    }


    public static function cabeceraPedido($ejercicio, $serie, $numero)
    {

        $query = DB::table('CabeceraPedidoCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaPedido',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioPedido',
            'SeriePedido',
            'NumeroPedido',
            'RazonSocial'
        )
            ->where('EjercicioPedido', $ejercicio)
            ->where('SeriePedido', $serie)
            ->where('NumeroPedido', $numero)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        return $query[0];
    }

    public static function lineasPedido($ejercicio, $serie, $numero)
    {
        $query = DB::table('LineasPedidoCliente')
        ->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesServidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas',
            'ImporteNeto'
        )
            ->where('EjercicioPedido', $ejercicio)
            ->where('SeriePedido', $serie)
            ->where('NumeroPedido', $numero)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();

        return $query;
    }

    public static function correoPedido()
    {

        $query = DB::table('CabeceraPedidoCliente')->select(
            'ImporteLiquido',
            'BaseImponible',
            'TotalIva',
            'FechaPedido',
            'Nombre',
            'CIFDNI',
            'Domicilio',
            'CodigoPostal',
            'Municipio',
            'CodigoCliente',
            'EjercicioPedido',
            'SeriePedido',
            'NumeroPedido',
            'RazonSocial'
        )
            ->where('EjercicioPedido', $_POST['ejercicio'])
            ->where('SeriePedido', $_POST['serie'])
            ->where('NumeroPedido', $_POST['numero'])
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->get();
        $query2 = DB::table('LineasPedidoCliente')->select(
            'DescripcionArticulo',
            'CodigoArticulo',
            'Precio',
            'UnidadesServidas',
            '%Descuento as descuento',
            '%Iva as iva',
            'BaseImponible',
            'CuotaIva',
            'ImporteLiquido',
            'Partida',
            'UnidadesPedidas'
        )
            ->where('EjercicioPedido', $_POST['ejercicio'])
            ->where('SeriePedido', $_POST['serie'])
            ->where('NumeroPedido', $_POST['numero'])
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
            Mail::to($correo)->send(new EnviarCorreo($datos));
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

    public static function repetirPedido()
    {

        //foreach($_POST['lineasPosicion'] as $linea){

        //return $_POST['lineasPosicion'][0];

        $linea = $_POST['lineasPosicion'][0];

        $consultar = DB::table('LineasAlbaranCliente')
            ->where('LineasPosicion', '=', $linea['guid'])
            ->get();

        if (!$consultar) {
            return 'error linea guid erronea';
        };

        $cabecera = DB::table('CabeceraAlbaranCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioAlbaran', '=', $consultar[0]->EjercicioAlbaran)
            ->where('SerieAlbaran', '=', $consultar[0]->SerieAlbaran)
            ->where('NumeroAlbaran', '=', $consultar[0]->NumeroAlbaran)
            ->get();

        if (!$cabecera) {
            return 'error cabecera no creada';
        };

        $contador = DB::table('LsysContadores')
            ->select('sysContadorValor')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNumeroSerie', '=', '')
            ->get();

        if (!$contador) {
            return 'error contador no encontrado crea serie  para que el contador funcione';
        };
        $contadorAct = self::contadorMenos('', $contador[0]->sysContadorValor + 1, 'pedidos_cli');

        $cliente = DB::table('Clientes')
        ->select('EMail1', 'IndicadorIva')
        ->where('CodigoCliente', '=', $cabecera[0]->CodigoCliente)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        $domicilios = DB::table('Domicilios')
        ->select("NumeroDomicilio", "Nombre", DB::raw("CONCAT(Domicilios.Domicilio,' ',Domicilios.Numero1,' ',Domicilios.Numero2,' ',Domicilios.Escalera,' ',Domicilios.Puerta,' ',Domicilios.Letra,',',Domicilios.Municipio,', ',Domicilios.Provincia) as Direccion"))
        ->where('CodigoCliente', '=', $cabecera[0]->CodigoCliente)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('TipoDomicilio', '=', 'E')
        ->get();

        $cabeceraPedido = DB::table('CabeceraPedidoCliente')
            ->insert([
                "CodigoEmpresa" => session('codigoEmpresa'),
                "CodigoCliente" => $cabecera[0]->CodigoCliente,
                "EjercicioPedido" => date("Y"),
                "SeriePedido" => '',
                "NumeroPedido" => $contador[0]->sysContadorValor + 1,
                "FechaPedido" => date("Y-m-d") . " " . date("H:i:s"),
                "CIFDNI" => $cabecera[0]->CifDni,
                "CIFEuropeo" => $cabecera[0]->CifEuropeo,
                "RazonSocial" => substr($cabecera[0]->RazonSocial, 0, 35),
                "Nombre" => substr($cabecera[0]->RazonSocial, 0, 35),
                "Domicilio" => $cabecera[0]->Domicilio,
                "CodigoPostal" => $cabecera[0]->CodigoPostal,
                "CodigoMunicipio" => $cabecera[0]->CodigoMunicipio,
                "Municipio" => $cabecera[0]->Municipio,
                "CodigoProvincia" => $cabecera[0]->CodigoProvincia,
                "Provincia" => $cabecera[0]->Provincia,
                "CodigoNacion" => $cabecera[0]->CodigoNacion,
                "Nacion" => $cabecera[0]->Nacion,
                "CodigoCondiciones" => $cabecera[0]->CodigoCondiciones,
                "NumeroPlazos" => $cabecera[0]->NumeroPlazos,
                "CodigoContable" => $cabecera[0]->CodigoContable,
                "IBAN" => $cabecera[0]->IBAN,
                "ReservarStock_" => -1,
                "CodigoComisionista" => session('codigoComisionista'),
                "ComercialAsignadoLc"=>session('codigoComisionista'),
                "StatusAprobado" => 0,
                "CodigoJefeVenta_" => $cabecera[0]->CodigoJefeVenta_,
                "DiasPrimerPlazo" => $cabecera[0]->DiasPrimerPlazo,
                "DiasFijos1" => $cabecera[0]->DiasFijos1,
                "DiasFijos2" => $cabecera[0]->DiasFijos2,
                "DiasFijos3" => $cabecera[0]->DiasFijos3,
                "DiasRetroceso" => $cabecera[0]->DiasRetroceso,
                "FormadePago" => $cabecera[0]->FormadePago,
                "MesesComerciales" => $cabecera[0]->MesesComerciales,
                "ControlarFestivos" => $cabecera[0]->ControlarFestivos,
                "RemesaHabitual" => $cabecera[0]->RemesaHabitual,
                "CodigoTipoEfecto" => $cabecera[0]->CodigoTipoEfecto,
                "CodigoBanco" => $cabecera[0]->CodigoBanco,
                "CodigoAgencia" => $cabecera[0]->CodigoAgencia,
                "DC" => $cabecera[0]->DC,
                "CCC" => $cabecera[0]->CCC,
                "CopiasAlbaran" => $cabecera[0]->CopiasAlbaran,
                "CopiasFactura" => $cabecera[0]->CopiasFactura,
                "AgruparAlbaranes" => -1,
                "AlbaranValorado"=> -1,

                "IndicadorIva" => $cliente[0]->IndicadorIva,
                "TipoPortesEnvios" => $cabecera[0]->TipoPortesEnvios,
                "CodigoTransaccion" => $cabecera[0]->CodigoTransaccion,
                "AgruparAlbaranes" => $cabecera[0]->AgruparAlbaranes,
                "MantenerCambio_" => $cabecera[0]->MantenerCambio_,
                //"FactorCambio" => $cabecera[0]->FactorCambio,
                "ReferenciaMandato" => $cabecera[0]->ReferenciaMandato,

                //"%Descuento"=>$cliente[0]['%Descuento'],
                // "ImporteBruto"=>$importeBruto,
                // "ImporteBrutoPendiente"=>$importeBruto, //realmente es con cálculo de importe pendiente
                // "ImporteNetoLineas"=>$importeNeto,
                // "ImporteNetoLineasPendiente"=>$importeNeto, //realmente es con cálculo de importe pendiente
                //"ImporteDescuento" =>$_POST['datos']['total'] * $cliente[0]['%Descuento'],
                // "ImporteParcial"=>$importeNeto - $importeDescuento,
                // "ImporteParcialPendiente"=>$importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                // "BaseImponible"=>$baseImponible,
                // "BaseImponiblePendiente"=>$importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                // "TotalCuotaIva"=>$totalIva,
                // "TotalIva"=>$totalIva,
                // "ImporteLiquido"=>$baseImponible + $totalIva,
                // "ImporteFactura"=>$baseImponible + $totalIva                        
            ]);

        $importeDescuentoLineas = 0;
        $importeBruto = 0;
        $importeNeto = 0;
        $importeDescuento = 0;
        $baseImponible = 0;
        //$desgloseIva= 0;
        $totalIva = 0;
        $totalRecargo = 0;
        $orden = 5;

        $codigoCliente = '';
        $codigoComisionista = '';
        $codigoJefeVenta = '';
        $partida = '';
        $fechaCaducidad = '';


        if ($cabeceraPedido != false) {


            foreach ($_POST['lineasPosicion'] as $lineas) {

                $guidArticulo = self::codigoGuid();

                if ($lineas['origen'] == 1) {
                    $lineo = DB::table('LineasAlbaranCliente')
                        ->select('*', '%Iva AS Iva', '%Descuento AS Descuento', '%Comision AS Comision')
                        ->where('LineasPosicion', '=', $lineas['guid'])
                        ->get();
                    
                    $tienePartida = DB::table('Articulos')
                    ->select('TratamientoPartidas', 'PrecioCompra','%Margen as Margen')
                    ->where('CodigoEmpresa',\session('codigoEmpresa'))
                    ->where('CodigoArticulo',$lineo[0]->CodigoArticulo)->get();                    
    
                    if($tienePartida[0]->TratamientoPartidas == -1){
                        $partidas = DB::table('AcumuladoStock')->select('Partida','FechaCaducidad')
                        ->where('Periodo',99)
                        ->where('CodigoAlmacen','0')
                        ->where('CodigoEmpresa',\session('codigoEmpresa'))
                        ->where('Ejercicio',date('Y'))
                        ->where('UnidadSaldo','>',0)
                        ->where('FechaCaducidad','<>',null)
                        ->where('CodigoArticulo',$lineo[0]->CodigoArticulo)
                        ->where('UnidadSaldo', '>=', $lineas['unidades'])
                        ->orderByDesc('FechaCaducidad')->get();   
                        
                        if (count($partidas) > 0) {
                            $partida = $partidas[0]->Partida;
                            $fechaCaducidad = $partidas[0]->FechaCaducidad;
                        }else{
                            $partida = '';
                            $fechaCaducidad = null;
                        }
                        
                    }

                    

                    $unidades = $lineas['unidades'];
                    $descuento = $lineas['descuento'];
                    $precio = $lineas['precio'];
                    $recargo = $lineas['recargo'];

                    $bruto = $precio * $unidades;

                    $precioDescuento =  $precio * ($descuento / 100);                                        
                    $totalDescuento = $bruto * $precioDescuento / 100;
                    $neto = $bruto - $totalDescuento;
                    $desCliente = $neto * $descuento / 100;
                    $baseImpo = $neto - $desCliente;
                    $baseRecargo = $baseImpo * $recargo;
                    $tIva = $baseImpo * $lineo[0]->Iva / 100;
                    $subtotal = $baseImpo + $tIva;
                    //$liquido = $baseImpo + $tIva;

                    $importeDescuentoLineas += $desCliente;
                    $importeBruto += $bruto;
                    $importeNeto += $neto;
                    $importeDescuento += $desCliente;
                    $baseImponible += $baseImpo;
                    //$desgloseIva += ($subtotal / 1.21);
                    $totalRecargo += $baseRecargo;
                    $totalIva += $tIva;  
                    
                    $precioCoste = $tienePartida[0]->PrecioCompra;
                    $importeCoste = $precioCoste * $unidades;
                    $margen = $tienePartida[0]->Margen;
                    $margenDeBeneficio = ($precio - $importeDescuento - $tienePartida[0]->PrecioCompra) * $unidades;   
                    if($tienePartida[0]->PrecioCompra == 0){
                        $porMargenBeneficio = 0;
                    }else{    
                        //$precio2 = $precio - $precioDescuento;
                        $porMargenBeneficio = ($margenDeBeneficio * 100) / $importeNeto;
                    }                                                     
                    //$porMargenBeneficio = ((($precio - $precioDescuento) - $tienePartida[0]->PrecioCompra) / $tienePartida[0]->PrecioCompra) * 100;                    
                    

                    $codigoCliente = $cabecera[0]->CodigoCliente;
                    $codigoComisionista = $lineo[0]->CodigoComisionista;
                    $codigoJefeVenta = $lineo[0]->CodigoJefeVenta_;

                    $cont = $contador[0]->sysContadorValor + 1;


                    $lineaPedido = DB::table('LineasPedidoCliente')
                        ->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "CodigoDelCliente" => $lineo[0]->CodigoArticulo,
                            "EjercicioPedido" => date("Y"),
                            'CodigoAlmacen' => '0',
                            "SeriePedido" => '',
                            "NumeroPedido" => $cont,
                            'LineasPosicion' => $guidArticulo,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            "DescripcionArticulo" => $lineo[0]->DescripcionArticulo,
                            "CodigoAlmacenAnterior"=>'0',
                            "CodigoFamilia" => $lineo[0]->CodigoFamilia,
                            "CodigoSubFamilia" => $lineo[0]->CodigoSubfamilia,
                            "TipoArticulo" => $lineo[0]->TipoArticulo,
                            //"FechaEntrega"=>session('fechaEntrega'). "00:00:00.000",
                            "ReservarStock_" => -1,
                            "Estado" => 0,
                            "GrupoIva" => $lineo[0]->GrupoIva,
                            "CodigoIva" => $lineo[0]->CodigoIva,
                            "%Iva" => $lineo[0]->Iva,
                            "UnidadesPendientesFabricar" => $unidades,
                            "UnidadesPedidas" => $unidades,
                            "UnidadesPendientes" => $unidades,
                            "UnidadesServidas" => 0,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "CodigoComisionista" => session('codigoComisionista'),
                            "%Descuento" => $descuento,
                            "Orden" => $orden,
                            "CodigoJefeVenta_" => $lineo[0]->CodigoJefeVenta_,
                            "%Comision" => $lineo[0]->Comision,

                            "ImporteBruto" => $bruto,
                            "ImporteBrutoPendiente" => $bruto,                            
                            "ImporteDescuento" => $desCliente,
                            "ImporteNeto" => $neto,
                            "ImporteNetoPendiente" => $neto,
                            "ImporteDescuentoCliente" => $desCliente,
                            "BaseImponible" =>$baseImpo,
                            "BaseIva" =>$baseImpo,
                            "CuotaIva" =>$tIva,
                            "%Recargo" =>$lineas['recargo'],
                            "CuotaRecargo"=>$baseRecargo,
                            "TotalIva" =>$tIva+$baseRecargo,
                            "ImporteLiquido"=>$subtotal+$baseRecargo,
                        
                            "Partida" => "$partida",
                            "FechaCaduca" => "$fechaCaducidad",

                            "UnidadesPendAnterior"=>$unidades,
                            "PrecioCoste"=>$precioCoste ,
                            "ImporteCoste"=>$importeCoste ,
                            "ImporteParcial"=> $bruto,
                            "ImporteParcialPendiente"=>$bruto,
                            "BaseImponiblePendiente"=>$baseImpo,
                            "PorMargenBeneficio"=>$porMargenBeneficio,
                            "MargenBeneficio"=>$margenDeBeneficio,
                            //"%Margen"=>$margen,

                            "CodigoTransaccion"=>1,
                            "CodigoDefinicion_"=>$lineo[0]->CodigoDefinicion_,
                        ]);

                    $orden = $orden + 5;

                    $existePedidoEnMovimientoPendientes = self::comprobarExisteArticuloEnMovimientoPendiente($cont, '', $lineo[0]->CodigoArticulo);

                    if ($existePedidoEnMovimientoPendientes == false) {
                        //insercción
                        $lineaPedido = DB::table('MovimientoPendientes');
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "Ejercicio" => date('Y'),
                            "Periodo" => date('m'),
                            "Serie" => '',
                            "Documento" => $cont,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Unidades" => $unidades,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "Importe"=> $bruto,
                            "Comentario" =>"PedidoVenta: " . date('Y')."/".''."/".$cont,
                            "CodigoCliente"=> $cabecera[0]->CodigoCliente,
                            "StatusAcumulado"=> -1,
                            "OrigenMovimiento"=> "C",
                            "MovOrigen"=>$guidArticulo,
                            "EmpresaOrigen"=>session('codigoEmpresa'),
                            "EjercicioDocumento"=> date('Y'),
                            "ReservarStock_"=> -1
                        ]);
                    }else{
                        $lineaPedido = DB::table('MovimientoPendientes');
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "Ejercicio"=> date('Y'),
                            "Periodo"=>date('m'),
                            "Serie" => '',
                            "Documento" => $cont,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Unidades" => $unidades,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "Importe"=> $bruto,
                            "Comentario" =>"PedidoVenta: " . date('Y')."/".''."/".$cont,
                            "CodigoCliente"=> $cabecera[0]->CodigoCliente,
                            "StatusAcumulado"=> -1,
                            "OrigenMovimiento"=> "C",
                            "MovOrigen"=>$guidArticulo,
                            "EmpresaOrigen"=>session('codigoEmpresa'),
                            "EjercicioDocumento"=> date('Y'),
                            "ReservarStock_"=> -1
                        ]);
                    }

                    $existeArticuloEnAcumuladoPendientes = self::comprobarExisteArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                    if ($existeArticuloEnAcumuladoPendientes == false) {
                        //si no hay ninguna línea en la que haya el artículo con la partida, el almacen y la empresa se realiza una insercción
                        $lineaPedido = DB::table("AcumuladoPendientes");
                        $lineaPedido->insert([
                        "CodigoEmpresa" => session('codigoEmpresa'),
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Partida" => $partida,
                            "PendienteServir" => $unidades,
                            "PendienteServirTipo_" => $unidades,
                            "StockReservadoPedidos_" => $unidades,
                            "StockReservadoPedidosTipo_" => $unidades
                        ]);
                    } else {
                        //si existe articulo se hace un update
                        //obtenemos cantidad para actualizarla
                        $cantidadAactualizar = self::obtenerCantidadArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                        self::actualizarCantidadAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $unidades);
                    }
                } else {

                    // dd($lineas['guid']);
                    $lineo = DB::table('Articulos')
                        ->select('*', '%Margen as Margen')
                        ->where('IdArticulo', '=', $lineas['guid'])
                        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->get();

                        if($lineo[0]->TratamientoPartidas == -1){
                            $partidas = DB::table('AcumuladoStock')
                            ->select('Partida','FechaCaducidad')
                            ->where('Periodo',99)
                            ->where('CodigoAlmacen','0')
                            ->where('CodigoEmpresa',\session('codigoEmpresa'))
                            ->where('Ejercicio',date('Y'))
                            ->where('UnidadSaldo','>',0)
                            ->where('FechaCaducidad','<>',null)
                            ->where('CodigoArticulo',$lineo[0]->CodigoArticulo)
                            ->where('UnidadSaldo', '>=', $lineas['unidades'])
                            ->orderByDesc('FechaCaducidad')->get();   
                            
                            if (count($partidas) > 0) {
                                # code...
                                $partida = $partidas[0]->Partida;
                                $fechaCaducidad = $partidas[0]->FechaCaducidad;
                            }else{
                                $partida = '';
                                $fechaCaducidad = null;
                            }
                            
                        }

                    $ivaArticulo = 0;
                    $codigoIva = 0;
                    //obtenemos iva de cada articulo
                    if ($lineo[0]->GrupoIva == 1) {
                        $ivaArticulo = 21;
                        $codigoIva = 1;
                    }
                    if ($lineo[0]->GrupoIva == 2) {
                        $ivaArticulo = 10;
                        $codigoIva = 2;
                    }
                    if ($lineo[0]->GrupoIva == 3) {
                        $ivaArticulo = 4;
                        $codigoIva = 3;
                    }
                    

                    $unidades = $lineas['unidades'];
                    $descuento = $lineas['descuento'];
                    $precio = $lineas['precio'];
                    $recargo = $lineas['recargo'];

                    if($lineo[0]->CodigoArticulo === 0){
                        $descripcion = $lineas['descripcion'];
                    }else{
                        $descripcion = $lineo[0]->DescripcionArticulo;
                    }    


                    $bruto = $precio * $unidades;

                    $precioDescuento =  $precio * ($descuento / 100);
                    
                    
                    $totalDescuento = $bruto * $precioDescuento / 100;
                    $neto = $bruto - $totalDescuento;
                    $desCliente = $neto * $descuento / 100;
                    $baseImpo = $neto - $desCliente;
                    $baseRecargo = $baseImpo * $recargo;
                    $tIva = $baseImpo * $ivaArticulo / 100;
                    $subtotal = $baseImpo + $tIva;
                    //$liquido = $baseImpo + $tIva;

                    $precioCoste = $lineo[0]->PrecioCompra;
                    $importeCoste = $precioCoste * $unidades;
                    $margen = $lineo[0]->Margen;
                    $margenDeBeneficio = ($precio  - $lineo[0]->PrecioCompra) * $unidades;
                    if($lineo[0]->PrecioCompra == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        if ($neto == 0) {
                            $precio2 = $precio - $precioDescuento;
                            $porMargenBeneficio = 0;
                        }else{
                            $precio2 = $precio - $precioDescuento;
                            $porMargenBeneficio = ($margenDeBeneficio * 100) / $neto ;
                        }
                    }                                                      
                    //$porMargenBeneficio = ((($precio - $precioDescuento) - $lineo[0]->PrecioCompra) / $lineo[0]->PrecioCompra) * 100;                    
                    

                    $importeDescuentoLineas += $desCliente;
                    $importeBruto += $bruto;
                    $importeNeto += $neto;
                    $importeDescuento += $desCliente;
                    $baseImponible += $baseImpo;
                    $totalRecargo += $baseRecargo;
                    //$desgloseIva += ($subtotal / 1.21);
                    $totalIva += $tIva;

                    $cont = $contador[0]->sysContadorValor + 1;


                    $lineaPedido = DB::table('LineasPedidoCliente')
                        ->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "CodigoDelCliente" => $lineo[0]->CodigoArticulo, 
                            "EjercicioPedido" => date("Y"),
                            'CodigoAlmacen' => '0',
                            "SeriePedido" => '',
                            "NumeroPedido" => $cont,
                            'LineasPosicion' => $guidArticulo,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,                            
                            "DescripcionArticulo" => $descripcion,                            
                            "CodigoAlmacenAnterior"=>'0',
                            "CodigoFamilia" => $lineo[0]->CodigoFamilia,
                            "CodigoSubFamilia" => $lineo[0]->CodigoSubfamilia,
                            "TipoArticulo" => $lineo[0]->TipoArticulo,
                            //"FechaEntrega"=>session('fechaEntrega'). " 00:00:00.000",
                            "ReservarStock_" => -1,
                            "Estado" => 0,
                            "GrupoIva" => $codigoIva,
                            "CodigoIva" => $ivaArticulo,
                            "%Iva" => $ivaArticulo,
                            "UnidadesPendientesFabricar" => $unidades,
                            "UnidadesPedidas" => $unidades,
                            "UnidadesPendientes" => $unidades,
                            "UnidadesServidas" => 0,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "CodigoComisionista" => session('codigoComisionista'),
                            "%Descuento" => $descuento,
                            "Orden" => $orden,
                            "CodigoJefeVenta_" => $codigoJefeVenta,
                            //"%Comision"=>$lineo[0]->Comision,
                            "ImporteBruto" => $bruto,
                            "ImporteBrutoPendiente" => $bruto,
                            "ImporteDescuento" => $desCliente,
                            "ImporteNeto" => $neto,
                            "ImporteNetoPendiente" => $neto,
                            "ImporteDescuentoCliente" => $desCliente,
                            "BaseImponible" => $baseImpo,
                            "BaseImponiblePendiente" => $baseImpo,
                            "BaseIva" => $baseImpo,
                            "CuotaIva" => $tIva,                            
                            "%Recargo" => $lineas['recargo'],
                            "CuotaRecargo" => $baseRecargo,
                            "TotalIva" => $tIva+$baseRecargo,
                            "ImporteLiquido" => $subtotal+$baseRecargo ,

                            "Partida" => "$partida",
                            "FechaCaduca" => "$fechaCaducidad",

                            "UnidadesPendAnterior"=>$unidades,
                            "PrecioCoste"=>$precioCoste ,
                            "ImporteCoste"=>$importeCoste ,
                            "ImporteParcial"=> $bruto,
                            "ImporteParcialPendiente"=>$bruto,
                            "BaseImponiblePendiente"=>$baseImpo,
                            "PorMargenBeneficio"=>$porMargenBeneficio,
                            "MargenBeneficio"=>$margenDeBeneficio,
                            //"%Margen"=>$margen,

                            "CodigoTransaccion"=>1,
                            "CodigoDefinicion_"=>$lineo[0]->CodigoDefinicion_,
                        ]);

                    $orden = $orden + 5;

                    $existePedidoEnMovimientoPendientes = self::comprobarExisteArticuloEnMovimientoPendiente($cont, '',$lineo[0]->CodigoArticulo);

                    if ($existePedidoEnMovimientoPendientes == false) {
                        //insercción
                        $lineaPedido = DB::table('MovimientoPendientes');
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "Ejercicio" => date('Y'),
                            "Periodo" => date('m'),
                            "Serie" => '',
                            "Documento" => $cont,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Unidades" => $unidades,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "Importe"=> $bruto,
                            "Comentario" =>"PedidoVenta: " . date('Y')."/".''."/".$cont,
                            "CodigoCliente"=> $codigoCliente,
                            "StatusAcumulado"=> -1,
                            "OrigenMovimiento"=> "C",
                            "MovOrigen"=>$guidArticulo,
                            "EmpresaOrigen"=>session('codigoEmpresa'),
                            "EjercicioDocumento"=> date('Y'),
                            "ReservarStock_"=> -1
                        ]);
                    }else{
                        $lineaPedido = DB::table('MovimientoPendientes');
                        $lineaPedido->insert([
                            "CodigoEmpresa" => session('codigoEmpresa'),
                            "Ejercicio"=> date('Y'),
                            "Periodo"=>date('m'),
                            "Serie" => '',
                            "Documento" => $cont,
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Unidades" => $unidades,
                            "Unidades2_" => $unidades,
                            "Precio" => $precio,
                            "Importe"=> $bruto,
                            "Comentario" =>"PedidoVenta: " . date('Y')."/".''."/".$cont,
                            "CodigoCliente"=> $codigoCliente,
                            "StatusAcumulado"=> -1,
                            "OrigenMovimiento"=> "C",
                            "MovOrigen"=>$guidArticulo,
                            "EmpresaOrigen"=>session('codigoEmpresa'),
                            "EjercicioDocumento"=> date('Y'),
                            "ReservarStock_"=> -1                        
                        ]);
                        //var_dump($updateMovimiento);
                    }

                    $existeArticuloEnAcumuladoPendientes = self::comprobarExisteArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                    if ($existeArticuloEnAcumuladoPendientes == false) {
                        //si no hay ninguna línea en la que haya el artículo con la partida, el almacen y la empresa se realiza una insercción
                        $lineaPedido = DB::table("AcumuladoPendientes");
                        $lineaPedido->insert([
                        "CodigoEmpresa" => session('codigoEmpresa'),
                            "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                            'CodigoAlmacen' => '0',
                            "Partida" => $partida,
                            "PendienteServir" => $unidades,
                            "PendienteServirTipo_" => $unidades,
                            "StockReservadoPedidos_" => $unidades,
                            "StockReservadoPedidosTipo_" => $unidades
                        ]);
                    } else {
                        //si existe articulo se hace un update
                        //obtenemos cantidad para actualizarla
                        $cantidadAactualizar = self::obtenerCantidadArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                        self::actualizarCantidadAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $unidades);
                }

            }


            
            }
            $nLineas = $orden/5-1;

            $query = DB::table('CabeceraPedidoCliente')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('SeriePedido', '=', '')
                ->where('EjercicioPedido', '=', date('Y'))
                ->where('NumeroPedido', '=', $cont)
                ->update([
                    "NumeroLineas"=>$nLineas,
                    "ImporteBruto" => $importeBruto,
                    "ImporteBrutoPendiente" => $importeBruto, //realmente es con cálculo de importe pendiente
                    "ImporteNetoLineas" => $importeNeto,
                    "ImporteNetoLineasPendiente" => $importeNeto, //realmente es con cálculo de importe pendiente
                    //"ImporteDescuento" =>$_POST['datos']['total'] * session('descuento') / 100,
                    "ImporteParcial" => $importeNeto - $importeDescuento,
                    "ImporteParcialPendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                    "BaseImponible" => $baseImponible,
                    "BaseImponiblePendiente" => $importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
                    "TotalCuotaIva" => $totalIva,
                    "TotalCuotaRecargo" => $totalRecargo,
                    "TotalIva" => $totalIva + $totalRecargo,
                    "ImporteLiquido" => $baseImponible + $totalIva + $totalRecargo,
                    "ImporteFactura" => $baseImponible + $totalIva + $totalRecargo,
                ]);

            
            $ejercicio = date('Y');
            //$datos = ['importeDescuentoLineas'=>$importeDescuentoLineas, 'importeBruto'=>$importeBruto, 'importeNeto'=>$importeNeto, 'importeDescuento'=>$importeDescuento, 'importeDescuento'=>$baseImponible, 'desgloseIva'=>$desgloseIva, 'totalIva'=>$totalIva];
            $datos = ['correcto'=>'1', 'numero'=>$cont, 'serie'=>'', 'ejercicio'=>$ejercicio, 'correo'=>$cliente[0]->EMail1, 'envio'=>$domicilios];
            return $datos;
        } else {

            //self::contadorMenos('',$contador[0]->sysContadorValor,'pedidos_cli');

            return 'cabecera no creada y contador restaurado cierra sesion si el error persiste';
        }



        // $importeDescuentoLineas = $_POST['datos']['total'] * $_POST['datos']['descuentoLineas'];
        // $importeBruto = $_POST['datos']['importeBruto'] ;
        // $importeNeto = $_POST['datos']['total'] / 1.21  - $importeDescuentoLineas;
        // $importeDescuento = $importeNeto * session('descuento') / 100;
        // $baseImponible = $importeNeto - $importeDescuento ;
        // $desgloseIva = $_POST['datos']['total'] / 1.21 ;
        // $totalIva = $_POST['datos']['total'] - $desgloseIva;

        //$datos = [$cabecera, $consultar, $precioDescuento, $subtotal, $precio, $descuento, $unidades];

        //}
    }

    public static function observacionPedido(){

        $quiery = DB::table('CabeceraPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->update(['ObservacionesPedido' => substr($_POST['comentario'],0,49),
            'ObservacionesAlbaran' => substr($_POST['comentario'],0,49),
            'ObservacionesFactura' => substr($_POST['comentario'],0,49),
            'VObservacionPedido' => $_POST['comentario']
        ]);
        return 'ok';
    }

    public static function guardarSuPedido(){

        $quiery = DB::table('CabeceraPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->update(['SuPedido' => $_POST['pedido']]);

        if($quiery){
            $quiery2 = DB::table('LineasPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('NumeroPedido', '=', $_POST['numero'])
            ->where('SeriePedido', '=', '')
            ->where('EjercicioPedido', '=', date('Y'))
            ->update(['SuPedido' => $_POST['pedido']]);

            if($quiery2){
                return 'ok';
            }
        }

    }

    public static function guardarProntoPago(){

        $quiery3 = DB::table('LineasPedidoCliente') 
        ->select('*','%Iva as Iva', '%Recargo as Recargo')       
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->get();

        $descuentoProntoPago = $_POST['prontoPago'];

        if ($quiery3->count() != null) {

            foreach($quiery3 as $lineas){

                $base = $lineas->ImporteBruto;
                $cuotaDescuento = $lineas->ImporteDescuento;
                $cuotaProntoPago = ($base - $cuotaDescuento) * ($descuentoProntoPago / 100);                
                $baseImponible = $base - $cuotaProntoPago - $cuotaDescuento;
                $neto = $base - $cuotaDescuento;
                $iva =  $lineas->Iva / 100;
                $recargo = $lineas->Recargo / 100;
                $cuotaIva = $baseImponible * $iva;
                $cuotaRecargo = $baseImponible * $recargo;
                $totalIva = $cuotaIva + $cuotaRecargo;
                $importeLiquido = $baseImponible + $totalIva;

                $lineasPedido = DB::table('LineasPedidoCliente')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('NumeroPedido', '=', $_POST['numero'])
                ->where('SeriePedido', '=', '')
                ->where('EjercicioPedido', '=', date('Y'))
                ->where('CodigoArticulo', '=', $lineas->CodigoArticulo)
                ->where('Orden', '=', $lineas->Orden)
                ->update([
                    
                    'ImporteProntoPago'=>$cuotaProntoPago,
                    'BaseImponible'=>$baseImponible,
                    'BaseIva'=>$baseImponible,
                    'CuotaIva'=>$cuotaIva,
                    'CuotaRecargo'=>$cuotaRecargo,
                    'TotalIva'=>$totalIva,
                    'ImporteLiquido'=>$importeLiquido,
                    'BaseImponiblePendiente'=>$baseImponible,                                                    
                    "ImporteParcial"=> $base,
                    "ImporteParcialPendiente"=>$base,
                ]);

            }
           
        } 

        $quiery2 = DB::table('LineasPedidoCliente')
        ->selectRaw('SUM(BaseImponible) as BImponible, SUM(CuotaIva) as CIva, SUM(CuotaRecargo) as CRecargo, SUM(ImporteProntoPago) as CProntoPago')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->get();

        //return $quiery2;

        if($quiery2[0]->BImponible != null){
            $query = DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('NumeroPedido', '=', $_POST['numero'])
            ->where('SeriePedido', '=', '')
            ->where('EjercicioPedido', '=', date('Y'))
            ->update([
                '%ProntoPago' => $_POST['prontoPago'],
                'ImporteProntoPago' => $quiery2[0]->CProntoPago,
                'BaseImponible' => $quiery2[0]->BImponible,
                'TotalCuotaIva' => $quiery2[0]->CIva,
                'TotalCuotaRecargo' => $quiery2[0]->CRecargo, 
                'TotalIva' => $quiery2[0]->CIva + $quiery2[0]->CRecargo,
                'ImporteLiquido' =>  $quiery2[0]->CIva + $quiery2[0]->CRecargo + $quiery2[0]->BImponible,
                'BaseImponiblePendiente' => $quiery2[0]->BImponible
            ]);
            if($query == 0){
                return DB::table('LineasPedidoCliente') 
                ->select('*','%Iva as Iva', '%Recargo as Recargo')       
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('NumeroPedido', '=', $_POST['numero'])
                ->where('SeriePedido', '=', '')
                ->where('EjercicioPedido', '=', date('Y'))
                ->get();

            }else{

                return DB::table('LineasPedidoCliente') 
                ->select('*','%Iva as Iva', '%Recargo as Recargo')       
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('NumeroPedido', '=', $_POST['numero'])
                ->where('SeriePedido', '=', '')
                ->where('EjercicioPedido', '=', date('Y'))
                ->get();

            }

        }else{

            $query = DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('NumeroPedido', '=', $_POST['numero'])
            ->where('SeriePedido', '=', '')
            ->where('EjercicioPedido', '=', date('Y'))
            ->update([
                '%ProntoPago' => $_POST['prontoPago'],                
            ]);

            if($query == 0){
                return 'ok';
            }else{
                return 'error';
            }
        }
        
    }
    
    public static function observacionSuPedido(){

        $quiery = DB::table('CabeceraPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->update(['SuPedido' => $_POST['suPedido'], 
        'ObservacionesPedido' => $_POST['observacion'],
        'ObservacionesAlbaran' => $_POST['observacion'],
        'ObservacionesFactura' => $_POST['observacion']]);

        $quiery2 = DB::table('LineasPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', '')
        ->where('EjercicioPedido', '=', date('Y'))
        ->update(['SuPedido' => $_POST['suPedido']]);

        return 'ok';
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


        if($update){
            return 'ok';
        }
    }

    public static function direcciones(){

        $update = DB::table('Domicilios')    
        ->select("NumeroDomicilio", "Nombre", DB::raw("CONCAT(Domicilios.Domicilio,' ',Domicilios.Numero1,' ',Domicilios.Numero2,' ',Domicilios.Escalera,' ',Domicilios.Puerta,' ',Domicilios.Letra,',',Domicilios.Municipio,', ',Domicilios.Provincia) as Direccion"))    
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))        
        ->where('CodigoCliente', '=', $_POST['cliente'])
        ->where('TipoDomicilio', '=', 'E')
        ->get();

        $datos = ['envio'=>$update];

        return $datos;
    }
    
    public static function recuperarPedido(){

        $datosPedido = DB::table('CabeceraPedidoCliente')
        ->select('CodigoEmpresa','EjercicioPedido','SeriePedido','NumeroPedido', 'CodigoCliente') 
        ->where('IdPedidoCli', '=', $_POST['idPedido'])   
        ->get();

        $lineasPedido = DB::table('LineasPedidoCliente')
        ->select('*','%Descuento as Descuento', '%Recargo as Recargo')
        ->where('CodigoEmpresa', '=', $datosPedido[0]->CodigoEmpresa)
        ->where('EjercicioPedido', '=', $datosPedido[0]->EjercicioPedido)
        ->where('SeriePedido', '=', $datosPedido[0]->SeriePedido)
        ->where('NumeroPedido', '=', $datosPedido[0]->NumeroPedido)
        ->get();

        //$lineasPedido[0]->CodigodelCliente = $datosPedido[0]->CodigoCliente;
        $respuesta = ["codigocliente"=>$datosPedido[0]->CodigoCliente, "lineaspedido"=>$lineasPedido];

        return  $respuesta;

    }

    public static function pedidomod(){        

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


        $cabecera = DB::table('CabeceraPedidoCliente')
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('EjercicioPedido', '=', $_POST['ejercicio'])
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        $cliente = DB::table('Clientes')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('CodigoCliente', '=', $cabecera[0]->CodigoCliente)
        ->get();

            
            foreach ($_POST['lineasPosicion'] as $lineas) {

                $guidArticulo = self::codigoGuid();

                list($codigo, $puesto) = explode("¬", $lineas['guid']);
                //var_dump($codigo);

                if($lineas['origen'] == 1){

                    $consultarLineas  = DB::table('LineasPedidoCliente')
                    ->where('SeriePedido', '=', $_POST['serie'])
                    ->where('EjercicioPedido', '=', $_POST['ejercicio'])
                    ->where('NumeroPedido', '=', $_POST['numero'])
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                    ->where('CodigoArticulo', '=', $codigo)
                    ->where('Orden','=', $puesto)
                    ->get();


                    //return $consultarLineas[0]->Partida;
                    $query2 = self::actualizarUnidadesAcumuladoPendientes($consultarLineas[0]->Partida, $codigo, 0, $consultarLineas[0]->UnidadesPedidas);
                        if ($query2 == 1) {

                            $query3 = DB::table('movimientopendientes')->where('MovOrigen', $consultarLineas[0]->LineasPosicion)->delete();
                            if ($query3 == 1) {
                               
                            } else {
                                return 'no se eliminaron los movimientos';
                            }
                        } else {
                            return "Error al realizar movimientos stock";
                    }

                    $eliminarlinea = DB::table('LineasPedidoCliente')->where('lineasPosicion', $consultarLineas[0]->LineasPosicion)->delete();

                }
                

                $lineo = DB::table('Articulos')                   
                ->where('CodigoArticulo', '=', $codigo)
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))                    
                ->get();                      

                $tienePartida = DB::table('Articulos')
                ->select('TratamientoPartidas','PrecioCompra', '%Descuento as Descuento', '%Margen as Margen' )
                ->where('CodigoEmpresa',\session('codigoEmpresa'))
                ->where('CodigoArticulo',$codigo)->get();

                if($tienePartida[0]->TratamientoPartidas == -1){
                    $partidas = DB::table('AcumuladoStock')->select('Partida','FechaCaducidad')
                    ->where('Periodo',99)
                    ->where('CodigoAlmacen','0')
                    ->where('CodigoEmpresa',\session('codigoEmpresa'))
                    ->where('Ejercicio',date('Y'))
                    ->where('UnidadSaldo','>',0)
                    ->where('FechaCaducidad','<>',null)
                    ->where('CodigoArticulo',$codigo)
                    ->where('UnidadSaldo', '>=', $lineas['unidades'])
                    ->orderByDesc('FechaCaducidad')->get();   
                    
                    if(count($partidas) > 0){
                        $partida = $partidas[0]->Partida;
                        $fechaCaducidad = $partidas[0]->FechaCaducidad;
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
                            

                $unidades = $lineas['unidades'];
                $descuento = $lineas['descuento'];
                $precio = $lineas['precio'];
                $recargo = $lineas['recargo'];                                                                                   
                
                $importeBruto = $precio * $unidades;
                $baseRecargo = $importeBruto * $recargo / 100;
                $importeDescuento = $importeBruto *  $descuento / 100;
                $importeNeto = $importeBruto - $importeDescuento;                   
                //$descuentoCliente = $importeNeto * $descuento / 100;
                $baseImponible = $importeNeto;
                $cuotaiva = ($baseImponible * $ivaArticulo / 100);
                $totalIva = $cuotaiva + $recargo;
                $importeLiquido = $importeNeto + $totalIva;                     
                $precioCompra = $tienePartida[0]->PrecioCompra;
                $margenDeBeneficio = ($precio - $importeDescuento - $precioCompra) * $unidades;
                if($importeNeto == 0 ){
                    if($importeNeto == 0 && $precio == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        $porMargenBeneficio = 100;
                    }
                }else{                            
                    $porMargenBeneficio = ($margenDeBeneficio  * 100) / $importeNeto;
                }


                $lineaPedido = DB::table('LineasPedidoCliente')
                ->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "CodigoDelCliente" => $lineo[0]->CodigoArticulo,
                    "EjercicioPedido" => $_POST['ejercicio'],
                    'CodigoAlmacen' => '0',
                    "SeriePedido" => $_POST['serie'],
                    "NumeroPedido" => $_POST['numero'],
                    'LineasPosicion'=> $guidArticulo,
                    "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                    "DescripcionArticulo" => $lineo[0]->DescripcionArticulo,
                    "CodigoAlmacenAnterior"=>'0',
                    "CodigoFamilia"=>$lineo[0]->CodigoFamilia,
                    "CodigoSubFamilia"=>$lineo[0]->CodigoSubfamilia,
                    "TipoArticulo"=>$lineo[0]->TipoArticulo,                   
                    "ReservarStock_"=>-1,
                    "Estado"=>0,
                    "GrupoIva" => $codigoIva,
                    "CodigoIva" => $ivaArticulo,
                    "%Iva"=>$ivaArticulo,
                    "UnidadesPendientesFabricar" => $unidades,
                    "UnidadesPedidas" => $unidades,
                    "UnidadesPendientes" => $unidades,
                    "UnidadesServidas" => 0,
                    "Unidades2_" => $unidades,
                    "Precio" => $precio,
                    "CodigoComisionista"=>session('codigoComisionista'),
                    "%Descuento" =>$descuento,
                    "Orden" => $orden,
                    "CodigoJefeVenta_"=>$codigoJefeVenta,
                    //"%Comision"=>$lineo[0]->Comision,
                    "ImporteBruto" => $importeBruto,
                    "ImporteBrutoPendiente" => $importeBruto,
                    "ImporteDescuento" => $importeDescuento,
                    "ImporteNeto" => $importeNeto,
                    "ImporteNetoPendiente" => $importeNeto,
                    //"ImporteDescuentoCliente" => $desCliente,
                    "BaseImponible" =>$baseImponible,
                    "BaseIva" =>$baseImponible,
                    "CuotaIva" =>$cuotaiva,
                    "%Recargo" =>$lineas['recargo'],
                    "CuotaRecargo" =>$baseRecargo,
                    "TotalIva" =>$totalIva + $baseRecargo,
                    "ImporteLiquido"=>$baseImponible + $baseRecargo + $totalIva,

                    "Partida" => "$partida",
                    "FechaCaduca" => "$fechaCaducidad",

                    "UnidadesPendAnterior"=>$unidades,
                    "PrecioCoste"=>$precioCompra ,
                    "ImporteCoste"=>$precioCompra * $unidades ,
                    "ImporteParcial"=> $importeBruto,
                    "ImporteParcialPendiente"=>$importeNeto,
                    "BaseImponiblePendiente"=>$baseImponible,
                    "PorMargenBeneficio"=>$porMargenBeneficio,
                    "MargenBeneficio"=>$margenDeBeneficio,                    

                    "CodigoTransaccion"=>1,
                    "CodigoDefinicion_"=>$lineo[0]->CodigoDefinicion_,
                ]);    
                    
                




                $existePedidoEnMovimientoPendientes = self::comprobarExisteArticuloEnMovimientoPendiente($_POST['numero'], $_POST['serie'],$lineo[0]->CodigoArticulo);

                if($existePedidoEnMovimientoPendientes == false){
                    //insercción
                    $lineaPedido = DB::table('MovimientoPendientes');
                    $lineaPedido->insert([
                        "CodigoEmpresa" => session('codigoEmpresa'),
                        "Ejercicio"=> $_POST['ejercicio'],
                        "Periodo"=>date('m'),
                        "Serie" => $_POST['serie'],
                        "Documento" => $_POST['numero'],
                        "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                        'CodigoAlmacen' => '0',
                        "Unidades" => $unidades,
                        "Unidades2_" => $unidades,
                        "Precio" => $precio,
                        "Importe"=> $importeBruto,
                        "Comentario" =>"PedidoVenta: " . $_POST['ejercicio']."/".$_POST['serie']."/".$_POST['numero']."/".$orden,
                        "CodigoCliente"=> $codigoCliente,
                        "StatusAcumulado"=> -1,
                        "OrigenMovimiento"=> "C",
                        "MovOrigen"=>$guidArticulo,
                        "EmpresaOrigen"=>session('codigoEmpresa'),
                        "EjercicioDocumento"=> date('Y'),
                        "ReservarStock_"=> -1
                    ]);
                }else{    
                    $lineaPedido = DB::table('MovimientoPendientes');
                    $lineaPedido->insert([
                        "CodigoEmpresa" => session('codigoEmpresa'),
                        "Ejercicio"=> $_POST['ejercicio'],
                        "Periodo"=>date('m'),
                        "Serie" => $_POST['serie'],
                        "Documento" => $_POST['numero'],
                        "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                        'CodigoAlmacen' => '0',
                        "Unidades" => $unidades,
                        "Unidades2_" => $unidades,
                        "Precio" => $precio,
                        "Importe"=> $importeBruto,
                        "Comentario" =>"PedidoVenta: " . $_POST['ejercicio']."/".$_POST['serie']."/".$_POST['numero']."/".$orden,
                        "CodigoCliente"=> $codigoCliente,
                        "StatusAcumulado"=> -1,
                        "OrigenMovimiento"=> "C",
                        "MovOrigen"=>$guidArticulo,
                        "EmpresaOrigen"=>session('codigoEmpresa'),
                        "EjercicioDocumento"=> date('Y'),
                        "ReservarStock_"=> -1
                    ]);
                }

                $existeArticuloEnAcumuladoPendientes = self::comprobarExisteArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                if ($existeArticuloEnAcumuladoPendientes == false) {
                    //si no hay ninguna línea en la que haya el artículo con la partida, el almacen y la empresa se realiza una insercción
                    $lineaPedido = DB::table("AcumuladoPendientes");
                    $lineaPedido->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                        "CodigoArticulo" => $lineo[0]->CodigoArticulo,
                        'CodigoAlmacen' => '0',
                        "Partida" => $partida,
                        "PendienteServir" => $unidades,
                        "PendienteServirTipo_" => $unidades,
                        "StockReservadoPedidos_" => $unidades,
                        "StockReservadoPedidosTipo_" => $unidades
                    ]);
                } else {
                    //si existe articulo se hace un update
                    //obtenemos cantidad para actualizarla
                    $cantidadAactualizar = self::obtenerCantidadArticuloEnAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida);
                    self::actualizarCantidadAcumuladoPendientes($lineo[0]->CodigoArticulo, $partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $unidades);
                }

                $orden = $orden + 5;

            }
            $nLineas = $orden/5-1;

            

            $cabeceraUpdate = DB::table('LineasPedidoCliente')
            ->selectRaw('Sum(ImporteBruto) as importeBruto, Sum(ImporteDescuento) as importeDescuento, Sum(ImporteNeto) as importeNeto, 
            Sum(ImporteDescuentoCliente) as importeDescuentoCliente, Sum(ImporteProntoPago) as importeProntoPago, Sum(BaseImponible) as baseImponible, 
            Sum(CuotaIva) as cuotaIva, Sum(CuotaRecargo) as cuotaRecargo, Sum(TotalIva) as totalIva, Sum(ImporteBrutoPendiente) as importeBrutoPendiente,
            sum(ImporteDescuentoPendiente) as importeDescuentoPendiente, Sum(ImporteNetoPendiente) as importeNetoPendiente,  Sum(BaseImponiblePendiente) as baseImponiblePendiente,
            Sum(ImporteLiquido) as importeLiquido')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $_POST['serie'])
            ->where('EjercicioPedido', '=', $_POST['ejercicio'])
            ->where('NumeroPedido', '=', $_POST['numero'])
            ->get();



            $query2 = DB::table('CabeceraPedidoCliente')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('SeriePedido', '=', $_POST['serie'])
            ->where('EjercicioPedido', '=', $_POST['ejercicio'])
            ->where('NumeroPedido', '=', $_POST['numero'])
            ->update([

                "ImporteBruto" => $cabeceraUpdate[0]->importeBruto,
                "ImporteDescuentoLineas"=> $cabeceraUpdate[0]->importeDescuento,
                "ImporteNetoLineas"=> $cabeceraUpdate[0]->importeNeto,
                "BaseImponible"=> $cabeceraUpdate[0]->baseImponible,
                "TotalCuotaIva"=>$cabeceraUpdate[0]->cuotaIva,
                "TotalCuotaRecargo"=>$cabeceraUpdate[0]->cuotaRecargo,
                "TotalIva"=>$cabeceraUpdate[0]->totalIva,
                "ImporteLiquido"=> $cabeceraUpdate[0]->importeLiquido,
                "ImporteBrutoPendiente"=>$cabeceraUpdate[0]->importeBrutoPendiente,
                "ImporteNetoLineasPendiente"=>$cabeceraUpdate[0]->importeNetoPendiente,
                "ImporteParcialPendiente"=>$cabeceraUpdate[0]->baseImponiblePendiente,
                "BaseImponiblePendiente"=>$cabeceraUpdate[0]->baseImponiblePendiente
            ]);
            // $query = DB::table('CabeceraPedidoCliente')
            //     ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            //     ->where('SeriePedido','=',$_POST['serie'])
            //     ->where('EjercicioPedido','=',$_POST['ejercicio'])
            //     ->where('NumeroPedido','=',$_POST['numero'])
            //     ->update([
            //         "NumeroLineas"=>$nLineas,
            //         "ImporteBruto"=>$importeBruto,
            //         "ImporteBrutoPendiente"=>$importeBruto, //realmente es con cálculo de importe pendiente
            //         "ImporteNetoLineas"=>$importeNeto,
            //         "ImporteNetoLineasPendiente"=>$importeNeto, //realmente es con cálculo de importe pendiente
            //         //"ImporteDescuento" =>$_POST['datos']['total'] * session('descuento') / 100,
            //         "ImporteParcial"=>$importeNeto - $importeDescuento,
            //         "ImporteParcialPendiente"=>$importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
            //         "BaseImponible"=>$baseImponible,
            //         "BaseImponiblePendiente"=>$importeNeto - $importeDescuento, //realmente es con cálculo de importe pendiente
            //         "TotalCuotaIva"=>$totalIva,
            //         "TotalCuotaRecargo"=>$totalRecargo,
            //         "TotalIva"=>$totalIva + $totalRecargo,
            //         "ImporteLiquido"=>$baseImponible + $totalIva + $totalRecargo,
            //         "ImporteFactura"=>$baseImponible + $totalIva + $totalRecargo,                    
            // ]);
                        
            //$datos = ['importeDescuentoLineas'=>$importeDescuentoLineas, 'importeBruto'=>$importeBruto, 'importeNeto'=>$importeNeto, 'importeDescuento'=>$importeDescuento, 'importeDescuento'=>$baseImponible, 'desgloseIva'=>$desgloseIva, 'totalIva'=>$totalIva];
            $datos = ['correcto'=>'1', 'numero'=>$_POST['numero'], 'serie'=>$_POST['serie'], 'ejercicio'=>$_POST['ejercicio'], 'correo'=>$cliente[0]->EMail1];
            return $datos;
            //return 1;

    }

    
    public static function eliminarmod(){
        
        $linea = DB::table('LineasPedidoCliente')
        ->select('LineasPosicion', 'Partida', 'UnidadesPedidas')
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('EjercicioPedido', '=', $_POST['ejercicio'])
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('CodigoArticulo', '=', $_POST['codigoArticulo'])
        ->where('Orden', '=', $_POST['orden'])
        ->get();

       // return $linea;

        $lineo = self::actualizarUnidadesAcumuladoPendientes($linea[0]->Partida, $_POST['codigoArticulo'], 0, $linea[0]->UnidadesPedidas);
        //return $lineo;
        if ($lineo == 1) {
            $query3 = DB::table('movimientopendientes')->where('MovOrigen', $linea[0]->LineasPosicion)->delete();
            if ($query3 == 1) {
                $borrar = DB::table('LineasPedidoCliente')->where('lineasPosicion', $linea[0]->LineasPosicion)->delete();
            } else {
                return 'error';
            }
        } else {
            return "Error al realizar movimientos stock";
        }
    
        return 1;

    }

    public static function estadoPedido(){

        
        $cabecera = DB::table('CabeceraPedidoCliente')
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('EjercicioPedido', '=', $_POST['ejercicio'])
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->update([
            'Estado'=>$_POST['estado']
        ]);

        return $_POST['estado'];
    }
    
    public static function descripcion0(){

        $update = DB::table('LineasPedidoCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('EjercicioPedido', '=', date('Y'))
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

        $update = DB::table('LineasPedidoCliente')        
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('EjercicioPedido', '=', date('Y'))
        ->where('Orden', '=', $_POST['orden'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->update([
            'PrecioCoste' =>$_POST['coste']
        ]);


        if($update){
            return 'ok articulo 0 coste';
        }
    }

    public static function ultimosPedidos(){        
    
        $quiery = DB::table('LineasPedidoCliente')
        ->select('LineasPedidoCliente.CodigoArticulo', 'LineasPedidoCliente.DescripcionArticulo', 
        'LineasPedidoCliente.UnidadesPedidas', 'LineasPedidoCliente.Precio', 
        'LineasPedidoCliente.ImporteLiquido', 'LineasPedidoCliente.%Descuento as Descuento', 'LineasPedidoCliente.FechaPedido')
        ->leftJoin('CabeceraPedidoCliente', function($join){
            $join->on('CabeceraPedidoCliente.SeriePedido', '=', 'LineasPedidoCliente.SeriePedido');
            $join->on('CabeceraPedidoCliente.EjercicioPedido', '=', 'LineasPedidoCliente.EjercicioPedido');
            $join->on('CabeceraPedidoCliente.NumeroPedido', '=', 'LineasPedidoCliente.NumeroPedido');
            $join->on('CabeceraPedidoCliente.CodigoEmpresa', '=', 'LineasPedidoCliente.CodigoEmpresa');
        })
        ->where('LineasPedidoCliente.CodigoEmpresa', '=', session('codigoEmpresa'))        
        ->where('CabeceraPedidoCliente.CodigoCliente', '=', $_POST['codigoCliente'])
        ->where('LineasPedidoCliente.CodigoArticulo', '=', $_POST['codigoArticulo'])
        ->where('LineasPedidoCliente.FechaPedido', '<', date("Y-m-d 00:00:00.000"))
        ->orderBy('LineasPedidoCliente.FechaPedido', 'desc')
        ->take(3)
        ->get();

        foreach($quiery as $quierys){
            $quierys->FechaPedido = substr($quierys->FechaPedido, 0, 10);
        }

        return $quiery;
    }

    public static function datosPedido(){
        $q = DB::table('CabeceraPedidoCliente')->select('EjercicioPedido', 'SeriePedido', 'NumeroPedido')->where('IdPedidoCli', '=', $_POST['idPedido'])->get();

        return $q;
    }

    public static function observacionArticulo(){
        $q = DB::table('LineasPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('EjercicioPedido', '=', date('Y'))
        ->where('SeriePedido', '=', $_POST['serie'])
        ->where('NumeroPedido', '=', $_POST['numero'])
        ->where('CodigoArticulo', '=', $_POST['articulo'])
        ->where('Orden', '=', $_POST['orden'])
        ->update(['DescripcionLinea' => $_POST['observacion'] ]);
    }

    public static function comprobacionPedido(){
        $comprobado = 'no';

        $pedido = DB::table('LineasPedidoCliente')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('NumeroPedido', '=', $_POST['pedido']['numero'])
        ->where('SeriePedido', '=', $_POST['pedido']['serie'])
        ->where('EjercicioPedido', '=', date('Y'))
        ->get();

        if(count($pedido) == count($_POST['pedido']['lineas'])){
            foreach($_POST['pedido']['lineas'] as $linea){

                $codigoArticulo = $linea['codigo'];
                $unidades = $linea['cantidad'];
                $descuento = $linea['descuento'];
                $precio = $linea['precio'];
                $recargo = $linea['recargo'];
                if($linea['precioCosteVarios'] != 0){
                    $precioCompra = $linea['precioCosteVarios'];
                }else{
                    $precioCompra = $linea['precioCompra'];
                }       
                $iva = $linea['iva'];
                $orden = $linea['orden'];
                $partida = $linea['partida'];
                if($linea['fechaCaducidad'] != 0){
                    $fechaCaducidad = $linea['fechaCaducidad'];
                }else{
                    $fechaCaducidad = null;
                }    
                $observacionArticulo = $linea['observacionArticulo'];

                $importeBruto = $precio * $unidades;
                $baseRecargo = $importeBruto * $recargo / 100;
                $importeDescuento = $importeBruto *  $descuento / 100;
                $importeNeto = $importeBruto - $importeDescuento;                   
                $descuentoCliente = $importeNeto * $descuento / 100;
                $baseImponible = $importeNeto - $descuentoCliente;
                $cuotaiva = ($baseImponible * $iva / 100);
                $totalIva = $cuotaiva + $recargo;
                $importeLiquido = $importeNeto + $totalIva;                     
                $precioCompra = $precioCompra;
                $margenDeBeneficio = ($precio - $importeDescuento - $precioCompra) * $unidades;
                if($importeNeto == 0 ){
                    if($importeNeto == 0 && $linea['precio'] == 0){
                        $porMargenBeneficio = 0;
                    }else{
                        $porMargenBeneficio = 100;
                    }
                }else{                            
                    $porMargenBeneficio = ($margenDeBeneficio  * 100) / $importeNeto;
                }
                
                
                $actualizarLinea = DB::table('LineasPedidoCliente')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('NumeroPedido', '=', $_POST['pedido']['numero'])
                ->where('SeriePedido', '=', $_POST['pedido']['serie'])
                ->where('EjercicioPedido', '=', date('Y'))
                ->where('CodigoArticulo', '=', $codigoArticulo)
                ->where('Orden', '=', $orden)
                ->update([
                    "UnidadesPendientesFabricar" => $unidades,
                    "UnidadesPedidas" => $unidades,
                    "UnidadesPendientes" => $unidades,
                    "UnidadesServidas" => 0,
                    "Unidades2_" => $unidades,
                    "UnidadesPendAnterior"=>$unidades,                        
                
                    "Partida" => "$partida",
                    "FechaCaduca" => "$fechaCaducidad",

                    "ImporteBruto" => $importeBruto,
                    "ImporteBrutoPendiente" => $importeBruto,
                    "ImporteDescuento" => $importeDescuento,
                    "ImporteNeto" => $importeNeto,
                    "ImporteNetoPendiente" => $importeNeto,
                    "ImporteDescuentoCliente" => $descuentoCliente,
                    "BaseImponible" => $baseImponible,
                    "%Iva"=>$iva,
                    "BaseIva" => $baseImponible,
                    "CuotaIva" => $cuotaiva,
                    "%Recargo" =>$recargo,
                    "CuotaRecargo"=>$baseRecargo,
                    "TotalIva" => $totalIva,
                    "ImporteLiquido" => $importeLiquido,
                    
                    "PrecioCoste"=>$precioCompra ,
                    "ImporteCoste"=>$precioCompra * $linea['cantidad'] ,
                    "ImporteParcial"=> $importeBruto,
                    "ImporteParcialPendiente"=>$importeBruto,
                    "BaseImponiblePendiente"=>$baseImponible,
                    "PorMargenBeneficio"=>$porMargenBeneficio,
                    "MargenBeneficio"=>$margenDeBeneficio,                        
                    "CodigoTransaccion"=>1,                        
                ]);    
            }

            $actualizarCabecera = self::actualizarCabeceraPedido( $_POST['pedido']['serie'],$_POST['pedido']['numero'],0,0);
            if($actualizarCabecera == 0){
                $comprobado = 'OK';
            }    
            return $comprobado;
        }
    }

    public static function obtenerContadorNuevo($serie){
        $contador = DB::table('LsysContadores')
            ->select('sysContadorValor')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNumeroSerie', '=', '')
            ->get();
        
        $query = DB::table('lsysContadores')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNombreContador', '=', 'PEDIDOS_CLI')
            ->where('sysNumeroSerie', '=', $serie)
            ->update(['sysContadorValor' => $contador[0]->sysContadorValor + 1]);

        return $contador[0]->sysContadorValor + 1;
    }

    public static function obtenerComisionLinea($codigoArticulo, $codigoComisionista, $familiaArticulo, $codigoCliente)
    {
        $comision = 0;
        //PRIMERO COMPROBAMOS SI EL ARTÍCULO LLEVA ALGUNA COMISION
        $comisionArticulo = Articulo::select("%Comision as comision")
            ->where('CodigoArticulo', $codigoArticulo)
            ->where('CodigoComisionista', $codigoComisionista)
            ->where('CodigoEmpresa', session('codigoEmpresa'))->get();

        //Si el articulo lleva alguna comision devolvemos comision obtenida del articulo
        if (count($comisionArticulo) > 0) {
            $comision = $comisionArticulo[0]->comision;
        } else {
            //En el caso de que no tenga comision en el articulo nos vamos a comprobarlo por la familia y el comisionista a la tabla ComisionesFamilia
            $comisionFamilia = DB::table("ComisionesFamilias_")->select("%Comision as comision")
                ->where('CodigoFamilia', $familiaArticulo)
                ->where('CodigoComisionista', $codigoComisionista)
                ->where('CodigoEmpresa', session('codigoEmpresa'))->get();
            //Comprobamos si hay resultados, en el que caso de que lo haya, asignamos la comision. En caso contrario, comprobamos la ficha del cliente
            if (count($comisionFamilia) > 0) {
                $comision = $comisionFamilia[0]->comision;
            } else {
                $comisionCliente = Cliente::select("%Comision as comision")
                    ->where('CodigoComisionista', $codigoComisionista)
                    ->where('CodigoCliente', $codigoCliente)
                    ->where('CodigoEmpresa', session('codigoEmpresa'))->get();
                //Por último comprobamos comision del cliente y en caso de que no haya resultado obtenemos la comision del comisionista
                if (count($comisionCliente) > 0) {
                    $comision = $comisionCliente[0]->comision;
                } else {
                    $comisionComisionista = Comisionista::select("%Comision as comision")
                        ->where('CodigoComisionista', $codigoComisionista)
                        ->where('CodigoEmpresa', session('codigoEmpresa'))->get();
                    if (count($comisionCliente) > 0) {
                        $comision = $comisionComisionista[0]->comision;
                    }
                }
            }
        }

        return round($comision, 2);
    }



    public static function eliminarPedido2()
    {
        $query = DB::table('CabeceraPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('IdPedidoCli', '=', $_POST["idPedido"]);
        $cabecera = $query->get();
        // if($query == 1 ){
        $query2 = DB::table('LineasPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('EjercicioPedido', '=', date('Y'))
            ->where('SeriePedido', '=', $cabecera[0]->SeriePedido)
            ->where('NumeroPedido', '=', $cabecera[0]->NumeroPedido);
        $lineas = $query2->get();
        //   if($query2 == 1 ) {
        //SI SE HAN PODIDO ELIMINAR AMBOS REGISTROS ELIMINAMOS REGISTRO DE MOVIMIENTOS PENDIENTES
        $query3 = DB::table('MovimientoPendientes')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Ejercicio', '=', date('Y'))
            ->where('Serie', '=', $cabecera[0]->SeriePedido)
            ->where('Documento', '=', $cabecera[0]->NumeroPedido)
            ->where('CodigoAlmacen', '0')->delete();
        //     if($query3 == 1 ){

        DB::table('CabeceraPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('EjercicioPedido', '=', date('Y'))
        ->where('IdPedidoCli', '=', $_POST["idPedido"])->delete();
        
        DB::table('LineasPedidoCliente')->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('EjercicioPedido', '=', date('Y'))
        ->where('SeriePedido', '=', $cabecera[0]->SeriePedido)
        ->where('NumeroPedido', '=', $cabecera[0]->NumeroPedido)->delete();

        foreach ($lineas as $linea) {
            if ($linea->FechaCaduca == 0) {
                $partida = "";
            } else {
                $partida = $linea->Partida;
            }
            $query4 = self::actualizarUnidadesAcumuladoPendientes($partida, $linea->CodigoArticulo, 0, $linea->UnidadesPedidas);
            //if($query4 == 1 ) $eliminado = "OK";
        }

        $eliminado = "OK";
        return $eliminado;
    }


    public static function duplicarPedido(){
        try {
            $cabecera = DB::table("CabeceraPedidoCliente")
            ->where('IdPedidoCli', $_POST["idPedido"])
            ->where('CodigoEmpresa', session("codigoEmpresa"))
            ->get();
    
            $lineas = DB::table("LineasPedidoCliente")
            ->where('NumeroPedido', $cabecera[0]->NumeroPedido)
            ->where('SeriePedido', $cabecera[0]->SeriePedido)
            ->where('EjercicioPedido', $cabecera[0]->EjercicioPedido)
            ->where('CodigoEmpresa', session("codigoEmpresa"))
            ->get();
    
            $numeroPed = self::contadorDuplicarPed($cabecera[0]->SeriePedido);
    
            $pedido = DB::table('CabeceraPedidoCliente');
            $pedido->insert([
                "CodigoEmpresa" => session('codigoEmpresa'),
                "CodigoCliente" => $cabecera[0]->CodigoCliente,
                "EjercicioPedido" => date('Y'),
                "SeriePedido" => $cabecera[0]->SeriePedido,
                "NumeroPedido" => $numeroPed,
                "FechaPedido" => date("Y-m-d"),
                "CifDni" => $cabecera[0]->CifDni,
                "CIFEuropeo" => $cabecera[0]->CifEuropeo,
                "RazonSocial" => $cabecera[0]->RazonSocial,
                "Nombre" => $cabecera[0]->Nombre,
                "Domicilio" => $cabecera[0]->Domicilio,
                "CodigoPostal" => $cabecera[0]->CodigoPostal,
                "CodigoMunicipio" => $cabecera[0]->CodigoMunicipio,
                "Municipio" => $cabecera[0]->Municipio,
                "CodigoProvincia" => $cabecera[0]->CodigoProvincia,
                "Provincia" => $cabecera[0]->Provincia,
                "CodigoNacion" => $cabecera[0]->CodigoNacion,
                "Nacion" => $cabecera[0]->Nacion,
                "CodigoCondiciones" => $cabecera[0]->CodigoCondiciones,
                "NumeroPlazos" => $cabecera[0]->NumeroPlazos,
                "CodigoContable" => $cabecera[0]->CodigoContable,
                "IBAN" => $cabecera[0]->IBAN,
                "ReservarStock_" =>  $cabecera[0]->ReservarStock_,
                "%Descuento" =>  $cabecera[0]->{"%Descuento"},
                "CodigoComisionista" => $cabecera[0]->CodigoComisionista,
                "ComercialAsignadoLc" => $cabecera[0]->ComercialAsignadoLc,
                "StatusAprobado" => $cabecera[0]->StatusAprobado,
                "CodigoJefeVenta_" => $cabecera[0]->CodigoJefeVenta_,
                "DiasPrimerPlazo" => $cabecera[0]->DiasPrimerPlazo,
                "DiasEntrePlazos" => $cabecera[0]->DiasEntrePlazos,
                "DiasFijos1" => $cabecera[0]->DiasFijos1,
                "DiasFijos2" => $cabecera[0]->DiasFijos2,
                "DiasFijos3" => $cabecera[0]->DiasFijos3,
                "DiasRetroceso" => $cabecera[0]->DiasRetroceso,
                "FormadePago" => $cabecera[0]->FormadePago,
                "InicioNoPago" => $cabecera[0]->InicioNoPago,
                "FinNoPago" => $cabecera[0]->FinNoPago,
                "MesesComerciales" => $cabecera[0]->MesesComerciales,
                "ControlarFestivos" => $cabecera[0]->ControlarFestivos,
                "RemesaHabitual" => $cabecera[0]->RemesaHabitual,
                "CodigoTipoEfecto" => $cabecera[0]->CodigoTipoEfecto,
                "CodigoBanco" => $cabecera[0]->CodigoBanco,
                "CodigoAgencia" => $cabecera[0]->CodigoAgencia,
                "DC" => $cabecera[0]->DC,
                "CCC" => $cabecera[0]->CCC,
                "CopiasAlbaran" => $cabecera[0]->CopiasAlbaran,
                "CopiasFactura" => $cabecera[0]->CopiasFactura,
                "AgruparAlbaranes" => $cabecera[0]->AgruparAlbaranes,
                "IndicadorIva" => $cabecera[0]->IndicadorIva,
                "TipoPortesEnvios" => $cabecera[0]->TipoPortesEnvios,
                "CodigoTransaccion" => $cabecera[0]->CodigoTransaccion,
                "AgruparAlbaranes" => $cabecera[0]->AgruparAlbaranes,
                "MantenerCambio_" => $cabecera[0]->MantenerCambio_,
                "ReferenciaMandato" => $cabecera[0]->ReferenciaMandato,
                "AlbaranValorado" => $cabecera[0]->AlbaranValorado,
                "StatusAprobado" => $cabecera[0]->StatusAprobado,
                "CodigoJefeVenta_" => $cabecera[0]->CodigoJefeVenta_,
                "ImporteBruto" => $cabecera[0]->ImporteBruto,
                "ImporteDescuentoLineas"=> $cabecera[0]->ImporteDescuentoLineas,
                "ImporteNetoLineas"=> $cabecera[0]->ImporteNetoLineas,
                "BaseImponible"=> $cabecera[0]->BaseImponible,
                "TotalCuotaIva"=>$cabecera[0]->TotalCuotaIva,
                "TotalCuotaRecargo"=>$cabecera[0]->TotalCuotaRecargo,
                "TotalIva"=>$cabecera[0]->TotalIva,
                "ImporteLiquido"=> $cabecera[0]->ImporteLiquido,
                "ImporteBrutoPendiente"=>$cabecera[0]->ImporteBrutoPendiente,
                "ImporteNetoLineasPendiente"=>$cabecera[0]->ImporteNetoLineasPendiente,
                "ImporteParcialPendiente"=>$cabecera[0]->ImporteParcialPendiente,
                "BaseImponiblePendiente"=>$cabecera[0]->BaseImponiblePendiente
            ]);
            foreach ($lineas as $linea) {
                $lineaPedido = DB::table('LineasPedidoCliente');
                $result = DB::select('SELECT NEWID() as lineaPosicion');
                $lineaPosicion = $result[0]->lineaPosicion;

                // dd($linea);
                $lineaPedido->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "CodigoDelCliente" => $linea->CodigodelCliente,
                    "EjercicioPedido" => $linea->EjercicioPedido,
                    'CodigoAlmacen' => $linea->CodigoAlmacen,
                    "SeriePedido" => $linea->SeriePedido,
                    "NumeroPedido" => $numeroPed,
                    'LineasPosicion' => $lineaPosicion,
                    "CodigoArticulo" => $linea->CodigoArticulo,
                    "DescripcionArticulo" => $linea->DescripcionArticulo,
                    "CodigoAlmacenAnterior" => $linea->CodigoAlmacenAnterior,
                    "CodigoFamilia" => $linea->CodigoFamilia,
                    "CodigoSubFamilia" => $linea->CodigoSubfamilia,
                    "TipoArticulo" => $linea->TipoArticulo,
                    "ReservarStock_" => $linea->ReservarStock_,
                    "Estado" => $linea->Estado,
                    "GrupoIva" => $linea->GrupoIva,
                    "CodigoIva" => $linea->CodigoIva,
                    "%Iva" => $linea->{"%Iva"},
                    "UnidadesPendientesFabricar" => $linea->UnidadesPendientesFabricar,
                    "UnidadesPedidas" => $linea->UnidadesPedidas,
                    "UnidadesPendientes" => $linea->UnidadesPendientes,
                    "UnidadesServidas" => $linea->UnidadesServidas,
                    "Unidades2_" => $linea->Unidades2_,
                    "Precio" => $linea->Precio,
                    "CodigoComisionista" => $linea->CodigoComisionista,
                    "%Recargo" => $linea->{"%Recargo"},
                    "CuotaRecargo" => $linea->CuotaRecargo,
                    "%Descuento" => $linea->{"%Descuento"},
                    "Orden" => $linea->Orden,
                    "CodigoJefeVenta_" => $linea->CodigoJefeVenta_,
                    "%Comision" => $linea->{"%Comision"},
                    "ImporteBruto" => $linea->ImporteBruto,
                    "ImporteBrutoPendiente" => $linea->ImporteBrutoPendiente,
                    "ImporteDescuento" => $linea->ImporteDescuento,
                    "ImporteNeto" => $linea->ImporteNeto,
                    "ImporteNetoPendiente" => $linea->ImporteNetoPendiente,
                    "ImporteDescuentoCliente" => $linea->ImporteDescuentoCliente,
                    "BaseImponible" => $linea->BaseImponible,
                    "BaseIva" => $linea->BaseIva,
                    "CuotaIva" => $linea->CuotaIva,
                    "TotalIva" => $linea->TotalIva,
                    "ImporteLiquido" => $linea->ImporteLiquido,
                    "UnidadesPendAnterior" => $linea->UnidadesPendAnterior,
                    "PrecioCoste" => $linea->PrecioCoste,
                    "ImporteCoste" => $linea->ImporteCoste,
                    "ImporteParcial" => $linea->ImporteParcial,
                    "ImporteParcialPendiente" => $linea->ImporteParcialPendiente,
                    "BaseImponiblePendiente" => $linea->BaseImponiblePendiente,
                    "PorMargenBeneficio" => $linea->PorMargenBeneficio,
                    "MargenBeneficio" => $linea->MargenBeneficio,
                    "CodigoTransaccion" => $linea->CodigoTransaccion,
                    "CodigoDefinicion_" => $linea->CodigoDefinicion_,
                ]);
    
                $lineaPedido = DB::table('MovimientoPendientes');
                $lineaPedido->insert([
                    "CodigoEmpresa" => session('codigoEmpresa'),
                    "Ejercicio" => date('Y'),
                    "Periodo" => date('m'),
                    "Serie" => $cabecera[0]->SeriePedido,
                    "Documento" => $_POST["idPedido"],
                    "CodigoArticulo" => $linea->CodigoArticulo,
                    'CodigoAlmacen' => $linea->CodigoAlmacen,
                    "Unidades" => $linea->Unidades2_,
                    "Unidades2_" => $linea->Unidades2_,
                    "Precio" => $linea->Precio,
                    "Importe" => $linea->Precio * $linea->Unidades2_,
                    "Comentario" => "PedidoVenta: " . date('Y') . "/" . $cabecera[0]->SeriePedido . "/" . $_POST["idPedido"]."/".$linea->Orden,
                    "CodigoCliente" => $cabecera[0]->CodigoCliente,
                    "StatusAcumulado" => -1,
                    "OrigenMovimiento" => "C",
                    "MovOrigen" => $lineaPosicion,
                    "EmpresaOrigen" => session('codigoEmpresa'),
                    "EjercicioDocumento" => date('Y'),
                    "ReservarStock_" => -1
                ]);
    
                $existeArticuloEnAcumuladoPendientes = self::comprobarExisteArticuloEnAcumuladoPendientes($linea->CodigoArticulo, $linea->Partida);
                if ($existeArticuloEnAcumuladoPendientes == false) {
                    //si no hay ninguna línea en la que haya el artículo con la partida, el almacen y la empresa se realiza una insercción
                    $lineaPedido = DB::table("AcumuladoPendientes");
                    $lineaPedido->insert([
                        "CodigoEmpresa" => session('codigoEmpresa'),
                        "CodigoArticulo" => $linea->CodigoArticulo,
                        'CodigoAlmacen' => $linea->CodigoAlmacen,
                        "Partida" => $linea->Partida,
                        "PendienteServir" => $linea->Unidades2_,
                        "PendienteServirTipo_" => $linea->Unidades2_,
                        "StockReservadoPedidos_" => $linea->Unidades2_,
                        "StockReservadoPedidosTipo_" => $linea->Unidades2_
                    ]);
                } else {
                    //si existe articulo se hace un update
                    //obtenemos cantidad para actualizarla
                    $cantidadAactualizar = self::obtenerCantidadArticuloEnAcumuladoPendientes($linea->CodigoArticulo, $linea->Partida);
                    self::actualizarCantidadAcumuladoPendientes($linea->CodigoArticulo, $linea->Partida, $cantidadAactualizar[0]->PendienteServir, $cantidadAactualizar[0]->StockReservadoPedidos_, $linea->Unidades2_);
                }
            }

            return "ok";
        } catch (QueryException $e) {
            return "error";
        }
    }


    public static function contadorDuplicarPed($serie)
    {

        $query = DB::table('lsysContadores')
            ->select('sysContadorValor')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=',0)
            ->where('sysNumeroSerie', '=', $serie)
            ->get();

        $query2 = DB::table('lsysContadores')
            ->where('sysNombreContador', '=', 'pedidos_cli')
            ->where('sysGrupo', '=', session('codigoEmpresa'))
            ->where('sysEjercicio', '=', 0)
            ->where('sysNumeroSerie', '=', $serie)
            ->update(['sysContadorValor' => $query[0]->sysContadorValor + 1]);

        return $query[0]->sysContadorValor + 1;
    }

    
}
