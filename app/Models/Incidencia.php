<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;
    protected $table='ABMS_CabeceraIncidencias';
    public $timestamps = false;
    //protected $primaryKey = '';
}
