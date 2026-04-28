import express from 'express'
import mongoose from 'mongoose'
import cors from 'cors'
import Contenido from './models/Content.js'

const app = express()
app.use(cors())
app.use(express.json())

mongoose.connect(process.env.MONGO_URI || 'mongodb://mongodb:27017/ContenidoDB')
  .then(() => console.log('✅ MongoDB conectado'))
  .catch(err => console.error('❌ MongoDB error:', err))

// ─── SEED DATA ────────────────────────────────────────────────────────────────
const SEED = [
  { titulo:'Dune: Parte Dos', tipo:'pelicula', anio:2024, generos:['Ciencia Ficción','Aventura'], clasificacion:'PG-13', duracion_segundos:9840, duracion:'2h 46m', poster_color:'#b5860d', poster_inicial:'D2', calificacion:8.5, descripcion:'Paul Atreides se une a los Fremen para vengar a su familia.' },
  { titulo:'Oppenheimer', tipo:'pelicula', anio:2023, generos:['Drama','Historia'], clasificacion:'R', duracion_segundos:11100, duracion:'3h 4m', poster_color:'#8b2500', poster_inicial:'OP', calificacion:8.9, descripcion:'La historia del padre de la bomba atómica.' },
  { titulo:'Barbie', tipo:'pelicula', anio:2023, generos:['Comedia','Fantasía'], clasificacion:'PG-13', duracion_segundos:6840, duracion:'1h 54m', poster_color:'#e91e8c', poster_inicial:'BA', calificacion:7.0, descripcion:'Barbie sale del mundo perfecto de Barbilandia.' },
  { titulo:'El Señor de los Anillos: La Comunidad', tipo:'pelicula', anio:2001, generos:['Fantasía','Aventura'], clasificacion:'PG-13', duracion_segundos:10800, duracion:'3h 0m', poster_color:'#3d6b35', poster_inicial:'SA', calificacion:9.2, descripcion:'Un hobbit emprende un peligroso viaje para destruir el Anillo Único.' },
  { titulo:'Interestelar', tipo:'pelicula', anio:2014, generos:['Ciencia Ficción','Drama'], clasificacion:'PG-13', duracion_segundos:10140, duracion:'2h 49m', poster_color:'#0d1b4b', poster_inicial:'IN', calificacion:8.7, descripcion:'Un equipo de astronautas viaja más allá de la galaxia.' },
  { titulo:'Parasite', tipo:'pelicula', anio:2019, generos:['Thriller','Drama'], clasificacion:'R', duracion_segundos:8220, duracion:'2h 17m', poster_color:'#1a1a1a', poster_inicial:'PA', calificacion:8.6, descripcion:'Una familia pobre se infiltra en una familia adinerada.' },
  { titulo:'Spider-Man: No Way Home', tipo:'pelicula', anio:2021, generos:['Acción','Superhéroes'], clasificacion:'PG-13', duracion_segundos:9000, duracion:'2h 30m', poster_color:'#1a0033', poster_inicial:'SM', calificacion:8.3, descripcion:'Peter Parker busca la ayuda del Doctor Strange.' },
  { titulo:'Everything Everywhere All at Once', tipo:'pelicula', anio:2022, generos:['Ciencia Ficción','Comedia'], clasificacion:'R', duracion_segundos:8520, duracion:'2h 19m', poster_color:'#6a0dad', poster_inicial:'EE', calificacion:8.8, descripcion:'Una mujer descubre que debe conectarse con versiones paralelas de sí misma.' },
  { titulo:'Avatar: El Camino del Agua', tipo:'pelicula', anio:2022, generos:['Ciencia Ficción','Aventura'], clasificacion:'PG-13', duracion_segundos:11520, duracion:'3h 12m', poster_color:'#005f73', poster_inicial:'AV', calificacion:7.8, descripcion:'Jake Sully y Neytiri huyen de la RDA hacia los océanos de Pandora.' },
  { titulo:'Inception', tipo:'pelicula', anio:2010, generos:['Ciencia Ficción','Acción'], clasificacion:'PG-13', duracion_segundos:8880, duracion:'2h 28m', poster_color:'#2d3561', poster_inicial:'IC', calificacion:8.8, descripcion:'Un ladrón roba secretos del subconsciente durante sueños compartidos.' },
  { titulo:'The Dark Knight', tipo:'pelicula', anio:2008, generos:['Acción','Crimen'], clasificacion:'PG-13', duracion_segundos:9120, duracion:'2h 32m', poster_color:'#101010', poster_inicial:'DK', calificacion:9.0, descripcion:'Batman enfrenta al Joker, un criminal que busca sumir Gotham en el caos.' },
  { titulo:'Gladiator II', tipo:'pelicula', anio:2024, generos:['Acción','Historia'], clasificacion:'R', duracion_segundos:9000, duracion:'2h 30m', poster_color:'#7b4f2e', poster_inicial:'GL', calificacion:7.5, descripcion:'Un nuevo gladiador busca venganza en el coloseo romano.' },
  { titulo:'Alien: Romulus', tipo:'pelicula', anio:2024, generos:['Terror','Ciencia Ficción'], clasificacion:'R', duracion_segundos:7020, duracion:'1h 59m', poster_color:'#0f2027', poster_inicial:'AR', calificacion:7.3, descripcion:'Un grupo de jóvenes colonizonadores enfrenta el peligro más mortal del universo.' },
  { titulo:'Wonka', tipo:'pelicula', anio:2023, generos:['Fantasía','Musical'], clasificacion:'PG', duracion_segundos:6720, duracion:'1h 56m', poster_color:'#7b2d8b', poster_inicial:'WK', calificacion:7.0, descripcion:'Los orígenes del excéntrico chocolatero Willy Wonka.' },
  { titulo:'Joker: Folie à Deux', tipo:'pelicula', anio:2024, generos:['Drama','Musical'], clasificacion:'R', duracion_segundos:7800, duracion:'2h 18m', poster_color:'#5c0a0a', poster_inicial:'JK', calificacion:6.2, descripcion:'Arthur Fleck y Lee Quinn desatan el caos mientras enfrentan la justicia.' },
  // Series
  { titulo:'Breaking Bad', tipo:'serie', anio:2008, generos:['Drama','Crimen'], clasificacion:'TV-MA', duracion_segundos:2700, duracion:'5 temporadas', poster_color:'#1a3300', poster_inicial:'BB', calificacion:9.5, temporadas:5, descripcion:'Un profesor de química transforma su vida al adentrarse en el mundo de las drogas.' },
  { titulo:'Stranger Things', tipo:'serie', anio:2016, generos:['Terror','Ciencia Ficción'], clasificacion:'TV-14', duracion_segundos:3000, duracion:'4 temporadas', poster_color:'#0a0a2e', poster_inicial:'ST', calificacion:8.7, temporadas:4, descripcion:'Un grupo de amigos descubre fenómenos sobrenaturales en Hawkins, Indiana.' },
  { titulo:'The Last of Us', tipo:'serie', anio:2023, generos:['Drama','Survival'], clasificacion:'TV-MA', duracion_segundos:3600, duracion:'2 temporadas', poster_color:'#2d1b00', poster_inicial:'LU', calificacion:9.0, temporadas:2, descripcion:'Joel y Ellie atraviesan un mundo devastado por un hongo mutante.' },
  { titulo:'House of the Dragon', tipo:'serie', anio:2022, generos:['Fantasía','Drama'], clasificacion:'TV-MA', duracion_segundos:3900, duracion:'2 temporadas', poster_color:'#4a0000', poster_inicial:'HD', calificacion:8.4, temporadas:2, descripcion:'La precuela de Game of Thrones narra la guerra civil de la Casa Targaryen.' },
  { titulo:'Wednesday', tipo:'serie', anio:2022, generos:['Misterio','Comedia'], clasificacion:'TV-14', duracion_segundos:2700, duracion:'1 temporada', poster_color:'#1a001a', poster_inicial:'WD', calificacion:8.1, temporadas:1, descripcion:'Wednesday Addams asiste a la Academia Nevermore y descubre un misterio oscuro.' },
  { titulo:'Squid Game', tipo:'serie', anio:2021, generos:['Thriller','Drama'], clasificacion:'TV-MA', duracion_segundos:2940, duracion:'2 temporadas', poster_color:'#1a4a2e', poster_inicial:'SQ', calificacion:8.0, temporadas:2, descripcion:'Personas endeudadas compiten en juegos infantiles con apuestas mortales.' },
  { titulo:'Severance', tipo:'serie', anio:2022, generos:['Misterio','Ciencia Ficción'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'2 temporadas', poster_color:'#003366', poster_inicial:'SV', calificacion:8.7, temporadas:2, descripcion:'Empleados cuya memoria laboral está separada de su vida personal descubren algo perturbador.' },
  { titulo:'The Bear', tipo:'serie', anio:2022, generos:['Drama','Comedia'], clasificacion:'TV-MA', duracion_segundos:1800, duracion:'3 temporadas', poster_color:'#3d2000', poster_inicial:'TB', calificacion:8.8, temporadas:3, descripcion:'Un chef de alta cocina regresa a Chicago para administrar el restaurante familiar.' },
  { titulo:'Andor', tipo:'serie', anio:2022, generos:['Ciencia Ficción','Espionaje'], clasificacion:'TV-14', duracion_segundos:3300, duracion:'2 temporadas', poster_color:'#001a33', poster_inicial:'AN', calificacion:8.4, temporadas:2, descripcion:'La historia de Cassian Andor, el espía que encendió la Rebelión.' },
  { titulo:'The White Lotus', tipo:'serie', anio:2021, generos:['Drama','Misterio'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'3 temporadas', poster_color:'#1a3333', poster_inicial:'WL', calificacion:8.1, temporadas:3, descripcion:'Huéspedes y empleados de un resort de lujo protagonizan dramas oscuros.' },
  { titulo:'Shogun', tipo:'serie', anio:2024, generos:['Drama','Historia'], clasificacion:'TV-MA', duracion_segundos:3600, duracion:'1 temporada', poster_color:'#1a0d00', poster_inicial:'SH', calificacion:8.9, temporadas:1, descripcion:'Un navegante inglés llega al Japón feudal y queda atrapado en las guerras de poder.' },
  { titulo:'True Detective', tipo:'serie', anio:2014, generos:['Crimen','Misterio'], clasificacion:'TV-MA', duracion_segundos:3540, duracion:'4 temporadas', poster_color:'#0d1a00', poster_inicial:'TD', calificacion:8.9, temporadas:4, descripcion:'Detectives investigan crímenes perturbadores a lo largo de décadas.' },
  { titulo:'Euphoria', tipo:'serie', anio:2019, generos:['Drama','Teen'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'2 temporadas', poster_color:'#1a0033', poster_inicial:'EU', calificacion:8.4, temporadas:2, descripcion:'Rue, una adolescente, enfrenta la adicción y el amor en la era digital.' },
  { titulo:'Dark', tipo:'serie', anio:2017, generos:['Ciencia Ficción','Misterio'], clasificacion:'TV-MA', duracion_segundos:3000, duracion:'3 temporadas', poster_color:'#001a1a', poster_inicial:'DK', calificacion:8.8, temporadas:3, descripcion:'Cuatro familias alemanas descubben una conspiración temporal que abarca generaciones.' },
  { titulo:'Peaky Blinders', tipo:'serie', anio:2013, generos:['Crimen','Drama'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'6 temporadas', poster_color:'#1a1000', poster_inicial:'PB', calificacion:8.8, temporadas:6, descripcion:'La familia Shelby domina el crimen organizado en el Birmingham de posguerra.' },
  // Películas adicionales
  { titulo:'The Shawshank Redemption', tipo:'pelicula', anio:1994, generos:['Drama'], clasificacion:'R', duracion_segundos:8520, duracion:'2h 22m', poster_color:'#3a2a1a', poster_inicial:'SR', calificacion:9.3, descripcion:'Dos hombres encarcelados forman una amistad durante décadas mientras conspiran una fuga.' },
  { titulo:'Pulp Fiction', tipo:'pelicula', anio:1994, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:9180, duracion:'2h 33m', poster_color:'#8b7500', poster_inicial:'PF', calificacion:8.9, descripcion:'Varias historias de crimen en Los Ángeles se entrelazan de formas inesperadas.' },
  { titulo:'The Matrix', tipo:'pelicula', anio:1999, generos:['Ciencia Ficción','Acción'], clasificacion:'R', duracion_segundos:8160, duracion:'2h 16m', poster_color:'#001a00', poster_inicial:'TM', calificacion:8.7, descripcion:'Un hacker descubre la verdadera naturaleza de su realidad y su rol en la guerra contra sus controladores.' },
  { titulo:'Forrest Gump', tipo:'pelicula', anio:1994, generos:['Drama','Romance'], clasificacion:'PG-13', duracion_segundos:8520, duracion:'2h 22m', poster_color:'#0d3a66', poster_inicial:'FG', calificacion:8.8, descripcion:'La vida extraordinaria de un hombre con discapacidad intelectual que presencia eventos históricos importantes.' },
  { titulo:'Titanic', tipo:'pelicula', anio:1997, generos:['Romance','Drama'], clasificacion:'PG-13', duracion_segundos:11880, duracion:'3h 18m', poster_color:'#1a3a5c', poster_inicial:'TI', calificacion:7.9, descripcion:'Un romance entre dos pasajeros de diferentes clases sociales abordo del crucero más famoso de la historia.' },
  { titulo:'The Godfather', tipo:'pelicula', anio:1972, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:10500, duracion:'2h 55m', poster_color:'#2a1a0a', poster_inicial:'GF', calificacion:9.2, descripcion:'La saga del imperio criminal de la familia Corleone y la sucesión del líder.' },
  { titulo:'The Godfather Part II', tipo:'pelicula', anio:1974, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:12360, duracion:'3h 26m', poster_color:'#1a0a00', poster_inicial:'G2', calificacion:9.0, descripcion:'La historia paralela del joven Vito Corleone y Michael Corleone en su camino al poder.' },
  { titulo:'Goodfellas', tipo:'pelicula', anio:1990, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:8460, duracion:'2h 21m', poster_color:'#3a0a0a', poster_inicial:'GF', calificacion:8.7, descripcion:'La vida de un miembro de la mafia desde su juventud hasta su participación como testigo.' },
  { titulo:'Scarface', tipo:'pelicula', anio:1983, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:8520, duracion:'2h 22m', poster_color:'#4a1a0a', poster_inicial:'SF', calificacion:8.3, descripcion:'Un refugiado cubano se convierte en el traficante de drogas más poderoso de Miami.' },
  { titulo:'The Wolf of Wall Street', tipo:'pelicula', anio:2013, generos:['Comedia','Crimen','Drama'], clasificacion:'R', duracion_segundos:10920, duracion:'3h 2m', poster_color:'#1a1a3a', poster_inicial:'WW', calificacion:8.2, descripcion:'La ascensión y caída de un estafador de Wall Street.' },
  { titulo:'Heat', tipo:'pelicula', anio:1995, generos:['Crimen','Thriller'], clasificacion:'R', duracion_segundos:10620, duracion:'2h 57m', poster_color:'#1a0a1a', poster_inicial:'HT', calificacion:8.3, descripcion:'Un detective y un criminal se persiguen en Los Ángeles mientras el respeto mutuo crece entre ellos.' },
  { titulo:'Casino', tipo:'pelicula', anio:1995, generos:['Crimen','Drama'], clasificacion:'R', duracion_segundos:10920, duracion:'3h 2m', poster_color:'#3a2a0a', poster_inicial:'CS', calificacion:8.2, descripcion:'La historia de dos amigos cuya amistad se desmorona en el mundo del juego de Las Vegas.' },
  { titulo:'The Departed', tipo:'pelicula', anio:2006, generos:['Crimen','Thriller'], clasificacion:'R', duracion_segundos:8520, duracion:'2h 22m', poster_color:'#0a1a2a', poster_inicial:'DP', calificacion:8.5, descripcion:'Un oficial encubierto y un criminal de la mafia intercambian vidas en Boston.' },
  { titulo:'Gladiator', tipo:'pelicula', anio:2000, generos:['Acción','Drama','Historia'], clasificacion:'R', duracion_segundos:9120, duracion:'2h 32m', poster_color:'#4a3a1a', poster_inicial:'GD', calificacion:8.5, descripcion:'Un general romano es esclavizado y busca venganza en la arena del Coliseo.' },
  { titulo:'Braveheart', tipo:'pelicula', anio:1995, generos:['Acción','Drama','Historia'], clasificacion:'R', duracion_segundos:10980, duracion:'3h 3m', poster_color:'#1a3a1a', poster_inicial:'BH', calificacion:8.4, descripcion:'Un escocés lidera la resistencia contra la opresión inglesa en el siglo XIII.' },
  { titulo:'The Last Samurai', tipo:'pelicula', anio:2003, generos:['Acción','Drama','Historia'], clasificacion:'R', duracion_segundos:9480, duracion:'2h 38m', poster_color:'#1a1a1a', poster_inicial:'LS', calificacion:8.3, descripcion:'Un oficial militar estadounidense es capturado y se convierte en samurái en el Japón del siglo XIX.' },
  { titulo:'Avatar', tipo:'pelicula', anio:2009, generos:['Ciencia Ficción','Aventura'], clasificacion:'PG-13', duracion_segundos:9960, duracion:'2h 46m', poster_color:'#003366', poster_inicial:'AV', calificacion:7.8, descripcion:'Un marine paralizado se une a los Na\'vi para combatir la colonización de Pandora.' },
  { titulo:'Back to the Future', tipo:'pelicula', anio:1985, generos:['Ciencia Ficción','Comedia'], clasificacion:'PG', duracion_segundos:7920, duracion:'2h 12m', poster_color:'#1a1a3a', poster_inicial:'BF', calificacion:8.5, descripcion:'Un adolescente viaja accidentalmente 30 años atrás y debe asegurar que sus padres se enamoren.' },
  { titulo:'Jurassic Park', tipo:'pelicula', anio:1993, generos:['Acción','Aventura','Ciencia Ficción'], clasificacion:'PG-13', duracion_segundos:8100, duracion:'2h 15m', poster_color:'#1a3a1a', poster_inicial:'JP', calificacion:8.2, descripcion:'Los científicos abren un parque con dinosaurios vivos que escapan causando caos.' },
  { titulo:'The Avengers', tipo:'pelicula', anio:2012, generos:['Acción','Aventura','Superhéroes'], clasificacion:'PG-13', duracion_segundos:8280, duracion:'2h 18m', poster_color:'#1a0a1a', poster_inicial:'AV', calificacion:8.0, descripcion:'Los héroes más poderosos de la Tierra se unen para salvar el mundo de una invasión alienígena.' },
  { titulo:'The Infinity War', tipo:'pelicula', anio:2018, generos:['Acción','Aventura','Superhéroes'], clasificacion:'PG-13', duracion_segundos:9540, duracion:'2h 39m', poster_color:'#3a0a0a', poster_inicial:'IW', calificacion:8.4, descripcion:'Los Avengers enfrentan a Thanos, un titán que busca obtener todas las piedras del infinito.' },
  { titulo:'Endgame', tipo:'pelicula', anio:2019, generos:['Acción','Aventura','Superhéroes'], clasificacion:'PG-13', duracion_segundos:10920, duracion:'3h 2m', poster_color:'#1a1a3a', poster_inicial:'EG', calificacion:8.4, descripcion:'Los Avengers restantes idean un plan para deshacerse del daño causado por Thanos.' },
  // Series adicionales
  { titulo:'The Office', tipo:'serie', anio:2005, generos:['Comedia'], clasificacion:'TV-14', duracion_segundos:2700, duracion:'9 temporadas', poster_color:'#8b6f47', poster_inicial:'TO', calificacion:9.0, temporadas:9, descripcion:'Una docuserie sobre los empleados de una sucursal de papelería en Scranton, Pensilvania.' },
  { titulo:'Friends', tipo:'serie', anio:1994, generos:['Comedia','Romance'], clasificacion:'TV-14', duracion_segundos:2400, duracion:'10 temporadas', poster_color:'#ff9933', poster_inicial:'FR', calificacion:8.9, temporadas:10, descripcion:'Seis amigos navegando la vida, el amor y el trabajo en Nueva York.' },
  { titulo:'Game of Thrones', tipo:'serie', anio:2011, generos:['Fantasía','Drama','Acción'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'8 temporadas', poster_color:'#1a1a1a', poster_inicial:'GT', calificacion:9.2, temporadas:8, descripcion:'Múltiples facciones luchan por el control del Trono de Hierro en un reino medieval de fantasía.' },
  { titulo:'The Crown', tipo:'serie', anio:2016, generos:['Drama','Historia'], clasificacion:'TV-MA', duracion_segundos:3600, duracion:'6 temporadas', poster_color:'#4a3a1a', poster_inicial:'CR', calificacion:8.7, temporadas:6, descripcion:'La vida política, personal y amorosa de la Reina Isabel II del Reino Unido.' },
  { titulo:'The Marvelous Mrs. Maisel', tipo:'serie', anio:2017, generos:['Comedia','Drama'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'5 temporadas', poster_color:'#6b4423', poster_inicial:'MM', calificacion:8.7, temporadas:5, descripcion:'Una mujer de la clase alta de Nueva York descubre su talento para la comedia stand-up.' },
  { titulo:'Mindhunter', tipo:'serie', anio:2017, generos:['Crimen','Drama','Thriller'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'2 temporadas', poster_color:'#1a0a1a', poster_inicial:'MH', calificacion:8.6, temporadas:2, descripcion:'Agentes del FBI desarrollan técnicas de perfilado de asesinos seriales.' },
  { titulo:'Ozark', tipo:'serie', anio:2017, generos:['Crimen','Drama','Thriller'], clasificacion:'TV-MA', duracion_segundos:3600, duracion:'4 temporadas', poster_color:'#2a1a0a', poster_inicial:'OZ', calificacion:8.5, temporadas:4, descripcion:'Un contador y su esposa son forzados a lavar dinero para un narcotraficante mexicano.' },
  { titulo:'Dexter', tipo:'serie', anio:2006, generos:['Crimen','Drama','Thriller'], clasificacion:'TV-14', duracion_segundos:2940, duracion:'8 temporadas + reboot', poster_color:'#8b0000', poster_inicial:'DX', calificacion:8.6, temporadas:8, descripcion:'Un forense asesino serial que mata a otros asesinos mantiene su doble vida en secreto.' },
  { titulo:'The Sopranos', tipo:'serie', anio:1999, generos:['Crimen','Drama'], clasificacion:'TV-MA', duracion_segundos:3600, duracion:'6 temporadas', poster_color:'#1a0a0a', poster_inicial:'SP', calificacion:9.2, temporadas:6, descripcion:'Un jefe de la mafia busca terapia mientras gestiona su familia criminal y personal.' },
  { titulo:'Mad Men', tipo:'serie', anio:2007, generos:['Drama'], clasificacion:'TV-14', duracion_segundos:3300, duracion:'7 temporadas', poster_color:'#3a2a1a', poster_inicial:'MM', calificacion:8.6, temporadas:7, descripcion:'Publicistas navegando la industria publicitaria de Nueva York en los años 60.' },
  { titulo:'Better Call Saul', tipo:'serie', anio:2015, generos:['Crimen','Drama'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'6 temporadas', poster_color:'#2a1a0a', poster_inicial:'BC', calificacion:9.0, temporadas:6, descripcion:'La transformación de un abogado fallido en el criminal Saul Goodman.' },
  { titulo:'The Leftovers', tipo:'serie', anio:2014, generos:['Drama','Misterio'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'3 temporadas', poster_color:'#1a1a2a', poster_inicial:'TL', calificacion:8.4, temporadas:3, descripcion:'La vida después de que el 2% de la población desaparece misteriosamente.' },
  { titulo:'Westworld', tipo:'serie', anio:2016, generos:['Ciencia Ficción','Drama','Misterio'], clasificacion:'TV-MA', duracion_segundos:3300, duracion:'4 temporadas', poster_color:'#3a2a1a', poster_inicial:'WW', calificacion:8.5, temporadas:4, descripcion:'Androides en un parque temático del oeste cobran conciencia de sí mismos.' },
]

// ─── SEED ENDPOINT ────────────────────────────────────────────────────────────
app.get('/seed', async (req, res) => {
  try {
    await Contenido.deleteMany({})
    const docs = await Contenido.insertMany(SEED)
    res.json({ ok: true, insertados: docs.length })
  } catch (err) {
    res.status(500).json({ error: err.message })
  }
})

// ─── HTML UI ──────────────────────────────────────────────────────────────────
function renderUI(items) {
  const cards = items.map(c => {
    const generos = (c.generos || []).join(', ')
    const badge = c.tipo === 'serie'
      ? `<span class="badge badge-serie">Serie</span>`
      : `<span class="badge badge-peli">Película</span>`
    const stars = '★'.repeat(Math.round((c.calificacion || 0) / 2))
    return `
    <div class="card" data-tipo="${c.tipo}" data-titulo="${c.titulo.toLowerCase()}" data-genero="${(c.generos||[]).join(',').toLowerCase()}">
      <div class="poster" style="background:${c.poster_color}">
        <span class="poster-letra">${c.poster_inicial || c.titulo.substring(0,2).toUpperCase()}</span>
        <div class="poster-overlay">
          <button class="btn-play">▶ Ver ahora</button>
        </div>
      </div>
      <div class="info">
        <div class="title-row">${badge}<span class="clasificacion">${c.clasificacion||''}</span></div>
        <h3 class="titulo">${c.titulo}</h3>
        <p class="meta">${c.anio} • ${c.duracion}</p>
        <p class="generos">${generos}</p>
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
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#0a0a0f;color:#e5e5e5;min-height:100vh}
header{background:linear-gradient(180deg,#0a0a0f 0%,transparent 100%);padding:1.5rem 2rem;display:flex;align-items:center;gap:2rem;position:sticky;top:0;z-index:100;backdrop-filter:blur(10px);border-bottom:1px solid rgba(255,255,255,0.05)}
.logo{font-size:1.8rem;font-weight:900;background:linear-gradient(135deg,#e50914,#ff6b6b);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.search-box{flex:1;max-width:400px;position:relative}
.search-box input{width:100%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:8px;padding:.6rem 1rem .6rem 2.5rem;color:#fff;font-size:.9rem;outline:none;transition:border .2s}
.search-box input:focus{border-color:#e50914}
.search-box::before{content:'🔍';position:absolute;left:.7rem;top:50%;transform:translateY(-50%);font-size:.85rem}
.filters{display:flex;gap:.5rem;flex-wrap:wrap;padding:1rem 2rem}
.filter-btn{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:20px;color:#ccc;padding:.4rem 1rem;cursor:pointer;font-size:.85rem;transition:all .2s}
.filter-btn:hover,.filter-btn.active{background:#e50914;border-color:#e50914;color:#fff}
.stats{padding:.5rem 2rem;font-size:.85rem;color:#888}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.2rem;padding:1rem 2rem 3rem}
.card{background:#141420;border-radius:12px;overflow:hidden;transition:transform .25s,box-shadow .25s;cursor:pointer}
.card:hover{transform:translateY(-8px) scale(1.02);box-shadow:0 20px 40px rgba(0,0,0,0.6)}
.poster{height:220px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.poster-letra{font-size:3.5rem;font-weight:900;color:rgba(255,255,255,0.3);text-shadow:0 2px 10px rgba(0,0,0,0.5)}
.poster-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.6);opacity:0;display:flex;align-items:center;justify-content:center;transition:opacity .2s}
.card:hover .poster-overlay{opacity:1}
.btn-play{background:#e50914;color:#fff;border:none;border-radius:6px;padding:.6rem 1.2rem;font-weight:700;cursor:pointer;font-size:.9rem}
.info{padding:1rem}
.title-row{display:flex;align-items:center;gap:.5rem;margin-bottom:.4rem}
.badge{font-size:.65rem;padding:.2rem .5rem;border-radius:4px;font-weight:600;text-transform:uppercase}
.badge-serie{background:rgba(59,130,246,0.2);color:#60a5fa;border:1px solid rgba(59,130,246,0.3)}
.badge-peli{background:rgba(229,9,20,0.2);color:#f87171;border:1px solid rgba(229,9,20,0.3)}
.clasificacion{font-size:.7rem;color:#888;border:1px solid #444;padding:.1rem .4rem;border-radius:3px}
.titulo{font-size:.95rem;font-weight:700;margin-bottom:.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.meta{font-size:.75rem;color:#888;margin-bottom:.3rem}
.generos{font-size:.72rem;color:#aaa;margin-bottom:.3rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rating{font-size:.78rem;color:#f59e0b}
.stars{letter-spacing:1px}
.desc{font-size:.72rem;color:#666;margin-top:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.hidden{display:none}
footer{text-align:center;padding:2rem;color:#333;font-size:.8rem;border-top:1px solid #1a1a2e}
</style>
</head>
<body>
<header>
  <div class="logo">StreamFlix</div>
  <div class="search-box">
    <input type="text" id="search" placeholder="Buscar películas y series..." oninput="filtrar()">
  </div>
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
let tipoActivo='todos', generoActivo='', textoBusq='';
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
filtrar();
</script>
</body>
</html>`
}

// ─── RUTAS ────────────────────────────────────────────────────────────────────
app.get('/', (_req, res) => res.send('StreamFlix Catalog Service 🚀 — visita /contenido'))

app.get('/contenido', async (req, res) => {
  const contenidos = await Contenido.find();

  if (req.query.format === 'json') {
    return res.json(contenidos);
  }

  res.send(renderUI(contenidos));
});

app.post('/contenido', async (req, res) => {
  const doc = await Contenido.create(req.body)
  res.json(doc)
})

app.get('/contenido/:id', async (req, res) => {
  const doc = await Contenido.findById(req.params.id)
  if (!doc) return res.status(404).json({ error: 'Contenido no existe' })
  res.json(doc)
})

app.put('/contenido/:id', async (req, res) => {
  const doc = await Contenido.findByIdAndUpdate(req.params.id, req.body, { new: true })
  if (!doc) return res.status(404).json({ error: 'Contenido no existe' })
  res.json(doc)
})

app.delete('/contenido/:id', async (req, res) => {
  await Contenido.findByIdAndDelete(req.params.id)
  res.json({ ok: true })
})

// ─── ARRANQUE ────────────────────────────────────────────────────────────────
app.listen(3001, '0.0.0.0', () => {
  console.log('Catalog service on port 3001');
});