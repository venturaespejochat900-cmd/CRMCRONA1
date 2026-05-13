<?php

namespace App\Http\Controllers;

use App\Models\Comisionista;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class LOGIN extends Controller
{
    public static function index()
    {
        return view('index');
    }

    public static function completeLoginSession(Comisionista $query): RedirectResponse
    {
        if ($query->CodigoComisionista == 100) {
            session(['codigoComisionista' => 55]);
        } else {
            session(['codigoComisionista' => $query->CodigoComisionista]);
        }
        session(['comisionista' => $query->Comisionista]);
        session(['codigoEmpresa' => $query->CodigoEmpresa]);
        if (($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 55)
            || ($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 36)
            || ($query->IndicadorJefeVenta_ == -1 && $query->CodigoComisionista == 100)) {
            session(['tipo' => 5]);
        } elseif ($query->IndicadorJefeVenta_ == -1) {
            session(['tipo' => 3]);
        } elseif ($query->IndicadorJefeZona_ == -1) {
            session(['tipo' => 2]);
        } else {
            session(['tipo' => 1]);
        }

        return redirect('/inicios');
    }

    public static function validarLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $query = Comisionista::where('AccesoUsuario', '=', $request->input('email'))
            ->where('AccesoPass', '=', $request->input('password'))
            ->first();

        if (!$query) {
            return redirect()->back()->withErrors(['email' => 'Usuario o contraseña incorrectos.'])->withInput();
        }

        if ($query->twoFactorIsConfigured()) {
            $request->session()->regenerate();
            session(['two_factor_login_pending_id' => $query->CodigoComisionista]);

            return redirect()->route('login.verificar2fa');
        }

        return self::completeLoginSession($query);
    }

    public static function redirigirInicio(Request $request)
    {
        if ($request->has('codigoAlmacen')) {
            session(['puntoVenta' => $request->input('codigoAlmacen')]);
        }

        return view('pedido.inicioPedido');
    }

    public static function inicioNuevoCliente()
    {
        return view('pedido.nuevoPedido2');
    }

    public static function inicioNuevoCliente2()
    {
        return view('pedido.nuevoPedido');
    }

    public static function inicioNuevoClienteOferta()
    {
        return view('pedido.oferta');
    }

    public static function inicioNuevoClienteOferta2()
    {
        return view('pedido.nuevaOferta');
    }

    public static function obtenerAlmacen($id)
    {
        $query = DB::table('Almacenes')->where('VTipoAlmacen', '=', 'P')
            ->where('CodigoAlmacen', '=', $id)->get();

        return $query;
    }
}
