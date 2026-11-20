<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'unidad',
        'stock',
        'estado'
    ];

    /**
     * Scope para consultar únicamente productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Verificar si el producto se encuentra activo
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }
}