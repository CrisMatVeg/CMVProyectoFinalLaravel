<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TareaUsuario extends Model
{
    use HasFactory;

    protected $table = 'tarea_usuario';

    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'user_id',
    ];
}
