# StreamFlix - Backend de Streaming Interactivo

Una plataforma de streaming moderna con catálogo dinámico, historial de visualización y interfaz bonita.

## 🎬 Características

- ✅ **Catálogo completo**: 55+ películas y series
- ✅ **5 Usuarios activos** con perfiles personalizados
- ✅ **Historial de visualización** con seguimiento de progreso
- ✅ **Dashboard bonito** con estadísticas en tiempo real
- ✅ **Interfaz responsive** para desktop y móvil
- ✅ **MongoDB** como base de datos
- ✅ **API REST** completa

## 📋 Requisitos

- Docker & Docker Compose
- Node.js 18+ (para el catálogo)
- PHP 8.2+ & Laravel 11 (para el historial)
- MongoDB 7

## 🚀 Inicio Rápido

### 1. Iniciar los servicios con Docker

```bash
docker-compose up -d
```

Esto inicia:
- **MongoDB**: Puerto 27017
- **Catalog Service**: Puerto 3001 (Node.js)
- **Interaction Service**: Puerto 8000 (Laravel)

### 2. Configurar Laravel

```bash
cd interaction-laravel

# Instalar dependencias
composer install

# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones (incluyendo la nueva tabla de dispositivos)
php artisan migrate

# Poblar el historial con datos de prueba
php artisan seed:historial
```

### 3. Acceder a las interfaces

- **Dashboard Principal**: http://localhost:8000
- **Catálogo de películas/series**: http://localhost:3001/contenido
- **Historial Usuario 1**: http://localhost:8000/api/historial/usuario/1/ui
- **Historial Usuario 2**: http://localhost:8000/api/historial/usuario/2/ui
- **Historial Usuario 3**: http://localhost:8000/api/historial/usuario/3/ui
- **Historial Usuario 4**: http://localhost:8000/api/historial/usuario/4/ui
- **Historial Usuario 5**: http://localhost:8000/api/historial/usuario/5/ui

## 📊 Usuarios del Sistema

| ID | Nombre | Color | Iniciales |
|---|---|---|---|
| 1 | Lucía Martínez | #e50914 (Rojo) | LM |
| 2 | Carlos Rodríguez | #0077b6 (Azul) | CR |
| 3 | Valentina López | #7b2d8b (Púrpura) | VL |
| 4 | Diego Fernández | #2d9e5f (Verde) | DF |
| 5 | Isabella Torres | #e5a009 (Oro) | IT |

## 🎬 Contenido en Catálogo

### Películas (25+)
- Dune: Parte Dos, Oppenheimer, Barbie, El Señor de los Anillos
- Spider-Man: No Way Home, Avatar, Inception, The Dark Knight
- Parasite, Everything Everywhere All at Once, Gladiator II
- The Shawshank Redemption, Pulp Fiction, The Matrix
- Forrest Gump, Titanic, The Godfather y muchas más...

### Series (30+)
- Breaking Bad, Stranger Things, The Last of Us, House of the Dragon
- Squid Game, Wednesday, Severance, The Bear, Andor
- Game of Thrones, The Office, Friends, The Crown
- The Marvelous Mrs. Maisel, Mindhunter, Ozark
- Dexter, The Sopranos, Mad Men, Better Call Saul y más...

## 📡 API Endpoints

### Historial
```
POST   /api/historial                  # Crear historial
GET    /api/historial/usuario/{id}    # Obtener historial (JSON)
GET    /api/historial/usuario/{id}/ui # Obtener historial (HTML)
GET    /api/historial/{id}            # Ver un historial
PUT    /api/historial/{id}            # Actualizar historial
DELETE /api/historial/{id}            # Eliminar historial
```

### Estadísticas
```
GET    /api/stats                      # Obtener estadísticas globales
```

## 🛠️ Comandos Útiles

### Artisan Commands

```bash
# Poblar historial con datos de prueba
php artisan seed:historial

# Ejecutar migraciones
php artisan migrate

# Ejecutar rollback
php artisan migrate:rollback

# Tinker (REPL interactivo)
php artisan tinker
```

### cURL Examples

```bash
# Ver historial de usuario 1 en JSON
curl http://localhost:8000/api/historial/usuario/1

# Ver historial de usuario 1 en HTML
curl http://localhost:8000/api/historial/usuario/1/ui

# Crear nuevo historial
curl -X POST http://localhost:8000/api/historial \
  -H "Content-Type: application/json" \
  -d '{
    "id_usuario": 1,
    "id_contenido": "c001",
    "progreso_segundos": 5000
  }'

# Ver estadísticas
curl http://localhost:8000/api/stats
```

## 📱 Características de la UI

### Dashboard Principal
- Overview de 5 usuarios
- Estadísticas globales
- Tarjetas de perfil interactivas
- Enlaces rápidos a catálogo

### Vista de Historial por Usuario
- Avatar personalizado con color del usuario
- Barra de selección de usuarios
- Estadísticas: Títulos, Completados, En progreso
- Tarjetas de contenido con:
  - Poster con letra inicial
  - Título y tipo (película/serie)
  - Género
  - Barra de progreso
  - Porcentaje visto
  - Tiempo desde última vista
  - Estado (Visto/En progreso)

### Catálogo
- Grid responsive de películas y series
- Búsqueda por título
- Filtrar por tipo (película/serie)
- Filtrar por género
- Calificación y reseña
- Botón "Ver ahora"

## 🎨 Paleta de Colores

- Rojo principal: #e50914 (Netflix style)
- Fondo oscuro: #0a0a0f
- Texto claro: #e5e5e5
- Acentos: #ff6b6b, #4ade80

## 🗄️ Estructura de Base de Datos

### MongoDB Collections

#### `Contenido`
```json
{
  "_id": ObjectId,
  "titulo": "Dune: Parte Dos",
  "tipo": "pelicula",
  "anio": 2024,
  "generos": ["Ciencia Ficción"],
  "clasificacion": "PG-13",
  "duracion_segundos": 9840,
  "poster_color": "#b5860d",
  "calificacion": 8.5
}
```

#### `Historial`
```json
{
  "_id": ObjectId,
  "id_usuario": 1,
  "id_contenido": "c001",
  "tipo_contenido": "pelicula",
  "progreso_segundos": 5000,
  "duracion_total_segundos": 9840,
  "visto_completado": false,
  "fecha_ultima_vista": ISODate("2026-04-28T10:30:00Z"),
  "dispositivo": "web"
}
```

## 🔧 Solución de Problemas

### La base de datos no conecta
```bash
# Verificar que MongoDB está corriendo
docker ps | grep streaming-mongo

# Ver logs de MongoDB
docker logs streaming-mongo
```

### Las migraciones fallan
```bash
# Verificar conexión a MongoDB
php artisan tinker
> DB::connection('mongodb')->getSchemaBuilder()->hasTable('historial')

# Forzar re-crear tabla
php artisan migrate:refresh
```

### El catálogo está vacío
```bash
# Ejecutar el seed del catálogo
curl http://localhost:3001/seed
```

## 📚 Stack Tecnológico

- **Backend Catálogo**: Node.js + Express + Mongoose
- **Backend Historial**: PHP + Laravel 11 + MongoDB
- **Base de Datos**: MongoDB 7
- **Frontend**: HTML5 + CSS3 + Vanilla JavaScript
- **Contenedor**: Docker + Docker Compose

## 📄 Licencia

MIT © 2026 StreamFlix

## 👨‍💻 Autor

Sistema desarrollado como parte de un proyecto de streaming interactivo.

---

**Última actualización**: 28 de Abril de 2026
**Versión**: 1.0.0
