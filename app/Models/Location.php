<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente (Mass Assignment).
     * Esto por seguridad evita que se inyecten datos maliciosos en otras columnas.
     */
    protected $fillable = [
        'code', 
        'name', 
        'type', 
        'parent_id', 
        'is_active', 
        'grid_row', 
        'grid_column'
    ];

    /**
     * Obtiene la ubicación padre a la que pertenece esta ubicación.
     * Ejemplo: Si esto es un "Estante", retorna la "Zona" que lo contiene.
     * Relación: Muchos a Uno (Muchos estantes pertenecen a una zona).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Obtiene todas las sub-ubicaciones contenidas dentro de esta ubicación.
     * Ejemplo: Si esto es una "Zona", retorna todos los "Estantes" que tiene adentro.
     * Relación: Uno a Muchos (Una zona tiene muchos estantes).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }
}