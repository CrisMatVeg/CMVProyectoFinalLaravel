<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar restricciones de clave foránea para poder truncar en cualquier orden
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Vaciar tablas dinámicas (datos de prueba/usuario), de hoja a raíz
        DB::table('foro_archivos')->truncate();
        DB::table('foro_mensajes')->truncate();
        DB::table('foro_hilos')->truncate();
        DB::table('notas_tarea')->truncate();
        DB::table('tarea_dependencias')->truncate();
        DB::table('participaciones')->truncate();
        DB::table('archivo_tipo')->truncate();
        DB::table('archivos')->truncate();
        DB::table('tareas')->truncate();
        DB::table('invitaciones')->truncate();
        DB::table('proyecto_accesos')->truncate();
        DB::table('dialogos')->truncate();
        DB::table('items')->truncate();
        DB::table('personajes')->truncate();
        DB::table('proyectos')->truncate();
        DB::table('usuarios')->truncate();
        DB::table('users')->truncate();
        DB::table('sessions')->truncate();
        DB::table('password_reset_tokens')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Cargar datos fijos del sistema (idempotente: usa firstOrCreate)
        $this->call(TiposEstadosSeeder::class);

        // Cargar datos de prueba
        $this->call([
            UsuariosSeeder::class,
            ProyectosPruebaSeeder::class,
        ]);
    }
}
