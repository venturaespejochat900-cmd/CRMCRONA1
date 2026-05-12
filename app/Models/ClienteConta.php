<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteConta extends Model
{
    use HasFactory;
    protected $table='ClientesConta';
    public $timestamps = false;
    protected $primaryKey = 'CodigoCliente';
}
