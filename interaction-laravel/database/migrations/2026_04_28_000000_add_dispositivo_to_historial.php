<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial', function (Blueprint $table) {
            if (!Schema::hasColumn('historial', 'dispositivo')) {
                $table->string('dispositivo')->default('web')->after('fecha_ultima_vista');
            }
        });
    }

    public function down(): void
    {
        Schema::table('historial', function (Blueprint $table) {
            $table->dropColumn('dispositivo');
        });
    }
};
