<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;


Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'procesarLogin']);
Route::get('/registro', [PageController::class, 'registro'])->name('registro');
Route::post('/registro', [PageController::class, 'procesarRegistro']);
Route::get('/proyectos', [PageController::class, 'proyectos'])->name('proyectos');
Route::get('/proyecto', [PageController::class, 'proyecto'])->name('proyecto');
