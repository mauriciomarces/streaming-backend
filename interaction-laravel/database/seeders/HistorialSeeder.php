<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Historial;
use Carbon\Carbon;

class HistorialSeeder extends Seeder
{
    /**
     * IDs de Mongo del catálogo (llenar después de correr GET /seed en el catalog-service).
     * Para el seeder usamos IDs simulados; en producción reemplazar con IDs reales.
     */
    private array $usuarios = [
        1 => ['nombre' => 'Lucía Martínez',  'avatar_color' => '#e50914'],
        2 => ['nombre' => 'Carlos Rodríguez', 'avatar_color' => '#0077b6'],
        3 => ['nombre' => 'Valentina López',  'avatar_color' => '#7b2d8b'],
        4 => ['nombre' => 'Diego Fernández',  'avatar_color' => '#2d9e5f'],
        5 => ['nombre' => 'Isabella Torres',  'avatar_color' => '#e5a009'],
    ];

    // Simulación de IDs de contenido y sus datos para el historial
    private array $contenidos = [
        ['id' => 'c001', 'titulo' => 'Dune: Parte Dos',          'tipo' => 'pelicula', 'duracion' => 9840,  'poster_color' => '#b5860d', 'poster_inicial' => 'D2'],
        ['id' => 'c002', 'titulo' => 'Oppenheimer',              'tipo' => 'pelicula', 'duracion' => 11100, 'poster_color' => '#8b2500', 'poster_inicial' => 'OP'],
        ['id' => 'c003', 'titulo' => 'Breaking Bad',             'tipo' => 'serie',    'duracion' => 2700,  'poster_color' => '#1a3300', 'poster_inicial' => 'BB'],
        ['id' => 'c004', 'titulo' => 'Stranger Things',          'tipo' => 'serie',    'duracion' => 3000,  'poster_color' => '#0a0a2e', 'poster_inicial' => 'ST'],
        ['id' => 'c005', 'titulo' => 'Inception',                'tipo' => 'pelicula', 'duracion' => 8880,  'poster_color' => '#2d3561', 'poster_inicial' => 'IC'],
        ['id' => 'c006', 'titulo' => 'The Last of Us',           'tipo' => 'serie',    'duracion' => 3600,  'poster_color' => '#2d1b00', 'poster_inicial' => 'LU'],
        ['id' => 'c007', 'titulo' => 'Spider-Man: No Way Home',  'tipo' => 'pelicula', 'duracion' => 9000,  'poster_color' => '#1a0033', 'poster_inicial' => 'SM'],
        ['id' => 'c008', 'titulo' => 'The Dark Knight',          'tipo' => 'pelicula', 'duracion' => 9120,  'poster_color' => '#101010', 'poster_inicial' => 'DK'],
        ['id' => 'c009', 'titulo' => 'Severance',                'tipo' => 'serie',    'duracion' => 3300,  'poster_color' => '#003366', 'poster_inicial' => 'SV'],
        ['id' => 'c010', 'titulo' => 'Squid Game',               'tipo' => 'serie',    'duracion' => 2940,  'poster_color' => '#1a4a2e', 'poster_inicial' => 'SQ'],
        ['id' => 'c011', 'titulo' => 'Parasite',                 'tipo' => 'pelicula', 'duracion' => 8220,  'poster_color' => '#1a1a1a', 'poster_inicial' => 'PA'],
        ['id' => 'c012', 'titulo' => 'House of the Dragon',      'tipo' => 'serie',    'duracion' => 3900,  'poster_color' => '#4a0000', 'poster_inicial' => 'HD'],
        ['id' => 'c013', 'titulo' => 'Interstelar',              'tipo' => 'pelicula', 'duracion' => 10140, 'poster_color' => '#0d1b4b', 'poster_inicial' => 'IN'],
        ['id' => 'c014', 'titulo' => 'Dark',                     'tipo' => 'serie',    'duracion' => 3000,  'poster_color' => '#001a1a', 'poster_inicial' => 'DK'],
        ['id' => 'c015', 'titulo' => 'Shogun',                   'tipo' => 'serie',    'duracion' => 3600,  'poster_color' => '#1a0d00', 'poster_inicial' => 'SH'],
    ];

