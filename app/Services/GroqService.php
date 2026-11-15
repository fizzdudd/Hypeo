<?php

namespace App\Services;

use App\Models\Campana;
use App\Models\Producto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Cliente de la API de Groq (compatible con OpenAI Chat Completions).
 * Recibe un producto + red + tono y devuelve las 5 piezas de la campaña.
 */
class GroqService
{
    public function generarCampana(Producto $producto, string $red, string $tono): array
    {
        $apiKey = config('services.groq.key');

        if (blank($apiKey)) {
            throw new GroqException(GroqException::CONFIG, 'GROQ_API_KEY no está definida.');
        }

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->timeout(config('services.groq.timeout'))
                ->post(config('services.groq.url').'/chat/completions', [
                    'model' => config('services.groq.model'),
                    'temperature' => 0.8,
                    'max_completion_tokens' => 1500,
                    'reasoning_effort' => 'low', // gpt-oss: menos tokens de razonamiento → menos 429 en el plan gratuito
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $this->promptSistema()],
                        ['role' => 'user', 'content' => $this->promptUsuario($producto, $red, $tono)],
                    ],
                ]);
        } catch (ConnectionException $e) {
            Log::warning('Groq: error de conexión', ['error' => $e->getMessage()]);
            throw new GroqException(GroqException::CONEXION, $e->getMessage());
        }

        if ($response->status() === 429) {
            $espera = (int) ($response->header('retry-after') ?: 60);
            Log::warning('Groq: límite de solicitudes (429)', [
                'retry_after' => $espera,
                'restantes_tokens' => $response->header('x-ratelimit-remaining-tokens'),
                'restantes_requests' => $response->header('x-ratelimit-remaining-requests'),
                'body' => $response->body(),
            ]);
            throw new GroqException(GroqException::OCUPADA, 'Rate limit', max(5, min($espera, 120)));
        }

        if ($response->failed()) {
            Log::error('Groq: respuesta fallida', ['status' => $response->status(), 'body' => $response->body()]);
            throw new GroqException(GroqException::CONEXION, 'HTTP '.$response->status());
        }

        $contenido = $response->json('choices.0.message.content');

        if (blank($contenido)) {
            Log::error('Groq: respuesta vacía', ['body' => $response->body()]);
            throw new GroqException(GroqException::FORMATO, 'Respuesta vacía');
        }

        return $this->normalizar($contenido, $red);
    }

    private function promptSistema(): string
    {
        return <<<'TXT'
        Eres un experto en marketing para redes sociales de emprendedores chilenos.
        Escribes en español neutro de Chile, sin inventar datos que no estén en la descripción del producto.
        Responde SOLO con un objeto JSON válido con exactamente estas claves:
        {
          "eslogan": "frase corta y memorable, máximo 60 caracteres, sin comillas",
          "publicacion": "texto de la publicación adaptado a la red y al tono, sin hashtags",
          "hashtags": ["#hashtag1", "#hashtag2", "... entre 5 y 8, en minúsculas y sin tildes"],
          "cta": "llamado a la acción breve, máximo 60 caracteres",
          "idea_imagen": "descripción concreta de la foto o video ideal, máximo 120 caracteres",
          "prompt_imagen": "la misma idea de imagen como prompt detallado EN INGLÉS para un generador de imágenes: producto, escena, luz, composición y estilo fotográfico; sin texto en la imagen; máximo 300 caracteres"
        }
        TXT;
    }

    private function promptUsuario(Producto $producto, string $red, string $tono): string
    {
        $guiaRed = [
            'instagram' => 'Instagram: visual, emotivo, 60–120 palabras, puede usar 1–2 emojis.',
            'tiktok' => 'TikTok: muy corto (máx. 40 palabras), empieza con un gancho tipo "POV:" o pregunta.',
            'facebook' => 'Facebook: cercano a la comunidad local, 60–100 palabras, invita a comentar.',
            'linkedin' => 'LinkedIn: profesional, orientado a empresas o regalos corporativos, 80–130 palabras, sin emojis.',
        ][$red];

        $intereses = implode(', ', $producto->intereses ?? []);

        return <<<TXT
        Producto: {$producto->nombre}
        Descripción: {$producto->descripcion}
        Precio: {$producto->precioFormateado()} CLP
        Público objetivo: personas de {$producto->edad} años, interesadas en {$intereses}, ubicadas en {$producto->ubicacion}.
        Red social: {$guiaRed}
        Tono: {$tono}.
        TXT;
    }

    /** Valida y limpia el JSON de la IA. Lanza FORMATO si falta alguna pieza. */
    private function normalizar(string $contenido, string $red): array
    {
        // Algunos modelos envuelven el JSON en ```json ... ```
        $json = Str::of($contenido)->trim()->replaceMatches('/^```(?:json)?|```$/m', '')->trim()->toString();
        $datos = json_decode($json, true);

        if (! is_array($datos)) {
            Log::error('Groq: JSON inválido', ['contenido' => $contenido]);
            throw new GroqException(GroqException::FORMATO, 'JSON inválido');
        }

        $hashtags = $datos['hashtags'] ?? [];
        if (is_string($hashtags)) {
            $hashtags = preg_split('/[\s,]+/', $hashtags);
        }
        $hashtags = collect($hashtags)
            ->map(fn ($h) => '#'.ltrim(Str::of((string) $h)->trim()->lower()->replace(' ', '')->toString(), '#'))
            ->filter(fn ($h) => mb_strlen($h) > 1)
            ->unique()->take(10)->values()->all();

        $resultado = [
            'eslogan' => Str::limit(trim((string) ($datos['eslogan'] ?? ''), " \"“”"), 200, ''),
            'publicacion' => Str::limit(trim((string) ($datos['publicacion'] ?? '')), Campana::REDES[$red]['limite'], ''),
            'hashtags' => $hashtags,
            'cta' => Str::limit(trim((string) ($datos['cta'] ?? '')), 200, ''),
            'idea_imagen' => Str::limit(trim((string) ($datos['idea_imagen'] ?? '')), 300, ''),
        ];

        foreach ($resultado as $clave => $valor) {
            if (blank($valor)) {
                Log::error('Groq: falta una pieza', ['pieza' => $clave, 'contenido' => $contenido]);
                throw new GroqException(GroqException::FORMATO, "Falta {$clave}");
            }
        }

        // Opcional: si falta, la imagen se genera con idea_imagen
        $resultado['prompt_imagen'] = Str::limit(trim((string) ($datos['prompt_imagen'] ?? '')), 500, '') ?: null;

        return $resultado;
    }
}
