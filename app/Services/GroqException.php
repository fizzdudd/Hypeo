<?php

namespace App\Services;

use RuntimeException;

/**
 * Error controlado al generar una campaña. El "tipo" decide qué mensaje ve el usuario.
 */
class GroqException extends RuntimeException
{
    public const OCUPADA = 'ocupada';   // 429: límite del plan gratuito
    public const FORMATO = 'formato';   // respuesta vacía o con formato inesperado
    public const CONEXION = 'conexion'; // sin conexión, timeout o error del servidor
    public const CONFIG = 'config';     // falta la API key

    public function __construct(
        public readonly string $tipo,
        string $message = '',
        public readonly int $reintentarEn = 60,
    ) {
        parent::__construct($message);
    }

    public function mensajeUsuario(): array
    {
        return match ($this->tipo) {
            self::OCUPADA => [
                'titulo' => 'La IA está ocupada',
                'mensaje' => 'Hubo muchas solicitudes. Prueba de nuevo en un minuto.',
            ],
            self::FORMATO => [
                'titulo' => 'Respuesta incompleta',
                'mensaje' => 'No pudimos armar la campaña. Inténtalo otra vez.',
            ],
            self::CONFIG => [
                'titulo' => 'Falta configurar la IA',
                'mensaje' => 'Agrega GROQ_API_KEY en el archivo .env para generar campañas.',
            ],
            default => [
                'titulo' => 'Sin conexión con la IA',
                'mensaje' => 'No logramos conectarnos con el servicio. Revisa tu conexión e inténtalo otra vez.',
            ],
        };
    }
}
