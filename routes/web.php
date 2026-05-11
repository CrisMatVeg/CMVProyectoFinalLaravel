<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\cUsuario;
use App\Http\Controllers\cProyecto;
use App\Http\Controllers\cTarea;
use App\Http\Controllers\cTareaUsuario;
use App\Http\Controllers\cInvitacion;
use App\Http\Controllers\cArchivo;
use App\Http\Controllers\cPredefinicion;


Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/login', [cUsuario::class, 'showLogin'])->name('login');
Route::post('/login', [cUsuario::class, 'login'])->name('login.post');
Route::post('/logout', [cUsuario::class, 'logout'])->name('logout');
Route::get('/registro', [cUsuario::class, 'showRegistro'])->name('registro');
Route::post('/registro', [cUsuario::class, 'registro'])->name('registro.post');
Route::get('/join', [cInvitacion::class, 'join'])->name('invitacion.join');
Route::middleware('auth')->group(function () {
    Route::get('/proyectos', [PageController::class, 'proyectos'])->name('proyectos');
    Route::post('/proyectos', [cProyecto::class, 'store'])->name('proyectos.store');
    Route::get('/proyecto/{proyecto}', [PageController::class, 'proyecto'])->name('proyecto');
    Route::get('/proyecto/{proyecto}/gantt', [PageController::class, 'gantt'])->name('proyecto.gantt');
    Route::get('/proyecto/{proyecto}/calendario', [PageController::class, 'calendario'])->name('proyecto.calendario');
    Route::get('/proyecto/{proyecto}/miembros', [PageController::class, 'miembros'])->name('proyecto.miembros');
    Route::get('/proyecto/{proyecto}/tipo/{tipo}', [PageController::class, 'tareasPorTipo'])->name('proyecto.tipo')->middleware('area.access');
    Route::put('/proyectos/{proyecto}', [cProyecto::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{proyecto}', [cProyecto::class, 'destroy'])->name('proyectos.destroy');
    Route::get('/proyectos/{id}/tareas', [cTarea::class, 'index']);
    Route::resource('tareas', cTarea::class)->except(['index']);
    Route::post('/tarea/usuario/asignar', [cTareaUsuario::class, 'assign'])->name('tarea.usuario.assign');
    Route::post('/tarea/usuario/remover', [cTareaUsuario::class, 'remove'])->name('tarea.usuario.remove');
    Route::post('/tarea/departamento/asignar', [cTareaUsuario::class, 'assignDepartamento'])->name('tarea.departamento.assign');
    Route::post('/invitaciones/generar/{proyecto}', [cInvitacion::class, 'generar'])->name('invitacion.generar');
    Route::post('/invitaciones/unirse', [cInvitacion::class, 'unirse'])->name('invitacion.unirse');
    Route::get('/perfil', [cUsuario::class, 'showPerfil'])->name('perfil');
    Route::put('/perfil', [cUsuario::class, 'actualizarPerfil'])->name('perfil.update');
    Route::get('/proyecto/{proyecto}/archivos', [cArchivo::class, 'index'])->name('proyecto.archivos');
    Route::post('/proyecto/{proyecto}/archivos', [cArchivo::class, 'store'])->name('proyecto.archivos.store');
    Route::delete('/archivos/{archivo}', [cArchivo::class, 'destroy'])->name('archivo.destroy');
    Route::get('/archivos/{archivo}/download', [cArchivo::class, 'download'])->name('archivo.download');
    Route::get('/archivos/{archivo}/preview', [cArchivo::class, 'preview'])->name('archivo.preview');

    // Predefinición de datos (por proyecto)
    Route::get('/proyecto/{proyecto}/predefinicion', [cPredefinicion::class, 'index'])->name('proyecto.predefinicion');
    Route::post('/proyecto/{proyecto}/predefinicion/personajes', [cPredefinicion::class, 'storePersonaje'])->name('proyecto.predefinicion.personajes.store');
    Route::put('/proyecto/{proyecto}/predefinicion/personajes/{personaje}', [cPredefinicion::class, 'updatePersonaje'])->name('proyecto.predefinicion.personajes.update');
    Route::delete('/proyecto/{proyecto}/predefinicion/personajes/{personaje}', [cPredefinicion::class, 'destroyPersonaje'])->name('proyecto.predefinicion.personajes.destroy');
    Route::post('/proyecto/{proyecto}/predefinicion/items', [cPredefinicion::class, 'storeItem'])->name('proyecto.predefinicion.items.store');
    Route::put('/proyecto/{proyecto}/predefinicion/items/{item}', [cPredefinicion::class, 'updateItem'])->name('proyecto.predefinicion.items.update');
    Route::delete('/proyecto/{proyecto}/predefinicion/items/{item}', [cPredefinicion::class, 'destroyItem'])->name('proyecto.predefinicion.items.destroy');
    Route::post('/proyecto/{proyecto}/predefinicion/dialogos', [cPredefinicion::class, 'storeDialogo'])->name('proyecto.predefinicion.dialogos.store');
    Route::put('/proyecto/{proyecto}/predefinicion/dialogos/{dialogo}', [cPredefinicion::class, 'updateDialogo'])->name('proyecto.predefinicion.dialogos.update');
    Route::delete('/proyecto/{proyecto}/predefinicion/dialogos/{dialogo}', [cPredefinicion::class, 'destroyDialogo'])->name('proyecto.predefinicion.dialogos.destroy');
});

