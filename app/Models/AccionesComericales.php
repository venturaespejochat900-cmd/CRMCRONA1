<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionesComericales extends Model
{
    use HasFactory;
    protected $table='LcComisionistaAgenda';
    public $timestamps = false;
}
