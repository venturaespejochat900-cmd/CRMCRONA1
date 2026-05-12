<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Comisionista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComisionistaController extends Controller
{

    public static function obtenerDatosComisionistaPedido($comisionista){
        $query = Comisionista::where('CodigoComisionista','=',$comisionista)->get();
        return $query;
    }

    public static function comisionista($codigo){
        $query = Comisionista::select('Comisionista')
        ->where('CodigoComisionista', '=', $codigo)
        ->where('CodigoEmpresa', '=', session('codigoEmpresa'))
        ->get();

        return $query[0]->Comisionista;
    }

    public function buscarPrescriptores(){
        $query = Comisionista::where('Comisionista','LIKE',"%".$_POST['prescriptorAbuscar']."%")->get();
        $html = "";
        if(count($query)!= 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                $html .="
                        <tr>
                        <td>".$value['CodigoComisionista']."</td>
                        <td>".$value['Comisionista']."</td>
                        <td><button class='btn ' id='btnPrescriptor' onclick='seleccionarPrescriptor(".$value.")'><i class='far fa-hand-pointer'></i>

                        </tr>
                 ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen prescriptores con esos datos</div>";
        }
        return $html;
    }

    public function buscarConsultaPrescriptores(){
        $query = Comisionista::where('Comisionista','LIKE',"%".$_POST['prescriptorAbuscar']."%")->get();
        $html = "";
        if(count($query)!= 0){
            $html = "<table class='table table-border'>
                        <thead>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>";
            foreach ($query as $value){
                $html .="
                        <tr>
                        <td>".$value['CodigoComisionista']."</td>
                        <td>".$value['Comisionista']."</td>
                        <td><button class='btn ' id='btnPrescriptor' onclick='seleccionarPrescriptorConsultaModal(".$value.")'><i class='far fa-hand-pointer'></i>
                        </tr>
                 ";
            }
            $html .= "</tbody>
                        </table>";
        }else{
            $html = "<div class='alert alert-warning'>No existen prescriptores con esos datos</div>";
        }
        return $html;
    }

    public function prescriptorAsociado(){
        $query = Comisionista::select('Comisionista')->where('CodigoComisionista','=',$_POST['prescriptor'])->get();
        return $query;
    }

    public function obtenerClientesComisionista(){
        $query = Cliente::select ('CodigoCliente', 'RazonSocial')
            ->where('CodigoComisionista','=',$_POST['codigoComisionista'])->get();
        $html = "<div class='listadoNombres'>";
        if(count($query)!= 0){
            foreach ($query as $value){
                $html .= "
                <table class='table table-hover'><tr><td>".$value['CodigoCliente']."</td>
                <td>".$value['RazonSocial']."</td></tr></table>" ;
            }
        }
        $html .= "</div>";
        return $html;
    }

    public static function comprobarPinOperario(){
        $correcto = "ERROR";
        $query = Comisionista::where('VPinOperario','=',$_POST['pinOperario'])->first();
        if($query) {
            $correcto = "OK";
            Session(['codigoComisionista'=>$query->CodigoComisionista]);
        }
        return $correcto;
    }

    public static function comprobarPassword(){
        $correcto = "ERROR";
        $query = Comisionista::where('CodigoComisionista',session('codigoComisionista'))
            ->where('AccesoPass',$_POST['password'])->get();
        if(count($query) > 0){
            $correcto = "OK";
        }
        return $correcto;
    }

    public static function modificarPassword(){
        $correcto = "ERROR";
        $query = Comisionista::where('CodigoComisionista',session('codigoComisionista'))
            ->update(["AccesoPass"=>$_POST['password']]);
        if ($query == 1){
            $correcto = "OK";
        }
        return $correcto;
    }
    public static function modificarPin(){
        $correcto = "ERROR";
        $query = Comisionista::where('CodigoComisionista',session('codigoComisionista'))
            ->update(["VPinOperario"=>$_POST['pin']]);
        if ($query == 1){
            $correcto = "OK";
        }
        return $correcto;
    }
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
     * @param  \App\Models\Comisionista  $comisionista
     * @return \Illuminate\Http\Response
     */
    public function show(Comisionista $comisionista)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comisionista  $comisionista
     * @return \Illuminate\Http\Response
     */
    public function edit(Comisionista $comisionista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comisionista  $comisionista
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comisionista $comisionista)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comisionista  $comisionista
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comisionista $comisionista)
    {
        //
    }
}
