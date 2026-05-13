<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Campos 2FA en tabla Sage Comisionistas
    |--------------------------------------------------------------------------
    |
    | Cree estos campos en Sage sobre la tabla Comisionistas (no use migraciones
    | Laravel sobre tablas Sage). Los nombres aquí deben coincidir EXACTAMENTE
    | con los generados en SQL Server (mayúsculas/minúsculas según corresponda).
    |
    | Tipos recomendados en SQL Server (ajuste al asistente de Sage):
    | - Secreto: NVARCHAR(MAX) NULL (texto cifrado con APP_KEY de Laravel)
    | - Confirmación: DATETIME2 NULL o DATETIME NULL (fecha en que el usuario
    |   confirmó el código de la app; NULL = 2FA no activado aún)
    |
    */

    'secret_column' => env('SAGE_COMISIONISTA_2FA_SECRET', 'CRM_TwoFactorSecret'),

    'confirmed_column' => env('SAGE_COMISIONISTA_2FA_CONFIRMED', 'CRM_TwoFactorConfirmedAt'),

];
