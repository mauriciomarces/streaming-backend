import mongoose from "mongoose";

const ContentSchema = new mongoose.Schema({
  id_contenido: Number,
  titulo: String,
  descripcion: String,
  anio: Number,
  tipo: String,
  generos: [String],
  clasificacion: String,
  poster_url: String,
  url_video: String,
  duracion: String
});

ContentSchema.index({ titulo: "text", descripcion: "text" });

export default mongoose.model("Contenido", ContentSchema);