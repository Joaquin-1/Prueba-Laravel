<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depart extends Model
{
    use HasFactory;

    protected $table = 'depart'; //Se le pone esto porque no sigue el convenio que seria que la tabla se llamara departs.

    protected $fillable = ['denominacion', 'localidad'];
}
