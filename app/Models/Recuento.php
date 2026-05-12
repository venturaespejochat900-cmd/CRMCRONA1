<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recuento extends Model
{
    use HasFactory;
    protected $table='recuentoEmpresas';
    public $timestamps = false;
}
