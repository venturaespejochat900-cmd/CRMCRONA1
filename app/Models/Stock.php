<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table='AcumuladoStock';
    protected $casts= [
        'UnidadSaldo' => 'float',
        'Articulos.StockMinimo' => 'float',
    ];
    public $timestamps = false;
    //protected $primaryKey = 'CodigoCliente';
}
