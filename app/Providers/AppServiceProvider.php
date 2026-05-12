<?php

namespace App\Providers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session(['modalTablaPrescriptores' => 0]);
        Session(['codigoComisionista' => 0]);
        Session(['codigoEmpresa' => 0]);
    }
}
