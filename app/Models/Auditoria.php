<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    // No se usa updated_at porque la auditoría es estrictamente inmutable
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'usuario_nombre',
        'accion',
        'modulo',
        'descripcion',
        'datos_cambio',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'datos_cambio' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Garantizar la inmutabilidad histórica: Impedir actualizaciones y eliminaciones (RF-023).
     */
    protected static function booted(): void
    {
        static::updating(function () {
            throw new \Exception('Los registros de auditoría son inmutables y no pueden modificarse.');
        });

        static::deleting(function () {
            throw new \Exception('Los registros de auditoría no pueden eliminarse del sistema.');
        });
    }

    /**
     * Usuario que ejecutó la acción.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Registrar una acción crítica en el log de auditoría (RF-023).
     */
    public static function registrar(
        string $accion,
        string $modulo,
        string $descripcion,
        ?array $datosCambio = null
    ): self {
        $user = Auth::user();

        return static::create([
            'user_id' => $user?->id,
            'usuario_nombre' => $user?->name ?? 'Sistema / Anónimo',
            'accion' => strtoupper($accion),
            'modulo' => $modulo,
            'descripcion' => $descripcion,
            'datos_cambio' => $datosCambio,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            'created_at' => now(),
        ]);
    }
}
