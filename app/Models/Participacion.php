<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participacion extends Model
{
    use HasFactory;

    protected $table = 'participaciones';

    protected $fillable = [
        'task_id',
        'user_id',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'task_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
