<?php

namespace App\Http\Controllers;

use App\Models\prescriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DateTime;
use Illuminate\Support\Facades\Date;

class InformesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //return view('prescriptores.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    public function informes1(){
        return view('informes.1');
    }
    public function informes2(){
        return view('informes.2');
    }
    public function informes3(){
        return view('informes.3');
    }
    public function informes4(){
        return view('informes.4');
    }

    public static function ejerciciosTrabajo(){
        if(session('tipo') != 5){
            $anio = DB::table('CabeceraAlbaranCliente')
            ->select('EjercicioAlbaran')
            ->where('CodigoComisionista', '=', session('codigoComisionista'))
            ->groupBy('EjercicioAlbaran')
            ->orderBy('EjercicioAlbaran','desc')
            ->get();
        }else{
            $anio = DB::table('CabeceraAlbaranCliente')
            ->select('EjercicioAlbaran')
            //->where('CodigoComisionista', '=', session('codigoComisionista'))
            ->groupBy('EjercicioAlbaran')
            ->orderBy('EjercicioAlbaran','desc')
            ->get();
        }

        
        return $anio;
    }
    
    public static function prescriptores(){       
            $datos = $_POST['datos'];            
            $prescriptores = DB::table('Comisionistas')
            ->select('CodigoComisionista', 'Comisionista')
            ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
            ->where('Comisionista', 'like', '%'.$datos.'%')
            ->orWhere('CodigoComisionista','LIKE',"%".$datos."%")
            ->get();
            return $prescriptores;
    }

    Public static function clientes(){
        $datos = $_POST['datos'];
        $clientes = DB::table('Clientes')
        ->select('Clientes.CodigoCliente', 'Clientes.RazonSocial')
        ->join('Comisionistas',function ($join){
            $join->on('Comisionistas.CodigoComisionista', '=', 'Clientes.CodigoComisionista');
            $join->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));
        })
        //->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
        ->where('Clientes.RazonSocial', 'like', '%'.$datos.'%')
        ->orWhere('Clientes.CodigoCliente','LIKE',"%".$datos."%")
        ->limit(200)
        ->get();
        return $clientes;
    }

    public static function productos(){
        $datos = $_POST['datos'];
        $productos = DB::table('Articulos')
        ->select('CodigoArticulo', 'DescripcionArticulo')
        ->where('CodigoEmpresa', '=', '1')
        ->where('DescripcionArticulo', 'like', '%'.$datos.'%')
        ->orwhere('CodigoArticulo', 'like', '%'.$datos.'%')
        ->get();
        return $productos;
    }

    public static function familia(){       
        $familia = DB::table('Familias')
        ->select('CodigoFamilia', 'CodigoSubfamilia', 'Descripcion')
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->where('CodigoSubfamilia', '=', '**********')
        ->orderBy('CodigoFamilia', 'asc')        
        ->get();
        return $familia;
    }

    public static function inforprescriptores(){
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $dato = $_POST['dato'];
        $ejercio = $_POST['ejercicio'];
        
        $fecha = date('Y-m-d');
        //return $ejercio;

        if($codigo == ''){
            if($ejercio == 1){
            
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P15D'));
                $fecha2 = $date2->format('Y-m-d');                                  
                
            }else if($ejercio == 2){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P1M'));
                $fecha2 = $date2->format('Y-m-d');
                
    
            }else if($ejercio == 3){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P3M'));
                $fecha2 = $date2->format('Y-m-d');
                
    
            }else if($ejercio == 4){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P6M'));
                $fecha2 = $date2->format('Y-m-d');
               
    
            }else if($ejercio == 5){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P1Y'));
                $fecha2 = $date2->format('Y-m-d');
               
    
            }

            $query = DB::table('Clientes')
                ->selectRaw('Comisionistas.IdComisionista as guid, Clientes.CodigoComisionista as id, Comisionistas.Comisionista as nombre , COUNT(Clientes.CodigoCliente) as clientesTotal, 0 as clientesActivos,
                0 as clientesActivosPeriodo, 0 as primeraCompra, 0 as ultimaCompra, 0 as numeroDepedidos, 0 as sumaVentas ')
                ->join('Comisionistas', function($join){
                    $join->on('Clientes.CodigoComisionista' ,'=', 'Comisionistas.CodigoComisionista');
                })
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                        
                // ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('Clientes.CodigoComisionista','Comisionistas.Comisionista', 'Comisionistas.IdComisionista')
                ->orderBy('Clientes.CodigoComisionista', 'asc')                        
                ->get();
                
                //return $query;
    
                $query2 = DB::table('CabeceraAlbaranCliente')
                ->selectRaw('CodigoComisionista as id, count(*) as numeroDepedidos, Sum(ImporteLiquido) as sumaVentas  ')                          
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                         
                // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->whereBetween('FechaAlbaran', [$fecha2, $fecha])
                ->where('CabeceraAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('CodigoComisionista')
                ->get();
                
                $query3 = DB::table('Clientes')
                ->selectRaw('Clientes.CodigoComisionista as id, COUNT(Clientes.CodigoCliente) AS clientesActivos')
                //->where('VFechaPrescripcion', '>', $fecha)
                ->join('Comisionistas', function($join){
                    $join->on('Clientes.CodigoComisionista' ,'=', 'Comisionistas.CodigoComisionista');
                })               
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                        
                // ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('Clientes.CodigoComisionista')
                ->get();


                $query4 = DB::table('CabeceraAlbaranCliente')
                ->selectRaw('CabeceraAlbaranCliente.CodigoComisionista AS id, count(distinct(CabeceraAlbaranCliente.CodigoCliente)) AS clientesActivosPeriodo')
                ->join('Comisionistas', function($join){
                    $join->on('Comisionistas.CodigoComisionista', '=', 'CabeceraAlbaranCliente.CodigoComisionista');
                })
                ->whereBetween('FechaAlbaran', [$fecha2, $fecha]) 
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                        
                // ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('CabeceraAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('CabeceraAlbaranCliente.CodigoComisionista')
                ->get();                

                if(session('tipo') != 5){
                    $query5= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as ultimaCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').' and ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select max(FechaAlbaran) from CabeceraAlbaranCliente
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').'and codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }else{
                    $query5= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as ultimaCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select max(FechaAlbaran) from CabeceraAlbaranCliente
                    where codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }

                if(session('tipo') != 5){
                    $query6= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as primeraCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').' and ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select min(FechaAlbaran) from CabeceraAlbaranCliente
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').'and codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }else{
                    $query6= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as primeraCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select min(FechaAlbaran) from CabeceraAlbaranCliente
                    where codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }


                //return $query2;
                
                if($dato == 1){

                    foreach($query as $dat){
                        foreach($query2 as $activos){
                            if($dat->id == $activos->id){
                                $dat->numeroDepedidos = $activos->numeroDepedidos;
                                $dat->sumaVentas = round($activos->sumaVentas,2)."€";
                            }
                        }
                        foreach($query3 as $activos2){
                            if($dat->id == $activos2->id){
                                $dat->clientesActivos = $activos2->clientesActivos;                                
                            }
                        }
                        foreach($query4 as $activos3){
                            if($dat->id == $activos3->id){
                                $dat->clientesActivosPeriodo = $activos3->clientesActivosPeriodo;                                
                            }
                        }
                        foreach($query5 as $activos4){
                            if($dat->id == $activos4->id){
                                $dat->ultimaCompra = date("d-m-Y", strtotime($activos4->ultimaCompra));                                
                            }
                        }
                        foreach($query6 as $activos5){
                            if($dat->id == $activos5->id){
                                $dat->primeraCompra =  date("d-m-Y", strtotime($activos5->primeraCompra));                                
                            }
                        }
                    }
                    return $query;
    
                }else if($dato == 2){

                    foreach($query as $dat){
                        foreach($query2 as $activos){
                            if($dat->id == $activos->id){
                                $dat->numeroDepedidos = $activos->numeroDepedidos;
                                $dat->sumaVentas =  round($activos->sumaVentas,2)."€";
                            }
                        }
                        foreach($query3 as $activos2){
                            if($dat->id == $activos2->id){
                                $dat->clientesActivos = $activos2->clientesActivos;                                
                            }
                        }
                        foreach($query4 as $activos3){
                            if($dat->id == $activos3->id){
                                $dat->clientesActivosPeriodo = $dat->clientesTotal - $activos3->clientesActivosPeriodo;                                
                            }
                        }
                        foreach($query5 as $activos4){
                            if($dat->id == $activos4->id){
                                $dat->ultimaCompra = date("d-m-Y", strtotime($activos4->ultimaCompra));                                
                            }
                        }
                        foreach($query6 as $activos5){
                            if($dat->id == $activos5->id){
                                $dat->primeraCompra = date("d-m-Y", strtotime($activos5->primeraCompra));                                
                            }
                        }
                    }
                    return $query;
    
                }

        }else if($codigo != ''){
            if($ejercio == 1){
            
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P15D'));
                $fecha2 = $date2->format('Y-m-d');
    
 
            }else if($ejercio == 2){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P1M'));
                $fecha2 = $date2->format('Y-m-d');
    
                
    
            }else if($ejercio == 3){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P3M'));
                $fecha2 = $date2->format('Y-m-d');
    
                
    
            }else if($ejercio == 4){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P6M'));
                $fecha2 = $date2->format('Y-m-d');
    
               
    
            }else if($ejercio == 5){
    
                $date2 = new \DateTime($fecha);
                $date2->sub(new \DateInterval('P1Y'));
                $fecha2 = $date2->format('Y-m-d');
    
                
    
            }
            $query = DB::table('Clientes')
                ->selectRaw('Clientes.CodigoComisionista as id, Comisionistas.Comisionista as nombre , COUNT(Clientes.CodigoCliente) as clientesTotal, 0 as clientesActivos,
                0 as clientesActivosPeriodo, 0 as primeraCompra, 0 as ultimaCompra, 0 as numeroDepedidos, 0 as sumaVentas ')
                ->join('Comisionistas', function($join){
                    $join->on('Clientes.CodigoComisionista' ,'=', 'Comisionistas.CodigoComisionista');
                }) 
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                       
                // ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('Clientes.CodigoComisionista', '=', $codigo)
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('Clientes.CodigoComisionista','Comisionistas.Comisionista')
                ->orderBy('Clientes.CodigoComisionista', 'asc')                        
                ->get();
                
                //return $query;
    
                $query2 = DB::table('CabeceraAlbaranCliente')
                ->selectRaw('CodigoComisionista as id, count(*) as numeroDepedidos, Sum(ImporteLiquido) as sumaVentas  ')
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })                           
                ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('CabeceraAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->where('CabeceraAlbaranCliente.CodigoComisionista', '=', $codigo)
                ->whereBetween('FechaAlbaran', [$fecha2, $fecha])
                ->groupBy('CodigoComisionista')
                ->get();
                
                $query3 = DB::table('Clientes')
                ->selectRaw('Clientes.CodigoComisionista as id, COUNT(Clientes.CodigoCliente) AS clientesActivos')
                //->where('VFechaPrescripcion', '>', $fecha)
                ->where('Clientes.CodigoComisionista', '=', $codigo)
                ->where('Clientes.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->join('Comisionistas', function($join){
                    $join->on('Clientes.CodigoComisionista' ,'=', 'Comisionistas.CodigoComisionista');
                    $join->on('Clientes.CodigoEmpresa' ,'=', 'Comisionistas.CodigoEmpresa');
                })               
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })
                // ->where('Comisionistas.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->groupBy('Clientes.CodigoComisionista')
                ->get();

                $query4 = DB::table('CabeceraAlbaranCliente')
                ->selectRaw('CabeceraAlbaranCliente.CodigoComisionista AS id, count(distinct(CabeceraAlbaranCliente.CodigoCliente)) AS clientesActivosPeriodo')
                ->whereBetween('FechaAlbaran', [$fecha2, $fecha]) 
                ->when(session('tipo') != 5, function ($query) {
                    $query->where('CabeceraAlbaranCliente.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                })
                // ->where('CabeceraAlbaranCliente.CodigoJefeVenta_', '=', session('codigoComisionista'))
                ->where('CabeceraAlbaranCliente.CodigoEmpresa', '=', session('codigoEmpresa'))
                ->groupBy('CabeceraAlbaranCliente.CodigoComisionista')
                ->get();   

                if(session('tipo') != 5){
                    $query5= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as ultimaCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').' and ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select max(FechaAlbaran) from CabeceraAlbaranCliente
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').'and codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }else{
                    $query5= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as ultimaCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select max(FechaAlbaran) from CabeceraAlbaranCliente
                    where codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');
                }

                if(session('tipo') != 5){
                    $query6= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as primeraCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').' and ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select min(FechaAlbaran) from CabeceraAlbaranCliente
                    where Comisionistas.codigojefeventa_='.session('codigoComisionista').'and codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');          
                }else{
                    $query6= DB::select('select ca.CodigoComisionista as id,FechaAlbaran as primeraCompra  
                    from CabeceraAlbaranCliente as ca 
                    inner join Comisionistas on ca.codigoComisionista = Comisionistas.CodigoComisionista
                    where ca.codigoempresa='.session('codigoEmpresa').'
                    and FechaAlbaran=(select min(FechaAlbaran) from CabeceraAlbaranCliente
                    where codigoempresa='.session('codigoEmpresa').' and CodigoComisionista=ca.CodigoComisionista)
                    group by ca.CodigoComisionista,FechaAlbaran
                    order by ca.CodigoComisionista');                     
                }
                
                if($dato == 1){

                    foreach($query as $dat){
                        foreach($query2 as $activos){
                            if($dat->id == $activos->id){
                                $dat->numeroDepedidos = $activos->numeroDepedidos;
                                $dat->sumaVentas = round($activos->sumaVentas,2)."€";
                            }
                        }
                        foreach($query3 as $activos2){
                            if($dat->id == $activos2->id){
                                $dat->clientesActivos = $activos2->clientesActivos;                                
                            }
                        }
                        foreach($query4 as $activos3){
                            if($dat->id == $activos3->id){
                                $dat->clientesActivosPeriodo = $activos3->clientesActivosPeriodo;                                
                            }
                        }
                        foreach($query5 as $activos4){
                            if($dat->id == $activos4->id){
                                $dat->ultimaCompra = date("d-m-Y", strtotime($activos4->ultimaCompra));                                
                            }
                        }
                        foreach($query6 as $activos5){
                            if($dat->id == $activos5->id){
                                $dat->primeraCompra =  date("d-m-Y", strtotime($activos5->primeraCompra));                                
                            }
                        }
                    }
                    return $query;
    
                }else if($dato == 2){

                    foreach($query as $dat){
                        foreach($query2 as $activos){
                            if($dat->id == $activos->id){
                                $dat->numeroDepedidos = $activos->numeroDepedidos;
                                $dat->sumaVentas = round($activos->sumaVentas,2)."€";
                            }
                        }
                        foreach($query3 as $activos2){
                            if($dat->id == $activos2->id){
                                $dat->clientesActivos = $activos2->clientesActivos;                                
                            }
                        }
                        foreach($query4 as $activos3){
                            if($dat->id == $activos3->id){
                                $dat->clientesActivosPeriodo = $dat->clientesTotal - $activos3->clientesActivosPeriodo;                                
                            }
                        }
                        foreach($query5 as $activos4){
                            if($dat->id == $activos4->id){
                                $dat->ultimaCompra = date("d-m-Y", strtotime($activos4->ultimaCompra));                                
                            }
                        }
                        foreach($query6 as $activos5){
                            if($dat->id == $activos5->id){
                                $dat->primeraCompra = date("d-m-Y", strtotime($activos5->primeraCompra));                                
                            }
                        }
                    }
                    return $query;
    
                }
        }
    }


    public static function aniomes(){
        $codigo = $_POST['codigo'];
        $dato = $_POST['dato'];
        $ejercio = $_POST['ejercicio'];
        $agrupacion = $_POST['agrupacion'];


        if($agrupacion == 1){
            if($dato == 1){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'bruto')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })                               
                    ->where('anio', '=', $ejercio)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))                          
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'bruto')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'bruto')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })
                    ->where('anio', '=', $ejercio)
                    ->where('prescriptor', '=', $codigo)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))                       
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'bruto')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }
            if($dato == 2){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'base_Imponible')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })
                    ->where('anio', '=', $ejercio)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))           
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'base_Imponible')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'base_Imponible')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })
                    ->where('anio', '=', $ejercio)
                    ->where('prescriptor', '=', $codigo)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))   
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'base_Imponible')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }
            if($dato == 3){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'total')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })
                    ->where('anio', '=', $ejercio)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))           
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'total')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_prescriptor_referencia')
                    ->select('mes','anio','Comisionistas.Comisionista','prescriptor','comercial', 'total')
                    ->join('Comisionistas', function($join) {                
                        $join->on('ventas_mes_prescriptor_referencia.prescriptor', '=', 'Comisionistas.CodigoComisionista');                            
                        $join->on('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));                        
                    })
                    ->where('anio', '=', $ejercio)
                    ->where('prescriptor', '=', $codigo)
                    ->Where('ventas_mes_prescriptor_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))   
                    ->groupBy('prescriptor','Comisionistas.Comisionista','mes','anio','comercial', 'total')
                    ->orderBy('prescriptor', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }            
        }
        if($agrupacion == 2){
            if($dato == 1){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'bruto')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));
                        $query->orWhere ('comisionista', '=', session('codigoComisionista'));
                    })  
                    ->where('anio', '=', $ejercio)  
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))         
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'bruto')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->limit(6000)
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'bruto')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    //->where('comercial', '=', session('codigoComisionista'))                    
                    ->where('anio', '=', $ejercio)
                    ->where('cliente', '=', $codigo)
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))   
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'bruto')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }
            if($dato == 2){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'base_Imponible')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));
                        $query->orWhere ('comisionista', '=', session('codigoComisionista'));
                    })  
                    ->where('anio', '=', $ejercio)     
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))      
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'base_Imponible')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->limit(6000)
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'base_Imponible')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    //->where('comercial', '=', session('codigoComisionista'))
                    ->where('anio', '=', $ejercio)
                    ->where('cliente', '=', $codigo)
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))   
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'base_Imponible')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }
            if($dato == 3){
                if($codigo == null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'total')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    ->when(session('tipo') != 5, function ($query) {
                        $query->where('comercial', '=', session('codigoComisionista'));
                        $query->orWhere ('comisionista', '=', session('codigoComisionista'));
                    })                                                           
                    ->where('anio', '=', $ejercio) 
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))          
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'total')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->limit(6000)
                    ->get();
                    return $informe;
                }                
                if($codigo != null){
                    $informe = DB::table('ventas_mes_cliente_referencia')
                    ->select('mes','anio','RazonSocial','cliente','comercial', 'total')
                    ->join('Clientes', function($join) {                
                        $join->on('ventas_mes_cliente_referencia.cliente', '=', 'Clientes.CodigoCliente');                            
                        $join->on('ventas_mes_cliente_referencia.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                    })
                    //->where('comercial', '=', session('codigoComisionista'))
                    ->where('anio', '=', $ejercio)
                    ->where('cliente', '=', $codigo)
                    ->Where('ventas_mes_cliente_referencia.CodigoEmpresa', '=', session('codigoEmpresa'))   
                    ->groupBy('cliente','RazonSocial','mes','anio','comercial', 'total')
                    ->orderBy('cliente', 'asc')
                    ->orderBy('mes', 'asc')
                    ->orderBy('anio', 'asc')
                    ->get();
                    return $informe;
                }  
            }            
        }
    } 
    



    public static function ventaFecha(){
        $codigo = $_POST['codigo'];
        $dato = $_POST['dato'];
        $agrupacion = $_POST['agrupacion'];
        $forma = $_POST['forma'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        // por años 
        if($forma == 0){
            if($agrupacion == 1){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(importe_bruto) as bruto')                        
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoComisionista', '=', $codigo)                                           
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(base_imponible) as base')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })                        
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoComisionista', '=', $codigo)                         
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(importe_total) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoComisionista', '=', $codigo)                          
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(importe_bruto) as bruto')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                                           
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(base_imponible) as base')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                        
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoComisionista, sum(importe_total) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                        
                        ->groupBy('CodigoComisionista','anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(importe_bruto) as bruto')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoCliente', '=', $codigo)                                           
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')                        
                        ->get();
                        return $informe;
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(base_imponible) as base')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoCliente', '=', $codigo)                         
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(importe_total) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)
                        ->where('CodigoCliente', '=', $codigo)                          
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(importe_bruto) as bruto')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                                           
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(base_imponible) as base')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                        
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, CodigoCliente, sum(importe_total) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                            $query->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));                        
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fecha', '>=', $fechaInicio)                        
                        ->where('fecha', '<=', $fechaFin)                        
                        ->groupBy('CodigoCliente','anio')
                        ->orderBy('CodigoCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                }
            }
        }
        //por meses
        if($forma == 1){
            if($agrupacion == 1){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto' )
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                           
                        ->groupBy('anio', 'mes','Comisionistas.Comisionista')
                        ->orderBy('anio', 'asc')                                                
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                           
                        ->groupBy('anio', 'mes','Comisionistas.Comisionista')
                        ->orderBy('anio', 'asc')                                                
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                           
                        ->groupBy('anio', 'mes','Comisionistas.Comisionista')
                        ->orderBy('anio', 'asc')                        
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                  
                        ->groupBy('Comisionistas.Comisionista','mes','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                                                
                        ->orderBy('anio', 'asc')                        
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                       
                        ->groupBy('Comisionistas.Comisionista','mes','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                       
                        ->groupBy('Comisionistas.Comisionista','mes','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                       
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','mes','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','mes','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                      
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })                        
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','mes','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                          
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        }) 
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                        
                        ->groupBy('Clientes.RazonSocial','mes','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                            
                        ->groupBy('Clientes.RazonSocial','mes','anio')                      
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, mes, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                            
                        ->groupBy('Clientes.RazonSocial','mes','anio')                      
                        ->orderBy('Clientes.RazonSocial', 'asc')                           
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                }
            }
        }
        // por semanas
        if($forma == 2){
            if($agrupacion == 1){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                                                                                        
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                                                                                        
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                 
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                                                                                        
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                      
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                      
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                       
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                      
                        ->groupBy('Comisionistas.Comisionista','NumSemana','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio,NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                    
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio,NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')            
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                            
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio,NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                            
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                       
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio,NumSemana, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                            
                        ->groupBy('Clientes.RazonSocial','NumSemana','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                          
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                }
            }
        }
        //por día
        if($forma == 3){
            if($agrupacion == 1){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                             
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                             
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                 
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
                        ->where('ventas_netas_comisionables_materializada.CodigoComisionista', '=', $codigo)                                             
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                       
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_bruto) as bruto')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(base_imponible) as base')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                          
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Comisionistas.Comisionista AS CodigoComisionista, sum(importe_total) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'));                        
                        })
                        // ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))    
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                
                        ->groupBy('Comisionistas.Comisionista','fecha', 'anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                    
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo != null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                       
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))  
                        ->where('ventas_netas_comisionables_materializada.CodigoCliente', '=', $codigo)                                           
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                    
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                }
                if($codigo == null){
                    if($dato == 1){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(importe_bruto) as bruto')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                         
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($dato == 2){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(base_imponible) as base')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                         
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($dato == 3){
                        $informe = DB::table('ventas_netas_comisionables_materializada')
                        ->selectRaw('anio, fecha, Clientes.RazonSocial AS CodigoCliente, sum(importe_total) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('ventas_netas_comisionables_materializada.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                            });                        
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                        // })                          
                        ->whereBetween('fecha', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                                         
                        ->groupBy('Clientes.RazonSocial','fecha' , 'anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')               
                        ->orderBy('fecha', 'asc')
                        ->get();
                        return $informe;
                    }
                }
            }
        }

    }

    

    public static function ventaFechaFamilia(){
        $familiaOarticulo = null;
        // articulo = 0 familia = 1
        
        if($_POST['familia'] != 0){
            $familiaOarticulo = 1;
        }else if($_POST['articulos'] != ''){
            $familiaOarticulo = 0;
        } 
        $codigo = $_POST['codigo'];        
        $agrupacion = $_POST['agrupacion'];
        $forma = $_POST['forma'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        $familia = $_POST['familia'];
        $articulo = $_POST['articulos'];
        

        if($forma == 0){
            if($agrupacion == 1){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigoComisionista as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));
                        })    
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoArticulo', '=', $articulo)                                                                    
                        ->groupBy('CodigoComisionista', 'anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigoComisionista as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)                                                                                          
                        ->where('CodigoFamilia', '=', $familia)
                        ->groupBy('CodigoComisionista', 'anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigoComisionista as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoArticulo', '=', $articulo)                                                                    
                        ->where('CodigoComisionista', '=', $codigo)
                        ->groupBy('CodigoComisionista', 'anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigoComisionista as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)                                                                                          
                        ->where('CodigoFamilia', '=', $familia)
                        ->where('CodigoComisionista', '=', $codigo)
                        ->groupBy('CodigoComisionista', 'anio')
                        ->orderBy('CodigoComisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigodelCliente as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                            ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoArticulo', '=', $articulo)                                                                    
                        ->groupBy('CodigodelCliente', 'anio')
                        ->orderBy('CodigodelCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){

                        $informe = DB::table('informes3')
                        ->selectRaw('CodigodelCliente as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                            ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoFamilia', '=', $familia)                                                                    
                        ->groupBy('CodigodelCliente', 'anio')
                        ->orderBy('CodigodelCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;                      
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigodelCliente as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                            ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoArticulo', '=', $articulo)                                                                    
                        ->where('CodigodelCliente', '=', $codigo)                                                                    
                        ->groupBy('CodigodelCliente', 'anio')
                        ->orderBy('CodigodelCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('CodigodelCliente as cod, Anio as anio, SUM(Unidades) as total')
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                            ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'));
                        })
                        // ->where('CodigoJefeVenta_', '=', session('codigoComisionista'))
                        // ->Orwhere('CodigoComisionista', '=', session('codigoComisionista'))
                        ->where('fechaAlbaran', '>=', $fechaInicio)                        
                        ->where('fechaAlbaran', '<=', $fechaFin)
                        ->where('CodigoFamilia', '=', $familia)                                                                    
                        ->where('CodigodelCliente', '=', $codigo)                                                                    
                        ->groupBy('CodigodelCliente', 'anio')
                        ->orderBy('CodigodelCliente', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->get();
                        return $informe;  
                    }
                }
            }
        }
          
        if($forma == 1){
            if($agrupacion == 1){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Comisionistas.Comisionista as cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)   
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                 
                        ->groupBy('Comisionistas.Comisionista', 'anio', 'mes')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;                        
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Comisionistas.Comisionista as cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                    
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                 
                        ->groupBy('Comisionistas.Comisionista', 'anio', 'mes')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Comisionistas.Comisionista as cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)
                        ->where('CodigoComisionista', '=', $codigo)                                                                    
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                 
                        ->groupBy('Comisionistas.Comisionista', 'anio', 'mes')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Comisionistas.Comisionista as cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                    
                        ->where('CodigoComisionista', '=', $codigo)                                                                    
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                 
                        ->groupBy('Comisionistas.Comisionista', 'anio', 'mes')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw(' Clientes.RazonSocial AS cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )                                            
                        ->where('CodigoArticulo', '=', $articulo)         
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                           
                        ->groupBy('Clientes.RazonSocial', 'anio', 'mes')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($familiaOarticulo == 1){

                        $informe = DB::table('informes3')                        
                        ->selectRaw(' Clientes.RazonSocial AS cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia)  
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                  
                        ->groupBy('Clientes.RazonSocial', 'anio', 'mes')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;                       
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw(' Clientes.RazonSocial AS cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoArticulo', '=', $articulo)
                        ->where('informes3.CodigoCliente', '=', $codigo)
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                   
                        ->groupBy('Clientes.RazonSocial', 'anio', 'mes')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw(' Clientes.RazonSocial AS cod, Anio as anio, mes, SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia)
                        ->where('informes3.CodigoCliente', '=', $codigo)  
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                 
                        ->groupBy('Clientes.RazonSocial', 'anio', 'mes')
                        ->orderBy('Clientes.RazonSocial', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('mes', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
        }


        if($forma == 2){
            if($agrupacion == 1){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)      
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                          
                        ->groupBy('Comisionistas.Comisionista','anio','NumSemana')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                                                                                                                                                                                                     
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                          
                        ->groupBy('Comisionistas.Comisionista','anio','NumSemana')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;  
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)                                                                                                               
                        ->where('CodigoComisionista', '=', $codigo)                                                                                                               
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                          
                        ->groupBy('Comisionistas.Comisionista','anio','NumSemana')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;  
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                                                                                                                                                                                                     
                        ->where('CodigoComisionista', '=', $codigo)                                                                                                               
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                          
                        ->groupBy('Comisionistas.Comisionista','anio','NumSemana')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                         
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoArticulo', '=', $articulo)  
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                             
                        ->groupBy('Clientes.RazonSocial','anio','NumSemana')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){

                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia)
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                                
                        ->groupBy('Clientes.RazonSocial','anio','NumSemana')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;                      
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoArticulo', '=', $articulo)                                                                                                               
                        ->where('informes3.CodigoCliente', '=', $codigo) 
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Clientes.RazonSocial','anio','NumSemana')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, NumSemana, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia)                                                                                                               
                        ->where('informes3.CodigoCliente', '=', $codigo)   
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                             
                        ->groupBy('Clientes.RazonSocial','anio','NumSemana')
                        ->orderBy('Clientes.RazonSocial', 'asc')                        
                        ->orderBy('anio', 'asc')
                        ->orderBy('NumSemana', 'asc')
                        ->get();
                        return $informe;  
                    }
                }
            }
        }


        if($forma == 3){
            if($agrupacion == 1){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Comisionistas.Comisionista','fechaAlbaran','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                                                
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe; 

                        
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                                                               
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Comisionistas.Comisionista','fechaAlbaran','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                                           
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;   
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoArticulo', '=', $articulo)                                                                                                               
                        ->where('CodigoComisionista', '=', $codigo)                                                                                                               
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Comisionistas.Comisionista','fechaAlbaran','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                                                 
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;  
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Comisionistas.Comisionista as cod,  SUM(Unidades) as total')
                        ->join('Comisionistas', function($join) {                
                            $join->on('informes3.CodigoComisionista', '=', 'Comisionistas.CodigoComisionista');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Comisionistas.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'])
                        ->where('CodigoFamilia', '=', $familia)                                                                                                               
                        ->where('CodigoComisionista', '=', $codigo)                                                                                                               
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Comisionistas.Comisionista','fechaAlbaran','anio')
                        ->orderBy('Comisionistas.Comisionista', 'asc')                                                 
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe; 
                    }
                }
            }
            if($agrupacion == 2){
                if($codigo == null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoArticulo', '=', $articulo)   
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                            
                        ->groupBy('Clientes.RazonSocial','fechaAlbaran','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                                                
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){

                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia) 
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                                
                        ->groupBy('Clientes.RazonSocial','fechaAlbaran','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                                                
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;                     
                    }
                }
                if($codigo != null ){
                    if($familiaOarticulo == 0){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoArticulo', '=', $articulo)
                        ->where('CodigodelCliente', '=', $codigo)
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                                 
                        ->groupBy('Clientes.RazonSocial','fechaAlbaran','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                                                
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;
                    }
                    if($familiaOarticulo == 1){
                        $informe = DB::table('informes3')
                        ->selectRaw('Anio as anio, fechaAlbaran, Clientes.RazonSocial as cod,  SUM(Unidades) as total')
                        ->join('Clientes', function($join) {                
                            $join->on('informes3.CodigoCliente', '=', 'Clientes.CodigoCliente');                            
                            $join->on('informes3.CodigoEmpresa', '=', 'Clientes.CodigoEmpresa');                            
                        })
                        ->when(session('tipo') != 5, function ($query) {
                            $query->where(function ($quiery){
                                $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                                         ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                            });
                        })
                        // ->where(function ($quiery){
                        //     $quiery  ->where('informes3.CodigoJefeVenta_', '=', session('codigoComisionista'))
                        //              ->orWhere('informes3.CodigoComisionista', '=', session('codigoComisionista'));
                        // })     
                        ->whereBetween('fechaAlbaran', [$fechaInicio.' 00:00:00.000', $fechaFin.' 23:59:00.000'] )
                        ->where('CodigoFamilia', '=', $familia)                                                                                                               
                        ->where('CodigodelCliente', '=', $codigo)  
                        ->where('informes3.CodigoEmpresa', '=', session('codigoEmpresa'))                                                                                                               
                        ->groupBy('Clientes.RazonSocial','fechaAlbaran','anio')
                        ->orderBy('Clientes.RazonSocial', 'asc')                                                
                        ->orderBy('fechaAlbaran', 'asc')
                        ->get();
                        return $informe;   
                    }
                }
            }
        }
    
    }

    public static function ventaFechaMaps(){
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        $agrupacion = $_POST['agrupacion'];

        if($agrupacion == 1){

            $query = DB::table('ventas_netas_comisionables_materializada')
            ->select('Comisionistas.VLatitud', 'Comisionistas.VLongitud', 'ventas_netas_comisionables_materializada.CodigoComisionista', 'ventas_netas_comisionables_materializada.fecha')
            ->join('Comisionistas', function ($join){
                $join->on('Comisionistas.CodigoComisionista', '=', 'ventas_netas_comisionables_materializada.CodigoComisionista');            
                $join->on('Comisionistas.CodigoEmpresa', '=', 'ventas_netas_comisionables_materializada.CodigoEmpresa');            
            })
            ->when(session('tipo') != 5, function ($query) {
                $query->where(function ($quiery){
                    $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                             ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                });
            })
            // ->where(
            //     function ($query){
            //         $query ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
            //         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
            // })
            //->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
            ->where('fecha', '>=', $fechaInicio)                        
            ->where('fecha', '<=', $fechaFin)
            ->where('VLatitud', '<>', 0)
            ->where('VLongitud', '<>', 0)    
            ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))                   
            ->get();

            return $query;

        }

        if($agrupacion == 2){

            $query = DB::table('ventas_netas_comisionables_materializada')
            ->select('Clientes.VLatitud', 'Clientes.VLongitud', 'ventas_netas_comisionables_materializada.CodigoCliente', 'ventas_netas_comisionables_materializada.fecha')
            ->join('Clientes', function ($join){
                $join->on('Clientes.CodigoCliente', '=', 'ventas_netas_comisionables_materializada.CodigoCliente');            
                $join->on('Clientes.CodigoEmpresa', '=', 'ventas_netas_comisionables_materializada.CodigoEmpresa');            
            })
            ->when(session('tipo') != 5, function ($query) {
                $query->where(function ($quiery){
                    $quiery  ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
                             ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
                });
            })
            // ->where(
            //     function ($query){
            //         $query ->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
            //         ->orWhere('ventas_netas_comisionables_materializada.CodigoComisionista', '=', session('codigoComisionista'));
            // })
            //->where('ventas_netas_comisionables_materializada.CodigoJefeVenta_', '=', session('codigoComisionista'))
            ->where('fecha', '>=', $fechaInicio)                        
            ->where('fecha', '<=', $fechaFin)
            ->where('VLatitud', '<>', 0)
            ->where('VLongitud', '<>', 0)
            ->where('ventas_netas_comisionables_materializada.CodigoEmpresa', '=', session('codigoEmpresa'))
            ->orderBy('fecha', 'asc')                  
            ->get();

            return $query;
        }
    }
}
