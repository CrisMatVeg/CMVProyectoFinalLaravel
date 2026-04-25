<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $fillable = [
        'name',
    ];

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'status_id');
    }
}
