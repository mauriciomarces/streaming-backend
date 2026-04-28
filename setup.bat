@echo off
setlocal enabledelayedexpansion

echo 🎬 StreamFlix - Setup Rapido
echo ================================

echo 📦 Iniciando servicios con Docker...
docker-compose up -d

echo ⏳ Esperando que MongoDB se inicie...
timeout /t 3 /nobreak

echo 📚 Configurando Laravel...
cd interaction-laravel

if not exist ".env" (
    echo 📋 Copiando .env.example a .env...
    copy .env.example .env
)

echo 🔑 Generando clave de aplicación...
php artisan key:generate --force

echo 🔄 Ejecutando migraciones...
php artisan migrate --force

echo 🌱 Poblando historial con datos...
php artisan seed:historial

cd ..

echo 📡 Sincronizando catálogo con MongoDB...
curl http://localhost:3001/seed

echo.
echo ✅ ¡Setup completado!
echo.
echo 🌐 Accede a:
echo    Dashboard: http://localhost:8000
echo    Catálogo: http://localhost:3001/contenido
echo    Historial Usuario 1: http://localhost:8000/api/historial/usuario/1/ui
echo.
echo 📖 Para más información, ver INSTRUCCIONES.md
pause
