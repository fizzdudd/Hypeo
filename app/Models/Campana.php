<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campana extends Model
{
    use HasFactory;

    /** Redes disponibles: clave => [nombre, abreviatura, descripción, ícono lucide, límite de caracteres] */
    public const REDES = [
        'instagram' => ['nombre' => 'Instagram', 'abrev' => 'IG', 'desc' => 'Visual + hashtags', 'icono' => 'instagram', 'limite' => 2200],
        'tiktok' => ['nombre' => 'TikTok', 'abrev' => 'TT', 'desc' => 'Corto, con gancho', 'icono' => 'music-2', 'limite' => 2200],
        'facebook' => ['nombre' => 'Facebook', 'abrev' => 'FB', 'desc' => 'Comunidad local', 'icono' => 'facebook', 'limite' => 5000],
        'linkedin' => ['nombre' => 'LinkedIn', 'abrev' => 'IN', 'desc' => 'B2B, profesional', 'icono' => 'linkedin', 'limite' => 3000],
    ];

    /** Tonos disponibles: clave => [nombre, frase de ejemplo] */
    public const TONOS = [
        'cercano' => ['nombre' => 'Cercano', 'ejemplo' => '“Te la hicimos con cariño ✦”'],
        'profesional' => ['nombre' => 'Profesional', 'ejemplo' => '“Calidad artesanal certificada.”'],
        'divertido' => ['nombre' => 'Divertido', 'ejemplo' => '“POV: tu pieza huele a spa.”'],
        'elegante' => ['nombre' => 'Elegante', 'ejemplo' => '“El detalle que define un espacio.”'],
    ];

    /** Piezas editables de una campaña: campo => etiqueta */
    public const PIEZAS = [
        'eslogan' => 'Eslogan',
        'publicacion' => 'Publicación',
        'hashtags' => 'Hashtags',
        'cta' => 'CTA',
        'idea_imagen' => 'Idea de imagen',
    ];

    protected $table = 'campanas';

    protected $fillable = [
        'red',
        'tono',
        'eslogan',
        'publicacion',
        'hashtags',
        'cta',
        'idea_imagen',
        'prompt_imagen',
        'texto_ia',
        'editada',
    ];

    protected function casts(): array
    {
        return [
            'hashtags' => 'array',
            'texto_ia' => 'array',
            'editada' => 'boolean',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function redInfo(): array
    {
        return self::REDES[$this->red];
    }

    public function tonoInfo(): array
    {
        return self::TONOS[$this->tono];
    }

    public function hashtagsTexto(): string
    {
        return implode(' ', $this->hashtags ?? []);
    }

    /** Texto completo listo para pegar en la red social. */
    public function textoCompleto(): string
    {
        return trim(implode("\n\n", array_filter([
            $this->eslogan,
            $this->publicacion,
            $this->cta,
            $this->hashtagsTexto(),
        ])));
    }

    /** Aplica la respuesta de la IA y la respalda en texto_ia para poder volver a ella. */
    public function aplicarGeneracion(array $datos): void
    {
        $this->fill($datos);
        $this->texto_ia = $datos;
        $this->editada = false;
    }

    /** Tamaño de la imagen según el formato de la red: [ancho, alto] */
    public function formatoImagen(): array
    {
        return match ($this->red) {
            'tiktok' => [576, 1024],             // 9:16 vertical
            'facebook', 'linkedin' => [1024, 576], // horizontal para el feed
            default => [1024, 1024],              // Instagram cuadrado
        };
    }

    /**
     * URL de la imagen generada por IA (Pollinations). Usa el prompt en inglés de Groq,
     * salvo que el usuario haya editado la idea de imagen: entonces usa su texto.
     * La semilla depende del prompt, así la imagen cambia al regenerar o editar.
     */
    public function urlImagen(): ?string
    {
        if (! config('services.imagenes.activa')) {
            return null;
        }

        $ideaOriginal = $this->texto_ia['idea_imagen'] ?? null;
        $base = ($this->prompt_imagen && $this->idea_imagen === $ideaOriginal)
            ? $this->prompt_imagen
            : $this->idea_imagen.'. Producto: '.$this->producto->nombre;
        $prompt = $base.', professional advertising product photography, high quality, no text, no watermark';

        [$ancho, $alto] = $this->formatoImagen();

        return rtrim(config('services.imagenes.url'), '/').'/'.rawurlencode($prompt).'?'.http_build_query([
            'width' => $ancho,
            'height' => $alto,
            'seed' => crc32($this->id.'|'.$prompt) % 1000000,
            'nologo' => 'true',
        ]);
    }

    /** Fecha corta estilo mockup: HOY, AYER o 12 NOV. */
    public function fechaCorta(): string
    {
        if ($this->created_at->isToday()) {
            return 'HOY';
        }
        if ($this->created_at->isYesterday()) {
            return 'AYER';
        }

        return rtrim(mb_strtoupper($this->created_at->locale('es')->translatedFormat('d M')), '.');
    }
}
