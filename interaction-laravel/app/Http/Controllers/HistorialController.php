<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Services\CatalogService;
use App\Http\Requests\StoreHistorialRequest;

class HistorialController extends Controller
{
    protected $catalog;

    // Mapa local de usuarios (en producción vendría de BD de usuarios)
    private array $usuarios = [
        1 => ['nombre' => 'Lucía Martínez',   'avatar_color' => '#e50914', 'inicial' => 'LM'],
        2 => ['nombre' => 'Carlos Rodríguez',  'avatar_color' => '#0077b6', 'inicial' => 'CR'],
        3 => ['nombre' => 'Valentina López',   'avatar_color' => '#7b2d8b', 'inicial' => 'VL'],
        4 => ['nombre' => 'Diego Fernández',   'avatar_color' => '#2d9e5f', 'inicial' => 'DF'],
        5 => ['nombre' => 'Isabella Torres',   'avatar_color' => '#e5a009', 'inicial' => 'IT'],
    ];

    // Datos enriquecidos locales para el historial (espejo del seeder)
    private array $contenidoLocal = [
        'c001' => ['titulo' => 'Dune: Parte Dos',         'tipo' => 'pelicula', 'duracion' => 9840,  'poster_color' => '#b5860d', 'poster_inicial' => 'D2', 'generos' => ['Sci-Fi','Aventura']],
        'c002' => ['titulo' => 'Oppenheimer',             'tipo' => 'pelicula', 'duracion' => 11100, 'poster_color' => '#8b2500', 'poster_inicial' => 'OP', 'generos' => ['Drama','Historia']],
        'c003' => ['titulo' => 'Breaking Bad',            'tipo' => 'serie',    'duracion' => 2700,  'poster_color' => '#1a3300', 'poster_inicial' => 'BB', 'generos' => ['Drama','Crimen']],
        'c004' => ['titulo' => 'Stranger Things',         'tipo' => 'serie',    'duracion' => 3000,  'poster_color' => '#0a0a2e', 'poster_inicial' => 'ST', 'generos' => ['Terror','Sci-Fi']],
        'c005' => ['titulo' => 'Inception',               'tipo' => 'pelicula', 'duracion' => 8880,  'poster_color' => '#2d3561', 'poster_inicial' => 'IC', 'generos' => ['Sci-Fi','Acción']],
        'c006' => ['titulo' => 'The Last of Us',          'tipo' => 'serie',    'duracion' => 3600,  'poster_color' => '#2d1b00', 'poster_inicial' => 'LU', 'generos' => ['Drama','Survival']],
        'c007' => ['titulo' => 'Spider-Man: No Way Home', 'tipo' => 'pelicula', 'duracion' => 9000,  'poster_color' => '#1a0033', 'poster_inicial' => 'SM', 'generos' => ['Acción','Superhéroes']],
        'c008' => ['titulo' => 'The Dark Knight',         'tipo' => 'pelicula', 'duracion' => 9120,  'poster_color' => '#101010', 'poster_inicial' => 'DK', 'generos' => ['Acción','Crimen']],
        'c009' => ['titulo' => 'Severance',               'tipo' => 'serie',    'duracion' => 3300,  'poster_color' => '#003366', 'poster_inicial' => 'SV', 'generos' => ['Misterio','Sci-Fi']],
        'c010' => ['titulo' => 'Squid Game',              'tipo' => 'serie',    'duracion' => 2940,  'poster_color' => '#1a4a2e', 'poster_inicial' => 'SQ', 'generos' => ['Thriller','Drama']],
        'c011' => ['titulo' => 'Parasite',                'tipo' => 'pelicula', 'duracion' => 8220,  'poster_color' => '#1a1a1a', 'poster_inicial' => 'PA', 'generos' => ['Thriller','Drama']],
        'c012' => ['titulo' => 'House of the Dragon',     'tipo' => 'serie',    'duracion' => 3900,  'poster_color' => '#4a0000', 'poster_inicial' => 'HD', 'generos' => ['Fantasía','Drama']],
        'c013' => ['titulo' => 'Interstelar',             'tipo' => 'pelicula', 'duracion' => 10140, 'poster_color' => '#0d1b4b', 'poster_inicial' => 'IN', 'generos' => ['Sci-Fi','Drama']],
        'c014' => ['titulo' => 'Dark',                    'tipo' => 'serie',    'duracion' => 3000,  'poster_color' => '#001a1a', 'poster_inicial' => 'DK', 'generos' => ['Sci-Fi','Misterio']],
        'c015' => ['titulo' => 'Shogun',                  'tipo' => 'serie',    'duracion' => 3600,  'poster_color' => '#1a0d00', 'poster_inicial' => 'SH', 'generos' => ['Drama','Historia']],
    ];

