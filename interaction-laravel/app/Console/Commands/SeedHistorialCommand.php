<?php

namespace App\Console\Commands;

use App\Models\Historial;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SeedHistorialCommand extends Command
{
    protected $signature = 'seed:historial';
    protected $description = 'Pobla la base de datos con historiales de visualización para los 5 usuarios';

    public function handle()
    {
        $this->info('🎬 Creando historiales de visualización...');
        
        // Contenido simulado (debe coincidir con los IDs del catálogo)
        $contenidos = [
            // Películas
            'c001', 'c002', 'c005', 'c007', 'c008', 'c011', 'c013',
            // Series
            'c003', 'c004', 'c006', 'c009', 'c010', 'c012', 'c014', 'c015'
        ];

        // Configuración de duraciones (en segundos)
        $duraciones = [
            'c001' => 9840,   // Dune: Parte Dos
            'c002' => 11100,  // Oppenheimer
            'c003' => 2700,   // Breaking Bad (ep)
            'c004' => 3000,   // Stranger Things (ep)
            'c005' => 8880,   // Inception
            'c006' => 3600,   // The Last of Us (ep)
            'c007' => 9000,   // Spider-Man: No Way Home
            'c008' => 9120,   // The Dark Knight
            'c009' => 3300,   // Severance (ep)
            'c010' => 2940,   // Squid Game (ep)
            'c011' => 8220,   // Parasite
            'c012' => 3900,   // House of the Dragon (ep)
            'c013' => 10140,  // Interstellar
            'c014' => 3000,   // Dark (ep)
            'c015' => 3600,   // Shogun (ep)
        ];

        $usuarios = [1, 2, 3, 4, 5];
        $ahora = Carbon::now();
        
        foreach ($usuarios as $usuario_id) {
            $historialesCreados = 0;
            
            // Cada usuario ve entre 8-12 títulos
            $titulos = array_rand(array_flip($contenidos), rand(8, 12));
            
            foreach ($titulos as $contenido_id) {
                // Algunos títulos completados, otros en progreso
                $duracion = $duraciones[$contenido_id] ?? 3600;
                $progreso = rand(0, 100);
                $progreso_segundos = (int)($duracion * ($progreso / 100));
                $visto_completado = $progreso >= 90;

                // Fecha aleatoria en los últimos 30 días
                $dias_atras = rand(0, 30);
                $fecha = $ahora->clone()->subDays($dias_atras)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                Historial::create([
                    'id_usuario' => $usuario_id,
                    'id_contenido' => $contenido_id,
                    'tipo_contenido' => in_array($contenido_id, ['c003', 'c004', 'c006', 'c009', 'c010', 'c012', 'c014', 'c015']) ? 'serie' : 'pelicula',
                    'progreso_segundos' => $progreso_segundos,
                    'duracion_total_segundos' => $duracion,
                    'visto_completado' => $visto_completado,
                    'fecha_ultima_vista' => $fecha,
                    'dispositivo' => ['web', 'mobile', 'tablet'][rand(0, 2)],
                ]);
                
                $historialesCreados++;
            }
            
            $this->info("✅ Usuario {$usuario_id}: {$historialesCreados} historiales creados");
        }

        $this->info('🎉 Historiales creados exitosamente!');
        $this->line('Total de registros: ' . Historial::count());
    }
}
