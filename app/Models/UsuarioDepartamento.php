<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioDepartamento extends Model
{
    use HasFactory;

    protected $table = 'usuario_departamento';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department_id',
        'project_id',
    ];
}