    public function __construct(CatalogService $catalog)
    {
        $this->catalog = $catalog;
    }

    // ── CREAR ─────────────────────────────────────────────────────────────────
    public function store(StoreHistorialRequest $request)
    {
        $contenido = $this->catalog->getContenido($request->id_contenido);
        if (!$contenido) {
            return response()->json(['error' => 'El contenido no existe en el catálogo'], 404);
        }
        $historial = Historial::create([
            'id_usuario'              => $request->id_usuario,
            'id_contenido'            => $contenido['id'],
            'tipo_contenido'          => $contenido['tipo_contenido'],
            'progreso_segundos'       => $request->progreso_segundos,
            'duracion_total_segundos' => $contenido['duracion_total_segundos'],
            'visto_completado'        => $request->progreso_segundos >= $contenido['duracion_total_segundos'],
            'fecha_ultima_vista'      => now(),
        ]);
        return response()->json($historial, 201);
    }

    // ── HISTORIAL POR USUARIO (JSON enriquecido) ───────────────────────────────
    public function getByUser($id_usuario)
    {
        $registros = Historial::where('id_usuario', (int)$id_usuario)
            ->orderBy('fecha_ultima_vista', 'desc')
            ->get();

        $enriquecido = $registros->map(function ($h) {
            $meta = $this->contenidoLocal[$h->id_contenido] ?? null;
            return array_merge($h->toArray(), [
                'titulo'       => $meta['titulo']        ?? 'Desconocido',
                'poster_color' => $meta['poster_color']  ?? '#1a1a2e',
                'poster_inicial'=> $meta['poster_inicial']?? '??',
                'generos'      => $meta['generos']       ?? [],
                'porcentaje'   => $h->duracion_total_segundos > 0
                    ? round(($h->progreso_segundos / $h->duracion_total_segundos) * 100)
                    : 0,
            ]);
        });

        return response()->json($enriquecido);
    }

