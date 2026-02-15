<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\cUsuario;
use App\Http\Controllers\cProyecto;
use App\Http\Controllers\cDepartamento;
use App\Http\Controllers\cTarea;
use App\Http\Controllers\cTareaUsuario;
use App\Http\Controllers\cUsuarioDepartamento;


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
    Route::get('/proyectos/{id}/departamentos', [cDepartamento::class, 'index']);
    Route::resource('departamentos', cDepartamento::class)->except(['index']);
    Route::get('/departamentos/{id}/tareas', [cTarea::class, 'index']);
    Route::resource('tareas', cTarea::class)->except(['index']);
    Route::post('/departamento/usuario/asignar', [cUsuarioDepartamento::class, 'assign'])->name('departamento.usuario.assign');
    Route::post('/departamento/usuario/remover', [cUsuarioDepartamento::class, 'remove'])->name('departamento.usuario.remove');
    Route::post('/tarea/usuario/asignar', [cTareaUsuario::class, 'assign'])->name('tarea.usuario.assign');
    Route::post('/tarea/usuario/remover', [cTareaUsuario::class, 'remove'])->name('tarea.usuario.remove');
});

