<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // ── 10 usuarios definidos por el equipo ──
            ['username' => 'cristian',  'description' => 'Cristian Mateos',     'email' => 'cristian@cmv.test'],
            ['username' => 'heraclio',  'description' => 'Heraclio Borbujo',    'email' => 'heraclio@cmv.test'],
            ['username' => 'amor',      'description' => 'Amor Rodriguez',      'email' => 'amor@cmv.test'],
            ['username' => 'alberto',   'description' => 'Alberto Bahillo',     'email' => 'alberto@cmv.test'],
            ['username' => 'meli',      'description' => 'Hermelinda Ramos',    'email' => 'meli@cmv.test'],
            ['username' => 'antonio',   'description' => 'Antonio Jañez',       'email' => 'antonio@cmv.test'],
            ['username' => 'jorge',     'description' => 'Jorge Corral',        'email' => 'jorge@cmv.test'],
            ['username' => 'claudio',   'description' => 'Claudio López',       'email' => 'claudio@cmv.test'],
            ['username' => 'gisela',    'description' => 'Gisela Folgueral',    'email' => 'gisela@cmv.test'],
            ['username' => 'ambrosio',  'description' => 'Ambrosio Casado',     'email' => 'ambrosio@cmv.test'],

            // ── 10 usuarios de prueba adicionales ──
            ['username' => 'lucia',     'description' => 'Lucía Fernández',     'email' => 'lucia@cmv.test'],
            ['username' => 'marcos',    'description' => 'Marcos Gutiérrez',    'email' => 'marcos@cmv.test'],
            ['username' => 'sara',      'description' => 'Sara Villanueva',     'email' => 'sara@cmv.test'],
            ['username' => 'diego',     'description' => 'Diego Morales',       'email' => 'diego@cmv.test'],
            ['username' => 'elena',     'description' => 'Elena Castro',        'email' => 'elena@cmv.test'],
            ['username' => 'rafael',    'description' => 'Rafael Suárez',       'email' => 'rafael@cmv.test'],
            ['username' => 'nuria',     'description' => 'Nuria Blanco',        'email' => 'nuria@cmv.test'],
            ['username' => 'pablo',     'description' => 'Pablo Romero',        'email' => 'pablo@cmv.test'],
            ['username' => 'ines',      'description' => 'Inés Delgado',        'email' => 'ines@cmv.test'],
            ['username' => 'felix',     'description' => 'Félix Martínez',      'email' => 'felix@cmv.test'],
        ];

        foreach ($usuarios as $u) {
            Usuario::create([
                'username'    => $u['username'],
                'description' => $u['description'],
                'email'       => $u['email'],
                'password'    => Hash::make($u['username'] . 'paso'),
            ]);
        }

        $this->command->info('20 usuarios creados. Contraseña: paso (para todos).');
    }
}
