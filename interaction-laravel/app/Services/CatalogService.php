<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CatalogService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('CATALOGO_URL', 'http://catalog-service:3001');
    }

    public function getContenido($id): ?array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/contenido/{$id}");
            if ($response->failed()) return null;
            return $this->normalizar($response->json());
        } catch (\Exception $e) {
            return null;
        }
    }

    public function listarContenido(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'application/json'])
                ->get("{$this->baseUrl}/contenido?format=json");
            return $response->ok() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function normalizar($data): ?array
    {
        if (!isset($data['_id'])) return null;

        return [
            'id'                    => $data['_id'],
            'titulo'                => $data['titulo']          ?? 'Sin título',
            'tipo_contenido'        => $data['tipo']            ?? 'pelicula',
            'duracion_total_segundos' => $data['duracion_segundos'] ?? 0,
            'duracion'              => $data['duracion']        ?? '',
            'anio'                  => $data['anio']            ?? null,
            'generos'               => $data['generos']         ?? [],
            'clasificacion'         => $data['clasificacion']   ?? '',
            'calificacion'          => $data['calificacion']    ?? null,
            'poster_color'          => $data['poster_color']    ?? '#1a1a2e',
            'poster_inicial'        => $data['poster_inicial']  ?? '',
            'descripcion'           => $data['descripcion']     ?? '',
            'temporadas'            => $data['temporadas']      ?? null,
        ];
    }
}
