<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropDownArticulo extends Model
{
    use HasFactory;
    protected $table='DropDownArticulo';
    protected $cast = [
        'StockMinimo' => 'float', 
        'PrecioVenta' => 'float',
        'UnidadSaldo' => 'float',
        'PendienteRecibir' => 'float',
        'StockReservado' => 'float'
    ];
}
