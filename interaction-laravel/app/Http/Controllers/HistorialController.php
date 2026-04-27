<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Services\CatalogService;
use App\Http\Requests\StoreHistorialRequest;

class HistorialController extends Controller
{
    protected $catalog;

    public function __construct(CatalogService $catalog)
    {
        $this->catalog = $catalog;
    }

    // 🟢 CREAR HISTORIAL
    public function store(StoreHistorialRequest $request)
    {
        // 1️⃣ obtener contenido desde servicio
        $contenido = $this->catalog->getContenido($request->id_contenido);

        // 2️⃣ validar existencia
        if (!$contenido) {
            return response()->json([
                'error' => 'El contenido no existe en el catálogo'
            ], 404);
        }

        // 3️⃣ guardar historial
        $historial = Historial::create([
            'id_usuario' => $request->id_usuario,
            'id_contenido' => $contenido['id'],
            'tipo_contenido' => $contenido['tipo_contenido'],
            'progreso_segundos' => $request->progreso_segundos,
            'duracion_total_segundos' => $contenido['duracion_total_segundos'],
            'visto_completado' =>
            $request->progreso_segundos >= $contenido['duracion_total_segundos'],
            'fecha_ultima_vista' => now(),
        ]);

        return response()->json($historial, 201);
    }

    // 🔵 HISTORIAL POR USUARIO
    public function getByUser($id_usuario)
    {
        return Historial::where('id_usuario', (int)$id_usuario)
            ->orderBy('fecha_ultima_vista', 'desc')
            ->get();
    }

    // 🔍 VER UN HISTORIAL POR ID
    public function show($id)
    {
        $historial = Historial::find($id);

        if (!$historial) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json($historial);
    }

    // 🔍 VER HISTORIAL + DATOS DEL CATÁLOGO
    public function showWithContent($id)
    {
        $historial = Historial::find($id);

        if (!$historial) {
            return response()->json(['error' => 'Historial no encontrado'], 404);
        }

        $contenido = $this->catalog->getContenido($historial->id_contenido);

        return response()->json([
            'historial'  => $historial,
            'contenido'  => $contenido ?? ['error' => 'Contenido no disponible en catálogo'],
        ]);
    }

    // 🟡 ACTUALIZAR
    public function update($id, StoreHistorialRequest $request)
    {
        $historial = Historial::find($id);

        if (!$historial) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        $historial->update([
            'progreso_segundos' => $request->progreso_segundos ?? $historial->progreso_segundos,
            'visto_completado' => $request->visto_completado ?? $historial->visto_completado,
            'fecha_ultima_vista' => now()
        ]);

        return response()->json($historial);
    }

    // 🔴 ELIMINAR
    public function destroy($id)
    {
        $historial = Historial::find($id);

        if (!$historial) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        $historial->delete();

        return response()->json(['mensaje' => 'Eliminado correctamente']);
    }
}
