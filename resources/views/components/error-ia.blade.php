{{-- 13 · Error de la IA: mensaje humano + reintento (con cuenta regresiva si es 429) --}}
@if ($fallo = session('error_ia'))
    <div role="alert" class="mx-5 mt-2.5 flex flex-col gap-2.5 rounded-[20px] bg-ink p-4 text-paper">
        <div class="flex items-center gap-2 font-mono text-xs font-medium tracking-[.06em] text-hype uppercase">
            <i data-lucide="{{ $fallo['tipo'] === 'ocupada' ? 'coffee' : ($fallo['tipo'] === 'formato' ? 'file-x' : 'wifi-off') }}" class="size-4"></i>
            {{ $fallo['titulo'] }}
        </div>
        <p class="font-display text-2xl leading-[1.1] font-bold tracking-tight">{{ $fallo['mensaje'] }}</p>
        <p class="text-sm leading-snug text-[#B5AEA4]">Guardamos tu selección; no tienes que volver a escribir nada.</p>
        {{ $slot }}
    </div>
@endif
