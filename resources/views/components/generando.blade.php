@props(['producto'])
{{-- 07 · Estado de espera mientras Groq responde (se activa al enviar el formulario) --}}
<div id="generando" role="alertdialog" aria-live="polite" aria-label="Generando campaña"
     class="fixed inset-0 z-50 hidden justify-center bg-[#E7E3DC]">
    <div class="flex min-h-dvh w-full max-w-[480px] flex-col bg-ink text-paper">
        <div class="flex flex-1 flex-col justify-center gap-9 px-7">
            <div class="flex flex-col gap-3.5">
                <span data-gen-sel class="font-mono text-xs font-medium tracking-[.08em] text-hype"></span>
                <h2 class="font-display text-[40px] leading-[1.02] font-bold tracking-tight">Escribiendo<br>tu campaña<span class="text-hype">…</span></h2>
            </div>
            @php($pasos = [
                'Entendiendo tu producto',
                'Pensando en personas de '.$producto->edad.', '.collect($producto->intereses)->first(),
                'Eslogan y publicación',
                'Hashtags y llamado a la acción',
                'Idea para la imagen',
            ])
            <ol class="border-t border-ink-2">
                @foreach ($pasos as $paso)
                    <li data-paso data-estado="pendiente"
                        class="group flex items-center justify-between border-b border-ink-2 py-3.5 text-base text-muted
                               data-[estado=activo]:font-semibold data-[estado=activo]:text-paper data-[estado=hecho]:text-[#B5AEA4]">
                        <span>{{ $paso }}</span>
                        <i data-lucide="check" class="hidden size-[18px] text-hype group-data-[estado=hecho]:block"></i>
                        <span class="spinner hidden size-[18px] rounded-full border-2 border-hype border-r-transparent group-data-[estado=activo]:block"></span>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="flex items-center justify-between px-7 pb-12 text-sm text-muted-2">
            <span>Suele tardar unos 5 segundos</span>
            <a href="{{ url()->current() }}" class="text-[15px] font-semibold text-paper">Cancelar</a>
        </div>
    </div>
</div>
