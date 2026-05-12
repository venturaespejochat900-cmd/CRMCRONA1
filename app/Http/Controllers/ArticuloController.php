<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Dato;
use ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ArticuloController extends Controller
{    

    

    public static function buscarArticulos(){
        // $consultaArticulos = Articulo::select('Articulos.*', 'ArticuloProveedor.CodigodelProveedor')
        // ->leftJoin('ArticuloProveedor', function($join){
        //     $join->on('Articulos.CodigoEmpresa', '=', 'ArticuloProveedor.CodigoEmpresa');
        //     $join->on('Articulos.CodigoArticulo', '=', 'ArticuloProveedor.CodigoArticulo');
        //     $join->on('Articulos.CodigoProveedor', '=', 'ArticuloProveedor.CodigoProveedor');
        // })
        // ->where('Articulos.ObsoletoLc', '=', 0)
        // ->where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'));

        // $consultaArticulos->where(function ($query){
        //     foreach(explode(' ', $_POST['productoAbuscar']) as $search){
        //         $query->where(function ($query) use ($search){
        //             $columnas = ['Articulos.CodigoArticulo', 'ArticuloProveedor.CodigodelProveedor', 'Articulos.DescripcionArticulo'];
        //             foreach($columnas as $columna) {
        //                 $query->orWhereRaw('LOWER(' . $columna . ') like ?', '%' . mb_strtolower($search) . '%');
        //             };
        //         });
        //     }    
        // })
        // ->take(15)
        // ->get();
        $datos = array();

        if($_POST['tipo'] == 'codigo'){

            $query =Articulo::where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Articulos.ObsoletoLc', '=', 0)                   
            ->where(function ($quiery){
                $where = "Articulos.CodigoArticulo = '".$_POST['productoAbuscar']."' or (Articulos.CodigoArticulo like '%".$_POST['productoAbuscar']."%')";
                $quiery->whereRaw($where);
            })
            ->take(10)
            ->get();

            // $query2 = Articulo::where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
            // ->where('Articulos.ObsoletoLc', '=', 0)
            // ->where('Articulos.CodigoArticulo', '=', $_POST['productoAbuscar'])
            // ->get();

            $query3 = DB::table('ArticuloProveedor')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigodelProveedor', '=', $_POST['productoAbuscar'])
            ->get();

            if(count($query)>0){
                foreach($query as $articulos){
                    //echo "3";
                    array_push($datos,$articulos);
                }
                //print_r($datos);
            }else{
                if(count($query3)>0){
                    //echo "1";
                    //print_r($datos);
                    $query4 = Articulo::where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
                    ->where('Articulos.ObsoletoLc', '=', 0)
                    ->where('Articulos.CodigoArticulo', '=', $query3[0]->CodigoArticulo)
                    ->get();
                }
    
                
                if(count($query3)>0){
                    //echo "2";
                    foreach($query4 as $codigoDelProveedor){
                        array_push($datos,$codigoDelProveedor);
                    }
                   // print_r($datos);
                }
            }
            
            // if(count($query2)>0){
            //     foreach($query2 as $codigoArticulo){
            //         array_push($datos,$codigoArticulo);
            //     }
            // }
            // if(count($query)>0){
            //     foreach($query as $articulos){
            //         echo "3";
            //         array_push($datos,$articulos);
            //     }
            //     //print_r($datos);
            // }

        }
        if($_POST['tipo'] == 'descripcion'){

            $query =Articulo::where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('Articulos.ObsoletoLc', '=', 0)                   
            ->where(function ($quiery){
                $where = "Articulos.DescripcionArticulo = '".$_POST['productoAbuscar']."' or (Articulos.DescripcionArticulo like '%".$_POST['productoAbuscar']."%')";
                $quiery->whereRaw($where);                               
            })       
            ->take(10)
            ->get();

            if(count($query)>0){
                foreach($query as $articulos){
                    array_push($datos,$articulos);
                }
            }

        }


        return $datos;
    }
    public static function rellenarArticulosMuestra(){
        $query = self::buscarArticulos();
        $html = "";        
        if(count($query)!= 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                $html .="
                        <tr>
                        <td><a>".$value['CodigoArticulo']."</a></td>
                        <td>".$value['DescripcionArticulo']."</td>
                        <td><button class='btn ' id='btnArticulo' onclick='seleccionarArticulo(".$value.")'><i class='far fa-hand-pointer'></i>
                        </button></td>
                        <td><input type='number' value='1' id='cantidadProducto'></td>
                        </tr>
                ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen articulos</div>";
        }
        return $html;
    }

    public static function buscarArticulosPedido(){
        
        $query = self::buscarArticulos();   
        //var_dump($query);
        $html = "";
        //return $query;
        if(count($query) != 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                
                $stock = self::obtenerStockArticuloAlmacen($value['CodigoArticulo']);

                $span = "";
                if($stock[0] > 20){
                    $span = "<span class='inline-flex items-center rounded-full bg-green-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[0],2)."</span>";
                }
                if( $stock[0] <= 0){
                    $span = "<span class='inline-flex items-center rounded-full bg-gray-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[0],2)."</span>";
                }
                if($stock[0] > 5 && $stock[0] <=20){
                    $span = "<span class='inline-flex items-center rounded-full bg-yellow-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[0],2)."</span>";
                }
                if($stock[0] > 0 && $stock[0] <=5){
                    $span = "<span class='inline-flex items-center rounded-full bg-red-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[0],2)."</span>";
                }

                $spanr = "";
                if($stock[1] > 20){
                    $spanr = "<span class='inline-flex items-center rounded-full bg-green-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[1],2)."</span>";
                }
                if( $stock[1] <= 0){
                    $spanr = "<span class='inline-flex items-center rounded-full bg-gray-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[1],2)."</span>";
                }
                if($stock[1] > 5 && $stock[1] <=20){
                    $spanr = "<span class='inline-flex items-center rounded-full bg-yellow-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[1],2)."</span>";
                }
                if($stock[1] > 0 && $stock[1] <=5){
                    $spanr = "<span class='inline-flex items-center rounded-full bg-red-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[1],2)."</span>";
                }

                $spanp = "";
                if($stock[2] > 20){
                    $spanp = "<span class='inline-flex items-center rounded-full bg-green-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[2],2)."</span>";
                }
                if( $stock[2] <= 0){
                    $spanp = "<span class='inline-flex items-center rounded-full bg-gray-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[2],2)."</span>";
                }
                if($stock[2] > 5 && $stock[2] <=20){
                    $spanp = "<span class='inline-flex items-center rounded-full bg-yellow-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[2],2)."</span>";
                }
                if($stock[2] > 0 && $stock[2] <=5){
                    $spanp = "<span class='inline-flex items-center rounded-full bg-red-600 px-2 py-1 text-sm font-semibold text-white'>".round($stock[2],2)."</span>";
                }


                $html .="
                        <tr>
                        <td><a>".$value['CodigoArticulo']."</a></td>
                        <td>".$value['DescripcionArticulo']." ".$span." ".$spanr." ".$spanp."</td>
                        <td>";
                //if($stock >= 1){
                    $html .= "<button class='btn ' id='btnArticulo' onclick='rellenarListadoArticuloPedido(".$value.");guardarUltimoProceso2()'><i class='fas fa-plus-square'></i>
                        </button></td>
                        <td><input type='number' value='1' id='cantidadProducto|".$value['CodigoArticulo']."'>";                    
                   // if($stock <= 0) $html .="disabled>";
                //}


                $html .="</td>
                        </tr>
                ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen articulos</div>";
        }
        return $html;
    }

    public static function buscarArticulo($codigoArticulo, $codigoCliente){
        $query = Articulo::selectRaw("Articulos.GrupoIva AS GI,*, ISNULL((SELECT [%RecargoEquivalencia] FROM TiposIva 
        WHERE TiposIva.CodigoTerritorio = ClientesConta.CodigoTerritorio and TiposIva.CodigoIva = 
        CASE Clientes.IndicadorIva
        WHEN 'I' THEN (SELECT TOP 1 CodigoIvaSinRecargo FROM GrupoIVA WHERE GrupoIVA.GrupoIva = Articulos.GrupoIva AND FechaInicio <= GETDATE() ORDER BY GrupoIva.FechaInicio DESC)
        WHEN 'R' THEN (SELECT TOP 1 CodigoIvaConRecargo FROM GrupoIVA WHERE GrupoIVA.GrupoIva = Articulos.GrupoIva AND FechaInicio <= GETDATE() ORDER BY GrupoIva.FechaInicio DESC)
        END),0) AS Recargo")        
        ->leftJoin('ClientesConta', function($join){
            $join->on('ClientesConta.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
        })
        ->leftJoin('Clientes', function($join){
            $join->on('Clientes.CodigoEmpresa', '=', 'ClientesConta.CodigoEmpresa');
            $join->on('Clientes.CodigoCliente', '=', 'ClientesConta.CodigoClienteProveedor');
        })
        ->where('ClientesConta.ClienteOProveedor', '=', 'C')
        ->where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('ClientesConta.CodigoClienteProveedor', '=', $codigoCliente)
        ->where('Articulos.CodigoArticulo', '=', $codigoArticulo)
        ->get();
        //$query = Articulo::where('CodigoArticulo','=',$codigoArticulo)->get();

        if(!isset($query[0])){            
            $query = Articulo::selectRaw("Articulos.GrupoIva AS GI,*, 0 AS Recargo")
            ->where('Articulos.CodigoArticulo', '=', $codigoArticulo)
            ->get();            
        }

        return $query;
    }

    public static function allArticulos(){
        $query = Articulo::select('CodigoArticulo', 'DescripcionArticulo', 'PrecioVenta')->get();
        return $query;
    }

    public static function comprobarTratamientoPartidas (){
        $query = Articulo::select('TratamientoPartidas')
            ->where('CodigoEmpresa',\session('codigoEmpresa'))
            ->where('CodigoArticulo',$_POST['codigoArticulo'])->get();
        return $query;
    }

    public static function obtenerPartida(){
        $query = DB::table('AcumuladoStock')->select('Partida','FechaCaducidad')
            ->where('Periodo',99)
            ->where('CodigoAlmacen','0')
            ->where('CodigoEmpresa',\session('codigoEmpresa'))
            ->where('Ejercicio',date('Y'))
            ->where('UnidadSaldo','>',0)
            ->where('FechaCaducidad','<>',null)
            ->where('CodigoArticulo',$_POST['codigoArticulo'])
            ->orderByDesc('FechaCaducidad')->get();

        return $query;
    }

    public static function obtenerStockArticuloAlmacen($idArticulo){
        $query = DB::table('AcumuladoStock')->selectRaw('sum(UnidadSaldo) as stock')            
            ->where('CodigoEmpresa','=', session('codigoEmpresa'))
            ->where('CodigoAlmacen', '0')
            ->where('CodigoArticulo',$idArticulo)
            ->where('Periodo',99)
            ->where('Ejercicio',date('Y'))->get();
            $stock = $query[0]->stock;
            $query2 = DB::table('AcumuladoPendientes')->selectRaw('sum(PendienteServirTipo_) as pendienteServir')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('CodigoAlmacen','0')
                ->where('CodigoArticulo',$idArticulo)->get();
            $pendienteServir = $query2[0]->pendienteServir;
            $stockVirtual = $stock-$pendienteServir;

            $query3  = DB::table('AcumuladoPendientes')
            ->select('PendienteRecibir','StockReservado')
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
            ->where('CodigoAlmacen','0')
            ->where('CodigoArticulo',$idArticulo)
            ->get();

            if(count($query3)>0){
            $pendiente = $query3[0]->PendienteRecibir + 0;
            $reserva = $query3[0]->StockReservado + 0;
            } else {
                $pendiente = 0;
                $reserva = 0;
            }
            
            $mandar = array($reserva, $stockVirtual, $pendiente);
            return $mandar;
    }

    public static function datosClientePedido(){
        $query = DB::table('Clientes')       
        ->where('IdCliente', '=', $_POST['cliente'])
        ->get();        
        return $query;
    }

    public static function findArticulo($articulo){
        $query = Articulo::select('CodigoArticulo', 'DescripcionArticulo')
        ->where('CodigoArticulo', '=', $articulo)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return $query[0]->CodigoArticulo.'-'.$query[0]->DescripcionArticulo;
    }

    public static function findFamilia($codigoFamilia){
        $query = DB::table('VFamilias')
        ->select('CodigoFamilia', 'Descripcion')
        ->where('CodigoFamilia', '=', $codigoFamilia)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return $query[0]->CodigoFamilia.'-'.$query[0]->Descripcion;
    }
    
    public static function comprobarTarifas(){ 

        $datos = new Dato();
        
        $codigoFamilia = 0;
        $codigoSubFamilia = 0;
        $marca = 0;
        $precio = 0;  
        $cliente_TarifaPrecio = 0;
        $cliente_TarifaDescuento = 0;
        $articuloCliente_PrecioOferta = 0;    
        $familiaPertenece = Articulo::select('CodigoFamilia', 'CodigoSubFamilia', 'MarcaProducto', 'PrecioVenta')
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'] )
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        if(count($familiaPertenece)>0){
            $codigoFamilia = $familiaPertenece[0]['CodigoFamilia'];
            $codigoSubFamilia = $familiaPertenece[0]['CodigoSubFamilia'];
            $precio = $familiaPertenece[0]['PrecioVenta'];
            $marca = $familiaPertenece[0]['MarcaProducto'];
        }    

        $clientes = DB::table('Clientes')
        ->select('TarifaPrecio', 'TarifaDescuento')
        ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get(); 

        if(count($clientes)>0){
            $cliente_TarifaPrecio = $clientes[0]->TarifaPrecio;
            $cliente_TarifaDescuento  = $clientes[0]->TarifaDescuento;
        }

        $articulosCliente = DB::table('ArticuloCliente')
        ->select('PrecioOferta', '%Descuento AS Descuento')
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        ->where('Automatico', '=', 0)
        // ->where('FechaInicio', '>', date('Y-m-d'))
        // ->where('FechaFin', '<', date('Y-m-d'))
        ->get();

        $articuloCliente_PrecioOferta = 0;
        $articuloCliente_Descuento = 0;


        if(count($articulosCliente)>0){            

            $articuloCliente_PrecioOferta = $articulosCliente[0]->PrecioOferta;
            $articuloCliente_Descuento = $articulosCliente[0]->Descuento;
        }

        $descuento1CondicionesEspeciales =0;
        $descuento2CondicionesEspeciales =0;

        
        $descuentosEspeciales = DB::table('LcCondicionesEspeciales')
        ->select('%Descuento as Descuento1', '%Descuento2 as Descuento2')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->whereRaw("(CodigoArticulo = '".$_POST['CodigoArticulo']."' Or CodigoArticulo = "."''".")")
        ->whereRaw("(CodigoCliente = '".$_POST['CodigoCliente']."' Or CodigoCliente = "."''".")")        
        ->whereRaw("(CodigoFamilia = '$codigoFamilia' OR CodigoFamilia = "."''".")")
        ->whereRaw("(CodigoSubFamilia = '$codigoSubFamilia' OR CodigoSubFamilia = "."''".")")
        ->where('FechaInicio', '<=', date('Y-m-d'))
        ->whereRaw("(FechaFinalLc >= ".date('Y-m-d')." OR FechaFinalLc = "."''".")")
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        //             ->Orwhere('CodigoArticulo', '=', '');
        // })
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        //             ->Orwhere('CodigoCliente', '=', '');
        // })
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoFamilia', '=', $codigoFamilia)
        //             ->Orwhere('CodigoFamilia', '=', '');
        // })
        // ->where(function ($quiery){            
        //     $quiery ->where('CodigoSubFamilia', '=', $codigoSubFamilia)
        //             ->Orwhere('CodigoSubFamilia', '=', '');
        // })                
        // ->where(function ($quiery){
        //     $quiery ->where('FechaFinalLc', '=', date('Y-m-d'))
        //             ->Orwhere('FechaFinalLc', '=', '');
        // })
        ->get();

        if(count($descuentosEspeciales)>0){
            $descuento1CondicionesEspeciales = $descuentosEspeciales[0]->Descuento1;
            $descuento2CondicionesEspeciales = $descuentosEspeciales[0]->Descuento2; 
        }

        $tarifaPrecio = DB::table('TarifaPrecio')
        ->select('Precio1 AS Precio')
        ->where('CodigoEmpresa', '=', session('CodigoEmpresa'))
        ->where('StatusActivo', '=', -1)
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        ->where('Tarifa', '=', $cliente_TarifaPrecio)
        ->where('FechaInicio', '<=', date('Y-m-d'))
        ->where('FechaFinal', '>=', date('Y-m-d'))
        ->get();
        
        $tarifaPrecio_Precio = 0;

        if(count($tarifaPrecio)>0){
            $tarifaPrecio_Precio = $tarifaPrecio[0]->Precio;            
        }
        

        if($articuloCliente_PrecioOferta != '' || $articuloCliente_PrecioOferta != "0"){
            $datos->precioVenta = $articuloCliente_PrecioOferta;
            $datos->descr = "articuloCliente";
        }else{
            if($tarifaPrecio_Precio != 0 || $tarifaPrecio_Precio != ''){
                $datos->precioVenta = $tarifaPrecio_Precio;
                $datos->descr = "precioArticulo";
            }else{
                $datos->precioVenta = $precio;
                $datos->descr = "precioVenta";
            }
        }

        $datos->Descuento1 = 0;
        $datos->Descuento2 = 0;

        if($articuloCliente_Descuento != '' || $articuloCliente_Descuento != "0"){
            $datos->Descuento1 = $articuloCliente_Descuento;

        }else{

            if($descuento1CondicionesEspeciales != '' || $descuento1CondicionesEspeciales != "0" || $descuento2CondicionesEspeciales != '' || $descuento2CondicionesEspeciales != "0" ){
                $datos->Descuento1 = $descuento1CondicionesEspeciales;
                $datos->Descuento2 = $descuento2CondicionesEspeciales;
            }else{

                $tarifaDescuento = DB::table('TarifaDescuento')
                ->select('%Descuento1 as Descuento')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('StatusActivo', '=', -1)
                ->where('CodigoFamilia', '=', $codigoFamilia)
                ->where(function ($quiery){
                    $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                            ->Orwhere('CodigoArticulo', '=', '');
                })
                ->where('Tarifa', '=', $cliente_TarifaDescuento)
                ->where('FechaInicio', '<=', date('Y-m-d'))
                ->where('FechaFinal', '>=', date('Y-m-d'))
                ->get();

                if(count($tarifaDescuento)>0){
                    $tarifaDescuento_Descuento = $tarifaDescuento[0]->Descuento;
                    $datos->Descuento1 = $tarifaDescuento_Descuento;
                    $datos->Descuento2 = 0;
                }else{
                    $tarifaDescuento2 = DB::table('TarifaDescuento')
                    ->select('%Descuento1 as Descuento')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                    ->where('StatusActivo', '=', -1)
                    ->where('CodigoFamilia', '=', $codigoFamilia)
                    ->where(function ($quiery){
                        $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                                ->Orwhere('CodigoArticulo', '=', '');
                    })
                    ->where('Tarifa', '=', $cliente_TarifaDescuento)
                    // ->where('FechaInicio', '>', date('Y-m-d'))
                    // ->where('FechaFinal', '<', date('Y-m-d'))
                    ->orderBy('CodigoEmpresa', 'desc')
                    ->orderBy('CodigoFamilia', 'desc')
                    ->orderBy('CodigoArticulo', 'desc')
                    ->orderBy('Tarifa', 'desc')
                    ->orderBy('FechaInicio', 'desc')
                    ->get();

                    if(count($tarifaDescuento2)>0){
                        $tarifaDescuento_Descuento = $tarifaDescuento2[0]->Descuento;
                        $datos->Descuento1 = $tarifaDescuento_Descuento;
                        $datos->Descuento2 = 0;
                    }else{

                        $tarifaDescuento3 = DB::table('TarifaDescuento')
                        ->select('%Descuento1 as Descuento')
                        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('StatusActivo', '=', 0)
                        ->where('CodigoFamilia', '=', $codigoFamilia)
                        //->where('CodigoSubFamilia', '=', $codigoSubFamilia)
                        //->where('VCodigoMarca', '=', $marca)
                        ->where(function ($quiery){
                            $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                                    ->Orwhere('CodigoArticulo', '=', '');
                        })
                        ->where('Tarifa', '=', $cliente_TarifaDescuento)
                        ->where('FechaInicio', '<=', date('Y-m-d'))
                        ->where('FechaFinal', '>=', date('Y-m-d'))
                        ->get();

                        if(count($tarifaDescuento3)>0){
                            $tarifaDescuento_Descuento = $tarifaDescuento3[0]->Descuento;
                            $datos->Descuento1 = $tarifaDescuento_Descuento;
                            $datos->Descuento2 = 0;
                        }else{

                            $tarifaDescuento4 = DB::table('TarifaDescuento')
                            ->select('%Descuento1 as Descuento')
                            ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                            ->where('StatusActivo', '=', 0)
                            ->where('CodigoFamilia', '=', $codigoFamilia)
                            //->where('CodigoSubFamilia', '=', $codigoSubFamilia)
                            //->where('VCodigoMarca', '=', $marca)
                            ->where(function ($quiery){
                                $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                                        ->Orwhere('CodigoArticulo', '=', '');
                            })
                            ->where('Tarifa', '=', $cliente_TarifaDescuento)
                            ->where('FechaInicio', '<=', date('Y-m-d'))
                            ->where('FechaFinal', '>=', date('Y-m-d'))
                            ->get();

                            if(count($tarifaDescuento4)>0){
                                $tarifaDescuento_Descuento = $tarifaDescuento4[0]->Descuento;
                                $datos->Descuento1 = $tarifaDescuento_Descuento;
                                $datos->Descuento2 = 0;
                            }else{

                            }
                        }
                    }
                }

            }
        }

        return $datos;                

    }

    public static function comprobarTarifasStandar(){        
        $datos = new Dato();
        
        $codigoFamilia = 0;
        $codigoSubFamilia = 0;
        $precio = 0;  
        $cliente_TarifaPrecio = 0;
        $cliente_TarifaDescuento = 0;
        $articuloCliente_PrecioOferta = 0;    
        $familiaPertenece = Articulo::select('CodigoFamilia', 'CodigoSubFamilia', 'PrecioVenta')
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'] )
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        if(count($familiaPertenece)>0){
            $codigoFamilia = $familiaPertenece[0]['CodigoFamilia'];
            $codigoSubFamilia = $familiaPertenece[0]['CodigoSubFamila'];
            $precio = $familiaPertenece[0]['PrecioVenta'];
        }    

        $clientes = DB::table('Clientes')
        ->select('TarifaPrecio', 'TarifaDescuento')
        ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get(); 

        if(count($clientes)>0){
            $cliente_TarifaPrecio = $clientes[0]->TarifaPrecio;
            $cliente_TarifaDescuento  = $clientes[0]->TarifaDescuento;
        }

        $articulosCliente = DB::table('ArticuloCliente')
        ->select('PrecioOferta', '%Descuento AS Descuento')
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        ->where('Automatico', '=', 0)
        // ->where('FechaInicio', '>', date('Y-m-d'))
        // ->where('FechaFin', '<', date('Y-m-d'))
        ->get();

        $articuloCliente_PrecioOferta = 0;
        $articuloCliente_Descuento = 0;


        if(count($articulosCliente)>0){           

            $articuloCliente_PrecioOferta = $articulosCliente[0]->PrecioOferta;
            $articuloCliente_Descuento = $articulosCliente[0]->Descuento;
        }

        $descuento1CondicionesEspeciales =0;
        $descuento2CondicionesEspeciales =0;

        
        $descuentosEspeciales = DB::table('LcCondicionesEspeciales')
        ->select('%Descuento as Descuento1', '%Descuento2 as Descuento2')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->whereRaw("(CodigoArticulo = '".$_POST['CodigoArticulo']."' Or CodigoArticulo = "."''".")")
        ->whereRaw("(CodigoCliente = '".$_POST['CodigoCliente']."' Or CodigoCliente = "."''".")")        
        ->whereRaw("(CodigoFamilia = '$codigoFamilia' OR CodigoFamilia = "."''".")")
        ->whereRaw("(CodigoSubFamilia = '$codigoSubFamilia' OR CodigoSubFamilia = "."''".")")
        ->where('FechaInicio', '>', date('Y-m-d'))
        ->whereRaw("(FechaFinalLc = ".date('Y-m-d')." OR FechaFinalLc = "."''".")")
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        //             ->Orwhere('CodigoArticulo', '=', '');
        // })
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoCliente', '=', $_POST['CodigoCliente'])
        //             ->Orwhere('CodigoCliente', '=', '');
        // })
        // ->where(function ($quiery){
        //     $quiery ->where('CodigoFamilia', '=', $codigoFamilia)
        //             ->Orwhere('CodigoFamilia', '=', '');
        // })
        // ->where(function ($quiery){            
        //     $quiery ->where('CodigoSubFamilia', '=', $codigoSubFamilia)
        //             ->Orwhere('CodigoSubFamilia', '=', '');
        // })                
        // ->where(function ($quiery){
        //     $quiery ->where('FechaFinalLc', '=', date('Y-m-d'))
        //             ->Orwhere('FechaFinalLc', '=', '');
        // })
        ->get();

        if(count($descuentosEspeciales)>0){
            $descuento1CondicionesEspeciales = $descuentosEspeciales[0]->Descuento1;
            $descuento2CondicionesEspeciales = $descuentosEspeciales[0]->Descuento2; 
        }

        $tarifaPrecio = DB::table('TarifaPrecio')
        ->select('Precio1 AS Precio')
        ->where('CodigoEmpresa', '=', session('CodigoEmpresa'))
        ->where('StatusActivo', '=', -1)
        ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
        ->where('Tarifa', '=', $cliente_TarifaPrecio)
        ->where('FechaInicio', '>', date('Y-m-d'))
        ->where('FechaFinal', '<', date('Y-m-d'))
        ->get();
        
        $tarifaPrecio_Precio = 0;

        if(count($tarifaPrecio)>0){
            $tarifaPrecio_Precio = $tarifaPrecio[0]->Precio;            
        }
        

        if($articuloCliente_PrecioOferta != '' || $articuloCliente_PrecioOferta != "0"){
            $datos->precioVenta = $articuloCliente_PrecioOferta;
            $datos->descr = "articuloCliente";
        }else{
            if($tarifaPrecio_Precio != 0 || $tarifaPrecio_Precio != ''){
                $datos->precioVenta = $tarifaPrecio_Precio;
                $datos->descr = "precioArticulo";
            }else{
                $datos->precioVenta = $precio;
                $datos->descr = "precioVenta";
            }
        }

        $datos->Descuento1 = 0;
        $datos->Descuento2 = 0;

        if($articuloCliente_Descuento != '' || $articuloCliente_Descuento != "0"){
            $datos->Descuento1 = $articuloCliente_Descuento;

        }else{

            if($descuento1CondicionesEspeciales != '' || $descuento1CondicionesEspeciales != "0" || $descuento2CondicionesEspeciales != '' || $descuento2CondicionesEspeciales != "0" ){
                $datos->Descuento1 = $descuento1CondicionesEspeciales;
                $datos->Descuento2 = $descuento2CondicionesEspeciales;
            }else{

                $tarifaDescuento = DB::table('TarifaDescuento')
                ->select('%Descuento1 as Descuento')
                ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('StatusActivo', '=', -1)
                ->where('CodigoFamilia', '=', $codigoFamilia)
                ->where(function ($quiery){
                    $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                            ->Orwhere('CodigoArticulo', '=', '');
                })
                ->where('Tarifa', '=', $cliente_TarifaDescuento)
                ->where('FechaInicio', '>', date('Y-m-d'))
                ->where('FechaFinal', '<', date('Y-m-d'))
                ->get();

                if(count($tarifaDescuento)>0){
                    $tarifaDescuento_Descuento = $tarifaDescuento[0]->Descuento;
                    $datos->Descuento1 = $tarifaDescuento_Descuento;
                    $datos->Descuento2 = 0;
                }else{
                    $tarifaDescuento2 = DB::table('TarifaDescuento')
                    ->select('%Descuento1 as Descuento')
                    ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
                    //->where('StatusActivo', '=', -1)
                    ->where('CodigoFamilia', '=', $codigoFamilia)
                    ->where(function ($quiery){
                        $quiery ->where('CodigoArticulo', '=', $_POST['CodigoArticulo'])
                                ->Orwhere('CodigoArticulo', '=', '');
                    })
                    ->where('Tarifa', '=', $cliente_TarifaDescuento)
                    ->where('FechaInicio', '>', date('Y-m-d'))
                    ->where('FechaFinal', '<', date('Y-m-d'))
                    ->orderBy('CodigoEmpresa', 'desc')
                    ->orderBy('CodigoFamilia', 'desc')
                    ->orderBy('CodigoArticulo', 'desc')
                    ->orderBy('Tarifa', 'desc')
                    ->orderBy('FechaInicio', 'desc')
                    ->get();

                    if(count($tarifaDescuento2)>0){
                        $tarifaDescuento_Descuento = $tarifaDescuento2[0]->Descuento;
                        $datos->Descuento1 = $tarifaDescuento_Descuento;
                        $datos->Descuento2 = 0;
                    }
                }

            }
        }

        return $datos;                

    }

    public static function datos(){
        $query = DB::table('Articulos')
        ->select('*')
        ->where('IdArticulo', '=', $_POST['IdArticulo'])
        ->get();

       // $bin2 = utf8_decode($firma);
       //$query[0]->ImagenExt = base64_encode($query[0]->ImagenExt);

        return $query;
    }


    public static function tarifario(){
        $query = 'hola';
        //DB::table('TarifaPrecio')
        // ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        // ->get();

        return view('comisionistas.stock.tarifario')->with('tarifario', $query);
    }

    

    public static function ivayrecargo(){
        $query = Articulo::selectRaw("Articulos.GrupoIva AS GI, ISNULL((SELECT [%RecargoEquivalencia] FROM TiposIva 
        WHERE TiposIva.CodigoTerritorio = ClientesConta.CodigoTerritorio and TiposIva.CodigoIva = 
        CASE Clientes.IndicadorIva
        WHEN 'I' THEN (SELECT TOP 1 CodigoIvaSinRecargo FROM GrupoIVA WHERE GrupoIVA.GrupoIva = Articulos.GrupoIva AND FechaInicio <= GETDATE() ORDER BY GrupoIva.FechaInicio DESC)
        WHEN 'R' THEN (SELECT TOP 1 CodigoIvaConRecargo FROM GrupoIVA WHERE GrupoIVA.GrupoIva = Articulos.GrupoIva AND FechaInicio <= GETDATE() ORDER BY GrupoIva.FechaInicio DESC)
        END),0) AS Recargo")        
        ->leftJoin('ClientesConta', function($join){
            $join->on('ClientesConta.CodigoEmpresa', '=', 'Articulos.CodigoEmpresa');
        })
        ->leftJoin('Clientes', function($join){
            $join->on('Clientes.CodigoEmpresa', '=', 'ClientesConta.CodigoEmpresa');
            $join->on('Clientes.CodigoCliente', '=', 'ClientesConta.CodigoClienteProveedor');
        })
        ->where('ClientesConta.ClienteOProveedor', '=', 'C')
        ->where('Articulos.CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('ClientesConta.CodigoClienteProveedor', '=', $_POST['cliente'])
        ->where('Articulos.CodigoArticulo', '=', $_POST['codigoProducto'])
        ->get();
        //$query = Articulo::where('CodigoArticulo','=',$codigoArticulo)->get();

        if(!isset($query[0])){
            
            $query[0] = ['GI'=> 0.21, 'Recargo'=> ".0000000000"];
            return $query;
        }

        switch ($query[0]['GI']) {
            case 1:
                $query[0]['GI'] = 0.21;
                break;
            case 2:
                $query[0]['GI'] = 0.10;
                break;
            case 3:
                $query[0]['GI'] = 0.05;
                break;
        }

        return $query;
    }

    
    public static function datosArticulo(){
        $query = Articulo::where('IdArticulo', '=', $_POST['id'])->get();

        return $query[0];
    }
    
}
