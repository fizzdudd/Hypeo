{{-- 06 · Red y tono (+ 13 · error de la IA si falló) --}}
@php
    $redSel = old('red', request('red', 'instagram'));
    $tonoSel = old('tono', request('tono', 'cercano'));
    $fallo = session('error_ia');
@endphp
<x-layouts.app title="Nueva campaña">
    <x-top-bar :back="route('productos.show', $producto)" title="Nueva campaña" />

    <x-error-ia>
        <div class="mt-1 flex gap-2">
            <button type="submit" form="form-campana" data-countdown="{{ $fallo['espera'] ?? 0 }}"
                    class="flex h-11 items-center gap-2 rounded-[14px] bg-hype px-4 text-[15px] font-semibold text-ink disabled:opacity-70">
                <i data-lucide="refresh-cw" class="size-4"></i>Reintentar<span data-countdown-text></span>
            </button>
        </div>
    </x-error-ia>

    <div class="mx-5 mt-1.5 flex items-center gap-2.5 rounded-[14px] bg-sand px-3 py-2.5">
        <div class="placeholder-photo size-9 rounded-[10px]"></div>
        <div class="flex min-w-0 flex-1 flex-col">
            <span class="truncate text-[15px] font-semibold">{{ $producto->nombre }}</span>
            <span class="truncate text-[13px] text-muted">{{ $producto->resumenPublico(1) }} · {{ $producto->ubicacion }}</span>
        </div>
        <a href="{{ route('campanas.elegir') }}?todos=1" class="text-[13px] font-semibold text-hype-dark">Cambiar</a>
    </div>

    <form id="form-campana" method="POST" action="{{ route('campanas.store', $producto) }}"
          data-generar data-resumen class="flex flex-col gap-3 px-5 pt-[22px]">
        @csrf
        <fieldset class="flex flex-col gap-3">
            <legend class="mb-3 font-display text-[22px] font-bold tracking-tight">¿Dónde vas a publicar?</legend>
            <div class="grid grid-cols-2 gap-2">
                @foreach (\App\Models\Campana::REDES as $clave => $red)
                    <label class="opt">
                        <input type="radio" name="red" value="{{ $clave }}" data-label="{{ $red['nombre'] }}" data-abrev="{{ $red['abrev'] }}" @checked($redSel === $clave)>
                        <span class="opt-red">
                            <span class="flex justify-between">
                                <i data-lucide="{{ $red['icono'] }}" class="size-[22px]"></i>
                                <i data-lucide="check-circle-2" class="check size-5"></i>
                            </span>
                            <span class="flex flex-col gap-0.5">
                                <span class="text-base font-semibold">{{ $red['nombre'] }}</span>
                                <span class="sub text-xs text-muted">{{ $red['desc'] }}</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('red') <span class="error-text">{{ $message }}</span> @enderror
        </fieldset>

        <fieldset class="mt-2 flex flex-col gap-1.5">
            <legend class="mb-3 font-display text-[22px] font-bold tracking-tight">¿Con qué tono?</legend>
            @foreach (\App\Models\Campana::TONOS as $clave => $tono)
                <label class="opt">
                    <input type="radio" name="tono" value="{{ $clave }}" data-label="{{ $tono['nombre'] }}" @checked($tonoSel === $clave)>
                    <span class="opt-tono">
                        <span class="flex flex-col">
                            <span class="text-[15px] font-semibold">{{ $tono['nombre'] }}</span>
                            <span class="ejemplo">{{ $tono['ejemplo'] }}</span>
                        </span>
                        <span class="dot"></span>
                    </span>
                </label>
            @endforeach
            @error('tono') <span class="error-text">{{ $message }}</span> @enderror
        </fieldset>
    </form>

    <x-slot:footer>
        <button type="submit" form="form-campana" class="btn btn-hype">
            <span class="flex items-center gap-2"><i data-lucide="sparkles" class="size-5"></i>Generar con IA</span>
            <span data-resumen-sel class="font-mono text-xs font-medium"></span>
        </button>
    </x-slot:footer>

    <x-slot:after>
        <x-generando :producto="$producto" />
    </x-slot:after>
</x-layouts.app>
