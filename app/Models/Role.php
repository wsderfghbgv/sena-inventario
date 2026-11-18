<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public const ADMIN = 'admin';
    public const INSTRUCTOR = 'instructor';
    public const APRENDIZ = 'aprendiz';

    /**
     * Usuarios que pertenecen a este rol (1 a N).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    /**
     * Permisos asignados a este rol (N a M - RF-022).
     */
    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'rol_id', 'permiso_id');
    }

    /**
     * Comprueba si el rol posee un permiso específico (RF-022).
     */
    public function tienePermiso(string $permisoNombre): bool
    {
        // El administrador siempre tiene todos los permisos
        if ($this->nombre === self::ADMIN) {
            return true;
        }

        return $this->permisos->contains('nombre', $permisoNombre);
    }
}
