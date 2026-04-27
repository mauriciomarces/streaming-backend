<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CatalogService
{
    public function getContenido($id)
    {
        $response = Http::get("http://catalog-service:3001/contenido/{$id}");

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        return $this->normalizar($data);
    }

    private function normalizar($data)
    {
        // 🧨 VALIDACIÓN DEL CONTRATO DEL CATÁLOGO
        if (!isset($data["_id"], $data["titulo"], $data["tipo"], $data["duracion_segundos"])) {
            return null;
        }

        return [
            "id" => $data["_id"],
            "titulo" => $data["titulo"],
            "tipo_contenido" => $data["tipo"],
            "duracion_total_segundos" => $data["duracion_segundos"],
        ];
    }
}
