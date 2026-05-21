# PIXEL — Documentación técnica completa

**App de gestión de proyectos de desarrollo de videojuegos**
Laravel 12 · PHP 8.2 · MySQL · Tailwind CSS 4 · Vite 7 · Livewire 3 · WebSockets

---

## Índice

1. [Resumen de la aplicación](#1-resumen-de-la-aplicación)
2. [Stack tecnológico](#2-stack-tecnológico)
3. [Arquitectura MVC y flujo de una petición](#3-arquitectura-mvc-y-flujo-de-una-petición)
4. [Estructura de directorios](#4-estructura-de-directorios)
5. [Base de datos — Modelos y relaciones](#5-base-de-datos--modelos-y-relaciones)
6. [Rutas — routes/web.php](#6-rutas--routeswebphp)
7. [Sistema de autenticación](#7-sistema-de-autenticación)
8. [Gestión de proyectos](#8-gestión-de-proyectos)
9. [Gestión de tareas](#9-gestión-de-tareas)
10. [Control de acceso por áreas](#10-control-de-acceso-por-áreas)
11. [Sistema de invitaciones](#11-sistema-de-invitaciones)
12. [Sistema de archivos](#12-sistema-de-archivos)
13. [Diagrama de Gantt](#13-diagrama-de-gantt)
14. [Vista Calendario](#14-vista-calendario)
15. [Foro y chat en tiempo real](#15-foro-y-chat-en-tiempo-real)
    - [15.1 ¿Qué es un WebSocket?](#151-qué-es-un-websocket)
    - [15.2 Componentes del chat](#152-componentes-del-chat)
    - [15.3 Diferencia desarrollo vs producción](#153-diferencia-desarrollo-vs-producción)
    - [15.4 Flujo completo de un mensaje](#154-flujo-completo-de-un-mensaje)
    - [15.5 Archivos clave del chat](#155-archivos-clave-del-chat)
    - [15.6 Cómo arrancar el sistema](#156-cómo-arrancar-el-sistema)
16. [Módulo de datos predefinidos](#16-módulo-de-datos-predefinidos)
17. [Despliegue en producción (Ionos + Plesk)](#17-despliegue-en-producción-ionos--plesk)
18. [Catálogo de requisitos funcionales](#18-catálogo-de-requisitos-funcionales)

---

## 1. Resumen de la aplicación

PIXEL es una plataforma web colaborativa para equipos de desarrollo de videojuegos. Permite organizar proyectos, crear y asignar tareas por áreas de trabajo (Desarrollo, Diseño, Audio, etc.), invitar colaboradores con permisos granulares, gestionar archivos adjuntos, y comunicarse en tiempo real a través de un foro con chat por WebSockets.

A diferencia de herramientas genéricas como Trello o Notion, PIXEL incluye un módulo especializado para definir entidades del videojuego (Personajes, Ítems, Diálogos) y exportarlas en formato JSON adaptado a Unity, Unreal Engine o Godot.

**URL de producción:** `https://cristianmatveg.ieslossauces.es`

---

## 2. Stack tecnológico

### Backend (PHP / servidor)

| Tecnología | Versión | Para qué se usa |
|---|---|---|
| Laravel | 12.x | Framework principal: routing, ORM, auth, broadcasting |
| PHP | 8.2 | Lenguaje de programación del servidor |
| MySQL | 8.0 | Base de datos relacional |
| Livewire | 3.x | Componentes reactivos PHP sin escribir JS (chat) |
| Laravel Reverb | — | Servidor WebSocket propio (usado en **desarrollo**) |
| pusher/pusher-php-server | — | SDK para emitir eventos a Pusher (usado en **producción**) |

### Frontend (navegador)

| Tecnología | Versión | Para qué se usa |
|---|---|---|
| Tailwind CSS | 4.x | Framework CSS utility-first, diseño responsivo |
| Vite | 7.x | Compilador y bundler del JS/CSS |
| Laravel Echo | — | Cliente JS para escuchar canales WebSocket |
| pusher-js | — | Librería de bajo nivel que usa Echo internamente |
| Axios | 1.x | Cliente HTTP para peticiones AJAX desde JS |

### Infraestructura

| Entorno | Servidor web | WebSocket | Cola |
|---|---|---|---|
| Desarrollo (local) | `php artisan serve` | Reverb en `localhost:8080` | `queue:work` + tabla `jobs` |
| Producción (Ionos) | Apache 2.4 + PHP-FPM | Pusher (cloud, `ws-eu.pusher.com`) | `QUEUE_CONNECTION=sync` |

---

## 3. Arquitectura MVC y flujo de una petición

Laravel sigue el patrón **Modelo-Vista-Controlador**. Cuando el navegador hace una petición HTTP (por ejemplo `GET /proyectos`), el flujo interno es:

```
Navegador
    │
    ▼
routes/web.php          ← identifica qué controlador gestiona la URL
    │
    ▼
Middleware              ← filtros previos: ¿está autenticado? ¿tiene acceso al área?
    │
    ▼
Controlador (app/Http/Controllers/)
    │  ← valida datos, llama a los modelos
    ▼
Modelo (app/Models/)   ← consulta MySQL via Eloquent ORM
    │
    ▼
Controlador            ← recibe los datos y los pasa a la vista
    │
    ▼
Vista Blade (resources/views/)
    │
    ▼
HTML final → Navegador
```

---

## 4. Estructura de directorios

```
CMVProyectoFinalLaravel/
├── app/
│   ├── Events/
│   │   └── MensajeEnviado.php          ← evento WebSocket del chat
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PageController.php      ← vistas informacionales (proyectos, gantt, etc.)
│   │   │   ├── cUsuario.php            ← auth: registro, login, perfil
│   │   │   ├── cProyecto.php           ← CRUD proyectos
│   │   │   ├── cTarea.php              ← CRUD tareas
│   │   │   ├── cTareaUsuario.php       ← asignaciones de tareas
│   │   │   ├── cInvitacion.php         ← sistema de invitaciones
│   │   │   ├── cArchivo.php            ← archivos del proyecto
│   │   │   ├── cForo.php               ← foro: hilos y mensajes
│   │   │   ├── cPredefinicion.php      ← personajes, ítems, diálogos
│   │   │   └── cNotaTarea.php          ← notas de tarea
│   │   └── Middleware/
│   │       └── VerificarAccesoTipo.php ← control de acceso por área
│   ├── Livewire/
│   │   └── ChatHilo.php                ← componente Livewire del chat en tiempo real
│   └── Models/
│       ├── Usuario.php, Proyecto.php, Tarea.php, Tipo.php, Estado.php
│       ├── Participacion.php, ProyectoAcceso.php, Invitacion.php
│       ├── Archivo.php, NotaTarea.php
│       ├── ForoHilo.php, ForoMensaje.php, ForoArchivo.php
│       └── Personaje.php, Item.php, Dialogo.php
├── config/
│   └── reverb.php                      ← configuración del servidor Reverb (desarrollo)
├── database/migrations/                ← esquema de la BD en código PHP versionado
├── public/build/                       ← assets compilados por Vite (CSS + JS)
├── resources/
│   ├── css/app.css                     ← estilos Tailwind
│   ├── js/
│   │   ├── app.js                      ← entrada JS principal
│   │   └── echo.js                     ← configuración del cliente WebSocket
│   └── views/
│       ├── livewire/chat-hilo.blade.php← vista del componente de chat
│       └── *.blade.php                 ← todas las vistas de la app
├── routes/
│   ├── web.php                         ← todas las rutas HTTP
│   └── channels.php                    ← autorización de canales WebSocket
└── .env                                ← variables de entorno (no se sube a Git)
```

---

## 5. Base de datos — Modelos y relaciones

### Tablas y migraciones

| Tabla | Descripción |
|---|---|
| `tipos` | Áreas de trabajo: Desarrollo, Diseño, Audio, Narrativa, Marketing, Arte |
| `estados` | Estados de tarea: Pendiente, En Proceso, Terminada |
| `usuarios` | Usuarios de la plataforma |
| `proyectos` | Proyectos creados por usuarios |
| `proyecto_usuario` | Tabla pivot: qué usuarios son miembros de qué proyectos |
| `proyecto_accesos` | Control de acceso granular por área (usuario + proyecto + tipo) |
| `tareas` | Tareas con tipo, estado, fechas, horas estimadas y flag de hito |
| `participaciones` | Tabla pivot: asignaciones de usuario a tarea con horas reales |
| `tarea_dependencias` | Autorreferencial: qué tareas dependen de otras |
| `invitaciones` | Códigos únicos de invitación con áreas configurables y expiración |
| `archivos` | Archivos adjuntos al proyecto |
| `archivo_tipo` | Tabla pivot: a qué áreas pertenece cada archivo |
| `foro_hilos` | Hilos de discusión del foro por proyecto |
| `foro_mensajes` | Mensajes dentro de un hilo |
| `foro_archivos` | Archivos adjuntos a mensajes del chat |
| `personajes` | Datos predefinidos: personajes del videojuego |
| `items` | Datos predefinidos: ítems del inventario |
| `dialogos` | Datos predefinidos: líneas de diálogo |

### Relaciones Eloquent principales

```
Usuario
  ├── hasMany → Proyecto (los que ha creado)
  ├── belongsToMany → Proyecto (via proyecto_usuario, membresía)
  └── belongsToMany → Tarea (via participaciones)

Proyecto
  ├── belongsTo → Usuario (creador)
  ├── hasMany → Tarea
  ├── hasMany → Invitacion
  ├── hasMany → Archivo
  ├── hasMany → ForoHilo
  ├── hasMany → Personaje, Item, Dialogo
  └── belongsToMany → Usuario (via proyecto_usuario)

Tarea
  ├── belongsTo → Proyecto, Tipo, Estado
  ├── belongsToMany → Usuario (via participaciones)
  └── belongsToMany → Tarea (via tarea_dependencias, autorreferencial)

ForoHilo → belongsTo Proyecto, Usuario
ForoMensaje → belongsTo ForoHilo, Usuario
ForoArchivo → belongsTo ForoMensaje, Usuario
Dialogo → belongsTo Proyecto, Personaje
```

---

## 6. Rutas — routes/web.php

### Rutas públicas (sin autenticación)

| Método | URL | Controlador | Función |
|---|---|---|---|
| GET | `/` | PageController@index | Página de inicio |
| GET | `/login` | cUsuario@showLogin | Formulario de login |
| POST | `/login` | cUsuario@login | Procesar login |
| POST | `/logout` | cUsuario@logout | Cerrar sesión |
| GET | `/registro` | cUsuario@showRegistro | Formulario de registro |
| POST | `/registro` | cUsuario@registro | Procesar registro |
| GET | `/join` | cInvitacion@join | Enlace de invitación |

### Rutas protegidas (requieren `auth`)

| Método | URL | Descripción |
|---|---|---|
| GET | `/proyectos` | Listado de proyectos del usuario |
| POST | `/proyectos` | Crear nuevo proyecto |
| GET | `/proyecto/{id}` | Dashboard del proyecto |
| PUT | `/proyectos/{id}` | Editar proyecto |
| DELETE | `/proyectos/{id}` | Eliminar proyecto |
| GET | `/proyecto/{id}/gantt` | Vista Gantt |
| GET | `/proyecto/{id}/calendario` | Vista Calendario |
| GET | `/proyecto/{id}/miembros` | Gestión de miembros |
| GET | `/proyecto/{id}/tipo/{tipo}` | Tareas por área (**middleware** `area.access`) |
| GET/POST/PUT/DELETE | `/tareas/...` | CRUD de tareas |
| POST | `/tarea/usuario/asignar` | Asignar usuario a tarea |
| POST | `/tarea/usuario/remover` | Desasignar usuario de tarea |
| GET | `/perfil` | Ver perfil |
| PUT | `/perfil` | Actualizar perfil |
| GET | `/proyecto/{id}/archivos` | Gestión de archivos del proyecto |
| POST | `/proyecto/{id}/archivos` | Subir archivo |
| DELETE | `/archivos/{id}` | Eliminar archivo |
| GET | `/archivos/{id}/download` | Descargar archivo |
| GET | `/archivos/{id}/preview` | Previsualizar archivo (devuelve JSON) |
| GET | `/proyecto/{id}/foro` | Listado de hilos del foro |
| POST | `/proyecto/{id}/foro` | Crear nuevo hilo |
| GET | `/proyecto/{id}/foro/{hilo}` | Ver hilo (incluye el chat Livewire) |
| DELETE | `/proyecto/{id}/foro/{hilo}` | Eliminar hilo |
| GET | `/proyecto/{id}/predefinicion` | Gestión de datos predefinidos |
| POST/PUT/DELETE | `/proyecto/{id}/predefinicion/personajes/...` | CRUD Personajes |
| POST/PUT/DELETE | `/proyecto/{id}/predefinicion/items/...` | CRUD Ítems |
| POST/PUT/DELETE | `/proyecto/{id}/predefinicion/dialogos/...` | CRUD Diálogos |

---

## 7. Sistema de autenticación

### Registro y login

El sistema usa una variación del hash estándar de Laravel: antes de hashear la contraseña, la **concatena con el username**. Esto significa que dos usuarios con la misma contraseña tienen hashes diferentes, añadiendo seguridad extra.

```php
// Al registrar:
$passwordConcatenada = $request->username . $request->password;
$usuario->password = Hash::make($passwordConcatenada);

// Al verificar login:
$passwordConcatenada = $request->username . $request->password;
Hash::check($passwordConcatenada, $user->password); // true o false
```

**Ejemplo:** si el username es `juandev` y la contraseña es `1234`, lo que se hashea es `juandev1234`.

### Protección de rutas

Las rutas protegidas usan el middleware `auth` de Laravel. Si el usuario no está autenticado, Laravel lo redirige automáticamente a `/login`.

---

## 8. Gestión de proyectos

### Funcionalidades
- Crear proyectos con nombre y descripción
- Ver en el dashboard la barra de progreso (tareas terminadas / total)
- Editar y eliminar proyectos propios
- Ver proyectos en los que se participa como miembro

### Dashboard del proyecto
Muestra acceso directo a cada área de trabajo (Desarrollo, Diseño, Audio, etc.) con estadísticas de tareas por área.

---

## 9. Gestión de tareas

### Datos de una tarea
- **Título** y descripción
- **Tipo/Área**: a qué departamento pertenece (Desarrollo, Diseño, etc.)
- **Estado**: Pendiente → En Proceso → Terminada
- **Fechas**: inicio y fin (se valida que `end_date >= start_date`)
- **Horas estimadas**: para el cálculo de progreso
- **Dependencias**: una tarea puede depender de otras (relación autorreferencial en `tarea_dependencias`)
- **Flag de hito**: las tareas marcadas como hito se muestran de forma especial en el Calendario

### Asignaciones (`participaciones`)
Un usuario puede ser asignado a una tarea. La tabla `participaciones` guarda además las `actual_hours` (horas reales trabajadas).

### Método `isBlocked()`
El modelo `Tarea` tiene un método que indica si está bloqueada por dependencias sin terminar:

```php
public function isBlocked(): bool {
    return $this->dependencias->contains(
        fn($t) => $t->status->name !== 'Terminada'
    );
}
```

---

## 10. Control de acceso por áreas

### Middleware `VerificarAccesoTipo` (`area.access`)

Se aplica en la ruta `/proyecto/{id}/tipo/{tipo}`. Funciona en tres pasos:

| Paso | Condición | Resultado |
|---|---|---|
| 1 | ¿El usuario es el creador del proyecto? | Sí → acceso total |
| 2 | ¿Tiene filas en `proyecto_accesos` para este proyecto? | No (cero filas) → acceso completo (modo legacy) |
| 3 | ¿Existe fila con (proyecto_id, user_id, tipo_id)? | Sí → acceso / No → `abort(403)` |

El **modo legacy** (paso 2) permite compatibilidad: si a un miembro nunca se le han configurado áreas, puede ver todo.

```php
// app/Http/Middleware/VerificarAccesoTipo.php
$proyecto = Proyecto::findOrFail($proyectoId);

if ($proyecto->created_by === $usuario->id) {
    return $next($request);  // owner: pasa siempre
}

$tieneAccesos = ProyectoAcceso::where('proyecto_id', $proyectoId)
    ->where('user_id', $usuario->id)->exists();

if (!$tieneAccesos) {
    return $next($request);  // sin configurar: pasa (legacy)
}

$tieneEsteAcceso = ProyectoAcceso::where('proyecto_id', $proyectoId)
    ->where('user_id', $usuario->id)
    ->where('tipo_id', $tipoId)->exists();

if (!$tieneEsteAcceso) {
    abort(403, 'Sin acceso a esta área.');
}
```

---

## 11. Sistema de invitaciones

El owner genera un código único en formato `PROY-XXXXXXXX`. El enlace tiene:
- Expiración automática a los 7 días (`expires_at`)
- Límite de usos (`max_uses`, `uses_count`)
- Áreas configurables (guardadas como JSON)

### Flujo de unión al proyecto

```
Owner genera invitación → POST /invitaciones/generar/{proyecto}
    Código: PROY-A3F7B2C1 | Expira: +7 días | Areas: [1, 3, 5]

GET /join?code=PROY-A3F7B2C1
    ¿Válida?
        SÍ → ¿Autenticado?
                NO → guarda código en sesión → redirige a /registro?code=...
                SÍ → unirAlProyecto()
                       → attach en proyecto_usuario
                       → ProyectoAcceso::firstOrCreate() por cada área
```

---

## 12. Sistema de archivos

Los archivos se guardan en `storage/app/public/proyectos/{proyectoId}/` y son accesibles en el navegador a través del enlace simbólico `public/storage`.

### Categorías automáticas

| Categoría | Extensiones | Comportamiento |
|---|---|---|
| `imagen` | jpg, jpeg, png, gif, webp, svg, bmp | Miniatura inline |
| `pdf` | pdf | Modal con iframe de previsualización |
| `audio` | mp3, wav, ogg, m4a, flac, aac | Modal con reproductor |
| `video` | mp4, mov, avi, webm, mkv | Modal con reproductor |
| `texto` | txt, doc, docx, csv, rtf, odt | Modal con primeros 5.000 caracteres |

La ruta `GET /archivos/{id}/preview` devuelve JSON con el contenido de texto para la previsualización (no renderiza HTML completo).

---

## 13. Diagrama de Gantt

Vista disponible en `/proyecto/{id}/gantt`. Muestra un diagrama de barras horizontal con:
- Una barra por tarea con su duración (fecha inicio → fecha fin)
- Líneas de dependencia entre tareas
- Marcado especial para hitos (`is_milestone = true`)

Generado puramente en el navegador con JavaScript a partir de los datos de las tareas del proyecto.

---

## 14. Vista Calendario

Vista disponible en `/proyecto/{id}/calendario`. Muestra un calendario mensual donde cada tarea aparece en su fecha de inicio y fin, con marcado visual diferenciado para las tareas hito.

---

## 15. Foro y chat en tiempo real

### 15.1 ¿Qué es un WebSocket?

El HTTP clásico es **unidireccional**: el navegador pregunta y el servidor responde. Para que un mensaje nuevo aparezca en pantalla de todos sin que nadie recargue la página, se necesita algo diferente.

Un **WebSocket** abre una **conexión bidireccional permanente**:

```
Navegador                         Servidor WebSocket
    |                                     |
    |  "Quiero conectarme"  ──────────►   |
    |  ◄──────────  "Conexión aceptada"   |
    |                                     |
    | ═══════════════════════════════════ |  ← canal abierto permanente
    |                                     |
    |  ◄─────────── "Nuevo mensaje de Ana" (el servidor avisa SIN que nadie pregunte)
    |  ◄─────────── "Ana está escribiendo"
    |  "Envío este texto" ─────────────►  |
```

Laravel implementa WebSockets con un modelo de **publicación/suscripción** (pub/sub):
- El navegador se **suscribe** a un canal (ej: `chat.42` para el hilo ID 42)
- El servidor **publica** eventos en ese canal (ej: `MensajeEnviado`)
- Todos los navegadores suscritos lo reciben al instante

### 15.2 Componentes del chat

| Componente | Tecnología | Función |
|---|---|---|
| Servidor WebSocket | Reverb (dev) / Pusher (prod) | Mantiene conexiones abiertas y distribuye eventos a los clientes |
| Cliente WebSocket | Laravel Echo + pusher-js | JS del navegador que se suscribe a canales y recibe eventos |
| Componente reactivo | Livewire (`ChatHilo.php`) | Escucha eventos WebSocket y re-renderiza el chat desde PHP |
| Evento de broadcasting | `MensajeEnviado.php` | Encapsula el mensaje nuevo y lo emite al canal privado |
| Autorización de canal | `routes/channels.php` | Verifica que el usuario tenga permiso para escuchar el canal |
| Inyección de config | `hilo.blade.php` | El servidor PHP inyecta la config de conexión correcta en la página |

### 15.3 Diferencia desarrollo vs producción

El chat es idéntico para el usuario final. Lo que cambia es **dónde vive el servidor WebSocket**:

#### En desarrollo (Reverb)

Reverb es un servidor WebSocket que corre como un proceso PHP en tu propia máquina.

```
.env desarrollo:
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database
REVERB_APP_KEY=...
REVERB_HOST=localhost
REVERB_PORT=8080
```

Necesita **dos procesos extra** corriendo:
- `php artisan reverb:start` → el servidor WebSocket en `ws://localhost:8080`
- `php artisan queue:work` → procesa los eventos de la cola antes de enviárselos a Reverb

La cola existe porque el evento `MensajeEnviado` implementa `ShouldBroadcast`, que encola el broadcasting para no bloquear la respuesta HTTP al emisor.

#### En producción (Pusher)

Pusher es un **servicio externo en la nube** (pusher.com). No hay que instalar ni arrancar nada en el servidor. Laravel simplemente llama a la API HTTP de Pusher con el SDK `pusher/pusher-php-server`, y Pusher se encarga de distribuirlo a los navegadores conectados.

```
.env producción:
BROADCAST_CONNECTION=pusher
QUEUE_CONNECTION=sync          ← sin cola: el evento se procesa al instante
PUSHER_APP_KEY=dff71be1c99842f92cc2
PUSHER_APP_CLUSTER=eu
PUSHER_APP_SECRET=...
PUSHER_APP_ID=...
```

Con `QUEUE_CONNECTION=sync` no hace falta `queue:work` porque los eventos se procesan de forma síncrona dentro del mismo proceso PHP.

#### Tabla comparativa

| Aspecto | Desarrollo | Producción |
|---|---|---|
| Servidor WebSocket | Reverb (`localhost:8080`) | Pusher (`ws-eu.pusher.com:443`) |
| Broadcaster de Echo | `reverb` | `pusher` + cluster `eu` |
| Cola de trabajos | `database` → necesita `queue:work` | `sync` → sin worker |
| Protocolo | `ws://` (sin TLS) | `wss://` (con TLS) |
| Procesos extra necesarios | `reverb:start` + `queue:work` | Ninguno |
| Compilación JS | `npm run dev` (Vite en vivo) | Assets ya compilados en `public/build/` |

### 15.4 Flujo completo de un mensaje

```
USUARIO A (emisor)                    SERVIDOR                    USUARIO B (receptor)

1. Pulsa Enter o botón Responder
   x-on:keydown.enter →
   $wire.guardarRespuesta()

2. Livewire envía AJAX
   POST /livewire/update ───────────►
                                    3. ChatHilo@guardarRespuesta()
                                       ├─ Valida contenido y archivos
                                       ├─ ForoMensaje::create(...)
                                       ├─ Guarda archivos en storage
                                       │   → ForoArchivo::create(...)
                                       └─ MensajeEnviado::dispatch($mensaje)

   DESARROLLO:                         ├─ Inserta job en tabla "jobs"
                                       │   queue:work lo procesa ~1s después
   PRODUCCIÓN:                         └─ Ejecuta broadcasting síncrono (sync)

                                    4. Livewire devuelve HTML ◄────────────────
                                       (el emisor ve su mensaje al instante)

                                    5. Broadcasting llega al servidor WebSocket
                                       (Reverb o Pusher)
                                       Canal: private-chat.{hiloId}
                                       Payload: { hilo_id, mensaje_id }

                                    6. Pusher/Reverb busca navegadores
                                       suscritos al canal
                                                                ─────────────────►
                                                                7. Echo recibe evento
                                                                   MensajeEnviado

                                                                8. Livewire detecta:
                                                                   #[On('echo-private:
                                                                   chat.{hiloId},
                                                                   .MensajeEnviado')]
                                                                   → refrescarMensajes()

                                                                9. Livewire hace AJAX
                                ◄───────────────────────────────── POST /livewire/update
                                    10. PHP recarga mensajes
                                        desde BD y devuelve HTML
                                        ─────────────────────────────────────────►

                                                                11. El mensaje aparece
                                                                    en pantalla sin
                                                                    recargar la página
```

### 15.5 Archivos clave del chat

#### `app/Events/MensajeEnviado.php`

El evento que dispara el broadcasting. Implementa `ShouldBroadcast` para que Laravel lo envíe al servidor WebSocket.

```php
class MensajeEnviado implements ShouldBroadcast
{
    public int $hiloId;
    public int $mensajeId;

    public function __construct(ForoMensaje $mensaje)
    {
        $this->hiloId    = $mensaje->hilo_id;
        $this->mensajeId = $mensaje->id;
    }

    // Canal privado exclusivo de cada hilo
    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->hiloId)];
    }

    // Nombre corto del evento (sin namespace de clase)
    public function broadcastAs(): string
    {
        return 'MensajeEnviado';
    }

    // Solo IDs: el componente recarga desde BD para evitar serializar relaciones
    public function broadcastWith(): array
    {
        return ['hilo_id' => $this->hiloId, 'mensaje_id' => $this->mensajeId];
    }
}
```

**Por qué solo se envían los IDs y no el mensaje completo:** si se serializara el mensaje entero (con autor, archivos, etc.), el payload podría ser muy grande y habría que gestionar la serialización de relaciones Eloquent. Es más limpio enviar solo el ID y que el receptor recargue desde BD, garantizando siempre datos frescos.

#### `app/Livewire/ChatHilo.php`

Componente Livewire con dos responsabilidades: enviar mensajes y escuchar eventos WebSocket.

```php
// Escucha el canal WebSocket. {hiloId} es reemplazado por $this->hiloId
#[On('echo-private:chat.{hiloId},.MensajeEnviado')]
public function refrescarMensajes(): void
{
    // El cuerpo está vacío a propósito.
    // Que este método se ejecute ya provoca que Livewire
    // re-renderice el componente, recargando $mensajes desde BD.
}
```

La sintaxis `echo-private:chat.{hiloId},.MensajeEnviado` se descompone así:
- `echo-private:` → indica que es un canal privado de Echo
- `chat.{hiloId}` → nombre del canal (Livewire interpola `{hiloId}` con `$this->hiloId`)
- `.MensajeEnviado` → el punto inicial significa nombre exacto del evento sin prefijo de namespace

#### `routes/channels.php`

Antes de que un navegador pueda suscribirse a un canal privado, el servidor verifica que tenga permiso. Esto es la **autenticación del canal**.

```php
Broadcast::channel('chat.{hiloId}', function ($user, $hiloId) {
    $hilo     = ForoHilo::find($hiloId);
    $proyecto = Proyecto::find($hilo->proyecto_id);

    // Solo miembros del proyecto pueden escuchar este canal
    return $proyecto->created_by === $user->id
        || $proyecto->miembros()->where('user_id', $user->id)->exists();
});
```

Cuando el navegador intenta suscribirse, Echo hace una petición POST a `/broadcasting/auth`. Laravel ejecuta esta función: si devuelve `true`, concede acceso; si devuelve `false`, rechaza.

#### `resources/js/echo.js`

Configura el cliente WebSocket del navegador. Lee `window.__BROADCAST_CONFIG__` (inyectado por el servidor PHP en cada carga de la página) para saber si conectarse a Reverb o a Pusher, sin tener nada hardcodeado en el JS compilado.

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const cfg      = window.__BROADCAST_CONFIG__ ?? {};
const provider = cfg.provider ?? import.meta.env.VITE_CHANNELS_PROVIDER ?? 'reverb';

if (provider === 'pusher') {
    // PRODUCCIÓN: conecta a Pusher en la nube
    window.Echo = new Echo({
        broadcaster:  'pusher',
        key:          cfg.key     ?? import.meta.env.VITE_PUSHER_APP_KEY,
        cluster:      cfg.cluster ?? import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS:     true,
        authEndpoint: (cfg.appBase ?? '') + '/broadcasting/auth',
    });
} else {
    // DESARROLLO: conecta a Reverb local
    window.Echo = new Echo({
        broadcaster:       'reverb',
        key:               cfg.key    ?? import.meta.env.VITE_REVERB_APP_KEY,
        wsHost:            cfg.host   ?? import.meta.env.VITE_REVERB_HOST,
        wsPort:            cfg.port   ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
        wssPort:           cfg.port   ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
        forceTLS:          (cfg.scheme ?? 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint:      (cfg.appBase ?? '') + '/broadcasting/auth',
    });
}
```

#### `resources/views/hilo.blade.php` (fragmento de configuración)

El Blade lee la config del servidor PHP (`.env` de producción) y la inyecta como objeto JavaScript antes de cargar el JS compilado. Así el JS nunca tiene valores hardcodeados:

```blade
@if(config('broadcasting.default') === 'pusher')
<script>
  window.__BROADCAST_CONFIG__ = {
    provider: 'pusher',
    key:      '{{ config("broadcasting.connections.pusher.key") }}',
    cluster:  '{{ config("broadcasting.connections.pusher.options.cluster") }}',
    appBase:  '{{ rtrim(parse_url(config("app.url"), PHP_URL_PATH) ?? "", "/") }}',
  };
</script>
@else
<script>
  window.__BROADCAST_CONFIG__ = {
    provider: 'reverb',
    key:      '{{ config("broadcasting.connections.reverb.key") }}',
    host:     '{{ config("broadcasting.connections.reverb.options.host") }}',
    port:      {{ config("broadcasting.connections.reverb.options.port", 443) }},
    scheme:   '{{ config("broadcasting.connections.reverb.options.scheme", "https") }}',
    appBase:  '{{ rtrim(parse_url(config("app.url"), PHP_URL_PATH) ?? "", "/") }}',
  };
</script>
@endif
```

**Por qué este diseño:** los assets JS se compilan una sola vez (`npm run build`). Si la config de Pusher/Reverb estuviera hardcodeada en el JS, habría que recompilar cada vez que cambia el entorno. Al inyectarla desde PHP, el mismo JS compilado funciona en desarrollo y producción sin tocar el bundle.

### 15.6 Cómo arrancar el sistema

#### En desarrollo (4 procesos en paralelo)

```bash
# Terminal 1 — Servidor web PHP
php artisan serve

# Terminal 2 — Servidor WebSocket Reverb
php artisan reverb:start

# Terminal 3 — Worker de cola (procesa los eventos de broadcasting)
php artisan queue:work

# Terminal 4 — Compilador frontend (solo si se modifican JS/CSS)
npm run dev
```

#### En producción

No hay procesos extra. El despliegue en Ionos/Plesk con:
```
BROADCAST_CONNECTION=pusher
QUEUE_CONNECTION=sync
```
hace que todo funcione dentro del proceso Apache normal. Pusher gestiona la distribución de eventos a los navegadores.

#### ¿Qué pasa si falta algún proceso (en desarrollo)?

| Falta | Consecuencia |
|---|---|
| `reverb:start` | Los mensajes se guardan en BD pero no llegan a otros navegadores en tiempo real |
| `queue:work` | Los jobs se acumulan en tabla `jobs` pero nunca se procesan; igual que el caso anterior |
| Ambos | El chat funciona como foro clásico: hay que recargar la página para ver mensajes nuevos |

---

## 16. Módulo de datos predefinidos

Disponible en `/proyecto/{id}/predefinicion`. Solo accesible para miembros del proyecto.

### Tipos de entidad

| Entidad | Campos |
|---|---|
| **Personaje** | `game_id` (ID único en el juego), nombre, vida, ataque, defensa, velocidad |
| **Ítem** | `game_id`, nombre, descripción, precio, tipo (Arma / Consumible / Misión) |
| **Diálogo** | `id_conversacion`, orden, personaje asociado, texto |

### Exportación a motores de juego

La exportación se realiza **en el navegador** (JavaScript puro), sin petición al servidor. El archivo descargado se llama `gamedata_{motor}_{proyecto_id}.json`.

| Motor | Formato | Detalle |
|---|---|---|
| **Unity** | Nomenclatura C# con prefijo `m_` | `m_Id`, `m_Nombre`, `m_Vida`... Los ítems se agrupan en `ItemList`. Compatible con `ScriptableObjects` y el JSON serializer de Unity |
| **Unreal Engine** | Array con clave `"Name"` por objeto | Compatible con la importación de `DataTables` en el editor UE |
| **Godot** | Diccionario anidado por `game_id` | Listo para `JSON.parse()` en GDScript |

---

## 17. Despliegue en producción (Ionos + Plesk)

### Entorno de producción

- **Proveedor:** Ionos (1&1), hosting compartido
- **Panel de control:** Plesk Obsidian con Laravel Toolkit
- **Servidor web:** Apache 2.4 con PHP 8.2 en modo PHP-FPM
- **Base de datos:** MySQL 8.0
- **Document Root:** apunta a `public/` del proyecto Laravel

### Proceso de despliegue manual (primera vez)

1. Crear la BD MySQL desde Plesk → Bases de datos → Añadir base de datos
2. En Laravel Toolkit → Instalar desde repositorio remoto → URL de GitHub
3. Editar `.env` de producción desde Plesk con las credenciales de la BD y las variables de Pusher
4. Ejecutar desde Plesk → Artisan: `php artisan migrate --force`
5. En Plesk → Despliegue → Desplegar

### Proceso de despliegue automático (actualizaciones)

Plesk ejecuta estos pasos en orden al pulsar "Desplegar":

1. Activa modo de mantenimiento
2. Recupera el código desde Git (`git pull`)
3. Ejecuta `composer install --no-dev`
4. Ejecuta `npm install` + `npm run build`
5. Desactiva modo de mantenimiento

### Variables de entorno de producción relevantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cristianmatveg.ieslossauces.es

BROADCAST_CONNECTION=pusher
QUEUE_CONNECTION=sync

PUSHER_APP_ID=2156133
PUSHER_APP_KEY=dff71be1c99842f92cc2
PUSHER_APP_SECRET=...
PUSHER_APP_CLUSTER=eu

VITE_CHANNELS_PROVIDER=pusher
VITE_PUSHER_APP_KEY=dff71be1c99842f92cc2
VITE_PUSHER_APP_CLUSTER=eu
```

---

## 18. Catálogo de requisitos funcionales

| ID | Nombre | Descripción |
|---|---|---|
| RF-01 | Autenticación | Registro, login, logout y edición de perfil |
| RF-02 | Gestión de proyectos | Crear, editar y eliminar proyectos propios. Ver proyectos ajenos en los que se participa |
| RF-03 | Gestión de tareas | Tareas con tipo, estado, fechas, horas estimadas y dependencias entre tareas |
| RF-04 | Asignaciones | Asignar usuarios a tareas y registrar horas trabajadas reales |
| RF-05 | Control de acceso por área | Restringir qué áreas ve cada miembro del proyecto (middleware `area.access`) |
| RF-06 | Sistema de invitaciones | Códigos únicos `PROY-XXXXXXXX` con expiración en 7 días y áreas configurables |
| RF-07 | Gestión de archivos | Subir, previsualizar y descargar archivos adjuntos al proyecto |
| RF-08 | Vista Gantt | Diagrama de barras horizontal con duraciones y dependencias entre tareas |
| RF-09 | Vista Calendario | Tareas distribuidas en un calendario mensual con marcado especial para hitos |
| RF-10 | Foro de discusión | Hilos de conversación por proyecto; el owner y el autor pueden fijar/eliminar hilos |
| RF-11 | Chat en tiempo real | Mensajes instantáneos sin recargar la página (Reverb en dev, Pusher en producción) |
| RF-12 | Archivos en el chat | Adjuntar archivos a mensajes con previsualización por categoría |
| RF-13 | Datos predefinidos | CRUD de Personajes, Ítems y Diálogos del videojuego por proyecto |
| RF-14 | Exportación a motores | JSON adaptado a Unity, Unreal Engine o Godot, generado en el navegador |

---

*Documentación generada a partir del código fuente real del proyecto y del PDF de documentación académica `PIXEL_Documentacion_Unificada_v6.pdf`. Refleja el estado actual del código incluyendo el soporte dual Reverb/Pusher añadido en mayo de 2026.*
