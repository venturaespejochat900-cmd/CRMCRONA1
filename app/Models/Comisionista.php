<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comisionista extends Model
{
    use HasFactory;
    protected $table='Comisionistas';
    protected $primaryKey = 'CodigoComisionista';
    protected $fillable = ['%Comision as Comision'];
}
