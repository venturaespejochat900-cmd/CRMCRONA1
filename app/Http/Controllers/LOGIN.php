<?php

namespace App\Http\Controllers;

use App\Models\Comisionista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LOGIN extends Controller
{

    static function index(){
        return view('index');
    }


    static  function validarLogin(){
        $correcto = "ERROR";
        $query = Comisionista::where('AccesoUsuario','=',$_POST['email'])
        ->where('AccesoPass','=',$_POST['password'])->first();
        if ($query){     
            if($query->CodigoComisionista == 100){
                session(['codigoComisionista' => 55]);
            }else{
                session(['codigoComisionista' => $query->CodigoComisionista]);        
            }
        session(['comisionista'=>$query->Comisionista]);
        session(['codigoEmpresa' => $query->CodigoEmpresa]);
            if(($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 55) || ($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 36) || ($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 100)){
                session(['tipo' => 5]);
            }else if($query->IndicadorJefeVenta_ == -1){
                session(['tipo' => 3]);
            }else if($query->IndicadorJefeZona_ == -1){
                session(['tipo' => 2]);
            }else{
                session(['tipo' => 1]);
            }
    
        //return session('codigoComisionista');
        return redirect('/inicios');
        }
        else{  
            //return $correcto;
            return redirect()->back();
        }
       
    }

    // static function redirigirInicio(){
    //     if(isset($_GET['codigoAlmacen'])){
    //         session(['puntoVenta' => $_GET['codigoAlmacen']]);
    //     }
       
    //     return view('inicioPedido');
    // }


    static function inicioNuevoCliente(){        
        //return view('pedido.index');
        return view('pedido.nuevoPedido2');
    }

    static function inicioNuevoCliente2(){        
        //return view('pedido.index');
        return view('pedido.nuevoPedido');
    }

    static function inicioNuevoClienteOferta(){        
        //return view('pedido.index');
        return view('pedido.oferta');
    }

    static function inicioNuevoClienteOferta2(){        
        //return view('pedido.index');
        return view('pedido.nuevaOferta');
    }

    public static function obtenerAlmacen($id){
        $query = DB::table('Almacenes')->where('VTipoAlmacen','=','P')
            ->where('CodigoAlmacen','=',$id)->get();
        return $query;
    }
}
