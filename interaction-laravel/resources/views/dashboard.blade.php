<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>StreamFlix — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
:root {
  /* Tema claro */
  --bg-primary: #FAFBFD;
  --bg-secondary: #f0f1f5;
  --text-primary: #27495F;
  --text-secondary: #7D7ECF;
  --accent-1: #E182CB;
  --accent-2: #8AD5DF;
  --border-color: #d0d2d8;
  --card-bg: #ffffff;
  --overlay-bg: rgba(0, 0, 0, 0.5);
}

body.dark-mode {
  /* Tema oscuro */
  --bg-primary: #27495F;
  --bg-secondary: #1f3a4a;
  --text-primary: #FAFBFD;
  --text-secondary: #8AD5DF;
  --accent-1: #E182CB;
  --accent-2: #7D7ECF;
  --border-color: #3a5a6f;
  --card-bg: #1f3a4a;
  --overlay-bg: rgba(0, 0, 0, 0.8);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  min-height: 100vh;
  transition: background 0.3s, color 0.3s;
}

header {
  background: linear-gradient(135deg, var(--bg-primary) 0%, transparent 100%);
  padding: 2rem;
  text-align: center;
  border-bottom: 2px solid var(--border-color);
  backdrop-filter: blur(10px);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content {
  flex: 1;
}

.logo {
  font-size: 2.5rem;
  font-weight: 900;
  background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: var(--text-secondary);
  font-size: 1rem;
}

.theme-toggle {
  background: var(--accent-1);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 1.2rem;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s;
  white-space: nowrap;
}

.theme-toggle:hover {
  background: var(--accent-2);
  transform: scale(1.05);
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.stat-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.3s;
}

.stat-card:hover {
  background: var(--bg-secondary);
  border-color: var(--accent-1);
  transform: translateY(-4px);
}

.stat-label {
  color: var(--text-secondary);
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: var(--accent-1);
}

.users-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}

.user-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s;
  cursor: pointer;
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
}

.user-card:hover {
  transform: translateY(-8px);
  border-color: var(--accent-1);
  box-shadow: 0 20px 40px rgba(225, 130, 203, 0.15);
}

.user-header {
  padding: 2rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.user-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 900;
  color: #fff;
  flex-shrink: 0;
  border: 3px solid var(--border-color);
}

.user-info h2 {
  font-size: 1.2rem;
  margin-bottom: 0.3rem;
}

.user-info p {
  font-size: 0.85rem;
  color: var(--text-secondary);
}

.user-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  padding: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.ustat {
  text-align: center;
}

.ustat .num {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--accent-1);
  display: block;
}

.ustat .label {
  font-size: 0.7rem;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.user-footer {
  padding: 1.5rem;
  text-align: center;
  flex-grow: 1;
  display: flex;
  align-items: flex-end;
  justify-content: center;
}

.btn-view {
  background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-block;
}

.btn-view:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(225, 130, 203, 0.4);
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 2rem;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.section-title:before {
  content: '';
  width: 4px;
  height: 24px;
  background: var(--accent-1);
  border-radius: 2px;
}

footer {
  text-align: center;
  padding: 2rem;
  color: var(--text-secondary);
  font-size: 0.8rem;
  border-top: 1px solid var(--border-color);
  margin-top: 3rem;
}

.badge-active {
  display: inline-block;
  background: rgba(225, 130, 203, 0.15);
  color: var(--accent-1);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 0.25rem 0.75rem;
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.stat-card a {
  text-decoration: none;
  color: inherit;
}

.stat-card p {
  color: var(--accent-1);
  font-weight: 600;
}

footer a {
  color: var(--accent-1);
  text-decoration: none;
}

footer a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .logo {
    font-size: 2rem;
  }
  .users-grid {
    grid-template-columns: 1fr;
  }
  .stat-card {
    padding: 1rem;
  }
  header {
    flex-direction: column;
    gap: 1rem;
  }
  .header-content {
    flex: 1;
  }
}
</style>
</head>
<body>
<header>
  <div class="header-content">
    <div class="logo">🎬 StreamFlix</div>
    <p class="subtitle">Tu plataforma de streaming personal</p>
  </div>
  <button class="theme-toggle" onclick="toggleTheme()">🌙 Oscuro</button>
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
      <p>Ver todas las películas y series</p>
    </a>
    <a href="http://localhost:3001/seed" class="stat-card" style="text-decoration:none">
      <div class="stat-label">🌱 Recargar Catálogo</div>
      <p>Sincronizar con MongoDB</p>
    </a>
  </div>
</div>

<footer>StreamFlix • Streaming Backend • © 2026 | <a href="http://localhost:3001/contenido">Ver Catálogo</a></footer>

<script>
  function toggleTheme() {
    const body = document.body;
    const btn = document.querySelector('.theme-toggle');

    body.classList.toggle('dark-mode');

    // Guardar preferencia
    localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');

    // Cambiar texto del botón
    btn.textContent = body.classList.contains('dark-mode') ? '☀️ Claro' : '🌙 Oscuro';
  }

  // Cargar tema guardado al iniciar
  document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.body.classList.add('dark-mode');
      document.querySelector('.theme-toggle').textContent = '☀️ Claro';
    }
  });
</script>
</body>
</html>
