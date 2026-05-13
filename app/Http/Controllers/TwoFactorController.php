<?php

namespace App\Http\Controllers;

use App\Models\Comisionista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    private const SESSION_PENDING_ID = 'two_factor_login_pending_id';

    private const SESSION_ENROLL_SECRET = 'two_factor_enroll_secret_enc';

    public function showLoginVerification()
    {
        if (!session(self::SESSION_PENDING_ID)) {
            return redirect('/');
        }

        return view('auth.verificacion-2fa');
    }

    public function submitLoginVerification(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $id = session(self::SESSION_PENDING_ID);
        if (!$id) {
            return redirect('/');
        }

        $comisionista = Comisionista::where('CodigoComisionista', $id)->first();
        if (!$comisionista || !$comisionista->twoFactorIsConfigured()) {
            session()->forget(self::SESSION_PENDING_ID);

            return redirect('/')->withErrors(['code' => 'La sesión de verificación ha caducado o no es válida.']);
        }

        $secretCol = Comisionista::twoFactorSecretColumn();
        $secretEnc = $comisionista->getAttribute($secretCol);
        $secret = Crypt::decryptString($secretEnc);
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey($secret, $request->input('code'))) {
            return redirect()->back()->withErrors(['code' => 'Código incorrecto. Inténtelo de nuevo.'])->withInput();
        }

        session()->forget(self::SESSION_PENDING_ID);

        return LOGIN::completeLoginSession($comisionista);
    }

    public function cancelLoginVerification()
    {
        session()->forget(self::SESSION_PENDING_ID);

        return redirect('/');
    }

    public function showActivationForm()
    {
        if (!session('codigoComisionista')) {
            return redirect('/');
        }

        $comisionista = Comisionista::where('CodigoComisionista', session('codigoComisionista'))->first();
        if (!$comisionista) {
            return redirect('/');
        }

        return view('auth.configurar-2fa', [
            'comisionista' => $comisionista,
            'alreadyEnabled' => $comisionista->twoFactorIsConfigured(),
            'otpauthUrl' => null,
        ]);
    }

    public function startActivation(Request $request)
    {
        if (!session('codigoComisionista')) {
            return redirect('/');
        }

        $comisionista = Comisionista::where('CodigoComisionista', session('codigoComisionista'))->first();
        if (!$comisionista) {
            return redirect('/');
        }

        if ($comisionista->twoFactorIsConfigured()) {
            return redirect()->route('seguridad.2fa')->with('status', 'La verificación en dos pasos ya está activada.');
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $label = $comisionista->AccesoUsuario ?: ('comisionista_' . $comisionista->CodigoComisionista);
        $issuer = config('app.name', 'CRM');
        $otpauthUrl = $google2fa->getQRCodeUrl($issuer, $label, $secret);

        session([self::SESSION_ENROLL_SECRET => Crypt::encryptString($secret)]);

        return view('auth.configurar-2fa', [
            'comisionista' => $comisionista,
            'alreadyEnabled' => false,
            'otpauthUrl' => $otpauthUrl,
        ]);
    }

    public function confirmActivation(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!session('codigoComisionista') || !session(self::SESSION_ENROLL_SECRET)) {
            return redirect()->route('seguridad.2fa')->withErrors(['code' => 'Sesión de configuración caducada. Pulse «Generar código QR» de nuevo.']);
        }

        $comisionista = Comisionista::where('CodigoComisionista', session('codigoComisionista'))->first();
        if (!$comisionista) {
            session()->forget(self::SESSION_ENROLL_SECRET);

            return redirect('/');
        }

        $secret = Crypt::decryptString(session(self::SESSION_ENROLL_SECRET));
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey($secret, $request->input('code'))) {
            return $this->activationPendingView($comisionista)->withErrors(['code' => 'Código incorrecto.']);
        }

        $comisionista->setAttribute(Comisionista::twoFactorSecretColumn(), Crypt::encryptString($secret));
        $comisionista->setAttribute(Comisionista::twoFactorConfirmedColumn(), now());
        $comisionista->save();

        session()->forget(self::SESSION_ENROLL_SECRET);

        return redirect()->route('seguridad.2fa')->with('status', 'Verificación en dos pasos activada correctamente.');
    }

    /**
     * Desactiva 2FA tras validar un código TOTP actual (sesión ya autenticada).
     */
    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!session('codigoComisionista')) {
            return redirect('/');
        }

        $comisionista = Comisionista::where('CodigoComisionista', session('codigoComisionista'))->first();
        if (!$comisionista || !$comisionista->twoFactorIsConfigured()) {
            return redirect()->route('seguridad.2fa')->with('status', 'La verificación en dos pasos no estaba activa.');
        }

        $secretCol = Comisionista::twoFactorSecretColumn();
        $secretEnc = $comisionista->getAttribute($secretCol);
        try {
            $secret = Crypt::decryptString($secretEnc);
        } catch (\Throwable $e) {
            return redirect()->route('seguridad.2fa')->withErrors(['disable_code' => 'No se pudo leer el secreto. Contacte con administración.']);
        }

        $google2fa = new Google2FA();
        if (!$google2fa->verifyKey($secret, $request->input('code'))) {
            return redirect()->route('seguridad.2fa')->withErrors(['disable_code' => 'Código incorrecto.']);
        }

        $clearSecret = config('sage_2fa.cleared_secret');
        $clearConfirmed = config('sage_2fa.cleared_confirmed');

        $comisionista->setAttribute($secretCol, $clearSecret);
        $comisionista->setAttribute(Comisionista::twoFactorConfirmedColumn(), $clearConfirmed);
        $comisionista->save();

        session()->forget(self::SESSION_ENROLL_SECRET);

        return redirect()->route('seguridad.2fa')->with('status', 'Verificación en dos pasos desactivada. Puede borrar la cuenta en su app de autenticación.');
    }

    private function activationPendingView(Comisionista $comisionista)
    {
        if (!session(self::SESSION_ENROLL_SECRET)) {
            return redirect()->route('seguridad.2fa');
        }

        $secret = Crypt::decryptString(session(self::SESSION_ENROLL_SECRET));
        $google2fa = new Google2FA();
        $label = $comisionista->AccesoUsuario ?: ('comisionista_' . $comisionista->CodigoComisionista);
        $issuer = config('app.name', 'CRM');
        $otpauthUrl = $google2fa->getQRCodeUrl($issuer, $label, $secret);

        return view('auth.configurar-2fa', [
            'comisionista' => $comisionista,
            'alreadyEnabled' => false,
            'otpauthUrl' => $otpauthUrl,
        ]);
    }
}
