<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\TareaDependencia;
use App\Models\Tipo;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TareasPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = Usuario::where('username', 'cristian')->firstOrFail();

        $proyecto = Proyecto::where('created_by', $usuario->id)
            ->where('name', 'like', '%Prueba%')
            ->firstOrFail();

        $tipos   = Tipo::pluck('id', 'name');
        $estados = Estado::pluck('id', 'name');

        $pendiente = $estados['Pendiente'];
        $enProceso = $estados['En Proceso'];
        $terminada = $estados['Terminada'];

        $tareas = [
            // ── DESARROLLO ──
            ['title' => 'Configurar entorno de desarrollo',       'description' => 'Instalar Docker, Laravel Sail y variables de entorno para el equipo.',             'type' => 'Desarrollo', 'status' => $terminada,  'hours' => 4,  'start' => '2026-04-01', 'end' => '2026-04-02', 'milestone' => false],
            ['title' => 'Módulo de autenticación con 2FA',        'description' => 'Implementar login con verificación por email usando Laravel Fortify.',             'type' => 'Desarrollo', 'status' => $terminada,  'hours' => 16, 'start' => '2026-04-03', 'end' => '2026-04-10', 'milestone' => false],
            ['title' => 'API REST de gestión de proyectos',       'description' => 'Endpoints CRUD para proyectos, tareas y usuarios con autenticación Sanctum.',      'type' => 'Desarrollo', 'status' => $enProceso,  'hours' => 32, 'start' => '2026-04-11', 'end' => '2026-04-28', 'milestone' => false],
            ['title' => 'Sistema de notificaciones en tiempo real','description' => 'Push notifications con Laravel Echo y Pusher para alertas de tareas.',            'type' => 'Desarrollo', 'status' => $pendiente,  'hours' => 20, 'start' => '2026-05-05', 'end' => '2026-05-20', 'milestone' => false],
            ['title' => 'Exportar reportes a PDF y Excel',        'description' => 'Generar reportes de avance de proyecto con gráficos usando DomPDF y PhpSpreadsheet.','type' => 'Desarrollo', 'status' => $pendiente,  'hours' => 12, 'start' => '2026-05-21', 'end' => '2026-05-30', 'milestone' => false],

            // ── DISEÑO ──
            ['title' => 'Sistema de diseño — Design System',      'description' => 'Definir tokens de color, tipografías, espaciado y componentes base en Figma.',     'type' => 'Diseño',     'status' => $terminada,  'hours' => 14, 'start' => '2026-04-01', 'end' => '2026-04-09', 'milestone' => false],
            ['title' => 'Wireframes del dashboard principal',     'description' => 'Flujo completo del panel de control con filtros, métricas y vista Kanban.',        'type' => 'Diseño',     'status' => $terminada,  'hours' => 10, 'start' => '2026-04-07', 'end' => '2026-04-14', 'milestone' => false],
            ['title' => 'Diseño de pantalla de creación de tarea','description' => 'Formulario modal con asignación de usuarios, fechas y dependencias.',             'type' => 'Diseño',     'status' => $enProceso,  'hours' => 8,  'start' => '2026-04-15', 'end' => '2026-04-22', 'milestone' => false],
            ['title' => 'Prototipo interactivo en Figma',         'description' => 'Prototipo navegable de los 5 flujos principales para pruebas con usuarios.',       'type' => 'Diseño',     'status' => $pendiente,  'hours' => 12, 'start' => '2026-04-28', 'end' => '2026-05-08', 'milestone' => false],
            ['title' => 'Adaptación responsive para móvil',       'description' => 'Ajustar todas las pantallas a breakpoints 320px, 768px y 1280px.',                 'type' => 'Diseño',     'status' => $pendiente,  'hours' => 16, 'start' => '2026-05-12', 'end' => '2026-05-23', 'milestone' => false],

            // ── AUDIO ──
            ['title' => 'Sonidos de interfaz — UI SFX',           'description' => 'Efectos de sonido para clicks, errores, notificaciones y drag & drop.',            'type' => 'Audio',      'status' => $terminada,  'hours' => 6,  'start' => '2026-04-05', 'end' => '2026-04-08', 'milestone' => false],
            ['title' => 'Música de fondo del dashboard',          'description' => 'Loop ambiental suave de 3 min para modo de trabajo concentrado (lo-fi).',          'type' => 'Audio',      'status' => $enProceso,  'hours' => 10, 'start' => '2026-04-14', 'end' => '2026-04-22', 'milestone' => false],
            ['title' => 'Jingle de onboarding',                   'description' => 'Pieza de 15 segundos para la bienvenida de nuevos usuarios al sistema.',           'type' => 'Audio',      'status' => $pendiente,  'hours' => 4,  'start' => '2026-04-28', 'end' => '2026-04-30', 'milestone' => false],
            ['title' => 'Grabación de tutoriales en video',        'description' => 'Narración en audio para 6 videos tutoriales de uso del sistema.',                  'type' => 'Audio',      'status' => $pendiente,  'hours' => 8,  'start' => '2026-05-05', 'end' => '2026-05-12', 'milestone' => false],

            // ── NARRATIVA ──
            ['title' => 'Redactar política de privacidad',        'description' => 'Documento legal sobre uso de datos, cookies y GDPR para la plataforma.',          'type' => 'Narrativa',  'status' => $terminada,  'hours' => 6,  'start' => '2026-04-02', 'end' => '2026-04-05', 'milestone' => false],
            ['title' => 'Copys de la interfaz de usuario',        'description' => 'Textos de botones, mensajes de error, tooltips y confirmaciones en tono claro.',   'type' => 'Narrativa',  'status' => $terminada,  'hours' => 8,  'start' => '2026-04-07', 'end' => '2026-04-11', 'milestone' => false],
            ['title' => 'Documentación técnica de la API',        'description' => 'Guía completa de endpoints, parámetros, respuestas y ejemplos con Postman.',       'type' => 'Narrativa',  'status' => $enProceso,  'hours' => 20, 'start' => '2026-04-15', 'end' => '2026-04-29', 'milestone' => false],
            ['title' => 'Guía de usuario — manual de ayuda',      'description' => 'Manual interactivo con capturas paso a paso de cada módulo del sistema.',          'type' => 'Narrativa',  'status' => $pendiente,  'hours' => 15, 'start' => '2026-05-05', 'end' => '2026-05-16', 'milestone' => false],
            ['title' => 'Notas de versión — Changelog v1.0',      'description' => 'Redactar release notes del lanzamiento con todas las funcionalidades incluidas.',   'type' => 'Narrativa',  'status' => $pendiente,  'hours' => 4,  'start' => '2026-05-26', 'end' => '2026-05-28', 'milestone' => false],

            // ── MARKETING ──
            ['title' => 'Análisis de competencia',                'description' => 'Comparativa de Trello, Asana y Monday: precios, funciones y posicionamiento.',    'type' => 'Marketing',  'status' => $terminada,  'hours' => 8,  'start' => '2026-04-01', 'end' => '2026-04-06', 'milestone' => false],
            ['title' => 'Landing page de presentación',           'description' => 'Página web con propuesta de valor, capturas del sistema y formulario de contacto.', 'type' => 'Marketing',  'status' => $enProceso,  'hours' => 16, 'start' => '2026-04-14', 'end' => '2026-04-25', 'milestone' => false],
            ['title' => 'Campaña de email marketing — beta',      'description' => 'Secuencia de 4 emails para invitar a usuarios beta y hacer onboarding.',          'type' => 'Marketing',  'status' => $pendiente,  'hours' => 10, 'start' => '2026-05-01', 'end' => '2026-05-09', 'milestone' => false],
            ['title' => 'Publicaciones en LinkedIn y X',          'description' => 'Calendario de 20 posts para el mes de lanzamiento: teaser, features y demo.',     'type' => 'Marketing',  'status' => $pendiente,  'hours' => 12, 'start' => '2026-05-12', 'end' => '2026-05-23', 'milestone' => false],
            ['title' => 'Demo grabada del sistema completo',      'description' => 'Video de 3-5 min mostrando el flujo principal para usar en redes y landing.',      'type' => 'Marketing',  'status' => $pendiente,  'hours' => 6,  'start' => '2026-05-19', 'end' => '2026-05-21', 'milestone' => false],

            // ── ARTE ──
            ['title' => 'Identidad visual — logotipo',            'description' => 'Diseño de logo en variantes color, blanco/negro y favicon para el sistema.',       'type' => 'Arte',       'status' => $terminada,  'hours' => 10, 'start' => '2026-04-01', 'end' => '2026-04-06', 'milestone' => false],
            ['title' => 'Ilustraciones de estados vacíos',        'description' => 'Ilustraciones para pantallas sin datos: sin tareas, sin proyectos, sin red.',       'type' => 'Arte',       'status' => $terminada,  'hours' => 8,  'start' => '2026-04-08', 'end' => '2026-04-13', 'milestone' => false],
            ['title' => 'Iconografía del sistema',                'description' => 'Set de 60+ iconos SVG consistentes para acciones, tipos y estados del sistema.',    'type' => 'Arte',       'status' => $enProceso,  'hours' => 14, 'start' => '2026-04-14', 'end' => '2026-04-23', 'milestone' => false],
            ['title' => 'Animaciones de transición (Lottie)',     'description' => 'Micro-animaciones para loaders, éxito, error y drag & drop con Lottie/JSON.',      'type' => 'Arte',       'status' => $pendiente,  'hours' => 12, 'start' => '2026-04-28', 'end' => '2026-05-07', 'milestone' => false],
            ['title' => 'Material gráfico para marketing',        'description' => 'Banners, thumbnails y assets para redes sociales en formato Figma exportable.',     'type' => 'Arte',       'status' => $pendiente,  'hours' => 10, 'start' => '2026-05-12', 'end' => '2026-05-20', 'milestone' => false],
        ];

        $creadas = [];
        foreach ($tareas as $t) {
            $tarea = Tarea::create([
                'title'           => $t['title'],
                'description'     => $t['description'],
                'project_id'      => $proyecto->id,
                'type_id'         => $tipos[$t['type']],
                'status_id'       => $t['status'],
                'estimated_hours' => $t['hours'],
                'start_date'      => $t['start'],
                'end_date'        => $t['end'],
                'is_milestone'    => $t['milestone'],
            ]);
            $creadas[$t['title']] = $tarea;
        }

        // Dependencias lógicas entre tareas
        $dependencias = [
            'API REST de gestión de proyectos'        => 'Módulo de autenticación con 2FA',
            'Sistema de notificaciones en tiempo real' => 'API REST de gestión de proyectos',
            'Exportar reportes a PDF y Excel'         => 'API REST de gestión de proyectos',
            'Diseño de pantalla de creación de tarea' => 'Wireframes del dashboard principal',
            'Prototipo interactivo en Figma'          => 'Diseño de pantalla de creación de tarea',
            'Adaptación responsive para móvil'        => 'Prototipo interactivo en Figma',
            'Documentación técnica de la API'         => 'API REST de gestión de proyectos',
            'Notas de versión — Changelog v1.0'       => 'Documentación técnica de la API',
            'Publicaciones en LinkedIn y X'           => 'Landing page de presentación',
            'Demo grabada del sistema completo'       => 'Landing page de presentación',
            'Material gráfico para marketing'         => 'Identidad visual — logotipo',
        ];

        foreach ($dependencias as $tarea => $prereq) {
            if (isset($creadas[$tarea]) && isset($creadas[$prereq])) {
                DB::table('tarea_dependencias')->insertOrIgnore([
                    'task_id'       => $creadas[$tarea]->id,
                    'depends_on_id' => $creadas[$prereq]->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        $this->command->info(
            "Proyecto '{$proyecto->name}' cargado con " . count($tareas) . " tareas y " . count($dependencias) . " dependencias."
        );
    }
}