    // ── HISTORIAL POR USUARIO (UI HTML) ──────────────────────────────────────
    public function getByUserUI($id_usuario)
    {
        $id_usuario = (int) $id_usuario;
        $registros  = Historial::where('id_usuario', $id_usuario)
            ->orderBy('fecha_ultima_vista', 'desc')
            ->get();

        $usuario = $this->usuarios[$id_usuario] ?? [
            'nombre'       => "Usuario #{$id_usuario}",
            'avatar_color' => '#555',
            'inicial'      => 'U',
        ];

        $totalVistos    = $registros->where('visto_completado', true)->count();
        $enProceso      = $registros->where('visto_completado', false)->count();

        // Generar tarjetas
        $cards = '';
        foreach ($registros as $h) {
            $meta    = $this->contenidoLocal[$h->id_contenido] ?? null;
            $titulo  = $meta['titulo']         ?? $h->id_contenido;
            $color   = $meta['poster_color']   ?? '#1a1a2e';
            $inicial = $meta['poster_inicial'] ?? '??';
            $generos = implode(', ', $meta['generos'] ?? []);
            $tipo    = $h->tipo_contenido === 'serie' ? 'Serie' : 'Película';
            $pct     = $h->duracion_total_segundos > 0
                ? round(($h->progreso_segundos / $h->duracion_total_segundos) * 100)
                : 0;
            $fecha   = \Carbon\Carbon::parse($h->fecha_ultima_vista)->diffForHumans();
            $badge   = $h->visto_completado
                ? '<span class="badge-completo">✓ Visto</span>'
                : '<span class="badge-proceso">▶ En progreso</span>';
            $progMin = intdiv($h->progreso_segundos, 60);
            $durMin  = intdiv($h->duracion_total_segundos, 60);

            $cards .= "
            <div class=\"hcard\">
              <div class=\"hposter\" style=\"background:{$color}\">
                <span class=\"hposter-letra\">{$inicial}</span>
              </div>
              <div class=\"hinfo\">
                <div class=\"htitle-row\">
                  <h3 class=\"htitulo\">{$titulo}</h3>
                  {$badge}
                </div>
                <p class=\"hmeta\">{$tipo} • {$generos}</p>
                <div class=\"progress-wrap\">
                  <div class=\"progress-bar\" style=\"width:{$pct}%\"></div>
                </div>
                <p class=\"hprogreso\">{$progMin} min / {$durMin} min — {$pct}%</p>
                <p class=\"hfecha\">Visto {$fecha}</p>
              </div>
            </div>";
        }

        $listaUsuarios = '';
        foreach ($this->usuarios as $uid => $u) {
            $active = $uid === $id_usuario ? 'active' : '';
            $listaUsuarios .= "<a href=\"/api/historial/usuario/{$uid}/ui\" class=\"user-pill {$active}\">
              <span class=\"user-dot\" style=\"background:{$u['avatar_color']}\">{$u['inicial']}</span>
              {$u['nombre']}
            </a>";
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>StreamFlix — Historial de {$usuario['nombre']}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#0a0a0f;color:#e5e5e5;min-height:100vh}
header{background:linear-gradient(135deg,#0d0d1a,#1a0a0a);padding:1.2rem 2rem;display:flex;align-items:center;gap:1.5rem;border-bottom:1px solid rgba(255,255,255,0.06)}
.logo{font-size:1.6rem;font-weight:900;background:linear-gradient(135deg,#e50914,#ff6b6b);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.header-right{margin-left:auto;font-size:.85rem;color:#666}
.header-right a{color:#e50914;text-decoration:none}
.users-bar{display:flex;gap:.75rem;padding:1.2rem 2rem;overflow-x:auto;background:#0f0f1a;border-bottom:1px solid rgba(255,255,255,0.05)}
.user-pill{display:flex;align-items:center;gap:.5rem;padding:.5rem 1rem;border-radius:20px;text-decoration:none;color:#aaa;font-size:.85rem;border:1px solid rgba(255,255,255,0.08);transition:all .2s;white-space:nowrap}
.user-pill:hover{border-color:#e50914;color:#fff}
.user-pill.active{background:rgba(229,9,20,0.15);border-color:#e50914;color:#fff}
.user-dot{width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#fff;flex-shrink:0}
.profile{padding:2rem;display:flex;align-items:center;gap:1.5rem;background:linear-gradient(135deg,rgba(229,9,20,0.05),transparent)}
.avatar{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:900;color:#fff;flex-shrink:0;border:3px solid rgba(255,255,255,0.1)}
.profile-info h1{font-size:1.6rem;font-weight:700}
.profile-stats{display:flex;gap:1.5rem;margin-top:.5rem}
.pstat{text-align:center}
.pstat .num{font-size:1.4rem;font-weight:700;color:#e50914}
.pstat .label{font-size:.75rem;color:#666}
.section-title{padding:.5rem 2rem 1rem;font-size:1rem;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.1em}
.hlist{display:flex;flex-direction:column;gap:.75rem;padding:0 2rem 3rem}
.hcard{display:flex;gap:1.2rem;background:#141420;border-radius:12px;overflow:hidden;transition:transform .2s,box-shadow .2s}
.hcard:hover{transform:translateX(4px);box-shadow:0 4px 20px rgba(229,9,20,0.1)}
.hposter{width:80px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.hposter-letra{font-size:1.8rem;font-weight:900;color:rgba(255,255,255,0.3)}
.hinfo{flex:1;padding:1rem 1rem 1rem 0}
.htitle-row{display:flex;align-items:center;gap:.75rem;margin-bottom:.3rem;flex-wrap:wrap}
.htitulo{font-size:1rem;font-weight:700}
.badge-completo{font-size:.7rem;padding:.2rem .6rem;border-radius:4px;background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3)}
.badge-proceso{font-size:.7rem;padding:.2rem .6rem;border-radius:4px;background:rgba(234,179,8,0.15);color:#facc15;border:1px solid rgba(234,179,8,0.3)}
.hmeta{font-size:.78rem;color:#888;margin-bottom:.6rem}
.progress-wrap{background:#1e1e30;border-radius:4px;height:4px;overflow:hidden;margin-bottom:.4rem}
.progress-bar{height:100%;background:linear-gradient(90deg,#e50914,#ff6b6b);border-radius:4px;transition:width .5s}
.hprogreso{font-size:.75rem;color:#666}
.hfecha{font-size:.72rem;color:#444;margin-top:.2rem}
.empty{text-align:center;padding:3rem;color:#444}
footer{text-align:center;padding:2rem;color:#333;font-size:.8rem;border-top:1px solid #1a1a2e}
@media(max-width:768px){header{flex-direction:column;gap:.5rem;padding:1rem}.logo{font-size:1.3rem}.header-right{margin-left:0;font-size:.75rem}.users-bar{padding:.75rem 1rem}.profile{flex-direction:column;text-align:center}.hlist{padding:0 1rem 2rem}.hcard{flex-direction:column}.hposter{width:100%;height:120px;margin-bottom:.5rem}.hinfo{padding:1rem}.section-title{padding:.5rem 1rem 1rem}}
</style>
</head>
<body>
<header>
  <a class="logo" href="http://localhost:3001/contenido">StreamFlix</a>
  <div class="header-right">
    <a href="/api/historial/usuario/{$id_usuario}">Ver JSON</a> &nbsp;|&nbsp;
    <a href="http://localhost:3001/contenido">Catálogo</a>
  </div>
</header>
<div class="users-bar">{$listaUsuarios}</div>
<div class="profile">
  <div class="avatar" style="background:{$usuario['avatar_color']}">{$usuario['inicial']}</div>
  <div class="profile-info">
    <h1>{$usuario['nombre']}</h1>
    <div class="profile-stats">
      <div class="pstat"><div class="num">{$registros->count()}</div><div class="label">Títulos</div></div>
      <div class="pstat"><div class="num">{$totalVistos}</div><div class="label">Completados</div></div>
      <div class="pstat"><div class="num">{$enProceso}</div><div class="label">En progreso</div></div>
    </div>
  </div>
</div>
<div class="section-title">Historial de reproducción</div>
<div class="hlist">
HTML;

        if ($registros->isEmpty()) {
            $html .= '<div class="empty">Este usuario no tiene historial aún.</div>';
        } else {
            $html .= $cards;
        }

        $html .= <<<HTML
</div>
<footer>StreamFlix · Interaction Service — puerto 8000</footer>
</body>
</html>
HTML;

        return response($html)->header('Content-Type', 'text/html; charset=utf-8');
    }

    // ── VER UN HISTORIAL ──────────────────────────────────────────────────────
    public function show($id)
    {
        $historial = Historial::find($id);
        if (!$historial) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($historial);
    }

    // ── CON DATOS DEL CATÁLOGO ────────────────────────────────────────────────
    public function showWithContent($id)
    {
        $historial = Historial::find($id);
        if (!$historial) return response()->json(['error' => 'Historial no encontrado'], 404);
        $contenido = $this->catalog->getContenido($historial->id_contenido);
        return response()->json([
            'historial' => $historial,
            'contenido' => $contenido ?? ['error' => 'Contenido no disponible en catálogo'],
        ]);
    }

    // ── ACTUALIZAR ────────────────────────────────────────────────────────────
    public function update($id, StoreHistorialRequest $request)
    {
        $historial = Historial::find($id);
        if (!$historial) return response()->json(['error' => 'No encontrado'], 404);
        $historial->update([
            'progreso_segundos' => $request->progreso_segundos ?? $historial->progreso_segundos,
            'visto_completado'  => $request->visto_completado  ?? $historial->visto_completado,
            'fecha_ultima_vista'=> now(),
        ]);
        return response()->json($historial);
    }

    // ── ELIMINAR ──────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $historial = Historial::find($id);
        if (!$historial) return response()->json(['error' => 'No encontrado'], 404);
        $historial->delete();
        return response()->json(['mensaje' => 'Eliminado correctamente']);
    }
}
