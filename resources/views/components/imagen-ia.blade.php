@props(['campana', 'class' => ''])
{{-- Imagen generada por IA: muestra un estado de carga y, si falla, la idea de imagen en texto --}}
@php
    $url = $campana->urlImagen();
    [$ancho, $alto] = $campana->formatoImagen();
@endphp
<div data-imagen class="placeholder-photo group relative overflow-hidden {{ $class }}" style="aspect-ratio: {{ $ancho }} / {{ $alto }}">
    @if ($url)
        <img src="{{ $url }}" alt="{{ $campana->idea_imagen }}" loading="lazy" decoding="async" referrerpolicy="no-referrer"
             class="absolute inset-0 size-full object-cover opacity-0 transition-opacity duration-500 group-data-[estado=ok]:opacity-100">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2.5 p-6 text-center group-data-[estado=ok]:hidden group-data-[estado=error]:hidden">
            <span class="spinner size-6 rounded-full border-2 border-hype border-r-transparent"></span>
            <span class="font-mono text-[11px] font-medium tracking-[.06em] text-muted">GENERANDO IMAGEN CON IA…</span>
            <span class="text-xs text-muted-2">Puede tardar unos segundos</span>
        </div>
    @endif
    <div @class([
        'absolute inset-0 flex flex-col justify-end gap-1.5 p-3.5',
        'hidden group-data-[estado=error]:flex' => $url,
    ])>
        <span class="font-mono text-[11px] font-medium text-muted">{{ $url ? 'NO SE PUDO GENERAR LA IMAGEN' : 'IMAGEN SUGERIDA' }}</span>
        <span class="text-[13px] text-ink-2">{{ $campana->idea_imagen }}</span>
    </div>
    {{ $slot }}
</div>
