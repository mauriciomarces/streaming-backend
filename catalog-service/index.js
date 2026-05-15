import express from 'express'
import mongoose from 'mongoose'
import cors from 'cors'
import Contenido from './models/Content.js'
import axios from 'axios';

const app = express()
app.use(cors())
app.use(express.json())

mongoose.connect(process.env.MONGO_URI || 'mongodb://mongodb:27017/ContenidoDB')
  .then(() => console.log('✅ MongoDB conectado'))
  .catch(err => console.error('❌ MongoDB error:', err))

// Usamos la llave que pusimos en el docker-compose
const API_KEY = process.env.TMDB_API_KEY; 
const BASE_URL = 'https://api.themoviedb.org/3';

// ─── FUNCIÓN PARA OBTENER DATOS DE TMDB ──────────────────────────────────────
async function getCatalogoDesdeAPI() {
  const paginasAObtener = [1, 2, 3]; // Esto nos dará 60 pelis y 60 series = 120 total
  
  try {
    // Creamos un arreglo de promesas para pedir varias páginas a la vez
    const promesasPeliculas = paginasAObtener.map(p => 
      axios.get(`${BASE_URL}/movie/popular?api_key=${API_KEY}&language=es-ES&page=${p}`)
    );
    
    const promesasSeries = paginasAObtener.map(p => 
      axios.get(`${BASE_URL}/tv/popular?api_key=${API_KEY}&language=es-ES&page=${p}`)
    );

    // Ejecutamos todas las peticiones en paralelo
    const respuestas = await Promise.all([...promesasPeliculas, ...promesasSeries]);

    // Procesamos y aplanamos los resultados
    const todosLosContenidos = respuestas.flatMap((res, index) => {
      const esPelicula = index < paginasAObtener.length;
      
      return res.data.results.map(item => ({
        titulo: esPelicula ? item.title : item.name,
        tipo: esPelicula ? 'pelicula' : 'serie',
        anio: (esPelicula ? item.release_date : item.first_air_date)?.split('-')[0] || 'N/A',
        poster: item.poster_path ? `https://image.tmdb.org/t/p/w500${item.poster_path}` : null,
        calificacion: item.vote_average,
        descripcion: item.overview
      }));
    });

    // Mezclamos el resultado final
    return todosLosContenidos.sort(() => Math.random() - 0.5);

  } catch (error) {
    console.error("Error al pedir más páginas:", error.message);
    throw error;
  }
}

