# Documentación Completa — CMV Proyecto Final Laravel

## Tabla de Contenidos

1. [Descripción General del Proyecto](#1-descripción-general-del-proyecto)
2. [Entorno de Desarrollo y Herramientas](#2-entorno-de-desarrollo-y-herramientas)
3. [Instalación Paso a Paso](#3-instalación-paso-a-paso)
4. [Estructura del Proyecto](#4-estructura-del-proyecto)
5. [Base de Datos — Migraciones y Tablas](#5-base-de-datos--migraciones-y-tablas)
6. [Modelos y Relaciones](#6-modelos-y-relaciones)
7. [Controladores y Lógica de Negocio](#7-controladores-y-lógica-de-negocio)
8. [Rutas de la Aplicación](#8-rutas-de-la-aplicación)
9. [Vistas (Blade)](#9-vistas-blade)
10. [Middleware y Control de Acceso](#10-middleware-y-control-de-acceso)
11. [Sistema de Autenticación](#11-sistema-de-autenticación)
12. [Sistema de Invitaciones](#12-sistema-de-invitaciones)
13. [Sistema de Archivos](#13-sistema-de-archivos)
14. [Flujos de Usuario Paso a Paso](#14-flujos-de-usuario-paso-a-paso)
15. [Frontend — Tailwind CSS y Vite](#15-frontend--tailwind-css-y-vite)
16. [Dependencias del Proyecto](#16-dependencias-del-proyecto)
17. [Diagrama de la Base de Datos](#17-diagrama-de-la-base-de-datos)

---

## 1. Descripción General del Proyecto

**CMV Proyecto Final** es una aplicación web de **gestión de proyectos colaborativa** construida con el framework PHP **Laravel 12**. Permite a equipos de trabajo organizar proyectos, crear y asignar tareas, controlar el acceso por áreas, invitar miembros y gestionar archivos adjuntos.

### ¿Qué puede hacer la aplicación?

| Funcionalidad | Descripción |
|---|---|
| Autenticación | Registro, login, logout y edición de perfil |
| Proyectos | Crear, editar y eliminar proyectos propios |
| Tareas | Crear tareas con tipo, estado, fechas, horas estimadas y dependencias |
| Asignaciones | Asignar usuarios a tareas y registrar horas trabajadas |
| Control de acceso | Restringir qué áreas ve cada miembro del proyecto |
| Invitaciones | Generar enlaces para invitar personas a proyectos |
| Archivos | Subir, previsualizar y descargar archivos por proyecto |
| Visualizaciones | Vista Gantt, Calendario y listado de tareas por área |

### Roles en el sistema

- **Owner (Creador)**: Tiene control total sobre el proyecto. Puede agregar/quitar miembros, generar invitaciones, crear/editar/eliminar tareas de cualquier área, y subir/eliminar archivos.
- **Miembro**: Puede ver el proyecto y las tareas de las áreas a las que tiene acceso. Solo ve las tareas en las que está asignado (no todas las del área).

---

## 2. Entorno de Desarrollo y Herramientas

### ¿Qué es XAMPP?

XAMPP es un paquete que instala en Windows todo lo necesario para ejecutar aplicaciones PHP localmente:
- **Apache**: servidor web
- **MySQL/MariaDB**: base de datos (no se usa en este proyecto, se usa SQLite)
- **PHP**: lenguaje de programación

En este equipo XAMPP está instalado en el Escritorio, por lo que PHP se ejecuta desde una ruta absoluta como:
```
C:\Users\PracticasSoftware4\Desktop\xampp\php\php.exe
```

> **Nota importante**: PHP no está registrado en el PATH del sistema, por lo que todos los comandos `php` deben ejecutarse desde la terminal ubicada en la carpeta XAMPP o usando la ruta completa.

### Herramientas necesarias

| Herramienta | Versión | Para qué sirve |
|---|---|---|
| PHP | 8.2 o superior | Ejecutar Laravel |
| Composer | Última versión | Gestor de paquetes PHP |
| Node.js + npm | LTS | Compilar CSS/JS con Vite |
| SQLite | Incluido en PHP | Base de datos del proyecto |
| Git | Cualquiera | Control de versiones |

### ¿Qué es Composer?

Composer es el gestor de dependencias de PHP. Es como `npm` para JavaScript o `pip` para Python. Con él se instalan todas las librerías que necesita Laravel. Se usa con:
```bash
composer install       # instala dependencias del composer.json
composer require xxx   # agrega una nueva dependencia
```

### ¿Qué es Vite?

Vite es una herramienta que procesa y empaqueta los archivos CSS y JavaScript del frontend. En este proyecto se usa para compilar Tailwind CSS y los scripts de las vistas. Corre en modo desarrollo con `npm run dev` y genera archivos optimizados con `npm run build`.

---

## 3. Instalación Paso a Paso

### Paso 1 — Clonar o descargar el proyecto

```bash
git clone <url-del-repositorio>
cd CMVProyectoFinalLaravel
```

### Paso 2 — Instalar dependencias PHP

```bash
composer install
```

Esto lee `composer.json` y descarga todas las librerías de Laravel en la carpeta `vendor/`.

### Paso 3 — Crear el archivo de configuración

```bash
cp .env.example .env
```

El archivo `.env` contiene las variables de entorno (configuración local del proyecto). El `.env.example` es la plantilla pública que incluye el repositorio.

### Paso 4 — Generar la clave de la aplicación

```bash
php artisan key:generate
```

Laravel usa una clave secreta para encriptar sesiones, cookies y tokens CSRF. Este comando genera una y la escribe en el `.env` como `APP_KEY`.

### Paso 5 — Configurar la base de datos

Este proyecto usa **SQLite** por defecto (un archivo de base de datos local, sin necesidad de instalar MySQL). El archivo de configuración `.env` ya trae:

```env
DB_CONNECTION=sqlite
```

Laravel buscará la base de datos en `database/database.sqlite`. Si no existe, créala:

```bash
# En Windows con PowerShell o terminal bash
touch database/database.sqlite
# O simplemente crea el archivo vacío desde el explorador de archivos
```

### Paso 6 — Ejecutar las migraciones

```bash
php artisan migrate
```

Este comando crea todas las tablas en la base de datos leyendo los archivos de `database/migrations/`. También ejecuta el código seed dentro de las propias migraciones (por ejemplo, los tipos y estados se insertan automáticamente al migrar).

### Paso 7 — Configurar el almacenamiento de archivos

```bash
php artisan storage:link
```

Esto crea un enlace simbólico desde `public/storage` hacia `storage/app/public`, lo que permite acceder a los archivos subidos desde el navegador.

### Paso 8 — Instalar dependencias del frontend

```bash
npm install
```

Instala Tailwind CSS, Vite y el resto de librerías definidas en `package.json`.

### Paso 9 — Compilar el frontend

```bash
# Para desarrollo (con recarga automática):
npm run dev

# Para producción (una sola vez):
npm run build
```

### Paso 10 — Iniciar el servidor de desarrollo

```bash
php artisan serve
```

Esto inicia un servidor en `http://127.0.0.1:8000`. Puedes abrir esa URL en el navegador para ver la aplicación.

### Resumen del script automático `setup`

El `composer.json` incluye un script que ejecuta todos los pasos anteriores de una vez:

```bash
composer run setup
```

Lo que hace internamente:
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`

---

## 4. Estructura del Proyecto

```
CMVProyectoFinalLaravel/
│
├── app/                          ← Código principal de la aplicación
│   ├── Http/
│   │   ├── Controllers/          ← Controladores (lógica de cada sección)
│   │   └── Middleware/           ← Filtros de peticiones HTTP
│   ├── Models/                   ← Modelos Eloquent (representan tablas)
│   └── Providers/                ← Proveedores de servicios de Laravel
│
├── database/
│   ├── migrations/               ← Archivos que crean/modifican tablas
│   ├── factories/                ← Fábricas para generar datos de prueba
│   └── seeders/                  ← Seeders para poblar la BD
│
├── resources/
│   ├── views/                    ← Plantillas Blade (HTML de las páginas)
│   ├── css/                      ← Estilos CSS (procesados por Vite)
│   └── js/                       ← JavaScript (procesado por Vite)
│
├── routes/
│   └── web.php                   ← Todas las rutas de la aplicación
│
├── storage/
│   └── app/public/proyectos/     ← Archivos subidos por los usuarios
│
├── public/                       ← Carpeta pública accesible desde el navegador
│   └── storage → (enlace a storage/app/public)
│
├── .env                          ← Variables de entorno (NO se sube a git)
├── .env.example                  ← Plantilla de variables de entorno
├── composer.json                 ← Dependencias PHP
├── package.json                  ← Dependencias JavaScript/CSS
└── vite.config.js                ← Configuración del compilador frontend
```

---

## 5. Base de Datos — Migraciones y Tablas

### ¿Qué son las migraciones?

Las migraciones son archivos PHP que definen la estructura de la base de datos mediante código. En lugar de ejecutar SQL manualmente, se escriben migraciones que Laravel ejecuta en orden. Esto permite que cualquier persona clone el proyecto y tenga la misma base de datos exacta ejecutando `php artisan migrate`.

### Orden de creación de tablas

Las migraciones se ejecutan en orden cronológico (por nombre de archivo):

---

#### Tabla: `tipos`
**Migración:** `2026_01_20_000001_create_tipos_table.php`

Almacena los tipos o áreas de trabajo disponibles en el sistema. **No son modificables por el usuario**, son categorías fijas.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| name | string (unique) | Nombre del área |
| created_at / updated_at | timestamps | Fechas automáticas |

**Datos insertados automáticamente al migrar:**
1. Desarrollo
2. Diseño
3. Audio
4. Narrativa
5. Marketing
6. Arte

---

#### Tabla: `estados`
**Migración:** `2026_01_20_000002_create_estados_table.php`

Define los estados del ciclo de vida de las tareas. También son fijos.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| name | string (unique) | Nombre del estado |

**Datos insertados automáticamente:**
1. Pendiente
2. En Proceso
3. Terminada

---

#### Tabla: `usuarios`
**Migración:** `2026_01_21_000001_create_usuarios_table.php`

Los usuarios del sistema (no confundir con `users` que es el modelo por defecto de Laravel que no se usa).

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| username | string (unique) | Nombre de usuario único |
| description | string | Descripción o bio del usuario |
| tipo_id | FK → tipos (nullable) | Tipo/área principal del usuario |
| email | string (unique) | Correo electrónico |
| password | string | Contraseña hasheada |
| remember_token | string (nullable) | Token para "recordarme" |

---

#### Tabla: `proyectos`
**Migración:** `2026_01_21_000002_create_proyectos_table.php`

Contenedor principal de toda la información de un proyecto.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| name | string | Nombre del proyecto |
| description | text (nullable) | Descripción detallada |
| created_by | FK → usuarios | ID del creador (owner) |

---

#### Tabla: `tareas`
**Migración:** `2026_01_21_000005_create_tareas_table.php` + migraciones posteriores

Unidades de trabajo dentro de un proyecto.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| title | string | Título de la tarea |
| description | text (nullable) | Descripción detallada |
| project_id | FK → proyectos | A qué proyecto pertenece |
| type_id | FK → tipos | Área de la tarea |
| status_id | FK → estados | Estado actual |
| estimated_hours | decimal(8,2) | Horas estimadas de trabajo |
| start_date | date (nullable) | Fecha de inicio |
| end_date | date (nullable) | Fecha de fin |
| is_milestone | boolean | Es un hito del proyecto |

---

#### Tabla: `participaciones`
**Migración:** `2026_01_21_000006_create_participaciones_table.php`

Tabla intermedia (pivot con datos extra) entre usuarios y tareas. Registra quién trabaja en qué tarea y cuántas horas reales lleva.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| task_id | FK → tareas | Tarea asignada |
| user_id | FK → usuarios | Usuario asignado |
| proposed_at | timestamp (nullable) | Cuándo se propuso la asignación |
| accepted_at | timestamp (nullable) | Cuándo se aceptó |
| actual_hours | decimal(8,2) | Horas reales trabajadas |

---

#### Tabla: `proyecto_usuario`
**Migración:** `2026_04_23_000002_create_proyecto_usuario_table.php`

Pivot simple que registra qué usuarios son miembros de qué proyectos.

| Columna | Tipo | Descripción |
|---|---|---|
| proyecto_id | FK → proyectos | ID del proyecto |
| user_id | FK → usuarios | ID del miembro |

Restricción unique: un usuario no puede ser miembro dos veces del mismo proyecto.

---

#### Tabla: `invitaciones`
**Migración:** `2026_04_25_000001_create_invitaciones_table.php`

Registra los enlaces de invitación generados por los owners de proyectos.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| proyecto_id | FK → proyectos | A qué proyecto invita |
| codigo | string (unique) | Código único (ej: PROY-A3F7B2C1) |
| created_by | FK → usuarios | Quién generó la invitación |
| expires_at | timestamp | Cuándo expira |
| max_uses | smallint (nullable) | Máximo de usos (null = ilimitado) |
| uses_count | integer | Cuántas veces se usó |
| areas | JSON | Array de IDs de tipos accesibles |

---

#### Tabla: `proyecto_accesos`
**Migración:** `2026_04_25_000003_create_proyecto_accesos_table.php`

Control de acceso granular: define a qué áreas/tipos tiene acceso cada usuario en cada proyecto.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| proyecto_id | FK → proyectos | El proyecto |
| user_id | FK → usuarios | El usuario |
| tipo_id | FK → tipos | El área a la que tiene acceso |

Una fila = "este usuario puede ver el área X en el proyecto Y".

---

#### Tabla: `tarea_dependencias`
**Migración:** `2026_04_25_000005_add_milestone_and_deps_to_tareas.php`

Define qué tareas deben completarse antes de que otra pueda empezarse.

| Columna | Tipo | Descripción |
|---|---|---|
| task_id | FK → tareas | La tarea que depende |
| depends_on_id | FK → tareas | La tarea de la que depende |

---

#### Tabla: `archivos`
**Migración:** `2026_04_28_000001_create_archivos_table.php`

Metadatos de los archivos subidos por los usuarios.

| Columna | Tipo | Descripción |
|---|---|---|
| id | integer (PK) | Identificador único |
| proyecto_id | FK → proyectos | Proyecto al que pertenece |
| uploaded_by | FK → usuarios | Quién subió el archivo |
| name | string | Nombre legible del archivo |
| description | text (nullable) | Descripción opcional |
| original_name | string | Nombre original del archivo |
| path | string | Ruta en el storage (para accederlo) |
| mime_type | string | Tipo MIME (image/jpeg, audio/mp3, etc.) |
| size | bigint | Tamaño en bytes |
| categoria | string | texto / pdf / audio / imagen / video |

#### Tabla: `archivo_tipo`
Pivot entre archivos y tipos: un archivo puede estar asociado a múltiples áreas.

---

## 6. Modelos y Relaciones

Los modelos en Laravel son clases PHP que representan una tabla de la base de datos. Usan **Eloquent ORM**, que permite hacer consultas SQL usando métodos PHP en lugar de escribir SQL directamente.

### Modelo Usuario (`app/Models/Usuario.php`)

Representa a los usuarios del sistema. Extiende `Authenticatable` para poder usar el sistema de autenticación de Laravel.

**Relaciones:**
- `tipo()` → belongsTo Tipo — el área principal del usuario
- `proyectos()` → hasMany Proyecto — proyectos que el usuario creó
- `proyectosParticipando()` → belongsToMany Proyecto via `proyecto_usuario` — proyectos donde es miembro
- `tareas()` → belongsToMany Tarea via `participaciones` — tareas asignadas

**Método especial:**
```php
public function tiposAccesiblesEn(int $proyectoId): array
```
Retorna los IDs de tipos a los que tiene acceso el usuario en un proyecto dado. Se usa para el control de acceso.

---

### Modelo Proyecto (`app/Models/Proyecto.php`)

Agregado raíz del sistema. Todo gira alrededor del proyecto.

**Relaciones:**
- `creador()` → belongsTo Usuario — el owner
- `miembros()` → belongsToMany Usuario via `proyecto_usuario`
- `tareas()` → hasMany Tarea
- `accesos()` → hasMany ProyectoAcceso
- `archivos()` → hasMany Archivo

---

### Modelo Tarea (`app/Models/Tarea.php`)

Unidad de trabajo. Pertenece a un proyecto, tiene un tipo (área) y un estado.

**Relaciones:**
- `proyecto()` → belongsTo Proyecto
- `tipo()` → belongsTo Tipo
- `status()` → belongsTo Estado
- `usuarios()` → belongsToMany Usuario via `participaciones`
- `dependencias()` → belongsToMany Tarea via `tarea_dependencias` — tareas que deben completarse antes
- `dependientes()` → belongsToMany Tarea via `tarea_dependencias` — tareas que dependen de esta

**Método especial:**
```php
public function isBlocked(): bool
```
Recorre todas las dependencias de la tarea. Si alguna no tiene estado "Terminada", retorna `true`. Esto significa que la tarea está **bloqueada** y no debería avanzar hasta que sus dependencias terminen.

**Ejemplo práctico:** Si la tarea "Implementar login" depende de "Diseñar mockup de login", y el mockup aún está "En Proceso", entonces "Implementar login" está bloqueada.

---

### Modelo Participacion (`app/Models/Participacion.php`)

Pivot con datos adicionales entre usuarios y tareas. No solo registra quién está asignado a qué, sino cuándo se propuso, aceptó y cuántas horas trabajó.

---

### Modelo Invitacion (`app/Models/Invitacion.php`)

**Métodos especiales:**
```php
public function scopeVigente($query)
```
Un scope es un filtro reutilizable. `scopeVigente` filtra las invitaciones que no han expirado y que aún tienen usos disponibles. Se usa como `Invitacion::vigente()->first()`.

```php
public function esValida(): bool
```
Verifica si una invitación puede ser usada ahora mismo.

```php
public static function generarCodigo(): string
```
Genera un código único con el formato `PROY-XXXXXXXX` donde X son caracteres hexadecimales aleatorios en mayúsculas.

---

### Modelo Archivo (`app/Models/Archivo.php`)

**Método especial (accessor):**
```php
public function getSizeFormateadoAttribute(): string
```
Un accessor en Laravel es un método que formatea automáticamente un atributo al leerlo. `getSizeFormateadoAttribute` se accede como `$archivo->size_formateado` y retorna el tamaño legible: "1.2 MB", "450 KB", "820 B".

---

### Modelo ProyectoAcceso (`app/Models/ProyectoAcceso.php`)

Define qué áreas puede ver cada miembro. Una fila por cada combinación (proyecto, usuario, tipo). Si un usuario no tiene ninguna fila para un proyecto, se asume acceso completo (modo legacy).

---

## 7. Controladores y Lógica de Negocio

Los controladores reciben las peticiones HTTP, ejecutan la lógica necesaria y retornan una respuesta (vista, redirección o JSON).

### `PageController` — Vistas Principales

Controlador de páginas. No modifica datos, solo prepara información y devuelve vistas.

#### `proyectos()`
Obtiene los proyectos del usuario autenticado divididos en dos grupos:
1. Proyectos que el usuario **creó** (es owner)
2. Proyectos en los que **participa** como miembro (pero no es owner)

```php
$proyectosCreados = $usuario->proyectos()->latest()->get();
$proyectosParticipando = $usuario->proyectosParticipando()
    ->where('created_by', '!=', $usuario->id)
    ->latest()->get();
```

#### `proyecto($id)`
Dashboard del proyecto. Calcula:
- Total de miembros
- Total de tareas y cuántas están terminadas
- **Progreso**: `(terminadas / total) * 100`
- Tipos accesibles del usuario (para mostrar/ocultar secciones)

#### `gantt($id)`
Prepara un array JSON con todas las tareas del proyecto incluyendo sus dependencias, fechas y si están bloqueadas. Este JSON se pasa a la vista para que JavaScript lo renderice como diagrama Gantt.

```php
$tareasJson = $proyecto->tareas->map(function ($t) {
    return [
        'id'           => $t->id,
        'title'        => $t->title,
        'tipo'         => $t->tipo->name,
        'start'        => $t->start_date,
        'end'          => $t->end_date,
        'is_milestone' => $t->is_milestone,
        'depends_on'   => $t->dependencias->pluck('id')->toArray(),
        'blocked'      => $t->isBlocked(),
    ];
});
```

#### `tareasPorTipo($proyectoId, $tipoId)`
Vista de tareas filtrada por área. Tiene lógica de permisos:
- **Si es owner**: ve TODAS las tareas del área
- **Si es miembro**: solo ve las tareas donde está asignado

Además calcula estadísticas del área: total de tareas, horas estimadas y porcentaje de progreso.

---

### `cUsuario` — Autenticación y Perfil

#### `registro(Request)` — Proceso de registro
1. Valida los campos del formulario
2. Hashea la contraseña con la técnica especial (ver sección 11)
3. Crea el usuario en la BD
4. Inicia sesión automáticamente con `Auth::login($usuario)`
5. Si hay una invitación pendiente en la sesión, une al usuario al proyecto automáticamente

#### `login(Request)` — Proceso de login
1. Busca el usuario por `username`
2. Verifica la contraseña con la técnica especial
3. Si coincide, llama `Auth::login($user)` para iniciar sesión
4. Redirige a `/proyectos`

#### `actualizarPerfil(Request)` — Actualizar perfil
Permite cambiar username, email y descripción. Si el usuario quiere cambiar la contraseña, primero verifica la contraseña actual antes de actualizar.

---

### `cProyecto` — CRUD de Proyectos

#### `store(Request)` — Crear proyecto
1. Valida nombre y descripción
2. Crea el proyecto con `created_by = Auth::id()`
3. Agrega al creador como miembro en `proyecto_usuario`
4. Crea filas en `proyecto_accesos` para TODOS los tipos → el owner tiene acceso a todo

#### `update` y `destroy`
Solo el creador puede ejecutar estas acciones. Verificación manual:
```php
if ($proyecto->created_by !== Auth::id()) {
    return back()->with('error', '...');
}
```

---

### `cTarea` — CRUD de Tareas

#### `store(Request)` — Crear tarea
Proceso completo:
1. Valida todos los campos (incluyendo que `end_date >= start_date`)
2. Valida que los IDs de `depends_on` existan en la tabla `tareas`
3. Crea la tarea
4. Sincroniza dependencias con `syncWithoutDetaching()` o `sync()`

#### `update(Request, $id)` — Actualizar tarea
Igual que store pero modifica una tarea existente. Usa `sync([])` si no hay dependencias para limpiarlas todas.

---

### `cTareaUsuario` — Asignaciones

#### `assign(Request)` — Asignar usuario a tarea
1. Crea la entrada en `participaciones` con `syncWithoutDetaching` (no elimina otras asignaciones)
2. **Automáticamente** agrega al usuario como miembro del proyecto si no lo era

#### `remove(Request)` — Quitar usuario de tarea
Hace `detach` del usuario en la relación many-to-many con la tarea.

---

### `cInvitacion` — Sistema de Invitaciones

#### `generar(Request, Proyecto)` — Crear invitación
Solo el owner puede generar invitaciones. Proceso:
1. Valida que `areas` sea un array de IDs de tipos válidos
2. Crea la `Invitacion` con:
   - Código único generado por `Invitacion::generarCodigo()`
   - Expiración en 7 días (`now()->addDays(7)`)
   - Las áreas seleccionadas
3. Retorna la URL completa: `http://app.com/join?code=PROY-XXXXXXXX`

#### `join(Request)` — Procesar enlace de invitación
Cuando alguien accede a `/join?code=...`:
- Si **no está autenticado**: redirige al registro, guardando el código en sesión
- Si **está autenticado**: llama directamente a `unirAlProyecto()`

#### `unirAlProyecto(Usuario, Invitacion)` — Lógica central
1. Si el usuario no es miembro: `attach()` al proyecto
2. Incrementa `uses_count` de la invitación
3. Por cada área (tipo) en `$invitacion->areas`, crea un `ProyectoAcceso` con `firstOrCreate` (evita duplicados)
4. Redirige al proyecto con mensaje de bienvenida

---

### `cArchivo` — Gestión de Archivos

#### `store(Request, $proyectoId)` — Subir archivo
Proceso completo:
1. Valida: archivo requerido, máximo 51MB, descripción opcional, tipos opcionales
2. Obtiene la extensión del archivo: `strtolower($file->getClientOriginalExtension())`
3. Resuelve la categoría mediante `resolverCategoria()`:
   - txt, doc, docx, csv → `texto`
   - pdf → `pdf`
   - mp3, wav, ogg, m4a → `audio`
   - jpg, jpeg, png, gif, webp → `imagen`
   - mp4, mov, avi, webm → `video`
4. Almacena el archivo en `storage/app/public/proyectos/{proyectoId}/`
5. Crea el registro `Archivo` en la BD con todos los metadatos
6. Si se especificaron tipos, los sincroniza con `$archivo->tipos()->sync($tipo_ids)`

#### `preview($id)` — Previsualización de texto
Solo funciona con archivos de categoría `texto`. Devuelve los primeros 5000 caracteres como JSON, e indica si el archivo fue truncado. El navegador recibe este JSON y lo muestra en un modal sin necesidad de descargar el archivo.

#### `download($id)` — Descargar archivo
Usa `Storage::disk('public')->download($archivo->path, $archivo->original_name)` para servir el archivo con el nombre original (no el nombre generado al subirse).

---

## 8. Rutas de la Aplicación

Las rutas están definidas en `routes/web.php`. Se dividen en rutas públicas (sin autenticación) y rutas protegidas (requieren login).

### Rutas Públicas

| Método | URL | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/` | PageController@index | `home` | Página de inicio |
| GET | `/login` | cUsuario@showLogin | `login` | Formulario de login |
| POST | `/login` | cUsuario@login | `login.post` | Procesar login |
| POST | `/logout` | cUsuario@logout | `logout` | Cerrar sesión |
| GET | `/registro` | cUsuario@showRegistro | `registro` | Formulario de registro |
| POST | `/registro` | cUsuario@registro | `registro.post` | Procesar registro |
| GET | `/join` | cInvitacion@join | `invitacion.join` | Procesar enlace de invitación |

### Rutas Protegidas (requieren `auth` middleware)

| Método | URL | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/proyectos` | PageController@proyectos | `proyectos` | Lista de proyectos |
| POST | `/proyectos` | cProyecto@store | `proyectos.store` | Crear proyecto |
| GET | `/proyecto/{id}` | PageController@proyecto | `proyecto` | Dashboard del proyecto |
| GET | `/proyecto/{id}/gantt` | PageController@gantt | `proyecto.gantt` | Vista Gantt |
| GET | `/proyecto/{id}/calendario` | PageController@calendario | `proyecto.calendario` | Vista Calendario |
| GET | `/proyecto/{id}/miembros` | PageController@miembros | `proyecto.miembros` | Gestión de miembros |
| GET | `/proyecto/{id}/tipo/{tipoId}` | PageController@tareasPorTipo | `proyecto.tipo` | Tareas por área |
| PUT | `/proyectos/{id}` | cProyecto@update | `proyectos.update` | Actualizar proyecto |
| DELETE | `/proyectos/{id}` | cProyecto@destroy | `proyectos.destroy` | Eliminar proyecto |
| POST | `/tareas` | cTarea@store | `tareas.store` | Crear tarea |
| PUT | `/tareas/{id}` | cTarea@update | `tareas.update` | Actualizar tarea |
| DELETE | `/tareas/{id}` | cTarea@destroy | `tareas.destroy` | Eliminar tarea |
| POST | `/tarea/usuario/asignar` | cTareaUsuario@assign | `tarea.usuario.assign` | Asignar usuario |
| POST | `/tarea/usuario/remover` | cTareaUsuario@remove | `tarea.usuario.remove` | Quitar usuario |
| POST | `/invitaciones/generar/{id}` | cInvitacion@generar | `invitacion.generar` | Generar invitación |
| POST | `/invitaciones/unirse` | cInvitacion@unirse | `invitacion.unirse` | Unirse con código |
| GET | `/perfil` | cUsuario@showPerfil | `perfil` | Ver perfil |
| PUT | `/perfil` | cUsuario@actualizarPerfil | `perfil.update` | Actualizar perfil |
| GET | `/proyecto/{id}/archivos` | cArchivo@index | `proyecto.archivos` | Ver archivos |
| POST | `/proyecto/{id}/archivos` | cArchivo@store | `proyecto.archivos.store` | Subir archivo |
| DELETE | `/archivos/{id}` | cArchivo@destroy | `archivo.destroy` | Eliminar archivo |
| GET | `/archivos/{id}/download` | cArchivo@download | `archivo.download` | Descargar archivo |
| GET | `/archivos/{id}/preview` | cArchivo@preview | `archivo.preview` | Previsualizar (JSON) |

> La ruta `/proyecto/{id}/tipo/{tipoId}` tiene además el middleware `area.access` que verifica permisos antes de llegar al controlador.

---

## 9. Vistas (Blade)

Blade es el sistema de plantillas de Laravel. Los archivos `.blade.php` son HTML con directivas especiales como `@if`, `@foreach`, `@extends`, etc.

### `index.blade.php` — Página de inicio
Página pública con acceso a login y registro.

### `login.blade.php` — Login
Formulario con campos `username` y `password`. Si hay errores de validación, Blade los muestra con `@error('campo')`.

### `registro.blade.php` — Registro
Formulario de registro. Si se accede con `?code=...` (invitación), muestra el nombre del proyecto al que se unirá el usuario.

### `proyectos.blade.php` — Listado de proyectos
Muestra dos secciones: "Mis proyectos" (creados) y "Proyectos en los que participo". Incluye formulario para crear nuevo proyecto.

### `proyecto.blade.php` — Dashboard del proyecto
Vista principal de un proyecto. Muestra:
- Nombre, descripción, número de miembros
- Barra de progreso (% de tareas terminadas)
- Listado de áreas/tipos con acceso (solo las que el usuario puede ver)
- Acceso rápido a Gantt, Calendario, Miembros, Archivos
- Si es owner: formulario de edición del proyecto y botón de eliminación

### `tareas.blade.php` — Tareas por área
Vista para gestionar las tareas de un área específica. Incluye:
- Estadísticas: total de tareas, total de horas, porcentaje de progreso
- Formulario de nueva tarea (solo visible para owner o si tiene acceso)
- Listado de tareas con estado, dependencias, usuarios asignados
- Indicador de "Tarea bloqueada" si tiene dependencias sin terminar
- Formulario de asignación de usuarios

### `gantt.blade.php` — Diagrama Gantt
Recibe el JSON `$tareasJson` generado por el controlador y lo renderiza como un diagrama de barras horizontal con líneas de dependencia entre tareas. Las tareas bloqueadas se muestran con estilo diferente.

### `calendario.blade.php` — Vista Calendario
Muestra las tareas distribuidas en un calendario mensual según sus fechas de inicio y fin. Los hitos (`is_milestone = true`) tienen marcado especial.

### `miembros.blade.php` — Gestión de miembros (solo owner)
Lista todos los miembros del proyecto (excepto el owner) con:
- Sus tareas asignadas
- Los accesos de área (tipos) que tienen
- Opción de gestionar permisos

### `archivos.blade.php` — Gestión de archivos
Interfaz para:
- Subir archivos con drag-and-drop
- Ver listado con nombre, categoría, tamaño y quién lo subió
- Filtrar por categoría (texto, imagen, audio, etc.)
- Previsualizar archivos de texto directamente en un modal
- Descargar cualquier archivo
- Eliminar (solo owner o quien lo subió)

### `perfil.blade.php` — Perfil de usuario
Formulario para actualizar username, email, descripción y contraseña.

---

## 10. Middleware y Control de Acceso

### ¿Qué es un middleware?

Un middleware es un "filtro" que se ejecuta antes de que la petición llegue al controlador. Si el middleware rechaza la petición, el controlador nunca se ejecuta. Se usan para verificar autenticación, permisos, etc.

### Middleware `auth` (incluido en Laravel)

Se aplica a todas las rutas protegidas con `Route::middleware('auth')->group(...)`. Si el usuario no está autenticado, lo redirige a `/login`.

### Middleware `VerificarAccesoTipo` (personalizado)

**Archivo:** `app/Http/Middleware/VerificarAccesoTipo.php`
**Nombre:** `area.access`
**Se aplica a:** Ruta `/proyecto/{proyecto}/tipo/{tipo}`

**Lógica paso a paso:**

```
1. Obtener proyectoId y tipoId de la URL
2. ¿Es el usuario el owner del proyecto?
   → SÍ: tiene acceso total, continúa normalmente
3. ¿Tiene el usuario filas en proyecto_accesos para este proyecto?
   → NO (cero filas): acceso legacy completo, continúa normalmente
4. ¿Existe una fila con (proyecto_id, user_id, tipo_id)?
   → SÍ: tiene acceso, continúa
   → NO: abort(403) — acceso denegado
```

**¿Por qué el "acceso legacy"?** Cuando se crearon los primeros usuarios del sistema, antes de que existiera `proyecto_accesos`, no tienen filas en esa tabla. Para no romper su experiencia, si no hay filas se asume acceso completo.

---

## 11. Sistema de Autenticación

### ¿Cómo funciona el hash de contraseña?

Laravel normalmente hashea solo la contraseña. Este proyecto usa una variación: **concatena el username con la contraseña antes de hashear**.

```php
// Al registrar:
$passwordConcatenada = $request->username . $request->password;
$usuario->password = Hash::make($passwordConcatenada);

// Al verificar login:
$passwordConcatenada = $request->username . $request->password;
Hash::check($passwordConcatenada, $user->password) // true/false
```

**Ejemplo:** Si username es `juandev` y password es `1234`, lo que se hashea es `juandev1234`.

**¿Por qué?** Agrega una capa extra de personalización. Si dos usuarios tuvieran la misma contraseña `1234`, sus hashes serían distintos porque los usernames difieren.

### Sesiones y `Auth`

Laravel usa la facade `Auth` para manejar sesiones:
- `Auth::login($usuario)` — inicia sesión
- `Auth::user()` — retorna el usuario autenticado actual
- `Auth::id()` — retorna el ID del usuario autenticado
- `Auth::logout()` — cierra sesión

Las sesiones se almacenan en la base de datos (tabla `sessions`, configurada con `SESSION_DRIVER=database` en `.env`).

### Protección CSRF

Todos los formularios en Blade incluyen `@csrf`. Esto genera un token oculto que Laravel verifica al recibir la petición POST. Si el token no coincide, la petición es rechazada (protección contra ataques Cross-Site Request Forgery).

---

## 12. Sistema de Invitaciones

### Flujo completo de una invitación

```
OWNER genera invitación
        │
        ▼
POST /invitaciones/generar/{proyecto}
        │
        ▼
Se crea Invitacion con:
  - código: PROY-A3F7B2C1
  - expires_at: ahora + 7 días
  - areas: [1, 3, 5]  (IDs de tipos)
  - uses_count: 0
        │
        ▼
Se retorna URL: http://localhost:8000/join?code=PROY-A3F7B2C1
        │
        │ (alguien recibe el enlace)
        ▼
GET /join?code=PROY-A3F7B2C1
        │
        ├── Usuario no autenticado →  GET /registro?code=PROY-A3F7B2C1
        │                                     │
        │                                     ▼
        │                              Se guarda el código en sesión
        │                              Usuario completa registro
        │                              Al crear la cuenta → se une automáticamente
        │
        └── Usuario autenticado → unirAlProyecto()
                                          │
                                          ▼
                              attach en proyecto_usuario
                              increment uses_count
                              ProyectoAcceso::firstOrCreate()
                              por cada área en invitación
                              redirect → proyecto con mensaje
```

### Validación de invitación

Antes de unir al usuario, se verifica que la invitación sea válida con `esValida()`:
- No haya expirado (`expires_at > ahora`)
- No haya alcanzado el máximo de usos (`uses_count < max_uses` o `max_uses` es null)

---

## 13. Sistema de Archivos

### ¿Cómo se almacenan los archivos?

Laravel usa el concepto de "discos" (disks). Este proyecto usa el disco `public` que almacena archivos en `storage/app/public/`. Estos archivos son accesibles desde el navegador gracias al enlace simbólico creado con `php artisan storage:link`.

### Ruta de almacenamiento

```
storage/app/public/proyectos/{proyectoId}/{nombre_generado}
```

El nombre del archivo en disco es generado automáticamente por Laravel (para evitar colisiones). El nombre original se guarda en la columna `original_name` de la base de datos.

### Categorías de archivo

| Categoría | Extensiones | Función |
|---|---|---|
| texto | txt, doc, docx, csv, rtf, odt | Previsualización de contenido |
| pdf | pdf | Indicador visual PDF |
| audio | mp3, wav, ogg, m4a, flac, aac | Indicador visual Audio |
| imagen | jpg, jpeg, png, gif, webp, svg, bmp | Preview de imagen |
| video | mp4, mov, avi, webm, mkv | Indicador visual Video |

### Previsualización de texto

Solo los archivos de categoría `texto` pueden previsualizarse. El proceso:
1. La vista hace un fetch a `/archivos/{id}/preview`
2. El controlador lee el archivo con `Storage::disk('public')->get($archivo->path)`
3. Retorna los primeros 5000 caracteres como JSON
4. JavaScript muestra el contenido en un modal sin descargar el archivo completo

### Seguridad en eliminación

Solo pueden eliminar un archivo:
- El **owner del proyecto** (puede eliminar cualquier archivo)
- El **usuario que lo subió** (solo puede eliminar los suyos)

---

## 14. Flujos de Usuario Paso a Paso

### Flujo 1: Primera vez usando la aplicación

1. Ir a `/` → ver página de inicio
2. Clic en "Registrarse" → completar formulario
3. Al registrarse → login automático → redirige a `/proyectos`
4. Clic en "Nuevo Proyecto" → completar nombre y descripción
5. Proyecto creado → aparece en la lista

### Flujo 2: Trabajar con tareas

1. Desde `/proyectos` → clic en el proyecto
2. Ver el dashboard con áreas disponibles
3. Clic en un área (ej: "Desarrollo") → ir a `/proyecto/{id}/tipo/1`
4. Clic en "Nueva Tarea" → completar formulario:
   - Título y descripción
   - Estado inicial (Pendiente)
   - Horas estimadas
   - Fechas inicio/fin
   - ¿Es hito? (checkbox)
   - Dependencias de otras tareas (selector)
5. Tarea creada → aparece en la lista
6. Asignar usuario: seleccionar miembro del proyecto → "Asignar"

### Flujo 3: Ver el progreso (Gantt)

1. Desde el dashboard → clic en "Gantt"
2. Ver el diagrama con todas las tareas en orden temporal
3. Las barras muestran duración, las líneas muestran dependencias
4. Las tareas bloqueadas (dependencias no terminadas) aparecen resaltadas

### Flujo 4: Invitar un colaborador

1. Owner va a `/proyecto/{id}/miembros`
2. Sección "Generar invitación"
3. Seleccionar qué áreas verá el invitado
4. Clic en "Generar" → obtener URL
5. Compartir la URL con el colaborador
6. El colaborador accede al link → se registra o hace login → queda unido automáticamente

### Flujo 5: Subir un archivo

1. Desde el dashboard → clic en "Archivos"
2. Arrastrar archivo a la zona de drop o clic en "Seleccionar"
3. Completar nombre descriptivo y descripción (opcional)
4. Seleccionar tipos/áreas relacionados (opcional)
5. Clic en "Subir" → archivo aparece en la lista
6. Para previsualizar texto: clic en "Previsualizar"
7. Para descargar: clic en "Descargar"

---

## 15. Frontend — Tailwind CSS y Vite

### Tailwind CSS

Tailwind es un framework CSS de tipo "utility-first". En lugar de escribir CSS personalizado, se usan clases predefinidas directamente en el HTML:

```html
<!-- Sin Tailwind: -->
<div class="card">...</div>
<!-- CSS: .card { background: white; padding: 1rem; border-radius: 0.5rem; } -->

<!-- Con Tailwind: -->
<div class="bg-white p-4 rounded-lg shadow">...</div>
```

Las vistas del proyecto usan extensivamente Tailwind para diseño responsivo, colores, espaciados y componentes.

### Vite

Vite es el bundler (empaquetador) que procesa los archivos CSS y JS:

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({ input: ['resources/css/app.css', 'resources/js/app.js'] }),
        tailwindcss(),
    ],
});
```

Las vistas incluyen los assets procesados con la directiva `@vite`:
```html
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

En desarrollo (`npm run dev`), Vite sirve los archivos con Hot Module Replacement (recarga automática al editar CSS/JS). En producción (`npm run build`), genera archivos minificados en `public/build/`.

---

## 16. Dependencias del Proyecto

### PHP (composer.json)

| Paquete | Para qué sirve |
|---|---|
| `laravel/framework ^12.0` | El framework completo (routing, ORM, auth, etc.) |
| `laravel/tinker ^2.10` | REPL interactivo para ejecutar código Laravel desde terminal |

**Dependencias de desarrollo:**
| Paquete | Para qué sirve |
|---|---|
| `fakerphp/faker` | Generar datos falsos para pruebas |
| `laravel/pail` | Ver logs en tiempo real en terminal |
| `laravel/pint` | Formateador de código PHP |
| `laravel/sail` | Entorno Docker para desarrollo |
| `phpunit/phpunit` | Framework de tests |

### JavaScript/CSS (package.json)

| Paquete | Para qué sirve |
|---|---|
| `tailwindcss ^4.0` | Framework CSS |
| `@tailwindcss/vite ^4.0` | Plugin de Tailwind para Vite |
| `vite ^7.0` | Bundler/compilador frontend |
| `laravel-vite-plugin ^2.0` | Integración entre Laravel y Vite |
| `axios ^1.11` | Cliente HTTP para hacer peticiones desde JavaScript |
| `concurrently ^9.0` | Ejecutar múltiples procesos npm en paralelo |

---

## 17. Diagrama de la Base de Datos

```
┌─────────────┐       ┌──────────────────┐       ┌───────────────┐
│   usuarios  │──────▶│    proyectos     │◀──────│   tipos       │
│─────────────│ 1   N │──────────────────│ N   N │───────────────│
│ id          │       │ id               │       │ id            │
│ username    │       │ name             │       │ name          │
│ email       │       │ description      │       │ (Desarrollo,  │
│ password    │       │ created_by ──────┘       │  Diseño,      │
│ description │       └──────────────────┘       │  Audio, ...)  │
│ tipo_id ────┘                │                 └───────────────┘
└─────────────┘                │ 1:N                     │
       │                       ▼                         │
       │              ┌──────────────────┐               │
       │              │      tareas      │               │
       │              │──────────────────│               │
       │              │ id               │               │
       │              │ title            │               │
       │              │ project_id       │               │
       │              │ type_id ─────────┼───────────────┘
       │              │ status_id ───────┼──────────────────┐
       │              │ estimated_hours  │                  │
       │              │ start_date       │         ┌────────┴─────┐
       │              │ end_date         │         │   estados    │
       │              │ is_milestone     │         │──────────────│
       │              └──────────────────┘         │ id           │
       │                    │    │                 │ name         │
       │                    │    │ M:N (tarea_dep) │ (Pendiente,  │
       │                    │    └──────────────┐  │  En Proceso, │
       │                    │                   │  │  Terminada)  │
       │                    │ M:N               │  └──────────────┘
       │            ┌───────┴────────┐          │
       │            │ participaciones│          │
       │            │────────────────│◀─────────┘
       │            │ task_id        │  self-ref
       │            │ user_id        │
       │            │ proposed_at    │
       │            │ accepted_at    │
       │            │ actual_hours   │
       │            └────────────────┘
       │                   │
       │ M:N               │
┌──────┴─────────────┐     │
│  proyecto_usuario  │     │
│────────────────────│     │
│ proyecto_id        │     │
│ user_id            │     │
└────────────────────┘     │
                           │
┌──────────────────────┐   │
│  proyecto_accesos    │   │
│──────────────────────│   │
│ proyecto_id          │   │
│ user_id ─────────────┼───┘
│ tipo_id              │
└──────────────────────┘

┌──────────────────────┐
│    invitaciones      │
│──────────────────────│
│ id                   │
│ proyecto_id          │
│ codigo               │
│ created_by           │
│ expires_at           │
│ max_uses             │
│ uses_count           │
│ areas (JSON)         │
└──────────────────────┘

┌──────────────────────┐
│      archivos        │
│──────────────────────│
│ id                   │
│ proyecto_id          │
│ uploaded_by          │
│ name                 │
│ original_name        │
│ path                 │
│ mime_type            │
│ size                 │
│ categoria            │
└──────────────────────┘
         │ M:N
┌────────┴─────────────┐
│    archivo_tipo      │
│ archivo_id           │
│ tipo_id              │
└──────────────────────┘
```

---

*Documentación generada para CMVProyectoFinalLaravel — Laravel 12 / PHP 8.2 / SQLite / Tailwind CSS 4 / Vite 7*
