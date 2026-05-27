<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Proyecto;
use App\Models\ProyectoAcceso;
use App\Models\Tarea;
use App\Models\Tipo;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectosPruebaSeeder extends Seeder
{
    private array $tipos;
    private array $estados;
    private array $usuarios;

    public function run(): void
    {
        $this->tipos    = Tipo::pluck('id', 'name')->toArray();
        $this->estados  = Estado::pluck('id', 'name')->toArray();
        $this->usuarios = Usuario::pluck('id', 'username')->toArray();

        $this->crearProyectoRPG();
        $this->crearProyectoAppWeb();
        $this->crearProyectoEditorial();
        $this->crearProyectoERP();
        $this->crearProyectoPodcast();
        $this->crearProyectoELearning();

        $this->command->info('6 proyectos creados con miembros, tareas y participaciones.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 1 — Videojuego RPG: Crónicas del Abismo  (owner: heraclio)
    // Miembros: los 8 usuarios definidos EXCEPTO cristian
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoRPG(): void
    {
        $p = $this->proyecto(
            'Videojuego RPG: Crónicas del Abismo',
            'RPG de acción en 2D con sistema de combate por turnos, mundo procedural y narrativa ramificada.',
            'heraclio'
        );

        $this->acceso($p->id, 'amor',     ['Arte', 'Narrativa']);
        $this->acceso($p->id, 'alberto',  ['Desarrollo']);
        $this->acceso($p->id, 'meli',     ['Audio']);
        $this->acceso($p->id, 'antonio',  ['Marketing']);
        $this->acceso($p->id, 'jorge',    ['Diseño']);
        $this->acceso($p->id, 'claudio',  ['Desarrollo']);
        $this->acceso($p->id, 'gisela',   ['Arte']);
        $this->acceso($p->id, 'ambrosio', ['Narrativa']);

        // Desarrollo
        $this->tarea($p, 'Motor de combate por turnos',        'Sistema de combate con turnos, habilidades y efectos de estado sobre enemigos.',            'Desarrollo', 'En Proceso',  40, '2026-02-01', '2026-04-15', ['alberto', 'claudio']);
        $this->tarea($p, 'Sistema de inventario del jugador',  'Gestión de objetos: slots, stacking, equipamiento y uso de consumibles.',                   'Desarrollo', 'Terminada',   20, '2026-01-15', '2026-02-28', ['alberto']);
        $this->tarea($p, 'Mapa procedural del dungeon',        'Generación aleatoria de niveles con semilla y parámetros de dificultad configurables.',     'Desarrollo', 'Pendiente',   35, '2026-04-20', '2026-06-10', ['claudio']);
        $this->tarea($p, 'Sistema de guardado en la nube',     'Sincronización de partidas entre dispositivos con fallback a guardado local.',              'Desarrollo', 'En Proceso',  15, '2026-03-01', '2026-04-01', ['alberto', 'claudio']);

        // Diseño
        $this->tarea($p, 'UI del menú principal',              'Pantalla de inicio con animaciones de fondo y navegación entre opciones del juego.',        'Diseño',     'Terminada',   12, '2026-01-10', '2026-02-05', ['jorge']);
        $this->tarea($p, 'HUD de combate en tiempo real',      'Barras de vida, maná, iniciativa de turnos y efectos de estado activos.',                   'Diseño',     'En Proceso',  18, '2026-02-10', '2026-04-01', ['jorge']);
        $this->tarea($p, 'Pantalla de carga animada',          'Loader con ilustraciones del mundo y tips de juego rotatorios.',                            'Diseño',     'Pendiente',    8, '2026-04-15', '2026-05-01', ['jorge']);
        $this->tarea($p, 'Iconos y UI del inventario',         'Set de iconos para armas, armaduras, consumibles y objetos de misión.',                     'Diseño',     'Terminada',   10, '2026-01-20', '2026-02-15', ['jorge']);

        // Audio
        $this->tarea($p, 'Banda sonora del menú principal',    'Tema orquestal de 2 minutos para la pantalla de inicio del juego.',                         'Audio',      'En Proceso',  25, '2026-02-01', '2026-04-10', ['meli']);
        $this->tarea($p, 'Efectos de sonido de combate',       'SFX de golpes, magia, esquiva, muerte del personaje y reacciones de enemigos.',             'Audio',      'Terminada',    8, '2026-01-15', '2026-02-10', ['meli']);
        $this->tarea($p, 'Música ambiental del dungeon',        'Loop atmosférico de 4 minutos para zonas de exploración oscuras.',                         'Audio',      'Pendiente',   30, '2026-05-01', '2026-06-15', ['meli']);

        // Narrativa
        $this->tarea($p, 'Historia principal — Acto I',        'Guión del primer acto: presentación del héroe, conflicto inicial y primer jefe.',           'Narrativa',  'Terminada',   20, '2026-01-05', '2026-02-20', ['amor', 'ambrosio']);
        $this->tarea($p, 'Diálogos de NPCs de la aldea',       'Conversaciones con 15 personajes secundarios con variantes según el progreso.',             'Narrativa',  'En Proceso',  15, '2026-03-01', '2026-04-20', ['ambrosio']);
        $this->tarea($p, 'Lore del mundo — enciclopedia',      '40 entradas de lore: bestiario, historia del mundo y facciones del reino.',                 'Narrativa',  'Pendiente',   12, '2026-05-01', '2026-06-01', ['amor']);

        // Marketing
        $this->tarea($p, 'Tráiler de anuncio del juego',       'Guión y supervisión del tráiler de 90 segundos para YouTube y redes sociales.',            'Marketing',  'Pendiente',   18, '2026-05-15', '2026-06-30', ['antonio']);
        $this->tarea($p, 'Gestión de redes sociales',          'Calendario de publicaciones: teasers, devlogs y behind-the-scenes semanales.',              'Marketing',  'En Proceso',  10, '2026-03-01', '2026-06-30', ['antonio']);

        // Arte
        $this->tarea($p, 'Concept art del protagonista',       'Diseño del personaje principal con 4 variantes de armadura y hoja de expresiones.',        'Arte',       'Terminada',   22, '2026-01-05', '2026-02-15', ['gisela']);
        $this->tarea($p, 'Diseño de enemigos principales',     'Concept art de los 8 tipos de enemigo con variantes de élite y versión especial.',          'Arte',       'En Proceso',  28, '2026-02-20', '2026-04-30', ['amor', 'gisela']);
        $this->tarea($p, 'Escenarios — Zona del bosque',       'Fondos pintados y props modulares para el primer bioma del juego.',                         'Arte',       'Pendiente',   35, '2026-05-01', '2026-06-30', ['gisela']);
        $this->tarea($p, 'Animaciones de combate del héroe',   'Sprites frame a frame: idle, ataque, esquiva, muerte y habilidad especial.',                'Arte',       'En Proceso',  40, '2026-03-15', '2026-05-30', ['amor']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 2 — App Web Corporativa  (owner: cristian)
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoAppWeb(): void
    {
        $p = $this->proyecto(
            'App Web Corporativa',
            'Plataforma web interna para gestión de recursos humanos, nóminas e incidencias de la empresa.',
            'cristian'
        );

        $this->acceso($p->id, 'heraclio', ['Desarrollo']);
        $this->acceso($p->id, 'jorge',    ['Diseño']);
        $this->acceso($p->id, 'lucia',    ['Desarrollo']);
        $this->acceso($p->id, 'marcos',   ['Diseño']);
        $this->acceso($p->id, 'sara',     ['Marketing']);

        // Desarrollo
        $this->tarea($p, 'API REST de gestión de empleados',   'Endpoints CRUD para empleados, departamentos y puestos con autenticación Sanctum.',        'Desarrollo', 'En Proceso',  32, '2026-03-01', '2026-04-20', ['heraclio', 'lucia']);
        $this->tarea($p, 'Módulo de autenticación con roles',  'Login con 2FA y roles granulares: admin, RRHH y empleado.',                                'Desarrollo', 'Terminada',   16, '2026-02-01', '2026-02-28', ['heraclio']);
        $this->tarea($p, 'Integración con sistema de nóminas', 'Conexión con API externa de nóminas y generación de recibos en PDF.',                      'Desarrollo', 'Pendiente',   24, '2026-04-25', '2026-06-10', ['lucia']);
        $this->tarea($p, 'Sistema de gestión de incidencias',  'Módulo de tickets con estados, prioridades, comentarios y notificaciones.',                 'Desarrollo', 'En Proceso',  20, '2026-03-15', '2026-05-01', ['heraclio', 'lucia']);

        // Diseño
        $this->tarea($p, 'Wireframes del panel de RRHH',       'Flujo completo del panel de RRHH con vistas de empleados y estadísticas.',                 'Diseño',     'Terminada',   10, '2026-01-20', '2026-02-10', ['jorge']);
        $this->tarea($p, 'Diseño del portal del empleado',     'Interfaz para consultar nóminas, vacaciones e incidencias propias.',                       'Diseño',     'En Proceso',  14, '2026-02-15', '2026-03-30', ['marcos']);
        $this->tarea($p, 'Sistema de diseño corporativo',      'Componentes reutilizables: botones, formularios, tablas y modales con branding.',          'Diseño',     'Pendiente',   16, '2026-04-01', '2026-05-15', ['jorge', 'marcos']);
        $this->tarea($p, 'Adaptación responsive',              'Ajuste de todas las vistas a tablets y móviles para acceso en movilidad.',                  'Diseño',     'Pendiente',   12, '2026-05-20', '2026-06-20', ['marcos']);

        // Marketing
        $this->tarea($p, 'Plan de comunicación interna',       'Estrategia de lanzamiento interno: emails de presentación y sesiones de formación.',       'Marketing',  'Terminada',    6, '2026-02-01', '2026-02-20', ['sara']);
        $this->tarea($p, 'Materiales de formación',            'Guías de usuario, vídeos tutoriales y FAQ para el personal de la empresa.',                'Marketing',  'En Proceso',  12, '2026-03-01', '2026-04-15', ['sara']);
        $this->tarea($p, 'Presentación para dirección',        'Deck de 20 diapositivas con métricas, ROI y roadmap del proyecto.',                        'Marketing',  'Pendiente',    8, '2026-04-20', '2026-05-10', ['sara']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 3 — Diseño Editorial Revista Mensual  (owner: amor)
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoEditorial(): void
    {
        $p = $this->proyecto(
            'Diseño Editorial — Revista Mensual',
            'Producción de la revista mensual de cultura y tecnología: maquetación, contenidos y distribución digital.',
            'amor'
        );

        $this->acceso($p->id, 'gisela',   ['Diseño']);
        $this->acceso($p->id, 'ambrosio', ['Narrativa']);
        $this->acceso($p->id, 'nuria',    ['Diseño']);
        $this->acceso($p->id, 'pablo',    ['Narrativa']);

        // Diseño
        $this->tarea($p, 'Maquetación portada — Junio',        'Diseño de portada e índice del número de junio con fotografía principal.',                 'Diseño',    'En Proceso',  14, '2026-05-15', '2026-05-28', ['gisela']);
        $this->tarea($p, 'Layout del artículo de portada',     'Diseño de 6 páginas del reportaje principal con infografías integradas.',                  'Diseño',    'Pendiente',   10, '2026-05-25', '2026-06-05', ['nuria']);
        $this->tarea($p, 'Diseño de secciones fijas',          'Plantillas reutilizables para las secciones de noticias, opinión y agenda.',               'Diseño',    'Terminada',   12, '2026-04-01', '2026-04-20', ['gisela', 'nuria']);
        $this->tarea($p, 'Infografías del número de junio',    '3 infografías de datos: tecnología, economía creativa y tendencias de diseño.',             'Diseño',    'En Proceso',   8, '2026-05-20', '2026-06-01', ['nuria']);
        $this->tarea($p, 'Export formato digital PDF',         'Export optimizado para lectura digital con hipervínculos y marcadores de sección.',        'Diseño',    'Pendiente',    6, '2026-06-05', '2026-06-10', ['gisela']);

        // Narrativa
        $this->tarea($p, 'Reportaje principal — IA y creatividad', 'Artículo de 4000 palabras sobre el impacto de la IA en las industrias creativas.',     'Narrativa', 'En Proceso',  20, '2026-05-10', '2026-05-28', ['ambrosio']);
        $this->tarea($p, 'Sección de opinión — 3 columnas',    'Redacción de tres columnas de opinión de 600 palabras cada una.',                          'Narrativa', 'Terminada',    8, '2026-04-20', '2026-05-05', ['pablo']);
        $this->tarea($p, 'Noticias breves del mes',            '15 noticias de 150 palabras en tecnología, diseño y cultura digital.',                     'Narrativa', 'Terminada',    6, '2026-05-01', '2026-05-12', ['ambrosio', 'pablo']);
        $this->tarea($p, 'Entrevista exclusiva — diseñador',   'Entrevista de 2000 palabras con fotografías al diseñador gráfico del mes.',                'Narrativa', 'Pendiente',   10, '2026-05-28', '2026-06-08', ['pablo']);
        $this->tarea($p, 'Corrección y edición final',         'Revisión ortotipográfica completa de todos los textos del número de junio.',               'Narrativa', 'Pendiente',    8, '2026-06-08', '2026-06-12', ['ambrosio']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 4 — Sistema ERP Interno  (owner: alberto)
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoERP(): void
    {
        $p = $this->proyecto(
            'Sistema ERP Interno',
            'ERP modular para gestión de stock, pedidos, facturación y contabilidad básica de la empresa.',
            'alberto'
        );

        $this->acceso($p->id, 'claudio', ['Desarrollo']);
        $this->acceso($p->id, 'antonio', ['Marketing']);
        $this->acceso($p->id, 'diego',   ['Desarrollo']);
        $this->acceso($p->id, 'elena',   ['Marketing']);

        // Desarrollo
        $this->tarea($p, 'Módulo de gestión de stock',         'Control de entradas/salidas de almacén con alertas de stock mínimo configurables.',        'Desarrollo', 'En Proceso',  28, '2026-03-01', '2026-04-15', ['claudio', 'diego']);
        $this->tarea($p, 'Sistema de facturación automática',  'Generación de facturas PDF, numeración correlativa y envío automático por email.',          'Desarrollo', 'Terminada',   20, '2026-02-01', '2026-03-10', ['claudio']);
        $this->tarea($p, 'Módulo de pedidos a proveedores',    'Flujo de creación, seguimiento y recepción de pedidos con validaciones de negocio.',        'Desarrollo', 'En Proceso',  24, '2026-03-15', '2026-05-01', ['diego']);
        $this->tarea($p, 'Dashboard de métricas financieras',  'Panel con gráficas de facturación, gastos, margen y forecast mensual.',                    'Desarrollo', 'Pendiente',   16, '2026-05-05', '2026-06-15', ['claudio', 'diego']);
        $this->tarea($p, 'Exportación a Excel y contabilidad', 'Export de datos a formato compatible con el software contable de la empresa.',              'Desarrollo', 'Pendiente',   12, '2026-06-01', '2026-06-30', ['diego']);

        // Marketing / adopción interna
        $this->tarea($p, 'Plan de adopción interna',           'Estrategia para migrar del sistema antiguo al nuevo ERP minimizando interrupciones.',      'Marketing',  'Terminada',    8, '2026-02-01', '2026-02-25', ['antonio']);
        $this->tarea($p, 'Formación del equipo comercial',     'Sesiones presenciales y materiales de formación para el equipo de ventas.',                'Marketing',  'En Proceso',  12, '2026-03-10', '2026-04-30', ['elena']);
        $this->tarea($p, 'Documentación de procesos',          'Manual de procedimientos para las áreas de almacén, compras y facturación.',               'Marketing',  'En Proceso',  10, '2026-03-20', '2026-05-10', ['antonio', 'elena']);
        $this->tarea($p, 'Comunicación del cambio a clientes', 'Emails y FAQs para informar a clientes sobre el nuevo sistema de pedidos online.',         'Marketing',  'Pendiente',    6, '2026-05-15', '2026-06-01', ['elena']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 5 — Podcast Tecnológico  (owner: meli)
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoPodcast(): void
    {
        $p = $this->proyecto(
            'Podcast Tecnológico — ByteZone',
            'Producción y distribución de podcast semanal de tecnología: grabación, edición, mezcla y promoción.',
            'meli'
        );

        $this->acceso($p->id, 'jorge',  ['Audio']);
        $this->acceso($p->id, 'rafael', ['Audio']);
        $this->acceso($p->id, 'ines',   ['Marketing']);

        // Audio
        $this->tarea($p, 'Grabación episodio 12 — IA generativa',  'Grabación de 90 minutos con 2 invitados sobre el estado del arte de la IA.',            'Audio',     'Terminada',    6, '2026-05-05', '2026-05-05', ['jorge', 'rafael']);
        $this->tarea($p, 'Edición y mezcla — episodio 12',         'Corte de silencios, ecualización, compresión y masterización del audio final.',          'Audio',     'En Proceso',   8, '2026-05-06', '2026-05-10', ['jorge']);
        $this->tarea($p, 'Grabación intro y outros de temporada',   'Jingle de entrada (15s) y cierre (10s) para la nueva temporada del podcast.',           'Audio',     'Terminada',    4, '2026-04-10', '2026-04-12', ['rafael']);
        $this->tarea($p, 'Edición episodio 13 — Ciberseguridad',   'Postproducción del episodio sobre amenazas y buenas prácticas de seguridad digital.',   'Audio',     'Pendiente',    8, '2026-05-15', '2026-05-20', ['jorge', 'rafael']);
        $this->tarea($p, 'Revisión de niveles — Temporada 2',      'Coherencia sonora y niveles entre todos los episodios de la temporada completa.',        'Audio',     'Pendiente',   12, '2026-06-01', '2026-06-20', ['rafael']);

        // Marketing
        $this->tarea($p, 'Estrategia de distribución multiplataforma', 'Plan de publicación en Spotify, Apple Podcasts, iVoox y YouTube.',                  'Marketing', 'Terminada',    6, '2026-03-01', '2026-03-15', ['ines']);
        $this->tarea($p, 'Campaña de crecimiento en redes sociales',   'Clips de 60s para TikTok e Instagram con fragmentos de cada episodio.',             'Marketing', 'En Proceso',  14, '2026-04-01', '2026-06-30', ['ines']);
        $this->tarea($p, 'Captación de patrocinadores',                'Dosier de patrocinio y contacto con 10 empresas del sector tecnológico.',            'Marketing', 'Pendiente',   10, '2026-05-01', '2026-06-15', ['ines']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROYECTO 6 — Plataforma E-Learning  (owner: jorge)
    // ─────────────────────────────────────────────────────────────────────────
    private function crearProyectoELearning(): void
    {
        $p = $this->proyecto(
            'Plataforma E-Learning — AulaDigital',
            'LMS propio para cursos online con videolecciones, tests, certificados y seguimiento del progreso del alumno.',
            'jorge'
        );

        $this->acceso($p->id, 'amor',  ['Arte']);
        $this->acceso($p->id, 'meli',  ['Audio']);
        $this->acceso($p->id, 'felix', ['Desarrollo']);
        $this->acceso($p->id, 'lucia', ['Diseño']);

        // Desarrollo
        $this->tarea($p, 'Motor de cursos y lecciones',          'Backend para gestión de cursos, módulos, lecciones y progreso individual del alumno.',   'Desarrollo', 'En Proceso',  30, '2026-03-01', '2026-05-01', ['felix']);
        $this->tarea($p, 'Sistema de tests y evaluaciones',      'Motor de preguntas: test, desarrollo, verdadero/falso y ejercicios de código.',           'Desarrollo', 'Pendiente',   20, '2026-05-05', '2026-06-20', ['felix']);
        $this->tarea($p, 'Generador de certificados PDF',        'Certificados automáticos con nombre, curso, fecha y QR de verificación de autenticidad.', 'Desarrollo', 'Pendiente',   12, '2026-06-01', '2026-06-30', ['felix']);

        // Diseño
        $this->tarea($p, 'UI del panel del alumno',              'Dashboard con progreso de cursos, notas, certificados y recomendaciones personalizadas.',  'Diseño',     'En Proceso',  18, '2026-03-15', '2026-05-01', ['lucia']);
        $this->tarea($p, 'Diseño del reproductor de vídeo',      'Player con subtítulos, control de velocidad, notas y marcadores temporales.',              'Diseño',     'Terminada',   14, '2026-02-01', '2026-03-10', ['lucia']);
        $this->tarea($p, 'Landing y planes de precio',           'Página de marketing con cursos destacados, testimonios y tabla de precios comparativa.',   'Diseño',     'Pendiente',   12, '2026-05-10', '2026-06-10', ['lucia']);

        // Audio
        $this->tarea($p, 'Narración de videolecciones — Módulo 1', 'Grabación de los 12 audios del primer módulo, cada uno de unos 15 minutos.',            'Audio',      'En Proceso',  24, '2026-04-01', '2026-05-20', ['meli']);
        $this->tarea($p, 'Música de fondo para lecciones',        'Tracks instrumentales sin copyright para usar de fondo durante las videolecciones.',     'Audio',      'Terminada',    8, '2026-03-01', '2026-03-20', ['meli']);
        $this->tarea($p, 'Efectos de sonido de la plataforma',    'SFX para notificaciones, logros, tests completados y bienvenida al curso.',              'Audio',      'Pendiente',    6, '2026-05-15', '2026-06-01', ['meli']);

        // Arte
        $this->tarea($p, 'Ilustraciones de cursos y categorías', 'Portadas ilustradas para los 8 cursos iniciales y sus categorías temáticas.',             'Arte',       'Terminada',   16, '2026-02-15', '2026-03-25', ['amor']);
        $this->tarea($p, 'Avatares e iconografía de la plataforma', 'Set de 20 avatares de usuario y 40 iconos para la interfaz del LMS.',                  'Arte',       'En Proceso',  14, '2026-03-25', '2026-05-10', ['amor']);
        $this->tarea($p, 'Badges y trofeos del sistema de logros', 'Diseño de 30 insignias desbloqueables por hitos de aprendizaje completados.',           'Arte',       'Pendiente',   12, '2026-05-15', '2026-06-20', ['amor']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function proyecto(string $nombre, string $desc, string $owner): Proyecto
    {
        return Proyecto::create([
            'name'        => $nombre,
            'description' => $desc,
            'created_by'  => $this->u($owner),
        ]);
    }

    private function acceso(int $proyectoId, string $username, array $tipos): void
    {
        foreach ($tipos as $tipo) {
            ProyectoAcceso::create([
                'proyecto_id' => $proyectoId,
                'user_id'     => $this->u($username),
                'tipo_id'     => $this->t($tipo),
            ]);
        }
    }

    private function tarea(
        Proyecto $proyecto,
        string $titulo,
        string $descripcion,
        string $tipo,
        string $estado,
        int|float $horas,
        ?string $inicio,
        ?string $fin,
        array $asignados = []
    ): Tarea {
        $tarea = Tarea::create([
            'title'           => $titulo,
            'description'     => $descripcion,
            'project_id'      => $proyecto->id,
            'type_id'         => $this->t($tipo),
            'status_id'       => $this->e($estado),
            'estimated_hours' => $horas,
            'start_date'      => $inicio,
            'end_date'        => $fin,
        ]);

        foreach ($asignados as $username) {
            DB::table('participaciones')->insertOrIgnore([
                'task_id'    => $tarea->id,
                'user_id'    => $this->u($username),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $tarea;
    }

    private function u(string $username): int
    {
        return $this->usuarios[$username];
    }

    private function t(string $nombre): int
    {
        return $this->tipos[$nombre];
    }

    private function e(string $nombre): int
    {
        return $this->estados[$nombre];
    }
}
