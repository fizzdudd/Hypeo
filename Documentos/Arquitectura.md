# Hypeo — Resumen de arquitectura

**Curso:** IIP323W — Tecnologías y Aplicaciones Web y Móviles · 2026-3
**Equipo:** Fabián Salinas Donoso · Ignacio Sarmiento

Hypeo es una aplicación web **mobile-first** en Laravel. El usuario registra sus productos con su público objetivo, elige una red social y un tono, y la IA de **Groq** genera una campaña lista para publicar: eslogan, publicación, hashtags, llamado a la acción e idea de imagen.

---

## 1. Stack

| Capa | Tecnología |
|---|---|
| Lenguajes | PHP 8.2+, JavaScript (ES modules), HTML (Blade), CSS |
| Framework | Laravel 12, con arquitectura MVC |
| Vistas | Blade (layout + componentes anónimos) |
| Estilos | Tailwind CSS 4, compilado con Vite 7 |
| Íconos / fuentes | Lucide (solo los íconos usados) · Bricolage Grotesque, Instrument Sans, JetBrains Mono |
| Base de datos | SQLite + migraciones, seeders y factories de Eloquent |
| IA (texto) | API de Groq (compatible con OpenAI), modelo `openai/gpt-oss-20b` |
| IA (imagen) | Pollinations: gratuito y sin API key; el navegador carga la imagen desde una URL |
| Cliente HTTP | `Http::withToken()` de Laravel |
| Pruebas | PHPUnit (tests de feature con `Http::fake`) |
| Herramientas | Composer, npm, Artisan, Git/GitHub |

## 2. Arquitectura

```
Navegador (móvil / escritorio)
   │  HTML (Blade + Tailwind) + JS liviano (copiar, pestañas, hojas, "Generando…")
   ▼
routes/web.php ── middleware guest | auth | throttle
   ▼
Controladores ── AuthController · ProductoController · CampanaController · PerfilController
   │       │
   │       ├── Policies (ProductoPolicy, CampanaPolicy) vía Gate::authorize
   │       └── GroqService ──HTTPS──► api.groq.com/openai/v1/chat/completions
   ▼
Modelos Eloquent ── User 1─* Producto 1─* Campana   (SQLite)
```

- **Modelo:** `User` → `hasMany(Producto)` y `hasManyThrough(Campana)`. `Producto` → `belongsTo(User)` y `hasMany(Campana)`. Los intereses, los hashtags y el respaldo del texto de la IA (`texto_ia`) se guardan como JSON con *casts*.
- **Vista:** un layout `x-layouts.app` y los componentes `bottom-nav`, `top-bar`, `sheet` (hoja inferior con `<dialog>`), `campana-card`, `generando` y `error-ia`.
- **Controlador:** controladores delgados. La llamada a la IA está aislada en `App\Services\GroqService`.

## 3. Funcionalidades (13 pantallas de los mockups)

1. **Acceso:** registro, inicio y cierre de sesión con autenticación escrita a mano (`Auth::attempt`, contraseñas con hash, regeneración de la sesión). El registro muestra la fuerza de la contraseña.
2. **Productos (CRUD):** nombre, descripción, precio en CLP y público objetivo (rango de edad, intereses como etiquetas y ubicación), con búsqueda por nombre.
3. **Generar campaña con IA:** se elige una red (Instagram, TikTok, Facebook o LinkedIn) y un tono (cercano, profesional, divertido o elegante). Mientras se genera aparece una pantalla de espera con los pasos, y luego el resultado con 5 piezas que se copian por separado.
4. **Imagen con IA y vista previa:** Groq escribe un prompt de imagen en inglés y Pollinations genera la foto en el formato de la red (cuadrada, vertical u horizontal). La vista previa muestra la publicación con esa imagen y un "ver más" desplegable. Si el usuario edita la idea de imagen, la foto se genera con su texto.
5. **Historial:** las campañas de cada producto y la lista general, con filtro por red y agrupadas por fecha. Se pueden **regenerar**, **editar** cada pieza (con un contador según el límite de la red), **volver al texto original de la IA**, copiar y eliminar.
6. **Perfil:** totales de productos y campañas, y cierre de sesión con confirmación.
7. **Errores de la IA:** un 429 muestra un mensaje amable con una cuenta regresiva para reintentar. También se cubren la respuesta vacía o con formato inválido y la falta de conexión. En todos los casos se conserva la selección del usuario.

## 4. Integración con Groq

- **Configuración:** `GROQ_API_KEY` y `GROQ_MODEL` van en `.env` y se leen desde `config/services.php`. El modelo se puede cambiar sin tocar el código.
- **Prompt:** un mensaje *system* fija el rol (experto en marketing chileno) y el formato JSON exacto. El mensaje *user* lleva los datos del producto, el público, una guía de estilo para cada red y el tono. Se usa `response_format: json_object`.
- **Validación:** se revisa `failed()`, el 429 (respetando `retry-after`) y que la respuesta no venga en blanco (`blank()`). El JSON se normaliza: se limpian los hashtags y se recortan los textos al límite de la red. Todos los errores se registran en el log.
- **Cuota:** el *rate limiter* `groq` permite 10 generaciones por minuto por usuario. Las campañas quedan en la base de datos, así que no hay que volver a generarlas.

## 5. Seguridad

- Middleware `auth` en todas las rutas privadas y `guest` en las de acceso. `throttle` en el login.
- **Privacidad:** cada acción usa `Gate::authorize` con las policies, y un usuario no puede ver ni modificar productos ni campañas de otros (responde 403).
- Validación en el backend, CSRF en todos los formularios, asignación masiva limitada con `$fillable` y Blade escapa la salida.
- El `.env` con la API key no se sube a GitHub.

## 6. Calidad

Hay 12 tests de feature que cubren: rutas protegidas, registro, login fallido, normalización de productos, aislamiento entre usuarios, generación con Groq simulada, error 429, respuesta incompleta, edición y restauración, que todas las pantallas carguen, sesión vencida (419) y la URL de la imagen según la red. Para la demo, el seeder crea a la usuaria Valentina (`valentina@luzdecerro.cl` / `password`) con 3 productos y 5 campañas.

## 7. Fuera del alcance del MVP

No se publica directamente en las redes (el texto se copia y la imagen se descarga) y no hay estadísticas. La imagen de Pollinations trae una pequeña marca de agua del plan gratuito.
