<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'rol', 'estado'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_ADMIN = 'admin';
    public const ROL_INSTRUCTOR = 'instructor';
    public const ROL_APRENDIZ = 'aprendiz';

    public const ROLES = [
        self::ROL_ADMIN => 'Administrador',
        self::ROL_INSTRUCTOR => 'Instructor',
        self::ROL_APRENDIZ => 'Aprendiz',
    ];

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

    public function esAdmin(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function esInstructor(): bool
    {
        return $this->rol === self::ROL_INSTRUCTOR;
    }

    public function esAprendiz(): bool
    {
        return $this->rol === self::ROL_APRENDIZ;
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
        $roles = is_array($roles) ? $roles : explode('|', $roles);
        return in_array($this->rol, $roles, true);
    }
}
