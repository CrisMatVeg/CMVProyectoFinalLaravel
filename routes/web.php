<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\cUsuario;
use App\Http\Controllers\cProyecto;


Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/login', [cUsuario::class, 'showLogin'])->name('login');
Route::post('/login', [cUsuario::class, 'login'])->name('login.post');
Route::post('/logout', [cUsuario::class, 'logout'])->name('logout');
Route::get('/registro', [cUsuario::class, 'showRegistro'])->name('registro');
Route::post('/registro', [cUsuario::class, 'registro'])->name('registro.post');
Route::middleware('auth')->group(function () {
    Route::get('/proyectos', [PageController::class, 'proyectos'])->name('proyectos');
    Route::post('/proyectos', [cProyecto::class, 'store'])->name('proyectos.store');
    Route::get('/proyecto/{proyecto}', [PageController::class, 'proyecto'])->name('proyecto');
    Route::put('/proyectos/{proyecto}', [cProyecto::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{proyecto}', [cProyecto::class, 'destroy'])->name('proyectos.destroy');
});

