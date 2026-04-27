<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historials', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario');
            $table->string('id_contenido'); // IMPORTANTE: string por Mongo
            $table->string('tipo_contenido');
            $table->integer('progreso_segundos');
            $table->integer('duracion_total_segundos');
            $table->boolean('visto_completado');
            $table->timestamp('fecha_ultima_vista');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historials');
    }
};
