<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Historial extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'historial';

    protected $fillable = [
        'id_usuario',
        'id_contenido',
        'tipo_contenido',
        'progreso_segundos',
        'duracion_total_segundos',
        'visto_completado',
        'fecha_ultima_vista',
        'dispositivo'
    ];
}
