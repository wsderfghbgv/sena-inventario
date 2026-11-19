<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entrada extends Model
{
    protected $table = 'entradas';

    protected $fillable = [
        'numero',
        'fecha',
        'hora',
        'usuario_id',
        'ubicacion_id',
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'ubicacion_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEntrada::class, 'entrada_id');
    }
}
