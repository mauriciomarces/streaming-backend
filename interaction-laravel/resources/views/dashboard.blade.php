<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>StreamFlix — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0a0a0f 0%,#1a0a0a 100%);color:#e5e5e5;min-height:100vh}
header{background:linear-gradient(135deg,rgba(0,0,0,0.8),rgba(255,0,0,0.05));padding:2rem;text-align:center;border-bottom:2px solid rgba(229,9,20,0.3);backdrop-filter:blur(10px)}
.logo{font-size:2.5rem;font-weight:900;background:linear-gradient(135deg,#e50914,#ff6b6b);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:.5rem}
.subtitle{color:#888;font-size:1rem}
.container{max-width:1400px;margin:0 auto;padding:2rem}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-bottom:3rem}
.stat-card{background:rgba(255,255,255,0.05);border:1px solid rgba(229,9,20,0.2);border-radius:12px;padding:1.5rem;transition:all .3s}
.stat-card:hover{background:rgba(229,9,20,0.1);border-color:#e50914;transform:translateY(-4px)}
.stat-label{color:#888;font-size:.85rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem}
.stat-value{font-size:2rem;font-weight:700;color:#ff6b6b}
.users-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:2rem}
.user-card{background:linear-gradient(135deg,rgba(255,255,255,0.05),rgba(229,9,20,0.1));border:1px solid rgba(229,9,20,0.3);border-radius:16px;overflow:hidden;transition:all .3s;cursor:pointer;text-decoration:none;color:inherit;display:flex;flex-direction:column}
.user-card:hover{transform:translateY(-8px);border-color:#e50914;box-shadow:0 20px 40px rgba(229,9,20,0.2)}
.user-header{padding:2rem;display:flex;align-items:center;gap:1.5rem;border-bottom:1px solid rgba(255,255,255,0.05)}
.user-avatar{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:900;color:#fff;flex-shrink:0;border:3px solid rgba(255,255,255,0.1)}
.user-info h2{font-size:1.2rem;margin-bottom:.3rem}
.user-info p{font-size:.85rem;color:#888}
.user-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;padding:1.5rem;border-bottom:1px solid rgba(255,255,255,0.05)}
.ustat{text-align:center}
.ustat .num{font-size:1.5rem;font-weight:700;color:#ff6b6b;display:block}
.ustat .label{font-size:.7rem;color:#666;text-transform:uppercase;letter-spacing:.05em}
.user-footer{padding:1.5rem;text-align:center;flex-grow:1;display:flex;align-items:flex-end;justify-content:center}
.btn-view{background:linear-gradient(135deg,#e50914,#ff6b6b);color:#fff;border:none;border-radius:8px;padding:.75rem 1.5rem;font-weight:600;cursor:pointer;transition:all .2s;display:inline-block}
.btn-view:hover{transform:scale(1.05);box-shadow:0 8px 20px rgba(229,9,20,0.4)}
.section-title{font-size:1.5rem;font-weight:700;margin-bottom:2rem;color:#fff;display:flex;align-items:center;gap:1rem}
.section-title:before{content:'';width:4px;height:24px;background:#e50914;border-radius:2px}
footer{text-align:center;padding:2rem;color:#333;font-size:.8rem;border-top:1px solid #1a1a2e;margin-top:3rem}
.badge-active{display:inline-block;background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);border-radius:20px;padding:.25rem .75rem;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em}
@media(max-width:768px){.logo{font-size:2rem}.users-grid{grid-template-columns:1fr}.stat-card{padding:1rem}.container{padding:1rem}}
</style>
</head>
<body>
<header>
  <div class="logo">🎬 StreamFlix</div>
  <p class="subtitle">Tu plataforma de streaming personal</p>
</header>
<div class="container">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Usuarios Activos</div>
      <div class="stat-value">5</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Títulos en Catálogo</div>
      <div class="stat-value">55+</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Horas Vistas</div>
      <div class="stat-value">200+</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Historiales Registrados</div>
      <div class="stat-value">150+</div>
    </div>
  </div>

  <div class="section-title">Perfiles de Usuarios</div>
  <div class="users-grid">
    <a href="/api/historial/usuario/1/ui" class="user-card">
      <div class="user-header">
        <div class="user-avatar" style="background:#e50914">LM</div>
        <div class="user-info">
          <h2>Lucía Martínez</h2>
          <p><span class="badge-active">Activo</span></p>
        </div>
      </div>
      <div class="user-stats">
        <div class="ustat"><span class="num">12</span><span class="label">Títulos</span></div>
        <div class="ustat"><span class="num">8</span><span class="label">Completados</span></div>
        <div class="ustat"><span class="num">4</span><span class="label">En Progreso</span></div>
      </div>
      <div class="user-footer"><button class="btn-view">Ver Historial</button></div>
    </a>

    <a href="/api/historial/usuario/2/ui" class="user-card">
      <div class="user-header">
        <div class="user-avatar" style="background:#0077b6">CR</div>
        <div class="user-info">
          <h2>Carlos Rodríguez</h2>
          <p><span class="badge-active">Activo</span></p>
        </div>
      </div>
      <div class="user-stats">
        <div class="ustat"><span class="num">10</span><span class="label">Títulos</span></div>
        <div class="ustat"><span class="num">6</span><span class="label">Completados</span></div>
        <div class="ustat"><span class="num">4</span><span class="label">En Progreso</span></div>
      </div>
      <div class="user-footer"><button class="btn-view">Ver Historial</button></div>
    </a>

    <a href="/api/historial/usuario/3/ui" class="user-card">
      <div class="user-header">
        <div class="user-avatar" style="background:#7b2d8b">VL</div>
        <div class="user-info">
          <h2>Valentina López</h2>
          <p><span class="badge-active">Activo</span></p>
        </div>
      </div>
      <div class="user-stats">
        <div class="ustat"><span class="num">9</span><span class="label">Títulos</span></div>
        <div class="ustat"><span class="num">7</span><span class="label">Completados</span></div>
        <div class="ustat"><span class="num">2</span><span class="label">En Progreso</span></div>
      </div>
      <div class="user-footer"><button class="btn-view">Ver Historial</button></div>
    </a>

    <a href="/api/historial/usuario/4/ui" class="user-card">
      <div class="user-header">
        <div class="user-avatar" style="background:#2d9e5f">DF</div>
        <div class="user-info">
          <h2>Diego Fernández</h2>
          <p><span class="badge-active">Activo</span></p>
        </div>
      </div>
      <div class="user-stats">
        <div class="ustat"><span class="num">11</span><span class="label">Títulos</span></div>
        <div class="ustat"><span class="num">5</span><span class="label">Completados</span></div>
        <div class="ustat"><span class="num">6</span><span class="label">En Progreso</span></div>
      </div>
      <div class="user-footer"><button class="btn-view">Ver Historial</button></div>
    </a>

    <a href="/api/historial/usuario/5/ui" class="user-card">
      <div class="user-header">
        <div class="user-avatar" style="background:#e5a009">IT</div>
        <div class="user-info">
          <h2>Isabella Torres</h2>
          <p><span class="badge-active">Activo</span></p>
        </div>
      </div>
      <div class="user-stats">
        <div class="ustat"><span class="num">8</span><span class="label">Títulos</span></div>
        <div class="ustat"><span class="num">6</span><span class="label">Completados</span></div>
        <div class="ustat"><span class="num">2</span><span class="label">En Progreso</span></div>
      </div>
      <div class="user-footer"><button class="btn-view">Ver Historial</button></div>
    </a>
  </div>

  <div class="section-title" style="margin-top:3rem">Enlaces Útiles</div>
  <div class="stats-grid">
    <a href="http://localhost:3001/contenido" class="stat-card" style="text-decoration:none">
      <div class="stat-label">📚 Catálogo Completo</div>
      <p style="color:#ff6b6b;font-weight:600">Ver todas las películas y series</p>
    </a>
    <a href="http://localhost:3001/seed" class="stat-card" style="text-decoration:none;background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.3)">
      <div class="stat-label">🌱 Recargar Catálogo</div>
      <p style="color:#4ade80;font-weight:600">Sincronizar con MongoDB</p>
    </a>
  </div>
</div>
<footer>StreamFlix • Streaming Backend • © 2026</footer>
</body>
</html>
