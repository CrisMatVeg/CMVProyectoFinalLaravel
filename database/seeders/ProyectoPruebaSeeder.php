<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\Tipo;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ProyectoPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = Usuario::where('email', 'test@example.com')->firstOrFail();

        $proyecto = Proyecto::create([
            'name'        => 'Proyecto Alpha — Videojuego RPG',
            'description' => 'Proyecto de prueba con tareas variadas para todos los departamentos.',
            'created_by'  => $usuario->id,
        ]);

        $tipos   = Tipo::pluck('id', 'name');
        $estados = Estado::pluck('id', 'name');

        $pendiente  = $estados['Pendiente'];
        $enProceso  = $estados['En Proceso'];
        $terminada  = $estados['Terminada'];

        $tareas = [
            // --- Desarrollo ---
            ['title' => 'Implementar sistema de inventario',       'description' => 'Crear lógica de inventario con slots, stack y drag & drop.',          'type' => 'Desarrollo', 'status' => $enProceso,  'hours' => 24, 'start' => '2026-04-28', 'end' => '2026-05-16'],
            ['title' => 'Optimizar pathfinding de enemigos',       'description' => 'Mejorar A* para mapas grandes sin caídas de FPS.',                    'type' => 'Desarrollo', 'status' => $pendiente,  'hours' => 16, 'start' => '2026-05-19', 'end' => '2026-06-02'],
            ['title' => 'Sistema de guardado automático',          'description' => 'Guardar partida en checkpoints y cada 5 minutos.',                     'type' => 'Desarrollo', 'status' => $terminada,  'hours' => 10, 'start' => '2026-04-25', 'end' => '2026-04-30'],
            ['title' => 'Integración de API de logros',            'description' => 'Conectar con Steam Achievements y fallback local.',                    'type' => 'Desarrollo', 'status' => $pendiente,  'hours' => 8,  'start' => '2026-06-09', 'end' => '2026-06-19'],

            // --- Diseño ---
            ['title' => 'Diseñar HUD principal del jugador',       'description' => 'Barra de vida, maná, minimapa y accesos rápidos.',                    'type' => 'Diseño',     'status' => $terminada,  'hours' => 12, 'start' => '2026-04-25', 'end' => '2026-05-08'],
            ['title' => 'Prototipar pantalla de inicio',           'description' => 'Mockup de menú principal con animaciones de fondo.',                   'type' => 'Diseño',     'status' => $enProceso,  'hours' => 8,  'start' => '2026-05-05', 'end' => '2026-05-15'],
            ['title' => 'Flujo UX del sistema de misiones',        'description' => 'Wireframes del diario de misiones y notificaciones.',                  'type' => 'Diseño',     'status' => $pendiente,  'hours' => 10, 'start' => '2026-05-18', 'end' => '2026-05-30'],
            ['title' => 'Guía de estilos visual del juego',        'description' => 'Paleta de colores, tipografías y espaciado estándar.',                 'type' => 'Diseño',     'status' => $enProceso,  'hours' => 6,  'start' => '2026-04-25', 'end' => '2026-05-02'],

            // --- Audio ---
            ['title' => 'Componer tema principal del juego',       'description' => 'Pieza orquestal de 2-3 min para pantalla de inicio.',                  'type' => 'Audio',      'status' => $terminada,  'hours' => 20, 'start' => '2026-04-25', 'end' => '2026-05-09'],
            ['title' => 'Efectos de sonido de combate',            'description' => 'SFX de golpes, magia, esquivar y muerte del personaje.',               'type' => 'Audio',      'status' => $enProceso,  'hours' => 14, 'start' => '2026-05-04', 'end' => '2026-05-18'],
            ['title' => 'Música ambiental de mazmorras',           'description' => 'Loop atmosférico de 4 min para zonas oscuras.',                        'type' => 'Audio',      'status' => $pendiente,  'hours' => 12, 'start' => '2026-05-25', 'end' => '2026-06-08'],
            ['title' => 'Voces de NPC del pueblo',                 'description' => 'Grabar y editar líneas de diálogo para 8 personajes secundarios.',     'type' => 'Audio',      'status' => $pendiente,  'hours' => 18, 'start' => '2026-06-08', 'end' => '2026-06-27'],

            // --- Narrativa ---
            ['title' => 'Escribir guión del acto I',               'description' => 'Introducción del héroe, conflicto inicial y primer boss.',             'type' => 'Narrativa',  'status' => $terminada,  'hours' => 30, 'start' => '2026-04-25', 'end' => '2026-05-22'],
            ['title' => 'Diálogos de la aldea de inicio',          'description' => 'Diálogos de 15 NPCs con variantes según progreso.',                   'type' => 'Narrativa',  'status' => $enProceso,  'hours' => 20, 'start' => '2026-05-11', 'end' => '2026-05-29'],
            ['title' => 'Lore del mundo — enciclopedia ingame',    'description' => 'Redactar 40 entradas de lore para el menú de coleccionables.',         'type' => 'Narrativa',  'status' => $pendiente,  'hours' => 25, 'start' => '2026-06-15', 'end' => '2026-07-10'],
            ['title' => 'Diseñar árbol de decisiones morales',     'description' => 'Elecciones con consecuencias en acto II y III.',                       'type' => 'Narrativa',  'status' => $pendiente,  'hours' => 15, 'start' => '2026-06-01', 'end' => '2026-06-15'],

            // --- Marketing ---
            ['title' => 'Crear página de Steam del juego',         'description' => 'Descripción, capturas, trailer embed y etiquetas.',                   'type' => 'Marketing',  'status' => $terminada,  'hours' => 6,  'start' => '2026-04-25', 'end' => '2026-05-01'],
            ['title' => 'Campaña de redes sociales — prelanzamiento', 'description' => 'Calendario de posts para Twitter, Instagram y TikTok.',             'type' => 'Marketing',  'status' => $enProceso,  'hours' => 20, 'start' => '2026-05-01', 'end' => '2026-05-22'],
            ['title' => 'Press kit para medios de videojuegos',    'description' => 'Logos, screenshots en alta res, ficha técnica y contacto.',            'type' => 'Marketing',  'status' => $pendiente,  'hours' => 8,  'start' => '2026-06-15', 'end' => '2026-06-25'],
            ['title' => 'Gestionar comunidad en Discord',          'description' => 'Configurar canales, roles, bots y responder feedback de beta.',        'type' => 'Marketing',  'status' => $pendiente,  'hours' => 12, 'start' => '2026-06-01', 'end' => '2026-06-15'],

            // --- Arte ---
            ['title' => 'Concept art del personaje principal',     'description' => 'Diseño de personaje con 4 variantes de armadura.',                    'type' => 'Arte',       'status' => $terminada,  'hours' => 16, 'start' => '2026-04-25', 'end' => '2026-05-08'],
            ['title' => 'Sprites de animación de combate',         'description' => 'Animaciones idle, ataque, esquiva y muerte en pixel art.',             'type' => 'Arte',       'status' => $enProceso,  'hours' => 28, 'start' => '2026-05-04', 'end' => '2026-05-26'],
            ['title' => 'Tileset de mazmorra oscura',              'description' => 'Set de tiles modulares para niveles de cueva: suelo, paredes, trampas.', 'type' => 'Arte',     'status' => $pendiente,  'hours' => 20, 'start' => '2026-06-01', 'end' => '2026-06-24'],
            ['title' => 'Ilustración de portada del juego',        'description' => 'Arte clave para Steam, físico y material promocional.',                'type' => 'Arte',       'status' => $enProceso,  'hours' => 30, 'start' => '2026-05-18', 'end' => '2026-06-12'],
        ];

        foreach ($tareas as $t) {
            Tarea::create([
                'title'           => $t['title'],
                'description'     => $t['description'],
                'project_id'      => $proyecto->id,
                'type_id'         => $tipos[$t['type']],
                'status_id'       => $t['status'],
                'estimated_hours' => $t['hours'],
                'start_date'      => $t['start'],
                'end_date'        => $t['end'],
            ]);
        }

        $this->command->info("Proyecto '{$proyecto->name}' creado con " . count($tareas) . " tareas.");
    }
}
