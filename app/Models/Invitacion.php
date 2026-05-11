<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitacion extends Model
{
    protected $table = 'invitaciones';

    protected $fillable = [
        'proyecto_id', 'codigo', 'created_by', 'expires_at', 'max_uses', 'uses_count', 'areas',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'areas'      => 'array',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function scopeVigente($query)
    {
        return $query->where('expires_at', '>', now())
                     ->where(fn($q) => $q->whereNull('max_uses')
                                         ->orWhereColumn('uses_count', '<', 'max_uses'));
    }

    public static function generarCodigo(): string
    {
        do {
            $c = 'PROY-' . strtoupper(Str::random(8));
        } while (static::where('codigo', $c)->exists());

        return $c;
    }

    public function esValida(): bool
    {
        if ($this->expires_at->isPast()) return false;
        if (!is_null($this->max_uses) && $this->uses_count >= $this->max_uses) return false;
        return true;
    }
}