// ─── HTML UI ──────────────────────────────────────────────────────────────────
function renderUI(items) {
  const cards = items.map(c => {
    const badge = c.tipo === 'serie'
      ? `<span class="badge badge-serie">Serie</span>`
      : `<span class="badge badge-peli">Película</span>`
    
    const stars = '★'.repeat(Math.round((c.calificacion || 0) / 2))
    
    // Cambiamos el div del poster para que use la imagen de la API
    const posterHTML = c.poster 
      ? `<img src="${c.poster}" style="width:100%; height:100%; object-fit:cover;">`
      : `<span class="poster-letra">${c.titulo.substring(0,2).toUpperCase()}</span>`;

    return `
    <div class="card" data-tipo="${c.tipo}" data-titulo="${c.titulo.toLowerCase()}">
      <div class="poster" style="background:#1a1a1a">
        ${posterHTML}
        <div class="poster-overlay">
          <button class="btn-play">▶ Ver ahora</button>
        </div>
      </div>
      <div class="info">
        <div class="title-row">${badge}</div>
        <h3 class="titulo">${c.titulo}</h3>
        <p class="meta">${c.anio}</p>
        <p class="rating"><span class="stars">${stars}</span> ${(c.calificacion||0).toFixed(1)}</p>
        <p class="desc">${c.descripcion||''}</p>
      </div>
    </div>`
  }).join('')

  return `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>StreamFlix — Catálogo</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  /* Tema claro */
  --bg-primary: #FAFBFD;
  --bg-secondary: #f0f1f5;
  --bg-tertiary: #e8eaef;
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
  --bg-tertiary: #182d38;
  --text-primary: #FAFBFD;
  --text-secondary: #8AD5DF;
  --accent-1: #E182CB;
  --accent-2: #7D7ECF;
  --border-color: #3a5a6f;
  --card-bg: #1f3a4a;
  --overlay-bg: rgba(0, 0, 0, 0.8);
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  min-height: 100vh;
  transition: background 0.3s, color 0.3s;
}

header {
  background: linear-gradient(180deg, var(--bg-primary) 0%, transparent 100%);
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  gap: 2rem;
  position: sticky;
  top: 0;
  z-index: 100;
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--border-color);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 2rem;
  flex: 1;
}

.logo {
  font-size: 1.8rem;
  font-weight: 900;
  background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  white-space: nowrap;
}

.search-box {
  flex: 1;
  max-width: 400px;
  position: relative;
}

.search-box input {
  width: 100%;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 0.6rem 1rem 0.6rem 2.5rem;
  color: var(--text-primary);
  font-size: 0.9rem;
  outline: none;
  transition: border 0.2s;
}

.search-box input:focus {
  border-color: var(--accent-1);
}

.search-box::before {
  content: '🔍';
  position: absolute;
  left: 0.7rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.85rem;
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

.filters {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  padding: 1rem 2rem;
  background: var(--bg-secondary);
  border-bottom: 1px solid var(--border-color);
}

.filter-btn {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  color: var(--text-secondary);
  padding: 0.4rem 1rem;
  cursor: pointer;
  font-size: 0.85rem;
  transition: all 0.2s;
}

.filter-btn:hover {
  background: var(--accent-1);
  border-color: var(--accent-1);
  color: white;
}

.filter-btn.active {
  background: var(--accent-1);
  border-color: var(--accent-1);
  color: white;
}

.stats {
  padding: 0.5rem 2rem;
  font-size: 0.85rem;
  color: var(--text-secondary);
  background: var(--bg-secondary);
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.2rem;
  padding: 1rem 2rem 3rem;
}

.card {
  background: var(--card-bg);
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.25s, box-shadow 0.25s;
  cursor: pointer;
  border: 1px solid var(--border-color);
}

.card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.poster {
  height: 220px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.poster-letra {
  font-size: 3.5rem;
  font-weight: 900;
  color: rgba(255, 255, 255, 0.3);
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

.poster-overlay {
  position: absolute;
  inset: 0;
  background: var(--overlay-bg);
  opacity: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.2s;
}

.card:hover .poster-overlay {
  opacity: 1;
}

.btn-play {
  background: var(--accent-1);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 0.6rem 1.2rem;
  font-weight: 700;
  cursor: pointer;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.btn-play:hover {
  background: var(--accent-2);
}

.info {
  padding: 1rem;
}

.title-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.4rem;
}

.badge {
  font-size: 0.65rem;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-serie {
  background: rgba(125, 126, 207, 0.2);
  color: var(--text-secondary);
  border: 1px solid var(--accent-2);
}

.badge-peli {
  background: rgba(225, 130, 203, 0.2);
  color: var(--accent-1);
  border: 1px solid var(--accent-1);
}

.clasificacion {
  font-size: 0.7rem;
  color: var(--text-secondary);
  border: 1px solid var(--border-color);
  padding: 0.1rem 0.4rem;
  border-radius: 3px;
}

.titulo {
  font-size: 0.95rem;
  font-weight: 700;
  margin-bottom: 0.2rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.meta {
  font-size: 0.75rem;
  color: var(--text-secondary);
  margin-bottom: 0.3rem;
}

.generos {
  font-size: 0.72rem;
  color: var(--text-secondary);
  margin-bottom: 0.3rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.rating {
  font-size: 0.78rem;
  color: var(--accent-1);
}
.stars {
  letter-spacing: 1px;
}

.desc {
  font-size: 0.72rem;
  color: var(--text-secondary);
  margin-top: 0.5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.hidden {
  display: none;
}

footer {
  text-align: center;
  padding: 2rem;
  color: var(--text-secondary);
  font-size: 0.8rem;
  border-top: 1px solid var(--border-color);
}

footer a {
  color: var(--accent-1);
  text-decoration: none;
}

footer a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>
<header>
  <div class="header-left">
    <div class="logo">StreamFlix</div>
    <div class="search-box">
      <input type="text" id="search" placeholder="Buscar películas y series..." oninput="filtrar()">
    </div>
  </div>
  <button class="theme-toggle" onclick="toggleTheme()">🌙 Oscuro</button>
</header>
<div class="filters">
  <button class="filter-btn active" onclick="setTipo('todos',this)">Todos</button>
  <button class="filter-btn" onclick="setTipo('pelicula',this)">Películas</button>
  <button class="filter-btn" onclick="setTipo('serie',this)">Series</button>
  <button class="filter-btn" onclick="setGenero('Acción',this)">Acción</button>
  <button class="filter-btn" onclick="setGenero('Drama',this)">Drama</button>
  <button class="filter-btn" onclick="setGenero('Ciencia Ficción',this)">Sci-Fi</button>
  <button class="filter-btn" onclick="setGenero('Terror',this)">Terror</button>
  <button class="filter-btn" onclick="setGenero('Crimen',this)">Crimen</button>
  <button class="filter-btn" onclick="setGenero('Comedia',this)">Comedia</button>
  <button class="filter-btn" onclick="setGenero('Misterio',this)">Misterio</button>
</div>
<div class="stats" id="stats"></div>
<div class="grid" id="grid">${cards}</div>
<footer>StreamFlix Catalog Service — puerto 3001 | <a href="/contenido?format=json" style="color:#e50914">Ver JSON</a></footer>
<script>
// ─── VARIABLES GLOBALES ────────────────────────────────────────────────
let tipoActivo='todos', generoActivo='', textoBusq='';

// ─── FUNCIÓN FILTRAR ────────────────────────────────────────────────────
function filtrar(){
  textoBusq=document.getElementById('search').value.toLowerCase();
  const cards=document.querySelectorAll('.card');
  let vis=0;
  cards.forEach(c=>{
    const t=c.dataset.tipo, ti=c.dataset.titulo, g=c.dataset.genero;
    const okTipo=tipoActivo==='todos'||t===tipoActivo;
    const okGenero=!generoActivo||g.includes(generoActivo.toLowerCase());
    const okBusq=!textoBusq||ti.includes(textoBusq);
    const show=okTipo&&okGenero&&okBusq;
    c.classList.toggle('hidden',!show);
    if(show)vis++;
  });
  document.getElementById('stats').textContent=vis+' título(s) encontrado(s)';
}

// ─── FUNCIONES DE FILTRADO ────────────────────────────────────────────
function setTipo(t,btn){
  tipoActivo=t; generoActivo='';
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  filtrar();
}

function setGenero(g,btn){
  generoActivo=generoActivo===g?'':g; tipoActivo='todos';
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  if(generoActivo)btn.classList.add('active');
  else document.querySelector('.filter-btn').classList.add('active');
  filtrar();
}

// ─── TEMA CLARO/OSCURO ────────────────────────────────────────────────
function toggleTheme() {
  const body = document.body;
  const btn = document.querySelector('.theme-toggle');
  
  body.classList.toggle('dark-mode');
  
  // Guardar preferencia
  localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
  
  // Cambiar texto del botón
  btn.textContent = body.classList.contains('dark-mode') ? '☀️ Claro' : '🌙 Oscuro';
}

// ─── INICIALIZAR AL CARGAR ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Cargar tema guardado
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    document.querySelector('.theme-toggle').textContent = '☀️ Claro';
  }
  
  // Aplicar filtros iniciales
  filtrar();
});


</script>

</body>
</html>`
}

