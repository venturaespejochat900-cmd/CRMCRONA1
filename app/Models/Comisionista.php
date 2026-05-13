<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comisionista extends Model
{
    use HasFactory;

    protected $table = 'Comisionistas';

    protected $primaryKey = 'CodigoComisionista';

    protected $fillable = ['%Comision as Comision'];

    public static function twoFactorSecretColumn(): string
    {
        return config('sage_2fa.secret_column', 'CRM_TwoFactorSecret');
    }

    public static function twoFactorConfirmedColumn(): string
    {
        return config('sage_2fa.confirmed_column', 'CRM_TwoFactorConfirmedAt');
    }

    /**
     * 2FA TOTP activo: hay secreto guardado y fecha de confirmación.
     */
    public function twoFactorIsConfigured(): bool
    {
        $secret = $this->getAttribute(static::twoFactorSecretColumn());
        $confirmed = $this->getAttribute(static::twoFactorConfirmedColumn());

        return !empty($secret) && $confirmed !== null && $confirmed !== '';
    }
}
