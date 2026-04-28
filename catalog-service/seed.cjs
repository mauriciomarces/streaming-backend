const mongoose = require('mongoose');

mongoose.connect('mongodb://mongodb:27017/ContenidoDB');

const Contenido = mongoose.model('Contenido', new mongoose.Schema({
  id_contenido: String,
  titulo: String,
  tipo: String,
  duracion_segundos: Number
}));

async function seed() {
  await Contenido.deleteMany({});

  await Contenido.insertMany([
    { id_contenido: "c001", titulo: "Oppenheimer", tipo: "pelicula", duracion_segundos: 11100 },
    { id_contenido: "c002", titulo: "Inception", tipo: "pelicula", duracion_segundos: 8880 },
    { id_contenido: "c003", titulo: "Interstellar", tipo: "pelicula", duracion_segundos: 10140 },
    { id_contenido: "c004", titulo: "The Dark Knight", tipo: "pelicula", duracion_segundos: 9120 },
    { id_contenido: "c005", titulo: "Spider-Man No Way Home", tipo: "pelicula", duracion_segundos: 9000 },
    { id_contenido: "c006", titulo: "Dark", tipo: "serie", duracion_segundos: 3000 },
    { id_contenido: "c007", titulo: "Severance", tipo: "serie", duracion_segundos: 3300 },
    { id_contenido: "c008", titulo: "Shogun", tipo: "serie", duracion_segundos: 3600 },
    { id_contenido: "c009", titulo: "Clean Code Audiolibro", tipo: "audio", duracion_segundos: 7200 },
    { id_contenido: "c010", titulo: "Curso Docker", tipo: "curso", duracion_segundos: 5400 }
  ]);

  console.log("CATALOGO GRANDE LISTO");
  process.exit();
}

seed();