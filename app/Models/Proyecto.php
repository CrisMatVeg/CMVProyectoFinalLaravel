<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    // Relación: un proyecto pertenece a un usuario
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'project_id');
    }

    public function usuarios()
    {
        return $this->hasManyThrough(
            Usuario::class,
            Departamento::class,
            'project_id', // FK en departamentos
            'id',         // FK en usuarios
            'id',         // local key proyecto
            'id'          // local key departamento
        );
    }

    protected static function booted()
    {
        static::created(function ($proyecto) {

            $departamentos = [
                'Desarrollo' => 'Programación y lógica del proyecto',
                'Diseño' => 'Diseño de jugabilidad y experiencia de usuario',
                'Audio' => 'Música y efectos de sonido',
                'Arte' => 'Arte conceptual y assets gráficos',
                'Narrativa' => 'Historia, guiones y diálogos',
                'Marketing' => 'Promoción y estrategia de comunicación',
            ];

            foreach ($departamentos as $nombre => $descripcion) {
                $proyecto->departamentos()->create([
                    'name' => $nombre,
                    'description' => $descripcion,
                ]);
            }
        });
    }
}
