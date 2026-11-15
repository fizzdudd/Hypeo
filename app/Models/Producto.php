<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    public const EDADES = ['18–24', '25–34', '35–44', '45+'];

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'edad',
        'intereses',
        'ubicacion',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'integer',
            'intereses' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campanas(): HasMany
    {
        return $this->hasMany(Campana::class)->latest();
    }

    /** Precio en formato chileno: $12.990 */
    public function precioFormateado(): string
    {
        return '$'.number_format($this->precio, 0, ',', '.');
    }

    /** Resumen corto del público: "25–34 · bienestar, yoga" */
    public function resumenPublico(int $maxIntereses = 2): string
    {
        $intereses = collect($this->intereses ?? [])->take($maxIntereses)->implode(', ');

        return collect([$this->edad, $intereses])->filter()->implode(' · ');
    }
}
