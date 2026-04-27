import express from 'express'
import mongoose from 'mongoose'
import cors from 'cors'

const app = express()
app.use(cors())
app.use(express.json())

// 🔗 Conexión a Mongo (el mismo contenedor)
mongoose.connect('mongodb://mongodb:27017/ContenidoDB')

// 📦 Modelo de contenido
const ContenidoSchema = new mongoose.Schema({
  titulo: String,
  tipo: String,
  duracion_segundos: Number
})

const Contenido = mongoose.model('Contenido', ContenidoSchema)

app.get('/', (req, res) => {
  res.send('Catalog Service funcionando 🚀')
})
//Obtener contenido
app.get('/contenido', async (req, res) => {
  const contenido = await Contenido.find()
  res.json(contenido)
})
// ✅ Crear contenido (para pruebas)
app.post('/contenido', async (req, res) => {
  const contenido = await Contenido.create(req.body)
  res.json(contenido)
})

// ✅ Obtener contenido por ID (LO QUE LARAVEL NECESITA)
app.get('/contenido/:id', async (req, res) => {
  const contenido = await Contenido.findById(req.params.id)

  if (!contenido) {
    return res.status(404).json({ error: 'Contenido no existe' })
  }

  res.json(contenido)
})

app.listen(3001, () => {
  console.log('Catalog Service corriendo en puerto 3001')
})