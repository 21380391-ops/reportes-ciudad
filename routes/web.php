<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'rol:usuario'])->group(function () {
    Route::get('/reportes/crear', [ReporteController::class, 'create'])->name('reportes.create');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
});

Route::middleware(['auth', 'rol:moderador,administrador'])->group(function () {
    Route::get('/panel/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::patch('/panel/reportes/{id}/estado', [ReporteController::class, 'updateEstado'])->name('reportes.updateEstado');
});
require __DIR__.'/auth.php';
