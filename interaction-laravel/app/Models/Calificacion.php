<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'calificaciones';

    protected $fillable = [
        'id_usuario',
        'id_contenido',
        'puntuacion',
        'comentario',
        'fecha_calificacion'
    ];
}
