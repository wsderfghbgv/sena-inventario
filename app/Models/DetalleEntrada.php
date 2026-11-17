<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleEntrada extends Model
{
    protected $table = 'detalle_entradas';

    protected $fillable = [
        'entrada_id',
        'producto_id',
        'cantidad',
    ];

    public function entrada(): BelongsTo
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(productos::class, 'producto_id');
    }
}

