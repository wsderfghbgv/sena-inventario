<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'rol_id', 'estado'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_INACTIVO = 'inactivo';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con el Rol del usuario (N a 1).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    /**
     * Obtener el nombre del rol (accesible como $user->rol_nombre o $user->rol).
     */
    public function getRolNombreAttribute(): string
    {
        return $this->role?->nombre ?? 'aprendiz';
    }

    // Para compatibilidad hacia atrás con $user->rol
    public function getRolAttribute(): string
    {
        return $this->rol_nombre;
    }

    public function esAdmin(): bool
    {
        return $this->rol_nombre === Role::ADMIN;
    }

    public function esInstructor(): bool
    {
        return $this->rol_nombre === Role::INSTRUCTOR;
    }

    public function esAprendiz(): bool
    {
        return $this->rol_nombre === Role::APRENDIZ;
    }

    public function estaActivo(): bool
    {
        return $this->estado === self::ESTADO_ACTIVO;
    }

    /**
     * Comprueba si el usuario tiene alguno de los roles indicados.
     */
    public function tieneRol(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : explode(',', $roles);
        $roles = array_map('trim', $roles);
        return in_array($this->rol_nombre, $roles, true);
    }
}
