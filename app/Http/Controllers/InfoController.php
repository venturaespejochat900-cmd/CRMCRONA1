<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Domicilio;
use App\Models\prescriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class InfoController extends Controller
{
    public static function datos(){

        $comisionista = session('codigoComisionista');
        $empresa = session('codigoEmpresa');
        $anioFechaActual = date("Y");
        $aniopasado = date("Y")-1;
        $aniopasado2 = date("Y")-2;
        $mesActual = date('m');
        $mesAnterior = date('m')-1; 
        $fecha_actual = date("Y-m-d");
        $fechaMesAnterior = date("Y-m-d",strtotime($fecha_actual."- 1 month"));
        $fechaAnioAnterior = date("Y-m-d",strtotime($fecha_actual."- 1 year"));
        $fechaSemanaAnterior = date("Y-m-d",strtotime($fecha_actual."- 7 day"));

        $datos = new stdClass();        
        $datos->comisionista=$comisionista;
        $datos->empresa=$empresa;
        $datos->anioFechaActual=$anioFechaActual;
        $datos->aniopasado=$aniopasado;
        $datos->aniopasado2=$aniopasado2;
        $datos->mesActual=$mesActual;
        $datos->mesAnterior=$mesAnterior;
        $datos->fecha_actual=$fecha_actual;
        $datos->fechaMesAnterior=$fechaMesAnterior;
        $datos->fechaAnioAnterior=$fechaAnioAnterior;
        $datos->fechaSemanaAnterior=$fechaSemanaAnterior;

        return $datos;
    }
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()    
    {
        $comisionista = session('codigoComisionista');
        $empresa = session('codigoEmpresa');
        $anioFechaActual = date("Y");
        $aniopasado = date("Y")-1;
        $aniopasado2 = date("Y")-2;
        $mesActual = date('m');
        $mesAnterior = date('m')-1; 
        $fecha_actual = date("Y-m-d");
        $fechaMesAnterior = date("Y-m-d",strtotime($fecha_actual."- 1 month"));
        $fechaAnioAnterior = date("Y-m-d",strtotime($fecha_actual."- 1 year"));
        $fechaSemanaAnterior = date("Y-m-d",strtotime($fecha_actual."- 7 day"));

            $clientesActivos = DB::select("SELECT Count(Clientes.CodigoCliente) AS activos from Clientes  
                                            INNER JOIN Comisionistas ON Clientes.CodigoComisionista = Comisionistas.Codigocomisionista 
                                            INNER JOIN CabeceraAlbaranCliente ON Clientes.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente                                            
                                            AND CabeceraAlbaranCliente.FechaAlbaran > DATEADD(d,-30,GETDATE()) AND Clientes.CodigoEmpresa = $empresa");
                                            
            $clientesActivosMesAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista 
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    where ClientesActivos.Periodo = $mesAnterior AND ClientesActivos.Ejercicio = $anioFechaActual");

            $clientesActivosAnioAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista  
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    where ClientesActivos.Periodo = $mesActual AND ClientesActivos.Ejercicio = $aniopasado");
            
            //TABLA CLIENTES ACTIVOS
            $tablaClientesCount = DB::select("SELECT  count(*) as total FROM ClientesActivos6 WHERE ClientesActivos6.CodigoJefeVenta_ = $comisionista");
            $tablaClientes = DB::select("SELECT  TOP 2000 ClientesActivos6.CodigoCliente, ClientesActivos6.RazonSocial FROM ClientesActivos6                                                                     
                                        WHERE ClientesActivos6.CodigoJefeVenta_ = $comisionista GROUP BY ClientesActivos6.CodigoCliente,  ClientesActivos6.RazonSocial");

            //Clientes Nuevos
            $nuevosClientes = DB::select("SELECT COUNT(*) AS usuarios From NuevosUsuarios WHERE periodo = $mesActual AND ejercicio = $anioFechaActual");
            $nuevosClientesMesAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesAnterior and ejercicio = $anioFechaActual ");
            $nuevosClietesAnioAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesActual and ejercicio = $aniopasado");

            return view('info')
            ->with('clientesActivos', $clientesActivos)->with('clientesActivosMesAnterior', $clientesActivosMesAnterior)->with('clientesActivosAnioAnterior', $clientesActivosAnioAnterior)
            ->with('tablaClientesCount', $tablaClientesCount)->with('tablaClientes', $tablaClientes)
            ->with('nuevosClientes', $nuevosClientes)->with('nuevosClientesMesAnterior', $nuevosClientesMesAnterior)->with('nuevosClietesAnioAnterior', $nuevosClietesAnioAnterior);
        
    }

    public static function semanal($codigo){

        $datos = self::datos();
        
        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$datos->fecha_actual') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$datos->fecha_actual') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }


        return $ventasSemanales;
    }

    public static function semanalC($codigo){

        $datos = self::datos();

        $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard  
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        WHERE DatePart(WEEK, '$datos->fecha_actual') = DatePart(WEEK,FechaAlbaran) 
        AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");

        return $ventasSemanales;
    }

    public static function semanaAnterior($codigo){

        $datos = self::datos();
        
        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasSemanaAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$datos->fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasSemanaAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$datos->fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }

        return $ventasSemanaAnterior;
    }

    public static function semanaAnteriorC($codigo){

        $datos = self::datos();

        $ventasSemanaAnterior = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard  
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        WHERE DatePart(WEEK, '$datos->fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) 
        AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");

        return $ventasSemanaAnterior;
    }

    public static function mes($codigo){
        
        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasMes = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$datos->fecha_actual') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasMes = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$datos->fecha_actual') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }

        return $ventasMes;
    }

    public static function mesC($codigo){

        $datos = self::datos();

        $ventasMes = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard 
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        WHERE DatePart(MONTH, '$datos->fecha_actual') = DatePart(MONTH,FechaAlbaran) 
        AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");

        return $ventasMes;
    }

    public static function mesAnterior($codigo){

        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            if( $datos->mesActual == 1){

                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard 
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(MONTH, '2022-12-12') = DatePart(MONTH,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
                and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
                and dashboard.CodigoEmpresa = '$datos->empresa'
                and dashboard.CodigoFamilia <> '116'");
                
            }else{
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard 
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(MONTH, '$datos->fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
                and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
                and dashboard.CodigoEmpresa = '$datos->empresa'
                and dashboard.CodigoFamilia <> '116'");
            }

        }else{
            if( $datos->mesActual == 1){

                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard 
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(MONTH, '2022-12-12') = DatePart(MONTH,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
                and Clientes.CodigoCliente = '$codigo' 
                and dashboard.CodigoEmpresa = '$datos->empresa'
                and dashboard.CodigoFamilia <> '116'");
                
            }else{
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard 
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(MONTH, '$datos->fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
                and Clientes.CodigoCliente = '$codigo' 
                and dashboard.CodigoEmpresa = '$datos->empresa'
                and dashboard.CodigoFamilia <> '116'");
            }
        }

        return $ventasMesAnterior;
    }

    public static function mesAnteriorC($codigo){

        $datos = self::datos();

        if( $datos->mesActual == 1){
            
            $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '2022-12-12') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
            and Clientes.CodigoComisionista = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{

            $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$datos->fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual' 
            and Clientes.CodigoComisionista = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }

        return $ventasMesAnterior;
    }

    public static function mesAnteriorAnio($codigo){

        $datos = self::datos();
        
        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasMesAnioAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$datos->fechaAnioAnterior') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasMesAnioAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$datos->fechaAnioAnterior') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }

        return $ventasMesAnioAnterior;
    }

    public static function mesAnteriorAnioC($codigo){

        $datos = self::datos();                

        $ventasMesAnioAnterior = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard 
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        WHERE DatePart(MONTH, '$datos->fechaAnioAnterior') = DatePart(MONTH,FechaAlbaran) 
        AND dashboard.EjercicioAlbaran = '$datos->aniopasado' 
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");


        return $ventasMesAnioAnterior;
    }

    public static function anual($codigo){

        $datos = self::datos();
        
        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasAnuales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard        
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual'         
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasAnuales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard        
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual'         
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }

        return $ventasAnuales;
    }

    public static function anualC($codigo){

        $datos = self::datos();                

        $ventasAnuales = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard         
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        AND dashboard.EjercicioAlbaran = '$datos->anioFechaActual'         
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");

        return $ventasAnuales;
    }

    public static function anualAnterior($codigo){

        $datos = self::datos();
        
        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $ventasAnualesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$datos->aniopasado'         
            and (Clientes.CodigoCliente = '$cod1' or Clientes.CodigoCliente = '$cod2') 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");

        }else{
            $ventasAnualesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$datos->aniopasado'         
            and Clientes.CodigoCliente = '$codigo' 
            and dashboard.CodigoEmpresa = '$datos->empresa'
            and dashboard.CodigoFamilia <> '116'");
        }

        


        return $ventasAnualesAnterior;
    }

    public static function anualAnteriorC($codigo){

        $datos = self::datos();        

        $ventasAnualesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
        from dashboard 
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente
        AND dashboard.EjercicioAlbaran = '$datos->aniopasado'         
        and Clientes.CodigoComisionista = '$codigo' 
        and dashboard.CodigoEmpresa = '$datos->empresa'
        and dashboard.CodigoFamilia <> '116'");

        return $ventasAnualesAnterior;
    }

    public static function preescriptorMes($codigo){

        $datos = self::datos();

        switch ($datos->mesActual) {
            case 1:
                $primero = 8; $segundo = 9; $tercero = 10; $cuarto = 11; $quinto = 12; $sexto = 1;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->aniopasado; $eQuinto = $datos->aniopasado; $eSexto = $datos->anioFechaActual;
                break;
            case 2:
                $primero = 9; $segundo = 10; $tercero = 11; $cuarto = 12; $quinto = 1; $sexto = 2;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->aniopasado; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 3:
                $primero = 10; $segundo = 11; $tercero = 12; $cuarto = 1; $quinto = 2; $sexto = 3;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 4:
                $primero = 11; $segundo = 12; $tercero = 1; $cuarto = 2; $quinto = 3; $sexto = 4;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 5:
                $primero = 12; $segundo = 1; $tercero = 2; $cuarto = 3; $quinto = 4; $sexto = 5;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 6:
                $primero = 1; $segundo = 2; $tercero = 3; $cuarto = 4; $quinto = 5; $sexto = 6;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 7:
                $primero = 2; $segundo = 3; $tercero = 4; $cuarto = 5; $quinto = 6; $sexto = 7;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 8:
                $primero = 3; $segundo = 4; $tercero = 5; $cuarto = 6; $quinto = 7; $sexto = 8;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 9:
                $primero = 4; $segundo = 5; $tercero = 6; $cuarto = 7; $quinto = 8; $sexto = 9;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 10:
                $primero = 5; $segundo = 6; $tercero = 7; $cuarto = 8; $quinto = 9; $sexto = 10;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 11:
                $primero = 6; $segundo = 7; $tercero = 8; $cuarto = 9; $quinto = 10; $sexto = 11;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 12:
                $primero = 7; $segundo = 8; $tercero = 9; $cuarto = 10; $quinto = 11; $sexto = 12;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;         
        }

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $preescriptorMes= DB::select("SELECT cli.CodigoCliente, cli.RazonSocial,
            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura        
            where ResumenCliente.codigoempresa= resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$ePrimero'
            and Month(ResumenCliente.fechaFactura)='$primero' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa'
            and resumen.CodigoFamilia <> '116') AS primero,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eSegundo'
            and Month(ResumenCliente.fechaFactura)='$segundo' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS segundo,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eTercero'
            and Month(ResumenCliente.fechaFactura)='$tercero' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS tercero,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eCuarto'
            and Month(ResumenCliente.fechaFactura)='$cuarto' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS cuarto,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eQuinto'
            and Month(ResumenCliente.fechaFactura)='$quinto' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS quinto,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eSexto'
            and Month(ResumenCliente.fechaFactura)='$sexto' 
            and (ResumenCliente.CodigoCliente = '$cod1' or ResumenCliente.CodigoCliente = '$cod2') 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS sexto

            from LineasAlbaranCliente AS rc 

            inner join ResumenCliente AS codCli
            on rc.CodigoEmpresa = codCli.CodigoEmpresa
            AND rc.EjercicioFactura = codCli.EjercicioFactura
            AND rc.SerieFactura = codCli.SerieFactura
            AND rc.NumeroFactura = codCli.NumeroFactura

            inner join Clientes As cli on codCli.CodigoCliente = cli.CodigoCliente 

            where cli.CodigoCategoriaCliente_ = 'CLI'
            AND rc.EjercicioFactura IN($datos->aniopasado,$datos->anioFechaActual)
            and (cli.CodigoCliente = '$cod1' or cli.CodigoCliente = '$cod2')
            and codCli.CodigoEmpresa = '$datos->empresa'
            and rc.CodigoFamilia <> '116'
            group by cli.Codigocliente, cli.RazonSocial, rc.CodigoEmpresa
            order by cli.CodigoCliente asc");

        }else{
            $preescriptorMes= DB::select("SELECT cli.CodigoCliente, cli.RazonSocial,
            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura        
            where ResumenCliente.codigoempresa= resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$ePrimero'
            and Month(ResumenCliente.fechaFactura)='$primero' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa'
            and resumen.CodigoFamilia <> '116') AS primero,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eSegundo'
            and Month(ResumenCliente.fechaFactura)='$segundo' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS segundo,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eTercero'
            and Month(ResumenCliente.fechaFactura)='$tercero' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS tercero,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eCuarto'
            and Month(ResumenCliente.fechaFactura)='$cuarto' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS cuarto,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eQuinto'
            and Month(ResumenCliente.fechaFactura)='$quinto' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS quinto,

            (select sum(resumen.ImporteNeto) from LineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
            AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            AND resumen.SerieFactura = ResumenCliente.SerieFactura
            AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where ResumenCliente.codigoempresa=resumen.codigoempresa 
            and ResumenCliente.CodigoCliente = cli.CodigoCliente 
            and Year(ResumenCliente.fechaFactura) = '$eSexto'
            and Month(ResumenCliente.fechaFactura)='$sexto' 
            and ResumenCliente.CodigoCliente = '$codigo' 
            and resumen.CodigoEmpresa = '$datos->empresa' 
            and resumen.CodigoFamilia <> '116') AS sexto

            from LineasAlbaranCliente AS rc 

            inner join ResumenCliente AS codCli
            on rc.CodigoEmpresa = codCli.CodigoEmpresa
            AND rc.EjercicioFactura = codCli.EjercicioFactura
            AND rc.SerieFactura = codCli.SerieFactura
            AND rc.NumeroFactura = codCli.NumeroFactura

            inner join Clientes As cli on codCli.CodigoCliente = cli.CodigoCliente 

            where cli.CodigoCategoriaCliente_ = 'CLI'
            AND rc.EjercicioFactura IN($datos->aniopasado,$datos->anioFechaActual)
            and cli.CodigoCliente = '$codigo' 
            and codCli.CodigoEmpresa = '$datos->empresa'
            and rc.CodigoFamilia <> '116'
            group by cli.Codigocliente, cli.RazonSocial, rc.CodigoEmpresa
            order by cli.CodigoCliente asc");
        }

        return $preescriptorMes;
    }

    public static function preescriptorMesC($codigo){

        $datos = self::datos();

        switch ($datos->mesActual) {
            case 1:
                $primero = 8; $segundo = 9; $tercero = 10; $cuarto = 11; $quinto = 12; $sexto = 1;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->aniopasado; $eQuinto = $datos->aniopasado; $eSexto = $datos->anioFechaActual;
                break;
            case 2:
                $primero = 9; $segundo = 10; $tercero = 11; $cuarto = 12; $quinto = 1; $sexto = 2;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->aniopasado; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 3:
                $primero = 10; $segundo = 11; $tercero = 12; $cuarto = 1; $quinto = 2; $sexto = 3;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->aniopasado; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 4:
                $primero = 11; $segundo = 12; $tercero = 1; $cuarto = 2; $quinto = 3; $sexto = 4;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->aniopasado; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 5:
                $primero = 12; $segundo = 1; $tercero = 2; $cuarto = 3; $quinto = 4; $sexto = 5;
                $ePrimero = $datos->aniopasado; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 6:
                $primero = 1; $segundo = 2; $tercero = 3; $cuarto = 4; $quinto = 5; $sexto = 6;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 7:
                $primero = 2; $segundo = 3; $tercero = 4; $cuarto = 5; $quinto = 6; $sexto = 7;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 8:
                $primero = 3; $segundo = 4; $tercero = 5; $cuarto = 6; $quinto = 7; $sexto = 8;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 9:
                $primero = 4; $segundo = 5; $tercero = 6; $cuarto = 7; $quinto = 8; $sexto = 9;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 10:
                $primero = 5; $segundo = 6; $tercero = 7; $cuarto = 8; $quinto = 9; $sexto = 10;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 11:
                $primero = 6; $segundo = 7; $tercero = 8; $cuarto = 9; $quinto = 10; $sexto = 11;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;
            case 12:
                $primero = 7; $segundo = 8; $tercero = 9; $cuarto = 10; $quinto = 11; $sexto = 12;
                $ePrimero = $datos->anioFechaActual; $eSegundo = $datos->anioFechaActual; $eTercero = $datos->anioFechaActual; $eCuarto = $datos->anioFechaActual; $eQuinto = $datos->anioFechaActual; $eSexto = $datos->anioFechaActual;
                break;         
        }
        
        $preescriptorMes= DB::select("SELECT cli.CodigoCliente, cli.RazonSocial,        
        
        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
        inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa= resumen.codigoempresa 
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$ePrimero'
        and Month(ResumenCliente.fechaFactura)='$primero' 
        and Clientes.CodigoComisionista = '$codigo' 
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS primero,

        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
        inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa=resumen.codigoempresa         
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$eSegundo'
        and Month(ResumenCliente.fechaFactura)='$segundo' 
        and Clientes.CodigoComisionista = '$codigo' 
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS segundo,

        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
         inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa=resumen.codigoempresa 
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$eTercero'
        and Month(ResumenCliente.fechaFactura)='$tercero' 
        and Clientes.CodigoComisionista = '$codigo' 
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS tercero,

        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
         inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa=resumen.codigoempresa 
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$eCuarto'
        and Month(ResumenCliente.fechaFactura)='$cuarto' 
        and Clientes.CodigoComisionista = '$codigo' 
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS cuarto,

        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
         inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa=resumen.codigoempresa 
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$eQuinto'
        and Month(ResumenCliente.fechaFactura)='$quinto' 
        and Clientes.CodigoComisionista = '$codigo'
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS quinto,

        (select sum(resumen.BaseImponible) from LineasAlbaranCliente AS resumen 
        inner join ResumenCliente 
        on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa
        AND resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
        AND resumen.SerieFactura = ResumenCliente.SerieFactura
        AND resumen.NumeroFactura = ResumenCliente.NumeroFactura
         inner join clientes
        on ResumenCliente.CodigoEmpresa = clientes.CodigoEmpresa
        and ResumenCLiente.CodigoCliente = clientes.CodigoCliente
        where ResumenCliente.codigoempresa=resumen.codigoempresa 
        and ResumenCliente.CodigoCliente = cli.CodigoCliente 
        and Year(ResumenCliente.fechaFactura) = '$eSexto'
        and Month(ResumenCliente.fechaFactura)='$sexto' 
        and Clientes.CodigoComisionista = '$codigo' 
        and resumen.CodigoEmpresa = '$datos->empresa'
        and resumen.CodigoFamilia <> '116') AS sexto

        from LineasAlbaranCliente AS rc 

        inner join ResumenCliente AS codCli
        on rc.CodigoEmpresa = codCli.CodigoEmpresa
        AND rc.EjercicioFactura = codCli.EjercicioFactura
        AND rc.SerieFactura = codCli.SerieFactura
        AND rc.NumeroFactura = codCli.NumeroFactura

        inner join Clientes As cli         
        on codCli.CodigoCliente = cli.CodigoCliente 
        and codCli.CodigoEmpresa = cli.CodigoEmpresa
        
        where cli.CodigoCategoriaCliente_ = 'CLI'
        AND rc.EjercicioFactura IN($datos->aniopasado,$datos->anioFechaActual)
        and cli.CodigoComisionista = '$codigo' 
        and codCli.CodigoEmpresa = '$datos->empresa'
        and rc.CodigoFamilia <> '116'
        group by cli.CodigoCliente, cli.RazonSocial, rc.Codigoempresa
        order by cli.CodigoCliente asc")
        
        
        ;


        return $preescriptorMes;
    }


    public static function pedidos6Meses($codigo){

        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";

            $pedidos6Meses= DB::select("SELECT FechaAlbaran, sum(ImporteLiquido) AS Total , count(FechaAlbaran) AS Cantidad from CabeceraAlbaranCliente 
            where CodigoEmpresa=$datos->empresa and FechaAlbaran BETWEEN DATEADD(MM, -6,GETDATE()) and GETDATE()
            and (CodigoCliente = '$cod1' or CodigoCliente = '$cod2') and CodigoEmpresa = '$datos->empresa'
            GROUP BY FechaAlbaran
            order by FechaAlbaran desc");

        }else{
            $pedidos6Meses= DB::select("SELECT FechaAlbaran, sum(ImporteLiquido) AS Total , count(FechaAlbaran) AS Cantidad from CabeceraAlbaranCliente 
            where CodigoEmpresa=$datos->empresa and FechaAlbaran BETWEEN DATEADD(MM, -6,GETDATE()) and GETDATE()
            and CodigoCliente = '$codigo' and CodigoEmpresa = '$datos->empresa'
            GROUP BY FechaAlbaran
            order by FechaAlbaran desc");
        }
        
        


        return $pedidos6Meses;
    }

    public static function pedidos6MesesC($codigo){

        $datos = self::datos();
        
        $pedidos6Meses= DB::select("SELECT FechaAlbaran, sum(ImporteLiquido) AS Total , count(FechaAlbaran) AS Cantidad from CabeceraAlbaranCliente 
            where CodigoEmpresa=$datos->empresa and FechaAlbaran BETWEEN DATEADD(MM, -6,GETDATE()) and GETDATE()
            and CodigoComisionista = '$codigo' and CodigoEmpresa = '$datos->empresa' 
            GROUP BY FechaAlbaran
            order by FechaAlbaran desc");


        return $pedidos6Meses;
    }

    public static function ventasComisionista($codigo){

        $datos = self::datos();
        
        $ventasComisionista = DB::select("SELECT Top 6  Periodo, sum(TotalDia)as TotalMes, EjercicioAlbaran FROM ResumenCliente3 
            WHERE EjercicioAlbaran IN ($datos->aniopasado,$datos->anioFechaActual) and CodigoJefeVenta_ = $datos->comisionista 
            and CodigoCliente = '$codigo' and CodigoEmpresa = '$datos->empresa' And CodigoFamilia <> 116
            GROUP BY EjercicioAlbaran, Periodo 
            ORDER BY  EjercicioAlbaran desc, Periodo desc");


        return $ventasComisionista;
    }

    public static function ventasComisionistaC($codigo){

        $datos = self::datos();
        
        $ventasComisionista = DB::select("SELECT Top 6  Periodo, sum(TotalDia)as TotalMes, EjercicioAlbaran FROM ResumenCliente3 
            WHERE EjercicioAlbaran IN ($datos->aniopasado,$datos->anioFechaActual) and CodigoJefeVenta_ = $datos->comisionista 
            and CodigoComisionista = '$codigo' and CodigoEmpresa = '$datos->empresa' And CodigoFamilia <> 116
            GROUP BY EjercicioAlbaran, Periodo 
            ORDER BY  EjercicioAlbaran desc, Periodo desc");


        return $ventasComisionista;
    }

    public static function totalVentasComisionista($codigo){

        $datos = self::datos();
        
        $totalVentasComisionista = DB::select("	SELECT Top 6  sum(TotalDia)as TotalMes FROM ResumenCliente3 
            WHERE EjercicioAlbaran IN ($datos->aniopasado,$datos->anioFechaActual)
            AND CodigoCliente = '$codigo' AND CodigoEmpresa = '$datos->empresa' And CodigoFamilia <> 116");


        return $totalVentasComisionista;
    }

    public static function totalVentasComisionistaC($codigo){

        $datos = self::datos();
        
        $totalVentasComisionista = DB::select("	SELECT Top 6  sum(TotalDia)as TotalMes FROM ResumenCliente3 
            WHERE EjercicioAlbaran IN ($datos->aniopasado,$datos->anioFechaActual)
            AND CodigoComisionista = '$codigo'  AND CodigoEmpresa = '$datos->empresa' And CodigoFamilia <> 116");


        return $totalVentasComisionista;
    }

    public static function resumenAnual($codigo){

        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";
        }else{
            $cod1 = $codigo;
            $cod2 = $codigo;
        }
        
        $ventasComisionistaMensuales = DB::select("SELECT TOP 1
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,        
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        from lineasAlbaranCliente 
        inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        where LineasAlbaranCliente.EjercicioFactura = $datos->anioFechaActual 
        and ResumenCliente.CodigoCliente = '$cod2' 
        and LineasAlbaranCliente.CodigoFamilia <> '116' 
        and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa' ");

        if(empty($ventasComisionistaMensuales)){
            $ventasComisionistaMensuales[0] = ["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
        }

        return $ventasComisionistaMensuales;
    }

    public static function resumenAnualC($codigo){

        $datos = self::datos();

        $ventasComisionistaMensuales = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='1') As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->anioFechaActual and Mes='12') As Diciembre");
        
        // $ventasComisionistaMensuales = DB::select("SELECT TOP 1
        // (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->anioFechaActual 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        //     from lineasAlbaranCliente 
        //     inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        //     and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        //     and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        //     and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        //     where LineasAlbaranCliente.EjercicioFactura = $datos->anioFechaActual 
        //     and ResumenCliente.CodigoComisionista = '$codigo' 
        //     and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa'
        //     and LineasAlbaranCliente.CodigoFamilia <> '116'");

        if(empty($ventasComisionistaMensuales)){
            $ventasComisionistaMensuales[0] = ["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
        }

        return $ventasComisionistaMensuales;
    }


    public static function resumenAnual2($codigo){

        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";
        }else{
            $cod1 = $codigo;
            $cod2 = $codigo;
        }
        
        $ventasComisionistaMensualesAnioAnterior = DB::select("SELECT TOP 1
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,        
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        from lineasAlbaranCliente 
        inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        where LineasAlbaranCliente.EjercicioFactura = $datos->aniopasado 
        and ResumenCliente.CodigoCliente = '$cod2' 
        and LineasAlbaranCliente.CodigoFamilia <> '116' 
        and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa' ");                    
            

            if(empty($ventasComisionistaMensualesAnioAnterior)){
                $ventasComisionistaMensualesAnioAnterior[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }

        return $ventasComisionistaMensualesAnioAnterior;
    }

    public static function resumenAnual2C($codigo){

        $datos = self::datos();

        $ventasComisionistaMensualesAnioAnterior = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes=$datos->empresa) As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado and Mes='12') As Diciembre");
        
        // $ventasComisionistaMensualesAnioAnterior = DB::select("SELECT TOP 1            
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        //     from lineasAlbaranCliente 
        //     inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        //     and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        //     and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        //     and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        //     where LineasAlbaranCliente.EjercicioFactura = $datos->aniopasado 
        //     and ResumenCliente.CodigoComisionista = '$codigo' 
        //     and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa'
        //     and LineasAlbaranCliente.CodigoFamilia <> '116'");

            if(empty($ventasComisionistaMensualesAnioAnterior)){
                $ventasComisionistaMensualesAnioAnterior[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }

        return $ventasComisionistaMensualesAnioAnterior;
    }


    public static function resumenAnual3($codigo){

        $datos = self::datos();

        if ($codigo == "021916" || $codigo == "021917") {
            $cod1 = "021917";
            $cod2 = "021916";
        }else{
            $cod1 = $codigo;
            $cod2 = $codigo;
        }
        
        $ventasComisionistaMensualesDosAniosAnteriores = DB::select("SELECT TOP 1
            (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,        
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        (SELECT sum(BaseImponible) from dashboard
        inner join Clientes
        on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        and dashboard.CodigoCliente = Clientes.CodigoCliente             
        where dashboard.codigoempresa= $datos->empresa 
        and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        and Clientes.CodigoCliente = '$cod1'
        and dashboard.CodigoFamilia <> '116'
        and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        from lineasAlbaranCliente 
        inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        where LineasAlbaranCliente.EjercicioFactura = $datos->aniopasado2 
        and ResumenCliente.CodigoCliente = '$cod2' 
        and LineasAlbaranCliente.CodigoFamilia <> '116' 
        and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa' ");     
        if(empty($ventasComisionistaMensualesDosAniosAnteriores)){
            $ventasComisionistaMensualesDosAniosAnteriores[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
        }

        return $ventasComisionistaMensualesDosAniosAnteriores;
    }

    public static function resumenAnual3C($codigo){

        $datos = self::datos();
        
        $ventasComisionistaMensualesDosAniosAnteriores = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes=$datos->empresa) As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$codigo' and CodigoEmpresa=$datos->empresa and anio=$datos->aniopasado2 and Mes='12') As Diciembre");

        // $ventasComisionistaMensualesDosAniosAnteriores = DB::select("SELECT TOP 1
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
        //     (SELECT sum(BaseImponible) from dashboard
        //     inner join Clientes
        //     on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
        //     and dashboard.CodigoCliente = Clientes.CodigoCliente             
        //     where dashboard.codigoempresa= $datos->empresa 
        //     and dashboard.EjercicioAlbaran = $datos->aniopasado2 
        //     and Clientes.CodigoComisionista = '$codigo'
        //     and dashboard.CodigoFamilia <> '116'
        //     and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
        //     from lineasAlbaranCliente 
        //     inner join ResumenCliente on ResumenCliente.CodigoEmpresa = LineasAlbaranCliente.CodigoEmpresa 
        //     and ResumenCliente.EjercicioFactura = LineasAlbaranCliente.EjercicioFactura
        //     and ResumenCliente.SerieFactura = LineasAlbaranCliente.SerieFactura
        //     and ResumenCliente.NumeroFactura = LineasAlbaranCliente.NumeroFactura  
        //     where LineasAlbaranCliente.EjercicioFactura = $datos->aniopasado2 
        //     and ResumenCliente.CodigoComisionista = '$codigo' 
        //     and LineasAlbaranCliente.CodigoEmpresa = '$datos->empresa'
        //     and LineasAlbaranCliente.CodigoFamilia <> '116'");

        if(empty($ventasComisionistaMensualesDosAniosAnteriores)){
            $ventasComisionistaMensualesDosAniosAnteriores[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
        }

        return $ventasComisionistaMensualesDosAniosAnteriores;
    }

    

}
?>