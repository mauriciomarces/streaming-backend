<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\DashboardController;

// ── DASHBOARD ─────────────────────────────────────────────────────────────────
Route::get('stats', [DashboardController::class, 'stats']);

// ── HISTORIAL CRUD ────────────────────────────────────────────────────────────
Route::prefix('historial')->group(function () {
    Route::post('/',                             [HistorialController::class, 'store']);
    Route::get('/usuario/{id_usuario}',          [HistorialController::class, 'getByUser']);
    Route::get('/usuario/{id_usuario}/ui',       [HistorialController::class, 'getByUserUI']);
    Route::get('/{id}',                          [HistorialController::class, 'show']);
    Route::put('/{id}',                          [HistorialController::class, 'update']);
    Route::delete('/{id}',                       [HistorialController::class, 'destroy']);
});

// ── RUTAS SEMÁNTICAS ──────────────────────────────────────────────────────────
Route::get('/users/{id}/historial',              [HistorialController::class, 'getByUser']);
Route::get('/users/{id}/historial/ui',           [HistorialController::class, 'getByUserUI']);
Route::get('/historial/{id}/with-content',       [HistorialController::class, 'showWithContent']);
