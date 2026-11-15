{{-- 10 · Editar texto (por pieza, con contador según el límite de la red) --}}
@php
    $limites = ['eslogan' => 200, 'publicacion' => $campana->redInfo()['limite'], 'hashtags' => 500, 'cta' => 200, 'idea_imagen' => 300];
    $valores = [
        'eslogan' => $campana->eslogan,
        'publicacion' => $campana->publicacion,
        'hashtags' => $campana->hashtagsTexto(),
        'cta' => $campana->cta,
        'idea_imagen' => $campana->idea_imagen,
    ];
    // Si hubo error de validación, abrimos la primera pieza con error
    $conError = collect(array_keys($valores))->first(fn ($k) => $errors->has($k));
    $activa = $conError ?? $piezaActiva;
@endphp
<x-layouts.app title="Editar campaña" :pad="false">
    <form id="form-editar" method="POST" action="{{ route('campanas.update', $campana) }}" data-tabs class="flex flex-1 flex-col">
        @csrf @method('PUT')

        <div class="flex items-center justify-between px-5 pt-4 pb-3 text-base font-medium">
            <a href="{{ route('campanas.show', $campana) }}" class="text-muted hover:text-ink">Cancelar</a>
            <span class="font-semibold">Editar campaña</span>
            <button type="submit" class="font-semibold text-hype-dark hover:text-ink">Guardar</button>
        </div>

        <div role="tablist" class="flex gap-1.5 overflow-x-auto px-5 pt-1 text-[13px] font-medium [scrollbar-width:none]">
            @foreach (\App\Models\Campana::PIEZAS as $clave => $nombre)
                <button type="button" role="tab" data-tab="{{ $clave }}" aria-selected="{{ $activa === $clave ? 'true' : 'false' }}"
                        @class([
                            'rounded-[10px] border-[1.5px] px-2.5 py-1.5 whitespace-nowrap aria-selected:border-ink aria-selected:bg-ink aria-selected:text-paper',
                            'border-danger text-danger' => $errors->has($clave),
                            'border-line' => ! $errors->has($clave),
                        ])>{{ $nombre }}</button>
            @endforeach
        </div>

        @foreach (\App\Models\Campana::PIEZAS as $clave => $nombre)
            <div data-panel="{{ $clave }}" class="hidden">
                <div @class(['mx-5 mt-3.5 flex h-[260px] flex-col gap-3 rounded-[18px] border-[1.5px] bg-white p-4 focus-within:border-ink', 'border-danger' => $errors->has($clave), 'border-ink' => ! $errors->has($clave)])>
                    <label for="campo-{{ $clave }}" class="sr-only">{{ $nombre }}</label>
                    <textarea id="campo-{{ $clave }}" name="{{ $clave }}" maxlength="{{ $limites[$clave] }}"
                              data-counter="#contador-{{ $clave }}"
                              class="flex-1 resize-none bg-transparent text-base leading-normal caret-hype outline-none">{{ old($clave, $valores[$clave]) }}</textarea>
                    <div class="flex justify-between font-mono text-xs font-medium text-muted">
                        <span>{{ ($campana->texto_ia[$clave] ?? null) == $campana->{$clave} ? 'TEXTO DE LA IA' : 'EDITADO POR TI' }}</span>
                        <span id="contador-{{ $clave }}"></span>
                    </div>
                </div>
                @error($clave) <p class="error-text mx-5 mt-2">{{ $message }}</p> @enderror
                @if ($clave === 'hashtags')
                    <p class="mx-5 mt-2 text-[13px] text-muted">Sepáralos con espacios. Agregamos el # si falta.</p>
                @endif
            </div>
        @endforeach
    </form>

    @if ($campana->editada)
        <form method="POST" action="{{ route('campanas.restore', $campana) }}" class="mx-5 mt-2.5">
            @csrf
            <button class="flex items-center gap-1.5 text-sm font-medium text-hype-dark hover:text-ink">
                <i data-lucide="rotate-ccw" class="size-4"></i>Volver al texto original
            </button>
        </form>
    @endif

    <div class="mt-auto px-5 pt-6 pb-10">
        <button type="submit" form="form-editar" class="btn btn-dark"><span>Guardar cambios</span><i data-lucide="check" class="size-5"></i></button>
    </div>
</x-layouts.app>
