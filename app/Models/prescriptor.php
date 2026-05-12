<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prescriptor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Comisionistas";
    protected $primaryKey = 'IdComisionista';
    protected $fillable = ['%Comision as Comision'];
}
