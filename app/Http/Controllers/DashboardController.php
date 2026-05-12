<?php

namespace App\Http\Controllers;

use App\Models\dashboard;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class DashboardController extends Controller
{
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
        $ejercicio = 0;
        
        if(session('tipo') != 5){
            //$ventasSemanales = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE DatePart(WEEK, '$fecha_actual') = DatePart(WEEK,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard  
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(WEEK, '$fecha_actual') = DatePart(WEEK,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
                and dashboard.CodigoComisionista = '$comisionista' 
                and dashboard.CodigoEmpresa = '$empresa'
                and dashboard.CodigoFamilia <> '116'");

            $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard  
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(WEEK, '$fecha_actual') = DatePart(WEEK,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
                and dashboard.CodigoComisionista = '$comisionista' 
                and dashboard.CodigoEmpresa = '$empresa'
                and dashboard.CodigoFamilia <> '116'");
            //$ventasSemanaAnterior = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE DatePart(WEEK, '$fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasSemanaAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
            and dashboard.CodigoComisionista = '$comisionista' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            //$ventasMes = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE DatePart(MONTH, '$fecha_actual') = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116'");
            $ventasMes = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$fecha_actual') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
            and dashboard.CodigoComisionista = '$comisionista' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            if( $mesActual == 1){
                //$ventasMesAnterior = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE DatePart(MONTH, 12) = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$aniopasado' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116'");
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                    from VentasComiAnual 
                    WHERE DatePart(MONTH, '2022-12-12') = VentasComiAnual.mes 
                    AND VentasComiAnual.anio = '$aniopasado'
                    and codigocomisionista = '$comisionista'
                    and VentasComiAnual.CodigoEmpresa = '$empresa'");
            }else{
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                    from VentasComiAnual 
                    WHERE (MONTH(GETDATE())-1) = VentasComiAnual.mes 
                    AND VentasComiAnual.anio = YEAR(GETDATE())
                    and codigocomisionista = '$comisionista'
                    and VentasComiAnual.CodigoEmpresa = '$empresa'");
            }
            $ventasMesAnioAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                from VentasComiAnual 
                WHERE DatePart(MONTH, $fechaAnioAnterior) = VentasComiAnual.mes 
                AND VentasComiAnual.anio = '$aniopasado'
                and codigocomisionista = '$comisionista'
                and VentasComiAnual.CodigoEmpresa = '$empresa'");

            //$ventasAnuales = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE EjercicioFactura = '$anioFechaActual' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116'");
            $ventasAnuales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard         
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$anioFechaActual'         
            and Clientes.CodigoComisionista = '$comisionista' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");

            //$ventasAnualesAnterior = DB::select("SELECT SUM(ImporteNeto) as total from lineasAlbaranCliente WHERE EjercicioFactura = '$aniopasado' and CodigoComisionista = '$comisionista' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasAnualesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$aniopasado'         
            and dashboard.CodigoComisionista = '$comisionista' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");

            $ventasComisionistaMensuales = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='1') As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$anioFechaActual and Mes='12') As Diciembre");

            $ventasComisionistaMensualesAnioAnterior = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='1') As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado and Mes='12') As Diciembre");

            $ventasComisionistaMensualesDosAniosAnteriores = DB::select("select
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='1') As Enero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='2') As Febrero,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='3') As Marzo,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='4') As Abril,
            (select baseImponible from VentasComiAnual 
                where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='5') As Mayo,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='6') As Junio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='7') As Julio,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='8') As Agosto,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='9') As Septiembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='10') As Octubre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='11') As Noviembre,
            (select baseImponible from VentasComiAnual 
            where CodigoComisionista='$comisionista' and CodigoEmpresa=$empresa and anio=$aniopasado2 and Mes='12') As Diciembre");

            switch ($mesActual) {
                case 1:
                    $primero = 8; $segundo = 9; $tercero = 10; $cuarto = 11; $quinto = 12; $sexto = 1;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $aniopasado; $eQuinto = $aniopasado; $eSexto = $anioFechaActual;
                    break;
                case 2:
                    $primero = 9; $segundo = 10; $tercero = 11; $cuarto = 12; $quinto = 1; $sexto = 2;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $aniopasado; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 3:
                    $primero = 10; $segundo = 11; $tercero = 12; $cuarto = 1; $quinto = 2; $sexto = 3;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 4:
                    $primero = 11; $segundo = 12; $tercero = 1; $cuarto = 2; $quinto = 3; $sexto = 4;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 5:
                    $primero = 12; $segundo = 1; $tercero = 2; $cuarto = 3; $quinto = 4; $sexto = 5;
                    $ePrimero = $aniopasado; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 6:
                    $primero = 1; $segundo = 2; $tercero = 3; $cuarto = 4; $quinto = 5; $sexto = 6;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 7:
                    $primero = 2; $segundo = 3; $tercero = 4; $cuarto = 5; $quinto = 6; $sexto = 7;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 8:
                    $primero = 3; $segundo = 4; $tercero = 5; $cuarto = 6; $quinto = 7; $sexto = 8;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 9:
                    $primero = 4; $segundo = 5; $tercero = 6; $cuarto = 7; $quinto = 8; $sexto = 9;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 10:
                    $primero = 5; $segundo = 6; $tercero = 7; $cuarto = 8; $quinto = 9; $sexto = 10;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 11:
                    $primero = 6; $segundo = 7; $tercero = 8; $cuarto = 9; $quinto = 10; $sexto = 11;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 12:
                    $primero = 7; $segundo = 8; $tercero = 9; $cuarto = 10; $quinto = 11; $sexto = 12;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;         
            }

            

            $preescriptorMes= DB::select("SELECT codCli.CodigoCliente, codCli.RazonSocial,
            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa 
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$ePrimero'
            and Month(resumen.FechaAlbaran)='$primero' ) AS primero,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$eSegundo'
            and Month(resumen.FechaAlbaran)='$segundo' ) AS segundo,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$eTercero'
            and Month(resumen.FechaAlbaran)='$tercero' ) AS tercero,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$eCuarto'
            and Month(resumen.FechaAlbaran)='$cuarto' ) AS cuarto,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa 
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$eQuinto'
            and Month(resumen.FechaAlbaran)='$quinto' ) AS quinto,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa 
            and resumen.CodigoComisionista='$comisionista' and resumen.CodigoEmpresa='$empresa' and ResumenCliente.CodigoCliente = codCli.CodigoCliente and Year(resumen.FechaAlbaran) = '$eSexto'
            and Month(resumen.FechaAlbaran)='$sexto' ) AS sexto

            from lineasAlbaranCliente AS rc 

            inner join ResumenCliente As codCli 
            on codCli.CodigoEmpresa = rc.CodigoEmpresa 
            and codCli.EjercicioFactura = rc.EjercicioFactura 
            and codCli.SerieFactura = rc.SerieFactura 
            and codCli.NumeroFactura = rc.NumeroFactura

            inner join Clientes As cli on codCli.CodigoCliente = cli.CodigoCliente  and CodCli.CodigoEmpresa = cli.CodigoEmpresa
            where cli.CodigoCategoriaCliente_ = 'CLI'

            AND rc.EjercicioFactura IN($aniopasado,$anioFechaActual) and rc.CodigoComisionista='$comisionista'
            and rc.CodigoEmpresa = '$empresa'
            group by codCli.Codigocliente, codCli.RazonSocial, rc.Codigoempresa
            order by CodigoCliente asc");

            //return $preescriptorMes;

            $pedidos6Meses= DB::select("SELECT FechaAlbaran as FechaFactura, sum(ImporteNeto) AS Total , count(FechaAlbaran) AS Cantidad from lineasAlbaranCliente 
            where CodigoComisionista = $comisionista and CodigoEmpresa=$empresa and FechaAlbaran BETWEEN DATEADD(MM, -6,GETDATE()) and GETDATE() and CodigoFamilia <> '116'
            GROUP BY FechaAlbaran
            order by FechaAlbaran desc");

            
            $ventasComisionista = DB::select("SELECT Top 6  Periodo, sum(TotalDia)as TotalMes, EjercicioAlbaran FROM ResumenCliente2 
            WHERE EjercicioAlbaran IN ($aniopasado,$anioFechaActual) 
            and CodigoComisionista = '$comisionista' and CodigoEmpresa = '$empresa' And CodigoFamilia <> 116
            GROUP BY EjercicioAlbaran, Periodo 
            ORDER BY  EjercicioAlbaran desc, Periodo desc");

            $totalVentasComisionista = DB::select("	SELECT top 6 CodigoEmpresa, EjercicioAlbaran, Periodo, sum(TotalDia) as TotalMes FROM ResumenCliente2             
            WHERE EjercicioAlbaran IN ($aniopasado,$anioFechaActual) 
            and CodigoComisionista = $comisionista and CodigoEmpresa = $empresa And CodigoFamilia <> 116
            group by CodigoEmpresa,EjercicioAlbaran, Periodo
            order by EjercicioAlbaran desc, Periodo desc");


            // $totalVentasComisionista = DB::select("	SELECT Top 6  sum(TotalDia)as TotalMes FROM ResumenCliente1 
            // WHERE EjercicioFactura IN ($aniopasado,$anioFechaActual) and CodigoComisionista = $comisionista");

            if(empty($ventasComisionistaMensualesDosAniosAnteriores)){
                $ventasComisionistaMensualesDosAniosAnteriores[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }
            
            if(empty($ventasComisionistaMensuales)){
                $ventasComisionistaMensuales[0] = ["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }

            if(empty($ventasComisionistaMensualesAnioAnterior)){
                $ventasComisionistaMensualesAnioAnterior[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }
            //return $totalVentasComisionista;


            //CLIENTES ACTIVOS
            $clientesActivos = DB::select("SELECT Count(Clientes.CodigoCliente) AS activos from Clientes  
                                            INNER JOIN Comisionistas ON Clientes.CodigoComisionista = Comisionistas.Codigocomisionista
                                            AND Clientes.CodigoEmpresa  = Comisionistas.CodigoEmpresa
                                            INNER JOIN CabeceraAlbaranCliente ON Clientes.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                            AND Clientes.CodigoEmpresa = CabeceraAlbaranCliente.CodigoEmpresa
                                            WHERE Clientes.CodigoComisionista = $comisionista
                                            AND Clientes.CodigoEmpresa = $empresa 
                                            AND CabeceraAlbaranCliente.FechaAlbaran > DATEADD(d,-30,GETDATE())");

            $clientesActivosMesAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista 
                                                    AND ClientesActivos.CodigoEmpresa = Comisionistas.CodigoEmpresa
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    AND ClientesActivos.CodigoEmpresa = CabeceraAlbaranCliente.CodigoEmpresa
                                                    WHERE ClientesActivos.Periodo = $mesAnterior AND ClientesActivos.Ejercicio = $anioFechaActual 
                                                    AND ClientesActivos.CodigoComisionista = $comisionista AND ClientesActivos.CodigoEmpresa = $empresa");

            $clientesActivosAnioAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista 
                                                    AND ClientesActivos.CodigoEmpresa = Comisionistas.CodigoEmpresa 
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    AND CabeceraAlbaranCliente.CodigoEmpresa = ClientesActivos.CodigoEmpresa
                                                    where ClientesActivos.Periodo = $mesActual AND ClientesActivos.Ejercicio = $aniopasado 
                                                    AND ClientesActivos.CodigoComisionista = $comisionista AND ClientesActivos.CodigoEmpresa = $empresa");
            
            //TABLA CLIENTES ACTIVOS
            $tablaClientesCount = DB::select("SELECT  count(*) as total FROM ClientesActivos6 WHERE ClientesActivos6.CodigoComisionista = $comisionista AND ClientesActivos6.CodigoEmpresa = $empresa");
            $tablaClientes = DB::select("SELECT  TOP 2000 ClientesActivos6.CodigoCliente, ClientesActivos6.RazonSocial, ClientesActivos6.CodigoEmpresa FROM ClientesActivos6  
                                        WHERE ClientesActivos6.CodigoEmpresa = $empresa                                                                   
                                        AND ClientesActivos6.CodigoComisionista = $comisionista 
                                        GROUP BY ClientesActivos6.CodigoCliente,  ClientesActivos6.RazonSocial, ClientesActivos6.CodigoEmpresa");

            //Clientes Nuevos
            $nuevosClientes = DB::select("SELECT COUNT(*) AS usuarios From NuevosUsuarios WHERE periodo = $mesActual AND ejercicio = $anioFechaActual AND CodigoComisionista = $comisionista AND CodigoEmpresa = $empresa");
            $nuevosClientesMesAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesAnterior and ejercicio = $anioFechaActual and CodigoComisionista = $comisionista AND CodigoEmpresa = $empresa");
            $nuevosClietesAnioAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesActual and ejercicio = $aniopasado and CodigoComisionista = $comisionista AND CodigoEmpresa = $empresa");

        } else {

            //$ventasSemanales = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(WEEK, '$fecha_actual') = DatePart(WEEK,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasSemanales = DB::select("SELECT SUM(BaseImponible) as total 
                from dashboard  
                inner join Clientes
                on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                and dashboard.CodigoCliente = Clientes.CodigoCliente
                WHERE DatePart(WEEK, '$fecha_actual') = DatePart(WEEK,FechaAlbaran) 
                AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
                and dashboard.CodigoEmpresa = '$empresa'
                and dashboard.CodigoFamilia <> '116'");
            //$ventasSemanaAnterior = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(WEEK, '$fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasSemanaAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard  
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(WEEK, '$fechaSemanaAnterior') = DatePart(WEEK,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$anioFechaActual'  
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            //$ventasMes = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(MONTH, '$fecha_actual') = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasMes = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$fecha_actual') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$anioFechaActual' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            if( $mesActual == 1){
                //$ventasMesAnterior = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(MONTH, 12) = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$aniopasado' and CodigoEmpresa='$empresa' where CodigoFamilia <> '116' ");
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                    from VentasComiAnual 
                    WHERE DatePart(MONTH, '2022-12-12') = VentasComiAnual.mes 
                    AND VentasComiAnual.anio = '$anioFechaActual'
                    -- and codigocomisionista = '$comisionista'
                    and VentasComiAnual.CodigoEmpresa = '$empresa'");

                // $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                // from dashboard 
                // inner join Clientes
                // on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                // and dashboard.CodigoCliente = Clientes.CodigoCliente
                // WHERE DatePart(MONTH, '2022-12-12') = DatePart(MONTH,FechaAlbaran) 
                // AND dashboard.EjercicioAlbaran = '$aniopasado' 
                // and dashboard.CodigoEmpresa = '$empresa'
                // and dashboard.CodigoFamilia <> '116'");
            }else{
                //$ventasMesAnterior = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(MONTH, '$fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$anioFechaActual' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
                $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                    from VentasComiAnual 
                    WHERE DatePart(MONTH, '$fechaMesAnterior') = VentasComiAnual.mes 
                    AND VentasComiAnual.anio = '$anioFechaActual'
                    -- and codigocomisionista = '$comisionista'
                    and VentasComiAnual.CodigoEmpresa = '$empresa'");

                // $ventasMesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
                // from dashboard 
                // inner join Clientes
                // on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
                // and dashboard.CodigoCliente = Clientes.CodigoCliente
                // WHERE DatePart(MONTH, '$fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) 
                // AND dashboard.EjercicioAlbaran = '$anioFechaActual'  
                // and dashboard.CodigoEmpresa = '$empresa'
                // and dashboard.CodigoFamilia <> '116'");
            }
            //$ventasMesAnioAnterior= DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE DatePart(MONTH, '$fechaMesAnterior') = DatePart(MONTH,FechaAlbaran) AND EjercicioFactura = '$aniopasado' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasMesAnioAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            WHERE DatePart(MONTH, '$fechaAnioAnterior') = DatePart(MONTH,FechaAlbaran) 
            AND dashboard.EjercicioAlbaran = '$aniopasado' 
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            //$ventasAnuales = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE EjercicioFactura = '$anioFechaActual' and CodigoEmpresa='$empresa' and CodigoFamilia <> '116' ");
            $ventasAnuales = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard         
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$anioFechaActual'         
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");
            //$ventasAnualesAnterior = DB::select("SELECT SUM(ImporteNeto) as total from LineasAlbaranCliente WHERE EjercicioFactura = '$aniopasado' and CodigoEmpresa='$empresa'  and CodigoFamilia <> '116' ");
            $ventasAnualesAnterior = DB::select("SELECT SUM(BaseImponible) as total 
            from dashboard 
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente
            AND dashboard.EjercicioAlbaran = '$aniopasado'         
            and dashboard.CodigoEmpresa = '$empresa'
            and dashboard.CodigoFamilia <> '116'");

            $ventasComisionistaMensuales = DB::select("SELECT TOP 1
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $anioFechaActual 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
            from lineasAlbaranCliente where EjercicioFactura = $anioFechaActual and CodigoEmpresa=$empresa");

            $ventasComisionistaMensualesAnioAnterior = DB::select("SELECT TOP 1
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
            from lineasAlbaranCliente where EjercicioFactura = $aniopasado and CodigoEmpresa=$empresa");


            $ventasComisionistaMensualesDosAniosAnteriores = DB::select("SELECT TOP 1
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=1 ) AS ENERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=2 ) AS FEBRERO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=3 ) AS MARZO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=4 ) AS ABRIL,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=5 ) AS MAYO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=6 ) AS JUNIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=7 ) AS JULIO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=8 ) AS AGOSTO,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=9 ) AS SEPTIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=10 ) AS OCTUBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=11 ) AS NOVIEMBRE,
            (SELECT sum(BaseImponible) from dashboard
            inner join Clientes
            on dashboard.CodigoEmpresa = Clientes.CodigoEmpresa
            and dashboard.CodigoCliente = Clientes.CodigoCliente             
            where dashboard.codigoempresa= $empresa 
            and dashboard.EjercicioAlbaran = $aniopasado2 
            and dashboard.CodigoFamilia <> '116'
            and Month(dashboard.FechaAlbaran)=12 ) AS DICIEMBRE
            from lineasAlbaranCliente where EjercicioFactura = $aniopasado2 and CodigoEmpresa=$empresa");



            switch ($mesActual) {
                case 1:
                    $primero = 8; $segundo = 9; $tercero = 10; $cuarto = 11; $quinto = 12; $sexto = 1;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $aniopasado; $eQuinto = $aniopasado; $eSexto = $anioFechaActual;
                    break;
                case 2:
                    $primero = 9; $segundo = 10; $tercero = 11; $cuarto = 12; $quinto = 1; $sexto = 2;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $aniopasado; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 3:
                    $primero = 10; $segundo = 11; $tercero = 12; $cuarto = 1; $quinto = 2; $sexto = 3;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $aniopasado; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 4:
                    $primero = 11; $segundo = 12; $tercero = 1; $cuarto = 2; $quinto = 3; $sexto = 4;
                    $ePrimero = $aniopasado; $eSegundo = $aniopasado; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 5:
                    $primero = 12; $segundo = 1; $tercero = 2; $cuarto = 3; $quinto = 4; $sexto = 5;
                    $ePrimero = $aniopasado; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 6:
                    $primero = 1; $segundo = 2; $tercero = 3; $cuarto = 4; $quinto = 5; $sexto = 6;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 7:
                    $primero = 2; $segundo = 3; $tercero = 4; $cuarto = 5; $quinto = 6; $sexto = 7;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 8:
                    $primero = 3; $segundo = 4; $tercero = 5; $cuarto = 6; $quinto = 7; $sexto = 8;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 9:
                    $primero = 4; $segundo = 5; $tercero = 6; $cuarto = 7; $quinto = 8; $sexto = 9;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 10:
                    $primero = 5; $segundo = 6; $tercero = 7; $cuarto = 8; $quinto = 9; $sexto = 10;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 11:
                    $primero = 6; $segundo = 7; $tercero = 8; $cuarto = 9; $quinto = 10; $sexto = 11;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;
                case 12:
                    $primero = 7; $segundo = 8; $tercero = 9; $cuarto = 10; $quinto = 11; $sexto = 12;
                    $ePrimero = $anioFechaActual; $eSegundo = $anioFechaActual; $eTercero = $anioFechaActual; $eCuarto = $anioFechaActual; $eQuinto = $anioFechaActual; $eSexto = $anioFechaActual;
                    break;         
            }

            

            $preescriptorMes= DB::select("SELECT codCli.CodigoCliente, CodCli.RazonSocial,
            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa  
             and resumen.CodigoEmpresa='$empresa' and codCli.CodigoCliente = ResumenCliente.CodigoCliente and Year(resumen.FechaAlbaran) = '$ePrimero'
             and Month(resumen.FechaAlbaran)='$primero' ) AS primero,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa  
             and resumen.CodigoEmpresa='$empresa' and codCli.CodigoCliente = ResumenCliente.CodigoCliente and Year(resumen.FechaAlbaran) = '$eSegundo'
             and Month(resumen.FechaAlbaran)='$segundo' ) AS segundo,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa  
             and resumen.CodigoEmpresa='$empresa' and codCli.CodigoCliente = ResumenCliente.CodigoCliente and Year(resumen.FechaAlbaran) = '$eTercero'
             and Month(resumen.FechaAlbaran)='$tercero' ) AS tercero,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa 
             and resumen.CodigoEmpresa='$empresa' and codCli.CodigoCliente = ResumenCliente.CodigoCliente and Year(resumen.FechaAlbaran) = '$eCuarto'
             and Month(resumen.FechaAlbaran)='$cuarto' ) AS cuarto,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa  
             and resumen.CodigoEmpresa='$empresa' and codCli.CodigoCliente = ResumenCliente.CodigoCliente and Year(resumen.FechaAlbaran) = '$eQuinto'
             and Month(resumen.FechaAlbaran)='$quinto' ) AS quinto,

            (select sum(resumen.ImporteNeto) from lineasAlbaranCliente AS resumen 
            inner join ResumenCliente 
            on resumen.CodigoEmpresa = ResumenCliente.CodigoEmpresa 
            and resumen.EjercicioFactura = ResumenCliente.EjercicioFactura
            and resumen.SerieFactura = ResumenCliente.SerieFactura
            and resumen.NumeroFactura = ResumenCliente.NumeroFactura
            where resumen.codigoempresa=rc.codigoempresa  
            and resumen.CodigoEmpresa='$empresa' 
            and codCli.CodigoCliente = ResumenCliente.CodigoCliente 
            and Year(resumen.FechaAlbaran) = '$eSexto'
            and Month(resumen.FechaAlbaran)='$sexto' ) AS sexto

            from lineasAlbaranCliente AS rc                         
            
            inner join ResumenCliente As codCli 
            on codCli.CodigoEmpresa = rc.CodigoEmpresa 
            and codCli.EjercicioFactura = rc.EjercicioFactura 
            and codCli.SerieFactura = rc.SerieFactura 
            and codCli.NumeroFactura = rc.NumeroFactura

            inner join Clientes As cli on codCli.CodigoCliente = cli.CodigoCliente 

            where cli.CodigoCategoriaCliente_ = 'CLI'
            AND rc.EjercicioFactura IN($aniopasado,$anioFechaActual)
            and rc.CodigoEmpresa = '$empresa'
            group by codCli.Codigocliente, codCli.RazonSocial, rc.Codigoempresa
            order by codCli.CodigoCliente asc");

            //return $preescriptorMes;

            $pedidos6Meses= DB::select("SELECT FechaAlbaran as FechaFactura, sum(ImporteNeto) AS Total , count(FechaAlbaran) AS Cantidad from LineasAlbaranCliente 
            where CodigoEmpresa=$empresa and FechaAlbaran BETWEEN DATEADD(MM, -6,GETDATE()) and GETDATE() and CodigoFamilia <> '116'
            GROUP BY FechaAlbaran
            order by FechaAlbaran desc");

            $ventasComisionista = DB::select("SELECT Top 6  Periodo, sum(TotalDia)as TotalMes, EjercicioAlbaran FROM ResumenCliente2 
            WHERE EjercicioAlbaran IN ($aniopasado,$anioFechaActual) and CodigoEmpresa = $empresa  And CodigoFamilia <> 116
            GROUP BY EjercicioAlbaran, Periodo 
            ORDER BY  EjercicioAlbaran desc, Periodo desc");

            $totalVentasComisionista = DB::select("	SELECT top 6 CodigoEmpresa, EjercicioAlbaran, Periodo, sum(TotalDia) as TotalMes FROM ResumenCliente2             
            WHERE EjercicioAlbaran IN ($aniopasado,$anioFechaActual) 
            and CodigoEmpresa = $empresa  And CodigoFamilia <> 116
            group by CodigoEmpresa,EjercicioAlbaran, Periodo
            order by EjercicioAlbaran desc, Periodo desc");


            // $totalVentasComisionista = DB::select("	SELECT Top 6  sum(TotalDia)as TotalMes FROM ResumenCliente1 
            // WHERE EjercicioFactura IN ($aniopasado,$anioFechaActual) and CodigoComisionista = $comisionista");

            if(empty($ventasComisionistaMensualesDosAniosAnteriores)){
                $ventasComisionistaMensualesDosAniosAnteriores[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }
            
            if(empty($ventasComisionistaMensuales)){
                $ventasComisionistaMensuales[0] = ["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }

            if(empty($ventasComisionistaMensualesAnioAnterior)){
                $ventasComisionistaMensualesAnioAnterior[0]=["ENERO"=>null,"FEBRERO"=>null,"MARZO"=>null,"ABRIL"=>null,"MAYO"=>null,"JUNIO"=>null,"JULIO"=>null,"AGOSTO"=>null,"SEPTIEMBRE"=>null,"OCTUBRE"=>null,"NOVIEMBRE"=>null,"DICIEMBRE"=>null];
            }
            //return $totalVentasComisionista;


            //CLIENTES ACTIVOS
            $clientesActivos = DB::select("SELECT Count(Clientes.CodigoCliente) AS activos from Clientes  
                                            INNER JOIN Comisionistas ON Clientes.CodigoComisionista = Comisionistas.Codigocomisionista
                                            AND Clientes.CodigoEmpresa  = Comisionistas.CodigoEmpresa
                                            INNER JOIN CabeceraAlbaranCliente ON Clientes.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                            AND Clientes.CodigoEmpresa = CabeceraAlbaranCliente.CodigoEmpresa
                                            WHERE Clientes.CodigoEmpresa = $empresa 
                                            AND CabeceraAlbaranCliente.FechaAlbaran > DATEADD(d,-30,GETDATE())");

            $clientesActivosMesAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista 
                                                    AND ClientesActivos.CodigoEmpresa = Comisionistas.CodigoEmpresa
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    AND ClientesActivos.CodigoEmpresa = CabeceraAlbaranCliente.CodigoEmpresa
                                                    WHERE ClientesActivos.Periodo = $mesAnterior AND ClientesActivos.Ejercicio = $anioFechaActual 
                                                    AND ClientesActivos.CodigoEmpresa = $empresa");

            $clientesActivosAnioAnterior = DB::select("SELECT Count(ClientesActivos.CodigoCliente) AS activos from ClientesActivos 
                                                    INNER JOIN Comisionistas ON ClientesActivos.CodigoComisionista = Comisionistas.Codigocomisionista 
                                                    AND ClientesActivos.CodigoEmpresa = Comisionistas.CodigoEmpresa 
                                                    INNER JOIN CabeceraAlbaranCliente ON ClientesActivos.CodigoCliente = CabeceraAlbaranCliente.CodigoCliente
                                                    AND CabeceraAlbaranCliente.CodigoEmpresa = ClientesActivos.CodigoEmpresa
                                                    where ClientesActivos.Periodo = $mesActual AND ClientesActivos.Ejercicio = $aniopasado 
                                                    AND ClientesActivos.CodigoEmpresa = $empresa");
            
            //TABLA CLIENTES ACTIVOS
            $tablaClientesCount = DB::select("SELECT  count(*) as total FROM ClientesActivos6 WHERE ClientesActivos6.CodigoEmpresa = $empresa");
            $tablaClientes = DB::select("SELECT  TOP 2000 ClientesActivos6.CodigoCliente, ClientesActivos6.RazonSocial, ClientesActivos6.CodigoEmpresa FROM ClientesActivos6  
                                        WHERE ClientesActivos6.CodigoEmpresa = $empresa                                                                                                           
                                        GROUP BY ClientesActivos6.CodigoCliente,  ClientesActivos6.RazonSocial, ClientesActivos6.CodigoEmpresa");

            //Clientes Nuevos
            $nuevosClientes = DB::select("SELECT COUNT(*) AS usuarios From NuevosUsuarios WHERE periodo = $mesActual AND ejercicio = $anioFechaActual AND CodigoEmpresa = $empresa");
            $nuevosClientesMesAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesAnterior and ejercicio = $anioFechaActual AND CodigoEmpresa = $empresa");
            $nuevosClietesAnioAnterior = DB::select("SELECT COUNT(*) as usuarios From NuevosUsuarios WHERE periodo = $mesActual and ejercicio = $aniopasado AND CodigoEmpresa = $empresa");

        }

            return view('dashboard')->with('semanal', $ventasSemanales)->with('semanaAnterior', $ventasSemanaAnterior)->with('mes', $ventasMes)
            ->with('mesAnterior', $ventasMesAnterior)->with('mesAnteriorAnio', $ventasMesAnioAnterior)->with('anual', $ventasAnuales)
            ->with('anualAnterior', $ventasAnualesAnterior)->with('resumenAnual', $ventasComisionistaMensuales)
            ->with('resumenAnual2', $ventasComisionistaMensualesAnioAnterior)->with('resumenAnual3', $ventasComisionistaMensualesDosAniosAnteriores)
            ->with('preescriptorMes', $preescriptorMes)->with('pedidos6Meses', $pedidos6Meses)->with('ventasComisionista', $ventasComisionista)
            ->with('clientesActivos', $clientesActivos)->with('clientesActivosMesAnterior', $clientesActivosMesAnterior)->with('clientesActivosAnioAnterior', $clientesActivosAnioAnterior)
            ->with('tablaClientesCount', $tablaClientesCount)->with('tablaClientes', $tablaClientes)
            ->with('nuevosClientes', $nuevosClientes)->with('nuevosClientesMesAnterior', $nuevosClientesMesAnterior)->with('nuevosClietesAnioAnterior', $nuevosClietesAnioAnterior)
            ->with('totalVentasComisionista', $totalVentasComisionista);
        
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
     * @param  \App\Models\dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(dashboard $dashboard)
    {
        //
    }
}
