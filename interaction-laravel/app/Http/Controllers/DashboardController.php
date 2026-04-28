<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|string
    {
        return view('dashboard');
    }

    public function stats()
    {
        $titulosUnicos = Historial::distinct('id_contenido')->count('id_contenido');

        $usuariosUnicos = Historial::distinct('id_usuario')->count('id_usuario');

        $titulosCompletados = Historial::where('visto_completado', true)
            ->distinct('id_contenido')
            ->count('id_contenido');

        $titulosEnProgreso = Historial::where('visto_completado', false)
            ->distinct('id_contenido')
            ->count('id_contenido');

        return response()->json([
            'total_titulos_unicos' => $titulosUnicos,
            'usuarios_unicos' => $usuariosUnicos,
            'completados' => $titulosCompletados,
            'en_progreso' => $titulosEnProgreso,
            'usuarios' => [
                ['id' => 1, 'nombre' => 'Lucía Martínez',  'avatar_color' => '#e50914', 'iniciales' => 'LM'],
                ['id' => 2, 'nombre' => 'Carlos Rodríguez', 'avatar_color' => '#0077b6', 'iniciales' => 'CR'],
                ['id' => 3, 'nombre' => 'Valentina López', 'avatar_color' => '#7b2d8b', 'iniciales' => 'VL'],
                ['id' => 4, 'nombre' => 'Diego Fernández', 'avatar_color' => '#2d9e5f', 'iniciales' => 'DF'],
                ['id' => 5, 'nombre' => 'Isabella Torres', 'avatar_color' => '#e5a009', 'iniciales' => 'IT'],
            ]
        ]);
    }
}
