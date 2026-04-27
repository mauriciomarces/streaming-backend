<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;

// Rutas de historial (CRUD base)
Route::prefix('historial')->group(function () {
    Route::post('/', [HistorialController::class, 'store']);
    Route::get('/usuario/{id_usuario}', [HistorialController::class, 'getByUser']);
    Route::get('/{id}', [HistorialController::class, 'show']);
    Route::put('/{id}', [HistorialController::class, 'update']);
    Route::delete('/{id}', [HistorialController::class, 'destroy']);
});

// Rutas REST semánticas
Route::get('/users/{id}/historial', [HistorialController::class, 'getByUser']);
Route::get('/historial/{id}/with-content', [HistorialController::class, 'showWithContent']);
