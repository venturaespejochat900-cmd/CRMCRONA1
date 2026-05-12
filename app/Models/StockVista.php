<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockVista extends Model
{
    use HasFactory;
    protected $table='vistaStock';    
    public $timestamps = false;
    //protected $primaryKey = 'CodigoCliente';
}
