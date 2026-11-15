# Hypeo

> Describe tu producto, Hypeo escribe tu campaña.

App web en Laravel 12 que genera campañas para redes sociales con IA, usando la API de Groq. El resumen de la arquitectura está en [Documentos/Arquitectura.md](Documentos/Arquitectura.md).

## Requisitos

- PHP 8.2 o superior, Composer, Node 20 o superior
- Una API key gratuita de Groq: <https://console.groq.com/keys>

## Instalación

```bash
composer install
npm install
cp .env.example .env        # luego completa GROQ_API_KEY
php artisan key:generate
php artisan migrate --seed  # crea database/database.sqlite y los datos de demo
npm run build
php artisan serve
```

Abre <http://localhost:8000> y entra con **valentina@luzdecerro.cl** / **password**.

Para desarrollo, corre `npm run dev` en otra terminal para recargar los estilos al guardar.

### Sin PHP instalado (Docker)

```bash
docker run --rm -it -p 8000:8000 -v "$PWD:/app" -w /app composer:2 \
  sh -c "composer install && cp -n .env.example .env && php artisan key:generate && php artisan migrate --seed && php artisan serve --host=0.0.0.0"
```

## Tests

```bash
php artisan test
```

## Variables de entorno de la IA

| Variable | Valor por defecto | Uso |
|---|---|---|
| `GROQ_API_KEY` | — | Clave de la API (obligatoria para generar campañas) |
| `GROQ_MODEL` | `openai/gpt-oss-20b` | Modelo de Groq |
| `GROQ_TIMEOUT` | `30` | Segundos máximos de espera |
