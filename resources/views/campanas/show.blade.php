{{-- 08 · Campaña generada  ·  09 · Vista previa --}}
@php
    $producto = $campana->producto;
    $red = $campana->redInfo();
    $usuario = \Illuminate\Support\Str::slug($producto->user->name, '');
@endphp
<x-layouts.app :title="'Campaña · '.$producto->nombre">
    <div data-tabs data-tabs-target="#footer-campana">
        <x-top-bar :back="route('productos.show', $producto)">
            <x-slot:center>
                <div role="tablist" class="flex rounded-xl bg-sand p-[3px] text-sm font-semibold">
                    <button type="button" role="tab" data-tab="contenido" aria-selected="true"
                            class="rounded-[10px] px-3.5 py-[7px] text-muted aria-selected:bg-white aria-selected:text-ink">Contenido</button>
                    <button type="button" role="tab" data-tab="preview" aria-selected="false"
                            class="rounded-[10px] px-3.5 py-[7px] text-muted aria-selected:bg-white aria-selected:text-ink">Vista previa</button>
                </div>
            </x-slot:center>
            <button type="button" data-open-dialog="opciones" class="btn-icon" aria-label="Más opciones"><i data-lucide="more-horizontal" class="size-5"></i></button>
        </x-top-bar>

        <x-error-ia>
            <div class="mt-1 flex gap-2">
                <button type="submit" form="form-regenerar" data-countdown="{{ session('error_ia.espera', 0) }}"
                        class="flex h-11 items-center gap-2 rounded-[14px] bg-hype px-4 text-[15px] font-semibold text-ink disabled:opacity-70">
                    <i data-lucide="refresh-cw" class="size-4"></i>Reintentar<span data-countdown-text></span>
                </button>
            </div>
        </x-error-ia>

        {{-- Contenido: las 5 piezas, cada una copiable --}}
        <section data-panel="contenido" class="flex flex-col gap-2 px-5 pt-1.5">
            <div class="eyebrow text-muted">{{ $producto->nombre }} · {{ $red['nombre'] }} · {{ $campana->tonoInfo()['nombre'] }}@if($campana->editada) · <span class="text-hype-dark">editada</span>@endif</div>

            <div class="flex flex-col gap-2 rounded-[20px] bg-ink p-4 text-paper">
                <div class="eyebrow flex justify-between text-hype">
                    <span>Eslogan</span>
                    <button type="button" data-copy="#p-eslogan" aria-label="Copiar eslogan" class="text-[#B5AEA4] hover:text-paper"><i data-lucide="copy" class="size-4"></i></button>
                </div>
                <p id="p-eslogan" class="font-display text-[26px] leading-[1.05] font-bold tracking-tight">{{ $campana->eslogan }}</p>
            </div>

            <div class="card flex flex-col gap-1.5 px-4 py-3.5">
                <div class="eyebrow flex justify-between text-muted">
                    <span>Publicación</span>
                    <div class="flex gap-3">
                        <a href="{{ route('campanas.edit', [$campana, 'pieza' => 'publicacion']) }}" aria-label="Editar publicación" class="hover:text-ink"><i data-lucide="pencil" class="size-4"></i></a>
                        <button type="button" data-copy="#p-publicacion" aria-label="Copiar publicación" class="hover:text-ink"><i data-lucide="copy" class="size-4"></i></button>
                    </div>
                </div>
                <p id="p-publicacion" class="text-[15px] leading-[1.45] whitespace-pre-line">{{ $campana->publicacion }}</p>
            </div>

            <div class="card flex flex-col gap-2 px-4 py-3.5">
                <div class="eyebrow flex justify-between text-muted">
                    <span>Hashtags · {{ count($campana->hashtags) }}</span>
                    <button type="button" data-copy-text="{{ $campana->hashtagsTexto() }}" aria-label="Copiar hashtags" class="hover:text-ink"><i data-lucide="copy" class="size-4"></i></button>
                </div>
                <div class="flex flex-wrap gap-[5px] font-mono text-[13px] font-medium text-hype-dark">
                    @foreach ($campana->hashtags as $tag)
                        <span class="rounded-lg bg-hype-soft px-2 py-1">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div class="card flex flex-col gap-1.5 px-3.5 py-3">
                    <div class="eyebrow flex justify-between text-muted">
                        <span>Llamado a la acción</span>
                        <button type="button" data-copy="#p-cta" aria-label="Copiar llamado a la acción" class="hover:text-ink"><i data-lucide="copy" class="size-3.5"></i></button>
                    </div>
                    <p id="p-cta" class="text-[15px] leading-tight font-semibold">{{ $campana->cta }}</p>
                </div>
                <div class="card flex flex-col gap-1.5 px-3.5 py-3">
                    <div class="eyebrow flex justify-between text-muted">
                        <span>Idea de imagen</span>
                        <button type="button" data-copy="#p-idea" aria-label="Copiar idea de imagen" class="hover:text-ink"><i data-lucide="copy" class="size-3.5"></i></button>
                    </div>
                    <p id="p-idea" class="text-sm leading-tight">{{ $campana->idea_imagen }}</p>
                </div>
            </div>

            @if ($urlImagen = $campana->urlImagen())
                <div class="card overflow-hidden">
                    <div class="eyebrow flex items-center justify-between px-4 py-3 text-muted">
                        <span>Imagen generada con IA</span>
                        <a href="{{ $urlImagen }}" target="_blank" rel="noopener" class="flex items-center gap-1 normal-case tracking-normal text-hype-dark hover:text-ink">
                            <i data-lucide="download" class="size-4"></i>Abrir / descargar
                        </a>
                    </div>
                    <x-imagen-ia :campana="$campana" />
                </div>
            @endif
        </section>

        {{-- Vista previa: marco genérico según la red --}}
        <section data-panel="preview" class="hidden px-5 pt-2">
            <div @class(['card overflow-hidden rounded-[22px]', 'bg-ink text-paper border-ink' => $campana->red === 'tiktok'])>
                <div class="flex items-center gap-2.5 px-3.5 py-3">
                    <div class="flex size-[34px] items-center justify-center rounded-full bg-ink text-xs font-semibold text-paper ring-1 ring-line">{{ $producto->user->iniciales() }}</div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold">{{ $campana->red === 'linkedin' ? $producto->user->name : $usuario }}</span>
                        <span @class(['text-xs', 'text-muted' => $campana->red !== 'tiktok', 'text-muted-2' => $campana->red === 'tiktok'])>{{ $campana->red === 'linkedin' ? 'Emprendedora · '.$producto->ubicacion : $producto->ubicacion }}</span>
                    </div>
                </div>

                @if (in_array($campana->red, ['facebook', 'linkedin']))
                    <p class="px-3.5 pb-3 text-sm leading-[1.45]"><b>{{ $campana->eslogan }}</b>
                        <x-texto-desplegable :texto="$campana->publicacion" :limite="160" etiqueta="ver más" :hashtags="$campana->hashtags" />
                    </p>
                @endif

                <x-imagen-ia :campana="$campana">
                    @if ($campana->red === 'tiktok')
                        <p class="absolute inset-x-3 bottom-3 rounded-lg bg-ink/75 p-2.5 text-[13px] leading-snug text-paper backdrop-blur-sm">
                            <b>{{ '@'.$usuario }}</b> <x-texto-desplegable :texto="$campana->publicacion" :limite="80" :hashtags="$campana->hashtags" />
                        </p>
                    @endif
                </x-imagen-ia>

                <div class="flex gap-3.5 px-3.5 pt-3 pb-1">
                    @if ($campana->red === 'linkedin')
                        <i data-lucide="thumbs-up" class="size-[22px]"></i><i data-lucide="message-square" class="size-[22px]"></i><i data-lucide="repeat-2" class="size-[22px]"></i>
                    @elseif ($campana->red === 'facebook')
                        <i data-lucide="thumbs-up" class="size-[22px]"></i><i data-lucide="message-circle" class="size-[22px]"></i><i data-lucide="share-2" class="size-[22px]"></i>
                    @else
                        <i data-lucide="heart" class="size-[22px]"></i><i data-lucide="message-circle" class="size-[22px]"></i><i data-lucide="send" class="size-[22px]"></i>
                    @endif
                </div>

                @if ($campana->red === 'instagram')
                    <p class="px-3.5 pt-1.5 pb-4 text-sm leading-[1.45]"><b>{{ $usuario }}</b>
                        <x-texto-desplegable :texto="$campana->eslogan.' '.$campana->publicacion" :limite="110" :hashtags="$campana->hashtags" />
                    </p>
                @else
                    <div class="pb-3"></div>
                @endif
            </div>
            <p class="mt-3 flex items-start gap-2 text-[13px] leading-snug text-muted">
                <i data-lucide="info" class="mt-px size-4 flex-none"></i>
                <span>Referencia aproximada. La imagen es una propuesta generada con IA; puedes usarla o subir tu propia foto al publicar.</span>
            </p>
        </section>

        <pre id="texto-completo" class="hidden">{{ $campana->textoCompleto() }}</pre>
    </div>

    <x-slot:footer>
        <div id="footer-campana">
            <div data-panel="contenido" class="grid grid-cols-[52px_52px_1fr] gap-2">
                <form id="form-regenerar" method="POST" action="{{ route('campanas.regenerate', $campana) }}"
                      data-generar data-red="{{ $red['nombre'] }}" data-tono="{{ $campana->tonoInfo()['nombre'] }}">
                    @csrf
                    <button type="submit" aria-label="Regenerar" title="Regenerar"
                            class="flex size-[52px] items-center justify-center rounded-2xl border-[1.5px] border-ink hover:bg-sand"><i data-lucide="refresh-cw" class="size-5"></i></button>
                </form>
                <a href="{{ route('campanas.edit', $campana) }}" aria-label="Editar texto" title="Editar texto"
                   class="flex size-[52px] items-center justify-center rounded-2xl border-[1.5px] border-ink hover:bg-sand"><i data-lucide="pencil" class="size-5"></i></a>
                <button type="button" data-copy="#texto-completo" class="btn btn-dark h-[52px] text-base">
                    <span data-copy-label>Copiar todo</span><i data-lucide="copy" class="size-[18px]"></i>
                </button>
            </div>
            <div data-panel="preview" class="hidden">
                <button type="button" data-copy="#texto-completo" class="btn btn-dark h-[52px] text-base">
                    <span data-copy-label>Copiar publicación</span><i data-lucide="copy" class="size-[18px]"></i>
                </button>
            </div>
        </div>
    </x-slot:footer>

    <x-slot:after>
        <x-generando :producto="$producto" />

        <dialog id="opciones" class="sheet" aria-label="Opciones de la campaña">
            <div class="mx-auto flex w-full max-w-[480px] flex-col gap-1 rounded-t-[32px] bg-paper px-6 pt-3 pb-10 text-ink">
                <div class="mb-3 h-[5px] w-10 self-center rounded-full bg-line-2"></div>
                <a href="{{ route('campanas.create', [$producto, 'red' => $campana->red, 'tono' => $campana->tono]) }}"
                   class="flex items-center gap-3 border-b border-line py-3.5 font-medium"><i data-lucide="sparkles" class="size-5"></i>Nueva campaña con otra red o tono</a>
                @if ($campana->editada)
                    <form method="POST" action="{{ route('campanas.restore', $campana) }}">
                        @csrf
                        <button class="flex w-full items-center gap-3 border-b border-line py-3.5 font-medium"><i data-lucide="rotate-ccw" class="size-5"></i>Volver al texto original de la IA</button>
                    </form>
                @endif
                <button type="button" data-close-dialog data-open-dialog="eliminar-campana"
                        class="flex items-center gap-3 py-3.5 font-medium text-danger"><i data-lucide="trash-2" class="size-5"></i>Eliminar campaña</button>
            </div>
        </dialog>

        <x-sheet id="eliminar-campana" title="¿Eliminar campaña?" :message="'Se quitará del historial de '.$producto->nombre.'. Esta acción no se puede deshacer.'">
            <form method="POST" action="{{ route('campanas.destroy', $campana) }}">
                @csrf @method('DELETE')
                <input type="hidden" name="volver" value="producto">
                <button class="flex h-[52px] w-full items-center rounded-2xl bg-danger px-[18px] font-semibold text-white">Eliminar</button>
            </form>
        </x-sheet>
    </x-slot:after>
</x-layouts.app>
