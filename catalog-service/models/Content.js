import mongoose from "mongoose";

const ContentSchema = new mongoose.Schema(
  {
    titulo:              { type: String, required: true },
    descripcion:         { type: String, default: "" },
    anio:                { type: Number, default: 2020 },
    tipo:                { type: String, enum: ["pelicula", "serie"], required: true },
    generos:             { type: [String], default: [] },
    clasificacion:       { type: String, default: "PG-13" }, // G, PG, PG-13, R, TV-MA…
    duracion_segundos:   { type: Number, required: true },   // para compatibilidad con CatalogService
    duracion:            { type: String, default: "" },       // texto legible "2h 10m" / "4 temporadas"
    poster_color:        { type: String, default: "#1a1a2e" },// color de fondo del poster generado
    poster_inicial:      { type: String, default: "" },       // 1-2 letras para el icono del poster
    calificacion:        { type: Number, default: 7.0 },      // 0-10
    temporadas:          { type: Number, default: null },      // solo series
  },
  { timestamps: true }
);

ContentSchema.index({ titulo: "text", descripcion: "text" });

export default mongoose.model("Contenido", ContentSchema);