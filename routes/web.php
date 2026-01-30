<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\cUsuario;


Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/login', [cUsuario::class, 'showLogin'])->name('login');
Route::post('/login', [cUsuario::class, 'login'])->name('login.post');
Route::post('/logout', [cUsuario::class, 'logout'])->name('logout');
Route::get('/registro', [cUsuario::class, 'showRegistro'])->name('registro');
Route::post('/registro', [cUsuario::class, 'registro'])->name('registro.post');
Route::get('/proyectos', [PageController::class, 'proyectos'])->name('proyectos')->middleware('auth');
Route::get('/proyecto', [PageController::class, 'proyecto'])->name('proyecto')->middleware('auth');
