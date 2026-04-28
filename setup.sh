#!/bin/bash

echo "🎬 StreamFlix - Setup Rápido"
echo "================================"

echo "📦 Iniciando servicios con Docker..."
docker-compose up -d

echo "⏳ Esperando que MongoDB se inicie..."
sleep 3

echo "📚 Configurando Laravel..."
cd interaction-laravel

if [ ! -f ".env" ]; then
    echo "📋 Copiando .env.example a .env..."
    cp .env.example .env
fi

echo "🔑 Generando clave de aplicación..."
php artisan key:generate --force

echo "🔄 Ejecutando migraciones..."
php artisan migrate --force

echo "🌱 Poblando historial con datos..."
php artisan seed:historial

cd ..

echo "📡 Sincronizando catálogo con MongoDB..."
curl http://localhost:3001/seed

echo ""
echo "✅ ¡Setup completado!"
echo ""
echo "🌐 Accede a:"
echo "   Dashboard: http://localhost:8000"
echo "   Catálogo: http://localhost:3001/contenido"
echo "   Historial Usuario 1: http://localhost:8000/api/historial/usuario/1/ui"
echo ""
echo "📖 Para más información, ver INSTRUCCIONES.md"
