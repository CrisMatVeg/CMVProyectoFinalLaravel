<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Tipo;
use App\Models\Estado;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tipos de tareas
        $tipos = ['Desarrollo', 'Diseño', 'Audio', 'Narrativa', 'Marketing', 'Arte'];
        foreach ($tipos as $tipo) {
            Tipo::firstOrCreate(['name' => $tipo]);
        }

        // Estados de tareas
        $estados = ['Pendiente', 'En Proceso', 'Terminada'];
        foreach ($estados as $estado) {
            Estado::firstOrCreate(['name' => $estado]);
        }

        // Usuario de prueba (password = username + password concatenados, igual que el registro)
        $username = 'testuser';
        $password = 'password';
        Usuario::create([
            'username'    => $username,
            'email'       => 'test@example.com',
            'password'    => bcrypt($username . $password),
            'description' => 'Usuario de prueba para el sistema',
        ]);
    }
}