    public function run(): void
    {
        Historial::truncate();

        // Historial de cada usuario
        $historiales = [
            // Usuario 1 - Lucía: fan de películas de acción y sci-fi
            1 => [
                ['id_contenido' => 'c001', 'progreso' => 9840,  'completado' => true,  'dias' => 1],
                ['id_contenido' => 'c005', 'progreso' => 8880,  'completado' => true,  'dias' => 3],
                ['id_contenido' => 'c007', 'progreso' => 9000,  'completado' => true,  'dias' => 5],
                ['id_contenido' => 'c008', 'progreso' => 4500,  'completado' => false, 'dias' => 7],
                ['id_contenido' => 'c013', 'progreso' => 10140, 'completado' => true,  'dias' => 10],
                ['id_contenido' => 'c002', 'progreso' => 6000,  'completado' => false, 'dias' => 12],
            ],
            // Usuario 2 - Carlos: fan de series de crimen y drama
            2 => [
                ['id_contenido' => 'c003', 'progreso' => 2700,  'completado' => true,  'dias' => 1],
                ['id_contenido' => 'c010', 'progreso' => 2940,  'completado' => true,  'dias' => 2],
                ['id_contenido' => 'c011', 'progreso' => 8220,  'completado' => true,  'dias' => 4],
                ['id_contenido' => 'c012', 'progreso' => 1200,  'completado' => false, 'dias' => 6],
                ['id_contenido' => 'c009', 'progreso' => 3300,  'completado' => true,  'dias' => 8],
                ['id_contenido' => 'c014', 'progreso' => 2100,  'completado' => false, 'dias' => 14],
                ['id_contenido' => 'c015', 'progreso' => 3600,  'completado' => true,  'dias' => 16],
            ],
            // Usuario 3 - Valentina: mix de series y algo de películas
            3 => [
                ['id_contenido' => 'c004', 'progreso' => 3000,  'completado' => true,  'dias' => 2],
                ['id_contenido' => 'c006', 'progreso' => 3600,  'completado' => true,  'dias' => 3],
                ['id_contenido' => 'c009', 'progreso' => 1650,  'completado' => false, 'dias' => 5],
                ['id_contenido' => 'c001', 'progreso' => 5000,  'completado' => false, 'dias' => 7],
                ['id_contenido' => 'c015', 'progreso' => 3600,  'completado' => true,  'dias' => 9],
            ],
            // Usuario 4 - Diego: fan de películas clásicas y ciencia ficción
            4 => [
                ['id_contenido' => 'c008', 'progreso' => 9120,  'completado' => true,  'dias' => 1],
                ['id_contenido' => 'c005', 'progreso' => 8880,  'completado' => true,  'dias' => 2],
                ['id_contenido' => 'c013', 'progreso' => 8000,  'completado' => false, 'dias' => 4],
                ['id_contenido' => 'c002', 'progreso' => 11100, 'completado' => true,  'dias' => 6],
                ['id_contenido' => 'c011', 'progreso' => 8220,  'completado' => true,  'dias' => 9],
                ['id_contenido' => 'c014', 'progreso' => 3000,  'completado' => true,  'dias' => 11],
            ],
            // Usuario 5 - Isabella: fan de series de suspenso y misterio
            5 => [
                ['id_contenido' => 'c010', 'progreso' => 2940,  'completado' => true,  'dias' => 1],
                ['id_contenido' => 'c003', 'progreso' => 2700,  'completado' => true,  'dias' => 3],
                ['id_contenido' => 'c004', 'progreso' => 1500,  'completado' => false, 'dias' => 4],
                ['id_contenido' => 'c012', 'progreso' => 3900,  'completado' => true,  'dias' => 6],
                ['id_contenido' => 'c009', 'progreso' => 3300,  'completado' => true,  'dias' => 8],
                ['id_contenido' => 'c006', 'progreso' => 2000,  'completado' => false, 'dias' => 10],
                ['id_contenido' => 'c015', 'progreso' => 3600,  'completado' => true,  'dias' => 12],
            ],
        ];

        $contenidoMap = collect($this->contenidos)->keyBy('id');

        foreach ($historiales as $id_usuario => $items) {
            foreach ($items as $item) {
                $c = $contenidoMap[$item['id_contenido']];
                Historial::create([
                    'id_usuario'             => $id_usuario,
                    'id_contenido'           => $item['id_contenido'],
                    'tipo_contenido'         => $c['tipo'],
                    'progreso_segundos'      => $item['progreso'],
                    'duracion_total_segundos' => $c['duracion'],
                    'visto_completado'       => $item['completado'],
                    'fecha_ultima_vista'     => Carbon::now()->subDays($item['dias']),
                    'created_at'             => Carbon::now()->subDays($item['dias']),
                    'updated_at'             => Carbon::now()->subDays($item['dias']),
                ]);
            }
        }

        $this->command->info('✅ HistorialSeeder: ' . array_sum(array_map('count', $historiales)) . ' registros insertados para 5 usuarios.');
    }
}
