# 🎬 Streaming Backend

Backend de microservicios para una plataforma de streaming, construido con **Node.js + MongoDB** para el catálogo y **Laravel + SQLite** para las interacciones de usuario.

## Arquitectura

```
streaming-backend/
├── catalog-service/          # Microservicio Node.js (Express + MongoDB)
│   ├── index.js              # Servidor Express + rutas /contenido
│   ├── Dockerfile
│   └── package.json
│
├── interaction-laravel/      # Microservicio Laravel (PHP 8.3)
│   ├── app/
│   │   ├── Http/Controllers/HistorialController.php
│   │   ├── Http/Requests/StoreHistorialRequest.php
│   │   ├── Models/Historial.php
│   │   └── Services/CatalogService.php
│   ├── database/migrations/
│   ├── routes/api.php
│   └── Dockerfile
│
└── docker-compose.yml        # Orquestación de todos los servicios
```

## Servicios Docker

| Servicio             | Puerto | Descripción                          |
|----------------------|--------|--------------------------------------|
| `mongodb`            | 27017  | Base de datos MongoDB (ContenidoDB)  |
| `catalog-service`    | 3001   | API REST de catálogo (Node.js)       |
| `interaction-laravel`| 8000   | API de historial de usuarios (Laravel)|

> **Red interna Docker:** todos los servicios se comunican por nombre de contenedor.  
> Laravel llama a `http://catalog-service:3001` y el catalog usa `mongodb://mongodb:27017`.

## Levantar el proyecto

### Requisitos
- [Docker](https://www.docker.com/) y Docker Compose

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/<tu-usuario>/streaming-backend.git
cd streaming-backend

# 2. Configurar variables de entorno de Laravel
cp interaction-laravel/.env.example interaction-laravel/.env
# Edita interaction-laravel/.env si necesitas cambiar algo

# 3. Configurar variables de entorno del catalog-service (opcional)
cp catalog-service/.env.example catalog-service/.env

# 4. Levantar todos los servicios
docker-compose up --build
```

### Endpoints disponibles

**Catalog Service** (`http://localhost:3001`)
```
GET  /contenido          → Lista todo el contenido
GET  /contenido/:id      → Obtiene contenido por ID
POST /contenido          → Crea nuevo contenido
```

**Interaction Laravel** (`http://localhost:8000/api`)
```
POST   /historial                       → Registra reproducción
GET    /historial/usuario/:id_usuario   → Historial de un usuario
GET    /historial/:id                   → Ver un historial
PUT    /historial/:id                   → Actualizar progreso
DELETE /historial/:id                   → Eliminar registro
GET    /users/:id/historial             → Alias REST semántico
GET    /historial/:id/with-content      → Historial + datos del catálogo
```

## Variables de entorno importantes

### `interaction-laravel/.env`

| Variable         | Valor Docker          | Descripción                        |
|------------------|-----------------------|------------------------------------|
| `MONGO_HOST`     | `mongodb`             | Nombre del servicio MongoDB        |
| `MONGO_PORT`     | `27017`               | Puerto MongoDB                     |
| `MONGO_DB`       | `ContenidoDB`         | Base de datos del catálogo         |
| `CATALOGO_URL`   | `http://catalog-service:3001` | URL interna del catalog  |

### `catalog-service/.env`

| Variable    | Valor Docker                          | Descripción         |
|-------------|---------------------------------------|---------------------|
| `MONGO_URI` | `mongodb://mongodb:27017/ContenidoDB` | URI de MongoDB      |
| `PORT`      | `3001`                                | Puerto del servicio |

## Base de datos

- **MongoDB** (`ContenidoDB`): almacena el catálogo de contenido (películas, series, etc.)
- **SQLite** (`interaction-laravel/database/database.sqlite`): almacena el historial de reproducción de usuarios (generado automáticamente por las migraciones de Laravel)

### Correr migraciones de Laravel (dentro del contenedor)

```bash
docker-compose exec interaction-laravel php artisan migrate
```
