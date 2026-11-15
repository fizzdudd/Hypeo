@props([
    'title' => null,
    'nav' => null,       // 'productos' | 'campanas' → muestra la barra inferior
    'dark' => false,     // fondo oscuro (login)
    'pad' => true,       // deja espacio para la barra / botón fijo inferior
])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="{{ $dark ? '#1B1916' : '#F7F5F1' }}">
    <title>{{ $title ? $title.' · ' : '' }}Hypeo</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231B1916'/%3E%3Ctext x='7' y='23' font-family='Arial' font-weight='700' font-size='20' fill='%23F7F5F1'%3Eh%3C/text%3E%3Ccircle cx='24' cy='21' r='3' fill='%23EE5A2F'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Instrument+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#E7E3DC]">
    <div @class([
        'relative mx-auto flex min-h-dvh w-full max-w-[480px] flex-col sm:shadow-[0_0_60px_-20px_rgba(40,30,20,.35)]',
        'bg-ink text-paper' => $dark,
        'bg-paper' => ! $dark,
        'pb-28' => $pad && ($nav || isset($footer)),
    ])>
        <div id="sin-conexion" class="hidden sticky top-0 z-40 flex items-center gap-2 bg-ink px-5 py-2 text-[13px] text-paper">
            <i data-lucide="wifi-off" class="size-4 text-hype"></i> Sin conexión · el historial sigue disponible.
        </div>

        @if (session('ok'))
            <div role="status" class="mx-5 mt-4 flex items-center gap-2 rounded-[14px] bg-ink px-4 py-3 text-sm font-medium text-paper">
                <i data-lucide="check" class="size-4 text-hype"></i>{{ session('ok') }}
            </div>
        @endif

        @if (session('aviso'))
            <div role="alert" class="mx-5 mt-4 flex items-center gap-2 rounded-[14px] bg-hype-soft px-4 py-3 text-sm font-medium text-hype-dark">
                <i data-lucide="refresh-cw" class="size-4"></i>{{ session('aviso') }}
            </div>
        @endif

        {{ $slot }}

        @isset($footer)
            <div class="fixed inset-x-0 bottom-0 z-20 mx-auto w-full max-w-[480px] border-t border-line bg-paper px-5 pt-3.5 pb-[max(1.5rem,env(safe-area-inset-bottom))]">
                {{ $footer }}
            </div>
        @endisset

        @if ($nav)
            <x-bottom-nav :active="$nav" />
        @endif
    </div>

    {{ $after ?? '' }}
</body>
</html>
