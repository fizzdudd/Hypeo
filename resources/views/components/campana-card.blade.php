@props(['campana', 'mostrarProducto' => false, 'abreviado' => false])
@php($red = $campana->redInfo())
<a href="{{ route('campanas.show', $campana) }}"
   class="card flex flex-col gap-1.5 rounded-2xl px-3.5 py-3 transition hover:border-ink">
    <div class="eyebrow flex justify-between tracking-[.04em] text-muted">
        <span>{{ $abreviado ? $red['abrev'] : $red['nombre'] }} · {{ $campana->tonoInfo()['nombre'] }}</span>
        <span>{{ $campana->fechaCorta() }}</span>
    </div>
    <span class="font-display text-base font-semibold leading-tight">“{{ $campana->eslogan }}”</span>
    @if ($mostrarProducto)
        <span class="text-[13px] text-muted">{{ $campana->producto->nombre }}</span>
    @endif
</a>
