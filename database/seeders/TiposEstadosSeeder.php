<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TiposEstadosSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = ['Desarrollo', 'Diseño', 'Audio', 'Narrativa', 'Marketing', 'Arte'];
        foreach ($tipos as $tipo) {
            Tipo::firstOrCreate(['name' => $tipo]);
        }

        $estados = ['Pendiente', 'En Proceso', 'Terminada'];
        foreach ($estados as $estado) {
            Estado::firstOrCreate(['name' => $estado]);
        }

        $this->command->info('Tipos y estados cargados.');
    }
}
