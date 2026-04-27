<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaUsuario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'listas_usuario';

    protected $fillable = [
        'id_usuario',
        'id_contenido',
        'tipo_lista',
        'fecha_agregado'
    ];
}