// ─── RUTAS ACTUALIZADAS ──────────────────────────────────────────────────────

// Agregamos una ruta específica para la API (más estándar para React)
app.get('/api/catalog', async (req, res) => {
  try {
    console.log("Serviendo datos JSON a React...");
    const contenidos = await getCatalogoDesdeAPI();
    res.json(contenidos);
  } catch (error) {
    console.error("Error en API:", error.message);
    res.status(500).json({ error: "Error al obtener datos de la API externa" });
  }
});

// Mantenemos tu ruta original para que sigas pudiendo ver el HTML si entras directo
app.get('/contenido', async (req, res) => {
  try {
    const contenidos = await getCatalogoDesdeAPI();
    
    // Si la petición viene de un navegador pidiendo JSON o de tu enlace en el footer
    if (req.query.format === 'json') {
      return res.json(contenidos);
    }
    
    // Renderiza la UI vieja en HTML
    res.send(renderUI(contenidos));
  } catch (error) {
    res.status(500).send("Error cargando el catálogo.");
  }
});



// ─── RUTAS ────────────────────────────────────────────────────────────────────
app.get('/contenido', async (req, res) => {
  try {
    console.log("Obteniendo datos de TMDB...");
    const contenidos = await getCatalogoDesdeAPI();
    
    if (req.query.format === 'json') {
      return res.json(contenidos);
    }
    // Usamos el nuevo renderUI que acepta imágenes
    res.send(renderUI(contenidos));
  } catch (error) {
    console.error("Error:", error.message);
    res.status(500).send("Error cargando la API. ¿Pusiste la API_KEY en el docker-compose?");
  }
});

// Rutas de administración (siguen usando Mongo)
app.post('/contenido', async (req, res) => {
  const doc = await Contenido.create(req.body)
  res.json(doc)
})

// ─── ARRANQUE ────────────────────────────────────────────────────────────────
app.listen(3001, '0.0.0.0', () => {
  console.log('Catalog service on port 3001');
});